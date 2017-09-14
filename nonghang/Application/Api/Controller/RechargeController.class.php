<?php
namespace Api\Controller;
use Think\Controller;
class RechargeController extends ApiController {
	private $_queryUrl = 'http://apps.chinapay.com/cpeduinterface/QueryGet.do';
	private $_payUrl = 'http://apps.chinapay.com/cpeduinterface/OrderGet.do';
	private $_signMethod = 'md5';
	private $_unionPayKey = '';
	private $_unionPayId = '';
	private $_cacheTime = 180;
	private $_checkTimes = 20;
	private $_keyName = '';
	private $logPath = '';
	private $traceMemo = '';

    public function index(){//app银联购票返回
        die('无法访问！');       
    }
    /**
     * 支付宝官网支付
     * @param string $value
     */
    public function alipay_www($value='') {
    	$this->logPath = 'Recharge/alipay_www';
    	$this->traceMemo = '官网（www）支付宝会员卡充值';
    	$rechargeNo = intval(I('request.out_trade_no'));

    	$orderNo = intval(I('request.out_trade_no'));
         if($_POST['trade_status'] == 'TRADE_FINISHED' || $_POST['trade_status'] == 'TRADE_SUCCESS'){
            $cacheTtade = '1';
        }else{
            $cacheTtade = '2';
        }

    	$this->_keyName = 'pay_' . $rechargeNo . '_' . $cacheTtade;
    	
        $cache_value = S($this->_keyName);
        if(!empty($cache_value)){
            //S($this->_keyName, null);
            wlog('多次请求异常处理，订单号' . $orderNo, $this->logPath);
            die();
        }
        S($this->_keyName, 'true', $this->_cacheTime);

    	wlog('收到支付宝返回请求订单号：' . $rechargeNo . ';请求信息:' . json_encode(I('request.')), $this->logPath);
    	if($rechargeNo != 0){
    		$recharge = D('Recharge');
    		$rechargeInfo = $recharge->getRechargeInfo(array('no' => $rechargeNo));
    		if(empty($rechargeInfo)){
    			wlog('fail', $this->logPath);
    			die('fail');
    		}

    		$cinemaInfo = $recharge->getCinema($rechargeInfo['placeNo']);
    		if(empty($cinemaInfo)){
    			wlog('fail', $this->logPath);
    			die('fail');
    		}


			$alipayConfig['partner'] = $cinemaInfo['partnerId'];
			$alipayConfig['key'] = 'nz0mklzdpc66p7evwu3kmg5sqwjwnfiz';
			$alipayConfig['sign_type'] = strtoupper('MD5');
			$alipayConfig['input_charset'] = strtolower('utf-8');
			$alipayConfig['cacert'] = getcwd().'\\cacert.pem';
			$alipayConfig['transport']   = 'http';

			$sign = htmlspecialchars_decode(I('post.sign'), ENT_COMPAT);
			$verifyValue = false;
			$signData = array();
			$signDataMd5 = array();

			$postValue = I('post.');

			foreach ($postValue as $key => $value) {
				if ($key == "sign" || $key == "sign_type" || $value == "" || $key == 'route' || $key == '_route_') {
					continue;
				}
				else {
					$signData[$key] = htmlspecialchars_decode($value, ENT_COMPAT);
					$signDataMd5[$key] = $key . '=' . $signData[$key];
				}
			}
			ksort($signDataMd5);
			reset($signDataMd5);


			if ('RSA' == I('post.sign_type')) {// RSA验证
				$responseTxt = 'false';
				if ( !empty($signData["notify_id"]) ) {
					$responseTxt = file_get_contents("https://mapi.alipay.com/gateway.do?service=notify_verify&" . "partner=" . $alipayConfig['partner'] . "&notify_id=" . $signData["notify_id"]);
					if ( preg_match("/true$/i", $responseTxt) ) {
						$verifyValue = true;
					}
				}
			}elseif ( md5(implode('&', $signDataMd5) . $alipayConfig['key']) == $sign ) {// MD5 验证
				//获取支付宝远程服务器ATN结果（验证是否是支付宝发来的消息）
				$responseTxt = 'false';
				if ( !empty($signData["notify_id"]) ) {
					$responseTxt = file_get_contents("https://mapi.alipay.com/gateway.do?service=notify_verify&" . "partner=" . $alipayConfig['partner'] . "&notify_id=" . $signData["notify_id"], $alipayConfig['input_charset']);
					if ( preg_match("/true$/i", $responseTxt) ) {
						$verifyValue = true;
					}
				}
			}


			if($verifyValue) {//验证成功

				wlog('支付宝请求验证成功订单号：' . $rechargeNo . ';请求信息:' . json_encode(I('request.')), $this->logPath);
				$rechargeNo = I('post.out_trade_no');
				$totalFee = I('post.total_fee');
				//支付宝交易号
				$tradeNo = I('post.trade_no');
				//交易状态
				$tradeStatus =I('post.trade_status');

				//判断支付的价格是否一致
				if($rechargeInfo['amountTotal'] != $totalFee * 100){
					wlog('支付宝请求支付价格异常订单号：' . $rechargeNo . ';请求信息:' . json_encode(I('request.')), $this->logPath);
					die('success');
				}
				//判断价格结束
			    if($_POST['trade_status'] == 'TRADE_FINISHED' || $_POST['trade_status'] == 'TRADE_SUCCESS') {
					$this->alipaySuccess(array('no' => $rechargeNo), $totalFee, $tradeNo);
			    }				
			}else {//验证失败
				wlog('支付宝请求验证失败订单号：' . $rechargeNo . ';请求信息:' . json_encode(I('request.')), $this->logPath);
			    die("fail");
			}
    	}
    	
    }

