<?php
/**
 *  Config redis 数据库模型
 * @author 罗仕辉
 * @create 2018-09-15
 */

namespace models\database\redis;

class ConfigRedis extends RedisModel
{
    public $dbConfigName = DB_NAME_REDIS_CONF;  // 数据库配置名
    public $db = DB_INDEX_REDIS_CONF;       // 数据库索引
    public $table = 'Config';         // 数据表
    public $primaryKey = 'Id';    // 主键索引

}