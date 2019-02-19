<?php
/**
 *  Source mysql 数据库模型
 * @author 罗仕辉
 * @create 2018-09-12
 */

namespace models\database\mysql;

class SourceMysql extends MysqlModel
{
    public $dbConfigName = DB_NAME_MYSQL_CONF;  // 数据库配置名
    public $db = DB_INDEX_MYSQL_CONF;       // 数据库索引
    public $table = 'Source';         // 数据表
    public $primaryKey = 'Id';    // 主键索引

}