    /**
     * 银联官网支付
     * @param string $value
     */
     public function unionpay_www($value=''){
    	$this->logPath = 'Recharge/unionpay_www';
    	$this->traceMemo = '官网（www）银联会员卡充值';
    	$rechargeNo = intval(I('request.OrdId'));

    	$this->_keyName = 'pay_' . $rechargeNo;
        $cache_value = S($this->_keyName);
        if(!empty($cache_value)){
            //S($this->_keyName, null);
            wlog('多次请求异常处理，订单号' . $orderNo, $this->logPath);
            die();
        }
        S($this->_keyName, 'true', $this->_cacheTime);

    	wlog('收到银联充值返回请求订单号：' . $rechargeNo . ';请求信息:' . json_encode(I('request.')), $this->logPath);
    	if($rechargeNo != 0){
    		$recharge = D('Recharge');
    		$rechargeInfo = $recharge->getRechargeInfo(array('no' => $rechargeNo));
    		if(empty($rechargeInfo)){
    			wlog('fail', $this->logPath);
    			die('fail');
    		}

    		$cinemaInfo = $recharge->getCinema($rechargeInfo['placeNo']);
    		if(empty($cinemaInfo)){
    			wlog('fail', $this->logPath);
    			die('fail');
    		}

	    	//提取服务器端的签名
			if (!I('request.ChkValue')) {
				wlog('No signature Or signMethod set in notify data!' . $rechargeNo . ';请求信息:' . json_encode(I('request.')), $this->logPath);
				die('No signature Or signMethod set in notify data!');
			}
			$signResult = $this->getSign(I('request.'), I('request.ChkValue'));
			//验证签名
			if ( empty($signResult) ) {
				wlog('Bad signature returned!' . $rechargeNo . ';请求信息:' . json_encode(I('request.')), $this->logPath);
				die('Bad signature returned!');
			}
			if ($signResult['PayStat'] != '1001') {
				wlog('Error:[code:' . $signResult['PayStat'] . ']' . $rechargeNo . ';请求信息:' . json_encode(I('request.')), $this->logPath);
				die('Error:[code:' . $signResult['PayStat'] . ']');
			}

			$order_amount = $signResult['OrdAmt'];
			$query = array(
				'MerId' => $signResult['MerId'],
				'BusiId' => $signResult['BusiId'],
				'OrdId' => $signResult['OrdId'],
				'Param1' => $signResult['Param1'],
				'Param2' => $signResult['Param2'],
				'Param3' => $signResult['Param3'],
				'Param4' => $signResult['Param4'],
				'Param5' => $signResult['Param5'],
				'Param6' => $signResult['Param6'],
				'Param7' => $signResult['Param7'],
				'Param8' => $signResult['Param8'],
				'Param9' => $signResult['Param9'],
				'Param10' => $signResult['Param10']
			);
			$tempString = implode('', $query);
			$conf['MerId'] = buildKey(C('DIR_RESOURCES') . 'MerPrK_808080201304360_20140409112454.key');
			$query['ChkValue'] = sign(base64_encode($tempString));
			$result = getHttpResponsePOST($this->_queryUrl, array(CURLOPT_HTTPHEADER => array('Expect:'), CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false), $query);
			wlog('验证返回结果！' . json_encode($result) . ';请求信息:' . json_encode(I('request.')), $this->logPath);

			$arr_args = array();
			parse_str($result, $arr_args);

			if (empty($arr_args)) {
				wlog('Bad signature returned!' . $rechargeNo . ';请求信息:' . json_encode(I('request.')), $this->logPath);
				die('Bad notify data returned!');
			}
			if (I('post.PayStat') == '1001') {

				$rechargeNo = I('post.OrdId');
				$totalFee = I('post.OrdAmt');
				$tradeNo = I('post.MerId');

				wlog('签名验证成功！' . $rechargeNo . ';请求信息:' . json_encode(I('request.')), $this->logPath);
				//判断支付的价格是否一致
				if($rechargeInfo['amountTotal'] != $totalFee){
					wlog('支付宝请求支付价格异常订单号：' . $rechargeNo . ';请求信息:' . json_encode(I('request.')), $this->logPath);
					die('eduok');
				}
				//判断价格结束
				
				$this->unionpaySuccess(array('no' => $rechargeNo), $totalFee, $tradeNo);


			}
    	}
    	echo '银联站点返回';
    }

