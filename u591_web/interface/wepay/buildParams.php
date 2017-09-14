<?php
//客户端支付请求
//http://gunweb.u591.com:83/interface/wepay/buildParams.php

ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);
require_once "./lib/WxPay.Api.php";
require_once 'log.php';
$body = $_POST['subject'];

$fenbaoId = $_POST['fenbao_id'];
$bizContent = $_POST['biz_content']; //json{"game_id":"8","server_id":"xx", "account_id":"xxx"}
$bizContentArr = json_decode($bizContent, true);
$gameId = $bizContentArr['game_id'];
$serverId = $bizContentArr['server_id'];
$accountId = $bizContentArr['account_id'];

//支付金额，单位：分
$total_fee = $_POST['total_amount'] * 100;
if (!$total_fee) $total_fee = 1;
if (!$body) $body = 'test';
//初始化日志
$logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);
//②、统一下单
$input = new WxPayUnifiedOrder();
$input->SetBody($body);
$input->SetAttach($body);


$outTradeNo = $gameId.'_'.$serverId.'_'.$accountId.'_'.date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);

$input->SetOut_trade_no($outTradeNo);
$input->SetTotal_fee($total_fee);
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag("充值");
$input->SetNotify_url(WxPayConfig::NOTIFY_URL);
$input->SetTrade_type("APP");
$order = WxPayApi::unifiedOrder($input);
$output = [];
$output['appid'] = WxPayConfig::APPID;
$output['partnerid'] = WxPayConfig::MCHID;
$output['package'] = 'Sign=WXPay';
$output['noncestr'] = WxPayApi::getNonceStr();
$output['prepayid'] = $order['prepay_id'];
$output['noncestr'] = $order['nonce_str'];
$output['timestamp'] = time();
$output['sign'] = $input->MakeSign($output);
unset($order);
echo json_encode($output);