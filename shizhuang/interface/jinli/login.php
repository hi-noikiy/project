<?php
/**
 * Created by PhpStorm.
 * User: wangtao
 * Date: 2017/5/24
 * Time: 下午1:36
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","jinli_login_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");


$userToken = $_REQUEST['user_token'];
$appUid = $_REQUEST['uid'];
$gameId = $_REQUEST['game_id'];

if(!$userToken || !$appUid || !$gameId){
    write_log(ROOT_PATH."log","jinli_login_error_","param error. post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('2 0');
}


$uids = explode('_', $appUid);
$uid = $uids[0];
$type = $uids[2];
$config = $key_arr[$gameId][$type];
$result_json = jinli_verify($userToken,$config);
write_log(ROOT_PATH."log","jinli_result_log_","result=".$result_json.", post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
$result_arr = json_decode($result_json,1);
if(isset($result_arr['r'])){
	$username = $uid.'@jinli';
	$bindtable = getAccountTable($username,'token_bind');
	$bindwhere = 'token';
	$insertinfo = insertaccount($username,$bindtable,$bindwhere,$gameId);
	if($insertinfo['status'] == '1'){
		write_log(ROOT_PATH."log","jinli_login_error_",json_encode($insertinfo).",post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
		exit('3 0');
	}else{
		$insert_id = $insertinfo['data'];
		if($insertinfo['isNew'] == '1'){
			write_log(ROOT_PATH."log","new_account_jinli_log_","jinli new account login , post=$post,get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
			exit("1 $insert_id");
		}else{
			exit("0 $insert_id");
		}
	}
}

write_log(ROOT_PATH."log","jinli_login_error_","result=".$rdata.date("Y-m-d H:i:s")."\r\n");
exit('4 0');


function jinli_verify($content,$config)
{
	$verify_url = "https://id.gionee.com/account/verify.do";
	$apiKey = $config['appKey'];    //替换成商户申请获取的APIKey
	$secretKey = $config['appSecret'];  //替换成商户申请获取的SecretKey
	$host = "id.gionee.com";
	$port = "443";
	$uri = "/account/verify.do";
	$method = "POST";

	$ts =  time();
	$nonce = strtoupper(substr(uniqid(),0,8)) ;

	$signature_str = $ts."\n".$nonce."\n".$method."\n".$uri."\n".$host."\n".$port."\n"."\n";

	$signature = base64_encode(hash_hmac('sha1',$signature_str,$secretKey,true));

	$Authorization = "MAC id=\"{$apiKey}\",ts=\"{$ts}\",nonce=\"{$nonce}\",mac=\"{$signature}\"";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$verify_url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_HTTPHEADER,array('Authorization: '.$Authorization));
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	$result = curl_exec ($ch);
	curl_close($ch);
	return $result;
}
