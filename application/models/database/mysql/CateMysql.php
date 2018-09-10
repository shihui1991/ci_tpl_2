<?php
/**
 *  Cate mysql 数据库模型
 * @user 罗仕辉
 * @create 2018-09-11
 */

namespace models\database\mysql;

class CateMysql extends MysqlModel
{
    public $dbConfigName = DB_NAME_MYSQL_CONF;  // 数据库配置名
    public $db = DB_INDEX_MYSQL_CONF;       // 数据库索引
    public $table = 'Cate';         // 数据表
    public $primaryKey = 'Id';    // 主键索引

}