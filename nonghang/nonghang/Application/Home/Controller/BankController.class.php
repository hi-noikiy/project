<?php
/**
 * 影片数据相册展示
 */

namespace Home\Controller;
use Think\Controller;
class BankController extends InitController {
	

	public function buying() {
		$userInfo = $this->getBindUserInfo(session('ftuser'));

		$this->userOrderInfo = D('Coupons')->checkCouponsOrder($userInfo['id']);
		// print_r($this->userOrderInfo);

		$couponsMap['buyingEndTime'] = array('egt',time());
		$this->couponsList = D('Coupons')->couponsList('', $couponsMap, '', 'buyingStartTime asc');
		$week[1] = '一';
		$week[2] = '二';
		$week[3] = '三';
		$week[4] = '四';
		$week[5] = '五';
		$week[6] = '六';
		$week[0] = '日';
		$this->week = $week;
		// print_r($this->couponsList);
		$this->display();
	}
	
	public function pay() {


		$couponId = I('request.couponId');
		$sum = I('request.sum');
		$couponOrderId = (int)I('request.couponOrderId');

		$userInfo = $this->getBindUserInfo(session('ftuser'));

		//统计用户订单数
		$couponMap['userId'] = $userInfo['id'];
		// $couponMap['couponId'] = $couponId;
		$couponMap['orderTime'] = array(array('gt',strtotime(date('Y-m-d') . ' 00:00:00')),array('lt',strtotime(date('Y-m-d') . ' 23:59:59')), 'and');
		$couponMap['status'] = 3;
		$userCouponsOrderList = D('Coupons')->getCouponsOrderInfo('sum(couponSum) as couponSum', $couponMap);

		if ($userCouponsOrderList['couponSum'] + $sum > 2) {
			$this->error('一个会员帐号,同一天仅限购买2张！');
		}

		if ($couponOrderId != 0) {
			$orderMap['userId'] = $userInfo['id'];
			$orderMap['couponId'] = $couponId;
			$orderMap['couponOrderId'] = $couponOrderId;
			$orderMap['status'] = 0;
			$orderMap['orderTime'] = array('gt', time() - 600);
			$couponInfo = D('Coupons')->getCouponsOrderInfo('', $orderMap);
			if (empty($couponInfo)) {
				$this->error('继续支付失败，请重试！');
			}
			$orderId = $couponInfo['couponOrderId'];
			$buyAmount = $couponInfo['couponPrice'];
		}else{
			if ($sum > 2) {
				$this->error('一个会员帐号仅限购买2张！');
			}

			$couponInfo = D('Coupons')->getCouponsInfo('couponName, voucherType, couponSum, newPrice, couponId, voucherStartDate, voucherEndDate', array('couponId'=>$couponId));
			if ($couponInfo['couponSum'] < $sum) {
				$this->error('数量不足，请重新下单！');
			}

			$getSurplusSum = D('Coupons')->getSurplusSum($couponId);

			if ($getSurplusSum['couponSum'] < $sum) {
				$this->error('数量不足，请重新下单！');
			}

			//开始添加订单
			$orderData['userId'] = $userInfo['id'];
			$orderData['cinemaGroupId'] = $this->weiXinInfo['cinemaGroupId'];
			$orderData['couponId'] = $couponId;
			$orderData['couponName'] = $couponInfo['couponName'];
			$orderData['voucherType'] = $couponInfo['voucherType'];
			$orderData['voucherStartDate'] = $couponInfo['voucherStartDate'];
			$orderData['voucherEndDate'] = $couponInfo['voucherEndDate'];
			$orderData['couponPrice'] = $couponInfo['newPrice'] * $sum;
			$orderData['couponSum'] = $sum;
			$orderData['orderTime'] = time();
			$orderData['status'] = 0;
			$orderId = D('Coupons')->addOrder($orderData);
			if(!$orderId){
				$this->error('生成订单失败！');
			}
			$buyAmount = $couponInfo['newPrice'] * $sum;
		}
			

		$weiXingConfig = getNowPayWay('35013801', $couponId);

		$payConfig = $weiXingConfig['payConfig'];
		$onlinePay = $weiXingConfig['onlinePay'];
		$cinemaName = $weiXingConfig['cinemaName'];

		$abchinapayConfig = $payConfig['abchinaConfing'];
		$config['TrustPayConnectMethod'] = 'https';
		$config['TrustPayServerName'] = 'pay.abchina.com';
		$config['TrustPayServerPort'] = '443';
		$config['TrustPayNewLine'] = '2';
		$config['PaymentType'] = '1';
		$config['NotifyType'] = '1';
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
		$abchinaOrderNo = date('YmdHis') . 'N' . $orderId;
		$tRequest = new \Think\Pay\Abchina\PaymentRequest();
		$tRequest->order["OrderNo"] = $abchinaOrderNo; //设定订单编号
		$tRequest->order["OrderAmount"] = $buyAmount; //设定交易金额

		$tRequest->order["OrderDesc"] = $cinemaName . '购券，订单号:' . $orderId; //设定订单说明
		$tRequest->order["OrderDate"] = date('Y/m/d'); //设定订单日期 （必要信息 - YYYY/MM/DD）
		$tRequest->order["OrderTime"] = date('H:i:s'); //设定订单时间 （必要信息 - HH:MM:SS）
		$tRequest->order["orderTimeoutDate"] = date('YmdHis', time() + 600); //设定订单有效期
		//3、生成支付请求对象
		$tRequest->request["PaymentLinkType"] = '2'; //设定支付接入方式
// 6228480068623866776
		$successUrl = C('SERVER_URL') . ',bank,paystatus,' . session('token') ;

		$sign = md5($orderId . $successUrl . C('singKey'));
		$tRequest->request["ResultNotifyURL"] = C('PAY_URL').'voucher/abchinapay_wap/orderId/' . $orderId .'/successUrl/' . $successUrl . '/sign/' . $sign;//设定通知URL地址

		$tResponse = $tRequest->postRequest($config);
		if ($tResponse->isSuccess()) {

			D('Coupons')->setCouponsInfo(array('couponOrderId'=>$orderId), array('otherPayInfo'=>$abchinaOrderNo));
		    $PaymentURL = $tResponse->GetValue("PaymentURL");
		    $this->success('',$PaymentURL);
		}else{
			$this->error('订单创建失败！');
		}
	}
	

