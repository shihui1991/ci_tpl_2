<?php


namespace libraries;


class RedisSingleModel
{
    static protected $intances;
    public $config = array(
        'host' => '127.0.0.1',
        'port' => 6379,
        'timeout' => 1.5,
        'reserved' => NULL,
        'retry_interval' => 0,
        'read_timeout' => 1.5,
        'auth' => '',
        'is_persistent' => FALSE,
    );
    public $redis;
    public $db;
    public $table;
    public $index;
    public $persistentId = 0;


    public function __construct()
    {
        $this->redis = new \Redis(); # 生成 redis 实例
        $this->open(); # 连接
        $this->auth(); # 验证密码
        $this->select(); # 选择数据库
    }


    /**
     * 生成实例
     * @param string $i
     * @return mixed
     */
    static public function instance($i = '')
    {
        if(empty($i)){
            $i = md5(get_called_class());
        }
        if( ! isset(static::$intances[$i])){
            static::$intances[$i] = new static();
        }

        return static::$intances[$i];
    }

    public function __call($name, $arguments)
    {
        return $this->redis->$name(...$arguments);
    }

    /**
     * 非持久化连接
     */
    public function connect()
    {
        $this->redis->connect( $this->config['host'], $this->config['port'], $this->config['timeout'], $this->config['reserved'], $this->config['retry_interval'], $this->config['read_timeout']);
    }

    /**
     * 持久化连接
     */
    public function pconnect()
    {
        $this->redis->pconnect($this->config['host'], $this->config['port'], $this->config['timeout'], $this->persistentId, $this->config['retry_interval'], $this->config['read_timeout']);
    }

    /**
     * 连接
     */
    public function open()
    {
        $this->config['is_persistent'] ? $this->pconnect() : $this->connect();
    }

    /**
     * 验证密码
     */
    public function auth()
    {
        if( ! empty($this->config['password'])){
            $this->redis->auth($this->config['password']);
        }
    }

    /**
     * 选择数据库
     */
    public function select()
    {
        $this->redis->select($this->db);
    }
}