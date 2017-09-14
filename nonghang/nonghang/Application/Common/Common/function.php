<?php
use Think\Crypt\Driver\Base64;


/**
 * 会员卡第三方退款
 */
function getBackOrder($order,$user,$cinema,$totalFee,$logPath){
	$memberChargearr = array (
			'cinemaCode' => $user['businessCode'],
			'loginNum' => $user['cardNum'],
			'chargeType' => 0, // 回充
			'orderCode' =>$order['orderCode'],
			'channelType' => '购票失败回充金额',
			'payment' =>  'Ali',
			'link' =>  $cinema['link'],
			'passWord' => decty($user['pword']),
			'transactionNo' => create_uuid(),
			'chargeAmount' => number_format ( $totalFee, 2 ,'.','' )
	); // 金额
	$iuser = D('ZMUser');
	$i=1;
	while ( $i < 6 ) {
		$b = $iuser->memberCharge ( $memberChargearr ); // 充值
		if ($b ['ResultCode'] == '0') {
			wlog ( '第' . $i . '次充值成功' . arrayeval ( $b ), $logPath );
			break;
		} else {
			wlog ( '第' . $i . '次充值失败' . arrayeval ( $b ), $logPath );
		}
		$i ++;
	}
	if ($b ['ResultCode'] == '0') {
		if(!empty($user['cardNum'])){
			wlog ( '更新用户前金额:' . ($user['basicBalance']+$user ['donateBalance']) , $logPath );
			$loginResult = $iuser->verifyMemberLogin(array('cinemaCode'=>$user['businessCode'],'loginNum'=>$user['cardNum'],'password'=>decty($user['pword']),'link'=>$cinema['link'],'cinemaName'=>$cinema['cinemaName']));
			if($loginResult['ResultCode'] == 0){//登录成功
				wlog ( '用户当前数据:' . arrayeval ( $loginResult ), $logPath );
				$result=D('member')->loginMember($loginResult,$user['businessCode'],decty($user['pword']),$user['cinemaGroupId']);
				$cxUserInfo=$result['user'];
				wlog ( '更新用户数据:' . arrayeval ( $result ), $logPath );
			}
		}
	} else {
		wlog ( '充值失败' .arrayeval ( $b ), $logPath );
	}
	return $b;
}
/**
 * 退券退积分
 * @param unknown $orderid
 * @param unknown $user
 * @param unknown $integral
 * @param unknown $logPath
 */
function backUserMoney($orderid,$user,$integral,$logPath){
	wlog('退券',  $logPath);
	D('Common/Voucher')->backVoucher($orderid, $user, $logPath);
	if($integral>0){
		wlog('开始返回积分【' . $integral . '】' . $orderid,  $logPath);
		setInc($user['id'],'integral', $integral);
	}
}
function setInc($userid,$field,$num){
	M('member')->where(array('id'=>$userid))->setInc($field,$num);
}
function setIncByMap($table,$map,$field,$num){
	$table->where($map)->setInc($field,$num);
}
//增加字段值
/*function setInc($field, $data, $map)
{
	return M('Member')->where($map)->setInc($field,$data);
}*/

/**
 * 验证手机
 * @param unknown $mobile
 */
function checkMobile($mobile){
	return preg_match("/1\d{10}$/",$mobile)?true:false;
}
/**
 * 检测用户是否登录
 * @return integer 0-未登录，大于0-当前登录用户ID
 * @author 
 */
function is_login(){
    $user = session('ftuser');
    
    return (int)$user['id'];
}

function chrent_md5($pwd,$ched='rmiswt'){
	return md5(sha1($pwd.$ched));
}

function encty($pwd,$ct='rmiswt'){
	return base64_encode($pwd.$ct);
}
function decty($pwd,$ct='rmiswt'){
	return substr(base64_decode($pwd),0,-6);
}

function random($length = 6 , $numeric = 0) {
	PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
	if($numeric == 0) {
		$hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
	} elseif ($numeric = 1) {
		$hash = '';
		$chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
		$max = strlen($chars) - 1;
		for($i = 0; $i < $length; $i++) {
			$hash .= $chars[mt_rand(0, $max)];
		}
	}else{
		$hash = '';
		$chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghjkmnpqrstuvwxyz';
		$max = strlen($chars) - 1;
		for($i = 0; $i < $length; $i++) {
			$hash .= $chars[mt_rand(0, $max)];
		}
	}
	return $hash;
}
function clearCache($name)
{
	$allCacheName = S(C('CACHE_NAME_LIST'));
	foreach ($allCacheName as $key => $value) {
		if(strstr($key, $name)){
			S($key, NULL);
		}
	}
}


