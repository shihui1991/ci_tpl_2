<?php
/**
 *  Tpl redis 数据库模型
 * @author 罗仕辉
 * @create 2018-09-18
 */

namespace models\database\redis;

class TplRedis extends RedisModel
{
    public $dbConfigName = DB_NAME_REDIS_CONF;  // 数据库配置名
    public $db = DB_INDEX_REDIS_CONF;       // 数据库索引
    public $table = '';         // 数据表
    public $primaryKey = '';    // 主键索引

    public function __construct(array $args)
    {
        $this->dbConfigName = @(string)$args['dbConfigName'];
        $this->db = @(string)$args['db'];
        $this->table = @(string)$args['table'];
        // 处理多主键
        $strpos = strpos($args['primaryKey'],',');
        if(false !== $strpos){
            $this->primaryKey = '';
        }else{
            $this->primaryKey = @(string)$args['primaryKey'];
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