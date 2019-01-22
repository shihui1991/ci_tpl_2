<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| 数据库配置
|--------------------------------------------------------------------------
*/
/* --------- mysql ----- start --------- */
// 配置
defined('DB_NAME_MYSQL_CONF')       OR define('DB_NAME_MYSQL_CONF', 'default');   // 配置 - 配置名
defined('DB_INDEX_MYSQL_CONF')      OR define('DB_INDEX_MYSQL_CONF', 'ci_tpl');   // 配置 - 数据库
// 后台管理
defined('DB_NAME_MYSQL_ADMIN')      OR define('DB_NAME_MYSQL_ADMIN', 'default');  // 后台管理 - 配置名
defined('DB_INDEX_MYSQL_ADMIN')     OR define('DB_INDEX_MYSQL_ADMIN', 'ci_tpl');  // 后台管理 - 数据库
/* --------- mysql ----- end ---------- */

/* --------- redis ----- start --------- */
// 配置
defined('DB_NAME_REDIS_CONF')       OR define('DB_NAME_REDIS_CONF', 'default');      // 配置 - 配置名
defined('DB_INDEX_REDIS_CONF')      OR define('DB_INDEX_REDIS_CONF', 0);             // 配置 - 数据库
// 后台管理
defined('DB_NAME_REDIS_ADMIN')      OR define('DB_NAME_REDIS_ADMIN', 'default');     // 后台管理 - 配置名
defined('DB_INDEX_REDIS_ADMIN')     OR define('DB_INDEX_REDIS_ADMIN', 1);            // 后台管理 - 数据库
/* --------- redis ----- end ----------- */