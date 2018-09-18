<?php
/**
 *  Tpl mysql 数据库模型
 * @user 罗仕辉
 * @create 2018-09-18
 */

namespace models\database\mysql;

class TplMysql extends MysqlModel
{
    public $dbConfigName = DB_NAME_MYSQL_CONF;  // 数据库配置名
    public $db = DB_INDEX_MYSQL_CONF;       // 数据库索引
    public $table = '';         // 数据表
    public $primaryKey = 'Id';    // 主键索引


    public function __construct(array $args)
    {
        if(isset($args['dbConfigFile'])){
            $this->dbConfigFile=$args['dbConfigFile'];
        }
        if(isset($args['dbConfigName'])){
            $this->dbConfigName=$args['dbConfigName'];
        }
        if(isset($args['db'])){
            $this->db=$args['db'];
        }
        if(isset($args['table'])){
            $this->table=$args['table'];
        }
        if(isset($args['primaryKey'])){
            $this->primaryKey=$args['primaryKey'];
        }

        parent::__construct();
    }

    /**  获取实例
     * @param string $table
     * @param array $args
     * @param string $k
     * @return MysqlModel
     * @throws \Exception
     */
    static public function instance($table, array $args, $k=0)
    {
        if(empty($k)){
            $k=get_called_class();
        }
        if(empty(static::$objs[$table][$k])){
            static::$objs[$table][$k] = new static($args);
        }
        return static::$objs[$table][$k];
    }

    /** 销毁实例
     * @param string $table
     * @param string $k
     */
    public function _unset($table, $k = 0)
    {
        if(empty($k)){
            $k=get_called_class();
        }
        if(isset(static::$objs[$table][$k])){
            unset(static::$objs[$table][$k]);
        }
    }
}