/*
 * 自动建立目录
 * @param string $folderpath 要建立的目录
 * @return bool
 */
function aotumkdir($folderpath) {

	// $folderpath = str_replace($_SERVER['DOCUMENT_ROOT'], './', $folderpath);
	$folderpath = str_replace ( "\\", "/", $folderpath );
	$dirNames = explode ( '/', $folderpath );
	$total = count ( $dirNames );
	$temp = '';
	$tempi = 0;

	if ($dirNames [$total] == "") {
		$tempi = 1;
	}
	for($i = 0; $i < ($total - $tempi); $i ++) {
		$temp .= $dirNames [$i];
		// echo $temp . "\r\n";
		if (! is_dir ( $temp ) && ! empty ( $temp )) { // if (!file_exists($temp) && !is_dir($temp)) {
			$oldmask = umask ( 0 );
			if (mkdir ( $temp, 0777 )) {
				chmod ( $temp, 0777 );
				umask ( $oldmask );
			}
			// echo $temp."<br>";
		}

		$temp .= "/";
	}
	return true;
}

/*
 * 写入日志
 * @param string $info 写入信息, $path 目录名称, $name 日志名称
 * @return bool
 */
function wlog($info, $path, $name) {
	$path = RUNTIME_PATH . $path . '/' . date ( 'Y-m-d' ) . '.log';
	aotumkdir ( $path );
	\Think\Log::write ( $info, $name, '', $path );
}

function create_uuid($prefix = "") { // 可以指定前缀
	$uuid = md5 ( time () . microtime () . rand ( 1000000, 9999999 ) );
	return $prefix . $uuid;
}
/*
 * 数组转换成字串
 * @param  array $array 要转换的数组;int $level 要转的数组级别
 * @return string
 */
function arrayeval($array, $level = 0) {
	$space = '';
	for($i = 0; $i <= $level; $i++) {
		$space .= "\t";
	}
	$evaluate = "Array\n$space(\n";
	$comma = $space;
	foreach($array as $key => $val) {
	$key = is_string($key) ? '\''.addcslashes($key, '\'\\').'\'' : $key;
	$val = !is_array($val) && (!preg_match("/^\-?\d+$/", $val) || strlen($val) > 12) ? '\''.addcslashes($val, '\'\\').'\'' : $val;
	if(is_array($val)) {
	$evaluate .= "$comma$key => ".arrayeval($val, $level + 1);
	} else {
	$evaluate .= "$comma$key => $val";
	}
	$comma = ",\n$space";
	}
	$evaluate .= "\n$space)";
	return $evaluate;
	}

	/**
 * 数据签名认证
 * @param  array  $data 被认证的数据
 * @return string       签名
 * @author 
 */
function data_auth_sign($data) {
    //数据类型检测
    if(!is_array($data)){
        $data = (array)$data;
    }
    ksort($data); //排序
    $code = http_build_query($data); //url编码并生成query字符串
    $sign = sha1($code); //生成签名
    return $sign;
}
/* *
 * 支付宝接口公用函数
 * 详细：该类是请求、通知返回两个文件所调用的公用函数核心处理文件
 * 版本：3.3
 * 日期：2012-07-19
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
 */

/**
 * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
 * @param $para 需要拼接的数组
 * return 拼接完成以后的字符串
 */
function createLinkstring($para) {
	$arg  = "";
	while (list ($key, $val) = each ($para)) {
		$arg.=$key."=".$val."&";
	}
	//去掉最后一个&字符
	$arg = substr($arg,0,count($arg)-2);

	//如果存在转义字符，那么去掉转义
	if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}

	return $arg;
}
/**
 * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串，并对字符串做urlencode编码
 * @param $para 需要拼接的数组
 * return 拼接完成以后的字符串
 */
function createLinkstringUrlencode($para) {
	$arg  = "";
	while (list ($key, $val) = each ($para)) {
		$arg.=$key."=".urlencode($val)."&";
	}
	//去掉最后一个&字符
	$arg = substr($arg,0,count($arg)-2);

	//如果存在转义字符，那么去掉转义
	if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}

	return $arg;
}
/**
 * 除去数组中的空值和签名参数
 * @param $para 签名参数组
 * return 去掉空值与签名参数后的新签名参数组
 */
