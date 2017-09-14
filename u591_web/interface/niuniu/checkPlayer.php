<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/1/17
 * Time: 上午11:53
 */
include_once 'config.php';
$playerName = $_REQUEST['usr_name'];
$serverId = $_REQUEST['serv_id'];
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","niuniu_checkPlayer_log_","post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");

if(!$playerName)
    exit(json_encode(array('err_code'=>1,'desc'=>'player name not empty.')));
if(!$serverId)
    exit(json_encode(array('err_code'=>1,'desc'=>'server not empty.')));
$conn = SetConn($serverId);

if($conn == false){
    write_log(ROOT_PATH."log","niuniu_checkPlayer_error_","mysql error. post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array('err_code'=>1,'desc'=>'mysql connect error.')));
}

$table = betaSubTable($serverId, 'u_player', '1000');
$sql = "select id,account_id,name,level from $table where serverid='$serverId' and name='$playerName' limit 1";
$query = @mysqli_query($conn,$sql);
$playerList = @mysqli_fetch_assoc($query);
if(isset($playerList['id'])){
    $accountId = $playerList['account_id'];
    $playerId = $playerList['id'];
    $playerName = $playerList['name'];
    $playerLevel = $playerList['level'];
    $appId = isset($arr_key[8]['app_id']) ? $arr_key[8]['app_id'] : 1038;
    exit(json_encode(array(
        'err_code'=>0,
        'usr_id'=>57,
        'usr_name'=>$playerName,
        'usr_rank'=>$playerLevel,
        'player_id'=>$playerId,
        'app_id'=>$appId,
    )));
}
exit(json_encode(array('err_code'=>1, 'desc'=>'没有该用户')));