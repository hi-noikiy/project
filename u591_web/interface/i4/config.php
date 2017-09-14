<?php
define('ROOT_PATH', str_replace('interface/i4/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";

$arr_key = array(
    	8=>array('appid'=>'2530','appkey'=>'5d3b5b1e4227480f93ba9ecd2d44018a')
);

function i4_HttpsPost($url,$data) {
    $ch = curl_init();
    // 设置选项，包括URL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);	// 对证书来源的检查
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);	// 从证书中检查SSL加密算法是否存在
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);	// 模拟用户使用的浏览器
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);	// 使用自动跳转
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);		// 自动设置Referer
    curl_setopt($ch, CURLOPT_POST, 1);		// 发送一个 常规的Post请求
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);	// Post提交的数据包
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);		// 设置超时限制防止死循环
    curl_setopt($ch, CURLOPT_HEADER, 0);		// 显示返回的Header区域内容
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 	//获取的信息以文件流的形式返回

    $output = curl_exec($ch);	// 执行操作
    if(curl_errno($ch))
    {
        echo "Errno".curl_error($ch); 	// 捕抓异常
    }
    curl_close($ch);	// 关闭CURL
    return $output;
}



?>
