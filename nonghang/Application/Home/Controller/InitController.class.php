<?php

namespace Home\Controller;
use Think\Controller;
class InitController extends Controller {
	protected $config = '';
	protected $weiXinInfo = '';
	protected $loginCinemaCode = '';
	protected $pageNum = 999;
	protected $payConfig = '';
	/**
	 * 系统基础控制器初始化
	 */
	public function _initialize(){
		$token = I('request.token');
		if (empty($token)) {
			$token = cookie('token');
			session('token', $token);
		}else{
			session('token', $token);
			cookie('token', $token, 3600*24*7);
		}
		if (empty($token)) {
			die('非法访问');
		}
		//获取微信的相关配置
		$this->weiXinInfo = getWeiXinInfo($token);
		//设置模板
		setTemp($this->weiXinInfo['tempName']);		

		$cinemaCode = I('request.cinemaCode');
		if(!empty($cinemaCode)){
			session('cinemaCode', $cinemaCode);
		}else{
			$cinemaCode=session('cinemaCode');
		}

		$cinemaList = explode(',', $this->weiXinInfo['cinemaList']);
		if (!in_array($cinemaCode, $cinemaList)) {
			$cinemaCode = $cinemaList[0];
			session('cinemaCode', $cinemaCode);
		}


		$op = I('request.op');
		if(!empty($op)){
			session('op', $op);
		}

	
		$pflag=I('request.pflag');
		if(!empty($pflag)){
			session('pflag', $pflag);
		}else{
			$pflag=session('pflag');
		}
		$this->loginCinemaCode=$pflag;
		define('UID',is_login());
		$noLoginArray = array('home/public', 'home/index', 'home/bank');
		if(!in_array(strtolower(MODULE_NAME . '/' . CONTROLLER_NAME), $noLoginArray)){
			if( !UID ){// 还没登录 跳转到登录页面
				if(!in_array(strtolower(MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME), array('home/user/ticket','home/user/tickin','home/user/ticketmain'))&& !(!empty($_GET['code']) && in_array(strtolower(MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME),array('home/user/recharge','home/user/payment','home/user/pay','home/user/paygoods')) )){
					$url_page=$_SERVER["REQUEST_URI"];

					if (I('request.thisUrl')) {
						session('url', I('request.thisUrl'));
					}elseif ($op == 'user') {
						session('url',U('user/user'));
					}else if(strtolower(ACTION_NAME) == 'indent'){
						session('datas', I('request.datas'));
						session('url', U('User/indent', array('featureAppNo' => I('request.featureAppNo'))));
					}else{
						session('url',$url_page);
					}
					if (IS_AJAX && IS_POST) {
	            		$this->error('未登录', '20001', U('public/login'));
	            	}
					$this->redirect('public/login');
				}
			}

		}
	
	$arrayActionNameNoLogin = array('registered', 'login', 'findpasswd', 'buying', 'checksum','checkUserOrderNum','ticket','tickin','ticketmain');
	 if (in_array(strtolower(MODULE_NAME . '/' . CONTROLLER_NAME), array('home/user', 'home/shake', 'home/bank')) && !in_array(strtolower(ACTION_NAME), $arrayActionNameNoLogin)) {
            $userInfo = $this->getBindUserInfo(session('ftuser'));
            // print_r($userInfo);
            if (empty($userInfo)) {
            	if (IS_AJAX) {
            		$sessionUrl = I('request.thisUrl') ? I('request.thisUrl') : U();
            		session('url', $sessionUrl);
            		$this->error('未登录', '20001');
            	}
                $this->redirect('public/login');
            }else{
                $this->payConfig['account'] = array(
                    'type' => 'account',
                    'icon' => C('IMG_URL').'Public/Home/default/images/movie/balance.png',
                    'name' => '余额支付',
                    'instruction' => '您前帐户余额为' . floatval($userInfo['userMoney']) . '元',
                    'userMoney' => floatval($userInfo['userMoney']),
                    'isShow' => 1
                );


                $this->payConfig['weixinpay'] = array(
                    'type' => 'weixinpay',
                    'icon' => C('IMG_URL').'Public/Home/default/images/movie/weixin.png',
                    'name' => '微信支付',
                    'instruction' => '推荐WAP端使用',
                    'isShow' => 1
                );

                $this->payConfig['abchinapay'] = array(
                    'type' => 'abchinapay',
                    'icon' => C('IMG_URL').'Public/Home/bank/images/movie/nhbank.png',
                    'name' => '农行支付',
                    'instruction' => '推荐持有农行掌上银行与K码支付客户使用',
                    'isShow' => 1
                );
            }
        }
		$TMPL_PARSE_STRING = C('TMPL_PARSE_STRING');
		
		$this->config['uploadUrl'] = $TMPL_PARSE_STRING['__UPLOAD__'] . '/';
		$this->config['imgUrl'] = $TMPL_PARSE_STRING['__IMG__'];
		$this->config['__JS__'] = $TMPL_PARSE_STRING['__JS__'];
        $this->config['cinemaCode'] = $cinemaCode;
        $this->config['planajax'] = U('index/planajax');
        $this->config['details'] = U('index/details');
        $this->config['indexSeat'] = U('Index/seat');
        $this->config['userIndent'] = U('User/indent');
        $this->config['userIndex'] = U('User/user');
        $this->config['userPay'] = U('User/pay');
        $this->config['userSeatLock'] = U('User/seatLock');
        $this->config['cinemaajax'] = U('index/cinemaajax');
        $this->config['cinemaplanajax'] = U('index/cinemaplanajax');
        $this->config['UID'] = is_login();
        $this->config['bindurl'] = U('user/setUserBind');
        $this->config['unbindurl'] = U('user/setUserUnBind');
        $this->config['userRecord'] = U('user/record');
        $this->config['otherpayMain'] = U('user/main');
        $this->config['payingUrl'] = U('user/haspaying',array('status'=>0));
        $this->config['codeUrlUrl'] = U('user/haspaying',array('status'=>3));
        $this->config['userCode'] = U('User/code');
        $this->config['cancelOrder'] = U("cancelOrder",array("orderid"=>$order["orderCode"]));
		$this->config['userPaying'] = U('User/paying');
		$this->config['getOrderList'] = U('User/orderajax1');
		$this->config['getRecordList'] = U('User/recordajax');
		$this->config['userFeedback'] = U('User/feedback');
		$this->config['indexHasfilm'] = U('index/hasfilm');
		$this->config['payround'] = U('user/setRoundOrder');
		$this->config['userSexajax'] = U('user/setUserInfo');
		$this->config['userInfo'] = U('user/info');
		$this->config['userNameajax'] = U('user/nameajax');
		$this->config['userPwdajax'] = U('user/pwdajax');



        // print_r($this->config);
        
        $this->assign('config',$this->config);		
        $this->assign('pflag',$pflag);		
    }


