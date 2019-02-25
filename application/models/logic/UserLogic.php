<?php
/**
 *  User 逻辑模型
 * @author 罗仕辉
 * @create 2019-01-29
 */

namespace models\logic;

use libraries\sdk\WeChat;
use models\data\UserData;
use models\database\mysql\UserMysql;
use models\database\redis\HandleQueuesRedis;
use models\database\redis\UserRedis;
use models\validator\UserValidator;

class UserLogic extends LogicModel
{
    public function __construct()
    {
        parent::__construct();

        // redis 为主，mysql 备份
        $this->databaseModel = UserRedis::instance();

        $this->dataModel = UserData::instance();
        $this->validatorModel = UserValidator::instance();
    }

    /**  验证 唯一
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function checkUnique(array $data)
    {

        return true;
    }


    /** 处理登录或注册
     * @param $input
     * @return array|mixed
     * @throws \Exception
     */
    public function handleLoginOrRegister($input)
    {
        $data = $this->dataModel->getRealRow($input);
        if(empty($data['LoginType'])){
            throw new \Exception('操作错误',EXIT_USER_INPUT);
        }
        #
        # 判断处理进程是否存在，不存在则添加
        $redisKey = 'HandleLoginOrRegister:'.$data['Did'];
        $queues = HandleQueuesRedis::instance()->existOrAddHandleQueues($redisKey,'',2);
        if(false !== $queues){
            throw new \Exception('请勿重复操作',EXIT_USER_INPUT);
        }
        # 处理在各类型的登录/注册之前，获取$uid/$user
        switch ($data['LoginType']) {
            # 游客
            case LOGIN_TYPE_GUEST:
                $handle = $this->handleBeforeGuestLoginOrRegister($data);

                break;
            # 账号
            case LOGIN_TYPE_REGISTER:
                $handle = $this->handleBeforeAccountLoginOrRegister($data);

                break;
            # 微信
            case LOGIN_TYPE_WECHAT:
                $handle = $this->handleBeforeWeChatLoginOrRegister($data);

                break;
            # 微信小游戏
            case LOGIN_TYPE_WECHAT_GAME:
                $handle = $this->handleBeforeWeChatGameLoginOrRegister($data);

                break;
            # 其他
            default:
                throw new \Exception('非法操作',EXIT_USER_INPUT);
                break;
        }
        # 注册
        if(true == $handle['IsNew']){
            $user = $handle['User'];
            # 处理通过分享链接注册
            if(!empty($data['ShareLogId'])){
                LogShareLogic::instance()->handleShareForRegister($data['ShareLogId'],$user['Uid']);
            }
        }
        # 登录
        else{
            $user = $this->login($handle['Uid'],$data);
            # 处理通过分享链接登录
            if(!empty($data['ShareLogId'])){
                LogShareLogic::instance()->handleShareForLogin($data['ShareLogId'],$user['Uid']);
            }
        }
        # 登录统计
        $loginNum = DayUserLoginLogic::instance()->incDayUserLoginNum($user['Uid']);

        $user = $this->dataModel->format($user,$this->isAlias);
        $_SESSION['User'] = $user;

        return $user;
    }


    /** 处理游客登录或注册
     * @param $data
     * @return array
     * @throws \Exception
     */
    public function handleBeforeGuestLoginOrRegister($data)
    {
        $this->valiData($data,'GuestLoginOrRegister');
        # 获取设备绑定的游客玩家Uid
        $uid = DidGuestLogic::instance()->getDeviceBindGuestUidByDid($data['Did']);
        $isNew = false;
        $user = array();
        if(false !== $uid){
            goto result;
        }
        # 生成UID
        $uid = CreateUidLogic::instance()->createUid();
        # 游客玩家绑定设备did
        $res = DidGuestLogic::instance()->bindDeviceDidForGuest($data['Did'], $uid);
        if(false == $res){
            throw new \Exception('马上就好！请稍候……',EXIT_USER_INPUT);
        }
        # 注册
        $data['Uid'] = $uid;
        $user = $this->register($data);
        $isNew = true;

        result:

        return array(
            'Uid' => (int)$uid,
            'IsNew' => $isNew,
            'User' => $user,
        );
    }

    /** 处理账号登录或注册
     * @param $data
     * @return array
     * @throws \Exception
     */
    public function handleBeforeAccountLoginOrRegister($data)
    {
        # 验证模型 验证数据格式
        $this->valiData($data,'AccountLoginOrRegister');
        # 获取账号玩家Uid
        $uid = AccountUidLogic::instance()->getUidByAccount($data['Account']);
        $isNew = false;
        $user = array();
        if(false !== $uid){
            goto result;
        }
        # 生成UID
        $uid = CreateUidLogic::instance()->createUid();
        # 注册玩家绑定账号
        $res = AccountUidLogic::instance()->setUidByAccount($data['Account'], $uid);
        if(false == $res){
            throw new \Exception('账号已存在',EXIT_USER_INPUT);
        }
        # 设备绑定注册账号
        $res = DidAccountLogic::instance()->handleDeviceDidBindAccount($data['Did'], $data['Account']);
        if(false == $res){
            throw new \Exception('马上就好！请稍候……',EXIT_USER_INPUT);
        }
        # 注册
        $data['Uid'] = $uid;
        $user = $this->register($data);
        $isNew = true;

        result:

        return array(
            'Uid' => (int)$uid,
            'IsNew' => $isNew,
            'User' => $user,
        );
    }

