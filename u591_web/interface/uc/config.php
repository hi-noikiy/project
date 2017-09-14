<?php
define('ROOT_PATH', str_replace('interface/uc/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";

$key_arr = array(
    	8=>array(
    			'android'=>array('cpId'=>'90','gameId'=>'731411','apiKey'=>'7c197aacc05ddb14be0f49899331be48','serverId'=>'0'),
    			'ios'=>array('cpId'=>'90','gameId'=>'731411','apiKey'=>'7c197aacc05ddb14be0f49899331be48','serverId'=>'0'),
    	),
);
function http_post($url,$data ){
	$curl = curl_init($url); // 启动一个CURL会话
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' ,'Content-Length: ' . strlen($data)));
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
	curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
	curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
	curl_setopt($curl, CURLOPT_TIMEOUT, 5); // 设置超时限制防止死循环
	curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
	$tmpInfo = curl_exec($curl);
	if (curl_errno($curl)) {
		$error = 'Errno' .curl_error($curl);
		curl_close($curl);
		return $error;
	}
	curl_close($curl);
	return $tmpInfo;
}
?>