function paraFilter($para) {
	$para_filter = array();
	while (list ($key, $val) = each ($para)) {
		if($key == "sign" || $key == "sign_type" || $val == "")continue;
		else	$para_filter[$key] = $para[$key];
	}
	return $para_filter;
}
/**
 * 对数组排序
 * @param $para 排序前的数组
 * return 排序后的数组
 */
function argSort($para) {
	ksort($para);
	reset($para);
	return $para;
}
/**
 * 写日志，方便测试（看网站需求，也可以改成把记录存入数据库）
 * 注意：服务器需要开通fopen配置
 * @param $word 要写入日志里的文本内容 默认值：空值
 */
function logResult($word='') {
	$fp = fopen("log.txt","a");
	flock($fp, LOCK_EX) ;
	fwrite($fp,"执行日期：".strftime("%Y%m%d%H%M%S",time())."\n".$word."\n");
	flock($fp, LOCK_UN);
	fclose($fp);
}

/**
 * 用 curl Post 方式..
 * @author LSH 2013-02-17 
 * @param string $url                 // 目标地址
 * @param array  $opt                // curl_setopt 参数
 * @param array  $post_data     // 需要post的数据
 * @return string                        // 返回的数据..
 */
function getHttpResponsePOST($url, $opt = array(),$post_data) 
{
    if (is_array($post_data)) {
        $post_data = http_build_query($post_data);
    }
    
    $setopt = array(
            CURLOPT_HEADER => 0,               //设置header
            CURLOPT_RETURNTRANSFER => 1,       //要求结果为字符串且输出到屏幕上
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POST => 1,                 //post提交方式
            CURLOPT_POSTFIELDS => $post_data,  // 需要Post的数据..
    );

    if ( !empty($opt) ) {
        foreach ($opt as $key => $value) {
            $setopt[$key] = $value;
        }
    }


    $curl = curl_init($url);

    foreach ($setopt as $key => $value) {
        curl_setopt($curl, $key, $value );
    }

    $responseText = curl_exec($curl);

    curl_close($curl);

    return $responseText;
}

/**
 * 远程获取数据，GET模式
 * 注意：
 * 1.使用Crul需要修改服务器中php.ini文件的设置，找到php_curl.dll去掉前面的";"就行了
 * 2.文件夹中cacert.pem是SSL证书请保证其路径有效，目前默认路径是：getcwd().'\\cacert.pem'
 * @param $url 指定URL完整路径地址
 * @param $cacert_url 指定当前工作目录绝对路径
 * return 远程输出的数据
 */
function getHttpResponseGET($url,$cacert_url) {
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
	curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
	curl_setopt($curl, CURLOPT_CAINFO,$cacert_url);//证书地址
	$responseText = curl_exec($curl);
	//var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
	curl_close($curl);

	return $responseText;
}


function getCurlResult($url,$curl_timeout=1){
	//初始化
	$ch = curl_init();
	//设置选项，包括URL
	curl_setopt($ch, CURLOPT_URL, $url . '/time/' . time());
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_TIMEOUT, $curl_timeout);
	//执行并获取HTML文档内容
	$output = curl_exec($ch);
	//释放curl句柄
	curl_close($ch);
	//打印获得的数据
	return $output;
}

/**
 * 实现多种字符编码方式
 * @param $input 需要编码的字符串
 * @param $_output_charset 输出的编码格式
 * @param $_input_charset 输入的编码格式
 * return 编码后的字符串
 */
function charsetEncode($input,$_output_charset ,$_input_charset) {
	$output = "";
	if(!isset($_output_charset) )$_output_charset  = $_input_charset;
	if($_input_charset == $_output_charset || $input ==null ) {
		$output = $input;
	} elseif (function_exists("mb_convert_encoding")) {
		$output = mb_convert_encoding($input,$_output_charset,$_input_charset);
	} elseif(function_exists("iconv")) {
		$output = iconv($_input_charset,$_output_charset,$input);
	} else die("sorry, you have no libs support for charset change.");
	return $output;
}
/**
 * 实现多种字符解码方式
 * @param $input 需要解码的字符串
 * @param $_output_charset 输出的解码格式
 * @param $_input_charset 输入的解码格式
 * return 解码后的字符串
 */
function charsetDecode($input,$_input_charset ,$_output_charset) {
	$output = "";
	if(!isset($_input_charset) )$_input_charset  = $_input_charset ;
	if($_input_charset == $_output_charset || $input ==null ) {
		$output = $input;
	} elseif (function_exists("mb_convert_encoding")) {
		$output = mb_convert_encoding($input,$_output_charset,$_input_charset);
	} elseif(function_exists("iconv")) {
		$output = iconv($_input_charset,$_output_charset,$input);
	} else die("sorry, you have no libs support for charset changes.");
	return $output;
}
/**
 * 签名字符串
 * @param $prestr 需要签名的字符串
 * @param $key 私钥
 * return 签名结果
 */
