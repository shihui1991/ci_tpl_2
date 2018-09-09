<?php
/**
 *  Menu 逻辑模型
 * @user 罗仕辉
 * @create 2018-09-08
 */

namespace models\logic;

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
        );
        $list=$this->databaseModel->getMany($where,$select,$orderBy);

        return $list;
    }
}