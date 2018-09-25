<?php
/**
 *  动态配置
 */

/* redis数据库分配 - 配置 */
defined('REDIS_DB_CONF')      OR define('REDIS_DB_CONF', '0');

/* redis数据库分配 - 后台管理 */
defined('REDIS_DB_ADMIN')      OR define('REDIS_DB_ADMIN', '1');

/* redis数据库分配 - 日志 */
defined('REDIS_DB_LOG')      OR define('REDIS_DB_LOG', '2');

/* redis数据库分配 - 玩家 */
defined('REDIS_DB_USER')      OR define('REDIS_DB_USER', '3');

/* 分享类型 - 普通分享 */
defined('SHARE_TYPE_NORMAL')      OR define('SHARE_TYPE_NORMAL', '1');

/* 分享类型 - 普通获胜炫耀分享 */
defined('SHARE_TYPE_WIN')      OR define('SHARE_TYPE_WIN', '2');

/* 分享类型 - 通关获胜红包分享 */
defined('SHARE_TYPE_CLEARANCE')      OR define('SHARE_TYPE_CLEARANCE', '3');

/* 分享类型 - 额外红包炫耀分享 */
defined('SHARE_TYPE_EXTRA')      OR define('SHARE_TYPE_EXTRA', '4');

/* 分享类型 - 赚复活卡活动分享 */
defined('SHARE_TYPE_CARD')      OR define('SHARE_TYPE_CARD', '5');

/* 分享类型 - 连胜宝箱分享 */
defined('SHARE_TYPE_WIN_BOX')      OR define('SHARE_TYPE_WIN_BOX', '6');

/* 分享类型 - 挑战失败求助分享 */
defined('SHARE_TYPE_FAIL')      OR define('SHARE_TYPE_FAIL', '7');

/* 分享类型 - 互助宝箱开启分享 */
defined('SHARE_TYPE_HELP_BOX')      OR define('SHARE_TYPE_HELP_BOX', '8');

/* 分享类型 - 迎新红包 */
defined('SHARE_TYPE_NEW_REDPAG')      OR define('SHARE_TYPE_NEW_REDPAG', '9');

/* 微信配置 - 微信 SECRET */
defined('WX_SECRET')      OR define('WX_SECRET', '90f959b5c385a5618f44824896d3b209');

/* 微信配置 - 微信 APPID */
defined('WX_APPID')      OR define('WX_APPID', 'wx5cf8b07877638bfc');

/* 玩家初始值 - 玩家复活卡初始值 */
defined('USER_CARD_DEFAULT')      OR define('USER_CARD_DEFAULT', '5');

/* 玩家初始值 - 玩家金币初始值 */
defined('USER_COIN_DEFAULT')      OR define('USER_COIN_DEFAULT', '1000');

/* 红包类型 - 迎新红包 */
defined('REDPAG_TYPE_NEW')      OR define('REDPAG_TYPE_NEW', '1');

/* 红包类型 - 通关红包 */
defined('REDPAG_TYPE_CLEARANCE')      OR define('REDPAG_TYPE_CLEARANCE', '2');

/* 红包类型 - 额外分享红包 */
defined('REDPAG_TYPE_EXTRA')      OR define('REDPAG_TYPE_EXTRA', '3');

/* 迎新红包范围 - 迎新红包起始金额 */
defined('NEW_REDPAG_START')      OR define('NEW_REDPAG_START', '0.20');

/* 迎新红包范围 - 迎新红包截止金额 */
defined('NEW_REDPAG_END')      OR define('NEW_REDPAG_END', '2');

/* 额外红包范围 - 额外红包范围起始 */
defined('EXTRA_REDPAG_START')      OR define('EXTRA_REDPAG_START', '0.1');

/* 额外红包范围 - 额外红包范围截止 */
defined('EXTRA_REDPAG_END')      OR define('EXTRA_REDPAG_END', '2');

/* 默认配置项 - 默认分页跳转页码个数 */
defined('DEFAULT_PAGEBAR_NUM')      OR define('DEFAULT_PAGEBAR_NUM', '10');

/* 默认配置项 - 默认分页条数 */
defined('DEFAULT_PERPAGE')      OR define('DEFAULT_PERPAGE', '15');

/* 默认配置项 - 后台操作等待最长时间（秒） */
defined('OPERAT_WAIT_TIME')      OR define('OPERAT_WAIT_TIME', '36000');
