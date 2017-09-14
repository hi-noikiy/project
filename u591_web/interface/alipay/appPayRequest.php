<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 自定义app支付类
* ==============================================
* @date: 2016-11-24
* @author: luoxue
* @version:
*/
class appPayRequest{
	//应用ID
	public $appId;
	//私钥文件路径
	public $rsaPrivateKeyFilePath;
	public $method = 'alipay.trade.app.pay'; //接口名称
	public $charset = 'utf-8';
	//私钥值
	public $rsaPrivateKey;
	
	public $rsaPublicKey;
	//api版本
	public $apiVersion = "1.0";
	//签名类型
	public $signType = "RSA";
	//public $timestamp;
	//public $out_trade_no; //订单号
	//public $notifyUrl;
	
	public $biz_content;
	public $subject;
	public $total_amount; //订单总金额，单位为元，精确到小数点后两位
	//public $product_code; //销售产品码
	private $fileCharset = "UTF-8";
	public $params = array();
	
	public function setRsaPrivateKey($rsaPrivateKey){
		$this->rsaPrivateKey = $rsaPrivateKey;
	}
	public function setAppId($appId){
		$this->appId = $appId;
	}
	
	public function setAmount($amount){
		$this->total_amount = $amount;
	}
	
	public function setSubject($subject){
		$this->subject = $subject;
	}
	public  function setBizContent ($bizContent){
		$this->biz_content = $bizContent;
	}
	
	public function setRsaPublicKey ($rsaPublicKey){
		$this->rsaPublicKey = $rsaPublicKey;
	}
	
	public function getParams(){
		$this->params['app_id'] =  $this->appId;
		$this->params['method'] =  $this->method;
		$this->params['format'] =  'JSON';
		$this->params['charset'] =  $this->charset;
		$this->params['sign_type'] =  $this->signType;
		$this->params['timestamp'] =  $this->setTimestamp();
		$this->params['version'] =  $this->version;
		$this->params['notify_url'] =  $this->setNotifyUrl();
		
		$bizContentArr  =array();
		$bizContentArr['subject'] = $this->subject;
		$bizContentArr['out_trade_no'] = $this->setOutTradeNo();
		$bizContentArr['total_amount'] = $this->total_amount;
		$bizContentArr['product_code'] = $this->setProductCode();
		
		$this->params['biz_content'] =  json_encode($bizContentArr);
		
		return $this->params;
	}
	
	
	public function rsaSign($params, $signType = "RSA") {
		return $this->sign($this->getSignContent($params), $signType);
	}
	
	protected function setProductCode(){
		return 'QUICK_MSECURITY_PAY';
	}
	
	protected function setNotifyUrl(){
		return 'http://gunweb.u591.com:83/interface/alipay/callback.php';
	}
	
	protected function setTimestamp(){
		return date('Y-m-d H:i:s');
	}
	
	protected function setOutTradeNo (){
		return $this->biz_content.'_'.date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
	}
	public  function getSignContent($params) {
		ksort($params);
		$stringToBeSigned = "";
		$i = 0;
		foreach ($params as $k => $v) {
			if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {
				// 转换成目标字符集
				$v = $this->characet($v, $this->postCharset);
				if ($i == 0) {
					$stringToBeSigned .= "$k" . "=" . "$v";
				} else {
					$stringToBeSigned .= "&" . "$k" . "=" . "$v";
				}
				$i++;
			}
		}
		unset ($k, $v);
		return $stringToBeSigned;
	}
	
	public function getSignContentUrlencode($params){
		ksort($params);
		$stringToBeSigned = "";
		$i = 0;
		foreach ($params as $k => $v) {
			if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {
				$v = urlencode($v);
				// 转换成目标字符集
				$v = $this->characet($v, $this->postCharset);
				if ($i == 0) {
					$stringToBeSigned .= "$k" . "=" . "$v";
				} else {
					$stringToBeSigned .= "&" . "$k" . "=" . "$v";
				}
				$i++;
			}
		}
		unset ($k, $v);
		return $stringToBeSigned;
	}
	
	
	protected function sign($data, $signType = "RSA") {
		if($this->checkEmpty($this->rsaPrivateKeyFilePath)){
			$priKey=$this->rsaPrivateKey;
			$res = "-----BEGIN RSA PRIVATE KEY-----\n" .
					wordwrap($priKey, 64, "\n", true) .
					"\n-----END RSA PRIVATE KEY-----";
		}else {
			$priKey = file_get_contents($this->rsaPrivateKeyFilePath);
			$res = openssl_get_privatekey($priKey);
		}
	
		($res) or die('您使用的私钥格式错误，请检查RSA私钥配置');
	
		if ("RSA2" == $signType) {
			openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA256);
		} else {
			openssl_sign($data, $sign, $res);
		}
	
		if(!$this->checkEmpty($this->rsaPrivateKeyFilePath)){
			openssl_free_key($res);
		}
		$sign = base64_encode($sign);
		return $sign;
	}
	
	/**
	 * 校验$value是否非空
	 *  if not set ,return true;
	 *    if is null , return true;
	 **/
	protected function checkEmpty($value) {
		if (!isset($value))
			return true;
		if ($value === null)
			return true;
		if (trim($value) === "")
			return true;
	
		return false;
	}
	/**
	 * 转换字符集编码
	 * @param $data
	 * @param $targetCharset
	 * @return string
	 */
	function characet($data, $targetCharset) {
	
		if (!empty($data)) {
			$fileType = $this->fileCharset;
			if (strcasecmp($fileType, $targetCharset) != 0) {
				$data = mb_convert_encoding($data, $targetCharset, $fileType);
				//$data = iconv($fileType, $targetCharset.'//IGNORE', $data);
			}
		}
		return $data;
	}
	
	public function rsaCheckV1($params, $rsaPublicKeyFilePath) {
		$sign = $params['sign'];
		$params['sign_type'] = null;
		$params['sign'] = null;
	
		return $this->verify($this->getSignContent($params), $sign, $rsaPublicKeyFilePath);
	}
	

	public function verify($data, $sign, $rsaPublicKeyFilePath, $signType = 'RSA') {
		if(!$this->checkEmpty($this->rsaPublicKey)){
			$pubKey= $this->rsaPublicKey;
			$res = "-----BEGIN PUBLIC KEY-----\n" .
					wordwrap($pubKey, 64, "\n", true) .
					"\n-----END PUBLIC KEY-----";
		}else {
			//读取公钥文件
			$pubKey = file_get_contents($rsaPublicKeyFilePath);
			//转换为openssl格式密钥
			$res = openssl_get_publickey($pubKey);
		}
	
		($res) or die('支付宝RSA公钥错误。请检查公钥文件格式是否正确');
	
		//调用openssl内置方法验签，返回bool值
	
		if ("RSA2" == $signType) {
			$result = (bool)openssl_verify($data, base64_decode($sign), $res, OPENSSL_ALGO_SHA256);
		} else {
			$result = (bool)openssl_verify($data, base64_decode($sign), $res);
		}
		if(!$this->checkEmpty($this->rsaPublicKey)) {
			//释放资源
			openssl_free_key($res);
		}
	
		return $result;
	}
	
}