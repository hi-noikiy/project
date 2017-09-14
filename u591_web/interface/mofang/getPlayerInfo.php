<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/1/13
 * Time: 下午6:27
 */
include_once 'config.php';

$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","mofang_playerinfo_log_", "post=$post, get=$get,"." ".date("Y-m-d H:i:s")."\r\n");
//$str = 'a:5:{s:3:"gid";s:6:"100059";s:3:"sid";s:4:"3001";s:3:"uid";s:8:"43003893";s:4:"time";s:10:"1484308604";s:4:"sign";s:32:"CCFDC37BF1BC9A437FE26EF919B30C4F";}';
//$_REQUEST = unserialize($str);
$gid = $_REQUEST['gid'];
$sid = $_REQUEST['sid'];
$uid = $_REQUEST['uid'];
$time = $_REQUEST['time'];
$sign = $_REQUEST['sign'];
$gameId = 8;
$serverId = $sid;
$type = 'android'; //对方iandroid ios，key一样
$playerKey = $key_arr[$gameId][$type]['playerkey'];
$md5Str ="gid=$gid&sid=$sid&uid=$uid&time=$time&key=$playerKey";

$mySign = strtoupper(md5($md5Str));
if($mySign != $sign){
    write_log(ROOT_PATH."log","mofang_playerinfo_error_","sign error,sign=$sign, mySign=$mySign,md5Str=$md5Str, ".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array('code'=>'99', 'msg'=>'sign error.')));
}

$accountConn = $accountServer[$gameId];
$conn = SetConn($accountConn);
$channel_account = mysqli_real_escape_string($conn,$uid.'@mofang');
$sql = "select id from account where channel_account='$channel_account' limit 1";

if(false == $query = mysqli_query($conn,$sql)){
    write_log(ROOT_PATH."log","mofang_playerinfo_error_","$accountConn, sql=$sql, mysql error, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
    exit('3 0');
}
$result = @mysqli_fetch_assoc($query);
if(!isset($result['id'])){
    write_log(ROOT_PATH."log","mofang_playerinfo_error_","account is not exist, ".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array('code'=>'1', 'msg'=>'account is not exist.')));
}
$accountId = intval($result['id']);
$conn = SetConn($serverId);
$info = array();
$table = betaSubTable($serverId, 'u_player', '1000');
$sql = "select id,name,level from $table where serverid='$serverId' and account_id='$accountId' limit 1";
$query = @mysqli_query($conn,$sql);
$playerList = @mysqli_fetch_array($query);

if(!isset($playerList['id'])){
    write_log(ROOT_PATH."log","mofang_playerinfo_error_","player is not exist, ".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array('code'=>'1', 'msg'=>'player is not exist.')));
}
if($playerList){
    $info['roleid'] = $playerList['id'];
    $info['rolename'] = $playerList['name'];
    $info['level'] = $playerList['level'];
}
$giftRechargeTable = betaSubTable($serverId, 'u_gift_recharge', 1000);
$sql3 = "select vip_level from $giftRechargeTable where account_id='$accountId' and server_id='$serverId'  limit 1";
$query3 = @mysqli_query($conn,$sql3);
$rs3 = @mysqli_fetch_assoc($query3);

$info['vip_level'] = intval($rs3['vip_level']);
//8_3001_1652167_android_0
$info['extra'] = base64_encode($gameId.'_'.$serverId.'_'.$accountId.'_'.$type.'_0');
exit(json_encode(array('code'=>'0', 'msg'=>'Success', 'data'=>array($info))));