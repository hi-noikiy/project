<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends InitController {
	/**
	 * 退票中心
	 */
	public function returnticket(){
		$weeks=array('星期日','星期一','星期二','星期三','星期四','星期五','星期六');
       $user = $this->getBindUserInfo(session('ftuser'));
		$page= I('page');
		if(empty($page)){
			$page=1;
		}
		$start=($page-1)*$this->pageNum;
		$map['uid']=$user['id'];
		$map['status']=3;
		$map['startTime']=array('egt',time()+7200);
		$orders=D('order')->getList('status,orderCode,myPrice,seatCount,filmNo,lockTime,status,printNo,verifyCode,filmName,startTime,seatIntroduce,copyType,downTime,hallName,cinemaName,orderTime,mobile,submitPrice',$map,$start,$this->pageNum);
		foreach ($orders as $k=>$v){
			$film[$k]=D('film')->getFilm(array('filmNo'=>$v['filmNo']));
			$orders[$k]['filmImg']=$film[$k]['image'];
			switch ((strtotime(date('Ymd',$v['startTime']))-strtotime(date('Ymd',time())))/(24*60*60)){
				case 0:$orders[$k]['week']='今天';break;
				case 1:$orders[$k]['week']='明天';break;
				case 2:$orders[$k]['week']='后天';break;
				default:$orders[$k]['week']=$weeks[date('w',$v['startTime'])];
			}
			$orders[$k]['week'].=date('m-d H:i',$v['startTime']);
			$orders[$k]['qrcode']=substr(C('IMG_URL'), 0,-1). U('index/getQRcode', array('orderid'=>$v['orderCode'],'code' => $v['printNo'] ));
		}
		$this->assign('orders',$orders);
		$this->display();
    }
    /**
     * 取消订单
     *
     * @param string $orderid
     * @return void
     */
    function backTicket(){
    	$orderid=I('orderid');
    	$backTip=I('backTip');
    	$result=D('Order')->backTicket($orderid,$backTip);
    	if ($result['ResultCode'] == 0) {
    		$this->success('退票成功！');
    	}else{
    		if(!empty($result['Message'])){
    			$this->error($result['Message']);
    		}else{
    			$this->error(getMtxError($result['ResultCode']));
    		}
    	}
    }
	
	/**
	 * 历史券包
	 */
	function packagerecord(){
		
		$userInfo = $this->getBindUserInfo(session('ftuser'));
		$map['memberId'] = $userInfo['id'];
		$map['_string'] = 'validData < ' . strtotime(date('Y-m-d')) . ' or isUnlock=1 or isUse=1';
		$memberVoucherList = D('Member')->getMemberVoucherList('typeId, voucherName, voucherNum, typeClass, voucherValue, createdDatetime, validData, isUse, isUnlock', $map);
		
		foreach ($memberVoucherList as $key => $value) {
			if ($value['validData'] < strtotime(date('Y-m-d')) ) {
				$remarks = '已过期';
			}elseif ($value['isUnlock'] == 1) {
				$remarks = '已解锁';
			}elseif ($value['isUse'] == 1) {
				$remarks = '已使用';
			}
			$memberVoucherList[$key]['remarks'] = $remarks;
			unset($memberVoucherList[$key]['isUnlock'], $memberVoucherList[$key]['isUse']);
		}
		$this->assign('memberVoucherList', $memberVoucherList);
		$this->display();
	}
	
	/**
	 * 影票订单
	 */
	function orderfilmlist(){
		
		$this->display();
	}
	
	/**
	 * 商品订单
	 */
	function ordergoodslist(){
		$user=session('ftuser');
		$user=$this->getBindUserInfo($user);
		$page= I('page');
		if(empty($page)){
			$page=1;
		}
		$start=($page-1)*$this->pageNum;
		$goodsorders=D('goods')->getMyGoods($user['id'],$start,$this->pageNum);
		$roundorders=D('goods')->getMyRound($user['id'],$start,$this->pageNum);
		$this->assign('goodsorders',$goodsorders);
		$this->assign('roundorders',$roundorders);
		$this->display();
	}
	
	/**
	 * 充值页面
	 *
	 * @param string $code
	 * @see Wxjspay::createOauthUrlForCode()
	 * @see Wxjspay::setCode()
	 * @see Wxjspay::getOpenId()
	 * @see Wxjspay::createNoncestr()
	 */
	public function recharge() {
		$user=session('ftuser');
		$user=$this->getBindUserInfo($user);
		if(!empty($user['cardNum'])){
			$cinemaCode=$user['businessCode'];
		}else{
			$cinemaCode='35012401';
		}
		$user['levelName']=$this->cardNames[$user['memberGroupId']];
		
		$cinema=D('cinema')->find($cinemaCode);
		$payConfig=json_decode($cinema['payConfig'],true);
		$wxConfig=$payConfig['weixinpayConfigWap'];
		if(!empty($wxConfig['appid'])){
			$openid=session('openid'.$cinemaCode);
			if(empty($openid)){
				$openid=cookie('openid'.$cinemaCode);
			}
			$jsApi = new \Org\Wechat\Wxjspay($wxConfig);
			$jsurl=strtolower('http://' . $_SERVER['HTTP_HOST'] . U('',I('request.')));
			//$jsurl='http://testapi.zmaxfilm.com'. U('');
			if (empty($openid)&&empty($_GET['code'])){
				$url = $jsApi->createOauthUrlForCode(urlencode($jsurl));
				Header("Location: $url");
				die();
	
			}elseif(empty($openid)){
				$code = $_GET['code'];
				$jsApi->setCode($code);
				$openid = $jsApi->getOpenId();
				session('openid'.$cinemaCode,$openid);
				cookie('openid'.$cinemaCode,$openid,3600);
			}else{
				cookie('openid'.$cinemaCode,$openid,3600);
				session('openid'.$cinemaCode,$openid);
			}
	
			$data['appid']=$wxConfig['appid'];
			$data['timestamp']=$timestamp=time();
			$data['wxnonceStr']=$wxnonceStr = $jsApi->createNoncestr();
			$wxticket=wx_get_jsapi_ticket($wxConfig['appid'],$wxConfig['appsecret']);
			$wxOri = 'jsapi_ticket=' . $wxticket.'&noncestr='.$wxnonceStr.'&timestamp='.$timestamp.'&url='.$jsurl;
			$data['wxSha1']=$wxSha1 = sha1($wxOri);
		}
		$payInfo=$this->getRechargePayway($cinemaCode);
		$this->assign('payInfo',$payInfo);
		$this->assign('data',$data);
		$this->assign('user',$user);
		$this->display(); //渲染支付页面
	}
	
	/**
	 * 微信充值
	 * 主方法
	 * @param [get] [orderno] [订单编号]
	 */
	public function main() {
		$payType=I('payType');
		$user=session('ftuser');
		$user=$this->getBindUserInfo($user);
		//获取用户订单号（非必须）
		$fee=I('fee');
		$fee=0.01;
		//H5网页端调起支付接口
		if(!empty($user['cardNum'])){
			$cinemaCode=$user['businessCode'];
		}else{
			$cinemaCode='35012401';
		}
		
		$cinemaInfo = D('Cinema')->getCinemaInfo('cinemaName, payConfig', array('cinemaCode' => $cinemaCode));
    	$topUparr=array(
    			'uid'=>$user['id'],
    			'cinemaCode'=>$cinemaCode,
    			'cinemaName'=>$cinemaInfo['cinemaName'],
    			'money'=>$fee,
    			'createTime'=>time(),
    			'way'=>'wap',
    			'cinemaGroupId'=>$this->weiXinInfo['cinemaGroupId'],
    	);
    	if(!empty($user['cardNum'])){
    		$topUparr['cardId']=$user['cardNum'];
    	}else{
    		$topUparr['mobile']=$user['mobile'];
    	}
    	$payConfig=json_decode($cinemaInfo['payConfig'],true);
    	if($payType == 'weixinpay'){   //微信支付
    		$wxConfig=$payConfig['weixinpayConfigWap'];
    		$topUparr['mchId']=$wxConfig['mchid'];
    		$topUparr['type']='weixinpay';
		}
        $orderno=D('orderRecharge')->add($topUparr);
		if($orderno){
			wlog('添加充值记录：'.arrayeval($topUparr),'wxpay');
			$jsApi = new \Org\Wechat\Wxjspay($wxConfig);
			//统一支付接口类
			$unifiedOrder = new \Org\Wechat\UnifiedOrder($wxConfig);
			$openid=session('openid'.$cinemaCode);
			/*-----------------------------必填--------------------------*/
			$unifiedOrder->setParameter('body', 'WAP充值' . $fee);//商品描述岚樨微支付平台
			$unifiedOrder->setParameter('out_trade_no', date('YmdHis') . $orderno);//商户订单号
			$unifiedOrder->setParameter('total_fee', $fee * 100);//总金额（微信支付以人民币“分”为单位）
			/*-------------------------------------------------------*/
			$unifiedOrder->setParameter('openid', $openid);//获取到的OPENID
			$unifiedOrder->setParameter('notify_url', C('PAY_URL').'home/recharge/weixinpay_wap.html');//通知地址
			$unifiedOrder->setParameter('trade_type', 'JSAPI');//交易类型
			 
			$prepay_id = $unifiedOrder->getPrepayId();
			//=========步骤3：使用jsapi调起支付============
			$jsApi->setPrepayId($prepay_id);
			$jsApiParameters = $jsApi->getParameters();
			$this->jsApiParameters = $jsApiParameters;
			echo json_encode($this->jsApiParameters);
		}else{
			wlog('添加充值记录失败：'.arrayeval($topUparr),'wxpay');
		}
	
	}
	
	/**
	 * 券包规则
	 */
	function voucherrule(){
		$this->assign('rule',$this->weiXinInfo['voucherRule']);
		$this->display();
	}
	
	/**
	 * 5.0.2获取用户券包
	 */
	public function package(){
		$this->display();
	}
	/**
	 * 取票密码
	 */
	public function code(){
		$codes=D('order')->getCodes();
		$this->assign('codes',$codes);
		$this->display();
	}

	/**
	 * 反馈
	 */
	public function feedback(){
		if(IS_AJAX && I('request.action') == 'setFeedback'){
			$data['uid']=UID;
			$data['cinemaGroupId']=$this->weiXinInfo['cinemaGroupId'];
			$data['content']=I('content');
			$data['puid']=$user['id'];
			$data['time']=time();
			if(D('feedback')->add($data)){
				echo '1';
			}else{
				echo '0';
			}
		}else{
			$feedbacks=D('feedback')->getList(array('puid'=>UID,'cinemaGroupId'=>$this->weiXinInfo['cinemaGroupId']));
			$this->assign('feedbacks',$feedbacks);
			$this->display();
		}
	}
	/**
	 * 时时反馈
	 */
	function fbajax(){
		$feedbacks=D('feedback')->getList(array('uid'=>UID,'cinemaGroupId'=>$this->weiXinInfo['cinemaGroupId']));
		echo json_encode($feedbacks);
	}

	/**
	 * 显示订单号并发送短信页面
	 *
	 * @param string $orderid
	 */
	public function paysuccess(){
		$order=D('Order')->findObj(I('orderid'));
		$user=D('Member')->getUser(array('id'=>$order['uid']));
		$this->assign('order',$order);
		$this->assign('user',$user);
		$this->display();
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
	 * 选座后确认页面
	 */
	public function indent(){
		$featureAppNo=I('featureAppNo');
		$user=session('ftuser');
		$mobileUser=$this->getBindCardInfo($user);
		$this->assign('mobile',$mobileUser['mobile']);
		$user=$this->getBindUserInfo($user);
		$user['levelName']=$this->cardNames[$user['memberGroupId']];
		$plan=D('Plan')->getPlan($featureAppNo,$user['memberGroupId']);

		$plan['orderstatus']=D('order')->updateOrder($user['id']);
		if(I('request.datas')){
			$data=preg_replace("/\|/",'#', I('request.datas'));
		}else{
			$data=preg_replace("/\|/",'#', session('datas'));
		}
		
		$datas=explode(',',$data);
		$str='';
		foreach ($datas as $value) {
			$seatinfo=explode('.',$value);
			$str.=$seatinfo[1].'    ';
			$seatno=$seatinfo[0];
		}
		$fead=D('seat')->findSectionId(array('cinemaCode'=>$plan['cinemaCode'],'hallNo'=>$plan['hallNo'],'seatCode'=>$seatno));
		$feadprice=D('cinema')->getseatprice(array('cinemaCode'=>$plan['cinemaCode'],'hallNo'=>$plan['hallNo']),$fead['findSectionId']);
		$plan['memberPrice']+=$feadprice;
		$plan['seatstr']=$str;
		$plan['seatcount']=count($datas);
		$scount=D('Order')->seatcount($user['id'],$featureAppNo);		
		$s=4;
		if($s<$plan['seatcount']+$scount){
			$plan['hsc']=$s-$scount;
		}else{
			$plan['hsc']='ok';
		}
		$plan['num']=count($datas)*$plan['memberPrice'];
		$this->assign('feadprice',$feadprice);
		$this->assign('plan',$plan);
		$this->assign('user',$user);
		$this->assign('mydata',$data);
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
		$datas=I('datas');
		$user=session('ftuser');
		$user=$this->getBindUserInfo($user);
		$plan=D('Plan')->getplan($featureAppNo,$user['memberGroupId']);
		$cinema=D('cinema')->find($plan['cinemaCode']);
		if(!empty($user['cardNum'])){
			$tflag=D('cinema')->isInCinemas($plan['cinemaCode'],$user['businessCode']);
			if($tflag=='1'){
				$this->error('该会员卡无法购买此影院影票');
			}
		}
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
				// print_r($fead);
			}else{
				$ofead = D('seat')->findSectionId(array('cinemaCode'=>$plan['cinemaCode'],'hallNo'=>$plan['hallNo'],'seatCode'=>$v['seatNo']));
				// print_r($ofead);
				if($ofead['findSectionId']!=$fead['findSectionId']){
					$this->error('座区不一样');
				}
			}
		}
		$priceConfig=json_decode($hall['price'],true);
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
			$orderid=D('Order')->addObj($lock,$plan,$user,$str,$mobile,json_encode($seats),$fead);
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
		$user = session('ftuser');
		$userInfo = $this->getBindUserInfo($user);
		$orderid = session('filmorderid');
		$order = getOrderInfo($orderid);

		$cinemaCode = $order['cinemaCode'];

		$cinema = D('cinema')->find($cinemaCode);

		$payConfig = getNowPayWay($cinemaCode, $orderid);
		$nowPayWay = $payConfig['payConfig'];

		$cinema['payConfig'] = json_decode($cinema['payConfig'], true);
		if ($nowPayWay['weixinpayConfigWap']) {		

			$wxConfig = $cinema['payConfig']['weixinpayConfigWap'];

			if(!empty($wxConfig['appid'])){
				$openid=session('openid'.$cinemaCode);
				if(empty($openid)){
					$openid=cookie('openid'.$cinemaCode);
				}
				$jsApi = new \Org\Wechat\Wxjspay($wxConfig);
				$jsurl=strtolower('http://' . $_SERVER['HTTP_HOST'] . U('',I('request.')));
				
				if (empty($openid) && empty($_GET['code'])){
					$url = $jsApi->createOauthUrlForCode(urlencode($jsurl));
					Header("Location: $url");
					die();
			
				}elseif(empty($openid)){
					$code = $_GET['code'];
					$jsApi->setCode($code);
					$openid = $jsApi->getOpenId();
					session('openid'.$cinemaCode,$openid);
					cookie('openid'.$cinemaCode,$openid,3600);
				}else{
					cookie('openid'.$cinemaCode,$openid,3600);
					session('openid'.$cinemaCode,$openid);
				}
			
				$data['appid']=$wxConfig['appid'];
				$data['timestamp']=$timestamp=time();
				$data['wxnonceStr']=$wxnonceStr = $jsApi->createNoncestr();
				$wxticket=wx_get_jsapi_ticket($wxConfig['appid'],$wxConfig['appsecret']);
				$wxOri = 'jsapi_ticket=' . $wxticket.'&noncestr='.$wxnonceStr.'&timestamp='.$timestamp.'&url='.$jsurl;
				$data['wxSha1']=$wxSha1 = sha1($wxOri);
			}
		}
		$this->assign('data',$data);
		$payInfo=$this->getBuyPayway('film',$orderid);
		$this->assign('payInfo',$payInfo);
		$this->assign('user',$userInfo);
		$this->assign('order',$order);
		$this->display();
	}

	public function getBuyPaywayJson()
	{
		$orderId = I('request.orderId');
		$type = I('request.type');
		
		$buyPayway = $this->getBuyPayway($type,$orderId);
		$this->success('', $buyPayway);
		// print_r($buyPayway);
	}


	/**
	 * 判断购票是否余额不足
	 *
	 * @param string $orderid
	 * @return mixed
	 */
	public function ordersuccess1(){
		$user=session('ftuser');
		$user=$this->getBindUserInfo($user);
		D('order')->updateOrder($user['id']);
		$orderid=I('orderid');
		$mypay=S('paymoney_'.$orderid);
		$order = getOrderInfo($orderid);
		$cinemaCode=$order['cinemaCode'];
		if(!empty($order['status'] )){
			wlog('该订单状态已改变'.arrayeval($order),'paylog');
			$this->error('该订单状态已改变');
		}else{
			$cinemaInfo = D('Cinema')->getCinemaInfo('cinemaName, payConfig', array('cinemaCode' => $order['cinemaCode']));
            $payType = I('payType');

            $updateData['payType'] = $payType;
			$updateData['orderCode'] = $orderid;
			D('Order')->saveObj($updateData);

            $buyAmount = D('Voucher')->getMyOrderPrice($orderid, $user, $this->weiXinInfo['proportion']);

            $weiXingConfig = getNowPayWay($order['cinemaCode'], $orderid);

            // print_r($weiXingConfig);
			$payConfig = $weiXingConfig['payConfig'];
			$onlinePay = $weiXingConfig['onlinePay'];
			$cinemaName = $weiXingConfig['cinemaName'];

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
            		$sign = md5('orderId=' . $orderid . C('singKey'));
            		$url=C('PAY_URL').'order/mobile_wap/orderId/' . $orderid . '/sign/' . $sign;
            		getCurlResult($url);
            	}
            	$this->success('跳转等待状态页面', U('paysuccess', array('orderid'=>$orderid)));
            }
            wlog('$buyAmount'.$buyAmount,'test');
            wlog('$user'.arrayeval($user),'test');
			if ($payType == 'account') {	//余额支付
				if (!empty($user['cardNum'])) {	//会员卡支付
					if($user['basicBalance']+$user['donateBalance']<$buyAmount){
						$this->error('会员卡余额不足'.$orderid);
					}else{
						$mypay=null;
						if(empty($mypay)){
							wlog('进入会员卡支付'.$orderid,'test');
                            $sign = md5('orderid=' . $orderid . C('singKey'));
							$url=C('PAY_URL').'order/account_app/orderid/' . $orderid . '/sign/' . $sign.'/logpath/account_wap';
                            getCurlResult($url);
                            S('paymoney_'.$orderid,1,60);
						}
						$this->success('跳转等待状态页面', U('paysuccess', array('orderid'=>$orderid)));
					}
				}else{   //手机余额支付
					if($user['mmoney'] < $buyAmount){
						$this->error('手机余额不足'.$orderid );
					}else{
						if(empty($mypay)){
                            $sign = md5('orderid=' . $orderid . C('singKey'));
							$url=C('PAY_URL').'order/mobile_app/orderid/' . $orderid . '/sign/' . $sign.'/logpath/mobile_wap';
							S('paymoney_'.$orderid,1,60);
                            // file_get_contents($url);
                            getCurlResult($url);
						}
						$this->success('跳转等待状态页面');
					}
				}
			}else{   //微信支付
				$payConfig=json_decode($cinemaInfo['payConfig'],true);
				$otherPayInfo = json_decode($orderInfo['otherPayInfo'], true);
				if($otherPayInfo['account']){
					$buyAmount-=$user['mmoney'];
				}
				if($payType == 'weixinpay'){
					$fee=$buyAmount=0.01;
					$orderno=$orderid;
					$wxConfig=$payConfig['weixinpayConfigWap'];
					$jsApi = new \Org\Wechat\Wxjspay($wxConfig);
					//统一支付接口类
					$unifiedOrder = new \Org\Wechat\UnifiedOrder($wxConfig);
					$openid=session('openid'.$cinemaCode);
					/*-----------------------------必填--------------------------*/
					$unifiedOrder->setParameter('body', 'WAP购票' . $fee);//商品描述岚樨微支付平台
					$unifiedOrder->setParameter('out_trade_no', $orderno);//商户订单号
					$unifiedOrder->setParameter('total_fee', $fee * 100);//总金额（微信支付以人民币“分”为单位）
					/*-------------------------------------------------------*/
					$unifiedOrder->setParameter('openid', $openid);//获取到的OPENID
					$unifiedOrder->setParameter('notify_url', C('PAY_URL').'home/order/weixinpay_wap.html');//通知地址
					$unifiedOrder->setParameter('trade_type', 'JSAPI');//交易类型
					
					$prepay_id = $unifiedOrder->getPrepayId();
					//=========步骤3：使用jsapi调起支付============
					$jsApi->setPrepayId($prepay_id);
					$jsApiParameters = $jsApi->getParameters();
					$this->jsApiParameters = $jsApiParameters;
					echo json_encode($this->jsApiParameters);
				}elseif ($payType == 'abchinapay') {

					if (!in_array('abchinapay', $onlinePay)) {
						$this->error('请选择正确的支付方式');
					}

					$abchinapayConfig = $payConfig['abchinaConfing'];
					$config['TrustPayConnectMethod'] = 'https';
					$config['TrustPayServerName'] = 'pay.abchina.com';
					$config['TrustPayServerPort'] = '443';
					$config['TrustPayNewLine'] = '2';
					$config['PaymentType'] = '1';
					$config['NotifyType'] = '1';
					$config['ExpiredDate'] = '60';
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
					$OrderNo = date('YmdHis') . 'N' . $orderid;
					$tRequest = new \Think\Pay\Abchina\PaymentRequest();
					$tRequest->order["OrderNo"] = $OrderNo; //设定订单编号
					$tRequest->order["OrderAmount"] = $buyAmount; //设定交易金额

					$tRequest->order["OrderDesc"] = $cinemaName . '购票，订单号:' . $orderid; //设定订单说明
					$tRequest->order["OrderDate"] = date('Y/m/d'); //设定订单日期 （必要信息 - YYYY/MM/DD）
					$tRequest->order["OrderTime"] = date('H:i:s'); //设定订单时间 （必要信息 - HH:MM:SS）
					$tRequest->order["orderTimeoutDate"] = date('YmdHis', time() + 600); //设定订单有效期
					//3、生成支付请求对象
					$tRequest->request["PaymentLinkType"] = '2'; //设定支付接入方式

					$successUrl = C('SERVER_URL') . ',user,paysuccess,' . session('token') ;

					$sign = md5($orderid . $successUrl . C('singKey'));

					$tRequest->request["ResultNotifyURL"] =C('PAY_URL').'order/abchinapay_wap/orderId/' . $orderid .'/successUrl/' . $successUrl . '/sign/' . $sign;//设定通知URL地址

					$tResponse = $tRequest->postRequest($config);
					if ($tResponse->isSuccess()) {

						$otherPayInfo['abchina'] = $OrderNo;
						$updateOrderData['otherPayInfo'] = json_encode($otherPayInfo);
						$updateOrderData['orderCode'] = $orderid;
						D('Order')->saveObj($updateOrderData);
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
	 * 查询购票是否成功并显示订单号
	 *
	 * @param string $orderid
	 * @return mixed
	 */
	public function orderajax(){
		$order=D('Order')->findObj(I('orderid'));
		echo json_encode($order);
	}

	/**
	 * 修改用户信息
	 */
	function setUserInfo(){
		$userInfo=session('ftuser');
		$type=I('type');
		$userNickname=I('userNickname');
		$email=I('email');
		$userSex=I('userSex');
		$userBirthday=I('userBirthday');
		$newCardPasswd=I('newCardPasswd');
		$oldPasswd=I('oldPasswd');
		$newMobilePasswd=I('newMobilePasswd');
		if($type=='1'){
			$upload = new \Think\Upload(); // 实例化上传类
			$upload->maxSize   =     3145728 ;// 设置附件上传大小
			$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
			$upload->rootPath  =     '.' . C('__UPLOAD__') . '/'; // 设置附件上传根目录
			$upload->savePath  =     'userIcons/'; // 设置附件上传（子）目录
			$info   =   $upload->upload();
				
			if ($upload->getError()) {
				$this->error($upload->getError());
			}
				
			if($info['userIcon']){
				$data['headImage']=$info['userIcon']['savepath'].$info['userIcon']['savename'];
				$userInfo['headImage'] = $data['headImage'];
			}
		}elseif(!empty($userNickname)){
			$data['otherName']=$userNickname;
			$userInfo['otherName'] = $userNickname;
		}elseif(!empty($email)){
			$userInfo['email'] = $email;
			$data['email']=$email;
		}elseif(!empty($userSex)){
			$userInfo['sex'] = $userSex;
			$data['sex']=$userSex;
		}elseif(!empty($userBirthday)){
			$userInfo['birthday'] = $userBirthday;
			$data['birthday']=$userBirthday;
		}elseif(!empty($newCardPasswd)){
			$user=$this->getBindUserInfo($userInfo);
			if(empty($user['cardNum'])){
				$this->error('无绑定会员卡');
			}
			if($user['pword']!=encty($oldPasswd)){
				$this->error('原始密码错误');
			}
			$data['pword']=encty($newCardPasswd);
			if(strlen($newCardPasswd)!=6){
				$this->error('密码必须6位数!');
			}
			if(!preg_match('/^\d{6}$/',$newCardPasswd)){
				$this->error('密码必须为纯数字!');
			}
			$arr['cinemaCode']=$user['businessCode'];
			$cinema=D('cinema')->find($arr['cinemaCode']);
			$arr['loginNum']=$user['cardNum'];
			$arr['link']=$cinema['link'];
			$arr['oldPassword']=$oldPasswd;
			$arr['newPassword']=$newCardPasswd;
			$result=D('ZMUser')->modifyMemberPassword($arr);
			if($result['ResultCode']!='0'){
				$this->error('暂不支持修改会员卡密码');
			}
				
			$userInfo['pword'] = encty($newCardPasswd);
		}elseif(!empty($newMobilePasswd)){
			$user=$this->getBindCardInfo($userInfo);
			if(empty($user['mobile'])){
				$this->error('无绑定手机号');
			}
			if($user['mpword']!=encty($oldPasswd)){
				$this->error('原始密码错误');
			}
			if(strlen($newMobilePasswd)<6){
				$this->error('密码长度必须大于6位!');
			}
			$userInfo['mpword'] = encty($newMobilePasswd);
			$data['mpword'] = encty($newMobilePasswd);
		}
		$result=D('member')->saveUser(array('id'=>$userInfo['id']), $data);
		if($result!==false){
			session('ftuser',$userInfo);
			cookie('ftuser',$userInfo,3600);
			$this->success('修改成功', '');
		}else{
			$this->error('修改失败');
		}
	}
	
	/**
	 * 用户详情
	 */
	public function info(){
		$userInfo=session('ftuser');
		$user=$this->setAppUserInfo($userInfo);
		$this->assign('user',$user);
		$this->display();
	}
	public function name(){
		$user=D('member')->getUser(array('id'=>UID));
		$this->assign('user',$user);
		$this->display();
	}
	public function email(){
		$user=session('ftuser');
		$this->assign('user',$user);
		$this->display();
	}

	/**
	 * 影票订单
	 */
	public function order(){
		$user=session('ftuser');
		$user=$this->getBindUserInfo($user);
		$orders=D('order')->getList(array('status'=>3,'uid'=>$user['id']));
		foreach ($orders as $k=>$v){
			$film=D('film')->findObj(array('filmNo'=>$v['filmNo']));
			$orders[$k]['image']=$film['image'];
		}
		$this->assign('orders',$orders);
		$this->display();
	}
	/**
	 * 影票订单ajax
	 */
	public function orderajax1(){
		$user=session('ftuser');
		$status= I('status');
		$page= I('page');
		if(empty($page)){
			$page=1;
		}
		$start=($page-1)*$this->pageNum;
		if(empty($status)){
			$this->error('参数错误！', '11001');
		}
		$map['cinemaGroupId']=$this->weiXinInfo['cinemaGroupId'];
		if($status=='1'){
			$map['status']=3;
		}elseif($status=='2'){
			$map['_string']='status=0 and way="wap"';
		}elseif($status=='3'){
			$map['status']=array('not in', '0,3');
		}else{
			$map['_string']='status !=0 or (status=0 and way="wap")';
		}
		$user=$this->getBindUserInfo($user);
		$map['uid']=$user['id'];
		$orders=D('order')->getList('status,orderCode,myPrice,seatCount,filmNo,lockTime,status,printNo,verifyCode,filmName,startTime,seatIntroduce,copyType,downTime,hallName,cinemaName,orderTime,mobile, submitPrice, amount',$map,$start,$this->pageNum);

		foreach ($orders as $k=>$v){
			if(($v['status']=='3'&&($v['startTime']+7200>time()))||$v['status']=='0'){
				$orders[$k]['isdel']=1;
			}
			$film[$k]=D('film')->getFilm(array('filmNo'=>$v['filmNo']));
			$orders[$k]['filmImg']=$film[$k]['image'];
			$orders[$k]['startTime']=date('Y-m-d H:i:s',$v['startTime']);
			$orders[$k]['downTime']=date('Y-m-d H:i:s',$v['downTime']);
			$orders[$k]['qrcode']=substr(C('IMG_URL'), 0,-1). U('index/getQRcode', array('orderid'=>$v['orderCode'],'code' => $v['printNo'] ));
		}
		$this->success('',$orders);
	}

	
	/**
	 * 等待支付
	 */
	public function paying(){
		$order=D('order')->getpaying(0);
		$this->assign('order',$order);
		$this->display();
	}
	/**
	 * 全部消费记录
	 */
	public function record(){
		$user=session('ftuser');
		$user=$this->getBindUserInfo($user);
		$moneylogs=D('moneyLog')->getList('',array('uid'=>$user['id']));
		$this->assign('moneylogs',$moneylogs);
		$this->display();
	}
	/**
	 * 消费记录ajax
	 */
	public function recordajax(){
		$type=I('type');
		$user=session('ftuser');
		$user=$this->getBindUserInfo($user);
		$moneylogs=D('moneyLog')->getList('',array('uid'=>$user['id']),$type);
		foreach ($moneylogs as $k=>$v){
			$moneylogs[$k]['createTime']=date('Y-m-d H:i:s',$v['createTime']);
		}
		echo json_encode($moneylogs);
	}
	/**
	 * 会员卡手机绑定
	 */
	function setUserBind(){
		$user = session('ftuser');
		$mobile=I('userMobile');
		if(!empty($mobile)){  //会员卡绑手机
			if(!checkMobile($mobile)){
				$this->error('手机格式不正确');
			}
			$bind=D('member')->getBindInfo(array('mobile'=>$mobile,'cinemaGroupId'=>$this->weiXinInfo['cinemaGroupId']));
	
			if(!empty($bind)){
				$this->error('该手机号已被卡号'.$bind['cardId'].'绑定');
			}
			$cardbind=D('member')->getBindInfo(array('cardId'=>$user['cardNum'],'cinemaCode'=>$user['businessCode'],'cinemaGroupId'=>$this->weiXinInfo['cinemaGroupId']));
			if(!empty($cardbind)){
				$this->error('该会员卡已被手机号'.$cardbind['mobile'].'绑定');
			}
			$randomCode=I('validateCode');
			$send=S('tokenId_getMobileVerification' . $mobile . 'bind');
			if(!empty($send)){
				if($randomCode!=$send['code'] || empty($randomCode)){
					$this->error('验证码填写错误');
				}else{
					$mobileUser=D('member')->getUser(array('mobile'=>$mobile,'cinemaGroupId'=>$this->weiXinInfo['cinemaGroupId']));
					if(empty($mobileUser)){
						$userarr['mobile']=$mobile;
						$userarr['memberGroupId']='99101';
						$userarr['levelCode']='99101';
						$userarr['cinemaGroupId']=$this->weiXinInfo['cinemaGroupId'];
						$userarr['mpword']=$user['pword'];
						$userarr['otherName']=$mobile;
						if(!D('member')->add($userarr)){
							$this->error('添加新手机账号失败');
						}
					}
					$arr['cardId']=$user['cardNum'];
					$arr['cinemaCode']=$user['businessCode'];
					$arr['mobile']=$mobile;
					$arr['cinemaGroupId']=$this->weiXinInfo['cinemaGroupId'];
					if(D('memberBind')->add($arr)){
						S('tokenId_getMobileVerification' . $mobile . 'bind',null);
						$this->success('绑定成功', $arr);
					}else{
						$this->error('绑定失败');
					}
				}
			}else{
				$this->error('验证码已失效');
			}
		}else{  //手机绑会员卡
			$cinemaCode=I('cinemaCode');
			$cardId=I('userAccount');
			$passwd=I('userPasswd');
			$cinema=D('cinema')->find($cinemaCode);
			$cardUser=D('member')->getUser(array('cardNum'=>$cardId,'businessCode'=>$cinemaCode,'cinemaGroupId'=>$this->weiXinInfo['cinemaGroupId']));
			if(empty($cardUser)){  //会员卡不存在
				$loginResult = D('ZMUser')->verifyMemberLogin(array('cinemaCode'=>$cinemaCode,'loginNum'=>$cardId,'password'=>$passwd,'link'=>$cinema['link'],'cinemaName'=>$cinema['cinemaName']));
				if($loginResult['ResultCode']=='0'){
					$result=D('member')->loginMember($loginResult,$cinemaCode,$passwd,$this->weiXinInfo);
					if($result['status']!='0'){
						$this->error('添加新会员卡信息失败');
					}else{
                        $arr['cardId']=$cardId;
                        $arr['cinemaCode']=$result['info']['businessCode'];
                        $arr['mobile']=$user['mobile'];
                        $arr['cinemaGroupId']=$this->weiXinInfo['cinemaGroupId'];
                        if(D('memberBind')->add($arr)){
                            // S('tokenId_getMobileVerification' . $mobile . 'bind',null);
                            $this->success('关联成功', $arr);
                        }else{
                            $this->error('关联失败');
                        }
                    }
				}else{
					$this->error($loginResult['Message']);
				}
			}else{  //会员卡已存在
				if(encty($passwd)!=$cardUser['pword']){
					$this->error('会员卡密码错误');
				}
	
				$bind=D('member')->getBindInfo(array('cardId'=>$cardId,'cinemaCode'=>$cardUser['businessCode'],'cinemaGroupId'=>$this->weiXinInfo['cinemaGroupId']));
				if(!empty($bind)){
					$this->error('该会员卡已被绑定');
				}
				$mobilebind=D('member')->getBindInfo(array('mobile'=>$user['mobile'],'cinemaGroupId'=>$this->weiXinInfo['cinemaGroupId']));
				if(!empty($mobilebind)){
					$this->error('该手机号已被绑定');
				}
				$arr['cardId']=$cardId;
				$arr['cinemaCode']=$cardUser['businessCode'];
				$arr['mobile']=$user['mobile'];
				$arr['cinemaGroupId']=$this->weiXinInfo['cinemaGroupId'];
				if(D('memberBind')->add($arr)){
					$this->success('绑定成功', $arr);
				}else{
					$this->error('绑定失败');
				}
			}
		}
	}
	
	function unbind(){
		$user=session('ftuser');
		$bind=D('member')->getBindInfo(array('cardId'=>$user['cardNum'],'cinemaCode'=>$user['businessCode'],'cinemaGroupId'=>$this->weiXinInfo['cinemaGroupId']));
		$this->assign('user',$bind);
		$this->display();
	}
	/**
	 * 解绑券包
	 */
	function delvoucher(){
		$vid=I('vid');
		if(empty($vid)){
			$this->error();
		}
		$voucher=M('memberVoucher')->find($vid);
		if(UID!=$voucher['memberId']){
			$this->error('此券不属于您，无法进行该操作');
		}
		$data['cardId']=$vid;
		$data['isUnlock']=1;
		if(M('memberVoucher')->save($data) !== false){
			$this->success();
		}else{
			$this->error();
		}
	}
	/**
	 * 解除绑定
	 */
	function setUserUnBind(){
		$user=session('ftuser');
		$type=I('type');
		if($type=='1'){ //解绑手机
			$bind=D('member')->getBindInfo(array('cardId'=>$user['cardNum'],'cinemaCode'=>$user['businessCode'],'cinemaGroupId'=>$this->weiXinInfo['cinemaGroupId']));
			if(empty($bind)){
				$this->error('该绑定关系不存在');
			}else{
				$randomCode=I('validateCode');
				$send=S('tokenId_getMobileVerification' . $bind['mobile'] . 'unbind');
				if(!empty($send)){
					if($randomCode!=$send['code'] || empty($randomCode)){
						$this->error('验证码填写错误');
					}else{
						if(D('memberBind')->delete($bind['id'])){
							$this->success('解绑成功', $bind);
						}else{
							$this->error('解绑失败');
						}
					}
				}else{
					$this->error('验证码已超时');
				}
			}
		}else{  //解绑会员卡
			$pword=I('passWord');
			$bind=D('member')->getBindInfo(array('mobile'=>$user['mobile'],'cinemaGroupId'=>$this->weiXinInfo['cinemaGroupId']));
			if(empty($bind)){
				$this->error('该绑定关系不存在');
			}else{
				$cardUser=D('member')->getUser(array('cardNum'=>$bind['cardId'],'businessCode'=>$bind['cinemaCode'],'cinemaGroupId'=>$this->weiXinInfo['cinemaGroupId']));
				if(encty($pword)!=$cardUser['pword']){
					$this->error('密码输入错误');
				}
				if(D('memberBind')->delete($bind['id'])){
					$this->success('解绑成功','');
				}else{
					$this->error('解绑失败');
				}
			}
		}
		 
	}
	
	/**
	 * 用户中心
	 */
	public function user(){
        $userInfo = D('Member')->getUser(array('id'=>UID));

        $user = $this->setAppUserInfo($userInfo);
        $str='';
		if(!empty($user['bindmobile'])&&!empty($user['bindcardId'])){
			$user['type']=0;
			$str.='解绑';
		}else{
			$user['type']=1;
			$str.='绑定';
		}
		if(empty($userInfo['cardNum'])){
			$url=U('cardbind');
			$user['cardtype']=6;
			$str.='会员卡';
		}else{
			$url=U('phonebind');
			$user['cardtype']=3;
			$str.='手机号';
		}
		$user['url']=$url;
        $user['sayStr']=$str;
		$this->assign('user',$user);
		$this->display();
	}
	/**
	 * 修改密码
	 */
	public function password(){
		$userInfo = D('Member')->getUser(array('id'=>UID));
        $user = $this->setAppUserInfo($userInfo);
        $str='';
        if(!empty($userInfo['cardNum'])){
        	$data['hascard']=1;
        	if(!empty($user['bindmobile'])){
        		$data['hasmobile']=1;
        	}
        }else{
        	$data['hasmobile']=1;
        	if(!empty($user['bindcardId'])){
        		$data['hascard']=1;
        	}
        }
		$this->assign('data',$data);
		$this->display();
	}
	/**
	 * 绑定手机
	 */
	public function phonebind(){
		$user=session('ftuser');
		$bind=D('member')->getBindInfo(array('cardId'=>$user['cardNum'],'cinemaCode'=>$user['businessCode'],'cinemaGroupId'=>$this->weiXinInfo['cinemaGroupId']));
		if(empty($bind)){
			$this->assign('flag',1);
		}else{
			$user=D('member')->getUser(array('mobile'=>$bind['mobile'],'cinemaGroupId'=>$this->weiXinInfo['cinemaGroupId']));
			$this->assign('user',$user);
		}
		$this->display();
	}
	/**
	 * 绑定会员卡
	 */
	public function cardbind(){
		$user=session('ftuser');
		$bind=D('member')->getBindInfo(array('mobile'=>$user['mobile'],'cinemaGroupId'=>$this->weiXinInfo['cinemaGroupId']));
		if(empty($bind)){
			$this->assign('flag',1);
		}else{
			$user=D('member')->getUser(array('cardNum'=>$bind['cardId'],'businessCode'=>$bind['cinemaCode'],'cinemaGroupId'=>$this->weiXinInfo['cinemaGroupId']));
			$myuser['levelName']=$user['levelName'];
			$myuser['integral']=round($user['integral'],1);
			$myuser['money']=round($user['basicBalance']+$user['donateBalance'],2);
			$myuser['expirationTime']=date('Y-m-d',$user['expirationTime']);
			if($user['cardStatus']=='0'){
				$myuser['cardStatus']='正常';
			}else{
				$myuser['cardStatus']='已过期或不可用';
			}
			$this->assign('user',$myuser);
		}
		$this->display();
	}
	/**
	 * 添加会员卡
	 */	
	function addcard(){
		$user=session('ftuser');
		$user['mmoney']=round($user['mmoney'],2);
		$cinemalist=D('cinema')->getCinemaList('cinemaCode,cinemaName');
		$this->assign('user',$user);
		$this->assign('data',$cinemalist);
		$this->display();
	}
	
	/**
	 * 修改密码
	 */
	function pwdajax(){
		$user=session('ftuser');
		$arr['cinemaCode']=$user['businessCode'];
		$cinema=D('cinema')->find($arr);
		$arr['link']=$cinema['link'];
		$arr['loginNum']=$user['cardNum'];
		$arr['oldPassword']=I('oldp');
		$arr['newPassword']=I('curp');
		$result=D('ZMUser')->modifyMemberPassword($arr);
		if($result['ResultCode']=='0'){
			$userarr['id']=UID;
			$userarr['pword']=encty($arr['newPassword']);
			if(D('member')->save($userarr)){
				$user=D('member')->getUser(array('id'=>UID));
			}else{
				$result['ResultCode']=1;
				$result['Message']='会员密码修改失败';
			}
		}
		echo json_encode($result);
	}
	
	/**
	 * 5.0.1添加卡包
	 */
	public function addvoucher(){
		$voucherNum=I('voucherNum');
		if(empty($voucherNum)){
			$this->error('参数错误！', '11001');
		}
	
		$voucherInfo = D('Voucher')->checkVoucher($voucherNum);
		if ($voucherInfo['status'] ==1) {
			$this->error($voucherInfo['content']);
		}
		$user=session('ftuser');
		$userInfo = $this->getBindUserInfo($user);
		//判断是否被添加
	
		if(D('Member')->checkVoucher($voucherNum)){
			$this->error('当前票券已被添加！');
		}
	
		//开始加入券包
	
		$data['memberId'] = $userInfo['id'];
		$data['voucherName'] = $voucherInfo['data']['typeName'];
		$data['voucherNum'] = $voucherInfo['data']['voucherNumber'];
		$data['typeClass'] = $voucherInfo['data']['typeClass'];
		$data['voucherValue'] = $voucherInfo['data']['typeValue'];
		$data['createdDatetime'] = time();
		$data['validData'] = $voucherInfo['data']['endTime'];
		$data['typeId'] = $voucherInfo['data']['typeId'];
	
		if(D('Member')->addMemberVoucher($data)){
			$data['validData']=date('Y-m-d',$data['validData']);
			$this->success('添加成功！');
		}else{
			$this->error('添加失败！');
		}
	}
	/**
	 *	获取用户券包ajax
	 */
	public function packageajax(){
		$voucherClass=I('voucherClass');
		$user=session('ftuser');
		$userInfo = $this->getBindUserInfo($user);
		if (!empty($voucherClass)) {
			$map['voucherType'] = $voucherClass;
		}
		$map['memberId'] = $userInfo['id'];
		$map['validData'] = array('EGT', strtotime(date('Y-m-d')));
		$map['isUnlock'] = 0;
		$map['isUse'] = 0;
		$memberVoucherList = D('Member')->getMemberVoucherList('cardId,typeId, voucherName, voucherNum, typeClass, voucherValue, createdDatetime, validData', $map, '', 'validData asc');
		$map['memberId'] = $userInfo['id'];
		$map['validData'] = array('between',strtotime(date('Y-m-d')) . ',' . (strtotime(date('Y-m-d')) + 604800));
		$map['isUnlock'] = 0;
		$map['isUse'] = 0;
		$expireVoucherList = D('Member')->getMemberVoucherList('typeId', $map, '', 'validData asc');
		$expireNum = count($expireVoucherList);
	
		foreach ($memberVoucherList as $key => $value) {
			$memberVoucherList[$key]['expireNum'] = $expireNum;
			$memberVoucherList[$key]['validDate'] =date('Y-m-d',$value['validData']);
		}
		if(empty($memberVoucherList)){
			$this->error();
		}else{
			$this->success('',$memberVoucherList);
		}
	}
	/**
	 * 5.0.5获取用户使用记录
	 */
	public function userVoucherHistory()
	{
		$userInfo = $this->getBindUserInfo($this->weiXinInfo['userInfo']);
	
		$nowPage = $this->param['page'] ? $this->param['page'] : 1;
		$pageNum = 5;
		$map['memberId'] = $userInfo['id'];
		$map['_string'] = 'validData < ' . strtotime(date('Y-m-d')) . ' or isUnlock=1 or isUse=1';
		$memberVoucherList = D('Member')->getMemberVoucherList('typeId, voucherName, voucherNum, typeClass, voucherValue, createdDatetime, validData, isUse, isUnlock', $map, ($nowPage - 1) * $pageNum . ',' . $pageNum, 'validData asc');
	
		foreach ($memberVoucherList as $key => $value) {
			if ($value['validData'] < strtotime(date('Y-m-d')) ) {
				$remarks = '已过期';
			}elseif ($value['isUnlock'] == 1) {
				$remarks = '已解锁';
			}elseif ($value['isUse'] == 1) {
				$remarks = '已使用';
			}
			$memberVoucherList[$key]['remarks'] = $remarks;
			unset($memberVoucherList[$key]['isUnlock'], $memberVoucherList[$key]['isUse']);
		}
	
		$this->success('', $memberVoucherList);
	}
	
	
	/**
	 * 5.0.6使用积分
	 */
	public function useIntegral()
	{
		$orderId=I('orderId');
		$type=I('type');
		if(empty($orderId) || empty($type)){
			$this->error('参数错误！', '11001');
		}
		//取得用户信息
		// $userInfo = $this->getBindUserInfo($this->weiXinInfo['userInfo']);
		// if ($userInfo['integral'] < $this->param['integral']) {
		//     $this->error('积分不足！');
		// }
		//取得订单信息
		if ($type== 'film') {
			$orderInfo = S('getBuyPaywayOrderInfo' . $orderId);
			if (empty($orderInfo)) {
				$orderInfo = D('Order')->findObj($orderId);
				S('getBuyPaywayOrderInfo' . $orderId, $orderInfo, 900);
			}
	
			$orderInfo['otherPayInfo'] = json_decode($orderInfo['otherPayInfo'], true);
			$orderInfo['otherPayInfo']['integral'] = true;
			$orderInfo['otherPayInfo'] = json_encode($orderInfo['otherPayInfo']);
			$data['orderCode'] = $orderId;
			$data['otherPayInfo'] = $orderInfo['otherPayInfo'];
			if (D('Order')->saveObj($data)) {
				S('getBuyPaywayOrderInfo' . $orderId, $orderInfo, 900);
				$this->success('使用积分成功！');
			}else{
				$this->error('使用积分失败');
			}
		}else{
			$goodOrderInfo = S('getBuyPaywayGoodsOrderInfo' . $orderId);
			if (empty($goodOrderInfo)) {
				$goodOrderInfo=D('Goods')->getGoodsOrderInfo('', array('id' => $orderId));
				S('getBuyPaywayGoodsOrderInfo' . $orderId, $goodOrderInfo, 900);
			}
	
			$goodOrderInfo['otherPayInfo'] = json_decode($goodOrderInfo['otherPayInfo'], true);
			$goodOrderInfo['otherPayInfo']['integral'] = true;
			$goodOrderInfo['otherPayInfo'] = json_encode($goodOrderInfo['otherPayInfo']);
	
			$data['otherPayInfo'] = $goodOrderInfo['otherPayInfo'];
			$map['id'] = $orderId;
			if (D('Goods')->updateGoodsOrder($data, $map)) {
				S('getBuyPaywayGoodsOrderInfo' . $orderId, $goodOrderInfo, 900);
				$this->success('使用积分成功！');
			}else{
				$this->error('使用积分失败');
			}
		}
	}
	
	/**
	 * 5.0.7取消使用积分
	 */
	public function cancelIntegral(){
		$orderId=I('orderId');
		$type=I('type');
		if(empty($orderId) || empty($type)){
			$this->error('参数错误！', '11001');
		}
		//取得用户信息
		// $userInfo = $this->getBindUserInfo($this->weiXinInfo['userInfo']);
		// if ($userInfo['integral'] < $this->param['integral']) {
		//     $this->error('积分不足！');
		// }
		//取得订单信息
		if ($type== 'film') {
			$orderInfo = S('getBuyPaywayOrderInfo' . $orderId);
			if (empty($orderInfo)) {
				$orderInfo = D('Order')->findObj($orderId);
				S('getBuyPaywayOrderInfo' . $orderId, $orderInfo, 900);
			}
	
			$orderInfo['otherPayInfo'] = json_decode($orderInfo['otherPayInfo'], true);
			unset($orderInfo['otherPayInfo']['integral']);
			$orderInfo['otherPayInfo'] = json_encode($orderInfo['otherPayInfo']);
			$data['orderCode'] = $orderId;
			$data['otherPayInfo'] = $orderInfo['otherPayInfo'];
			if (D('Order')->saveObj($data)) {
				S('getBuyPaywayOrderInfo' . $orderId, $orderInfo, 900);
				$this->success('取消使用积分成功！');
			}else{
				$this->error('取消使用积分失败');
			}
		}else{
			$goodOrderInfo = S('getBuyPaywayGoodsOrderInfo' . $orderId);
			if (empty($goodOrderInfo)) {
				$goodOrderInfo=D('Goods')->getGoodsOrderInfo('', array('id' => $orderId));
				S('getBuyPaywayGoodsOrderInfo' . $orderId, $goodOrderInfo, 900);
			}
	
			$goodOrderInfo['otherPayInfo'] = json_decode($goodOrderInfo['otherPayInfo'], true);
			unset($goodOrderInfo['otherPayInfo']['integral']);
			$goodOrderInfo['otherPayInfo'] = json_encode($goodOrderInfo['otherPayInfo']);
	
			$data['otherPayInfo'] = $goodOrderInfo['otherPayInfo'];
			$map['id'] = $orderId;
	
			if (D('Goods')->updateGoodsOrder($data, $map)) {
				S('getBuyPaywayGoodsOrderInfo' . $orderId, $goodOrderInfo, 900);
				$this->success('取消使用积分成功！');
			}else{
				$this->error('取消使用积分失败');
			}
		}
	}
	
	
	/**
	 * 5.0.8使用余额
	 */
	public function useAccount(){
		$orderId=I('orderId');
		$type=I('type');
		if(empty($orderId) || empty($type)){
			$this->error('参数错误！', '11001');
		}
		//取得用户信息
		// $userInfo = $this->getBindUserInfo($this->weiXinInfo['userInfo']);
		// if ($userInfo['integral'] < $this->param['integral']) {
		//     $this->error('积分不足！');
		// }
		//取得订单信息
		if ($type== 'film') {
			$orderInfo = S('getBuyPaywayOrderInfo' . $orderId);
			if (empty($orderInfo)) {
				$orderInfo = D('Order')->findObj($orderId);
				S('getBuyPaywayOrderInfo' . $orderId, $orderInfo, 900);
			}
	
			$orderInfo['otherPayInfo'] = json_decode($orderInfo['otherPayInfo'], true);
			$orderInfo['otherPayInfo']['account'] = true;
			$orderInfo['otherPayInfo'] = json_encode($orderInfo['otherPayInfo']);
			$data['orderCode'] = $orderId;
			$data['otherPayInfo'] = $orderInfo['otherPayInfo'];
			if (D('Order')->saveObj($data)) {
				S('getBuyPaywayOrderInfo' . $orderId, $orderInfo, 900);
				$this->success('使用余额支付成功！');
			}else{
				$this->error('使用余额支付失败');
			}
		}else{
	
			$goodOrderInfo = S('getBuyPaywayGoodsOrderInfo' . $orderId);
			if (empty($goodOrderInfo)) {
				$goodOrderInfo=D('Goods')->getGoodsOrderInfo('', array('id' => $orderId));
				S('getBuyPaywayGoodsOrderInfo' . $orderId, $goodOrderInfo, 900);
			}
	
			$goodOrderInfo['otherPayInfo'] = json_decode($goodOrderInfo['otherPayInfo'], true);
			$goodOrderInfo['otherPayInfo']['account'] = true;
			$goodOrderInfo['otherPayInfo'] = json_encode($goodOrderInfo['otherPayInfo']);
	
			$data['otherPayInfo'] = $goodOrderInfo['otherPayInfo'];
			$map['id'] = $orderId;
	
			if (D('Goods')->updateGoodsOrder($data, $map)) {
				S('getBuyPaywayGoodsOrderInfo' . $orderId, $goodOrderInfo, 900);
				$this->success('使用余额支付成功！');
			}else{
				$this->error('使用余额支付失败');
			}
		}
	}
	
	/**
	 * 5.0.9取消使用余额
	 */
	public function cancelAccount(){
		$orderId=I('orderId');
		$type=I('type');
		if(empty($orderId) || empty($type)){
			$this->error('参数错误！', '11001');
		}
		//取得用户信息
		// $userInfo = $this->getBindUserInfo($this->weiXinInfo['userInfo']);
		// if ($userInfo['integral'] < $this->param['integral']) {
		//     $this->error('积分不足！');
		// }
		//取得订单信息
		if ($type== 'film') {
			$orderInfo = S('getBuyPaywayOrderInfo' . $orderId);
			if (empty($orderInfo)) {
				$orderInfo = D('Order')->findObj($orderId);
				S('getBuyPaywayOrderInfo' . $orderId, $orderInfo, 900);
			}
	
			$orderInfo['otherPayInfo'] = json_decode($orderInfo['otherPayInfo'], true);
			unset($orderInfo['otherPayInfo']['account']);
			$orderInfo['otherPayInfo'] = json_encode($orderInfo['otherPayInfo']);
			$data['orderCode'] = $orderId;
			$data['otherPayInfo'] = $orderInfo['otherPayInfo'];
			if (D('Order')->saveObj($data)) {
				S('getBuyPaywayOrderInfo' . $orderId, $orderInfo, 900);
				$this->success('取消余额支付成功！');
			}else{
				$this->error('取消余额支付失败');
			}
		}else{
			$goodOrderInfo = S('getBuyPaywayGoodsOrderInfo' . $orderId);
			if (empty($goodOrderInfo)) {
				$goodOrderInfo=D('Goods')->getGoodsOrderInfo('', array('id' => $orderId));
				S('getBuyPaywayGoodsOrderInfo' . $orderId, $goodOrderInfo, 900);
			}
	
	
			$goodOrderInfo['otherPayInfo'] = json_decode($goodOrderInfo['otherPayInfo'], true);
			unset($goodOrderInfo['otherPayInfo']['account']);
			$goodOrderInfo['otherPayInfo'] = json_encode($goodOrderInfo['otherPayInfo']);
	
			$data['otherPayInfo'] = $goodOrderInfo['otherPayInfo'];
			$map['id'] = $orderId;
	
			if (D('Goods')->updateGoodsOrder($data, $map)) {
				S('getBuyPaywayGoodsOrderInfo' . $orderId, $goodOrderInfo, 900);
				$this->success('取消余额支付成功！');
			}else{
				$this->error('取消余额支付失败');
			}
		}
	}
	
	/**
	 * 5.0.4使用票券
	 */
	public function useVoucher(){
		$orderId=I('orderId');
		$voucherNum=I('voucherNum');
		$type=I('type');
		if(empty($orderId) || empty($voucherNum) || empty($type)){
			$this->error('参数错误！', '11001');
		}
	
		//验证票券状态
		$voucherInfo = D('Voucher')->checkVoucher($voucherNum);
		if ($voucherInfo['status'] ==1) {
			$this->error($voucherInfo['content']);
		}
		if($type=='goods'&&$voucherInfo['data']['typeClass']!='2'){
			$this->error('该券不是卖品券');
		}
		//取得用户信息
		$user=session('ftuser');
		$userInfo = $this->getBindUserInfo($user);
		//取得订单信息
		

		//判断是否在券包中
		$checkVoucherInfo = D('Member')->checkVoucher($voucherNum);
		if(empty($checkVoucherInfo)){
			//开始加入券包
			$data['memberId'] = $userInfo['id'];
			$data['voucherName'] = $voucherInfo['data']['typeName'];
			$data['voucherNum'] = $voucherInfo['data']['voucherNumber'];
			$data['typeClass'] = $voucherInfo['data']['typeId'];
			$data['typeClass'] = $voucherInfo['data']['typeClass'];
			$data['voucherValue'] = $voucherInfo['data']['typeValue'];
			$data['createdDatetime'] = time();
			$data['validData'] = $voucherInfo['data']['endTime'];
			$data['typeId'] = $voucherInfo['data']['typeId'];
			if(!D('Member')->addMemberVoucher($data)){
				$this->error('加入券包失败，请重试！');
			}
			unset($data);
		}elseif ($checkVoucherInfo['memberId'] != $userInfo['id']) {
			$this->error('该票券已被绑定不可使用！');
		}


		if ($type== 'film') {
			$orderInfo = S('getBuyPaywayOrderInfo' . $orderId);
			if (empty($orderInfo)) {
				$orderInfo = D('Order')->findObj($orderId);
				S('getBuyPaywayOrderInfo' . $orderId, $orderInfo, 900);
			}
	
			$seatInfo = json_decode($orderInfo['seatInfo'], true);
			$seatInfoCount = count($seatInfo);
			//取得排期信息
			$planInfo = S('getBuyPaywayPlanInfo' . $orderInfo['featureAppNo']);
			if (empty($planInfo)) {
				$planInfo = D('Plan')->getplan($orderInfo['featureAppNo']);
				S('getBuyPaywayPlanInfo' . $orderInfo['featureAppNo'], $planInfo, 900);
			}
	
			//取得当前场次的配置
			$arraySetingConfig = S('getBuyPaywaySetingConfig' . $orderInfo['featureAppNo']);
			if (empty($arraySetingConfig)) {
				$arraySetingConfig = D('Voucher')->isVoucher($planInfo);
				S('getBuyPaywaySetingConfig' . $orderInfo['featureAppNo'], $arraySetingConfig, 900);
			}
			$flag = false;
	
			$canUserTypeList = array_keys($arraySetingConfig[$voucherInfo['data']['typeClass']]);

			$copyType = substr(strtolower($planInfo['copyType']), 0, 3);



			$canUseCopyTypeList = array_keys($arraySetingConfig[$voucherInfo['data']['typeClass']][$voucherInfo['data']['typeId']]);


			if (in_array($voucherInfo['data']['typeId'], $canUserTypeList) && in_array($copyType, $canUseCopyTypeList)) {

				$flag = true;
			}

			if (!$flag) {
				$this->error('该票券在当前场次不可使用！');
			}
		}elseif ($type == 'goods') {
			$orderInfo = S('getBuyPaywayGoodsOrderInfo' . $orderId);
			if (empty($goodOrderInfo)) {
				$orderInfo=D('Goods')->getGoodsOrderInfo('', array('id' => $orderId));
				S('getBuyPaywayGoodsOrderInfo' . $orderId, $orderInfo, 900);
			}
			$seatInfoCount = 1;
		}else{
			$this->error('参数错误！', '11001');
		}
	
	
		
	
		$orderInfo['otherPayInfo'] = json_decode($orderInfo['otherPayInfo'], true);
		$typeClass = 0;
		$useNum = 0;
		$otherPayInfo = $orderInfo['otherPayInfo'];
		if (!empty($otherPayInfo['account']) || $otherPayInfo['integral']) {
			$this->error('请先取消积分或余额支付后，再使用票券支付！');
		}
	
	
		if (is_array($otherPayInfo)) {
			foreach ($otherPayInfo as $key => $value) {
				$typeClass = $key;
				$useNum += count(current($value));
				if ($key != $voucherInfo['data']['typeClass']) {
					$this->error('一个订单中只能使用一种类型的券！');
				}
				if ($key == 1) {
					$this->error('一个订单只能使用一张立减券！');
				}
			}
		}
		if ($seatInfoCount <= $useNum) {
			$this->error('该订单只能使用' . $seatInfoCount . '张券！');
		}
	
		if (in_array($voucherInfo['data']['voucherNumber'], $orderInfo['otherPayInfo'][$typeClass][$voucherInfo['data']['typeId']])) {
			$this->error('该订单中已使用这张票券，请勿重复使用');
		}
	
		$orderInfo['otherPayInfo'][$voucherInfo['data']['typeClass']][$voucherInfo['data']['typeId']][] = $voucherInfo['data']['voucherNumber'];
		$orderInfo['otherPayInfo'] = json_encode($orderInfo['otherPayInfo']);
	
		if ($type == 'film') {
			$data['orderCode'] = $orderId;
			$data['otherPayInfo'] = $orderInfo['otherPayInfo'];
			if (D('Order')->saveObj($data)) {
				S('getBuyPaywayOrderInfo' . $orderId, $orderInfo, 900);
				$this->success('票券使用成功！');
			}else{
				$this->error('票券使用失败！');
			}
		}else{
			$data['otherPayInfo'] = $orderInfo['otherPayInfo'];
			$map['id'] = $orderId;
			if (D('Goods')->updateGoodsOrder($data, $map)) {
				S('getBuyPaywayGoodsOrderInfo' . $orderId, $goodOrderInfo, 900);
				$this->success('票券使用成功！');
			}else{
				$this->error('票券使用失败！');
			}
		}
	
	
	}
	
	/**
	 * 5.0.5取消票券
	 */
	public function cancelVoucher(){
		$orderId=I('orderId');
		$voucherNum=I('voucherNum');
		$type=I('type');
		if(empty($orderId) || empty($voucherNum) || empty($type)){
			$this->error('参数错误！', '11001');
		}
		if ($type== 'film') {
			$orderInfo = S('getBuyPaywayOrderInfo' . $orderId);
			if (empty($orderInfo)) {
				$orderInfo = D('Order')->findObj($orderId);
				S('getBuyPaywayOrderInfo' . $orderId, $orderInfo, 900);
			}
			$orderInfo['otherPayInfo'] = json_decode($orderInfo['otherPayInfo'], true);
			$typeClass = empty($orderInfo['otherPayInfo'][1]) ? 0 : 1;
		}elseif ($type== 'goods') {
			$orderInfo = S('getBuyPaywayGoodsOrderInfo' . $orderId);
			if (empty($goodOrderInfo)) {
				$orderInfo=D('Goods')->getGoodsOrderInfo('', array('id' => $orderId));
				S('getBuyPaywayGoodsOrderInfo' . $orderId, $orderInfo, 900);
			}
			$orderInfo['otherPayInfo'] = json_decode($orderInfo['otherPayInfo'], true);
			$typeClass = 2;
		}else{
			$this->error('参数错误！', '11001');
		}
	
	
		$otherPayInfo = $orderInfo['otherPayInfo'][$typeClass];
	
		foreach ($otherPayInfo as $key => $value) {
			unset($orderInfo['otherPayInfo'][$typeClass][$key][array_search($voucherNum,$value)]);
			if (empty($orderInfo['otherPayInfo'][$typeClass][$key])) {
				unset($orderInfo['otherPayInfo'][$typeClass][$key]);
			}
	
			if (empty($orderInfo['otherPayInfo'][$typeClass])) {
				unset($orderInfo['otherPayInfo'][$typeClass]);
			}
		}
	
		$orderInfo['otherPayInfo'] = json_encode($orderInfo['otherPayInfo']);
	
		if ($type== 'film') {
			$data['orderCode'] = $orderId;
			$data['otherPayInfo'] = $orderInfo['otherPayInfo'];
			if (D('Order')->saveObj($data)) {
				S('getBuyPaywayOrderInfo' . $orderId, $orderInfo, 900);
				$this->success('票券取消成功！');
			}else{
				$this->error('票券取消失败！');
			}
		}else{
			$data['otherPayInfo'] = $orderInfo['otherPayInfo'];
			$map['id'] = $orderId;
			if (D('Goods')->updateGoodsOrder($data, $map)) {
				S('getBuyPaywayGoodsOrderInfo' . $orderId, $goodOrderInfo, 900);
				$this->success('票券取消成功！');
			}else{
				$this->error('票券取消失败！');
			}
		}
	}
	
	/**
	 * 周边支付页面
	 */
	function payment(){
		$goodsid=session('roundid');
		$goods=D('goodsRound')->find($goodsid);
		$seller=D('goodsSeller')->find($goods['sellerNo']);
		$cinemaCode=$seller['cinemaCode'];
		$user=session('ftuser');
		$mobileUser=$this->getBindCardInfo($user);
		$this->assign('mobile',$mobileUser['mobile']);
		$user=$this->getBindUserInfo($user);
		$cinema=D('cinema')->find($cinemaCode);
		$payInfo=$this->getBuyPayway('round','',$cinemaCode);
		$payConfig=json_decode($cinema['payConfig'],true);
		foreach ($payInfo['online'] as $k=>$v){
			if($v['type']=='weixinpay'){
				$wxConfig=$payConfig['weixinpayConfigWap'];
				if(!empty($wxConfig)){
					$openid=session('openid'.$cinemaCode);
					if(empty($openid)){
						$openid=cookie('openid'.$cinemaCode);
					}
					
					$jsApi = new \Org\Wechat\Wxjspay($wxConfig);
					$jsurl=strtolower('http://' . $_SERVER['HTTP_HOST'] . U('',I('request.')));
					//$jsurl='http://testapi.zmaxfilm.com'. U('');
					if (empty($openid)&&empty($_GET['code'])){
						$url = $jsApi->createOauthUrlForCode(urlencode($jsurl));
						Header("Location: $url");
						die();
				
					}elseif(empty($openid)){
						$code = $_GET['code'];
						$jsApi->setCode($code);
						$openid = $jsApi->getOpenId();
						session('openid'.$cinemaCode,$openid);
						cookie('openid'.$cinemaCode,$openid,3600);
					}else{
						cookie('openid'.$cinemaCode,$openid,3600);
					}
					$data['appid']=$wxConfig['appid'];
					$data['timestamp']=$timestamp=time();
					$data['wxnonceStr']=$wxnonceStr = $jsApi->createNoncestr();
					$wxticket=wx_get_jsapi_ticket($wxConfig['appid'],$wxConfig['appsecret']);
					$wxOri = 'jsapi_ticket=' . $wxticket.'&noncestr='.$wxnonceStr.'&timestamp='.$timestamp.'&url='.$jsurl;
					$data['wxSha1']= sha1($wxOri);
				}
			}
		}
		$this->assign('data',$data);
		$this->assign('payInfo',$payInfo['online']);
		$this->assign('user',$user);
		$this->assign('goods',$goods);
		$this->display(); //渲染支付页面
	}
	
	
	/**
	 * 生成影院周边卖品订单
	 */
	function setRoundOrder(){
		$user=session('ftuser');
		$user= $this->getBindUserInfo($user);
		$data['uid']=$user['id'];
		if(!empty($user['cardNum'])){
			$data['cardNum']=$user['cardNum'];
		}
		if(!empty($user['mobile'])){
			$data['mobileNum']=$user['mobile'];
		}
		$goodsId=I('goodsId');
		$number=I('number');
		$mobile=I('mobile');
		$payType=I('payType'); //选择支付类型
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
			$cinemaCode=$order['cinemaCode'];
			$buyAmount=$order['otherpay'];
			$cinemaInfo = D('Cinema')->getCinemaInfo('cinemaName, payConfig', array('cinemaCode' =>$order['cinemaCode']));
			
			if($payType == 'weixinpay'){   //微信支付
				$orderno=$orderid;
				$fee=$buyAmount=0.01;
				$payConfig=json_decode($cinemaInfo['payConfig'],true);
				$wxConfig =$payConfig['weixinpayConfigWap'];
				$jsApi = new \Org\Wechat\Wxjspay($wxConfig);
				//统一支付接口类
				$unifiedOrder = new \Org\Wechat\UnifiedOrder($wxConfig);
				$openid=session('openid' . $cinemaCode);
				
				/*-----------------------------必填--------------------------*/
				$unifiedOrder->setParameter('body', 'WAP团购' . $fee);//商品描述岚樨微支付平台
				$unifiedOrder->setParameter('out_trade_no', time().'round' . $orderno);//商户订单号
				$unifiedOrder->setParameter('total_fee', $fee * 100);//总金额（微信支付以人民币“分”为单位）
				/*-------------------------------------------------------*/
				$unifiedOrder->setParameter('openid', $openid);//获取到的OPENID
				$unifiedOrder->setParameter('notify_url', C('PAY_URL').'/home/saleround/weixinpay_wap.html');//通知地址
				$unifiedOrder->setParameter('trade_type', 'JSAPI');//交易类型
				
				$prepay_id = $unifiedOrder->getPrepayId();
				
				//=========步骤3：使用jsapi调起支付============
				$jsApi->setPrepayId($prepay_id);
				$jsApiParameters = $jsApi->getParameters();
				$jsApiParameters['orderid']=$orderid;
				$this->jsApiParameters = $jsApiParameters;
				echo json_encode($this->jsApiParameters);
			}
		}else{
			$this->error('生成订单失败');
		}
	}
	/**
	 * 支付卖品页面
	 */
	function paygoods(){
		$orderid=session('ordergoodsid');
		$user=session('ftuser');
		$user=$this->getBindUserInfo($user);
		$order=D('orderGoods')->find($orderid);

		$cinemaCode=$order['cinemaCode'];
		$cinema=D('cinema')->find($cinemaCode);

		$payInfo=$this->getBuyPayway('goods',$orderid);
		$payConfig=json_decode($cinema['payConfig'],true);
		foreach ($payInfo['online'] as $k=>$v){
			if($v['type']=='weixinpay'){
				
				$wxConfig=$payConfig['weixinpayConfigWap'];
				if(!empty($wxConfig)){
					$openid=session('openid'.$cinemaCode);
					if(empty($openid)){
						$openid=cookie('openid'.$cinemaCode);
					}
					
					$jsApi = new \Org\Wechat\Wxjspay($wxConfig);
					$jsurl=strtolower('http://' . $_SERVER['HTTP_HOST'] . U('',I('request.')));
					//$jsurl='http://testapi.zmaxfilm.com'. U('');
					if (empty($openid)&&empty($_GET['code'])){
						$url = $jsApi->createOauthUrlForCode(urlencode($jsurl));
						Header("Location: $url");
						die();
				
					}elseif(empty($openid)){
						$code = $_GET['code'];
						$jsApi->setCode($code);
						$openid = $jsApi->getOpenId();
						session('openid'.$cinemaCode,$openid);
						cookie('openid'.$cinemaCode,$openid,3600);
					}else{
						cookie('openid'.$cinemaCode,$openid,3600);
						session('openid'.$cinemaCode);
					}
					$data['appid']=$wxConfig['appid'];
					$data['timestamp']=$timestamp=time();
					$data['wxnonceStr']=$wxnonceStr = $jsApi->createNoncestr();
					$wxticket=wx_get_jsapi_ticket($wxConfig['appid'],$wxConfig['appsecret']);
					$wxOri = 'jsapi_ticket=' . $wxticket.'&noncestr='.$wxnonceStr.'&timestamp='.$timestamp.'&url='.$jsurl;
					$data['wxSha1']= sha1($wxOri);
				}
			}
		}
		$this->assign('data',$data);
		$this->assign('payInfo',$payInfo);
		$this->assign('user',$user);
		$this->assign('order',$order);
		$this->display(); //渲染支付页面
	}
	/**
	 * 支付卖品订单
	 */
	function salepay(){
		$data['id']=$orderid=I('orderid');
		if(empty($orderid)){
			$this->error('参数错误！', '11001');
		}
		$hasgoods=S('goods'.$orderid);
		$ctime=1;
		if(!empty($hasgoods)){
			$this->error('同物品订单间隔至少'.$ctime.'秒');
		}
		$order=D('orderGoods')->find($orderid);
		$cinemaCode=$order['cinemaCode'];
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
		$payType=I('payType');  //选择支付类型
		$buyAmount = D('Voucher')->getGoodOrderPrice($orderid, $user);
		S('goods'.$orderid,1,$ctime);
		$cinemaInfo = D('Cinema')->getCinemaInfo('cinemaName, payConfig', array('cinemaCode' =>$order['cinemaCode']));
		$payConfig=json_decode($cinemaInfo['payConfig'],true);
		if(empty($buyAmount)||$payType=='account'){
			getCurlResult(C('PAY_URL').'sale/mobile_app/orderid/'.$orderid.'/logpath/mobile_wap');
			$this->success('',$orderid);
		}elseif($payType == 'weixinpay'){   //微信支付
			$orderno=$orderid;
			$fee=$buyAmount=0.01;
			$wxConfig = $payConfig['weixinpayConfigWap'];
			$jsApi = new \Org\Wechat\Wxjspay($wxConfig);
			//统一支付接口类
			$unifiedOrder = new \Org\Wechat\UnifiedOrder($wxConfig);
			$openid=session('openid' . $cinemaCode);
		
			/*-----------------------------必填--------------------------*/
			$unifiedOrder->setParameter('body', 'WAP卖品' . $fee);//商品描述岚樨微支付平台
			$unifiedOrder->setParameter('out_trade_no', time().'goods' . $orderno);//商户订单号
			$unifiedOrder->setParameter('total_fee', $fee * 100);//总金额（微信支付以人民币“分”为单位）
			/*-------------------------------------------------------*/
			$unifiedOrder->setParameter('openid', $openid);//获取到的OPENID
			$unifiedOrder->setParameter('notify_url', C('PAY_URL').'/home/sale/weixinpay_wap.html');//通知地址
			$unifiedOrder->setParameter('trade_type', 'JSAPI');//交易类型
			
			$prepay_id = $unifiedOrder->getPrepayId();
		
			//=========步骤3：使用jsapi调起支付============
			$jsApi->setPrepayId($prepay_id);
			$jsApiParameters = $jsApi->getParameters();
			$this->jsApiParameters = $jsApiParameters;
			echo json_encode($this->jsApiParameters);
		}
	}
	
	/**
	 * 确认卖品订单
	 */
	function confirmorder(){
		$data=I('data');
		session('goodsdata'.UID,$data);
		$goodss=explode(',', $data);
		foreach ($goodss as $k=>$v){
			$good=explode(':', $v);
			$mygoods=D('goods')->find($good[0]);
			if(empty($mygoods)){
				die('物品'.$good[0].'信息出错');
			}
			$detail[$k]['goodsName']=$mygoods['goodsName'];
			$detail[$k]['price']=$mygoods['price'];
			$detail[$k]['number']=$good[1];
			$detail[$k]['cinemaCode']=$data['cinemaCode']=$mygoods['cinemaCode'];
			$price+=$mygoods['price'];
			unset($good);
			unset($mygoods);
		}
		$showdata['price']=$price;
		$cinema=D('cinema')->find(session('cinemaCode'));
		$user=session('ftuser');
		$mobileUser=$this->getBindCardInfo($user);
		$this->assign('mobile',$mobileUser['mobile']);
		$showdata['cinemaName']=$cinema['cinemaName'];
		$showdata['details']=$detail;
		$this->assign('showdata',$showdata);
		$this->display();
	}
	
	/**
	 * 生成影院卖品订单
	 */
	function setCinemaOrder(){
		$user=session('ftuser');
		$goods=session('goodsdata'.UID);
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
	 * 我的卖品订单列表
	 */
	function getMyGoods(){
		$user=session('ftuser');
		$user=$this->getBindUserInfo($user);
		$page= I('page');
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
		$user=session('ftuser');
		$user=$this->getBindUserInfo($user);
		$page= I('page');
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
	 * 卖品支付等待页面
	 */
	function goodssuccess(){
		$orderid=I('orderid');
		$order=D('orderGoods')->find($orderid);
		$details=D('orderDetail')->where(array('orderid'=>$orderid))->select();
		$this->assign('order',$order);
		$this->assign('details',$details);
		$this->display();
	}
	/**
	 * 团购支付等待页面
	 */
	function paymentsuccess(){
		$orderid=I('orderid');
		$order=D('orderRound')->find($orderid);
		$this->assign('order',$order);
		$this->display();
	}
	
	/**
	 * 查询卖品订单状态
	 */
	function getGoodsStatus(){
		$orderid=I('orderid');
		$order=D('orderGoods')->find($orderid);
		$this->success('',$order);
	}
	
	/**
	 * 查询周边卖品订单状态
	 */
	function getRoundStatus(){
		$orderid=I('orderid');
		$order=D('goods')->getRoundStatus($orderid);
		$this->success('',$order);
	}
	
	
	/**
	 * 删除影票订单
	 */
	function delOrderFilm(){
		$orderid=I('orderid');
		if(empty($orderid) ){
			$this->error('参数校验信息错误', 100101);
		}
		$order=D('orderFilm')->find($orderid);
		if(!empty($order)&&$order['status']=='3'&&($order['startTime']+7200>time())){ //2小时
			$this->error('该影片未放映结束，删除失败');
		}
		$data['orderCode']=$orderid;
		$data['visible']=1;
		if(D('orderFilm')->save($data)){
			if($order['status']=='0'){
				$this->success('取消订单成功',$orderid);
			}
			$this->success('删除成功',$orderid);
		}else{
			if($order['status']=='0'){
				$this->success('取消订单失败',$orderid);
			}
			$this->error('删除失败');
		}
	}
	
	/**
	 * 删除充值订单
	 */
	function delOrderRecharge(){
		$orderid=I('orderid');; //评论id
		if(empty($orderid) ){
			$this->error('参数校验信息错误', 100101);
		}
		$data['id']=$orderid;
		$data['visible']=1;
		if(D('orderRecharge')->save($data)){
			$this->success('删除成功',$orderid);
		}else{
			$this->error('删除失败');
		}
	}
	
	/**
	 * 删除卖品订单
	 */
	function delOrderGoods(){
		$orderid=I('orderid');; //评论id
		if(empty($orderid) ){
			$this->error('参数校验信息错误', 100101);
		}
		$order=D('orderGoods')->find($orderid);
		if(!empty($order)&&$order['status']=='1'&&$order['exstatus']!='1'){
			$this->error('该订单未兑换完成，删除失败');
		}
		$data['id']=$orderid;
		$data['visible']=1;
		if(D('orderGoods')->save($data)){
			$this->success('删除成功',$orderid);
		}else{
			$this->error('删除失败');
		}
	}
	/**
	 * 删除周边订单
	 */
	function delOrderRound(){
		$orderid=I('orderid');; //评论id
		if(empty($orderid) ){
			$this->error('参数校验信息错误', 100101);
		}
		$order=D('goods')->getRoundStatus($orderid);
		if(!empty($order)&&$order['status']=='1'){
			foreach ($order['codes'] as $v){
				if($v['status']!='1'){
					$this->error('该订单未兑换完成，删除失败');
				}
			}
		}
		$data['id']=$orderid;
		$data['visible']=1;
		if(D('orderRound')->save($data)){
			$this->success('删除成功',$orderid);
		}else{
			$this->error('删除失败');
		}
	}
	
	
	/**
	 * 补发短信
	 */
	function supply(){
		$orderid=I('orderid'); 
		$mobile=I('mobile'); 
		if(empty($orderid)|| empty($mobile)){
			$this->error('参数校验信息错误', 100101);
		}
		$order=D('orderFilm')->find($orderid);
		if(empty($order)||$order['status']!=3){
			$this->error('订单无效');
		}elseif($order['supply']>0){
			$this->error('订单已补发过');
		}
		$weiXinInfo=$this->weiXinInfo;
		$smsConfig['smsType'] = $weiXinInfo['smsType'];
		$smsConfig['smsAccount'] = $weiXinInfo['smsAccount'];
		$smsConfig['smsPassword'] = $weiXinInfo['smsPassword'];
		$smsConfig['smsSign'] = $weiXinInfo['smsSign'];
		if(smsajax($smsConfig,$order,$mobile)){
			$this->success('补发成功',$mobile);
		}else{
			$this->error('补发失败');
		}
	}
	/**
	 * 我的评论列表
	 */
	function comment(){
		$map['pid']=I('pid'); 
		if(empty($map['pid'])){
			$this->error('参数错误！', '11001');
		}
		$map['status']=0;
		$map['cinemaGroupId']=$this->weiXinInfo['cinemaGroupId'];
		$views['cur']=D('filmView')->find($map['pid']);
		$user=session('ftuser');
		if(!empty($user)){
			$myviews=explode(',', $user['onview']);  //点赞过的
			if(in_array($views['cur']['id'], $myviews)){
				$views['cur']['hasClick']='1';
			}else{
				$views['cur']['hasClick']='0';
			}
		}
		$mem=D('member')->find($views['cur']['uid']);
		if(empty($mem['headImage'])){
			$views['cur']['headImage']=C('HEAD_IMG_URL');
		}else{
			$views['cur']['headImage']=C('IMG_URL').'Uploads/'.$mem['headImage'];
		}
		$views['cur']['otherName']=$mem['otherName'];
		$views['cur']['time']=date('Y-m-d H:i',$views['cur']['time']);
		$views['list']=D('film')->getViews($map);
		$this->assign('views', $views);
		$this->display();
	}
	/**
	 * 我的评论列表
	 */
	function mycomment(){
		$page= I('page');
		if(empty($page)){
			$page=1;
		}
		$start=($page-1)*$this->pageNum;
		$user=$this->getBindUserInfo(session('ftuser'));
		$map['status']=0;
		$map['pid']=0;
		$map['uid']=$user['id'];
		$map['cinemaGroupId']=$this->weiXinInfo['cinemaGroupId'];
		$views=D('film')->getMyViews($map,$start,$this->pageNum);
		$this->assign('views',$views);
		$this->display();
	}
	
	/**
	 * 点赞
	 */
	function addClick(){
		$pid=I('pid'); //评论id
		if(empty($pid) ){
			$this->error('参数校验信息错误', 100101);
		}
		$user=session('ftuser');
		$views=explode(',', $user['onview']);   //已经点过赞的评论
		if(in_array($pid, $views)){
			$this->error('已经点过赞了');
		}
		$data['id']=$user['id'];
		if(empty($user['onview'])){
			$data['onview']=$pid;
		}else{
			$data['onview']=$user['onview'].','.$pid;
		}
		$user['onview']=$data['onview'];
		if(D('member')->save($data)){
			$view=D('filmView')->find($pid);
			D('filmView')->where(array('id'=>$pid))->setInc('clickNum',1);
			$filmView=D('filmView')->find($pid);
			session('ftuser',$user);
			$this->success('',$filmView['clickNum']);
		}else{
			$this->error('点赞失败');
		}
	}
	
	/**
	 * 取消点赞
	 */
	function delClick(){
		$pid=I('pid'); //评论id
		$user=session('ftuser');
		$views=explode(',', $user['onview']);   //已经点过赞的评论
		if(!in_array($pid, $views)){
			$this->error('');
		}
		$data['id']=$user['id'];
		$onview='';
		foreach ($views as $v){
			if($v!=$pid){
				$onview.=$v.',';
			}
		}
		
		$user['onview']=$data['onview']=substr($onview, 0,-1);
		if(D('member')->save($data)){
			$view=D('filmView')->find($pid);
			D('filmView')->where(array('id'=>$pid))->setInc('clickNum',-1);
			session('ftuser',$user);
			$filmView=D('filmView')->find($pid);
			session('ftuser',$user);
			$this->success('',$filmView['clickNum']);
		}else{
			$this->error('取消点赞失败');
		}
	}
	/**
	 * 添加评论
	 */
	function addView(){
		$filmId=I('filmId'); //评论id
		if(!empty($filmId)){
			$film=D('film')->find($filmId);
			$map['filmId']=$data['filmId']=$filmId;
			$data['filmName']=$film['filmName'];
		}
		$pid=I('pid'); //评论id
		if(!empty($pid)){
			$data['pid']=$pid;
		}else{
			$pid=0;
		}
		$data['uid'] = UID;
		$data['content']=I('content');
		$data['time']=time();
		$data['cinemaGroupId']=$this->weiXinInfo['cinemaGroupId'];
		if(D('film')->addView($data)){
			$map['status']=0;
			$map['pid']=$pid;
			$map['cinemaGroupId']=$this->weiXinInfo['cinemaGroupId'];
			$views=D('film')->getViews($map);
			$user=session('ftuser');
			if(!empty($user)){
				$myviews=explode(',', $user['onview']);  //点赞过的
				foreach ($views as $k=>$v){
					if(in_array($v['id'], $myviews)){
						$views[$k]['hasClick']='1';
					}else{
    					$views[$k]['hasClick']='0';
    				}
				}
			}
			$this->success('',$views);
		}else{
			$this->error('评论失败');
		}
	}


	public function voucherorder()
	{
		
		$couponsMap['userId'] = UID;
		$couponsMap['status'] = 3;
		$this->couponsList=D('Coupons')->getCouponsOrderList('', $couponsMap);
		// print_r($this->couponsList);
		$this->display();
	}
	
	/**
	 * 微信支付入口
	 */
	function tickin(){
		$servername=I('servername');
		if(!empty($servername)){
			session('ticketsn',$servername);
		}else{
			die('参数错误');
		}
		$this->redirect('ticket');
	}
	
	function ticket(){
		$servername=session('ticketsn');
		$cinemaCode=substr($servername, 0,8);
		$cinema=D('cinema')->find($cinemaCode);
		$payConfig=json_decode($cinema['payConfig'],true);
		$wxConfig=$payConfig['weixinpayConfigWap'];
		if(!empty($wxConfig['appid'])){
			$openid=session('openid'.$cinemaCode);
			if(empty($openid)){
				$openid=cookie('openid'.$cinemaCode);
			}
			$jsApi = new \Org\Wechat\Wxjspay($wxConfig);
			$jsurl=strtolower('http://' . $_SERVER['HTTP_HOST'] . U('',I('request.')));
			//$jsurl='http://testapi.zmaxfilm.com'. U('');
			if (empty($openid)&&empty($_GET['code'])){
				$url = $jsApi->createOauthUrlForCode(urlencode($jsurl));
				Header("Location: $url");
				die();
				 
			}elseif(empty($openid)){
				$code = $_GET['code'];
				$jsApi->setCode($code);
				$openid = $jsApi->getOpenId();
				session('openid'.$cinemaCode,$openid);
				cookie('openid'.$cinemaCode,$openid,3600);
			}else{
				cookie('openid'.$cinemaCode,$openid,3600);
				session('openid'.$cinemaCode,$openid);
			}
			$mod = M('user',' ','mysql://wangtao:wangtaotest@127.0.0.1:3306/worker_man#utf8');
			$wsdata=array('openid'=>$openid,'cinemaCode'=>$cinemaCode);
			$wsuser=$mod->where($wsdata)->find();
			if(empty($wsuser)){
				$access_token=wx_get_token($wxConfig['appid'],$wxConfig['appsecret']);
				$wxUserInfo=$jsApi->getUserInfo($access_token,$openid);
				if($wxUserInfo['subscribe']!='1'){
					$this->redirect('index/code');
				}else{
					$wsdata['time']=time();
					$wsdata['nickname']=$wxUserInfo['nickname'];
					$mod->add($wsdata);
				}
			}
			$data['appid']=$wxConfig['appid'];
			$data['timestamp']=$timestamp=time();
			$data['wxnonceStr']=$wxnonceStr = $jsApi->createNoncestr();
			$wxticket=wx_get_jsapi_ticket($wxConfig['appid'],$wxConfig['appsecret']);
			$wxOri = 'jsapi_ticket=' . $wxticket.'&noncestr='.$wxnonceStr.'&timestamp='.$timestamp.'&url='.$jsurl;
			$data['wxSha1']=$wxSha1 = sha1($wxOri);
		}else{
			die('未配置微信参数');
		}
		$this->assign('data',$data);
		$this->display();
	}
	
	
	/**
	 * 微信支付
	 * 主方法
	 * @param [get] [orderno] [订单编号]
	 */
	public function ticketmain() {
		//获取用户订单号（非必须）
		$fee=I('fee');
		$fee=0.01;
		//H5网页端调起支付接口
		$ticketsn=session('ticketsn');
		if(empty($ticketsn)){
			die('参数错误');
		}
		$cinemaCode=substr($ticketsn, 0,8);
		$cinemaInfo = D('Cinema')->getCinemaInfo('cinemaName, payConfig', array('cinemaCode' => $cinemaCode));
		$payConfig=json_decode($cinemaInfo['payConfig'],true);
		$wxConfig=$payConfig['weixinpayConfigWap'];
		if(empty($wxConfig['appid'])){
			die('未配置微信参数');
		}
		$openid=session('openid'.$cinemaCode);
		$usermod = M('user',' ','mysqli://wangtao:wangtaotest@127.0.0.1:3306/worker_man#utf8');
		$paylogmod = M('paylog',' ','mysqli://wangtao:wangtaotest@127.0.0.1:3306/worker_man#utf8');
		$wsdata=array('openid'=>$openid,'cinemaCode'=>$cinemaCode);
		$wsuser=$usermod->where($wsdata)->find();
		if(empty($wsuser)){
			die('未关注');
		}
		$payarr=array(
				'openid'=>$openid,
				'cinemaCode'=>$cinemaCode,
				'money'=>$fee,
				'time'=>time(),
				'nickname'=>$wsuser['nickname'],
				'mchId'=>$wxConfig['mchid'],
				'cinemaName'=>$cinemaInfo['cinemaName'],
		);
		wlog('$payarr'.arrayeval($payarr),'wxpay');
		$payid=$paylogmod->add($payarr);
		wlog('sql:'.$paylogmod->_sql(),'wxpay');
		if($payid){
			$jsApi = new \Org\Wechat\Wxjspay($wxConfig);
			//统一支付接口类
			$unifiedOrder = new \Org\Wechat\UnifiedOrder($wxConfig);
			/*-----------------------------必填--------------------------*/
			$unifiedOrder->setParameter('body', 'WAP支付' . $fee);//商品描述岚樨微支付平台
			$unifiedOrder->setParameter('out_trade_no', $payid . 'ticket'.$ticketsn);//商户订单号
			$unifiedOrder->setParameter('total_fee', $fee * 100);//总金额（微信支付以人民币“分”为单位）
			/*-------------------------------------------------------*/
			$unifiedOrder->setParameter('openid', $openid);//获取到的OPENID
			$unifiedOrder->setParameter('notify_url', C('PAY_URL').'home/ticket/weixinpay_wap.html');//通知地址
			$unifiedOrder->setParameter('trade_type', 'JSAPI');//交易类型
			
			$prepay_id = $unifiedOrder->getPrepayId();
			//=========步骤3：使用jsapi调起支付============
			$jsApi->setPrepayId($prepay_id);
			$jsApiParameters = $jsApi->getParameters();
			$this->jsApiParameters = $jsApiParameters;
			echo json_encode($this->jsApiParameters);
		}else{
			die('添加日志失败');
		}
	}
	
	
	
	
	
	
}