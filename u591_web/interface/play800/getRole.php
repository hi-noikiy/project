<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2016/12/28
 * Time: 上午10:19
 * 获取区服列表
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","play800_ios_role_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$gid = $_REQUEST['gid'];
$site = $_REQUEST['site'];
$key = $_REQUEST['key'];
$sid = $_REQUEST['sid'];
$uid = $_REQUEST['uid'];
$channelId = $_REQUEST['channelId'];
$timeStamp = $_REQUEST['timeStamp'];
$sign = $_REQUEST['sign'];


$md5Str = $gid.$sid.$uid.$site.$key.$channelId.$timeStamp;
$mySign = md5($md5Str);
if($sign != $mySign){
    write_log(ROOT_PATH."log","play800_ios_role_error_","sign error. post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array('code'=>'error','message'=>'sign error.')));
}

global $accountServer;
$gameId = 8;
$accountConn = $accountServer[$gameId];
$conn = SetConn($accountConn);
$sql_account = "select NAME,dwFenBaoID,clienttype,id from account where channel_account='$uid@play800' limit 1";
$query_account = @mysqli_query($conn, $sql_account);
$result_account = @mysqli_fetch_assoc($query_account);
if(!isset($result_account['NAME'])){
	write_log(ROOT_PATH."log","play800_ios_role_error_", "account is not exist. sql=$sql_account, ".date("Y-m-d H:i:s")."\r\n");
	exit(json_encode(array('code'=>'error','message'=>'未查询到匹配的角色')));
}

$table = betaSubTable($sid, 'u_player', '1000');
$conn = SetConn($sid);
$sql = "select id,account_id,name,level from $table where serverid='$sid' and account_id='".$result_account['id']."' limit 1;";
if(false == $query = mysqli_query($conn,$sql)){
    write_log(ROOT_PATH."log","play800_ios_role_error_","sql=$sql, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array('code'=>'error','message'=>'database error.')));
}
$rs = @mysqli_fetch_assoc($query);
if($rs){
	$resultdata['code'] = 'success';
	$resultdata['data'] = [array( 'roleid'=>$rs['id'], 'rolename'=>$rs['name'])];
    exit(json_encode($resultdata));
} else {
    exit(json_encode(array('code'=>'error','message'=>'未查询到匹配的角色')));
}