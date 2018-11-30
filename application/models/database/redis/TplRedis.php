<?php
/**
 *  Tpl redis 数据库模型
 * @user 罗仕辉
 * @create 2018-09-18
 */

namespace models\database\redis;

class TplRedis extends RedisModel
{
    public $dbConfigName = DB_NAME_REDIS_CONF;  // 数据库配置名
    public $db = DB_INDEX_REDIS_CONF;       // 数据库索引
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
     * @return RedisModel
     * @throws \Exception
     */
    static public function instance($table='', array $args=array(), $k=0)
    {
        $obj = new static($args);
        // 数据库连接 单例
        $connectKey = $obj->makeConnectDBKey();
        if(empty(static::$connectDBs[$connectKey])){
            $obj->connect();
            static::$connectDBs[$connectKey] = $obj->dbModel;
        }else{
            $obj->dbModel = static::$connectDBs[$connectKey];
        }
        // 模型单例
        if(empty($k)){
            $k = get_called_class();
        }
        if(empty(static::$objs[$table][$k])){
            static::$objs[$table][$k] = $obj;
        }

        return static::$objs[$table][$k];
    }
}