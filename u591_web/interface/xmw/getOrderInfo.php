<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2016/12/6
 * Time: 下午2:28
 */
require_once 'common.php';
require_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","xmw_order_all_log_","post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");

$data = array();
$data['access_token'] = $_REQUEST['access_token'];
// = $_REQUEST['app_user_id'];
$data['amount'] = $_REQUEST['amount'];
$data['app_subject'] = $_REQUEST['app_subject'];
$data['app_ext1'] = $_REQUEST['app_ext1'];

$appExt1Arr = explode("_", $data['app_ext1']);
$gameId = $appExt1Arr[0];
$serverId = $appExt1Arr[1];
$accountId = $appExt1Arr[2];

$appId = $key_arr[$gameId]['appkey'];
$appSecret = $key_arr[$gameId]['appsecret'];
$accessToken = $data['access_token'];
$url = 'http://open.xmwan.com/v2/users/me?access_token='.$accessToken;
$result = file_get_contents($url);
write_log(ROOT_PATH."log","xmw_order_result_log_","result=$result, ".date("Y-m-d H:i:s")."\r\n");
$resultArr = json_decode($result, true);
if(!isset($resultArr['xmw_open_id'])) {
    write_log(ROOT_PATH."log","xmw_order_error_log_","result=$result, ".date("Y-m-d H:i:s")."\r\n");
    exit('fail');
}
$data['app_user_id'] = $resultArr['xmw_open_id'];
$data['timestamp'] = time();
$data['notify_url'] = 'http://gunweb.u591.com:83/interface/xmw/callback.php';
$data['app_order_id'] = $gameId.'_'.$serverId.'_'.$accountId.'_'.date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);

try
{
    $purchase = new XMWPurchase($appId, $appSecret);
    $orderInfo = $purchase->createPurchase($accessToken, $data);
    if(!isset($orderInfo['serial'])){
        write_log(ROOT_PATH."log","xmw_order_error_log_","result=$orderInfo, ".date("Y-m-d H:i:s")."\r\n");
        exit('fail');
    }
    $jsonData = json_encode($orderInfo);
    write_log(ROOT_PATH."log","xmw_order_result_log_","result=$jsonData, ".date("Y-m-d H:i:s")."\r\n");
    header('Content-Type: application/json; charset=utf-8');
    exit($jsonData);
}
catch(XMWException $exception)
{
    $errorMsg = $exception->getMessage();
    write_log(ROOT_PATH."log","xmw_order_error_log_", "error=$errorMsg, post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('fail');
}