    /**
     * 微信端支付宝支付
     * @param string $value
     */
    public function alipay_wap($value=''){
		$this->logPath = 'Recharge/alipay_wap';
    	$this->traceMemo = '微信（wap）支付宝会员卡充值';
    	

    	foreach (I('post.') as $key => $value) {
            $post[$key] = htmlspecialchars_decode($value, ENT_COMPAT);
        }

        wlog('支付宝请求信息:' . json_encode($post), $this->logPath);


    	if( !empty($post) && isset($post['notify_data']) &&  isset($post['sign']) ) {

       		$post['notify_data'] = rsaDecrypt(I('post.notify_data'), C('DIR_RESOURCES') . '/rsa_private_key.pem');
    	 	 $signData = array(
                'service' => 'service=' . $post['service'],
                'v' => 'v=' . $post['v'],
                'sec_id' => 'sec_id=' . $post['sec_id'],
                'notify_data' => 'notify_data=' . $post['notify_data']
            );

        	wlog('支付宝请求信息:' . json_encode($post), $this->logPath);
            $notifyData = json_decode(json_encode(simplexml_load_string($post['notify_data'])), true);

            wlog('【解密支付宝返回请求】:' . json_encode($notifyData), $this->logPath);

            $rechargeNo = $notifyData['out_trade_no'];



	        if($notifyData['trade_status'] == 'TRADE_FINISHED' || $notifyData['trade_status'] == 'TRADE_SUCCESS'){
	            $cacheTtade = '1';
	        }else{
	            $cacheTtade = '2';
	        }

	    	$this->_keyName = 'pay_' . $rechargeNo . '_' . $cacheTtade;

	        $cache_value = S($this->_keyName);
	        if(!empty($cache_value)){
	            //S($this->_keyName, null);
	            wlog('多次请求异常处理，订单号' . $orderNo, $this->logPath);
	            die();
	        }
	        S($this->_keyName, 'true', $this->_cacheTime);


    		$recharge = D('Recharge');
    		$rechargeInfo = $recharge->getRechargeInfo(array('no' => $rechargeNo));
    		if(empty($rechargeInfo)){
    			wlog('fail', $this->logPath);
    			die('fail');
    		}

    		$cinemaInfo = $recharge->getCinema($rechargeInfo['placeNo']);
    		if(empty($cinemaInfo)){
    			wlog('fail', $this->logPath);
    			die('fail');
    		}

    		$alipayConfig['partner'] = $cinemaInfo['partnerId'];
			$alipayConfig['key'] = 'nz0mklzdpc66p7evwu3kmg5sqwjwnfiz';
			$alipayConfig['sign_type'] = strtoupper('MD5');
			$alipayConfig['input_charset'] = strtolower('utf-8');
			$alipayConfig['cacert'] = getcwd().'\\cacert.pem';
			$alipayConfig['transport']   = 'http';

			$verifyValue = false;
			$verify = rsaVerify(implode('&', $signData), C('DIR_RESOURCES') . '/alipay_public_key_' . $rechargeInfo['placeNo'] . '.pem', $post['sign']);
			wlog('【支付宝验证结果】'. json_encode($verify), $this->logPath);
			if ($verify) {
			 	$verifyValue = true;
			 	wlog('【支付宝返回结果】'.implode("&",$signData), $this->logPath);
			}

			if($rechargeInfo['placeNo'] == '35013901'){
				$verifyValue = file_get_contents("https://mapi.alipay.com/gateway.do?service=notify_verify&" . "partner=" . $alipayConfig['partner'] . "&notify_id=" . $notifyData["notify_id"]);
				wlog('【二次验证信息】'. $verifyValue, $this->logPath);
			}


			if($verifyValue == true) {//验证成功

				wlog('支付宝请求验证成功订单号：' . $rechargeNo, $this->logPath);
				
				$rechargeNo = $notifyData['out_trade_no'];
				$totalFee = $notifyData['total_fee'];
				$tradeNo = $notifyData['trade_no'];

				//判断支付的价格是否一致
				if($rechargeInfo['amountTotal'] != $totalFee * 100){
					wlog('支付宝请求支付价格异常订单号：' . $rechargeNo, $this->logPath);
					die('success');
				}
				//判断价格结束
			    if($notifyData['trade_status'] == 'TRADE_FINISHED' || $notifyData['trade_status'] == 'TRADE_SUCCESS') {
					$this->alipaySuccess(array('no' => $rechargeNo), $totalFee, $tradeNo);
			    }				
			}else {//验证失败
				wlog('支付宝请求验证失败订单号：' . $rechargeNo, $this->logPath);
			    die("fail");
			}
    	}
    	echo '支付宝wap';
    }

