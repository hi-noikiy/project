<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 活动信息
* ==============================================
* @date: 2016-7-15
* @author: luoxue
* @version:
*/
include_once 'config.php';
$post = serialize($_POST);
write_log(ROOT_PATH."log","actinfos_log_","post=$post, ".date("Y-m-d H:i:s")."\r\n");
$serverid = $_POST['serverid'];
/*$sign = trim($_POST['sign']);
$appKey = $key_arr['appKey'];

$my_sign = md5($serverid.$appKey);

if($sign != $my_sign)
	exit(json_encode(array('status'=>1, 'msg'=>'sign error.')));
*/
$sid = substr($serverid, 0,strlen($serverid)-3).'001';
$conn = SetConn($sid);
$serverid = togetherServer($serverid);
$sql = "select data from u_statistic where serverid = '$serverid' and event_type='27' limit 1";
if(false == $query = mysqli_query($conn,$sql)){
	write_log(ROOT_PATH."log","actinfos_error_",$sql.",post=$post, ".mysqli_error($conn).','.date("Y-m-d H:i:s")."\r\n");
	exit(json_encode(array('status'=>1, 'msg'=>'u_statistic error.')));
}
$result = @mysqli_fetch_assoc($query);
if(!$result){
	write_log(ROOT_PATH."log","actinfos_error_","get server data error,post=$post, ".date("Y-m-d H:i:s")."\r\n");
	exit(json_encode(array('status'=>1, 'msg'=>'get server data error')));
}
$createtime = strtotime(date('Ymd',$result['data']));
$cday = (strtotime(date('Ymd'))-$createtime)/(24*60*60)+1; //开服天数
$nowdate = date('Y-m-d H:i:s');
$conn = SetConn(88);
$sql = "select * from web_act where isShowActivity='0' and starttime<='$nowdate' and endtime>='$nowdate'";
if(false == $query = mysqli_query($conn,$sql)){
	write_log(ROOT_PATH."log","actinfos_error_",$sql.",post=$post, ".date("Y-m-d H:i:s")."\r\n");
	exit(json_encode(array('status'=>1, 'msg'=>'web_act error.')));
}
$info = array();
while($row = @mysqli_fetch_assoc($query)){
	$servers = explode(',', $row['serverid']);
	if(in_array($serverid, $servers)){
		if($row['showday'] && $row['showday']<$cday){ //开服几天内显示
			continue;
		}
		if($row['noshowday'] && $row['noshowday']>$cday){ // 开服几天内不显示
			continue;
		}
		unset($row['serverid'],$row['starttime'],$row['endtime'],$row['id'],$row['gameid'],$row['isShowActivity'],$row['showday'],$row['noshowday'],$row['isShowActivity']);
		$info [] = $row;
	}
}
write_log(ROOT_PATH."log","actinfos_result_",json_encode($info).",info show,post=$post, ".date("Y-m-d H:i:s")."\r\n");
exit(json_encode(array('status'=>0, 'msg'=>$info)));