function md5Sign($prestr, $key) {
	$prestr = $prestr . $key;
	return md5($prestr);
}

/**
 * 验证签名
 * @param $prestr 需要签名的字符串
 * @param $sign 签名结果
 * @param $key 私钥
 * return 签名结果
 */
function md5Verify($prestr, $sign, $key) {
	$prestr = $prestr . $key;
	$mysgin = md5($prestr);

	if($mysgin == $sign) {
		return true;
	}
	else {
		return false;
	}
}
/**
 * RSA签名
 * @param $data 待签名数据
 * @param $private_key_path 商户私钥文件路径
 * return 签名结果
 */
function rsaSign($data, $priKey) {
	$res = openssl_get_privatekey($priKey);
	openssl_sign($data, $sign, $priKey);
	openssl_free_key($res);
	//base64编码
	$sign = base64_encode($sign);
	return $sign;
}

/**
 * RSA验签
 * @param $data 待签名数据
 * @param $ali_public_key_path 支付宝的公钥文件路径
 * @param $sign 要校对的的签名结果
 * return 验证结果
 */
function rsaVerify($data, $pubKey, $sign)  {
	$res = openssl_get_publickey($pubKey);
	$result = (bool)openssl_verify($data, base64_decode($sign), $res);
	openssl_free_key($res);
	return $result;
}

/**
 * RSA解密
 * @param $content 需要解密的内容，密文
 * @param $private_key_path 商户私钥文件路径
 * return 解密后内容，明文
 */
function rsaDecrypt($content, $priKey) {
	$res = openssl_get_privatekey($priKey);
	//用base64将内容还原成二进制
	$content = base64_decode($content);
	//把需要解密的内容，按128位拆开解密
	$result  = '';
	for($i = 0; $i < strlen($content)/128; $i++  ) {
		$data = substr($content, $i * 128, 128);
		openssl_private_decrypt($data, $decrypt, $res);
		$result .= $decrypt;
	}
	openssl_free_key($res);
	return $result;
}



/** 
* @desc 根据两点间的经纬度计算距离 
* @param float $lat 纬度值 
* @param float $lng 经度值 
*/
function getDistance($lat1, $lng1, $lat2, $lng2) 
{ 
$earthRadius = 6367000; //approximate radius of earth in meters 
 
/* 
Convert these degrees to radians 
to work with the formula 
*/
 
$lat1 = ($lat1 * pi() ) / 180; 
$lng1 = ($lng1 * pi() ) / 180; 
 
$lat2 = ($lat2 * pi() ) / 180; 
$lng2 = ($lng2 * pi() ) / 180; 
 
/* 
Using the 
Haversine formula 
 
http://en.wikipedia.org/wiki/Haversine_formula 
 
calculate the distance 
*/
 
$calcLongitude = $lng2 - $lng1; 
$calcLatitude = $lat2 - $lat1; 
$stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2); 
$stepTwo = 2 * asin(min(1, sqrt($stepOne))); 
$calculatedDistance = $earthRadius * $stepTwo; 
 
return round($calculatedDistance); 
}


//会员卡密码加密
 function desEncrypt($data, $key) {
    $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_DES, MCRYPT_MODE_ECB), MCRYPT_RAND);
    $passcrypt = mcrypt_encrypt(MCRYPT_DES, $key, $data, MCRYPT_MODE_ECB, $iv);
    $encode = base64_encode($passcrypt);
    return $encode;
}
//会员卡密码解密
function desDecrypt($data, $key) {
    $decoded = base64_decode($data);
    $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_DES, MCRYPT_MODE_ECB), MCRYPT_RAND);
    $decrypted = mcrypt_decrypt(MCRYPT_DES, $key, $decoded, MCRYPT_MODE_ECB, $iv);
    return trim($decrypted);
}


/**
 * 银联签名加密
 * @param array $data 待签表单数据
 * @return string 签名
 */
function unionSign($data, $unionPayKey, $decode = false) {
    $sign_data = array();

    ksort($data);
    reset($data);
    foreach ($data as $key => $value) {
        if ($key == '' || $key == 'signature' || $key == 'signMethod')
            continue;
        else
            $sign_data[] = $key . '=' . ($decode ?  htmlspecialchars_decode($value, ENT_COMPAT) : $value);
    }

    return md5(implode('&', $sign_data) . '&' .  md5($unionPayKey));
}