	public function cancelOrder($couponOrderId)
	{
		$userInfo = $this->getBindUserInfo(session('ftuser'));
		$orderMap['userId'] = $userInfo['id'];
		$orderMap['couponOrderId'] = $couponOrderId;
		$couponInfo = D('Coupons')->getCouponsOrderInfo('couponOrderId', $orderMap);
		// print_r($couponInfo);
		if (!empty($couponInfo)) {
			if(D('Coupons')->cancelCouponsOrder($couponOrderId)){
				$this->success('取消成功');
			}else{
				$this->error('取消失败');
			}
		}
	}

	public function checkOrderNum($couponId)
	{
		$this->checkUserOrderNum();
		$resultData = D('Coupons')->getSurplusSum($couponId);
		$resultData['couponSum'] = $resultData['couponSum'] >=0 ? $resultData['couponSum'] : 0;
		$this->success('', $resultData);
	}

	public function checkUserOrderNum()
	{
		$userInfo = $this->getBindUserInfo(session('ftuser'));
		$userOrderInfo = D('Coupons')->checkCouponsOrder($userInfo['id']);
		if (!empty($userOrderInfo)) {
			$this->error('已有未支付订单', '20002', $userOrderInfo);
		}
	}

	public function checkSum($couponId)
	{

		$resultData = S('getCouponsInfo' . $couponId);
		if (empty($couponSum)) {
			$resultData = D('Coupons')->getSurplusSum($couponId);
			$resultData['couponSum'] = $resultData['couponSum'] >=0 ? $resultData['couponSum'] : 0;
			S('getCouponsInfo' . $couponId, $resultData, 5);
		}
		$this->success('', $resultData);
	}

	public function paystatus()
	{
		if (IS_AJAX) {
			$userInfo = $this->getBindUserInfo(session('ftuser'));
			$orderMap['couponOrderId'] = I('request.orderid');
			$orderMap['userId'] = $userInfo['id'];
			$couponInfo = D('Coupons')->getCouponsOrderInfo('status', $orderMap);
			$this->success('', $couponInfo);
		}else{
			$this->orderId = I('request.orderid');
			$userInfo = $this->getBindUserInfo(session('ftuser'));
			$orderMap['couponOrderId'] = $this->orderId;
			$orderMap['userId'] = $userInfo['id'];
			$this->couponInfo = D('Coupons')->getCouponsOrderInfo('status', $orderMap);
			$this->display();
		}

	}
}