    /**
     * 微信端银联支付
     * @param string $value
     */
    public function unionpay_wap($value=''){
    	echo '银联wap返回';
    }

    /**
     * app支付宝支付
     */
    public function alipay_app($value='') {
    	$this->logPath = 'Recharge/alipay_app';
    	$this->traceMemo = '客户端（app）支付宝会员卡充值';
    	$rechargeNo = intval(I('request.out_trade_no'));

    	$orderNo = intval(I('request.out_trade_no'));
         if($_POST['trade_status'] == 'TRADE_FINISHED' || $_POST['trade_status'] == 'TRADE_SUCCESS'){
            $cacheTtade = '1';
        }else{
            $cacheTtade = '2';
        }

    	$this->_keyName = 'pay_' . $rechargeNo . '_' . $cacheTtade;
        $cache_value = S($this->_keyName);
        if(!empty($cache_value)){
            //S($this->_keyName, null);
            wlog('多次请求异常处理，订单号' . $orderNo, $this->logPath);
            die();
        }
        S($this->_keyName, 'true', $this->_cacheTime);

    	wlog('收到支付宝返回请求订单号：' . $rechargeNo . ';请求信息:' . json_encode(I('request.')), $this->logPath);
    	if($rechargeNo != 0){
    		$recharge = D('Recharge');
    		$rechargeInfo = $recharge->getRechargeInfo(array('no' => $rechargeNo));
    		if(empty($rechargeInfo)){
    			wlog('fail', $this->logPath);
    			die('fail');
    		}

    		$cinemaInfo = $recharge->getCinema($rechargeInfo['placeNo']);
    		if(empty($cinemaInfo)){
    			wlog('fail', $this->logPath);
    			die('fail');
    		}


			$alipayConfig['partner'] = $cinemaInfo['partnerId'];
			$alipayConfig['key'] = 'nz0mklzdpc66p7evwu3kmg5sqwjwnfiz';
			$alipayConfig['sign_type'] = strtoupper('MD5');
			$alipayConfig['input_charset'] = strtolower('utf-8');
			$alipayConfig['cacert'] = getcwd().'\\cacert.pem';
			$alipayConfig['transport']   = 'http';

			$sign = htmlspecialchars_decode(I('post.sign'), ENT_COMPAT);
			$verifyValue = false;
			$signData = array();
			$signDataMd5 = array();

			$postValue = I('post.');

			foreach ($postValue as $key => $value) {
				if ($key == "sign" || $key == "sign_type" || $value == "" || $key == 'route' || $key == '_route_') {
					continue;
				}
				else {
					$signData[$key] = htmlspecialchars_decode($value, ENT_COMPAT);
					$signDataMd5[$key] = $key . '=' . $signData[$key];
				}
			}
			ksort($signDataMd5);
			reset($signDataMd5);


			if ('RSA' == I('post.sign_type')) {// RSA验证
				$responseTxt = 'false';
				if ( !empty($signData["notify_id"]) ) {
					$responseTxt = file_get_contents("https://mapi.alipay.com/gateway.do?service=notify_verify&" . "partner=" . $alipayConfig['partner'] . "&notify_id=" . $signData["notify_id"]);
					if ( preg_match("/true$/i", $responseTxt) ) {
						$verifyValue = true;
					}
				}
			}elseif ( md5(implode('&', $signDataMd5) . $alipayConfig['key']) == $sign ) {// MD5 验证
				//获取支付宝远程服务器ATN结果（验证是否是支付宝发来的消息）
				$responseTxt = 'false';
				if ( !empty($signData["notify_id"]) ) {
					$responseTxt = file_get_contents("https://mapi.alipay.com/gateway.do?service=notify_verify&" . "partner=" . $alipayConfig['partner'] . "&notify_id=" . $signData["notify_id"], $alipayConfig['input_charset']);
					if ( preg_match("/true$/i", $responseTxt) ) {
						$verifyValue = true;
					}
				}
			}


			if($verifyValue) {//验证成功

				wlog('支付宝请求验证成功订单号：' . $rechargeNo . ';请求信息:' . json_encode(I('request.')), $this->logPath);
				$rechargeNo = I('post.out_trade_no');
				$totalFee = I('post.total_fee');
				//支付宝交易号
				$tradeNo = I('post.trade_no');
				//交易状态
				$tradeStatus =I('post.trade_status');

				//判断支付的价格是否一致
				if($rechargeInfo['amountTotal'] != $totalFee * 100){
					wlog('支付宝请求支付价格异常订单号：' . $rechargeNo . ';请求信息:' . json_encode(I('request.')), $this->logPath);
					die('success');
				}
				//判断价格结束
			    if($_POST['trade_status'] == 'TRADE_FINISHED' || $_POST['trade_status'] == 'TRADE_SUCCESS') {
					$this->alipaySuccess(array('no' => $rechargeNo), $totalFee, $tradeNo);
			    }				
			}else {//验证失败
				wlog('支付宝请求验证失败订单号：' . $rechargeNo . ';请求信息:' . json_encode(I('request.')), $this->logPath);
			    die("fail");
			}
    	}else{
    		die('fail');
    	}
    }

