<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/3/22
 * Time: 下午7:48
 *
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","play800_ios_orderId_new_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$roleid = $_REQUEST['roleid'];
$sid = $_REQUEST['serverid'];
$ext = $_REQUEST['ext'];
$timeStamp = $_REQUEST['timeStamp'];
$sign = $_REQUEST['sign'];

$md5Str = $ext.$roleid.$sid.$timeStamp;
$mySign = md5($md5Str);
if($sign != $mySign){
    write_log(ROOT_PATH."log","play800_ios_orderId_new_error_","sign error. post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array('code'=>'error','message'=>'sign error.')));
}
$table = betaSubTable($sid, 'u_player', '1000');
$conn = SetConn($sid);
$sql = "select account_id from $table where id='{$roleid}' limit 1;";
if(false == $query = mysqli_query($conn,$sql)){
	write_log(ROOT_PATH."log","play800_ios_orderId_new_error_","sql=$sql, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
	exit(json_encode(array('code'=>'error','message'=>'database error.')));
}
$rs = @mysqli_fetch_assoc($query);
if($rs){
	$exts = explode('_', $ext);
	$gameId = $exts['0'];
	$accountId = $exts['2'];
	$type =$exts['3'];
	$giftId = $exts['4'];
	if($rs['account_id'] != $accountId){
		write_log(ROOT_PATH."log","play800_ios_orderId_new_error_","accountId error. post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
		exit(json_encode(array('code'=>'error','message'=>'database error.')));
	}
	global $accountServer;
	$accountConn = $accountServer[$gameId];
	$conn = SetConn($accountConn);
	$sql_account = "select NAME,channel_account from account where id='{$accountId}' limit 1";
	$query_account = @mysqli_query($conn, $sql_account);
	$result_account = @mysqli_fetch_assoc($query_account);
	if(!isset($result_account['NAME'])){
		write_log(ROOT_PATH."log","play800_ios_orderId_new_error_", "account is not exist. sql=$sql_account, ".date("Y-m-d H:i:s")."\r\n");
		exit(json_encode(array('code'=>'error','message'=>'未查询到匹配的角色')));
	}
	$uid = explode('@', $result_account['channel_account'])[0];
	$resultdata['code'] = 'success';
	$cpOrderId = $gameId.'_'.$sid.'_'.$accountId.'_'.$type.'_'.$giftId.'_'.date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
	$resultdata['data'] = array('cp_order_id'=>$cpOrderId,'uid'=>$uid);
	exit(json_encode($resultdata));
} else {
	exit(json_encode(array('code'=>'error','message'=>'未查询到匹配的角色')));
}