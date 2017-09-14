<?php
/**
 * Created by PhpStorm.
 * User: wangtao
 * Date: 2017/5/24
 * Time: 下午1:36
 */
include_once 'config.php';
$post = file_get_contents('php://input');
write_log(ROOT_PATH."log","xiao7_sign_","post=$post ".date("Y-m-d H:i:s")."\r\n");
$gameId = 8;
parse_str($post,$data);
$extendsInfo = $data['game_orderid']; //提取拓展信息
$extendsInfoArr = explode('_', $extendsInfo);
$type = $extendsInfoArr[3];
$game_id = $extendsInfoArr[0];
$accountConn = $accountServer[$game_id];
$conn = SetConn($accountConn);
$account = $extendsInfoArr[2];
$sql = "select channel_account from account where id='$account' limit 1";
if(false == $query = mysqli_query($conn,$sql)){
	write_log(ROOT_PATH."log","xiao7_sign_error_",json_encode($data).", sql=$sql, mysql error, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
	exit(json_encode(array()));
}
$result = @mysqli_fetch_assoc($query);
if(!$result){
	write_log(ROOT_PATH."log","xiao7_sign_error_","no data, sql=$sql ".date("Y-m-d H:i:s")."\r\n");
	exit(json_encode(array()));
}
$channel_account = $result['channel_account'];
$data['game_guid'] = explode('@', $channel_account)[0];
global $key_arr;
$key = $key_arr[$gameId][$type]['publickey'];
ksort($data);
$signstr = urldecode(http_build_query($data)).$key;
$sign = md5($signstr);
write_log(ROOT_PATH."log","xiao7_sign_","signstr=$signstr,sign=$sign ".date("Y-m-d H:i:s")."\r\n");
exit(json_encode(array('sign'=>$sign,'game_guid'=>$data['game_guid'])));