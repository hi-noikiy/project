<?php
namespace Web\Controller;
use Think\Controller;
class PlanController extends InitController {
	/**
	 * 获取快捷购票影片列表
	 */	
	function quickfilms(){
		$cinemaCode=I('cinemaCode');
		if(empty($cinemaCode)){
			$this->error('参数错误！', '11001');
		}
		$map['cinemaCode']=$cinemaCode;
		$map['startTime']=array('egt',time());
		$films=D('plan')->quickfilms($map);
		$this->success('',$films);
	}
	/**
	 * 获取快捷购票时间列表
	 */
	function quicktimes(){
		$cinemaCode=I('cinemaCode');
		$filmNo=I('filmNo');
		if(empty($cinemaCode)||empty($filmNo)){
			$this->error('参数错误！', '11001');
		}
		$map['cinemaCode']=$cinemaCode;
		$map['filmNo']=$filmNo;
		$times=D('plan')->gettime($map);
		$this->success('',$times);
	}
	/**
	 * 获取快捷购票排期列表
	 */
	function quickplans(){
		$cinemaCode=I('cinemaCode');
		$filmNo=I('filmNo');
		$time=I('time');
		if(empty($cinemaCode)||empty($filmNo)||empty($time)){
			$this->error('参数错误！', '11001');
		}
		$user=$this->user;
		if(empty($user)){
			$mystr=$this->wwwInfo['defaultLevel'];
		}else{
			$mystr=$user['memberGroupId'];
			$data['hasuser']=1;
		}
		$data['plans']=D('plan')->findplans($time,$cinemaCode,$filmNo,$mystr);
		$this->success('',$data);
	}
	/**
	 * 快捷购票
	 */
	function quickpurchase(){
		$cinemaCode=session('cinemaCode');
		$map['cinemaCode'] = array('IN', $this->wwwInfo['cinemaList']);
		$cinemalist=D('cinema')->getCinemaList($map,$cinemaCode);
		$this->assign('cinemalist',$cinemalist);
		$this->display();
	}
	/**
     * 排期
     */
    public function plan(){
    	$this->assign('pageId', 'page-plan');
    	$cinemaCode=I('cinemaCode');
    	if(!empty($cinemaCode)){
    		session('cinemaCode',$cinemaCode);
    	}else{
    		$cinemaCode=session('cinemaCode');
    	}
    	$map['cinemaCode'] = array('IN', $this->wwwInfo['cinemaList']);
    	$cinemalist=D('cinema')->getCinemaList($map,$cinemaCode);
    	$this->assign('cinemalist',$cinemalist);
    	$this->display();
    }
    /**
     * 排期ajax
     */
    public function planajax(){
    	$cinemaCode=I('request.cinemaCode');
    	if(empty($cinemaCode)){
    		$cinemaCode=session('cinemaCode');
    	}else{
    		session('cinemaCode',$cinemaCode);
    	}
    	$time=I('startTime');
    	if(empty($time)){
    		$data['planTime']=$planTime=D('Plan')->gettime(array('cinemaCode'=>$cinemaCode));//时间列表
    		$time=$planTime[0]['time'];
    	}
    	$user=session('ftuser');
    	$user=$this->getBindUserInfo($user);
    	if(empty($user)){
    		$mystr=$this->wwwInfo['defaultLevel'];
    	}else{
    		$mystr=$user['memberGroupId'];
    		$data['hasuser']=1;
    	}
    	$data['films']=D('plan')->planInfos($time,$cinemaCode,$mystr);
    	echo json_encode($data);
    }
	
