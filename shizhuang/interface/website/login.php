<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 短信登陆   新的接口以后都采用json 返回
* ==============================================
* @date: 2016-7-14
* @author: luoxue
* @version:
*/
include_once 'config.php';
global $mdString;
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","website_login_log_","post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
$username = $_REQUEST['username'];
$gameId = intval($_REQUEST['game_id']);
$password = $_REQUEST['password'];
$sign = trim($_POST['sign']);

if(!$username)
	exit(json_encode(array('status'=>1,'msg'=>'账号不能为空.')));
if(!$password)
	exit(json_encode(array('status'=>1,'msg'=>'密码不能为空.')));
$appKey = $key_arr['appKey'];
$array['username'] = $username;
$array['password'] = $password;
$array['game_id'] = $gameId;
$mySign = httpBuidQuery($array, $appKey);
if($mySign != $sign)
	exit(json_encode(array('status'=>1, 'msg'=>'验证失败.')));

global $accountServer;
$accountConn = $accountServer[$gameId];
$conn = SetConn($accountConn);
$sql = "select  id, NAME, password, reg_date  from account where  NAME= '$username'";
$query = @mysqli_query($conn,$sql);
$result = @mysqli_fetch_assoc($query);

if(empty($result))
	exit(json_encode(array('status'=>1,'msg'=>'账号不存在.')));
$pass = $result['password'];
$myPass=md5($password.$mdString);
if($myPass != $pass)
	exit(json_encode(array('status'=>1,'msg'=>'密码不正确.')));

exit(json_encode(array('status'=>0, 'msg'=>'success','data'=>array('account_id'=>$result['id'], 'username'=>$result['NAME'], 'reg_date'=>$result['reg_date']))));
?>