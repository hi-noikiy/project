<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/1/22
 * Time: 下午2:39
 */
define('ROOT_PATH', str_replace('interface/ailuoke/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";
$key_arr = array(
    8=>array(
        'android' => array(
            'appId'     =>'scmnzv',
            'appKey'    =>'969e451355c44c1c8f67037af79b9515',
        ),
        'android1' => array(
            'appId'     =>'aru7dr',
            'appKey'    =>'a3be3c383ea841d580d65f8ab13d2ebe',
        ),
    )
);

/**
 * 验证支付通知签名
 * @return bool
 * @param  Array $request 此次通知的内容
 * @param String $app_key CP方在魔方平台接入的游戏密钥
 * @author AndyLee <root@lostman.org>
 */
function verify_signature($request, $app_key){
    $signature = $request['sign'];
    unset($request['sign'],$request['notify_time'],$request['request_count']);
    $str = '';
    ksort($request);
    foreach ($request as $k => $v)
    {
        $str .= $v;
    }
    if(md5($str.$app_key) !== $signature){
        return false;
    }
    return true;
}

$yuanbaoArr = array(
    'yoyo.easy2play.item16'=>array('USD','180'),
    'yoyo.easy2play.item1'=>array('USD','60'),
    'yoyo.easy2play.item3'=>array('USD','300'),
    'yoyo.easy2play.item5'=>array('USD','600'),
    'yoyo.easy2play.item6'=>array('USD','900'),
    'yoyo.easy2play.item19'=>array('USD','1500'),
    'yoyo.easy2play.item11'=>array('USD','3000'),
    'yoyo.easy2play.item15'=>array('USD','6000'),
);