<?php


namespace libraries;


class RedisClusterModel
{
    static protected $intances;
    static public $redis;
    public $seeds = array(
        '127.0.0.1:6379',
        '127.0.0.1:6380',
    );
    public $config = array(
        'name' => NULL,
        'timeout' => 1.5,
        'read_timeout' => 1.5,
        'persistent' => FALSE,
        'auth' => '',
    );
    public $db;
    public $table;
    public $index;


    public function __construct()
    {
        static::$redis = new \RedisCluster($this->config['name'], $this->seeds, $this->config['timeout'], $this->config['read_timeout'], $this->config['persistent'], $this->config['auth']); # 生成 redis 实例
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
        $nodeCmd = array(
            'SAVE',
            'BGSAVE',
            'FLUSHDB',
            'FLUSHALL',
            'DBSIZE',
            'BGREWRITEAOF',
            'LASTSAVE',
            'INFO',
            'CLIENT',
            'CLUSTER',
            'CONFIG',
            'PUBSUB',
            'SLOWLOG',
            'RANDOMKEY',
            'PING',
            'SCAN',
        );
        if(in_array(strtoupper($name),$nodeCmd)){
            $res = array();
            foreach(static::$redis->_masters() as $master){
                $res[] = static::$redis->$name($master,...$arguments);
            }
            return $res;
        }
        return static::$redis->$name(...$arguments);
    }
}