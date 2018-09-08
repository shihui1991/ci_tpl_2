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

        $this->DB = MenuRedis::instance();
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
        $list=$this->DB->getMany($where,$select,$orderBy);

        return $list;
    }

    /** 通过 ID 获取数据
     * @param int $id
     * @return mixed
     */
    public function getRowById($id)
    {
        $row=$this->DB->getOneByKey($id);

        return $row;
    }

    /** 表单新增
     * @param array $input
     * @return array
     * @throws \Exception
     */
    public function add(array $input)
    {
        // 批量赋值
        $data=MenuData::instance()->fill($input,'add');
        // 验证模型 验证数据格式
        $vali=MenuValidator::instance()->validate($data,MenuData::instance()->columns,'add');
        if(true !== $vali){
            $err=array_shift($vali);
            throw new \Exception($err,EXIT_USER_INPUT);
        }
        // 验证字段唯一
        $this->checkUnique($data);
        // 新增
        $id = $this->DB->insert($data);
        if(false === $id){
            throw new \Exception('保存失败',EXIT_DATABASE);
        }
        $data['Id']=$id;
        $newRow=MenuData::instance()->format($data);

        return $newRow;
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
            $name=MenuData::instance()->fieldsName['Url'];
            throw new \Exception($name.' 已存在',EXIT_USER_INPUT);
        }
        // 验证 UrlAlias
        if(true !== $this->checkUrlAliasUnique($data)){
            $name=MenuData::instance()->fieldsName['UrlAlias'];
            throw new \Exception($name.' 已存在',EXIT_USER_INPUT);
        }

        return true;
    }

    /** 表单修改
     * @param array $input
     * @return array
     * @throws \Exception
     */
    public function edit(array $input)
    {
        // 批量赋值
        $data=MenuData::instance()->fill($input,'edit');
        // 验证模型 验证数据格式
        $vali=MenuValidator::instance()->validate($data,MenuData::instance()->columns,'edit');
        if(true !== $vali){
            $err=array_shift($vali);
            throw new \Exception($err,EXIT_USER_INPUT);
        }
        // 验证字段唯一
        $this->checkUnique($data);
        $preRow=$this->DB->getOneByKey($data['Id']);
        if(empty($preRow['Id'])){
            throw new \Exception('数据不存在',EXIT_USER_INPUT);
        }
        // 修改
        $result = $this->DB->setOneByKey($data['Id'],$data);
        if(false === $result){
            throw new \Exception('保存失败',EXIT_DATABASE);
        }
        // 获取更新后数据
        $updated=array_merge($preRow,$data);
        $updated=MenuData::instance()->format($updated);

        return $updated;
    }
}