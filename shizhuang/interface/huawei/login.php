<?php
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
$file_in = file_get_contents("php://input");
write_log(ROOT_PATH."log","huawei_login_log_"," post=$post,get=$get,$HTTP_RAW_POST_DATA,file_in=$file_in, ".date("Y-m-d H:i:s")."\r\n");

$gameId = $_REQUEST['game_id'];
$ps = explode('_', $_REQUEST['user_token']);
$ts = $ps[0];
$playerId = $ps[1];
$playerLevel = $ps[2];
$playerSign = $ps[3];
$type = $ps[4];
if(!$playerId || !$ts || !$playerLevel || !$playerSign || !$gameId || !$type){
    write_log(ROOT_PATH."log","huawei_login_error_"," 参数异常, str=$str ".date("Y-m-d H:i:s")."\r\n");
    exit("2 0");//参数异常
}
$data['method'] = 'external.hms.gs.checkPlayerSign';
$data['ts'] = $ts;
$data['playerId'] = $playerId;
$data['playerLevel'] = $playerLevel;
$data['playerSSign'] = str_replace(' ','+',$playerSign);
$data['appId'] = $key_arr[$gameId][$type]['appid'];
$data['cpId'] = $key_arr[$gameId][$type]['cpid'];
ksort($data);
$rsastr = http_build_query($data);
$pubKey = $key_arr[$gameId][$type]['prikey'];
if(!$prikey = openssl_get_privatekey($pubKey)){
    write_log(ROOT_PATH."log","huawei_login_error_"," 私钥错误 ".openssl_error_string().date("Y-m-d H:i:s")."\r\n");
    exit("2 0");//参数异常
}
openssl_sign($rsastr, $crypted, $prikey,OPENSSL_ALGO_SHA256);
openssl_free_key($prikey);
//openssl_private_encrypt($rsastr, $crypted, $prikey);
$data['cpSign'] = urlencode(base64_encode($crypted));
$url = "https://gss-cn.game.hicloud.com/gameservice/api/gbClientApi";
$data['playerSSign'] = urlencode($data['playerSSign']);
$url_result = https_post($url,$data);
write_log(ROOT_PATH."log","huawei_login_result_",$rsastr.", post=$post,get=$get ,url=$url, url_result=$url_result ,data=".json_encode($data).date("Y-m-d H:i:s")."\r\n");

$url_result_arr = json_decode($url_result,true);
if(!isset($url_result_arr['rtnCode']) || $url_result_arr['rtnCode'] != '0'){
    write_log(ROOT_PATH."log","huawei_login_error_"," 验证失败, post=$post,get=$get ,url=$url, url_result=$url_result ".date("Y-m-d H:i:s")."\r\n");
    exit("4 0");//参数异常
}

$username = $playerId.'@huawei';
$bindtable = getAccountTable($username,'token_bind');
$bindwhere = 'token';
$insertinfo = insertaccount($username,$bindtable,$bindwhere,$gameId);
if($insertinfo['status'] == '1'){
	write_log(ROOT_PATH."log","huawei_login_error_",json_encode($insertinfo).",post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit('3 0');
}else{
	$insert_id = $insertinfo['data'];
	if($insertinfo['isNew'] == '1'){
		exit("1 $insert_id");
	}else{
		exit("0 $insert_id");
	}
}


?>
