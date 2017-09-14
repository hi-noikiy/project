<?php

date_default_timezone_set('PRC');
require_once dirname(dirname(__FILE__)) . '/util/ConfigUtil.php';

/**
 * 使用系统的日志输出功能输出日志
 */
class Logger {

    public static function info($msg) {
        $logpath = ConfigUtil::get_val_by_key("logpath");
        if (!is_readable($logpath)) {
            is_file($logpath) or mkdir($logpath, 0700);
        }
        error_log(date('y-m-d H:i:s ', time()) . $msg . "\n", 3, $logpath . "ttsdk-" . date('y-m-d-H', time()) . ".log");
    }

}
