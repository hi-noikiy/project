<?php
namespace Api\Controller;
use Think\Controller;

class SaleController extends ServiceController {
	

	/**
	 * 生成影院卖品订单
	 */
	function setCinemaOrder(){
		$goods=$this->param['goods'];
		$mobile=$this->param['mobile'];	//接收手机
		if(empty($goods)||empty($mobile)){
			$this->error('参数错误！', '11001');
		}else{
			$data['mobile']=$mobile;
		}
		$user= $this->getBindUserInfo($this->appInfo['userInfo']);
		$data['uid']=$user['id'];
		if(!empty($user['cardNum'])){
			$data['cardNum']=$user['cardNum'];
		}
		if(!empty($user['mobile'])){
			$data['mobileNum']=$user['mobile'];
		}
		$data['cinemaGroupId']=$user['cinemaGroupId'];
		$orderid=D('goods')->setCinemaOrder($data,$goods);
		if($orderid){//开始扣款
			$this->success('生成订单成功',$orderid);
		}else{
			$this->error('生成订单失败');
		}
		
	}
	
	/**
	 * 我的卖品订单列表
	 */
	function getMyGoods(){
		$user=$this->getBindUserInfo($this->appInfo['userInfo']);
		$page= $this->param['page'];
		if(empty($page)){
			$page=1;
		}
		$start=($page-1)*$this->pageNum;
		$orders=D('goods')->getMyGoods($user['id'],$start,$this->pageNum);
		if(empty($orders)){
			$this->success('');
		}
		$this->success('',$orders);
	}
	
	/**
	 * 我的周边订单列表
	 */
	function getMyRound(){
		$user=$this->getBindUserInfo($this->appInfo['userInfo']);
		$page= $this->param['page'];
		if(empty($page)){
			$page=1;
		}
		$start=($page-1)*$this->pageNum;
		$orders=D('goods')->getMyRound($user['id'],$start,$this->pageNum);
		if(empty($orders)){
			$this->success('');
		}
		$this->success('',$orders);
	}
	
	/**
	 * 查询卖品订单状态
	 */
	function getGoodsStatus(){
		$orderid=$this->param['orderid'];
		$order=D('orderGoods')->find($orderid);
		$this->success('',$order);
	}
	
	/**
	 * 查询周边卖品订单状态
	 */
	function getRoundStatus(){
		$orderid=$this->param['orderid'];
		$order=D('goods')->getRoundStatus($orderid);
		$this->success('',$order);
	}
	
