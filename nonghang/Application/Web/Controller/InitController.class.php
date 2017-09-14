<?php

namespace Web\Controller;
use Think\Controller;
class InitController extends Controller {
	protected $config = '';
	protected $wwwInfo = '';
	protected $loginCinemaCode = '';
	protected $pageNum = 5;
	protected $payConfig = '';
	protected $limit = 10;
	/**
	 * 系统基础控制器初始化
	 */
	public function _initialize(){
// 		print_r(ShearURL);
// 		die();
		$this->wwwInfo=session('wwwInfo');
		if(empty($this->wwwInfo)){
			$domain =$_SERVER['HTTP_HOST'];
			$this->wwwInfo = getWwwInfo($domain);
			if (empty($this->wwwInfo)) {
				die('非法访问');
			}
			session('wwwInfo',$this->wwwInfo);
		}
		//设置模板
		setTemp($this->wwwInfo['tempName']);
		$cinemaCode = I('request.cinemaCode');
		if(!empty($cinemaCode)){
			session('cinemaCode', $cinemaCode);
		}else{
			$cinemaCode=session('cinemaCode');
		}
		define('UID',is_login());
		$user=session('ftuser');
		$userInfo=$this->getBindUserInfo($user);
		$this->user=$userInfo;
		$headImage=C('IMG_URL').'Public/Web/zrfilm/images/user/user.png';
		if(!empty($userInfo['headImage'])){
			$headImage=C('IMG_URL').'Uploads/'.$userInfo['headImage'];
		}
		$this->assign('headImage',$headImage);
		$arrayActionNameNoLogin = array('register', 'login','useIntegral','cancelIntegral','cancelVoucher','useVoucher','cancelAccount','useAccount','backpw','getValidateCode','remain');
		$arrayModuleNameNoLogin=array('web/plan/pay', 'web/plan/paymentStatus');
		$arrayControllerNameNoLogin = array('web/user','web/public');
		if (in_array(strtolower(MODULE_NAME.'/'.CONTROLLER_NAME . '/' . ACTION_NAME), $arrayModuleNameNoLogin) || (in_array(strtolower(MODULE_NAME.'/'.CONTROLLER_NAME), $arrayControllerNameNoLogin)&&!in_array(ACTION_NAME, $arrayActionNameNoLogin))) {
			if (empty($userInfo)) {
				$this->assign('nologin',1);
				if(IS_AJAX){
					$this->error('请先登录');
				}
			}else{
				$this->payConfig['account'] = array(
						'type' => 'account',
						'icon' => C('IMG_URL').'Public/Web/default/images/pay-1.png',
						'name' => '余额支付',
						'instruction' => '您前帐户余额为' . floatval($userInfo['userMoney']) . '元',
						'userMoney' => floatval($userInfo['userMoney']),
						'isShow' => 1
				);
		
		
				$this->payConfig['alipay'] = array(
						'type' => 'alipay',
						'icon' => C('IMG_URL').'Public/Web/default/images/pay-2.png',
						'name' => '支付宝支付',
						'instruction' => '推荐安装支付宝的用户使用',
						'isShow' => 1
				);
		
				$this->payConfig['unionpay'] = array(
						'type' => 'unionpay',
						'icon' => C('IMG_URL').'Public/Web/default/images/pay-3.png',
						'name' => '银联支付',
						'instruction' => '支持储蓄卡信用卡，无需开通开通网银',
						'isShow' => 1
				);
			}
		}
	}


    public function success($content='', $dataList = array())
	{
		$data['status']  = 0;
        $data['content'] = $content;
        $data['data'] = $dataList;
        $this->ajaxReturn($data);
	}
	public function error($content='', $status = 1, $dataList = array())
	{
		$data['status']  = $status;
        $data['content'] = $content;
        $data['data'] = $dataList;
        $this->ajaxReturn($data);
	}
   
