<?php
/**
 *  Cate redis 数据库模型
 * @author 罗仕辉
 * @create 2018-09-11
 */

namespace models\database\redis;

class CateRedis extends RedisModel
{
    public $dbConfigName = DB_NAME_REDIS_CONF;  // 数据库配置名
    public $db = DB_INDEX_REDIS_CONF;       // 数据库索引
    public $table = 'Cate';         // 数据表
    public $primaryKey = 'Id';    // 主键索引

}