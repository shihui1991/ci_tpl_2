<?php
/**
 *  Master 逻辑模型
 * @user 罗仕辉
 * @create 2018-09-09
 */

namespace models\logic;

use libraries\ListIterator;
use models\data\MasterData;
use models\database\mysql\MasterMysql;
use models\database\redis\MasterRedis;
use models\validator\MasterValidator;

class MasterLogic extends LogicModel
{
    public function __construct()
    {
        parent::__construct();

        // redis 为主，mysql 备份
        $this->databaseModel = MasterRedis::instance();
        $this->backDB = MasterMysql::instance();
//        $this->databaseModel = MasterMysql::instance();
//        $this->backDB = MasterRedis::instance();

        $this->dataModel = MasterData::instance();
        $this->validatorModel = MasterValidator::instance();
    }

    /**  验证 唯一
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function checkUnique(array $data)
    {
        // 验证 Account
        if(true !== $this->checkAccountUnique($data)){
            $name=$this->dataModel->fieldsName['Account'];
            throw new \Exception($name.' 已存在',EXIT_USER_INPUT);
        }

        return true;
    }

    /** 查询分页列表
     * @param int $page
     * @param int $perPage
     * @param array $params
     * @param array $order
     * @param array $select
     * @return array
     */
    public function getListWithInfoByPage($page=1, $perPage=DEFAULT_PERPAGE, array $params=array(), array $order=array(), array $select=array())
    {
        // 获取列表
        $where=$this->trunsParamsToWhere($params);
        $select=array(
            'Id',
            'Realname',
            'RoleId',
            'Account',
            'State',
        );
        $select=$this->trunsSelect($select);
        $orderBy=$this->trunsParamsToOrderBy($order);
        $offset = $perPage * ($page - 1);
        $list=$this->databaseModel->getMany($where, $select, $orderBy, $perPage, $offset);
        if(empty($list)){
            return array();
        }
        // 获取角色
        $roleIds=array_column($list,'RoleId','Id');
        $where=array(
            array('Id','in',$roleIds),
        );
        $orderBy=array();
        $select=array(
            'Id',
            'Name',
        );
        $roleList=RoleLogic::instance()->getAll($where,$orderBy,$select);
        $roleNames=array_column($roleList,'Name','Id');
        // 整理数据
        $result = array();
        foreach($list as $row){
            $row = $this->dataModel->format($row);
            $row['RoleName']=$roleNames[$row['RoleId']];

            $result[]=$row;
        }

        return $result;
    }

    /** 登录
     * @param array $input
     * @return array
     * @throws \Exception
     */
    public function login(array $input)
    {
        // 获取真实字段数据
        $data=$this->dataModel->getRealRow($input,true);
        // 验证模型 验证数据格式
        $vali=$this->validatorModel->validate($data,$this->dataModel->getColumns(),'login');
        if(true !== $vali){
            $err=array_shift($vali);
            throw new \Exception($err,EXIT_USER_INPUT);
        }
        // 获取用户
        $where=array(
            array('Account','eq',$data['Account']),
        );
        $master=$this->databaseModel->getOne($where);
        if(empty($master['Id'])){
            throw new \Exception('用户不存在',EXIT_USER_INPUT);
        }
        $vali=password_verify($data['Password'],$master['Password']);
        if(false == $vali){
            throw new \Exception('密码 错误',EXIT_USER_INPUT);
        }
        if(STATE_OFF == $master['State']){
            throw new \Exception('用户已禁用',EXIT_USER_INPUT);
        }
        // 更新登录数据
        $update=$this->dataModel->fill($data,'login');
        $result = $this->databaseModel->setOneByKey($master['Id'],$update);
        if(false === $result){
            throw new \Exception('登录失败',EXIT_DATABASE);
        }
        // 获取用户全部信息
        $master=array_merge($master,$update);
        $this->makePrivateInfo($master);

        return $_SESSION['Master'];
    }

