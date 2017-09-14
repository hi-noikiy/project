<?php
namespace Web\Controller;
use Think\Controller;
class GoodsController extends InitController {

    public function index(){
    	$model=D('Goods');
    	$mode2=D('Cinema');
    	$cinema_list=$mode2->cinema_getlist();
    	$this->assign('cinema_list', $cinema_list);   	
    	$map=array();    	
    	
    	$map['cinemaCode']=$cinema_list['0']['cinemaCode'];   	
	
	
    	$list=$model->getCinemaGoods($map);
    	
    	foreach($list as $k=>$v) {
    		
    		$data=array();
    		$data['cinemaCode']=$v['cinemaCode'];
    		$data['getField']='cinemaName';
    		$ret=$mode2->cinema_getlist($data,4);   		
    		$list[$k]['cinemaName']=$ret['cinemaName'];	
    	}
    	
//    	dump($list);
    	$this->assign('list', $list);	
    	
    	
        $this->assign('pageId', 'page-goodsIndex');
        $this->display();
    }
	/**
	 * 生成影院卖品订单
	 */
	function setCinemaOrder(){
		$user=session('ftuser');
		$goods=I('data');
		$mobile=I('mobile');	//接收手机
		if(empty($goods)||empty($mobile)){
			$this->error('参数错误！', '11001');
		}else{
			$data['mobile']=$mobile;
		}
		$user= $this->getBindUserInfo($user);
		$data['uid']=$user['id'];
		if(!empty($user['cardNum'])){
			$data['cardNum']=$user['cardNum'];
		}
		if(!empty($user['mobile'])){
			$data['mobileNum']=$user['mobile'];
		}
		$orderid=D('goods')->setCinemaOrder($data,$goods);
		if($orderid){//开始扣款
			session('ordergoodsid',$orderid);
			$this->success('生成订单成功',$orderid);
		}else{
			$this->error('生成订单失败');
		}
	
	}
	/**
	 * 生成影院卖品订单
	 */
	function loginsetCinemaOrder(){
		$user=session('ftuser');
		$goods=$_COOKIE['oderdata'];
		$mobile=$_COOKIE['mobile'];	//接收手机
		if(empty($goods)||empty($mobile)){
			$this->error('参数错误！', '11001');
		}else{
			$data['mobile']=$mobile;
		}
		$user= $this->getBindUserInfo($user);
		$data['uid']=$user['id'];
		if(!empty($user['cardNum'])){
			$data['cardNum']=$user['cardNum'];
		}
		if(!empty($user['mobile'])){
			$data['mobileNum']=$user['mobile'];
//			$data['mobile']=$user['mobile'];
		}
		$orderid=D('goods')->setCinemaOrder($data,$goods);
		if($orderid){//开始扣款
			session('ordergoodsid',$orderid);
//			$this->success('生成订单成功',$orderid);			
			 $this->redirect('Goods/pay/orderid/'.$orderid);
		}else{
			$this->error('生成订单失败');
		}
	
	}
    
