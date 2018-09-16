<?php
/**
 *  动态配置
 */

defined('DEFAULT_PERPAGE')      OR define('DEFAULT_PERPAGE', 15);      // 默认分页条数
defined('DEFAULT_PAGEBAR_NUM')  OR define('DEFAULT_PAGEBAR_NUM', 10);  // 默认分页跳转页码个数
defined('OPERAT_WAIT_TIME')     OR define('OPERAT_WAIT_TIME', 36000);   // 后台操作等待最长时间（秒）
defined('WX_APPID')             OR define('WX_APPID', 'wx5cf8b07877638bfc');                    // 微信 APPID
defined('WX_SECRET')            OR define('WX_SECRET', '90f959b5c385a5618f44824896d3b209');     // 微信 SECRET

defined('UPLOAD_DIR')        OR define('UPLOAD_DIR', 'uploads');      // 上传目录
defined('DOWNLOAD_DIR')      OR define('DOWNLOAD_DIR', 'download');   // 下载目录
defined('CONFIG_UPLOAD_DIR') OR define('CONFIG_UPLOAD_DIR', 'configFile');   // 配置文件上传目录