    /**
     * app银联支付
     * @param string $value
     */
    public function unionpay_app($value=''){
    	$this->logPath = 'Recharge/unionpay_app';
    	$this->traceMemo = '客户端（app）银联会员卡充值';

    	$numbers = I('post.orderNumber');
    	$this->_keyName = 'pay_' . $numbers;
        $cache_value = S($this->_keyName);
        if(!empty($cache_value)){
            //S($this->_keyName, null);
            wlog('多次请求异常处理，订单号' . $orderNo, $this->logPath);
            die();
        }
        S($this->_keyName, 'true', $this->_cacheTime);

    	wlog('收到银联充值返回请求订单号：' . $numbers . ';请求信息:' . json_encode(I('post.')), $this->logPath);
    	if(intval($numbers) != 0){
    		$recharge = D('Recharge');
    		$rechargeInfo = $recharge->getRechargeInfo(array('numbers' => $numbers));
    		if(empty($rechargeInfo)){
    			wlog('fail', $this->logPath);
    			die('fail');
    		}

    		$cinemaInfo = $recharge->getCinema($rechargeInfo['placeNo']);
    		if(empty($cinemaInfo)){
    			wlog('fail', $this->logPath);
    			die('fail');
    		}

    		$this->_unionPayKey=$cinemaInfo['unionPayKey'];
			$this->_unionPayId=$cinemaInfo['unionPayId'];

	    	$arr_args = array();
			$arr_reserved = array();

			$arr_args       = I('post.');
			$cupReserved    = isset($arr_args['sysReserved']) ? $arr_args['sysReserved'] : '';
			parse_str(substr($cupReserved, 1, -1), $arr_reserved); //去掉前后的{}

			//提取服务器端的签名
			if (!isset($arr_args['signature']) || !isset($arr_args['signMethod'])) {
				wlog('No signature Or signMethod set in notify data!' . $numbers . ';请求信息:' . json_encode(I('request.')), $this->logPath);
	            die("fail");
			}
			if ($arr_args['signature'] != $this->sign(I('post.'), true)) {
				wlog('Bad signature returned!' . $numbers . '对比信息：' . $arr_args['signature'] . '=' . $this->sign(I('post.')), $this->logPath);
	            die("fail");
			}

			if ($arr_args['respCode'] != '00') {
				wlog('Error:[code:' . $arr_args['respCode'] . ']' . $arr_args['respMsg']. ';请求信息:' . json_encode(I('request.')), $this->logPath);
	            die("fail");
			}

			$arr_args = array_merge($arr_args, $arr_reserved);

			unset($arr_reserved);

			$order_amount = (float)$arr_args['settleAmount'] / 100;

			$query = array(
					'version' => $arr_args['version'],
					'charset' => $arr_args['charset'],
					'signMethod' => $this->_signMethod,
					'transType' => $arr_args['transType'],
					'merId' => $this->_unionPayId,
					'orderNumber' => $arr_args['orderNumber'],
					'orderTime' => $arr_args['orderTime']
			);
			$query['signature'] = $this->sign($query);
			foreach(I('post.') as $key=>$val){
				$implode[]=$key."=".$val;
			}

			$result = getHttpResponsePOST($this->_queryUrl, array(CURLOPT_HTTPHEADER => array('Expect:'), CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false), $query);

			wlog('充值-支付结果查询 - UPMP应答报文:' . json_encode($result) . ';请求信息:' . json_encode(I('request.')), $this->logPath);

			if ( !isset($arr_args['respCode']) ) {
				wlog('fail1', $this->logPath);
	            die("fail");
			}

			// if ( $arr_args['signature'] != $this->sign($arr_args) ) {
			// 	wlog(json_encode($result), $this->logPath);
	  //           die("fail");
			// }

			if ($arr_args['transStatus'] == '00') {

				$numbers = intval(I('request.orderNumber'));
				$totalFee = I('post.settleAmount');
				$tradeNo = I('post.merId');

				wlog('签名验证成功！' . $rechargeNo . ';请求信息:' . json_encode(I('request.')), $this->logPath);
				//判断支付的价格是否一致
				if($rechargeInfo['amountTotal'] != $totalFee){
					wlog('支付宝请求支付价格异常订单号：' . $rechargeNo . ';请求信息:' . json_encode(I('request.')), $this->logPath);
					die('eduok');
				}
				//判断价格结束
				
				$this->unionpaySuccess(array('no' => $rechargeInfo['no']), $totalFee, $tradeNo);


			}
    	}
    	echo '银联app返回';
    }

