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
        # 各类型的注册登录处理
        $methods = array(
            LOGIN_TYPE_GUEST         => 'handleLoginOrRegisterForGuest',
            LOGIN_TYPE_ACCOUNT_REG   => 'handleRegisterForAccount',
            LOGIN_TYPE_ACCOUNT_LOGIN => 'handleLoginForAccount',
            LOGIN_TYPE_WECHAT        => 'handleLoginOrRegisterForWeChat',
            LOGIN_TYPE_WECHAT_GAME   => 'handleLoginOrRegisterForWeChatGame',
        );
        if(!isset($methods[$data['LoginType']])){
            throw new \Exception('非法操作',EXIT_USER_INPUT);
        }
        $method = $methods[$data['LoginType']];
        $user = $this->$method($data);
        # 处理通过分享链接注册登录
        if(!empty($data['ShareLogId'])){
            if(true == $user['IsNew']){
                LogShareLogic::instance()->handleShareForRegister($data['ShareLogId'],$user['Uid']);
            }else{
                LogShareLogic::instance()->handleShareForLogin($data['ShareLogId'],$user['Uid']);
            }
        }
        $loginNum = DayUserLoginLogic::instance()->incDayUserLoginNum($user['Uid']);
        $user = $this->dataModel->format($user);
        $_SESSION['User'] = $user;
        # 生成登录需返回玩家数据
        $return = $this->makeLoginUserData($user);

        return $return;
    }

    /** 生成登录需返回玩家数据
     * @param $user
     * @return array
     */
    public function makeLoginUserData($user)
    {
        $fields = $this->dataModel->getFieldsForLogin();
        $data = array();
        foreach($fields as $field){
            $data[$field] = isset($user[$field]) ? $user[$field] : '';
        }
        $data = $this->dataModel->format($data,$this->isAlias);

        return $data;
    }

    /** 处理游客注册登录
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function handleLoginOrRegisterForGuest($data)
    {
        $this->valiData($data,'handleLoginOrRegisterForGuest');
        # 获取设备绑定的游客玩家Uid
        $uid = DidGuestLogic::instance()->getDeviceBindGuestUidByDid($data['Did']);
        if(false !== $uid){
            # 登录
            $user = $this->login($uid,$data,'handleLoginForGuest');
            $user['IsNew'] = false;
            return $user;
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
        $user['IsNew'] = true;
        return $user;
    }

    /** 账号注册
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function handleRegisterForAccount($data)
    {
        # 验证模型 验证数据格式
        $this->valiData($data,'handleLoginOrRegisterForAccount');
        # 获取账号玩家Uid
        $uid = AccountUidLogic::instance()->getUidByAccount($data['Account']);
        if($uid){
            throw new \Exception('账号已被注册',EXIT_USER_INPUT);
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
        $user['IsNew'] = true;
        return $user;
    }

    /** 账号登录
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function handleLoginForAccount($data)
    {
        # 验证模型 验证数据格式
        $this->valiData($data,'handleLoginOrRegisterForAccount');
        # 获取账号玩家Uid
        $uid = AccountUidLogic::instance()->getUidByAccount($data['Account']);
        if(false == $uid){
            throw new \Exception('账号不存在',EXIT_USER_INPUT);
        }
        # 验证密码
        $select = $this->dataModel->getFieldsForAccountLogin();
        $user = $this->getUserByUid($uid,$select);
        $vali = password_verify($data['Password'],$user['Password']);
        if(false == $vali){
            throw new \Exception('密码错误',EXIT_USER_INPUT);
        }
        # 登录
        $user = $this->login($uid,$data,'handleLoginForAccount');
        $user['IsNew'] = false;
        return $user;
    }

    /** 微信APK 注册登录
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    public function handleLoginOrRegisterForWeChat($data)
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
        # 获取微信玩家UID
        $uid = WeChatUidLogic::instance()->getUidByOpenid($data['Openid']);
        if(false !== $uid){
            # 登录
            $user = $this->login($uid,$data,'handleLoginForWeChat');
            $user['IsNew'] = false;
            return $user;
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
        $user['IsNew'] = true;
        return $user;
    }

    /** 微信小游戏注册登录
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function handleLoginOrRegisterForWeChatGame($data)
    {
        if(empty($data['Code'])){
            throw new \Exception('请先授权微信登录',EXIT_USER_INPUT);
        }
        # 微信小游戏通过code获取openid
        $data['Openid'] = WeChat::instance()->handleCodeToOpenid($data['Code']);
        # 获取微信小游戏玩家UID
        $uid = WeChatGameUidLogic::instance()->getUidByOpenid($data['Openid']);
        if(false !== $uid){
            # 登录
            $user = $this->login($uid,$data,'handleLoginForWeChatGame');
            $user['IsNew'] = false;
            return $user;
        }
        # 生成UID
        $uid = CreateUidLogic::instance()->createUid();
        # 绑定玩家微信小游戏
        $res = WeChatGameUidLogic::instance()->setUidByOpenid($data['Openid'], $uid);
        if(false == $res){
            throw new \Exception('马上就好！请稍候……',EXIT_USER_INPUT);
        }
        # 注册
        $data['Uid'] = $uid;
        $user = $this->register($data);
        $user['IsNew'] = true;
        return $user;
    }

    /** 注册
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function register($data)
    {
        $row = $this->dataModel->fill($data);
        $res = $this->databaseModel->insert($row);
        if(false == $res){
            throw new \Exception('注册失败',EXIT_DATABASE);
        }
        $user = $this->dataModel->format($row);

        return $user;
    }

    /** 登录
     * @param int $uid
     * @param array $data
     * @param string $method
     * @return array
     * @throws \Exception
     */
    public function login($uid, $data, $method)
    {
        $update = $this->dataModel->fill($data,$method);
        $res = $this->databaseModel->setOneByKey($uid,$update);
        if(false == $res){
            throw new \Exception('登录失败',EXIT_DATABASE);
        }
        # 获取玩家数据
        $user = $this->getUserByUid($uid,array(),false);

        return $user;
    }

    /** 通过UID 获取玩家数据
     * @param $uid
     * @param array $select
     * @param bool $isAlias
     * @return array
     */
    public function getUserByUid($uid, $select = array(),$isAlias = null)
    {
        $user = $this->databaseModel->getOneByKey($uid,$select);
        if(is_null($isAlias)){
            $isAlias = $this->isAlias;
        }
        $user = $this->dataModel->format($user,$isAlias);

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
            array('Created','>=',strtotime("$date 00:00:00")),
            array('Created','<=',strtotime("$date 23:59:59")),
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
        foreach(makeArrayIterator($list) as $row){
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

    /** 增加玩家物品
     * @param $uid
     * @param $field
     * @param $num
     * @return mixed
     */
    public function incField($uid, $field, $num)
    {
        $symbol = $num > 0 ? '+' : '-';
        $res = $this->databaseModel->incFieldByKey($uid, $field, abs($num), $symbol, 0, INF);

        return $res;
    }

    /** 发放物品
     * @param array $input
     * @return mixed
     * @throws \Exception
     */
    public function incItem($input = array())
    {
        if(empty($input['Uid']) || empty($input['Field']) || empty($input['Num'])){
            throw new \Exception('参数错误',EXIT_USER_INPUT);
        }
        $res = $this->incField($input['Uid'],$input['Field'],$input['Num']);

        return $res;
    }
}