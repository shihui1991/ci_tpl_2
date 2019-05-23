<?php


namespace libraries;


class RedisArrayModel
{
    static protected $intances;
    static public $redis;
    public $hosts = array(
        '127.0.0.1:6379',
        '127.0.0.1:6380',
    );
    public $options = array(
        'function' => 'extractKeyPart', # 声明一个包含节点列表的新数组和一个提取键的一部分的函数
        'previous' => array(), # 在添加或删除节点时定义“上一个”数组
        'retry_timeout' => 100,
        'lazy_connect' => TRUE,
        'connect_timeout' => 0.5,
        'read_timeout' => 0.5,
        'algorithm' => 'md5',
        'consistent' => FALSE,
        'auth' => '',
    );
    public $db;
    public $table;
    public $index;

    public function __construct()
    {
        if(empty(static::$redis)){
            static::$redis = new \RedisArray($this->hosts,$this->options); # 生成 redis 实例
        }
    }

    /**
     * 截取计算散列分布的部分key
     * @param $key
     * @return mixed
     */
    public function extractKeyPart($key)
    {
        return $key;
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
        return static::$redis->$name(...$arguments);
    }
}