    /** 验证是否登录
     * @param array $input
     * @return array
     * @throws \Exception
     */
    public function checkLogin(array $input)
    {
        // 获取真实字段数据
        $data=$this->dataModel->getRealRow($input);
        // 验证模型 验证数据格式
        $vali=$this->validatorModel->validate($data,$this->dataModel->getColumns(),'online');
        if(true !== $vali){
            $err=array_shift($vali);
            throw new \Exception($err,EXIT_USER_INPUT);
        }
        // 获取用户
        $master=$this->databaseModel->getOneByKey($data['Id']);
        if(empty($master['Id'])){
            throw new \Exception('用户不存在',EXIT_USER_INPUT);
        }
        if($data['Token'] != $master['Token']){
            throw new \Exception('账号已在其他设备登录',EXIT_USER_INPUT);
        }
        if(STATE_OFF == $master['State']){
            throw new \Exception('用户已禁用',EXIT_USER_INPUT);
        }
        // 获取用户全部信息
        $this->makePrivateInfo($master);

        return $_SESSION['Master'];
    }

    /** 获取管理员全部信息
     * @param array $master
     * @return array
     */
    public function getFullInfo(array $master)
    {
        // 获取角色数据
        $role=RoleLogic::instance()->isFormat(true)->getRowById($master['RoleId']);

        $other=array(
            'RoleName'=>$role['Name'],
            'IsAdmin'=>$role['Admin'],
            'MenuIds'=>$role['MenuIds'],
            'Timeout'=>time()+OPERAT_WAIT_TIME,
        );
        $result=array_merge($master,$other);

        return $result;
    }

    /** 生成管理员私有信息
     * @param array $master
     * @return array
     */
    public function makePrivateInfo(array $master)
    {
        $masterFullInfo=$this->getFullInfo($master);
        unset(
            $masterFullInfo['Password']
        );
        $_SESSION['Master']=$this->dataModel->format($masterFullInfo);
        // 字段映射
        $result=$this->dataModel->format($masterFullInfo,true);

        return $result;
    }

    /** 生成管理员公开信息
     * @param array $master
     * @return array
     */
    public function makePublicInfo(array $master)
    {
        $masterFullInfo=$this->getFullInfo($master);
        unset(
            $masterFullInfo['Account'],
            $masterFullInfo['Password'],
            $masterFullInfo['Token']
        );
        $_SESSION['Master']=$this->dataModel->format($masterFullInfo);
        // 字段映射
        $result=$this->dataModel->format($masterFullInfo,true);
        
        return $result;
    }

    /**  修改资料
     * @param array $master
     * @param array $input
     * @return mixed
     * @throws \Exception
     */
    public function modify(array $master, array $input)
    {
        // 获取真实字段数据
        $data=$this->dataModel->getRealRow($input,true);
        // 验证模型 验证数据格式
        $vali=$this->validatorModel->validate($data,$this->dataModel->getColumns(),'modify');
        if(true !== $vali){
            $err=array_shift($vali);
            throw new \Exception($err,EXIT_USER_INPUT);
        }
        // 验证唯一
        $data['Id']=$master['Id'];
        $this->checkUnique($data);
        // 修改
        $update=$this->dataModel->fill($data,'modify');
        $result = $this->databaseModel->setOneByKey($master['Id'],$update);
        if(false === $result){
            throw new \Exception('修改失败',EXIT_DATABASE);
        }
        // 获取用户全部信息
        $master=array_merge($master,$update);
        $this->makePrivateInfo($master);

        return $_SESSION['Master'];
    }

    /** 修改密码
     * @param array $master
     * @param array $input
     * @throws \Exception
     */
    public function editPasswd(array $master, array $input)
    {
        // 获取真实字段数据
        $data=$this->dataModel->getRealRow($input);
        // 验证模型 验证数据格式
        $vali=$this->validatorModel->validate($data,$this->dataModel->getColumns(),'editPasswd');
        if(true !== $vali){
            $err=array_shift($vali);
            throw new \Exception($err,EXIT_USER_INPUT);
        }
        // 验证重复密码
        if($input['PasswordConfim'] != $input['Password'])
        {
            throw new \Exception('重复密码错误',EXIT_USER_INPUT);
        }
        $passwd=$this->databaseModel->getOneByKey($master['Id'],array('Password'));
        // 验证密码
        $vali=password_verify($input['OldPassword'],$passwd['Password']);
        if(false == $vali){
            throw new \Exception('旧密码错误',EXIT_USER_INPUT);
        }
        $update=$this->dataModel->fill($data,'editPasswd');
        $result = $this->databaseModel->setOneByKey($master['Id'],$update);
        if(false === $result){
            throw new \Exception('修改失败',EXIT_DATABASE);
        }
    }
}