<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 验证码修改密码 先支持手机  
* ==============================================
* @date: 2016-7-29
* @author: luoxue
* @version:
*/
die;
include_once 'config.php';
set_time_limit(0);
$OperID = "hainwl";
$OperPass = "hnsy47";
$content = "《衣范儿》5月9日奢华公测！快来打造你的时尚衣柜吧！
【IOS】App Store搜索“衣范儿”下载。【安卓】加入官方Q群72945037下载。";
$content =iconv("UTF-8","GBK",$content);
$content = urlencode($content);
$conn = mysqli_connect('127.0.0.1','root','root', 'shizhuang');
$sql = "select phone from phone where status=0";
$query = mysqli_query($conn,$sql);
$i=0;
$y=0;
while($rs = @mysqli_fetch_assoc($query)){
	$phone = $rs['phone'];
	$url = "http://221.179.180.158:9007/QxtSms/QxtFirewall?OperID=$OperID&OperPass=$OperPass&SendTime=&ValidTime=&AppendID=&DesMobile=$phone&Content=$content&ContentType=8";
	$result =  https_post($url, array());
	$xml_arr = simplexml_load_string($result);
	$code = $xml_arr->code;
	if($code=="00" || $code=="01" || $code=="03"){
		write_log(ROOT_PATH."log","phone_success_","$phone".date("Y-m-d H:i:s")."\r\n");
		echo $i++;
		$upsql = "update phone set status=1 where phone ='$phone'";
		if(false != mysqli_query($conn,$upsql)){
			echo $y++;
			echo $phone;
		}
	}
}
echo $i,$y;

?>
