<?php
/**
 * Created by PhpStorm.
 * User: Guangpeng Chen
 * Date: 8/7-007
 * Time: 11:22
 */
define('BASE_LOG_DIR', '/data/log/site');
define('BASEPATH', '/var/www/html/ci');

/**
 * 统一写日志函数，日志统一写入BASE_LOG_DIR下面
 * @author dwer
 * @date   2016-03-07
 *
 * @param string $path 在BASE_LOG_DIR这个下面的目录 - product/reseller_storage
 * @param string $content 需要写入的内容
 * @param string $pathMode 目录分隔模式：
 *                      day：按日切分 - product/reseller_storage/2016/03/23.log
 *                      month：按月切分 - product/reseller_storage/2016/03.log
 *                      year：按年切分 - product/reseller_storage/2016.log
 * @return
 */
function pft_log($path, $content, $pathMode = 'day') {
    $path    = strval($path);
    $path    = str_replace("\\", '/', trim($path, '/'));
    $content = strval($content);
    if(!$path || !$content) {
        return false;
    }

    $pathMode = in_array($pathMode, array('day', 'month', 'year')) ? $pathMode : 'day';

    $tmpPath = BASE_LOG_DIR . '/' . $path . '/';
    $fileName = date('Y') . '.log';
    if($pathMode == 'day') {
        $tmpPath .= date('Y') . '/' . date('m') . '/';
        $fileName = date('d') . '.log';
    } elseif($pathMode == 'month') {
        $tmpPath .= date('Y') . '/';
        $fileName = date('m') . '.log';
    }

    //如果文件不存在，就创建文件
    if(!file_exists($tmpPath)) {
        $res = mkdir($tmpPath, 0777, true);
        if(!$res) {
            return false;
        }
    }

    //内容写入日志文件
    $file    = $tmpPath . $fileName;
    $content = date('Y-m-d H:i:s') . ' # ' . $content . "\r\n";
    $res     = file_put_contents($file, $content, FILE_APPEND);

    if($res) {
        return true;
    } else {
        return false;
    }
}