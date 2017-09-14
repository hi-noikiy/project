<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 礼包发放
* ==============================================
* @date: 2016-8-20
* @author: luoxue
* @version:
*/
include_once 'config.php';
$file_in = file_get_contents("php://input");
write_log(ROOT_PATH."log","uc_gift_set_all_log_","json=$file_in ".date("Y-m-d H:i:s")."\r\n");
$data = json_decode($file_in, true);
if(isset($data['data']['params'])){
	$apiKey = $key_arr['apiKey'];
	$caller = $key_arr['caller'];
	$aesKey = $key_arr['aesKey'];

	$id = $data['id'];
	$aesText = $data['data']['params'];
	$sign = $data['sign'];

	$signStr = $caller.'params='.$aesText.$apiKey;
	$mySign = md5($signStr);

	if($mySign != $sign){
		write_log(ROOT_PATH."log","uc_gift_set_error_log_","code=5000011,signStr=$signStr, json=$file_in ".date("Y-m-d H:i:s")."\r\n");
		exit(json_encode(array('id'=>$id, 'state'=>array('code'=>5000011, 'msg'=>'签名错误'))));
	}
	$json = decryptText($aesText, $aesKey, $aesKey);
	$result = json_decode($json, true);
	$kaId = $result['kaId']; //我们的游戏礼包ID
	$ucAccountId = $result['accountId'];
	$serverId = $result['serverId'];
	$roleId = $result['roleId'];
	
	if(!isset($giftList[$kaId])){
		write_log(ROOT_PATH."log","uc_gift_check_error_log_","code=5000032,signStr=$signStr, json=$file_in ".date("Y-m-d H:i:s")."\r\n");
		exit(json_encode(array('id'=>$id, 'state'=>array('code'=>5000032, 'msg'=>'礼包ID错误'))));
	}
	$conn = SetConn(85); //口袋账号库
	$channel_account = $ucAccountId.'@uc';
	$sql = "select id from account where channel_account='$channel_account' limit 1";
	if(false == $query = mysqli_query($conn,$sql)){
		write_log(ROOT_PATH."log","uc_gift_player_error_log_","code=5000000, sql=$sql, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
		exit(json_encode(array('id'=>$id, 'state'=>array('code'=>5000000, 'msg'=>'服务器内部错误'))));
	}
	$result = @mysqli_fetch_assoc($query);
	if(!$result){
		write_log(ROOT_PATH."log","uc_gift_player_error_log_","code=5000030, sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
		exit(json_encode(array('id'=>$id, 'state'=>array('code'=>5000030, 'msg'=>'accountId不存在'))));
	}
	$conn = SetConn(88);
	$sql = "select id ,addtime from web_gift_record where server_id=$serverId and player_id=$roleId and gift_id=$kaId order by id desc limit 1";
	if(false == $query = mysqli_query($conn,$sql)){
		write_log(ROOT_PATH."log","uc_gift_player_error_log_","code=5000000, sql=$sql, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
		exit(json_encode(array('id'=>$id, 'state'=>array('code'=>5000000, 'msg'=>'服务器内部错误'))));
	}
	$giftRs = @mysqli_fetch_assoc($query);
	if(date('Y-m-d', $giftRs['addtime']) == date('Y-m-d')){
		write_log(ROOT_PATH."log","uc_gift_player_error_log_","code=5000036, sql=$sql, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
		exit(json_encode(array('id'=>$id, 'state'=>array('code'=>5000036, 'msg'=>'同角色一天不能领取多次'))));
	}
	if(addWebGiftRecord($serverId, $roleId, $kaId)){
		$message = $giftList[$kaId];
		$conn = SetConn($serverId);
		$sql ="INSERT INTO `gmtool` ( `type`, `serverid`, `message`, `param`, `status`, `award_type1`, `award_param1`, `award_amount1`, `award_type2`, `award_param2`, `award_amount2`, `award_type3`, `award_param3`, `award_amount3`, `award_type4`, `award_param4`, `award_amount4`)
		VALUES (9, $serverId, '$message', $roleId, 0, $kaId, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);";
		if(mysqli_query($conn,$sql) == false){
			write_log(ROOT_PATH."log","uc_gift_player_error_log_","code=5000000, sql=$sql, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
			exit(json_encode(array('id'=>$id, 'state'=>array('code'=>5000000, 'msg'=>'服务器内部错误'))));
		}
		$jsonData = json_encode(array('result'=>true));
		$data = encyptText($jsonData, $aesKey, $aesKey);
		
		exit(json_encode(array('id'=>$id, 'state'=>array('code'=>2000000, 'msg'=>'成功'), 'data'=>$data)));
	} else {
		write_log(ROOT_PATH."log","uc_gift_player_error_log_","code=5000000, addWebGiftRecord ".date("Y-m-d H:i:s")."\r\n");
		exit(json_encode(array('id'=>$id, 'state'=>array('code'=>5000000, 'msg'=>'服务器内部错误'))));
	}
} else{
	write_log(ROOT_PATH."log","uc_gift_set_error_log_","code=5000020, json=$file_in ".date("Y-m-d H:i:s")."\r\n");
	exit(json_encode(array('id'=>$id, 'state'=>array('code'=>5000020, 'msg'=>'业务参数无效、错误'))));
}
function addWebGiftRecord($serverId, $roleId, $kaId){
	$conn = SetConn(88);
	$addtime = time();
	$sql ="insert into web_gift_record (server_id, player_id, gift_id, addtime) values ('$serverId', '$roleId', '$kaId', '$addtime')";
	if(mysqli_query($conn,$sql) == false)
		return false;
	return true;
}
?>