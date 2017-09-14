<?php
include_once 'init.php';

$username =  $_REQUEST['username'];
$password =  $_REQUEST['password'];
$SuperPwd = $_REQUEST['SuperPwd'];
$email   =  $_REQUEST['email'];

$str = "username =$username,password = $password,SuperPwd = $SuperPwd,email = $email  ";
write_log(ROOT_PATH.'log','create_account_info_',$str.date('Y-m-d H:i:s')."\r\n");


if(!$username){
    exit('100');//账号不能为空
}
if(!$password){
    exit('101');//密码不能为空
}
//用户名前两位包含yk,hn提醒已被注册，作为内部使用
if(substr(trim(strtolower($username)),0,2)=='yk' || substr(trim(strtolower($username)),0,2)=='hn'){
    echo '111';
    exit;
}
if($SuperPwd==''){
    echo '102';
    exit;
}
if(!ereg("^[0-9a-zA-Z\]*$",$username)){
    echo('103');
    exit;
}
if(strlen($username) > 13){
    echo('104');
    exit;
}
if(!ereg("^[0-9a-zA-Z\]*$",$password)){
    echo('105');
    exit;
}

if(strlen($password) < 6){
    echo('105');
    exit;
}
if(strlen($password) > 10){
    echo('106');
    exit;
}
//if($password == $SuperPwd) {
//    echo('107');
//    exit;
//}
if(!ereg("^[0-9a-zA-Z\]*$",$SuperPwd)){
    echo('107');
    exit;
}
if($email==''){
    echo '108';
    exit;
}
if(!eregi("^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3}$",$email))
{
    exit("116");//邮箱格式有误;
}

$username=strtolower($username);//帐号转为小写
$password_my=md5($password.$mdString);
$SuperPwd=md5($SuperPwd.$mdString);
SetConn(81);
$account_name=mysql_escape_string($username);
$sql = " select id from account where name = '$account_name'";
$query=mysql_query($sql);
$result=mysql_fetch_assoc($query);
if($result['id']){
    exit('111');//帐号已存在
}

$reg_time=date("ymdHi");
$sql_game = "insert into account (NAME,password,superpasswd,reg_date) VALUES ('$username','$password_my','$SuperPwd','$reg_time')";
mysql_query($sql_game);
$insert_id = mysql_insert_id();
mysql_close();
if($insert_id){
    echo "0";exit;
}else{
    echo "109";exit;
}
exit('999');

function random($length, $numeric = 0) {
	PHP_VERSION < '4.2.0' ? mt_srand((double)microtime() * 1000000) : mt_srand();
	$seed = base_convert(md5(print_r($_SERVER, 1).microtime()), 16, $numeric ? 10 : 35);
	$seed = $numeric ? (str_replace('0', '', $seed).'012340567890') : ($seed.'zZ'.strtoupper($seed));
	$hash = '';
	$max = strlen($seed) - 1;
	for($i = 0; $i < $length; $i++) {
		$hash .= $seed[mt_rand(0, $max)];
	}
	return $hash;
}


?>
