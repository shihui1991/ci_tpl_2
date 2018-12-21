<?php
/**
 *  数据模型
 * @user 罗仕辉
 * @create 2018-09-08
 */

namespace models\data;

use models\data\component\GetAttr;
use models\data\component\GetField;
use models\data\component\SetAttr;
use models\data\component\SetField;

abstract class DataModel
{
    public $columns=array();       // 字段详情 field => [field,name,alias,attr,desc,rules]
    public $fields=array();        // 字段
    public $fieldsName=array();    // 字段名称
    public $fieldsAttr=array();    // 字段属性
    public $fieldsAlias=array();   // 字段映射

    static protected $objs;

    use GetAttr;  // 组件 - 按字段属性格式化字段
    use GetField; // 组件 - 按字段格式化字段

    use SetAttr;  // 组件 - 按字段属性修改字段
    use SetField; // 组件 - 按字段修改字段


    /**
     *  初始化，整理字段
     */
    public function __construct()
    {
        // 字段详情
        foreach($this->columns as $field=>$column) {
            $this->fields[] = $field;
            $this->fieldsName[$field]  = $column['name'];
            $this->fieldsAttr[$field]  = $column['attr'];
            $this->fieldsAlias[$field] = isset($column['alias']) ? $column['alias'] : $field;
        }
    }

    /** 获取实例
     * @param string $k
     * @param array $columns
     * @return DataModel
     */
    public static function instance($k = 0)
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

    /** 获取字段详情列表
     * @return mixed
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /** 获取字段列表
     * @return mixed
     */
    public function getFields()
    {
        return $this->fields;
    }

    /** 获取真实字段
     * @param string $alias
     * @return false|int|string
     */
    public function getRealField($alias){
        $field=array_search($alias,$this->fieldsAlias);

        return $field;
    }

    /** 获取映射字段
     * @param string $field
     * @return mixed
     */
    public function getAliasField($field)
    {
        $alias=$this->fieldsAlias[$field];

        return $alias;
    }

    /**  获取真实数据
     * @param array $input
     * @param bool $filter  是否过滤无关字段
     * @return array
     */
    public function getRealRow(array $input,$filter=false)
    {
        if(empty($input)){
            return array();
        }
        $result=array();
        foreach($input as $alias=>$val){
            $field=$this->getRealField($alias);
            // 真实字段不存在，则返回原数据
            if(false == $field){
                $field=$alias;
            }
            // 是否过滤无关字段
            if($filter && !in_array($field,$this->fields)){
                continue;
            }
            $result[$field]=$val;
        }

        return $result;
    }

    /**  生成映射数据
     * @param array $data
     * @return array
     */
    public function setAliasRow(array $data)
    {
        if(empty($data)){
            return array();
        }
        $result=array();
        foreach($data as $field=>$val){
            $alias=$this->getAliasField($field);
            $result[$alias]=$val;
        }

        return $result;
    }

    /**  批量赋值
     * @param array $data
     * @param string $method
     * @param bool $isReal   请求数据是否为真实数据
     * @return array
     */
    public function fill(array $data, $method='',$isReal=true)
    {
        // 获取批量赋值字段
        if(empty($method)){
            $fields=$this->fields;
        }
        else{
            $method='fill'.ucfirst($method).'Fields';
            if(method_exists($this,$method)){
                $fields=$this->$method();
            }else{
                $fields=$this->fields;
            }
        }
        if(empty($fields)){
            return array();
        }
        // 获取真实数据
        if(false == $isReal){
            $data=$this->getRealRow($data);
        }
        // 批量赋值
        $result=array();
        foreach($fields as $field){
            $value=isset($data[$field])?$data[$field]:'';
            // 字段特殊赋值
            $setField='set'.ucfirst($field).'Field';
            if(method_exists($this,$setField)){
                $value=$this->$setField($value,$data);
            }
            // 属性赋值
            $setAttr='set'.ucfirst($this->fieldsAttr[$field]).'Attr';
            if(method_exists($this,$setAttr)){
                $value=$this->$setAttr($value);
            }

            $result[$field]=$value;
        }

        return $result;
    }

    /**  批量格式化
     * @param array $data
     * @param bool $makeAlias
     * @return array
     */
    public function format(array $data, $makeAlias=false)
    {
        if(empty($data)){
            return array();
        }
        $result=array();
        foreach($data as $field=>$value){
            if(isset($this->fieldsAttr[$field])){
                $getAttr='get'.ucfirst($this->fieldsAttr[$field]).'Attr';
                if(method_exists($this,$getAttr)){
                    $value=$this->$getAttr($value);
                }

                $getField='get'.ucfirst($field).'Field';
                if(method_exists($this,$getField)){
                    $value=$this->$getField($value,$data);
                }
                if($makeAlias){
                    $field=$this->getAliasField($field);
                }
            }

            $result[$field]=$value;
        }

        return $result;
    }
}