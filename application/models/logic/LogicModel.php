<?php
/**
 *  逻辑模型
 * @user 罗仕辉
 * @create 2018-09-07
 */

namespace models\logic;

use libraries\Excel;
use libraries\ListIterator;
use models\logic\component\CheckUnique;

abstract class LogicModel
{
    public $databaseModel;
    public $backDB;
    public $dataModel;
    public $validatorModel;
    static protected $objs;

    public $isFormat=true;
    public $isAlias=false;

    public $filterMethods=array(
        'eq'    => 'eq',
        'neq'   => '!=',
        'gt'    => '>',
        'egt'   => '>=',
        'lt'    => '<',
        'elt'   => '<=',
        'like'  => 'like',
        'nlike' => 'not like',
        'in'    => 'in',
        'nin'   => 'not in',
    );
    public $filterMethodsName=array(
        'eq'    => '等于',
        'neq'   => '不等于',
        'gt'    => '大于',
        'egt'   => '大于或等于',
        'lt'    => '小于',
        'elt'   => '小于或等于',
        'like'  => '类似于',
        'nlike' => '不类似于',
        'in'    => '在内',
        'nin'   => '除外',
    );

    use CheckUnique; // 组件 - 验证唯一

    public function __construct()
    {

    }

    /**  获取实例
     * @param int $k
     * @return LogicModel
     */
    static public function instance($k=0)
    {
        if(empty($k)){
            $k=get_called_class();
        }
        if(empty(static::$objs[$k])){
            static::$objs[$k] = new static();
        }
        return static::$objs[$k];
    }

