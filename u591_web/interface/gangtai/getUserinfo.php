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
write_log(ROOT_PATH."log","gangtai_getuserinfo_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
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
	write_log(ROOT_PATH."log","gangtai_getuserinfo_error_",$str.",sign error,{$data['sign']}, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	$returnarr['code'] = '1';
	$returnarr['message'] = 'sign error';
	exit(json_encode($returnarr));
}
$serverId = $_POST['serverId'];
$accountId = $_POST['accountId'];
global $accountServer;
$accountConn = $accountServer[8];
$conn = SetConn($accountConn);
$sql_account = "select id from account where channel_account ='$accountId' limit 1;";
$query_account = mysqli_query($conn, $sql_account);
$result_account = @mysqli_fetch_assoc($query_account);
@mysqli_close($conn);
if(!$result_account['id']){ 
	write_log(ROOT_PATH."log","gangtai_getuserinfo_error_",$sql_account."accountid is null, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	$returnarr['code'] = '1';
	$returnarr['message'] = 'accountid is not exist';
	exit(json_encode($returnarr));
}
$myconn = mydb($serverId);
if($myconn == false) return false;
$table = betaSubTable($serverId, 'u_player', '1000');
$sql = "select id as roleId,name as roleName,level as roleLevel,login_time as lastLoginTime,create_time as createTime from $table where account_id='{$result_account['id']}' limit 1";
$query = @mysqli_query($myconn,$sql);
$rows = @mysqli_fetch_assoc($query);
if(!$rows){
	write_log(ROOT_PATH."log","gangtai_getuserinfo_error_",$sql."player is null, post=$post, ".mysqli_error($myconn).','.date("Y-m-d H:i:s")."\r\n");
	$returnarr['code'] = '1';
	$returnarr['message'] = 'player is not exist';
	exit(json_encode($returnarr));
}
@mysqli_close($myconn);
write_log(ROOT_PATH."log","gangtai_getuserinfo_success_","获取角色信息成功, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
$returnarr['code'] = '200';
$returnarr['message'] = 'ok';
$returnarr['data'] = $rows;
exit(json_encode($returnarr));
