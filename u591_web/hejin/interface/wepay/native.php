<?php
ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);

require_once "lib/WxPay.Api.php";
require_once "lib/WxPay.NativePay.php";
require_once 'lib/log.php';
//模式二
/**
 * 流程：
 * 1、调用统一下单，取得code_url，生成二维码
 * 2、用户扫描二维码，进行支付
 * 3、支付完成之后，微信服务器会通知支付成功
 * 4、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
 */
$body = $_REQUEST['body'];
$attach = $_REQUEST['attach'];
$tradeNo = $_REQUEST['trade_no'];
$total_fee = $_REQUEST['total_fee'];
$goddsTag = $_REQUEST['tag'];
$productId = $_REQUEST['product_id'];
$notifyUrl = $_REQUEST['notify_url'];


$notify = new NativePay();
$input = new WxPayUnifiedOrder();
$input->SetBody($body);
$input->SetAttach($attach);
$input->SetOut_trade_no($tradeNo);
$input->SetTotal_fee($total_fee);
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag($goddsTag);
$input->SetNotify_url($notifyUrl);
$input->SetTrade_type("NATIVE");
$input->SetProduct_id($productId);
$result = $notify->GetPayUrl($input);

$url = $result["code_url"];

exit($url);
?>