	/**
	 * 设置app展示用户信息
	 * @param unknown $mobile
	 */
	function setAppUserInfo($user){
		$appUserInfo['integral'] = intval($user['integral']);
		if(!empty($user['cardNum'])){
			$appUserInfo['userMoney'] = $user['basicBalance']+$user['donateBalance'];
			$bind=D('member')->getBindInfo(array('cardId'=>$user['cardNum'],'cinemaCode'=>$user['businessCode'],'cinemaGroupId'=>$this->wwwInfo['cinemaGroupId']));
			$appUserInfo['bindmobile']=$bind['mobile'];
			$appUserInfo['bindcardId']=$user['cardNum'];
			$appUserInfo['memberGroupId']=$user['memberGroupId'];
		}else{
			$appUserInfo['userMoney'] = $user['mmoney'];
			$appUserInfo['memberGroupId']=$user['memberGroupId'];
			$bind=D('member')->getBindInfo(array('mobile'=>$user['mobile'],'cinemaGroupId'=>$this->wwwInfo['cinemaGroupId']));
			$appUserInfo['bindcardId']=$bind['cardId'];
			$appUserInfo['bindmobile']=$user['mobile'];
			if (!empty($bind)) {
				$bindUserInfo = $this->getBindUserInfo($user);
				$appUserInfo['memberGroupId']=$bindUserInfo['memberGroupId'];
				$appUserInfo['integral'] = intval($bindUserInfo['integral']);
				$appUserInfo['userMoney'] = $bindUserInfo['basicBalance']+$bindUserInfo['donateBalance'];
			}
		}
	
	
		if (empty($user['headImage'])) {
			$appUserInfo['userIcon'] =C('IMG_URL').'Uploads/'. 'userIcons/default/defaultIcon.png';
		}else{
			$appUserInfo['userIcon'] = C('IMG_URL').'Uploads/'.$user['headImage'];
		}
		$appUserInfo['userMoney']=round( $appUserInfo['userMoney'],2);
		/*$userGroupList = S('userGroupList' . $this->wwwInfo['cinemaGroupId']);
		if (empty($userGroupList)) {
			$userGroupList = M('CinemaMemberGroup')->where(array('cinemaGroupId'=>$this->wwwInfo['cinemaGroupId']))->select();
			foreach ($userGroupList as $key => $value) {
				$newUserGroupList[$value['groupId']] = $value;
			}
			unset($userGroupList);
			$newUserGroupList['99101']['groupName'] = '注册会员';
			S('userGroupList' . $this->wwwInfo['cinemaGroupId'], $newUserGroupList, 7200);
			$userGroupList = $newUserGroupList;
		}*/
		// print_r($userGroupList);
		$appUserInfo['servicePhone'] = '110-110-110';
		$appUserInfo['updateTxt'] = '当前版本';
		$appUserInfo['userNickname'] = $user['otherName'];
		$appUserInfo['userSex'] = $user['sex'];
		$appUserInfo['userBirthday'] = $user['birthday'];
		$appUserInfo['email'] = $user['email'];
		//$appUserInfo['memberGroupName'] = $userGroupList[$appUserInfo['memberGroupId']]['groupName'];
	
		// wlog(json_encode($appUserInfo) . json_encode($_SERVER), 'testLog');
		return $appUserInfo;
	}
	
