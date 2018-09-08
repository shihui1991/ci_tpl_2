<?php
/**
 *  数据库模型
 * @user 罗仕辉
 * @create 2018-09-07
 */

namespace models\database;

class DatabaseModel
{
    protected $CI;
    public $dbConfigFile;  // 数据库配置文件
    public $dbConfigName;  // 数据库配置名
    public $dbConfig;      // 数据库配置
    public $dbModel;       // 数据库实例
    public $db;            // 数据库
    public $table;         // 数据表
    public $primaryKey;    // 主键

    public function __construct()
    {
        $this->CI = & get_instance();

    }

    /**  获取实例
     * @return DatabaseModel
     */
    public static function instance()
    {
        return new static();
    }

}