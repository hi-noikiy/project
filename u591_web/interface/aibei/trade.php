<?php
header("Content-type: text/html; charset=utf-8");

require_once ("config.php");
require_once ("base.php");
$post = serialize($_POST);
$get = serialize($_GET);
global $orderUrl, $appkey, $platpkey , $transid,$appid;
write_log(ROOT_PATH."log","aibei_trade_info_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
//下单接口

$data = json_decode($_POST['transdata'],true);
$config = explode('_', $data['cpprivateinfo']);
$appid = $karr[$config[3]]['appid'];
$platpkey = $karr[$config[3]]['platpkey'];
$appkey = $karr[$config[3]]['appkey'];
$orderReq['appid'] = "$appid";
$orderReq['waresid'] = $data['waresid'];
$orderReq['cporderid'] = $data['cporderid'];//确保该参数每次 都不一样。否则下单会出问题。
$orderReq['price'] = intval($data['price']);   //单位：元
$orderReq['currency'] = 'RMB';
$orderReq['appuserid'] = $data['appuserid'];
$orderReq['cpprivateinfo'] = $data['cpprivateinfo'];
$orderReq['notifyurl'] = 'http://gunweb.u591.com:83/interface/aibei/callback.php';
//组装请求报文  对数据签名

$reqData = composeReq($orderReq, $appkey);
//发送到爱贝服务后台请求下单
$respData = request_by_curl($orderUrl, $reqData, 'order test');
 
//验签数据并且解析返回报文
if(!parseResp($respData, $platpkey, $respJson)) {
	write_log(ROOT_PATH."log","aibei_trade_info_",json_encode($orderReq)."验证数据失败 ".json_encode($respJson).date("Y-m-d H:i:s")."\r\n");
	echo 0;
}else{
	write_log(ROOT_PATH."log","aibei_trade_info_","验证数据成功 ".json_encode($respJson).date("Y-m-d H:i:s")."\r\n");
	//     下单成功之后获取 transid
	echo json_encode(array('status'=>0,'msg'=>'success','data'=>array('orderInfo'=>$respJson)));
	exit($transid);
}



?>