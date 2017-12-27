<?php
/**
 * @created by PhpStorm.
 * @user: luoxue
 * @date: 2017/4/14 下午2:14
 * @desc: 微信组装参数返回
 * @param:
 * @return:
 */
include_once 'config.php';
include_once './lib/appPayRequest.php';

$post = serialize($_POST);
$get = serialize($_GET);
$body = $_POST['subject'];
$bizContent = $_POST['biz_content']; //json{"game_id":"8","server_id":"xx", "account_id":"xxx"}
//支付金额，单位：分
$amount = $_POST['total_amount'] * 100;

write_log(ROOT_PATH."log","wepay_params_all_","post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
if(!$body || !$bizContent || !$amount){
    write_log(ROOT_PATH."log","wepay_params_error_","post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit();
}
$bizContentArr = json_decode($bizContent, true);
$gameId = $bizContentArr['game_id'];
$serverId = $bizContentArr['server_id'];
$accountId = $bizContentArr['account_id'];
$fenbaoId = $bizContentArr['fenbao_id'];

if(!isset($fenbao_arr[$fenbaoId])){
    write_log(ROOT_PATH."log","wepay_params_error_","fenbao_id must config. post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit();
}
$appId = $fenbao_arr[$fenbaoId]['appid'];
$MCHID = $fenbao_arr[$fenbaoId]['MCHID'];
$appKey = $fenbao_arr[$fenbaoId]['appkey'];
$host = $fenbao_arr[$fenbaoId]['appkey'];
$port = $fenbao_arr[$fenbaoId]['appkey'];

$reportLevel = $fenbao_arr[$fenbaoId]['REPORT_LEVEL'];

$obj = new appPayRequest();

//定义配置信息
$obj->setAppKey($appKey);
$obj->setAppId($appId);
$obj->setMCHID($MCHID);
$obj->setReportLevel($reportLevel);
//定义支付参数
$outTradeNo = $gameId.'_'.$serverId.'_'.$accountId.'_'.time();

$obj->setBody($body);
$obj->setAttach($fenbaoId);
$obj->setOutTradeNo($outTradeNo);
$obj->setAmont($amount);
$obj->setTimeStart();
$obj->setTimeExpire();
$obj->setGoodsTag();
$obj->setNotifyUrl();
$obj->setTradeType();

$order = $obj->unifiedOrder();
$output = [];

$output['appid'] = $appId;
$output['partnerid'] = $MCHID;
$output['package'] = 'Sign=WXPay';
$output['prepayid'] = $order['prepay_id'];
$output['noncestr'] = $order['nonce_str'];
$output['timestamp'] = time();

$output['sign'] = $obj->MakeSign($appKey, $output);

unset($order);
echo json_encode($output);