    public function unionpaySuccess($whereRecharge, $totalFee, $tradeNo){
    	
    	$recharge = D('Recharge');
    	$rechargeInfo = $recharge->getRechargeInfo($whereRecharge);
    	if($rechargeInfo['status'] != 0){
			wlog('当前订单已充值成功，不可重复充值！' . $whereRecharge['no'] . ';', $this->logPath);
    		die('eduok');
    	}
    	//更新订单为已支付状态
    	$updateInfo['payType'] = $tradeNo;
		$updateInfo['payTime'] = date('Y-m-d H:i:s');
		$where['no'] = $whereRecharge['no'];
		$where['status'] = 0;
		$updateResults = $recharge->updateRecharge($updateInfo, $where);
		if(!$updateResults){
			wlog('当前订单已充值成功，不可重复充值！' . $whereRecharge['no'] . ';', $this->logPath);
			die('eduok');
		}
		if($rechargeInfo['userType'] == 'account'){
			wlog('帐户余额充值：' . $whereRecharge['no'] . ';', $this->logPath);
			$whereAccount['id'] = $rechargeInfo['cardId'];
			$updateResults = $recharge->updateAccountBalance($rechargeInfo['amountTotal'], $whereAccount);
		}else{
			//开始执行充值
			wlog('开始调用满天星充值订单号：' . $whereRecharge['no'] . ';', $this->logPath);
			$T = true;
			$key = 1;
			while ($T) {
				$rechargeResults = $recharge->rechargeCard($rechargeInfo, $this->logPath,  $this->traceMemo, $key);
				if($rechargeResults['ResultCode'] == 0 || $key >= $this->_checkTimes){
					$T = false;
					if($key >= $this->_checkTimes){
						wlog('满天星充值失败订单号：' . $whereRecharge['no'] . ';', $this->logPath);
						die('eduok');
					}
				}else{
					sleep(5);
					$key ++ ;
				}
			}
			$whereAccount['cardPassWd'] = $rechargeInfo['data'];
			$whereAccount['cardId'] = $rechargeInfo['cardId'];
			$updateAccountInfo['balance'] = $rechargeResults['Balance'] * 100;
			$updateResults = $recharge->updateAccount($updateAccountInfo, $whereAccount);
		}
		$userInfo = $recharge->getUserInfo('id', $whereAccount);

		$accountRecordsData['accountId'] = $userInfo['id'];
		$accountRecordsData['balance'] = $rechargeInfo['amountTotal'];
		$accountRecordsData['remarks'] = '银联在线充值';
		$accountRecordsData['createdDatetime'] = date('Y-m-d H:i:s');
		$accountRecordsData['type'] = 'recharge';
        $accountRecordsData['relationId'] = $whereRecharge['no'];
		$recharge->addAccountRecords($accountRecordsData);
		unset($updateInfo,$where);
		//更新订单为已订单完成
		$updateInfo['status'] = 1;
		$updateInfo['data'] = '';
		$updateInfo['newTotal'] = $rechargeResults['Balance'] * 100;
		$where['no'] = $whereRecharge['no'];
		$where['status'] = 0;
		$updateResults = $recharge->updateRecharge($updateInfo, $where);
		
		S($this->_keyName, null);
		//结束充值
		wlog('银联请求成功订单号：' . $whereRecharge['no'] . ';开始执行:' . $_POST['trade_status'] . json_encode($updateResults), $this->logPath);
		die('eduok');	//请不要修改或删除
    }

