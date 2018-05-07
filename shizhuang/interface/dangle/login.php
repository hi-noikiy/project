<?php
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
$file_in = file_get_contents("php://input");
write_log(ROOT_PATH."log","dl_login_log_"," post=$post,get=$get,file_in=$file_in, ".date("Y-m-d H:i:s")."\r\n");

$game_id = $_REQUEST['game_id'];
$token = $_REQUEST['token'];
$mid = $_REQUEST['mid'];

$api_key = $key_arr[$game_id]['app_key'];
$app_id = $key_arr[$game_id]['app_id'];

$sig = md5($app_id.'|'.$api_key.'|'.$token."|".$mid);
$pre = substr($token,0,3);
/*private final static String MASTER_HOST = "https://ctmaster.d.cn/api/cp/checkToken"; // 主路线地址
private final static String SLAVE_HOST = "https://ctslave.d.cn/api/cp/checkToken"; // 灾备线路地址
private final static String THIRD_CHECK_HOST = "http://ctslave.downjoy.com/api/cp/checkToken";*/
if($pre == 'ZB_'){
	$url = 'https://ctslave.d.cn/api/cp/checkToken';
}else{
	$url = 'https://ctmaster.d.cn/api/cp/checkToken';
}
//$url = 'http://ngsdk.d.cn/api/cp/checkToken';
$data = array();
$data['appid'] = $app_id;
$data['token'] = $token;
$data['umid'] = $mid;
$data['sig'] = $sig;
$result = https_post($url,$data);
$result_arr = json_decode($result,true);
if(!isset($result_arr['valid'])){
	$url = 'http://ctslave.downjoy.com/api/cp/checkToken';
	$result = https_post($url,$data);
	$result_arr = json_decode($result,true);
}
write_log(ROOT_PATH."log","dl_login_result_log_","url=".$url.", result=$result".date("Y-m-d H:i:s")."\r\n");

if(isset($result_arr['valid']) && $result_arr['valid']==1){
	$username = $mid.'@dl';
	$bindtable = getAccountTable($username,'token_bind');
	$bindwhere = 'token';
	$insertinfo = insertaccount($username,$bindtable,$bindwhere,$game_id);
	if($insertinfo['status'] == '1'){
		write_log(ROOT_PATH."log","dl_login_error_",json_encode($insertinfo).",post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
		exit('3 0');
	}else{
		$insert_id = $insertinfo['data'];
		if($insertinfo['isNew'] == '1'){
			write_log(ROOT_PATH."log","new_account_dl_log_","dl new account login , post=$post,get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
			exit("1 $insert_id");
		}else{
			exit("0 $insert_id");
		}
	}
}else{
    exit("4 0");
}
?>