    /** 处理微信登录或注册
     * @param $data
     * @return array
     * @throws \Exception
     */
    public function handleBeforeWeChatLoginOrRegister($data)
    {
        # 优先通过openid,accessToken 获取微信玩家信息
        if(!empty($data['Openid']) && !empty($data['AccessToken'])){
            $weChat = WeChat::instance()->getUserinfo($data['Openid'], $data['AccessToken'],$data['Code']);
        }
        # 第一次授权登录或AccessToken失效
        if(empty($weChat)){
            if(empty($data['Code'])){
                throw new \Exception('请先授权微信登录',EXIT_USER_INPUT);
            }
            $weChat = WeChat::instance()->getUserinfo('', '',$data['Code']);
        }
        # 合入微信玩家数据
        $weChatData = $this->dataModel->getUserDataFromWeChat($weChat);
        $data = array_merge($data,$weChatData);
        # 微信通用处理登录或注册
        $res = $this->handleWeChatCommonLoginOrRegister($data);

        return $res;
    }

    /**  微信通用处理登录或注册
     * @param $data
     * @return array
     * @throws \Exception
     */
    public function handleWeChatCommonLoginOrRegister($data)
    {
        # 获取微信玩家UID
        $uid = WeChatUidLogic::instance()->getUidByOpenid($data['Openid']);
        $isNew = false;
        $user = array();
        if(false !== $uid){
            goto result;
        }
        # 生成UID
        $uid = CreateUidLogic::instance()->createUid();
        # 绑定玩家微信
        $res = WeChatUidLogic::instance()->setUidByOpenid($data['Openid'], $uid);
        if(false == $res){
            throw new \Exception('马上就好！请稍候……',EXIT_USER_INPUT);
        }
        # 注册
        $data['Uid'] = $uid;
        $user = $this->register($data);
        $isNew = true;

        result:

        return array(
            'Uid' => (int)$uid,
            'IsNew' => $isNew,
            'User' => $user,
        );
    }

    /** 微信小游戏登录或注册
     * @param $data
     * @return array
     * @throws \Exception
     */
    public function handleBeforeWeChatGameLoginOrRegister($data)
    {
        if(empty($data['Code'])){
            throw new \Exception('请先授权微信登录',EXIT_USER_INPUT);
        }
        # 微信小游戏通过code获取openid
        $data['Openid'] = WeChat::instance()->handleCodeToOpenid($data['Code']);
        # 微信通用处理登录或注册
        $res = $this->handleWeChatCommonLoginOrRegister($data);

        return $res;
    }

    /** 注册
     * @param array $data
     * @param string $fillMethod   批量赋值方法
     * @return array
     * @throws \Exception
     */
    public function register($data, $fillMethod='')
    {
        $row = $this->dataModel->fill($data,$fillMethod);
        $res = $this->databaseModel->insert($row);
        if(false == $res){
            throw new \Exception('注册失败',EXIT_DATABASE);
        }

        return $row;
    }

    /** 登录
     * @param $uid
     * @param $data
     * @return array
     * @throws \Exception
     */
    public function login($uid, $data)
    {
        # 更新登录数据
        $update = $this->dataModel->fill($data,'Login');
        $res = $this->databaseModel->setOneByKey($uid,$update);
        if(false == $res){
            throw new \Exception('登录失败',EXIT_DATABASE);
        }
        # 获取玩家数据
        $user = $this->getUserByUid($uid);

        return $user;
    }

    /** 通过UID 获取玩家数据
     * @param $uid
     * @param array $select
     * @return array
     */
    public function getUserByUid($uid, $select = array())
    {
        $user = $this->databaseModel->getOneByKey($uid,$select);
        $user = $this->dataModel->format($user,$this->isAlias);

        return $user;
    }

    /** 验证玩家是否登录
     * @param array $input
     * @return array
     * @throws \Exception
     */
    public function checkLogin($input = array())
    {
        $data = $this->dataModel->getRealRow($input,true);
        // 验证模型 验证数据格式
        $vali=$this->validatorModel->validate($data,$this->dataModel->getColumns(),'online');
        if(true !== $vali){
            $err=array_shift($vali);
            throw new \Exception($err,EXIT_USER_INPUT);
        }
        // 获取玩家
        $user = $this->getUserByUid($data['Uid']);
        if(empty($user)){
            throw new \Exception('玩家不存在',EXIT_DATABASE);
        }
        if($data['Token'] != $user['Token']){
            throw new \Exception('登录令牌已过期，请重新登录',EXIT_USER_INPUT);
        }
        $_SESSION['user'] = $user;

        return $_SESSION['user'];
    }

    /** 获取当天注册的玩家UID列表
     * @param $date
     * @return array|bool
     */
    public function getRegUidsForDate($date)
    {
        # 今天的读取redis,之前的读取mysql
        if(date('Y-m-d') == $date){
            $model = UserRedis::instance();
        }else{
            $model = UserMysql::instance();
        }
        $where = array(
            array('Created','>=',"$date 00:00:00"),
            array('Created','<=',"$date 23:59:59"),
        );
        $select = array('Uid');
        $list = $model->getMany($where,$select);
        if(empty($list)){
            return false;
        }
        $uids = array_column($list,'Uid');

        return $uids;
    }

    /** redis 同步到 mysql
     * @return bool|int
     */
    public function handleRedisToMysql()
    {
        # 获取 reids 所有玩家
        $list = UserRedis::instance()->getMany();
        if(empty($list)){
            return false;
        }
        # 过滤、补充字段
        $users = array();
        $list = makeArrayIterator($list);
        foreach($list as $row){
            $row = $this->dataModel->fill($row);
            $row = $this->dataModel->getRealRow($row,true);
            $users[] = $row;
        }
        $inserts = $this->dataModel->getFields();
        $updates = array();
        foreach($inserts as $field){
            if(!in_array($field,array('Uid'))){
                $updates[] = $field;
            }
        }
        # 同步到 mysql
        $res = UserMysql::instance()->batchInsertUpdate($users,$inserts,$updates);

        return $res;
    }
}