<?php

defined('DEFAULT_PERPAGE')      OR define('DEFAULT_PERPAGE', 15);      // 默认分页条数
defined('DEFAULT_PAGEBAR_NUM')  OR define('DEFAULT_PAGEBAR_NUM', 10);  // 默认分页跳转页码个数
defined('OPERAT_WAIT_TIME')     OR define('OPERAT_WAIT_TIME', 3600);   // 后台操作等待最长时间（秒）

// 两值比较结果
defined('VALUE_LEFT_EQ_RIGHT')   OR define('VALUE_LEFT_EQ_RIGHT', 0);   // 两值比较结果 - 相等
defined('VALUE_LEFT_GT_RIGHT')   OR define('VALUE_LEFT_GT_RIGHT', 1);   // 两值比较结果 - 左大于右
defined('VALUE_LEFT_LT_RIGHT')   OR define('VALUE_LEFT_LT_RIGHT', -1);  // 两值比较结果 - 左小于右

// 状态
defined('STATE_OFF')    OR define('STATE_OFF', 0);  // 状态 - 关闭
defined('STATE_ON')     OR define('STATE_ON', 1);   // 状态 - 开启

// 超管
defined('ADMIN_NO')       OR define('ADMIN_NO', 0);     // 超管 - 否
defined('ADMIN_YES')      OR define('ADMIN_YES', 1);    // 超管 - 是

// 排序顺序
defined('ORDER_BY_ASC')       OR define('ORDER_BY_ASC', 'ASC');      // 排序顺序 - 升序
defined('ORDER_BY_DESC')      OR define('ORDER_BY_DESC', 'DESC');    // 排序顺序 - 降序