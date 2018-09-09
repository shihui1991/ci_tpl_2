<?php
/**
 *  Menu 逻辑模型
 * @user 罗仕辉
 * @create 2018-09-08
 */

namespace models\logic;

use libraries\ListIterator;
use models\data\MenuData;
use models\database\redis\MenuRedis;
use models\validator\MenuValidator;

class MenuLogic extends LogicModel
{
    public function __construct()
    {
        parent::__construct();

        $this->databaseModel = MenuRedis::instance();
        $this->dataModel = MenuData::instance();
        $this->validatorModel = MenuValidator::instance();
    }

    /**  验证 唯一
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function checkUnique(array $data)
    {
        // 验证 Url
        if(true !== $this->checkUrlUnique($data)){
            $name=$this->dataModel->fieldsName['Url'];
            throw new \Exception($name.' 已存在',EXIT_USER_INPUT);
        }
        // 验证 UrlAlias
        if(true !== $this->checkUrlAliasUnique($data)){
            $name=$this->dataModel->fieldsName['UrlAlias'];
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
            'Sort'=>ORDER_BY_ASC,
            'Id'=>ORDER_BY_ASC,
        );
        $list=$this->databaseModel->getMany($where,$select,$orderBy);
        if(empty($list)){
            return array();
        }
        $list=new ListIterator($list);
        $result=array();
        foreach($list as $row){
            $result[]=$this->dataModel->format($row);
        }

        return $result;
    }

    /** 获取全部
     * @return mixed
     */
    public function getAll()
    {
        $where=array();
        $select=array();
        $orderBy=array(
            'Sort'=>ORDER_BY_ASC,
            'Id'=>ORDER_BY_ASC,
        );
        $list=$this->databaseModel->getMany($where,$select,$orderBy);
        if(empty($list)){
            return array();
        }
        $list=new ListIterator($list);
        $result=array();
        foreach($list as $row){
            $result[]=$this->dataModel->format($row);
        }

        return $result;
    }

    /** 通过 Url 获取菜单
     * @param string $url
     * @return array
     */
    public function getRowByUrl($url)
    {
        $where=array(
            array('Url','eq',$url),
        );
        $row=$this->databaseModel->getOne($where);
        if(empty($row)){
            return array();
        }
        $result=$this->dataModel->format($row);

        return $result;
    }
}