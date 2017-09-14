<?php
/**
 * 微信配置常量
 */
	define('NOTIFY_URL','http://zhongxingpay.zrfilm.com/Wechatpay/wxPayNotify.html'); 
	define('NOTIFY_URL1','http://zhongxingpay.zrfilm.com/Wechatpay/wxPayNotify1.html');
	define('CURL_TIMEOUT',30); 
	define('SSLCERT_PATH',getcwd().'/WxPayPubHelper/cacert/apiclient_cert.pem'); 
	define('SSLKEY_PATH',getcwd().'/WxPayPubHelper/cacert/apiclient_key.pem'); 
	function wx_get_token($appid,$appsecret) {
		$token = S('access_token'.$appid);
		if (!$token) {
			$res = file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appsecret);
			$res = json_decode($res, true);
			$token = $res['access_token'];
			S('access_token'.$appid, $token, 7200);
		}
		return $token;
	}
	function wx_get_jsapi_ticket($appid,$appsecret){
		$ticket = S('wx_ticket'.$appid);
		if($ticket){
			return $ticket;
		}
		$runTimes = 5;
		while (true && $runTimes >=0) {
			$token = S('access_token'.$appid);
			if(empty($token)){
				$token = wx_get_token($appid,$appsecret);
			}
	
			$getTicketUrl = sprintf("https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=%s&type=jsapi", $token);
			$ticketRes = json_decode(file_get_contents($getTicketUrl), true);
			if(!empty($ticketRes['ticket'])){
				S('wx_ticket'.$appid, $ticketRes['ticket'], 7200);
				return $ticketRes['ticket'];
			}
			$runTimes--;
		}
	}
	
	 function verify_c(){
	
		$Verify = new \Think\Verify();
	
		$Verify->fontSize = 18;
	
		$Verify->length   = 4;
	
		$Verify->useNoise = false;
	
		$Verify->codeSet = '0123456789';
	
		$Verify->imageW = 130;
	
		$Verify->imageH = 50;
	
		//$Verify->expire = 600;
	
		$Verify->entry();
	
	}
	//获取微信相关配置
	function getWeiXinInfo()
	{
		$token = session('token');
		$weiXinInfo = S('weiXinInfo' . $token);
		if(empty($weiXinInfo)){
			$weiXinInfo = D('Cinema')->getCinemaGroupByToken($token);
			S('weiXinInfo' . $token, $weiXinInfo, 3600*24*7);
		}
		return $weiXinInfo;
	}

	//获取订单信息
	function getOrderInfo($orderId)
	{	
		$getOrderInfo = S('OrderInfo' . $orderId);
		if (empty($getOrderInfo)) {
			$getOrderInfo = D('order')->findObj($orderId);
			S('OrderInfo' . $orderId, $arr, 600);
		}
		return $getOrderInfo;
	}
	//获取当前可用支付方式
	function getNowPayWay($cinemaCode, $orderId)
	{

		$getNowPayWay = S('payWay' . $orderId);
		if (empty($getNowPayWay)) {
			//$cinema = S('GETCINEMABYCODE' . $cinemaCode);
			if (empty($cinema)) {
				$cinema = D('cinema')->find($cinemaCode);
				S('GETCINEMABYCODE' . $cinemaCode, $cinema, 3600*24*7);
			}
			$getWeiXinInfo = getWeiXinInfo();
			$payWay = json_decode($getWeiXinInfo['payWay'], true);
			$weiXingPayWay = $payWay['weixinPayWay'];
			$cinemaPayWay = json_decode($cinema['payWay'], true);
			$cinemaWeiXinPayWay = $cinemaPayWay['weixinPayWay'];
			
			$getNowPayWay['onlinePay'] = array_intersect($weiXingPayWay, $cinemaWeiXinPayWay);
			$getNowPayWay['cinemaName'] = $cinema['cinemaName'];
			$getNowPayWay['payConfig'] = json_decode($cinema['payConfig'], true);
			
			S('payWay' . $orderId, $getNowPayWay, 600);
		}
		return $getNowPayWay;
		
	}
	//设置模板
	function setTemp($tempName)
	{
		C('DEFAULT_THEME', $tempName);
		$TMPL_PARSE_STRING = C('TMPL_PARSE_STRING');	

		$TMPL_PARSE_STRING = array(
        '__STATIC__' => __ROOT__ . '/Public/static',
        '__IMG__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/'.$tempName.'/images',
        '__CSS__'    => __ROOT__ . '/Public/' . MODULE_NAME  . '/'.$tempName.'/css',
        '__JS__'     => __ROOT__ . '/Public/' . MODULE_NAME  . '/'.$tempName.'/js',
		'__UPLOAD__'     => __ROOT__ . '/Uploads',
		);

		C('TMPL_PARSE_STRING', $TMPL_PARSE_STRING);
	}


	function check_verify($code, $id = ""){
	
		$verify = new \Think\Verify();
	
		return $verify->check($code, $id);
	
	}
?>