	/**
	 * 设置手机对应用户信息
	 * @param unknown $mobile
	 */
	function getBindUserInfo($user){
		if(!empty($user)){
			if(empty($user['cardNum'])){
				$bind=D('member')->getBindInfo(array('mobile'=>$user['mobile'],'cinemaGroupId'=>$this->wwwInfo['cinemaGroupId']));
				if(!empty($bind)){
					$cardUser=D('member')->getUser(array('cardNum'=>$bind['cardId'],'businessCode'=>$bind['cinemaCode'],'cinemaGroupId'=>$this->wwwInfo['cinemaGroupId']));
					if(!empty($cardUser)){
						$user=$cardUser;
					}
				}
			}
			$user=D('member')->find($user['id']);  //同步价格
			if(empty($user['cardNum'])){
				$user['userMoney']=round($user['mmoney'],2);
				$user['loginNum']=$user['mobile'];
			}else{
				$user['userMoney']=round($user['basicBalance']+$user['donateBalance'],2);
				$user['loginNum']=$user['cardNum'];
			}
		}
		return $user;
	}
	
	
	/**
	 * 2.12.1获取可以购票的支付方式
	 */
	public function getBuyPayway($type='',$orderId='',$cinemaCode=''){
	
		
		if ($type == 'round' && empty($cinemaCode)) {
			$this->error('参数错误！', '11001');
		}
	
		if (($type == 'film' || $type == 'goods') && empty($orderId)) {
			$this->error('参数错误！', '11001');
		}

		$userInfo = $this->user;
		if ($type == 'film') {//获得影票的支付渠道
			$orderInfo = S('getBuyPaywayOrderInfo'. $orderId);
			if (empty($orderInfo)) {
				$orderInfo = D('Order')->findObj($orderId);
				S('getBuyPaywayOrderInfo' . $orderId, $orderInfo, 900);
			}
			$otherPayInfo = json_decode($orderInfo['otherPayInfo'], true);
			$seatInfo = json_decode($orderInfo['seatInfo'], true);
			$copyType = strtolower($orderInfo['copyType']);
			if (strstr($copyType, 'max')) {
				$copyType = 'max';
			}
	
			// print_r($orderInfo);
			/*if (!isset($orderInfo['status']) || $orderInfo['status'] != 0) {
				$this->error('订单状态失效');
			}*/

			$planInfo = S('getBuyPaywayPlanInfo' . $orderInfo['featureAppNo']);
			if (empty($planInfo)) {
				$planInfo = D('Plan')->getplan($orderInfo['featureAppNo']);
				S('getBuyPaywayPlanInfo' . $orderInfo['featureAppNo'], $planInfo, 900);
			}
	
			$arraySetingConfig = S('getBuyPaywaySetingConfig' . $orderInfo['featureAppNo']);
			if (empty($arraySetingConfig)) {
				$arraySetingConfig = D('Voucher')->isVoucher($planInfo);
				S('getBuyPaywaySetingConfig' . $orderInfo['featureAppNo'], $arraySetingConfig, 900);
			}
			$weiXingConfig = getNowPayWay($planInfo['cinemaCode'], $orderId);

			$payConfig = $weiXingConfig['payConfig'];
			$onlinePay = $weiXingConfig['onlinePay'];

			// print_r($weiXingConfig);

			$typeClass = empty($orderInfo['otherPayInfo'][1]) ? 0 : 1;
	
			$price = $orderInfo['myPrice'] * count($seatInfo);
			$ticketPrice = $orderInfo['myPrice'];
	
			
			$voucherKey = 0;
			if (in_array('reduce', $onlinePay)) {
				$isShow = 0;
				if (( empty($otherPayInfo[0]) && empty( $otherPayInfo[1] ) ) || ( !empty($otherPayInfo[1]) && empty( $otherPayInfo[0] ) ) ) {
					$isShow = 1;
				}
		
				if (!empty($arraySetingConfig[1])) {
					$isCanUse = 0;
				}
				$voucerMap['memberId'] = $userInfo['id'];
				$voucerMap['validData'] = array('EGT', strtotime(date('Y-m-d')));
				$voucerMap['isUnlock'] = 0;
				$voucerMap['isUse'] = 0;
				$voucerMap['typeClass'] = 1;
				$memberVoucherList = D('Member')->getMemberVoucherList('typeId, voucherName, voucherNum, typeClass, voucherValue, createdDatetime, validData', $voucerMap);
				
				// print_r($memberVoucherList);
				$useNum = 0;
				$isCanUseNum = 0;
				foreach ($memberVoucherList as $key => $value) {
					$memberVoucherList[$key]['isUse'] = 0;
					$memberVoucherList[$key]['validDataStr'] = date('Y-m-d',$value['validData']);
					$canUserTypeList = array_keys($arraySetingConfig[1]);
		
					if (in_array($value['typeId'], $canUserTypeList)) {
						$memberVoucherList[$key]['isCanUse'] = 1;
						$isCanUseNum++;
					}else{
						$memberVoucherList[$key]['isCanUse'] = 0;
					}
					foreach ($otherPayInfo[1] as $vKey => $vValue) {
						foreach ($vValue as $k => $v) {
							if ($value['voucherNum'] == $v) {
								$memberVoucherList[$key]['isUse'] = 1;
								$price -= $arraySetingConfig[1][$vKey][$copyType];
								$useNum++;
							}
						}
		
					}
		
				}
		
				$rechargePayway['voucher'][$voucherKey] = array(
						'type' => 'voucher',
						'voucherClass' => 1,
						'name' => '立减券',
						//'content' => '您有' . $isCanUseNum . '张立减券可用',
						'content' => '',
						'list' => $memberVoucherList,
						'useNum' => intval(1 - $useNum),
						'isShow' => $isShow
				);
				if($isCanUseNum!='0'){
					$rechargePayway['voucher'][$voucherKey]['content']='（'.$isCanUseNum . '张可用）';
				}
				if (empty($memberVoucherList)) {
					unset($rechargePayway['voucher'][$voucherKey]['list']);
				}
				$voucherKey++;
				
			}
			$isUnCard = false;
			if (in_array('exchange', $onlinePay)) {
				$isShow = 0;
				
				if (empty($otherPayInfo[0]) && empty($otherPayInfo[1]) || empty($otherPayInfo[1]) && !empty($otherPayInfo[0])) {
					$isShow = 1;
				}
		
				$voucerMap['memberId'] = $userInfo['id'];
				$voucerMap['validData'] = array('EGT', strtotime(date('Y-m-d')));
				$voucerMap['isUnlock'] = 0;
				$voucerMap['isUse'] = 0;
				$voucerMap['typeClass'] = 0;
				$memberVoucherList = D('Member')->getMemberVoucherList('typeId, voucherName, voucherNum, typeClass, voucherValue, createdDatetime, validData', $voucerMap);
				// print_r($memberVoucherList);
				$useNum = 0;
				
				$isCanUseNum = 0;
				foreach ($memberVoucherList as $key => $value) {
					$memberVoucherList[$key]['isUse'] = 0;
					$memberVoucherList[$key]['validDataStr'] = date('Y-m-d',$value['validData']);
					$canUserTypeList = array_keys($arraySetingConfig[0]);
					if (in_array($value['typeId'], $canUserTypeList)) {
						$memberVoucherList[$key]['isCanUse'] = 1;
						$isCanUseNum++;
					}else{
						$memberVoucherList[$key]['isCanUse'] = 0;
					}
					

					foreach ($otherPayInfo[0] as $vKey => $vValue) {

						// print_r( $arraySetingConfig[0]);

						foreach ($vValue as $k => $v) {
							if ($value['voucherNum'] == $v) {
								$memberVoucherList[$key]['isUse'] = 1;
								$newPrice += $arraySetingConfig[0][$vKey][$copyType];
								$useNum++;
								$isUnCard = true;
							}
						}
		
					}
		
				}
				$price = $price - $ticketPrice * $useNum + $newPrice;
				$rechargePayway['voucher'][$voucherKey] = array(
						'type' => 'voucher',
						'voucherClass' => 0,
						'name' => '兑换券',
						//'content' => '您有' . $isCanUseNum . '张兑换券可用',
						'content' => '',
						'list' => $memberVoucherList,
						'useNum' => intval(count($seatInfo) - $useNum),
						'isShow' => $isShow
				);
				if($isCanUseNum!='0'){
					$rechargePayway['voucher'][$voucherKey]['content']='（'.$isCanUseNum . '张可用）';
				}
				if (empty($memberVoucherList)) {
					unset($rechargePayway['voucher'][$voucherKey]['list']);
				}
			}
			$price = $price >=0 ? $price : 0;
			$allIsShow = 1;
			if ($price == 0) {
				$allIsShow = 0;
			}
			unset($otherPayInfo[0], $otherPayInfo[0]);
			if (empty($otherPayInfo['integral']) && empty($otherPayInfo['account'])) {
				$otherPayInfo['account'] = '';
				$otherPayInfo['integral'] = '';
			}elseif (!empty($otherPayInfo['integral']) && empty($otherPayInfo['account'])) {
				$otherPayInfo['account'] = '';
			}elseif (empty($otherPayInfo['integral']) && !empty($otherPayInfo['account'])) {
				$otherPayInfo['integral'] = '';
			}
	
			$this->payConfig['account']['isUse'] = 0;
			foreach ($otherPayInfo as $key => $value) {
				// echo $key . '--' . $value .'---' . $price . '<br />';
				if ($key == 'integral' && in_array('integral', $onlinePay)) {
					if ($price == 0 || $allIsShow == 0) {
						$isShow = 0;
					}else{
						$isShow = 1;
					}
					$isIntegral = 0;
					// print_r($otherPayInfo);
					if (!empty($otherPayInfo['integral'])) {
						$isIntegral = 1;
	
						$allIntegral = $price * $this->wwwInfo['proportion'];
						if ($allIntegral >= $userInfo['integral']) {
							$useIntegral = $userInfo['integral'];
							$price -= round($userInfo['integral'] / $this->wwwInfo['proportion'],2);
						}else{
							$useIntegral = $allIntegral;
							$price = 0;
						}
	
					}
					if ($userInfo['integral'] <= 0) {
						$isShow = 0;
					}
					$rechargePayway['integral'][] = array(
							'type' => 'integral',
							'name' => '积分',
							'content' => '您有' . intval($userInfo['integral']) . '积分可用',
							'integral' => intval($userInfo['integral'] - $useIntegral),
							'proportion' => $this->wwwInfo['proportion'],
							'isUse' => $isIntegral,
							'isShow' => $isShow
					);
				}elseif ($key == 'account' && in_array('account', $onlinePay)) {
					if ($price == 0 || $allIsShow == 0) {
						$isShow = 0;
					}else{
						$isShow = 1;
					}
					if (!empty($otherPayInfo['account'])) {
						$this->payConfig['account']['isUse'] = 1;
						if ($price >= $userInfo['userMoney']) {
							$price -= $userInfo['userMoney'];
							$this->payConfig['account']['userMoney'] = 0;
						}else{
							$this->payConfig['account']['userMoney'] -= $price;
							$price = 0;
						}
	
					}
					if ($userInfo['userMoney'] <= 0) {
						$isShow = 0;
					}
					$this->payConfig['account']['isShow'] = $isShow;
				}
			}
	
			$order['price'] = $price > 0 ? $price : 0;
			$order['surplusTime'] = $orderInfo['lockTime'] - time();
			$rechargePayway['orderInfo'] = $order;
			$cinemaCode = $planInfo['cinemaCode'];
			if (!$isUnCard) {
				if (!in_array('account', $onlinePay)) {
					$isUnCard = true;
				}
			}


			if(empty($userInfo['cardNum']) || $isUnCard){

				foreach ($onlinePay as $key => $value) {
					if ($value == 'account') {
						if ($isUnCard && !empty($userInfo['cardNum'])) {
							$this->payConfig['account']['isShow'] = 0;
						}
						$rechargePayway['account'][] = $this->payConfig['account'];
					}elseif(strstr($value, 'pay')){
						if ($price == 0) {
							$this->payConfig[$value]['isShow'] = 0;
						}
						$rechargePayway['online'][] = $this->payConfig[$value];
					}
	
				}
			}else{
				if ($isUnCard) {
					$this->payConfig['account']['isShow'] = 0;
				}
				$rechargePayway['account'][] = $this->payConfig['account'];
			}
		}elseif ($type== 'goods') {//获得卖品的支付渠道
			$goodsOrderInfo = S('getBuyPaywayGoodsOrderInfo' . $orderId);
			if (empty($goodsOrderInfo)) {
				$goodsOrderInfo=D('Goods')->getGoodsOrderInfo('', array('id' => $orderId));
				S('getBuyPaywayGoodsOrderInfo' . $orderId, $goodsOrderInfo, 900);
			}

//			dump($goodsOrderInfo);
			
	
			$otherPayInfo = json_decode($goodsOrderInfo['otherPayInfo'], true);
	
			$cinemaCode = $goodsOrderInfo['cinemaCode'];
			
//			dump($cinemaCode);
//			$cinemaInfo = D('Cinema')->getCinemaInfoBycinemaCode('pcPayWay', $cinemaCode);
			$cinemaInfo = D('Cinema')->getCinemaInfoBycinemaCode('payWay', $cinemaCode);
			
			
			
//			dump($cinemaInfo);
			$price = $goodsOrderInfo['price'];
	
			$voucerMap['memberId'] = $userInfo['id'];
			$voucerMap['validData'] = array('EGT', strtotime(date('Y-m-d')));
			$voucerMap['isUnlock'] = 0;
			$voucerMap['isUse'] = 0;
			$voucerMap['typeClass'] = 2;
			$memberVoucherList = D('Member')->getMemberVoucherList('typeId, voucherName, voucherNum, typeClass, voucherValue, createdDatetime, validData', $voucerMap);
			
//			dump($memberVoucherList);
			$useNum = 0;
			foreach ($memberVoucherList as $key => $value) {
				$memberVoucherList[$key]['isUse'] = 0;
				
				$memberVoucherList[$key]['validDataStr'] = date('Y-m-d',$value['validData']);
				foreach ($otherPayInfo[2] as $vKey => $vValue) {
					foreach ($vValue as $k => $v) {
	
	
						$voucherInfo = D('Voucher')->checkVoucher($v);
						if ($value['voucherNum'] == $v && $voucherInfo['status'] == 0) {
							$memberVoucherList[$key]['isUse'] = 1;
							$useNum++;
							$price = $price - intval($voucherInfo['data']['typeValue']);
						}
					}
	
				}
	
			}
	
			$rechargePayway['voucher'][0] = array(
					'type' => 'voucher',
					'voucherClass' => 2,
					'name' => '卖品券',
//					'content' => '您有' . count($memberVoucherList) . '张卖品券可用',
					'content' => count($memberVoucherList). '张可用',
					'canusenum' => count($memberVoucherList),
					'list' => $memberVoucherList,
					'useNum' => intval(1 - $useNum),
					'isShow' => 1
			);
	
			if (empty($memberVoucherList)) {
				unset($rechargePayway['voucher'][0]['list']);
			}
	
	
	
			if (empty($otherPayInfo['integral']) && empty($otherPayInfo['account'])) {
				$otherPayInfo['account'] = '';
				$otherPayInfo['integral'] = '';
			}elseif (!empty($otherPayInfo['integral']) && empty($otherPayInfo['account'])) {
				$otherPayInfo['account'] = '';
			}elseif (empty($otherPayInfo['integral']) && !empty($otherPayInfo['account'])) {
				$otherPayInfo['integral'] = '';
			}
			$price = $price >=0 ? $price : 0;
			$this->payConfig['account']['isUse'] = 0;
			foreach ($otherPayInfo as $key => $value) {
				if ($key == 'integral') {
					if ($price == 0) {
						$isShow = 0;
					}else{
						$isShow = 1;
					}
					$isShow = 0;
					$isIntegral = 0;
					if (!empty($otherPayInfo['integral'])) {
						$isIntegral = 1;
	
						$allIntegral = $price * $this->wwwInfo['proportion'];
						if ($allIntegral >= $userInfo['integral']) {
							$useIntegral = $userInfo['integral'];
							$price -= round($userInfo['integral'] / $this->wwwInfo['proportion'],2);
						}else{
							$useIntegral = $allIntegral;
							$price = 0;
						}
					}
					if ($userInfo['integral'] <= 0) {
						$isShow = 0;
					}
					// print_r($useIntegral);
					$rechargePayway['integral'][] = array(
							'type' => 'integral',
							'name' => '积分',
							'content' => '您有' . intval($userInfo['integral']) . '积分可用',
							'integral' => intval($userInfo['integral'] - $useIntegral),
							'proportion' => $this->wwwInfo['proportion'],
							'isUse' => $isIntegral,
							'isShow' => $isShow
					);
				}elseif ($key == 'account') {
					if ($price == 0) {
						$isShow = 0;
					}else{
						$isShow = 1;
					}
					if (!empty($otherPayInfo['account'])) {
	
						$this->payConfig['account']['isUse'] = 1;
						if ($price >= $userInfo['userMoney']) {
							$price -= $userInfo['userMoney'];
							$this->payConfig['account']['userMoney'] = 0;
						}else{
							$this->payConfig['account']['userMoney'] -= $price;
							$price = 0;
						}
					}
					if ($userInfo['userMoney'] <= 0) {
						$isShow = 0;
					}
					$this->payConfig['account']['isShow'] = $isShow;
					
					
					if(!empty($userInfo['cardNum'])){
					
						$this->payConfig['account']['isShow'] = 0;
					}
				}
	
			}
	
			$order['price'] = $price > 0 ? $price : 0;
			$rechargePayway['orderInfo'] = $order;
	
			if (!isset($this->payConfig['account']['isShow'])) {
				$this->payConfig['account']['isShow'] = 0;
				
				
			}
	
			// print_r($cinemaInfo);
			if (!empty($cinemaInfo['pcPayWay'])) {
				$rechargePaywayArray = explode(',', $cinemaInfo['pcPayWay']);
			}
			if (!empty($cinemaInfo['payWay'])) {
				$payWay = json_decode($cinemaInfo['payWay'],true);
				
				
				$rechargePaywayArray =$payWay['pcPayWay'];
//				dump($payWay);
			}
//			dump($payWay);
//			 dump($rechargePaywayArray);
			foreach ($rechargePaywayArray as $key => $value) {
				if(empty($userInfo['cardNum']) || $value == 'account'){
					if ($value == 'account') {
						$rechargePayway['account'][] = $this->payConfig['account'];
					}elseif(strstr($value, 'pay')){
						if ($price == 0) {
							$this->payConfig[$value]['isShow'] = 0;
						}
						$rechargePayway['online'][] = $this->payConfig[$value];
					}
				}else{
					if(strstr($value, 'pay')){
						if ($price == 0) {
							$this->payConfig[$value]['isShow'] = 0;
						}
						$rechargePayway['online'][] = $this->payConfig[$value];
				
					}
				
				
				}
			}
			
//			dump($this->payConfig);
	
		}elseif ($type== 'round') { //获得团购的支付渠道
			$cinemaInfo = D('Cinema')->getCinemaInfoBycinemaCode('pcPayWay', $cinemaCode);
			if (!empty($cinemaInfo['pcPayWay'])) {
				$rechargePaywayArray = explode(',', $cinemaInfo['pcPayWay']);
			}
	
			foreach ($rechargePaywayArray as $key => $value) {
				if($value != 'account'){
					$rechargePayway['online'][] = $this->payConfig[$value];
				}
			}
		}
		return $rechargePayway;
	}
	
