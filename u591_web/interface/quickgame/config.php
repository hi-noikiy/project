<?php
define('ROOT_PATH', str_replace('interface/quickgame/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";
$key_arr = array(
		8=>array(
			'gp'=>array('appSecret'=>'50704582867718988291231225629175'),
			'ios'=>array('appSecret'=>'93439501941770912853201758957337'),
		),
);
$xmVal = array(
		'99.99'=> 6000,
		'49.99'=> 3000,
		'24.99'=> 1500,
		//'15.99'=> 960, //狩猎券月卡
		'14.99'=> 900,
		'9.99'=> 600,
		//'5.99'=> 360, //月卡
		'4.99'=> 300,
		'0.99'=> 60,
		'0'=>0
);
$mVal = array(
		'kdygxm_10'=>['15.99',980,8], //狩猎券月卡
		'kdygxm_9'=>['5.99',360,1], //月卡
);
$moncards = array('kdygxm_9','kdygxm_10');
function checkPlayer($serverId, $playerId){
	$conn = SetConn($serverId);
	if($conn == false) return false;
	$table = betaSubTable($serverId, 'u_player', '1000');
	$sql = "select account_id from $table where id='$playerId' limit 1";
	$query = @mysqli_query($conn,$sql);
	$rows = @mysqli_fetch_assoc($query);
	if($rows)
		$rs = $rows;
	@mysqli_close($conn);
	return $rs['account_id']?$rs['account_id']:'';
}
function sendGift($serverId, $playerId , $goodid){
	$sid = togetherServer($serverId);
	$table = subTable($sid, 'u_gmtool', 1000);
	$conn = SetConn($sid);
	if($conn == false){
		write_log(ROOT_PATH.'log', 'exchange_code_ioslog_error_', "数据库连接失败$sid".date('Y-m-d H:i:s')."\r\n");
		return false;
	}
	$sql = "insert into $table(type, serverid, param, message, award_type1, award_param1, award_amount1)";
	$sql .= " values(8, '$sid', '$playerId' ,'活动奖励', '0', '$goodid', '1') ";
	if(false == mysqli_query($conn, $sql)){
		write_log(ROOT_PATH.'log', 'exchange_code_ioslog_error_', "sql=$sql,".mysqli_error($conn).date('Y-m-d H:i:s')."\r\n");
		return false;
	}
	return true;
}
?>
