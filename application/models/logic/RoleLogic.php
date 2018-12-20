<?php
/**
 *  Role 逻辑模型
 * @user 罗仕辉
 * @create 2018-09-09
 */

namespace models\logic;

use libraries\ListIterator;
use models\data\RoleData;
use models\database\mysql\RoleMysql;
use models\database\redis\RoleRedis;
use models\validator\RoleValidator;

class RoleLogic extends LogicModel
{
    public function __construct()
    {
        parent::__construct();

        // redis 为主，mysql 备份
//        $this->databaseModel = RoleRedis::instance();
//        $this->backDB = RoleMysql::instance();
        // mysql 为主，redis 备份
        $this->databaseModel = RoleMysql::instance();
//        $this->backDB = RoleRedis::instance();
        $this->backDBStr = 'models\database\redis\RoleRedis';

        $this->dataModel = RoleData::instance();
        $this->validatorModel = RoleValidator::instance();
    }

    /**  验证 唯一
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function checkUnique(array $data)
    {
        // 验证 Name
        if(true !== $this->checkNameUnique($data)){
            $name=$this->dataModel->fieldsName['Name'];
            throw new \Exception($name.' 已存在',EXIT_USER_INPUT);
        }

        return true;
    }


    /** 通过 ParantId 获取子菜单
     * @param int $parentId
     * @return mixed
     */
    public function getListChildByParentId($parentId)
    {
        $where=array(
            array('ParentId','eq',$parentId),
        );
        $select=array();
        $orderBy=array(
            'Id'=>ORDER_BY_ASC,
        );
        $list=$this->databaseModel->getMany($where,$select,$orderBy);
        if(empty($list)){
            return array();
        }
        $list=new ListIterator($list);
        $result=array();
        foreach($list as $row){
            if($this->isFormat){
                $result[]=$this->dataModel->format($row,$this->isAlias);
            }else{
                $result[] = $row;
            }
        }

        return $result;
    }
}