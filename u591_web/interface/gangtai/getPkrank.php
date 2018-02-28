<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/1/4
 * Time: 下午2:01
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","gangtai_getchargenum_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
$returnarr =array();
$returnarr['data'] = array();
$sign = $_POST['sign'];
global $key_arr;
$key = $key_arr[8]['android']['appkey'];
$data = $_POST;
unset($data['sign']);
ksort($data);
$str = urldecode(http_build_query($data)).'&'.$key;
$data['sign'] = md5($str);
if($sign != $data['sign']) {
	write_log(ROOT_PATH."log","gangtai_getchargenum_error_",$str.",sign error,{$data['sign']}, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	$returnarr['code'] = '1';
	$returnarr['message'] = 'sign error';
	exit(json_encode($returnarr));
}
$serverId = $_POST['serverId'];
$roleId = $_POST['roleId'];
$beginTime = $_POST['beginTime'];
$endTime = $_POST['endTime'];
$myconn = mydb($serverId);
if($myconn == false) return false;
$table = betaSubTable($serverId, 'u_player', '1000');
$sql = "select account_id from $table where id='$roleId' limit 1";
$query = @mysqli_query($myconn,$sql);
$rows = @mysqli_fetch_assoc($query);
if(!$rows){
	write_log(ROOT_PATH."log","gangtai_getchargenum_error_",$sql."account is null, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	$returnarr['code'] = '1';
	$returnarr['message'] = 'account is not exist';
	exit(json_encode($returnarr));
}
$sid = togetherServer($serverId);
$myconn = mydb($sid);
$table = betaSubTable($sid, 'u_card', '1000');
$sql = "select sum(data) as money from $table where account_id='{$rows['account_id']}' and server_id='$serverId' and time_stamp between '$beginTime' and '$endTime' limit 1";
$query = @mysqli_query($myconn,$sql);
$rows = @mysqli_fetch_assoc($query);
@mysqli_close($myconn);
$money = $rows?$rows['money']:0;
write_log(ROOT_PATH."log","gangtai_getchargenum_success_",$sql."充值金额获取成功, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
$returnarr['code'] = '200';
$returnarr['message'] = 'ok';
$returnarr['data'] = $money;
exit(json_encode($returnarr));