    public function alipaySuccess($whereRecharge, $totalFee, $tradeNo){
    	$recharge = D('Recharge');
    	$rechargeInfo = $recharge->getRechargeInfo($whereRecharge);
    	if($rechargeInfo['status'] != 0){
			wlog('当前订单已充值成功，不可重复充值！' . $whereRecharge['no'] . ';', $this->logPath);
    		die('success');
    	}
		//更新订单为已支付状态
		$updateInfo['payType'] = $tradeNo;
		$updateInfo['payTime'] = date('Y-m-d H:i:s');
		$where['no'] = $whereRecharge['no'];
		$where['status'] = 0;
		$updateResults = $recharge->updateRecharge($updateInfo, $where);
		if(!$updateResults){
			wlog('当前订单已充值成功，不可重复充值！' . $whereRecharge['no'] . ';', $this->logPath);
			die('success');
		}

		if($rechargeInfo['userType'] == 'account'){
			wlog('帐户余额充值：' . $whereRecharge['no'] . ';', $this->logPath);
			$whereAccount['id'] = $rechargeInfo['cardId'];
			$accountId = $recharge->updateAccountBalance($rechargeInfo['amountTotal'], $whereAccount);
		}else{
			//开始执行充值
			wlog('开始调用满天星充值订单号：' . $whereRecharge['no'] . ';', $this->logPath);
			$T = true;
			$key = 1;
			while ($T) {
				$rechargeResults = $recharge->rechargeCard($rechargeInfo, $this->logPath, $this->traceMemo, $key);
				if($rechargeResults['ResultCode'] == 0 || $key >= $this->_checkTimes){
					$T = false;
					if($key >= $this->_checkTimes){
						wlog('满天星充值失败订单号：' . $whereRecharge['no'] . ';', $this->logPath);
						die('success');
					}
				}else{
					sleep(5);
					$key ++ ;
				}
			}
			$whereAccount['cardPassWd'] = $rechargeInfo['data'];
			$whereAccount['cardId'] = $rechargeInfo['cardId'];
			$updateAccountInfo['balance'] = $rechargeResults['Balance'] * 100;
			$accountId = $recharge->updateAccount($updateAccountInfo, $whereAccount);
		}

		$userInfo = $recharge->getUserInfo('id', $whereAccount);

		$accountRecordsData['accountId'] = $userInfo['id'];
		$accountRecordsData['balance'] = $rechargeInfo['amountTotal'];
		$accountRecordsData['remarks'] = '支付宝在线充值';
		$accountRecordsData['createdDatetime'] = date('Y-m-d H:i:s');
		$accountRecordsData['type'] = 'recharge';
        $accountRecordsData['relationId'] = $whereRecharge['no'];
		$recharge->addAccountRecords($accountRecordsData);
		unset($updateInfo,$where);
		//更新订单为已订单完成
		$updateInfo['status'] = 1;
		$updateInfo['data'] = '';
		$updateInfo['newTotal'] = $rechargeResults['Balance'] * 100;
		$where['no'] = $whereRecharge['no'];
		$where['status'] = 0;
		$updateResults = $recharge->updateRecharge($updateInfo, $where);

		wlog('支付宝请求成功订单号：' . $whereRecharge['no'] . ';开始执行:' . $_POST['trade_status'] . json_encode($updateResults), $this->logPath);
		S($this->_keyName, null);
		echo "success";		//请不要修改或删除    
	}


