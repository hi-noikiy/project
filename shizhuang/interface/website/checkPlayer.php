<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/1/17
 * Time: 上午11:53
 */
include_once 'config.php';
$playerName = $_REQUEST['player_name'];
$gameId = $_REQUEST['game_id'];
$serverId = $_REQUEST['server_id'];
$sign = $_REQUEST['sign'];
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","website_checkPlayer_log_","post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");

if(!$playerName)
    exit(json_encode(array('status'=>1,'msg'=>'角色名不能为空.')));
if(!$serverId)
    exit(json_encode(array('status'=>1,'msg'=>'服务器不能为空.')));
$appKey = $key_arr['appKey'];
$array['player_name'] = $playerName;
$array['server_id'] = $serverId;
$array['game_id'] = $gameId;
$mySign = httpBuidQuery($array, $appKey);
if($mySign != $sign)
    exit(json_encode(array('status'=>1, 'msg'=>'验证失败.')));

$conn = SetConn($serverId);

$table = betaSubTable($serverId, 'u_player', '1000');
$sql = "select id,account_id from $table where serverid='$serverId' and name='$playerName' limit 1";
$query = @mysqli_query($conn,$sql);
$playerList = @mysqli_fetch_array($query);
if(isset($playerList['id']))
    exit(json_encode(array('status'=>0, 'msg'=>'success', 'data'=>array('accountId'=>$playerList['account_id']))));
exit(json_encode(array('status'=>1, 'msg'=>'角色不存在')));