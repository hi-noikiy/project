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
write_log(ROOT_PATH."log","play800_ios_menu_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$gid = $_REQUEST['gid'];
$site = $_REQUEST['site'];
$key = $_REQUEST['key'];
$sid = $_REQUEST['sid'];
$roleid = $_REQUEST['roleid'];
$channelId = $_REQUEST['channelId'];
$timeStamp = $_REQUEST['timeStamp'];
$sign = $_REQUEST['sign'];

$md5Str = $gid.$site.$key.$channelId.$timeStamp;
$mySign = md5($md5Str);
if($sign != $mySign){
    write_log(ROOT_PATH."log","play800_ios_menu_error_","sign error. post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array('code'=>'error','message'=>'sign error.')));
}

$table = betaSubTable($sid, 'u_player', '1000');
$conn = SetConn($sid);
$sql = "select id,account_id,name,level from $table where serverid='$sid' and id='$roleid' limit 1;";
if(false == $query = mysqli_query($conn,$sql)){
    write_log(ROOT_PATH."log","play800_ios_menu_error_","sql=$sql, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array('code'=>'error','message'=>'database error.')));
}
$rs = @mysqli_fetch_assoc($query);
if($rs){
	$resultdata['code'] = 'success';
	$resultdata['data'] = $menu_arr;
    exit(json_encode($resultdata));
} else {
    exit(json_encode(array('code'=>'error','message'=>'未查询到匹配的角色')));
}