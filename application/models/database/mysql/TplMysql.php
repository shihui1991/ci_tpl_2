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
    public $primaryKey = '';    // 主键索引


    public function __construct(array $args)
    {
        if(!empty($args['mysql'])){
            $conf = $args['mysql'];
            $this->dbConfigName = @(string)$conf['dbConfigName'];
            $this->db = @(string)$conf['db'];
            $this->table = @(string)$conf['table'];
            // 处理多主键
            $strpos = strpos($args['primaryKey'],',');
            if(false !== $strpos){
                $this->primaryKey = '';
            }else{
                $this->primaryKey = @(string)$conf['primaryKey'];
            }

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