	/**
	 * 支付影院卖品订单
	 */
	function pay(){
		$data['id']=$orderid=$this->param['orderid'];
		if(empty($orderid)){
			$this->error('参数错误！', '11001');
		}
		$hasgoods=S('goods'.$orderid);
		$ctime=1;
		if(!empty($hasgoods)){
			$this->error('同物品订单间隔至少'.$ctime.'秒');
		}
		$order=D('orderGoods')->find($orderid);
		if(empty($order)){
			$this->error('该订单号无效');
		}
		if(!empty($order['status'])){
			$this->error('该订单状态已经改变'.$order['status']);
		}
		$user=D('member')->find($order['uid']);
		if(empty($user)){
			$this->error('无订单所属人信息');
		}
		$payType=$this->param['payType'];  //选择支付类型
		$buyAmount = D('Voucher')->getGoodOrderPrice($orderid, $user);
		S('goods'.$orderid,1,$ctime);
		$cinemaInfo = D('Cinema')->getCinemaInfo('cinemaName, alipayConfig, weixinpayConfig, unionpayConfig', array('cinemaCode' =>$order['cinemaCode']));
		if(empty($buyAmount)||$payType=='account'){
			getCurlResult(C('PAY_URL').'sale/mobile_app/orderid/'.$orderid.'/logpath/mobile_app');
			$this->success('',$orderid);
		}elseif ($payType == 'alipay') {//支付宝支付
			$alipayConfig = json_decode($cinemaInfo['alipayConfig'], true);
			// print_r($alipayConfig);
			$buyAmount=0.01;
			$msg['alipay'] = array(
					'notifyUrl'      => C('PAY_URL').'sale/alipay_app.html',
					'PartnerID'      => $alipayConfig['partnerId'],
					'SellerID'       => $alipayConfig['sellerEmail'],
					'Md5Key'         =>'',
					'PartnerPrivKey' => getKeyInfo($alipayConfig['privateKey'], 27),
					'AlipayPubKey'   => getKeyInfo($alipayConfig['publicKey'],26),
					'outTradeNo'     => time().$orderid,
					"subject"        => $cinemaInfo['cinemaName'] . ' 会员APP购买影城卖品',
					"totalFee"       => $buyAmount,
					"body"           => $cinemaInfo['cinemaName'] . ' 会员APP购买影城卖品' . $buyAmount . '元',
					"showUrl"        => 'http://wap.zrfilm.com',
			);
			$this->success('创建支付订单成功', $msg);
				
		}elseif($payType == 'weixinpay'){   //微信支付
			$weixinpayConfig = json_decode($cinemaInfo['weixinpayConfig'], true);
			$buyAmount=0.01;
			// print_r($weixinpayConfig);
			$orderno = $orderid;
			$jsApi = new \Org\Wechat\Wxjspay($weixinpayConfig);
			//统一支付接口类
			$unifiedOrder = new \Org\Wechat\UnifiedOrder($weixinpayConfig);
			/*-----------------------------必填--------------------------*/
			$unifiedOrder->setParameter('body', $cinemaInfo['cinemaName'] . ' 注册会员APP购票');//商品描述岚樨微支付平台
			$unifiedOrder->setParameter('out_trade_no', date('YmdHis') . $orderno);//商户订单号
			$unifiedOrder->setParameter('total_fee', $buyAmount * 100);//总金额（微信支付以人民币“分”为单位）
			/*-------------------------------------------------------*/
			$unifiedOrder->setParameter('notify_url', C('PAY_URL').'sale/weixinpay_app.html');//通知地址
			$unifiedOrder->setParameter('trade_type', 'APP');//交易类型
			$unifiedOrder->setParameter('spbill_create_ip', $_SERVER['REMOTE_ADDR']);//交易IP
				
			$weixinPayInfo = $unifiedOrder->getPayInfo();
				
			// print_r($weixinPayInfo);
			
				if ($weixinPayInfo['return_code'] == 'FAIL') {
					$this->error($weixinPayInfo['return_msg']);
				}

    			$msg['weixinpay'] = array(
    					'appid'     => $weixinPayInfo['appid'],
    					'partnerid' => $weixinPayInfo['mch_id'],
    					'prepayid'  => $weixinPayInfo['prepay_id'],
    					'package'   => 'Sign=WXPay',
    					'noncestr'  => $weixinPayInfo['nonce_str'],
    					'timestamp' => time()
    			);

                $msg['weixinpay']['sign'] = $unifiedOrder->getSign($msg['weixinpay']);
					
				$this->success('创建支付订单成功', $msg);
				
		}elseif($payType == 'unionpay'){   //银联
			$buyAmount=0.01;   //测试1分购票
			$unionpayConfig = json_decode($cinemaInfo['unionpayConfig'], true);
			$orderTitle =  $cinemaInfo['cinemaName'] . ' 注册会员APP购票';
			$unionPayKey = $unionpayConfig['unionPayKey'];
			$unionPayId = $unionpayConfig['unionPayId'];
			$conf = array(
					"version"          => '1.0.0',
					"charset"          => 'UTF-8',
					"transType"        => '01',
					"merId"            => $unionPayId,
					"frontEndUrl"      =>  'http://wap.zrfilm.com/',
					'backEndUrl'       =>C('PAY_URL').'sale/unionpay_app.html',
					"orderTime"        => date('YmdHis'),
					"orderTimeout"     => date('YmdHis', time() + 20 * 60),
					"orderNumber"      => date('YmdHis') . 'N' . $orderid,
					"orderAmount"      => round($buyAmount * 100, 2),
					"orderCurrency"    => 156,
					"orderDescription" => $orderTitle,
			);
			$conf['signature'] = unionSign($conf, $unionPayKey);
			$conf['signMethod'] = 'MD5';
			$msg = getHttpResponsePOST('https://mgate.unionpay.com/gateway/merchant/trade', array(CURLOPT_HTTPHEADER => array('Expect:'), CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false), $conf) ;
			parse_str($msg, $msg);
				
			if ( !empty($msg) && isset($msg['respCode']) && isset($msg['tn']) && isset($msg['signature']) && $msg['respCode'] == '00' && $msg['signature'] == unionSign($msg, $unionPayKey) ) {
				$data['unionpay']['tn'] = $msg['tn'];
				$this->success('创建支付订单成功', $data);
			}
			$this->error('生成订单失败');
		}
	}
	
