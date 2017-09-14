<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/2/23
 * Time: 下午7:38
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."../log","share_info_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
$conn = SetConn(88);
if($conn == false){
	write_log(ROOT_PATH."../log","share_error_","web数据库连接失败,post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('web数据库连接失败');
}

$player_id = $_POST['player_id'];
$logdate = $_POST['logdate'];
$server_id = $_POST['server_id'];
if($logdate != date('Ymd')){ //日期不对
	write_log(ROOT_PATH."../log","share_error_","日期错误,post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit('日期错误');
}
$sql = "select id from web_share_log where player_id = '$player_id' and server_id='$server_id' and logdate='$logdate' limit 1;";
$query = @mysqli_query($conn, $sql);
$result = @mysqli_fetch_array($query);
if($result['id']){ //当日已分享
	write_log(ROOT_PATH."../log","share_error_","当日已分享,post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit('当日已分享');
}
$sql = "insert into web_share_log(player_id,logdate,server_id)values('$player_id','$logdate','$server_id') ";
if (mysqli_query($conn,$sql) == false){ //执行失败
	write_log(ROOT_PATH."../log","share_error_","插入web数据库失败,sql=$sql, ".mysqli_error($conn).date("Y-m-d H:i:s")."\r\n");
	exit('插入web数据库失败');
}
$insert_id = mysqli_insert_id($conn);
if(!$insert_id){
	write_log(ROOT_PATH."../log","share_error_","插入web数据库失败,".date("Y-m-d H:i:s")."\r\n");
	exit('插入web数据库失败');
}
$gmtoolTable = 'u_gmtool';
$sid = togetherServer($server_id);
$table = subTable($sid, $gmtoolTable, 1000);
$myconn = SetConn($sid);
$message = '感谢分享，恭喜获得金币*500、体力*5。';
$type1 = 1;
$param1 = 0;
$amount1 = 500;
$type2 = 9;
$param2 = 0;
$amount2 = 5;
$type3 = 0;
$param3 = 0;
$amount3 = 0;
$type4 = 0;
$param4 = 0;
$amount4 = 0;
$sql = "insert into $table(type, serverid, param, message, award_type1, award_param1, award_amount1, award_type2, award_param2, award_amount2,  award_type3, award_param3, award_amount3,  award_type4, award_param4, award_amount4)";
$sql .= " values(8, '$sid', '$player_id' ,'$message', '$type1', '$param1', '$amount1', '$type2', '$param2', '$amount2', '$type3', '$param3', '$amount3', '$type4', '$param4', '$amount4') ";
if(false == mysqli_query($myconn, $sql)){
	write_log(ROOT_PATH."../log","share_error_","插入游服数据库失败,sql=$sql, ".mysqli_error($myconn).date("Y-m-d H:i:s")."\r\n");
	exit('插入游服数据库失败');
}
write_log(ROOT_PATH."../log","share_info_","发放成功, ".date("Y-m-d H:i:s")."\r\n");
exit('发放成功');

//分表
function subTable($serverId, $table, $sum){
	if($serverId == 0)
		return $table;
	$suffix = $serverId%$sum;
	$s = sprintf('%03d', $suffix);
	return $table.$s;
}