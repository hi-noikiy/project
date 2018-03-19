<?php
class oppo {
	protected $serverUrl = "http://i.open.game.oppomobile.com/gameopen/user/fileIdInfo";
	protected $appvKey;
	protected $appSecret;
	protected $request_serverUrl;
	protected $time;
	protected $dataParams = array();
	protected $oauthSignatureMethod = 'HMAC-SHA1';
	protected $oauthTimestamp;
	protected $oauthNonce;
	protected $oauthVersion = '1.0';

	public function __construct($filedId, $token, $appvKey = NULL, $appSecret = NULL){
		$this->appvKey = $appvKey;
		$this->appSecret = $appSecret;
		$this->time = microtime(true);
		$dataParams['oauthConsumerKey'] = $this->appvKey;
		$dataParams['oauthToken'] = $token;
		$dataParams['oauthSignatureMethod'] = $this->oauthSignatureMethod;
		$dataParams['oauthTimestamp'] 		= intval($this->time*1000);
		$dataParams['oauthNonce'] 			= intval($this->time) + rand(0,9);
		$dataParams['oauthVersion'] 		= $this->oauthVersion;
		$this->dataParams = $dataParams;
		$this->request_serverUrl = $this->serverUrl."?fileId=".$filedId."&token=".$token;

	}


	public function result(){
		$sign = urldecode($this->_sign());
		$result = $this->OauthPostExecuteNew($this->_sign(), $this->_requestString(), $this->request_serverUrl);
		$result = json_decode($result,true);
		$str = $this->_requestString();
		$url = $this->request_serverUrl;
		
		write_log(ROOT_PATH."log","oppo_login_check_result_"," str=$str, url=$url, sign=$sign, ".date("Y-m-d H:i:s")."\r\n");
		return $result;
	}

	private function _requestString(){
		return $this->_assemblyParameters($this->dataParams);
	}

	private function _sign(){
		return $this->_signatureNew($this->appSecret.'&', $this->_requestString());
	}

	/**
	 * 请求的参数串组合
	 */
	private function _assemblyParameters($dataParams){
		$requestString 				= "";
		foreach($dataParams as $key=>$value){
			$requestString = $requestString . $key . "=" . $value . "&";
		}
		return $requestString;
	}


	/**
	 * 使用HMAC-SHA1算法生成签名
	 */
	private function _signatureNew($oauthSignature,$requestString){
		return urlencode(base64_encode( hash_hmac( 'sha1', $requestString,$oauthSignature,true) ));
	}


	/**
	 * Oauth身份认证请求
	 * @param string $Authorization 请求头值
	 * @param string $serverUrl     请求url
	 */
	public function OauthPostExecuteNew($sign,$requestString,$request_serverUrl){
			
		$ch = curl_init($request_serverUrl);

		$headers = array(
				"Expect:",
				"param: ".$requestString,
				"oauthsignature: ".$sign
		);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($ch);
			
		curl_close($ch);

		return $result;
	}

	/* public function OauthPostExecuteNew($sign,$requestString,$request_serverUrl){

	$opt = array(
			"http"=>array(
					"method"=>"GET",
					'header'=>array("param:".$requestString, "oauthsignature:".$sign),
			)
	);
	$res=file_get_contents($request_serverUrl,null,stream_context_create($opt));
	return $res;
	} */
}
?>