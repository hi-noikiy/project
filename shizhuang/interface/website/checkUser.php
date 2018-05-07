<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/1/17
 * Time: 上午11:53
 */
include_once 'config.php';
$player_id = $_REQUEST['player_id']; //传的是userid
$serverid = $_REQUEST['serverid'];
$account_id = $_REQUEST['account_id'];
$sign = $_REQUEST['sign'];
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","wap_checkPlayer_log_","post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");

if(!$player_id || !$account_id)
    exit(json_encode(array('status'=>1,'msg'=>'信息不完整')));
$appKey = $key_arr['appKey'];
$array['player_id'] = $player_id;
$array['account_id'] = $account_id;
$array['serverid'] = $serverid;
$mySign = httpBuidQuery($array, $appKey);
if($mySign != $sign)
    exit(json_encode(array('status'=>1, 'msg'=>'验证失败.')));
$conn = SetConn($serverid);
$table = 'u_player';
$table = betaSubTable($account_id, $table, 200);
$sql = "select id,account_id,name from $table where id='$player_id' and account_id='$account_id' limit 1";
$query = @mysqli_query($conn,$sql);
$playerList = @mysqli_fetch_array($query);
if(isset($playerList['id']))
    exit(json_encode(array('status'=>0, 'msg'=>'success', 'data'=>array('accountId'=>$playerList['account_id'],'name'=>$playerList['name']))));
exit(json_encode(array('status'=>1, 'msg'=>'角色不存在')));