    public function goods_index() {
    	
    	$model=D('Goods');
    	$mode2=D('Cinema');
    	$map=array();    	
    	
    	$map['cinemaCode']=$_REQUEST['cinemaCode'];   	
	
	
    	$list=$model->getCinemaGoods($map);
    	
    	foreach($list as $k=>$v) {
    		
    		$data=array();
    		$data['cinemaCode']=$v['cinemaCode'];
    		$data['getField']='cinemaName';
    		$ret=$mode2->cinema_getlist($data,4);   		
    		$list[$k]['cinemaName']=$ret['cinemaName'];	
    	}
    	
    	echo json_encode($list);
    	
    
    
    
    }
    /**
     * 
     * 卖品支付等待页面
     * 
     */
    public function pay(){
    	$this->assign('list', json_decode($_COOKIE['package_price'],true));
    	$user =$this->user;	
    	$orderid=I('orderid');
		$details=D('orderDetail')->where(array('orderid'=>$orderid))->select();
		$order=D('orderGoods')->where(array('id'=>$orderid))->find();
    	if(empty($order)||empty($user)||$order['uid']!=$user['id']){
    		//$this->redirect('plan/plan');
    	}
//    	dump($order);
//    	$cinemaCode = $order['cinemaCode'];
//    	$cinema = D('cinema')->find($cinemaCode);
//    	$payConfig = getNowPayWay($cinemaCode, $orderid);
//    	$nowPayWay = $payConfig['payConfig'];
//    	$cinema['payConfig'] = json_decode($cinema['payConfig'], true);
    	
    	
    	$payInfo=$this->getBuyPayway('goods',$orderid);
//    	dump($payInfo);
//    	dump($this->payConfig);

    	
    	
    	if($order) {
    		$map=array();
    		$map['orderid']=$order['id'];
    		$ret=D('Goods')->order_detail_getlist($map);
    		if(is_array($ret)){
    			foreach($ret as $k1=>$v1){
    				if(file_exists('./Uploads/'.$v1['goodsImg'])&&$v1['goodsImg']){
    					$ret[$k1]['goodsImg']=C('IMG_URL').'Uploads/'.$v1['goodsImg'];
    				}else{
    					$ret[$k1]['goodsImg']=C('GOODS_IMG_URL');
    				}
    				$ret[$k1]['tolprice']=$v1['price']*$v1['number'];
    			}
    		}
    		$order['order_detail']=$ret;
    	}
    	 
//   	dump($this->user);				
    	$this->assign('payInfo',$payInfo);
    	$this->assign('order',$order);
    	$this->assign('pageId', 'page-pay');
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
    	$orderid=I('orderid');
    	$mypay=S('paymoney_'.$orderid);
    	$order=D('orderGoods')->where(array('id'=>$orderid))->find();
//    	$order = getOrderInfo($orderid);
    	$cinemaCode=$order['cinemaCode'];
    	if(!empty($order['status'] )){
    		wlog('该订单状态已改变'.arrayeval($order),'paylog');
    		$this->error('该订单状态已改变');
    	}else{
    		$payType = I('payType');
    		//$payType='alipay';
    		$updateData=array();
    		$updateData['payType'] = $payType;
    		$updateData['id'] = $orderid;
    		D('Goods')->order_goods_update($updateData);
    
//    		$buyAmount = D('Voucher')->getGoodOrderPrice($orderid, $user, $this->wwwInfo['proportion']);
			$buyAmount = D('Voucher')->getGoodOrderPrice($orderid, $user);

    		$wwwConfig = getNowPayWay($order['cinemaCode'], $orderid);
//    		 print_r($wwwConfig);
    		$payConfig = $wwwConfig['payConfig'];
    		$onlinePay = $wwwConfig['onlinePay'];
    		$cinemaName = $wwwConfig['cinemaName'];
    
    		if (isset($buyAmount['content'])&&is_array($buyAmount)) {
    			$this->error($buyAmount['content']);
    		}
    		
//    		$this->error($buyAmount);
    
    
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
    				$url=C('PAY_URL').'sale/mobile_app/orderid/' . $orderid . '/sign/' . $sign.'/logpath/mobile_www';
    				getCurlResult($url);
    			}
    			$this->success('跳转等待状态页面1');
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
    						$url=C('PAY_URL').'sale/account_app/orderid/' . $orderid . '/sign/' . $sign.'/logpath/account_www';
    						getCurlResult($url);
    						S('paymoney_'.$orderid,1,60);
    					}
    					$this->success('跳转等待状态页面2');
    				}
    			}else{   //手机余额支付
    				if($user['mmoney'] < $buyAmount){
    					$this->error('手机余额不足'.$orderid );
    				}else{
    					if(empty($mypay)){
    						$sign = md5('orderid=' . $orderid . C('singKey'));
    						$url=C('PAY_URL').'sale/mobile_app/orderid/' . $orderid . '/sign/' . $sign.'/logpath/mobile_www';
    						wlog('进入手机支付'.$url,'test');
    						S('paymoney_'.$orderid,1,60);
    						// file_get_contents($url);
    						getCurlResult($url);
    					}
    					$this->success('跳转等待状态页面3');
    				}
    			}
    		}else{   //支付宝支付
    			$otherPayInfo = json_decode($orderInfo['otherPayInfo'], true);
    			if($otherPayInfo['account']){
    				$buyAmount-=$user['mmoney'];
    			}
    			$cinemaCode='35012401';
    			$cinema = S('GETCINEMABYCODE' . $cinemaCode);
    			if (empty($cinema)) {
    				$cinema = D('cinema')->find($cinemaCode);
    				S('GETCINEMABYCODE' . $cinemaCode, $cinema, 3600*24*7);
    			}
    			
    			
    			//商户订单号
				$out_trade_no = time().'goods'.$orderid;//$_POST ['WIDout_trade_no'];
				//商户网站订单系统中唯一订单号，必填
    			if($payType == 'alipay'){
    				$total_fee = $buyAmount;
    				$total_fee = 0.01;//$_POST ['WIDtotal_fee'];
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
    				$notify_url =C('PAY_URL'). "sale/alipay_www";
    				$return_url =C('IMG_URL'). "web/goods/paymentStatus/orderid/".$orderid;
    				$out_trade_no = $out_trade_no;
    				$subject = I('title');
    				$subject = '卖品';
    				$body =I('body');
    				$body = '卖品';
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
    						"total_fee"	=> $total_fee,
    						"body"	=> $body,
    						"show_url"	=> $show_url,
    						"anti_phishing_key"	=> $anti_phishing_key,
    						"exter_invoke_ip"	=> $exter_invoke_ip,
    						"_input_charset"	=> trim(strtolower($alipayConfig['input_charset']))
    				);
    				
//    				$this->error(dump($parameter));
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
    
    
    
    public function paymentStatus(){
    	
    	$order=D('orderGoods')->find(I('orderid'));
//    	dump($order);
		$this->assign('order',$order);
        $this->assign('pageId', 'page-paymentStatus');
        $this->display('paymentStatus');
    }
/**
	 * 查询影票订单状态
	 */
	function getGoodsStatus(){
		$orderid=I('orderid');
		$order=D('orderGoods')->find($orderid);
		$this->success('',$order);
	}

}