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
write_log(ROOT_PATH."log","gangtai_getonline_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
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
	write_log(ROOT_PATH."log","gangtai_getonline_error_",$str.",sign error,{$data['sign']}, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	$returnarr['code'] = '1';
	$returnarr['message'] = 'sign error';
	exit(json_encode($returnarr));
}
$serverId = $_POST['serverId'];
$roleId = $_POST['roleId'];
$myconn = mydb($serverId);
if($myconn == false) return false;
$table = betaSubTable($serverId, 'u_player_activity', '1000');
$sql = "select type5 from $table where player_id='$roleId' limit 1";
$query = @mysqli_query($myconn,$sql);
$rows = @mysqli_fetch_assoc($query);
@mysqli_close($myconn);

$time = $rows?$rows['type5']:0;
write_log(ROOT_PATH."log","gangtai_getonline_success_",$sql."在线时长获取成功, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
$returnarr['code'] = '200';
$returnarr['message'] = 'ok';
$returnarr['data'] = $time;
exit(json_encode($returnarr));