/**
 * 补发购票短信
 * @param unknown $orderid
 * @param string $errmsg
 * @param string $seno
 */
function smsajax($smsConfig,$order,$mobile,$errmsg='',$seno='userpay') {
	$sms = new \Think\SmsModel($smsConfig);
	
	if(!empty($order['printNo'])){
		$order['printNo']=substr($order['printNo'],-8,8);
	}else{
		$order['printNo']=$order['verifyCode'];
	}
	//$content="订单号：".$order['orderCode']."，尊敬的用户：您已成功订购".date('Y年m月d日 H:i',$order['startTime']).$order['cinemaName'].$order['screenName'].$order['filmName'].' '.$order['seatIntroduce']."电影票，请携带会员卡至影城自助机取票或凭验证码".substr($order['verifyCode'],-8,8)."至影城自助取票机取票，祝您观影愉快！";
	$content="订单号".$order['orderCode']."，尊敬的用户：您已成功订购".date('Y年m月d日 H:i',$order['startTime']).$order['cinemaName'].$order['screenName'].'《'.$order['filmName'].'》'.$order['seatIntroduce']."电影票，请携带会员卡或凭短信验证码".$order['printNo']."至影城自助取票机或柜台取票，祝您观影愉快！";
	if(!empty($errmsg)){
		$errmsg1='';
		$length=mb_strlen($errmsg,'utf8');
		for($i=0;$i<$length;$i++){
			$errmsg1.=mb_substr( $errmsg, $i, 1, 'utf8' ).' ';
		}
		if(strrpos($content,$errmsg)){
			$content=str_replace($errmsg,$errmsg1,$content);
		}
	}
	$result=$sms->sendSms($mobile,$content);
	wlog('[短信状态]'.arrayeval($result), $seno);
	if($result['code']=='407'){
		$str = $result['text'];
		$str = substr ( $str, strpos ( $str, '(' ) + 1 );
		$str = substr ( $str, 0, strpos ( $str, ')' ) );
		smsajax($order,$mobile,$str);
		die();
	}
	if(D('orderFilm')->where(array('orderCode'=>$order['orderCode']))->setInc('supply',1)){
		return 1;
	}else{
		return 0;
	}
}

