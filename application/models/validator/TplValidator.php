<?php
/**
 *  Tpl 验证模型
 * @user 罗仕辉
 * @create 2018-09-18
 */

namespace models\validator;

class TplValidator extends ValidatorModel
{

    /**  获取实例
     * @param string $table
     * @param string $k
     * @return ValidatorModel
     * @throws \Exception
     */
    static public function instance($table='', $k=0)
    {
        if(empty($k)){
            $k=get_called_class();
        }
        if(empty(static::$objs[$table][$k])){
            static::$objs[$table][$k] = new static();
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
}