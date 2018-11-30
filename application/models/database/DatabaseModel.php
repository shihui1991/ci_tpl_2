<?php
/**
 *  数据库模型
 * @user 罗仕辉
 * @create 2018-09-07
 */

namespace models\database;

abstract class DatabaseModel
{
    protected $CI;
    public $dbConfigFile;  // 数据库配置文件
    public $dbConfigName;  // 数据库配置名
    public $dbConfig;      // 数据库配置
    public $dbModel;       // 数据库实例
    public $db;            // 数据库
    public $table;         // 数据表
    public $primaryKey;    // 主键

    static protected $objs;
    static protected $connectDBs;

    public function __construct()
    {
        $this->CI = & get_instance();
        // 加载配置
        $this->CI->load->config($this->dbConfigFile,TRUE);
        $configs=$this->CI->config->item($this->dbConfigFile);
        $this->dbConfig=$configs[$this->dbConfigName];
    }

    /**  获取实例
     * @param string $k
     * @return DatabaseModel
     */
    public static function instance($k=0)
    {
        $obj = new static();
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
        if(empty(static::$objs[$k])){
            static::$objs[$k] = $obj;
        }

        return static::$objs[$k];
    }

    /** 生成连接数据库单例键
     * @return string
     */
    protected function makeConnectDBKey()
    {
        $key = md5($this->dbConfig['hostname'].':'.$this->dbConfig['port'].':'.$this->db);

        return $key;
    }

    /** 连接数据库
     * @return mixed
     */
    abstract public function connect();

    /** 执行一条原生命令
     * @param string $cmd
     * @param array $arguments
     * @return mixed
     */
    abstract public function query($cmd, $arguments=array());

    /** 建表
     * @param array $columns
     * @param bool $drop
     * @return mixed
     */
    abstract public function createTable(array $columns, $drop=false);

    /** 删除表
     * @return mixed
     */
    abstract public function dropTable();

    /** 重置 ID
     * @param int $start
     * @return bool
     */
    abstract public function resetId($start=0);

    /**  处理查询条件
     * @param array $wheres  查询条件
     * @return bool|array
     */
    abstract public function dealWhere(array $wheres);

    /**  处理排序
     * @param array $orderBy
     */
    abstract public function dealOrderBy(array $orderBy);

    /** 过滤字段
     * @param array $list
     * @return array
     */
    abstract public function dealSelect(array $list);

    /**  获取列表
     * @param array $where
     * @param array $select
     * @param array $orderBy
     * @param int $limit
     * @param int $offset
     * @param bool $distinct
     * @return array
     */
    abstract public function getMany($where=array(), $select=array(), $orderBy=array(), $limit=0, $offset=0,$distinct=false);

    /**  获取数据
     * @param array $where
     * @param array $select
     * @param array $orderBy
     * @param int $offset
     * @return array
     */
    abstract public function getOne($where=array(), $select=array(), $orderBy=array(), $offset=0);

    /**  计数
     * @param mixed $where
     * @return int
     */
    abstract public function getCount($where=array());

    /**  通过主键索引获取列表
     * @param array $keys
     * @param array $select
     * @param array $orderBy
     * @return array
     */
    abstract public function getListByKeys(array $keys, $select=array(), $orderBy=array());

    /**  通过主键索引获取数据
     * @param mixed $key
     * @param array $select
     * @return array
     */
    abstract public function getOneByKey($key, $select=array());

    /**  通过主键索引更新数据
     * @param mixed $key
     * @param array $data
     * @return bool
     */
    abstract public function setOneByKey($key, array $data);

    /**  通过主键索引对多个字段值增减
     * @param mixed $key
     * @param array $list 字段值，field=>array(num,symbol,min,max)
     * @return mixed 成功返回最新值，失败返回false
     */
    abstract public function incListByKey($key, array $list);

    /**  通过主键索引对字段值增减
     * @param string $key
     * @param string $field
     * @param number $num
     * @param string $symbol
     * @param number $min
     * @param number $max
     * @return mixed  成功返回最新值，失败返回false
     */
    abstract public function incFieldByKey($key, $field, $num=1, $symbol='+', $min=0, $max=INF);

    /**  通过主键索引删除数据
     * @param array $keys
     * @return mixed 成功返回条数，失败返回false
     */
    abstract public function deleteByKeys(array $keys);

    /**  获取主键值
     * @param array $data
     * @return mixed
     */
    abstract public function getKey(array $data);

    /**  插入数据
     * @param array $data
     * @return mixed 成功返回 ID，失败返回 false
     */
    abstract public function insert(array $data);

    /**  批量插入或更新
     * @param array $list
     * @param array $fields
     * @return int 返回条数
     */
    abstract public function batchInsertUpdate(array $list, array $fields=array());

    /**  更新数据
     * @param array $data
     * @param array $where
     * @return bool 是否成功
     */
    abstract public function update(array $data, $where=array());

    /**  批量更新
     * @param array $list
     * @param array $whereFields
     * @param array $updateFields
     * @return int  返回更新条数
     */
    abstract public function batchUpdate(array $list, array $whereFields, array $updateFields);

    /**  删除数据
     * @param array $where
     * @return int 返回条数
     */
    abstract public function delete($where=array());

    /**  清空数据
     * @return mixed
     */
    abstract public function truncate();
}