<?php
/**
 *  逻辑模型
 * @user 罗仕辉
 * @create 2018-09-07
 */

namespace models\logic;

use libraries\ListIterator;
use models\logic\component\CheckUnique;

class LogicModel
{
    public $databaseModel;
    public $backDB;
    public $dataModel;
    public $validatorModel;

    use CheckUnique; // 组件 - 验证唯一

    public function __construct()
    {

    }

    /**  获取实例
     * @return LogicModel
     */
    public static function instance()
    {
        return new static();
    }

    /** 同步
     * @return int
     */
    public function rsync()
    {
        $list = $this->databaseModel->getMany();
        if(empty($list)){
            return 0;
        }
        $result = $this->backDB->batchInsertUpdate($list);

        return $result;
    }

    /** 将请求参数转换为查询条件
     * @param array $params
     * @return array
     */
    public function trunsParamsToWhere(array $params=array())
    {
        if(empty($params)){
            return array();
        }
        $fields=$this->dataModel->getFields();
        $where=array();
        foreach($params as $param){
            $field=$this->dataModel->getRealField($param[0]);
            if(false == $field){
                $field=$param[0];
            }
            if(in_array($field,$fields)){
                $where[]=array($param[0],$param[1],$param[2]);
            }
        }
        return $where;
    }

    /** 将请求参数转换为排序条件
     * @param array $params
     * @return array
     */
    public function trunsParamsToOrderBy(array $params=array())
    {
        if(empty($params)){
            return array();
        }
        $fields=$this->dataModel->getFields();
        $orderBy=array();
        foreach($params as $key=>$by){
            $by=strtoupper($by);
            if(!in_array($by,array(ORDER_BY_ASC,ORDER_BY_DESC))){
                continue;
            }
            $field=$this->dataModel->getRealField($key);
            if(false == $field){
                $field=$key;
            }
            if(in_array($field,$fields)){
                $orderBy[$field]=$by;
            }
        }
        return $orderBy;
    }

    /** 查询条数
     * @param array $params
     * @return mixed
     */
    public function getTotoal(array $params=array())
    {
        $where=$this->trunsParamsToWhere($params);
        $result=$this->databaseModel->getCount($where);

        return $result;
    }

    /** 查询分页列表
     * @param int $page
     * @param int $perPage
     * @param array $params
     * @param array $order
     * @param array $select
     * @return array
     */
    public function getListByPage($page=1, $perPage=DEFAULT_PERPAGE, array $params=array(), array $order=array(), array $select=array())
    {
        $where=$this->trunsParamsToWhere($params);
        $orderBy=$this->trunsParamsToOrderBy($order);
        $offset = $perPage * ($page - 1);
        $list=$this->databaseModel->getMany($where, $select, $orderBy, $perPage, $offset);
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
        $list=$this->databaseModel->getMany();
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

    /** 通过 ID 获取数据
     * @param int $id
     * @return mixed
     */
    public function getRowById($id)
    {
        $row=$this->databaseModel->getOneByKey($id);
        if(empty($row)){
            return array();
        }
        $result=$this->dataModel->format($row);

        return $result;
    }


    /**  表单添加
     * @param array $input
     * @return mixed
     * @throws \Exception
     */
    public function add(array $input)
    {
        // 获取真实字段数据
        $data=$this->dataModel->getRealRow($input);
        // 验证模型 验证数据格式
        $vali=$this->validatorModel->validate($data,$this->dataModel->columns,'add');
        if(true !== $vali){
            $err=array_shift($vali);
            throw new \Exception($err,EXIT_USER_INPUT);
        }
        // 验证字段唯一
        $this->checkUnique($data);
        // 批量赋值
        $row=$this->dataModel->fill($data,'add');
        // 新增
        $id = $this->databaseModel->insert($row);
        if(false === $id){
            throw new \Exception('保存失败',EXIT_DATABASE);
        }
        $row['Id']=$id;
        $result=$this->dataModel->format($row);

        return $result;
    }

    /** 表单修改
     * @param array $input
     * @return array
     * @throws \Exception
     */
    public function edit(array $input)
    {
        // 获取真实字段数据
        $data=$this->dataModel->getRealRow($input);
        // 验证模型 验证数据格式
        $vali=$this->validatorModel->validate($data,$this->dataModel->columns,'edit');
        if(true !== $vali){
            $err=array_shift($vali);
            throw new \Exception($err,EXIT_USER_INPUT);
        }
        // 验证字段唯一
        $this->checkUnique($data);
        $preRow=$this->databaseModel->getOneByKey($data['Id']);
        if(empty($preRow['Id'])){
            throw new \Exception('数据不存在',EXIT_USER_INPUT);
        }
        // 批量赋值
        $row=$this->dataModel->fill($data,'edit');
        // 修改
        $result = $this->databaseModel->setOneByKey($data['Id'],$row);
        if(false === $result){
            throw new \Exception('保存失败',EXIT_DATABASE);
        }
        // 获取更新后数据
        $updated=array_merge($preRow,$row);
        $result=$this->dataModel->format($updated);

        return $result;
    }
}