    /**
	 * 签名加密
	 * @param array $data 待签表单数据
	 * @return array 签名
	*/
	private function getSign($data, $chkValue) {
		$sign_data = array();
		
		$waitSignKey = array(
				'MerId', 
				'BusiId', 
				'OrdId', 
				'OrdAmt', 
				'CuryId', 
				'Version', 
				'GateId', 
				'Param1', 
				'Param2', 
				'Param3', 
				'Param4', 
				'Param5', 
				'Param6', 
				'Param7', 
				'Param8', 
				'Param9', 
				'Param10', 
				'ShareType',
				'ShareData',
				'Priv1',
				'CustomIp',
				'PayStat',
				'PayTime'
		);
		
		foreach ($waitSignKey as $item) {
			if (isset($data[$item]))
				$sign_data[$item] = $data[$item];
			else
				$sign_data[$item] = '';
		}
		
		$flag = buildKey(C('DIR_RESOURCES') . '/PgPubk.key');
		
		if(!$flag) {

			wlog('银联支付 - 导入公钥文件失败' . C('DIR_RESOURCES') . '/PgPubk.key', $this->logPath);
			//$this->log->write('银联支付 - 导入公钥文件失败');
			return null;
		}
		else {
			$flag = verify(base64_encode(implode('', $sign_data)), $chkValue);
			
			if ($flag) {
				return $sign_data;
			}
			else {
				wlog('银联支付 - 验证签名失败，待验信息：' . json_encode($sign_data));
				return null;
			}
		}
	}

		/**
	 * 签名加密
	 * @param array $data 待签表单数据
	 * @return string 签名
	 */
	private function sign($data, $decode = false) {
		$sign_data = array();
		
		ksort($data);
		reset($data);
		
		foreach ($data as $key => $value) {
			if ($key == 'bank' || $key == '' || $key == 'signature' || $key == 'signMethod' || $key == 'route' || $key == '_route_')
				continue;
			else
				$sign_data[] = $key . '=' . ($decode ?  htmlspecialchars_decode($value, ENT_COMPAT) : $value);
		}
		
		return md5(implode('&', $sign_data) . '&' .  md5($this->_unionPayKey));
	}

}