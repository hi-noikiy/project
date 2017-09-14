<?php
/**
 * 公用控制器
 * 
 * @author 王涛
 * @package home
 */
namespace Web\Controller;
use Think\Controller;
class PublicController extends InitController {
    /**
     * 生成验证码
     */
    function verity(){
    	$type=I('type');
    	ob_clean();
    	verify_c($type);
    }
    /**
     * 获取短信验证码
     * @param
     * @return result
     *        {
     *            "status": "0",
     *            "data": md5(tokenId)
     *        }xmmgf123
     * @author
     */
    public function getValidateCode() {
    	$userMobile=I('userMobile');
    	$codeType=I('codeType');
    	if(empty($userMobile)){
    		$this->error('请输入手机号',1,1);
    	}
    	if(!checkMobile($userMobile)){
    		$this->error('手机格式不正确',1,1);
    	}
    	if(empty($codeType)){
    		$this->error('参数错误！', '11001');
    	}
    	$codeInfo = S('tokenId_getMobileVerification' . $userMobile . $codeType);
    	if(($codeInfo['time'] + 60) >= time()){
    		$this->error('您发送的太频繁，请稍后再试');
    	}
    	$memberMap['mobile'] = $userMobile;
    	$memberMap['cinemaGroupId'] = $this->wwwInfo['cinemaGroupId'];

    	if(in_array($codeType, array('register','find'))){
    		if($codeType=='register'){
    			$hasUser=D('member')->getUser(array('mobile'=>$userMobile));
    			if(!empty($hasUser)){
    				$this->error('该手机用户已存在',1,1);
    			}
    		}
    		$verify=I('verify');
    		if(!check_verify($verify,$codeType)){
    			$this->error('验证码填写错误',1,2);
    		}
    	}
    	$memberInfo = D('Member')->getUser( $memberMap);
    
    	$code = rand(100000, 999999);
    
    	if($codeType == 'find'){
    		if(empty($memberInfo)){
    			$this->error('手机号不存在！',1,1);
    		}
    		$content = '请输入验证码：' . $code . '，验证码2分钟之内有效，超时请重新获取。';
    		//$content = '您正在申请找回密码，校验码是：' . $code . '，验证码2分钟之内有效，超时请重新获取。';
    
    	}elseif ($codeType == 'register') {
    		if(!empty($memberInfo)){
    			$this->error('手机号已被注册！',1,1);
    		}
    		$content = '请输入验证码：' . $code . '，验证码2分钟之内有效，超时请重新获取。';
    	}elseif ($codeType == 'bind') {
    
    		$hasUser=D('Member')->getBindInfo(array('mobile'=>$userMobile,'cinemaGroupId'=>$this->wwwInfo['cinemaGroupId']));
    
    		if (!empty($hasUser)) {
    			$this->error('手机号已被绑定！',1,1);
    		}
    		$content = '请输入验证码：' . $code . '，验证码2分钟之内有效，超时请重新获取。';
    	}elseif ($codeType == 'unbind') {
    
    		$content = '请输入验证码：' . $code . '，验证码2分钟之内有效，超时请重新获取。';
    	}else{
    		wlog('参数校验信息错误！' . $userMobile, 'testLog');
    		$this->error('参数校验信息错误！');
    	}
    	$smsConfig['smsType'] = $this->wwwInfo['smsType'];
    	$smsConfig['smsAccount'] = $this->wwwInfo['smsAccount'];
    	$smsConfig['smsPassword'] = $this->wwwInfo['smsPassword'];
    	$smsConfig['smsSign'] = $this->wwwInfo['smsSign'];
    	$sms = new \Think\SmsModel($smsConfig);
    	$smsResult = $sms->sendSms($userMobile, $content);
    	if($smsResult['code'] != 1){
    		wlog(json_encode($smsResult),'getrechargeOrderStatus');
    		$this->error($smsResult['text']);
    	}else{
    		$codeInfo['code'] = $code;
    		$codeInfo['time'] = time();
    		$successData['deadline'] = 120;
    		S('tokenId_getMobileVerification' . $userMobile . $codeType, $codeInfo, 120);
    		wlog('验证码发送成功！' .$userMobile, 'testLog');
    		$this->success('发送成功，2分钟内有效！', $successData);
    	}
    
    }
    /**
     * 获取支付方式
     */
    public function getBuyPaywayJson(){
    	$orderId = I('request.orderId');
    	$type = I('request.type');
    	$buyPayway = $this->getBuyPayway($type,$orderId);
//     	print_r($buyPayway);
//     	die();
    	$this->success('', $buyPayway);
    	 
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
    	// $userInfo = $this->getBindUserInfo($this->appInfo['userInfo']);
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
    	// $userInfo = $this->getBindUserInfo($this->appInfo['userInfo']);
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
    	$ty=I('ty');
    	$voucherNum=I('voucherNum');
    	$type=I('type');
    	if(empty($voucherNum)){
    		$this->error('请使用券码');
    	}
    	if(empty($orderId)  || empty($type)){
    		$this->error('参数错误！', '11001');
    	}
    
    	//验证票券状态
    	$voucherInfo = D('Voucher')->checkVoucher($voucherNum);
    	if ($voucherInfo['status'] ==1) {
    		$this->error($voucherInfo['content']);
    	}
    	if($ty=='0'||$ty=='1'||$ty=='2'){
    		if($voucherInfo['data']['typeClass']!=$ty){
    			$this->error('票券类型不同，无法绑定使用！');
    		}
    	}
    	//取得用户信息
    	$userInfo=$this->user;
    	//取得订单信息
    
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
    		foreach ($arraySetingConfig[$voucherInfo['data']['typeClass']] as $key => $value) {
    			if ($key == $voucherInfo['data']['typeId']) {
    				$flag = true;
    				break;
    			}
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
    
    
    	//判断是否在券包中
    	$checkVoucherInfo = D('Member')->checkVoucher($voucherNum);
    	if(empty($checkVoucherInfo)){
    		//开始加入券包
    		$data['memberId'] = $userInfo['id'];
    		$data['voucherName'] = $voucherInfo['data']['typeName'];
    		$data['voucherNum'] = $voucherInfo['data']['voucherNumber'];
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
    	// $userInfo = $this->getBindUserInfo($this->appInfo['userInfo']);
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
    	// $userInfo = $this->getBindUserInfo($this->appInfo['userInfo']);
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
     * 余座
     */
    function remain(){
    	$featureAppNo=I('featureAppNo');
    	$plan=D('plan')->getplanInfo('cinemaCode, cinemaName,featureAppNo, listingPrice,startTime, filmNo, filmName, hallName, priceConfig, hallNo, otherfilmNo, featureNo, startTime, totalTime, copyType', $featureAppNo);
    	$cinema=D('cinema')->find($plan['cinemaCode']);
    	$remain=S('remain'.$this->wwwInfo['cinemaGroupId'].$plan['cinemaCode'].$featureAppNo);
    	if(empty($remain)){
    		$seats=D('ZMMove')->getPlanSiteState(array('cinemaCode'=>$plan['cinemaCode'],'featureAppNo'=>$featureAppNo,'link'=>$cinema['link'],'hallNo'=>$plan['hallNo'],'filmNo'=>$plan['otherfilmNo'],'showSeqNo'=>$plan['featureNo'],'planDate'=>$plan['startTime']));
    		$remain=D('seat')->remain($seats['PlanSiteState']);
    		S('remain'.$this->wwwInfo['cinemaGroupId'].$plan['cinemaCode'].$featureAppNo,$remain,60);
    	}
    	$this->success('',$remain);
    }
}