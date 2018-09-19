<?php
/**
 *  固定配置
 * @user 罗仕辉
 */
if(file_exists(APPPATH.'config/common/conf.php')){
    require_once APPPATH.'config/common/conf.php';
}

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

defined('UPLOAD_DIR')        OR define('UPLOAD_DIR', 'uploads');      // 上传目录
defined('DOWNLOAD_DIR')      OR define('DOWNLOAD_DIR', 'download');   // 下载目录
defined('CONFIG_UPLOAD_DIR') OR define('CONFIG_UPLOAD_DIR', 'configFile');   // 配置文件上传目录