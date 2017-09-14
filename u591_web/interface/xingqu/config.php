<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/2/15
 * Time: 上午10:31
 * 星趣sdk
 */
define('ROOT_PATH', str_replace('interface/xingqu/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH."inc/function.php";
include_once ROOT_PATH.'inc/config_account.php';

$key_arr = array(
    8=>array('appid'=>'100017','appkey'=>'5ixnhojGf2MlX4m','paykey'=>'WrIGsOdzvt7xNgDJY0Um1yjZ8'),
);

function xingquSign($param, $signKey) {
    $paramFilter = array();
    while ( list ($key, $val) = each($param) ) {
        if ( $key == 'sign' || $key == 'sign_type' || $val === '' ) continue;
        else
            $paramFilter[$key] = $param[$key];
    }
    if( empty($paramFilter) ){
        return false; }
    ksort($paramFilter);
    reset($paramFilter);
    $arg = '';
    while ( list ($key, $val) = each($paramFilter) ) {
        $arg .= $key . '=' . urlencode($val) . '&';
    }
    //去掉最后一个&字符
    $arg = substr($arg, 0, count($arg) - 2);
    //如果存在转义字符，那么去掉转义
    if (get_magic_quotes_gpc()) {
        $arg = stripslashes($arg);
    }
    return md5($arg . $signKey);
}