    public function success($content, $dataList = array())
	{
		$data['status']  = 0;
        $data['content'] = $content;
        $data['data'] = $dataList;
        $this->ajaxReturn($data);
	}

	public function error($content, $status = 1, $dataList = array())
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
			$bind=D('member')->getBindInfo(array('cardId'=>$user['cardNum'],'cinemaCode'=>$user['businessCode'],'cinemaGroupId'=>$this->weiXinInfo['cinemaGroupId']));
			$appUserInfo['bindmobile']=$bind['mobile'];
			$appUserInfo['bindcardId']=$user['cardNum'];
			$appUserInfo['memberGroupId']=$user['memberGroupId'];
		}else{
			$appUserInfo['userMoney'] = $user['mmoney'];
			$appUserInfo['memberGroupId']=$user['memberGroupId'];
			$bind=D('member')->getBindInfo(array('mobile'=>$user['mobile'],'cinemaGroupId'=>$this->weiXinInfo['cinemaGroupId']));
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
		/*$userGroupList = S('userGroupList' . $this->appInfo['cinemaGroupId']);
		if (empty($userGroupList)) {
			$userGroupList = M('CinemaMemberGroup')->where(array('cinemaGroupId'=>$this->appInfo['cinemaGroupId']))->select();
			foreach ($userGroupList as $key => $value) {
				$newUserGroupList[$value['groupId']] = $value;
			}
			unset($userGroupList);
			$newUserGroupList['99101']['groupName'] = '注册会员';
			S('userGroupList' . $this->appInfo['cinemaGroupId'], $newUserGroupList, 7200);
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


				$bind=D('member')->getBindInfo(array('mobile'=>$user['mobile'],'cinemaGroupId'=>$this->weiXinInfo['cinemaGroupId']));
				if(!empty($bind)){
					$cardUser=D('member')->getUser(array('cardNum'=>$bind['cardId'],'businessCode'=>$bind['cinemaCode'],'cinemaGroupId'=>$this->weiXinInfo['cinemaGroupId']));
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


		$user=session('ftuser');
		$userInfo = $this->getBindUserInfo($user);
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
			if (!isset($orderInfo['status']) || $orderInfo['status'] != 0) {
				$this->error('订单状态失效');
			}
	
			$planInfo = S('getBuyPaywayPlanInfo' . $orderInfo['featureAppNo']);
			if (empty($planInfo)) {
				$planInfo = D('Plan')->getplan($orderInfo['featureAppNo']);
				S('getBuyPaywayPlanInfo' . $orderInfo['featureAppNo'], $planInfo, 900);
			}
	
			// $arraySetingConfig = S('getBuyPaywaySetingConfig' . $orderInfo['featureAppNo']);
			if (empty($arraySetingConfig)) {
				$arraySetingConfig = D('Voucher')->isVoucher($planInfo);
				S('getBuyPaywaySetingConfig' . $orderInfo['featureAppNo'], $arraySetingConfig, 900);
			}
		

			$weiXingConfig = getNowPayWay($planInfo['cinemaCode'], $orderId);

			$payConfig = $weiXingConfig['payConfig'];
			$onlinePay = $weiXingConfig['onlinePay'];


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
				$useNum = 0;
				$isCanUseNum = 0;
				foreach ($memberVoucherList as $key => $value) {
					$memberVoucherList[$key]['isUse'] = 0;
					$memberVoucherList[$key]['validDataStr'] = date('Y-m-d',$value['validData']);
					$canUserTypeList = array_keys($arraySetingConfig[1]);
		
					$copyType = substr(strtolower($planInfo['copyType']), 0, 3);
					$canUseCopyTypeList = array_keys($arraySetingConfig[1][$value['typeId']]);

					if (in_array($value['typeId'], $canUserTypeList) && in_array($copyType, $canUseCopyTypeList)) {

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
						'content' => '您有' . $isCanUseNum . '张立减券可用',
						'list' => $memberVoucherList,
						'useNum' => intval(1 - $useNum),
						'isShow' => $isShow
				);
				if (empty($memberVoucherList)) {
					unset($rechargePayway['voucher'][$voucherKey]['list']);
				}
				$voucherKey++;
			}
	

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
				$isUnCard = false;
				$isCanUseNum = 0;
				foreach ($memberVoucherList as $key => $value) {
					$memberVoucherList[$key]['isUse'] = 0;
					// print_r($arraySetingConfig);
					$canUserTypeList = array_keys($arraySetingConfig[0]);

					$copyType = substr(strtolower($planInfo['copyType']), 0, 3);
					$canUseCopyTypeList = array_keys($arraySetingConfig[0][$value['typeId']]);
					// print_r($canUseCopyTypeList);
					if (in_array($value['typeId'], $canUserTypeList) && in_array($copyType, $canUseCopyTypeList)) {

						$memberVoucherList[$key]['isCanUse'] = 1;
						$isCanUseNum++;
					}else{
						$memberVoucherList[$key]['isCanUse'] = 0;
					}
		
					foreach ($otherPayInfo[0] as $vKey => $vValue) {
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
						'content' => '您有' . $isCanUseNum . '张兑换券可用',
						'list' => $memberVoucherList,
						'useNum' => intval(count($seatInfo) - $useNum),
						'isShow' => $isShow
				);
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
	
						$allIntegral = $price * $this->appInfo['proportion'];
						if ($allIntegral >= $userInfo['integral']) {
							$useIntegral = $userInfo['integral'];
							$price -= round($userInfo['integral'] / $this->appInfo['proportion'],2);
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
							'proportion' => $this->appInfo['proportion'],
							'isUse' => $isIntegral,
							'isShow' => $isShow
					);
				}elseif ($key == 'account' && in_array('account', $onlinePay)) {
					// dump($allIsShow);
					if ($price == 0 || $allIsShow == 0) {
						$isShow = 0;
					}else{
						$isShow = 1;
					}
					// dump($isShow);
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
					// print_r($this->payConfig);
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
	
			$otherPayInfo = json_decode($goodsOrderInfo['otherPayInfo'], true);
	
			$cinemaCode = $goodsOrderInfo['cinemaCode'];
			$cinemaInfo = D('Cinema')->getCinemaInfoBycinemaCode('weixinPayWay', $cinemaCode);
			$price = $goodsOrderInfo['price'];
	
			$voucerMap['memberId'] = $userInfo['id'];
			$voucerMap['validData'] = array('EGT', strtotime(date('Y-m-d')));
			$voucerMap['isUnlock'] = 0;
			$voucerMap['isUse'] = 0;
			$voucerMap['typeClass'] = 2;
			$memberVoucherList = D('Member')->getMemberVoucherList('typeId, voucherName, voucherNum, typeClass, voucherValue, createdDatetime, validData', $voucerMap);
			$useNum = 0;
			foreach ($memberVoucherList as $key => $value) {
				$memberVoucherList[$key]['isUse'] = 0;
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
					'content' => '您有' . count($memberVoucherList) . '张卖品券可用',
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
					$isIntegral = 0;
					if (!empty($otherPayInfo['integral'])) {
						$isIntegral = 1;
	
						$allIntegral = $price * $this->appInfo['proportion'];
						if ($allIntegral >= $userInfo['integral']) {
							$useIntegral = $userInfo['integral'];
							$price -= round($userInfo['integral'] / $this->appInfo['proportion'],2);
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
							'proportion' => $this->appInfo['proportion'],
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
				}
	
			}
	
			$order['price'] = $price > 0 ? $price : 0;
			$rechargePayway['orderInfo'] = $order;
	
			if (!isset($this->payConfig['account']['isShow'])) {
				$this->payConfig['account']['isShow'] = 0;
			}
	
			// print_r($cinemaInfo);
			if (!empty($cinemaInfo['weixinPayWay'])) {
				$rechargePaywayArray = explode(',', $cinemaInfo['weixinPayWay']);
			}
			// print_r($rechargePaywayArray);
			foreach ($rechargePaywayArray as $key => $value) {
				if(empty($userInfo['cardNum']) || $value != 'account'){
					if ($value == 'account') {
						$rechargePayway['account'][] = $this->payConfig['account'];
					}elseif(strstr($value, 'pay')){
						if ($price == 0) {
							$this->payConfig[$value]['isShow'] = 0;
						}
						$rechargePayway['online'][] = $this->payConfig[$value];
					}
				}
			}
	
		}elseif ($type== 'round') { //获得团购的支付渠道
			$cinemaInfo = D('Cinema')->getCinemaInfoBycinemaCode('weixinPayWay', $cinemaCode);
			if (!empty($cinemaInfo['weixinPayWay'])) {
				$rechargePaywayArray = explode(',', $cinemaInfo['weixinPayWay']);
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
		$payWay=json_decode($cinemaInfo['payWay'],true);
		if (!empty($payWay['weixinPayWay'])) {
			$rechargePaywayArray = $payWay['weixinPayWay'];
		}
		foreach ($rechargePaywayArray as $key => $value) {
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
}