<?php
/**
 *  Menu 逻辑模型
 * @author 罗仕辉
 * @create 2018-09-08
 */

namespace models\logic;


use models\data\MenuData;
use models\database\mysql\MenuMysql;
use models\database\redis\MenuRedis;
use models\validator\MenuValidator;

class MenuLogic extends LogicModel
{
    public function __construct()
    {
        parent::__construct();

        // redis 为主，mysql 备份
//        $this->databaseModel = MenuRedis::instance();
//        $this->backDB = MenuMysql::instance();
        // mysql 为主，redis 备份
        $this->databaseModel = MenuMysql::instance();
//        $this->backDB = MenuRedis::instance();
        $this->backDBStr = 'models\database\redis\MenuRedis';

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
        $orderBy=array(
            'Sort'=>ORDER_BY_ASC,
            'Id'=>ORDER_BY_ASC,
        );
        $list=$this->getAll($where,$orderBy);

        return $list;
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
        if($this->isFormat){
            $row = $this->dataModel->format($row,$this->isAlias);
        }

        return $row;
    }

    /** 获取导航菜单
     * @param int $parentId
     * @param array $ids
     * @param bool $ctrl  是否限制
     * @return array
     */
    public function getNavList($parentId=0, $ids=array(), $ctrl=false)
    {
        $where=array(
            array('Display','eq',YES),
            array('State','eq',STATE_ON),
            array('ParentId','eq',$parentId),
        );
        $select=array(
            'Id',
            'ParentId',
            'Url',
            'Name',
            'Icon',
        );
        $orderBy=array(
            'Sort'=>ORDER_BY_ASC,
            'Id'=>ORDER_BY_ASC,
        );

        if($ctrl){
            if(empty($ids)){
                $where[]=array('Ctrl','eq',NO);
                $list=$this->databaseModel->getMany($where,$select,$orderBy);
            }else{
                $where1=$where2=$where;
                $where1[]=array('Id','in',$ids);
                $list1=$this->databaseModel->getMany($where1,$select,$orderBy);

                $where2[]=array('Ctrl','eq',NO);
                $list2=$this->databaseModel->getMany($where2,$select,$orderBy);

                $list=array_values(array_unique(array_merge($list1,$list2)));
            }
        }else{
            $list=$this->databaseModel->getMany($where,$select,$orderBy);
        }

        if(empty($list)){
            return array();
        }
        if($this->isFormat){
            foreach(makeArrayIterator($list) as $i => $row){
                $list[$i]=$this->dataModel->format($row,$this->isAlias);
            }
        }

        return $list;
    }
}