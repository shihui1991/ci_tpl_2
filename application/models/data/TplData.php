<?php
/**
 *  Tpl 数据模型
 * @user 罗仕辉
 * @create 2018-09-18
 */

namespace models\data;

class TplData extends DataModel
{

    public function __construct(array $columns)
    {
        $this->setColumns($columns);

        parent::__construct();
    }

    /**  获取实例
     * @param string $table
     * @param string $k
     * @return DataModel
     * @throws \Exception
     */
    static public function instance($table='', array $columns=array(), $k=0)
    {
        if(empty($k)){
            $k=get_called_class();
        }
        if(empty(static::$objs[$table][$k])){
            static::$objs[$table][$k] = new static($columns);
        }
        return static::$objs[$table][$k];
    }

    /** 销毁实例
     * @param string $table
     * @param string $k
     */
    public function _unset($table='', $k = 0)
    {
        if(empty($k)){
            $k=get_called_class();
        }
        if(isset(static::$objs[$table][$k])){
            unset(static::$objs[$table][$k]);
        }
    }

    /** 设置字段
     * @param array $columns
     */
    public function setColumns(array $columns = array())
    {
        if(!empty($columns)){
            $this->columns=$columns;
        }
    }
}