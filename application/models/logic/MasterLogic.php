<?php
/**
 *  Master 逻辑模型
 * @user 罗仕辉
 * @create 2018-09-09
 */

namespace models\logic;

use libraries\ListIterator;
use models\data\MasterData;
use models\database\redis\MasterRedis;
use models\validator\MasterValidator;

class MasterLogic extends LogicModel
{
    public function __construct()
    {
        parent::__construct();

        $this->databaseModel = MasterRedis::instance();
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

    /** 登录
     * @param array $input
     * @return array
     * @throws \Exception
     */
    public function login(array $input)
    {
        // 获取真实字段数据
        $data=$this->dataModel->getRealRow($input);
        // 验证模型 验证数据格式
        $vali=$this->validatorModel->validate($data,$this->dataModel->columns,'login');
        if(true !== $vali){
            $err=array_shift($vali);
            throw new \Exception($err,EXIT_USER_INPUT);
        }
        // 获取用户
        $where=array(
            array('Account','eq',$data['Account']),
        );
        $select=array(
            'Id',
            'Realname',
            'RoleId',
            'Account',
            'Password',
            'State',
        );
        $master=$this->databaseModel->getOne($where,$select);
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
        unset($master['Password'],$master['State']);
        // 获取用户全部信息
        $master=array_merge($master,$update);
        $masterFullInfo=$this->getMasterFullInfo($master);
        $masterFullInfo['Timeout']=time()+OPERAT_WAIT_TIME;

        $_SESSION['Master']=$masterFullInfo;

        return $masterFullInfo;
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
        $vali=$this->validatorModel->validate($data,$this->dataModel->columns,'online');
        if(true !== $vali){
            $err=array_shift($vali);
            throw new \Exception($err,EXIT_USER_INPUT);
        }
        // 获取用户
        $select=array(
            'Id',
            'Realname',
            'RoleId',
            'Account',
            'Token',
            'State',
        );
        $master=$this->databaseModel->getOneByKey($data['Id'],$select);
        if(empty($master['Id'])){
            throw new \Exception('用户不存在',EXIT_USER_INPUT);
        }
        if(STATE_OFF == $master['State']){
            throw new \Exception('用户已禁用',EXIT_USER_INPUT);
        }
        if($data['Token'] != $master['Token']){
            throw new \Exception('登录令牌已过期',EXIT_USER_INPUT);
        }
        unset($master['Token'],$master['State']);
        // 获取用户全部信息
        $online=$this->dataModel->fill($data,'online');
        $master=array_merge($master,$online);
        $masterFullInfo=$this->getMasterFullInfo($master);
        $masterFullInfo['Timeout']=time()+OPERAT_WAIT_TIME;

        $_SESSION['Master']=$masterFullInfo;

        return $masterFullInfo;
    }

    /** 获取管理员全部信息
     * @param array $master
     * @return array
     */
    public function getMasterFullInfo(array $master)
    {
        // 获取角色数据
        $role=RoleLogic::instance()->getRowById($master['RoleId']);
        // 字段映射
        $master=$this->dataModel->format($master,true);

        $other=array(
            'RoleName'=>$role['Name'],
            'IsAdmin'=>$role['Admin'],
            'MenuIds'=>$role['MenuIds'],
        );
        $result=array_merge($master,$other);

        return $result;
    }
}