	/**
	 * 生成影院周边卖品订单
	 */
	function setRoundOrder(){
		$user= $this->getBindUserInfo($this->appInfo['userInfo']);
		$data['uid']=$user['id'];
		if(!empty($user['cardNum'])){
			$data['cardNum']=$user['cardNum'];
		}
		if(!empty($user['mobile'])){
			$data['mobileNum']=$user['mobile'];
		}
		$goodsId=$this->param['goodsId'];
		$number=$this->param['number'];
		$mobile=$this->param['mobile'];
		$payType=$this->param['payType'];  //选择支付类型
		if(empty($goodsId)||empty($number)||empty($mobile)||empty($payType)){
			$this->error('参数错误！', '11001');
		}else{
			$data['goodsId']=$goodsId;
			$data['number']=$number;
			$data['mobile']=$mobile;
			$data['payType']=$payType;
		}
		$orderid=D('goods')->setRoundOrder($data);
		if($orderid){//开始扣款
			$goods=D('goodsRound')->find($goodsId);
			$order=D('orderRound')->find($orderid);
			$buyAmount=$order['otherpay'];
			$cinemaInfo = D('Cinema')->getCinemaInfo('cinemaName, alipayConfig, weixinpayConfig, unionpayConfig', array('cinemaCode' =>$order['cinemaCode']));
			if ($payType == 'alipay') {//支付宝支付
				$alipayConfig = json_decode($cinemaInfo['alipayConfig'], true);
				// print_r($alipayConfig);
				$buyAmount=0.01;
				$msg['alipay'] = array(
						'notifyUrl'      => C('PAY_URL').'saleround/alipay_app.html',
						'PartnerID'      => $alipayConfig['partnerId'],
						'SellerID'       => $alipayConfig['sellerEmail'],
						'Md5Key'         =>'',
						'PartnerPrivKey' => getKeyInfo($alipayConfig['privateKey'], 27),
						'AlipayPubKey'   => getKeyInfo($alipayConfig['publicKey'],26),
						'outTradeNo'     => time().'round'.$orderid,
						"subject"        => $cinemaInfo['cinemaName'] . ' 会员APP购买影城卖品',
						"totalFee"       => $buyAmount,
						"body"           => $cinemaInfo['cinemaName'] . ' 会员APP购买影城卖品' . $buyAmount . '元',
						"showUrl"        => 'http://wap.zrfilm.com',
				);
				$msg['alipay']['orderid'] = $orderid;
				$this->success('创建支付订单成功', $msg);
					
			}elseif($payType == 'weixinpay'){   //微信支付
				$weixinpayConfig = json_decode($cinemaInfo['weixinpayConfig'], true);
				$buyAmount=0.01;
				// print_r($weixinpayConfig);
				$orderno = $orderid;
				$jsApi = new \Org\Wechat\Wxjspay($weixinpayConfig);
				//统一支付接口类
				$unifiedOrder = new \Org\Wechat\UnifiedOrder($weixinpayConfig);
				/*-----------------------------必填--------------------------*/
				$unifiedOrder->setParameter('body', $cinemaInfo['cinemaName'] . ' 会员APP购物');//商品描述岚樨微支付平台
				$unifiedOrder->setParameter('out_trade_no', date('YmdHis') .$orderno);//商户订单号
				$unifiedOrder->setParameter('total_fee', $buyAmount * 100);//总金额（微信支付以人民币“分”为单位）
				/*-------------------------------------------------------*/
				$unifiedOrder->setParameter('notify_url', C('PAY_URL').'saleround/weixinpay_app.html');//通知地址
				$unifiedOrder->setParameter('trade_type', 'APP');//交易类型
				$unifiedOrder->setParameter('spbill_create_ip', $_SERVER['REMOTE_ADDR']);//交易IP
					
				$weixinPayInfo = $unifiedOrder->getPayInfo();
				if ($weixinPayInfo['return_code'] == 'FAIL') {
					$this->error($weixinPayInfo['return_msg']);
				}

    			$msg['weixinpay'] = array(
    					'appid'     => $weixinPayInfo['appid'],
    					'partnerid' => $weixinPayInfo['mch_id'],
    					'prepayid'  => $weixinPayInfo['prepay_id'],
    					'package'   => 'Sign=WXPay',
    					'noncestr'  => $weixinPayInfo['nonce_str'],
    					'timestamp' => time()
    			);

                $msg['weixinpay']['sign'] = $unifiedOrder->getSign($msg['weixinpay']);
				$msg['weixinpay']['orderid'] = $orderid;
					
				$this->success('创建支付订单成功', $msg);
					
			}elseif($payType == 'unionpay'){   //银联
				$buyAmount=0.01;   //测试1分购票
				$unionpayConfig = json_decode($cinemaInfo['unionpayConfig'], true);
				$orderTitle =  $cinemaInfo['cinemaName'] . ' 注册会员APP购票';
				$unionPayKey = $unionpayConfig['unionPayKey'];
				$unionPayId = $unionpayConfig['unionPayId'];
				$conf = array(
						"version"          => '1.0.0',
						"charset"          => 'UTF-8',
						"transType"        => '01',
						"merId"            => $unionPayId,
						"frontEndUrl"      =>  'http://wap.zrfilm.com/',
						'backEndUrl'       =>C('PAY_URL').'sale/unionpay_app.html',
						"orderTime"        => date('YmdHis'),
						"orderTimeout"     => date('YmdHis', time() + 20 * 60),
						"orderNumber"      => date('YmdHis') . 'N' . $orderid,
						"orderAmount"      => round($buyAmount * 100, 2),
						"orderCurrency"    => 156,
						"orderDescription" => $orderTitle,
				);
				$conf['signature'] = unionSign($conf, $unionPayKey);
				$conf['signMethod'] = 'MD5';
				$msg = getHttpResponsePOST('https://mgate.unionpay.com/gateway/merchant/trade', array(CURLOPT_HTTPHEADER => array('Expect:'), CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false), $conf) ;
				parse_str($msg, $msg);
					
				if ( !empty($msg) && isset($msg['respCode']) && isset($msg['tn']) && isset($msg['signature']) && $msg['respCode'] == '00' && $msg['signature'] == unionSign($msg, $unionPayKey) ) {
					$data['unionpay']['tn'] = $msg['tn'];
					$data['unionpay']['orderid']=$orderid;
					$this->success('创建支付订单成功', $data);
				}
				$this->error('创建支付订单失败',$orderid);
			}
		}else{
			$this->error('生成订单失败');
		}
	}
}