	/**
	 * 2.7.1获取可以充值的支付方式
	 */
	public function getRechargePayway($cinemaCode){
		if(empty($cinemaCode)){
			$this->error('参数错误！', '11001');
		}
		$cinemaInfo = D('Cinema')->getCinemaInfoBycinemaCode('payWay', $cinemaCode);
		$payway=json_decode($cinemaInfo['payWay'],true);
		foreach ($payway['pcPayWay'] as $key => $value) {
			if ( !in_array($value, array('account','exchange','reduce','sale','integral'))) {
				$rechargePayway[] = $this->payConfig[$value];
			}
		}
		return $rechargePayway;
	
	}
	
	/**
	 * 设置会员卡对应用户信息
	 * @param unknown $mobile
	 */
	function getBindCardInfo($user){
		if(!empty($user)){
			if(!empty($user['cardNum'])){
				$bind=D('member')->getBindInfo(array('cardId'=>$user['cardNum'],'cinemaCode'=>$user['businessCode'],'cinemaGroupId'=>$this->weiXinInfo['cinemaGroupId']));
				if(!empty($bind)){
					$mobileUser=D('member')->getUser(array('mobile'=>$bind['mobile'],'cinemaGroupId'=>$this->weiXinInfo['cinemaGroupId']));
					if(!empty($mobileUser)){
						$user=$mobileUser;
					}
				}
			}
		}
		return $user;
	}
	
	/**
     *
     * @param int $limitCount 
     * @param int $pageLength 
     * @return string
     */
	public function getPageList($limitCount , $pageLength, $map = ''){
		$Page       = new \Think\Page($limitCount,$pageLength); // 实例化分页类 传入总记录数和每页显示的记录数
		$config  = array(
	        'prev'   => '上一页',
	        'next'   => '下一页',
	        'first'  => '首页',
	        'last'   => '最后一页',
	        'theme'  => '%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%',
	    );
		foreach($config as $key=>$val){
			$Page->setConfig($key , $val);
		}

		foreach($map as $key=>$val) {
		    $Page->parameter[$key]   =   $val;
		}

		$show  = $Page->show();
		
		if($show){
			$show = '<div class="pagination"><div class="page">' . $show . '</div></div>';
		}
		return $show ;// 分页显示输出
	}
	/**
	 *
	 * @param int $page
	 * @param int $count
	 * @return Ambigous <number, unknown>
	 */
	function curPage($page,$count){
		if($page < 0 || empty($page)){
			$page=1;
		}elseif($page > $count){
			$page = $count;
		}
		return $page;
	}
	
	
	
	
	
	
	
}