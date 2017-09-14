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
write_log(ROOT_PATH."log","play800_ios_orderId_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$gid = $_REQUEST['gid'];
$sid = $_REQUEST['sid'];
$roleid = $_REQUEST['roleid'];
$site = $_REQUEST['site'];
$key = $_REQUEST['key'];
$pid = $_REQUEST['pid'];
$uid = $_REQUEST['uid'];
$channelId = $_REQUEST['channelId'];
$timeStamp = $_REQUEST['timeStamp'];
$sign = $_REQUEST['sign'];
$gameId = 8;

$md5Str = $gid.$sid.$roleid.$pid.$site.$key.$channelId.$timeStamp;
$mySign = md5($md5Str);
if($sign != $mySign){
    write_log(ROOT_PATH."log","play800_ios_orderId_error_","sign error. post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array('code'=>'error','message'=>'sign error.')));
}

global $accountServer;
$accountConn = $accountServer[$gameId];
$conn = SetConn($accountConn);
$sql_account = "select NAME,dwFenBaoID,clienttype,id from account where channel_account='$uid@play800' limit 1";
$query_account = @mysqli_query($conn, $sql_account);
$result_account = @mysqli_fetch_assoc($query_account);
if(!isset($result_account['NAME'])){
	write_log(ROOT_PATH."log","play800_ios_orderId_error_", "account is not exist. sql=$sql_account, ".date("Y-m-d H:i:s")."\r\n");
	exit(json_encode(array('code'=>'error','message'=>'未查询到匹配的角色')));
}
//$dwFenBaoID = $result_account['dwFenBaoID'];//防止跨平台充值

$table = betaSubTable($sid, 'u_player', '1000');
$conn = SetConn($sid);
$sql = "select id,account_id,name,level from $table where serverid='$sid' and account_id='".$result_account['id']."' limit 1;";
if(false == $query = mysqli_query($conn,$sql)){
    write_log(ROOT_PATH."log","play800_ios_orderId_error_","sql=$sql, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array('code'=>'error','message'=>'database error.')));
}
$rs = @mysqli_fetch_assoc($query);
if($rs){
	$resultdata['code'] = 'success';
	/*global $fenbao_arr;
	$site = $fenbao_arr[$dwFenBaoID];*/
	
	$servId = $sid;
	$number = str_replace('kdyg_ios','', $site);
	$type = 'ios'.($number+1);
	if($type == 'ios1'){
		$type ='ios';
	}
	$gift = 0;
	$lev = intval($rs['level']);
	$cpOrderId = $gameId.'_'.$servId.'_'.$rs['account_id'].'_'.$type.'_'.$gift.'_'.$lev.'_'.date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
	$resultdata['data'] = array('cp_order_id'=>$cpOrderId);
    exit(json_encode($resultdata));
} else {
    exit(json_encode(array('code'=>'error','message'=>'未查询到匹配的角色')));
}