//随机字符串
function getRandChar($length){
   $str = null;
   $strPol = "0123456789abcdefghijklmnopqrstuvwxyz";
   $max = strlen($strPol)-1;
   for($i=0;$i<$length;$i++){
    $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
   }
   return $str;
 }


 function getMtxError($errorCode)
 {
 	$errorMsg['100101'] = '参数校验信息错误';
    $errorMsg['100102'] = 'XML参数解释错误';
    $errorMsg['100103'] = '地面连接数量已满或不存在此地面链接';
    $errorMsg['100104'] = '应用无权限';
    $errorMsg['100105'] = '令牌失效';
    $errorMsg['100201'] = '对外合作应用编码不存在';
    $errorMsg['100202'] = '对外合作应用密码错误';
    $errorMsg['100203'] = '对外合作应用没权限访问';
    $errorMsg['100204'] = '接入IP无权访问';
    $errorMsg['100205'] = '应用编码未获授权使用此方法(或合作商不卖票)';
    $errorMsg['100301'] = '令牌无效';
    $errorMsg['100302'] = '获取令牌应用不可用';
    $errorMsg['100303'] = '获取令牌失败';
    $errorMsg['100500'] = '通用异常错误';
    $errorMsg['100501'] = 'AppConfig配置读取错误';
    $errorMsg['100502'] = 'Sql执行错误';
    $errorMsg['100503'] = '手机钱包支付失败';
    $errorMsg['100050'] = '参数格式错误：AppPric(不符合定义格式，譬如空值，长度错误，类型错误)';
    $errorMsg['100051'] = '参数格式错误：BalancePric';
    $errorMsg['102101'] = '中心解锁成功地面解锁失败（实时解锁座位错误）';
    $errorMsg['101101'] = '获取地面影院信息出错';
    $errorMsg['101102'] = '中心锁座位出错';
    $errorMsg['101103'] = '排期截止';
    $errorMsg['101104'] = '座位数不够';
    $errorMsg['101105'] = '座位参数出错';
    $errorMsg['101106'] = '流水号已经存在';
    $errorMsg['101107'] = '地面锁坐失败（实时锁座通用错误码）';
    $errorMsg['101108'] = '影院与中心平台的网络异常';
    $errorMsg['101109'] = '排期截止或停售（实时锁座错误）';
    $errorMsg['101110'] = '座位已售出（实时锁座错误）';
    $errorMsg['101111'] = '请求已到影城，但影城处理过程中遇到未知异常（实时锁座错误）';
    $errorMsg['101112'] = '票务中心请求影城时遇到未知异常（实时锁座）';
    $errorMsg['101113'] = '该应用非实时锁座应用';
    $errorMsg['101114'] = '截止开放实时锁坐';
    $errorMsg['101115'] = '排期不可以网售';
    $errorMsg['101150'] = '参数格式错误：FeatureAppNo (不符合定义格式，譬如空值，长度错误，类型错误)';
    $errorMsg['101151'] = '参数格式错误：SerialNum';
    $errorMsg['101152'] = '参数格式错误：SeatNo';
    $errorMsg['101153'] = '参数格式错误：TicketPrice';
    $errorMsg['101154'] = '参数格式错误：PayType';
    $errorMsg['101155'] = '参数格式错误：RecvpMobilePhone';
    $errorMsg['101156'] = '参数格式错误：SeatInfos';
    $errorMsg['101157'] = '锁座成功修改订单价格失败';
    $errorMsg['101201'] = '没有对应的定单';
    $errorMsg['101257'] = '订单已无效';
    $errorMsg['101258'] = '订单已退';
    $errorMsg['101259'] = '订单已打票';
    $errorMsg['101260'] = '订单已确认未打票';
    $errorMsg['101261'] = '订单状态异常';
    $errorMsg['101200'] = '取票密码格式不正确';
    $errorMsg['101202'] = '中心卖票出错';
    $errorMsg['101203'] = '中心发送二唯码失败';
    $errorMsg['101204'] = '售票流水号已经存在';
    $errorMsg['101205'] = '锁座的流水号不存在';
    $errorMsg['101207'] = '地面卖票失败';
    $errorMsg['101208'] = '座位已售';
    $errorMsg['101209'] = '中心解锁失败';
    $errorMsg['101250'] = '参数格式错误：FeatureAppNo (不符合定义格式，譬如空值，长度错误，类型错误)';
    $errorMsg['101251'] = '参数格式错误：PayType';
    $errorMsg['101252'] = '参数格式错误：PaySeqNo';
    $errorMsg['101254'] = '参数格式错误：RecvpMobilePhone';
    $errorMsg['101301'] = '当月已经有支付记录';
    $errorMsg['101302'] = '应用商非法';
    $errorMsg['101303'] = '小额支付错误状态   小额支付出错';
    $errorMsg['101401'] = '支付成功,但发二维码和更新订单状态出错,退费成功';
    $errorMsg['101402'] = '支付成功,但发二维码和更新订单状态出错,但退费不成功';
    $errorMsg['101405'] = '生成订单失败';
    $errorMsg['101406'] = '单个号码票数受限制';
    $errorMsg['101407'] = '活动已经结束';
    $errorMsg['101408'] = '活动票数不够';
    $errorMsg['101409'] = '不明错误';
    $errorMsg['100600'] = '打印机出错(没有票机)';
    $errorMsg['100601'] = '调用远程服务器出错';
    $errorMsg['100603'] = '验证码出错';
    $errorMsg['100604'] = '订单状态异常';
    $errorMsg['100605'] = '无此订单';
    $errorMsg['100608'] = '排期截至,无法打票';
    $errorMsg['100609'] = '订单已打';
    $errorMsg['101501'] = '未找到该订单';
    $errorMsg['101502'] = '订单所属合作商与调用合作商不匹配';
    $errorMsg['101503'] = '订单状态不可退';
    $errorMsg['101504'] = '获取地面影院信息出错';
    $errorMsg['101505'] = '影院退票成功，中心退票失败';
    $errorMsg['101506'] = '排期截止';
    $errorMsg['101507'] = '不明错误';
    $errorMsg['101508'] = '退票失败(排期截止,或影城无此订单)';
    $errorMsg['101509'] = '退票操作返回代表地面退票失败(超时等原因)';
    $errorMsg['101601'] = '计划停售或截至';
    $errorMsg['101602'] = '座位不可售';
    $errorMsg['101603'] = '无该座位信息或排期已截止';
    $errorMsg['101604'] = '预订失败';
    $errorMsg['101701'] = '无该订单信息';
    return $errorMsg[$errorCode];
 }