    /** 销毁实例
     * @param string $k
     */
    public function _unset($k = 0)
    {
        if(empty($k)){
            $k=get_called_class();
        }
        if(isset(static::$objs[$k])){
            unset(static::$objs[$k]);
        }
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

    /** 同步
     * @param string $act
     * @return int
     * @throws \Exception
     */
    public function rsync($act='backup')
    {
        // 备份
        if('backup' == $act){
            $db1=$this->databaseModel;
            $db2=$this->backDB;
        }
        // 还原
        else{
            $db1=$this->backDB;
            $db2=$this->databaseModel;
        }

        $where=array();
        $select=$this->dataModel->fields;
        $list = $db1->getMany($where,$select);
        if(empty($list)){
            return 0;
        }
        $result = $db2->createTable($this->dataModel->getColumns(),false);
        if(false == $result){
            throw new \Exception('建表失败',EXIT_DATABASE);
        }
        $result = $db2->batchInsertUpdate($list);

        return $result;
    }

    /** 是否格式化数据
     * @param bool $isFormat
     * @return $this
     */
    public function isFormat($isFormat=true)
    {
        $this->isFormat = $isFormat;

        return $this;
    }

    /** 是否开启字段映射
     * @param bool $isAlias
     * @return $this
     */
    public function isAlias($isAlias=false)
    {
        $this->isAlias = $isAlias;

        return $this;
    }

    /** 处理筛选
     * @param array $input
     * @return array
     */
    public function handleFilter($input=array())
    {
        // 筛选条件
        $filterParams = array();
        if(!empty($input['FilterParams'])){
            $filterParams = $input['FilterParams'];
        }
        $params=$this->trunsFilterMethods($filterParams);
        // 排序方式
        $filterOrders = array();
        if(!empty($input['FilterOrders'])){
            $filterOrders = $input['FilterOrders'];
        }
        $orderBy = $this->trunsFilterOrders($filterOrders);
        $result=array(
            'Params'=>$params,
            'OrderBy'=>$orderBy,
            'FilterFields'=>$this->dataModel->fieldsName,
            'FilterMethods'=>$this->filterMethodsName,
            'FilterParams'=>$filterParams,
            'FilterOrders'=>$filterOrders,
        );

        return $result;
    }

    /** 将筛选参数转换为查询条件
     * @param array $list
     * @return array
     */
    public function trunsFilterMethods($list=array())
    {
        if(empty($list)){
            return array();
        }
        $result = array();
        foreach($list as $row){
            $field = $row['Field'];
            $method = $this->filterMethods[$row['Method']];
            $value = trim($row['Value']);

            if(in_array($method,array('in','not in'))){
                $value = explode('|',$value);
            }

            $result[]=array(
                $field,
                $method,
                $value,
            );
        }

        return $result;
    }

    /** 将排序参数转换为排序条件
     * @param array $list
     * @return array
     */
    public function trunsFilterOrders($list=array())
    {
        if(empty($list)){
            return array();
        }
        $result = array();
        foreach($list as $row){
            $result[$row['Field']]=$row['By'];
        }

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
                $where[]=array($field,$param[1],$param[2]);
            }
        }
        return $where;
    }

    /** 转换查询字段
     * @param array $select
     * @return array
     */
    public function trunsSelect(array $select=array())
    {
        if(empty($select)){
            return array();
        }
        $fields=$this->dataModel->getFields();
        $result=array();
        foreach($select as $key){
            $field=$this->dataModel->getRealField($key);
            if(false == $field){
                $field=$key;
            }
            if(in_array($field,$fields)){
                $result[]=$field;
            }
        }

        return $result;
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
    public function getTotal(array $params=array())
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
        $select=$this->trunsSelect($select);
        $orderBy=$this->trunsParamsToOrderBy($order);
        $offset = $perPage * ($page - 1);
        $list=$this->databaseModel->getMany($where, $select, $orderBy, $perPage, $offset);
        if(empty($list)){
            return array();
        }

        $result=array();
        if($this->isFormat){
            $list=new ListIterator($list);
            foreach($list as $row){
                $result[]=$this->dataModel->format($row,$this->isAlias);
            }
        }else{
            $result = $list;
        }

        return $result;
    }

    /** 获取全部
     * @param array $params
     * @param array $order
     * @param array $select
     * @param int $limit
     * @param int $offset
     * @param bool $distinct
     * @return mixed
     */
    public function getAll(array $params=array(), array $order=array(), array $select=array(),$limit=0, $offset=0, $distinct=false)
    {
        $where=$this->trunsParamsToWhere($params);
        $select=$this->trunsSelect($select);
        $orderBy=$this->trunsParamsToOrderBy($order);

        $list=$this->databaseModel->getMany($where,$select,$orderBy,$limit,$offset,$distinct);
        if(empty($list)){
            return array();
        }

        $result=array();
        if($this->isFormat){
            $list=new ListIterator($list);
            foreach($list as $row){
                $result[]=$this->dataModel->format($row,$this->isAlias);
            }
        }else{
            $result = $list;
        }

        return $result;
    }

    /** 通过 ID 获取列表
     * @param array $ids
     * @param array $select
     * @param array $orderBy
     * @return array
     */
    public function getListByIds(array $ids, $select=array(), $orderBy=array())
    {
        $select=$this->trunsSelect($select);
        $orderBy=$this->trunsParamsToOrderBy($orderBy);

        $list = $this->databaseModel->getListByKeys($ids,$select,$orderBy);
        if(empty($list)){
            return array();
        }

        $result=array();
        if($this->isFormat){
            $list=new ListIterator($list);
            foreach($list as $row){
                $result[]=$this->dataModel->format($row,$this->isAlias);
            }
        }else{
            $result = $list;
        }

        return $result;
    }

    /** 通过 ID 获取数据
     * @param int $id
     * @param array $select
     * @return mixed
     */
    public function getRowById($id, array $select=array())
    {
        $select=$this->trunsSelect($select);
        $row=$this->databaseModel->getOneByKey($id,$select);
        if(empty($row)){
            return array();
        }

        if($this->isFormat){
            $result=$this->dataModel->format($row,$this->isAlias);
        }else{
            $result = $row;
        }

        return $result;
    }

    /** 通过 ID 删除数据
     * @param array $ids
     * @return mixed
     */
    public function delByIds(array $ids)
    {
        $result = $this->databaseModel->deleteByKeys($ids);

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
        $vali=$this->validatorModel->validate($data,$this->dataModel->getColumns(),'add');
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
        if($this->isFormat){
            $result=$this->dataModel->format($row,$this->isAlias);
        }else{
            $result = $row;
        }

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
        $vali=$this->validatorModel->validate($data,$this->dataModel->getColumns(),'edit');
        if(true !== $vali){
            $err=array_shift($vali);
            throw new \Exception($err,EXIT_USER_INPUT);
        }
        // 验证字段唯一
        $this->checkUnique($data);
        $key = $this->databaseModel->getKey($data);
        $preRow=$this->databaseModel->getOneByKey($key);
        if(empty($preRow[$this->databaseModel->primaryKey])){
            throw new \Exception('数据不存在',EXIT_USER_INPUT);
        }
        // 批量赋值
        $update=$this->dataModel->fill($data,'edit');
        // 修改
        $result = $this->databaseModel->setOneByKey($key,$update);
        if(false === $result){
            throw new \Exception('保存失败',EXIT_DATABASE);
        }
        // 获取更新后数据
        $row=array_merge($preRow,$update);
        if($this->isFormat){
            $result=$this->dataModel->format($row,$this->isAlias);
        }else{
            $result = $row;
        }

        return $result;
    }

    /** 导入数据
     * @param array $list
     * @return bool|int
     */
    public function importData(array $list)
    {
        // 建表
        $result = $this->databaseModel->createTable($this->dataModel->getColumns(),true);
        if(false == $result){
            return false;
        }
        if(empty($list)){
            return 0;
        }
        // 导入数据
        $result = $this->databaseModel->batchInsertUpdate($list);

        return $result;
    }

    /** 导出Excel
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function exportConfig()
    {
        // 获取数据
        $list = $this->databaseModel->getMany();
        // 输入 Excel
        Excel::instance()->exportConfig($list,$this->dataModel->getColumns(),$this->databaseModel->table,true);
    }
}