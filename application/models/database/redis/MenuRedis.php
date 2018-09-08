<?php
/**
 *  Menu redis 数据库模型
 * @user 罗仕辉
 * @create 2018-09-08
 */

namespace models\database\redis;

class MenuRedis extends RedisModel
{
    public $dbConfigName = DB_NAME_REDIS_ADMIN;  // 数据库配置名
    public $db = DB_INDEX_REDIS_ADMIN;       // 数据库索引
    public $table = 'Menu';         // 数据表
    public $primaryKey = 'Id';    // 主键索引

}