    /**
     * 是否存在等待支付
     */
    public function haspaying(){
    	$status=I('status');
    	$order=D('order')->getpaying($status);
    	if(empty($order)){
    		echo 0;
    	}else{
    		echo 1;
    	}
    }
    /**
     * 座位图
     */
    public function seat(){
    	$this->assign('pageId', 'page-plan');
    	$user=$this->user;
    	$featureAppNo=I('featureAppNo');
    	$plan=D('plan')->getplanInfo('cinemaCode, cinemaName,featureAppNo, listingPrice,startTime, filmNo, filmName, hallName, priceConfig, hallNo, otherfilmNo, featureNo, startTime, totalTime, copyType', $featureAppNo);
    	$cinemaCode = $plan['cinemaCode'];
    	if(empty($user)){
    		$mystr=$this->wwwInfo['defaultLevel'];
    	}else{
    		$mystr=$user['memberGroupId'];
    		$myuser=session('ftuser');
    		if(!empty($myuser['mobile'])){
    			$mobile=$myuser['mobile'];
    		}elseif(!empty($myuser['mobileNum'])){
    			$mobile=$myuser['mobileNum'];
    		}elseif(!empty($user['mobile'])){
    			$mobile=$user['mobile'];
    		}elseif(!empty($user['mobileNum'])){
    			$mobile=$user['mobileNum'];
    		}
    		$this->assign('mobile',$mobile);
    	}
    	$priceConfig = json_decode($plan['priceConfig'],true);
    	$priceConfig = $priceConfig[$this->wwwInfo['cinemaGroupId']];
    	if(!empty($priceConfig)){
    		$plan['memberPrice']=$priceConfig[$mystr];
    	}
    	if(empty($plan['memberPrice'])){
    		$plan['memberPrice']=$plan['listingPrice'];
    	}
    	$otherplans=D('plan')->getplans($featureAppNo,$mystr);
    	$cinema=D('cinema')->find($plan['cinemaCode']);
    	$hallprice=D('cinema')->findHallPrice(array('cinemaCode'=>$plan['cinemaCode'],'hallNo'=>$plan['hallNo']));
    	$hallprice=$hallprice[$this->wwwInfo['cinemaGroupId']];
    	$seatinfos=S('seat'.$this->wwwInfo['cinemaGroupId'].$plan['cinemaCode'].$featureAppNo);
    	if(empty($seatinfos)){
    		$seats=D('ZMMove')->getPlanSiteState(array('cinemaCode'=>$plan['cinemaCode'],'featureAppNo'=>$featureAppNo,'link'=>$cinema['link'],'hallNo'=>$plan['hallNo'],'filmNo'=>$plan['otherfilmNo'],'showSeqNo'=>$plan['featureNo'],'planDate'=>$plan['startTime']));
    		$seatinfos=D('seat')->seatInfos($seats['PlanSiteState']);
    		S('seat'.$this->wwwInfo['cinemaGroupId'].$plan['cinemaCode'].$featureAppNo,$seatinfos,30);
    	}
    	if($user){
    		$hasorder=D('order')->updateOrder($user['id']);
    		$this->assign('hasorder',$hasorder);
    	}
    	$this->assign('plan',$plan);
    	$this->assign('url',$_SERVER["REQUEST_URI"]);
    	$this->assign('otherplans',$otherplans);
    	$this->assign('hallprice',$hallprice);
    	$this->assign('seatinfos',$seatinfos);
    	$this->display();
    }
    /**
     * 锁座
     *
     * @param string $data
     * @param string $featureAppNo
     * @see CxUser::lockSeat()
     * @return mixed
     */
    public function seatLock(){
    	$mobile=I('mobile');
    	$featureAppNo=I('featureAppNo');
    	$datas=preg_replace("/\|/",'#', I('datas'));
    	$user=session('ftuser');
    	if(empty($user)){
    		$this->error('请先登录');
    	}
    	$user=$this->getBindUserInfo($user);
    	$plan=D('Plan')->getplan($featureAppNo,$user['memberGroupId']);
    	$cinema=D('cinema')->find($plan['cinemaCode']);
    	$datas=explode(',',$datas);
    	$str='';
    	$srctr='';
    	$hall=M('cinemaHall')->where(array('cinemaCode'=>$plan['cinemaCode'],'hallNo'=>$plan['hallNo']))->find();
    	foreach ($datas as $k=>$value){
    		$seatinfo=explode('.',$value);
    		$start=explode('排',$seatinfo[1]);
    		$end=explode('座',$start[1]);
    		$srctr.=$start[0].':'.$end[0].'|';
    		$str.=$seatinfo[1].',';
    		$seats[$k]['seatNo']=$seatinfo[0];
    	}
    	foreach ($seats as $k=>$v){
    		if($k==0){
    			$fead = D('seat')->findSectionId(array('cinemaCode'=>$plan['cinemaCode'],'hallNo'=>$plan['hallNo'],'seatCode'=>$v['seatNo']));
    		}else{
    			$ofead = D('seat')->findSectionId(array('cinemaCode'=>$plan['cinemaCode'],'hallNo'=>$plan['hallNo'],'seatCode'=>$v['seatNo']));
    			if($ofead['findSectionId']!=$fead['findSectionId']){
    				$this->error('座区不一样');
    			}
    		}
    	}
    	$priceConfig=json_decode($hall['price'],true);
    	$priceConfig=$priceConfig[$this->wwwInfo['cinemaGroupId']];
    	if(empty($priceConfig[$fead['findSectionId']])){
    		$price=0;
    	}else{
    		$price=$priceConfig[$fead['findSectionId']];
    	}
    	foreach ($seats as $k=>$v){
    		$seats[$k]['ticketPrice']=$plan['memberPrice']+$price;
    	}
    	$str=substr($str,0,strlen($str)-1);
    	$map=array(
    			'cinemaCode'=>$plan['cinemaCode'],
    			'featureAppNo'=>$featureAppNo,
    			'seatInfos'=>$seats,
    			'mobile'=>$mobile,
    			'seatstr'=>substr($srctr, 0,-1),
    			'ticketCount'=>count($datas),
    			'link'=>$cinema['link'],
    			'hallNo'=>$plan['hallNo'],
    			'sectionId'=>$fead['findSectionId'],
    			'filmNo'=>$plan['otherfilmNo'],
    			'featureNo'=>$plan['featureNo'],
    			'planDate'=>$plan['startTime'],
    	);
    	$lock=D('ZMMove')->checkSeatState($map);
    	if($lock['ResultCode']=='0'){
    		$orderid=D('order')->addObj($lock,$plan,$user,$str,$mobile,json_encode($seats),$fead);
    		if(!empty($orderid)){
    			$this->success('锁座成功',array('orderCode'=>$orderid));
    		}else{
    			$this->error('锁座成功，添加订单数据失败');
    		}
    	}else{
    		$this->error($lock['Message']);
    	}
    }
    
    
    /**
     * 结算页面
     */
    public function pay(){
    	$this->assign('pageId', 'page-pay');
    	$user =$this->user;
    	$orderid = I('orderid');
    	$order = getOrderInfo($orderid);
    	$order['allprice']=round($order['myPrice']*$order['seatCount'],2);
    	if(empty($order)||empty($user)||$order['uid']!=$user['id']){
    		$this->redirect('plan/plan');
    	}
    	$cinemaCode = $order['cinemaCode'];
    	$cinema = D('cinema')->find($cinemaCode);
    	$payConfig = getNowPayWay($cinemaCode, $orderid);
    	$nowPayWay = $payConfig['payConfig'];
    	$cinema['payConfig'] = json_decode($cinema['payConfig'], true);
    	$payInfo=$this->getBuyPayway('film',$orderid);
    	$this->assign('payInfo',$payInfo);
    	$this->assign('order',$order);
    	$this->display();
    }

    /**
     * 判断购票是否余额不足
     *
     * @param string $orderid
     * @return mixed
     */
    public function ordersuccess(){
    	$user=$this->user;
    	D('order')->updateOrder($user['id']);
    	$orderid=I('orderid');
    	$mypay=S('paymoney_'.$orderid);
    	$order = getOrderInfo($orderid);
    	$cinemaCode=$order['cinemaCode'];
    	if(!empty($order['status'] )){
    		wlog('该订单状态已改变'.arrayeval($order),'paylog');
    		$this->error('该订单状态已改变');
    	}else{
    		$payType = I('payType');
    		//$payType='alipay';
    		$updateData['payType'] = $payType;
    		$updateData['orderCode'] = $orderid;
    		D('Order')->saveObj($updateData);
    
    		$buyAmount = D('Voucher')->getMyOrderPrice($orderid, $user, $this->wwwInfo['proportion']);

    		$wwwConfig = getNowPayWay($order['cinemaCode'], $orderid);
    		// print_r($wwwConfig);
    		$payConfig = $wwwConfig['payConfig'];
    		$onlinePay = $wwwConfig['onlinePay'];
    		$cinemaName = $wwwConfig['cinemaName'];
    
    		if (isset($buyAmount['content'])) {
    			$this->error($buyAmount['content']);
    		}
    
    
    		$orderInfo = S('getBuyPaywayOrderInfo'. $orderid);
    		if (empty($orderInfo)) {
    			$orderInfo = D('Order')->findObj($orderid);
    			S('getBuyPaywayOrderInfo' . $orderid, $orderInfo, 900);
    		}
    		$otherPayInfo = json_decode($orderInfo['otherPayInfo'], true);
    		if (!empty($otherPayInfo[0]) && !empty($user['cardNum'])){  //兑换券
    			if ($payType == 'account') {
    				// die('立减券不可用会员卡支付');
    				$this->error('兑换券不可用会员卡补差');
    			}
    		}
    		if($buyAmount == 0){
    			if(empty($mypay)){
    				S('paymoney_'.$orderid,1,60);
    				$sign = md5('orderid=' . $orderid . C('singKey'));
    				$url=C('PAY_URL').'order/mobile_app/orderid/' . $orderid . '/sign/' . $sign.'/logpath/mobile_www';
    				getCurlResult($url);
    			}
    			$this->success('跳转等待状态页面');
    		}
    		wlog('buyAmount'.$buyAmount,'test');
//     		$mypay=null;
    		if ($payType == 'account') {	//余额支付
    			if (!empty($user['cardNum'])) {	//会员卡支付
    				if($user['basicBalance']+$user['donateBalance']<$buyAmount){
    					$this->error('会员卡余额不足'.$orderid);
    				}else{
    					if(empty($mypay)){
    						wlog('进入会员卡支付'.$orderid,'test');
    						$sign = md5('orderid=' . $orderid . C('singKey'));
    						$url=C('PAY_URL').'order/account_app/orderid/' . $orderid . '/sign/' . $sign.'/logpath/account_www';
    						getCurlResult($url);
    						S('paymoney_'.$orderid,1,60);
    					}
    					$this->success('跳转等待状态页面');
    				}
    			}else{   //手机余额支付
    				if($user['mmoney'] < $buyAmount){
    					$this->error('余额不足请充值或使用其他支付方式' );
    				}else{
    					if(empty($mypay)){
    						$sign = md5('orderid=' . $orderid . C('singKey'));
    						$url=C('PAY_URL').'order/mobile_app/orderid/' . $orderid . '/sign/' . $sign.'/logpath/mobile_www';
    						wlog('进入手机支付'.$url,'test');
    						S('paymoney_'.$orderid,1,60);
    						// file_get_contents($url);
    						getCurlResult($url);
    					}
    					$this->success('跳转等待状态页面');
    				}
    			}
    		}else{   //支付宝支付
    			$otherPayInfo = json_decode($orderInfo['otherPayInfo'], true);
    			if($otherPayInfo['account']){
    				$buyAmount-=$user['mmoney'];
    			}
    			// $cinemaCode='35012401';
    			$cinema = S('GETCINEMABYCODE' . $cinemaCode);
    			if (empty($cinema)) {
    				$cinema = D('cinema')->find($cinemaCode);
    				S('GETCINEMABYCODE' . $cinemaCode, $cinema, 3600*24*7);
    			}
    			if($this->wwwInfo['isDebug']){
    				$buyAmount = 0.01;
    			}
    			if($payType == 'alipay'){
    				$payConfig=json_decode($cinema['payConfig'],true);
    				$alipayConfig=$payConfig['alipayConfig'];
    				$alipayConfig['partner']=$alipayConfig['partnerId'];
    				$alipayConfig['seller_email']=$alipayConfig['sellerEmail'];
    				$alipayConfig['key']=$alipayConfig['alipayKey'];
    				$alipayConfig['sign_type']=$alipayConfig['signType'];
    				$alipayConfig['input_charset']='utf-8';
    				$alipayConfig['transport']='http';
    				$alipayConfig['cacert']=getcwd().'/cacert.pem';
    				$alipayConfig['pay_way']=='www';
    				$payment_type = "1";
    				$notify_url =C('PAY_URL'). "order/alipay_www";
    				$return_url =C('IMG_URL'). "web/plan/paymentStatus/orderid/".$orderid;
    				$out_trade_no = $orderid;
    				$subject = I('title');
                    $subject = '购票：' . $orderid . '《' . $orderInfo['filmName'] . '》' . $cinema['cinemaName'];
                    $body = '购票：' . $orderid . '《' . $orderInfo['filmName'] . '》' . $cinema['cinemaName'];
    				$show_url = I('show_url');
    				$anti_phishing_key = "";
    				$exter_invoke_ip = "";
    				$parameter = array(
    						"service" => "create_direct_pay_by_user",
    						"partner" => trim($alipayConfig['partner']),
    						"seller_email" => trim($alipayConfig['seller_email']),
    						"payment_type"	=> $payment_type,
    						"notify_url"	=> $notify_url,
    						"return_url"	=> $return_url,
    						"out_trade_no"	=> $out_trade_no,
    						"subject"	=> $subject,
    						"total_fee"	=> $buyAmount,
    						"body"	=> $body,
    						"show_url"	=> $show_url,
    						"anti_phishing_key"	=> $anti_phishing_key,
    						"exter_invoke_ip"	=> $exter_invoke_ip,
    						"_input_charset"	=> trim(strtolower($alipayConfig['input_charset']))
    				);
    				$alipaySubmit = new \Think\Pay\AlipaySubmit ( $alipayConfig );
    				$html_text = $alipaySubmit->buildRequestParaToString ( $parameter, 'get', '确认' );
    				
    				$surl = 'https://mapi.alipay.com/gateway.do?'. $html_text;
    				$data['payType']='alipay';
    				$data['url']=$surl;
    				$this->success('',$data);
    			}elseif ($payType == 'abchinapay') {
    
    				if (!in_array('abchinapay', $onlinePay)) {
    					$this->error('请选择正确的支付方式');
    				}
    
    				$abchinapayConfig = $payConfig['abchinaConfing'];
    				$config['TrustPayConnectMethod'] = 'https';
    				$config['TrustPayServerName'] = 'pay.abchina.com';
    				$config['TrustPayServerPort'] = '443';
    				$config['TrustPayNewLine'] = '2';
    				$config['TrustPayTrxURL'] = '/ebus/trustpay/ReceiveMerchantTrxReqServlet';
    				$config['TrustPayIETrxURL'] = 'https://pay.abchina.com/ebus/ReceiveMerchantIERequestServlet';
    				$config['MerchantErrorURL'] = 'http://127.0.0.1:83/TrustPayClient';
    				$config['TrustPayCertFile'] = ROOR_PATH . $abchinapayConfig['TrustPayCertFile'];
    				$config['MerchantID'] = $abchinapayConfig['MerchantID'];
    				$config['MerchantKeyStoreType'] = '0';
    				$config['MerchantCertFile'] = ROOR_PATH . $abchinapayConfig['MerchantCertFile'];
    				$config['MerchantCertPassword'] = $abchinapayConfig['MerchantCertPassword'];
    				if ($this->weiXinInfo['isDebug'] == 1) {
    					$buyAmount = '0.01';
    				}
    				$tRequest = new \Think\Pay\Abchina\PaymentRequest();
    				$tRequest->order["OrderNo"] = date('YmdHis') . 'N' . $orderid; //设定订单编号
    				$tRequest->order["OrderAmount"] = $buyAmount; //设定交易金额
    
    				$tRequest->order["OrderDesc"] = $cinemaName . '购票，订单号:' . $orderid; //设定订单说明
    				$tRequest->order["OrderDate"] = date('Y/m/d'); //设定订单日期 （必要信息 - YYYY/MM/DD）
    				$tRequest->order["OrderTime"] = date('H:i:s'); //设定订单时间 （必要信息 - HH:MM:SS）
    				$tRequest->order["orderTimeoutDate"] = date('YmdHis', time() + 600); //设定订单有效期
    				//3、生成支付请求对象
    				$tRequest->request["PaymentLinkType"] = '2'; //设定支付接入方式
    
    				$successUrl = C('SERVER_URL') . ',user,paysuccess,' . session('token') ;
    
    				$sign = md5($orderid . $successUrl . C('singKey'));
    
    				$tRequest->request["ResultNotifyURL"] =C('PAY_URL').'order/abchinapay_www/orderId/' . $orderid .'/successUrl/' . $successUrl . '/sign/' . $sign;//设定通知URL地址
    
    				$tResponse = $tRequest->postRequest($config);
    				if ($tResponse->isSuccess()) {
    
    					$PaymentURL = $tResponse->GetValue("PaymentURL");
    					$this->success('',$PaymentURL);
    				}else{
    					$this->error('农行支付订单创建失败！');
    				}
    			}
    		}
    	}
    }
    /**
     * 取消订单
     *
     * @param string $orderid
     * @return int
     */
    public function cancelOrder(){
    	$release=D('order')->cancelOrder(I('orderid'));
    	echo json_encode($release);
    }
	/**
     * 支付成功
     */
	 public function paymentStatus(){
			$this->assign('pageId', 'page-paymentStatus');
			$order=D('order')->findObj(I('orderid'));
			$map=array();
			$map['cinemaCode']=	$order['cinemaCode'];	
			$list=D('goods')->getCinemaGoods($map);
			$this->assign('list',$list);			
			$this->assign('order',$order);
			$this->display();
	}
	/**
	 * 查询影票订单状态
	 */
	function getFilmStatus(){
		$orderid=I('orderid');
		$order=D('order')->findObj($orderid);

		$this->success('',$order);
	}
}