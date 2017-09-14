<?php
include_once 'init.php';

$username = trim($_REQUEST['username']);
$password = trim($_REQUEST['password']);
$SuperPwd = trim($_REQUEST['SuperPwd']);
$email = trim($_REQUEST['email']);
$id = trim($_REQUEST['id']);

$str = "id = $id,username =$username,password = $password,SuperPwd = $SuperPwd,email = $email  ";
write_log(ROOT_PATH.'log','up_account_info_',$str.date('Y-m-d H:i:s')."\r\n");

$username=strtolower($username);//帐号转为小写

if($username==''){
    echo '100';
    write_log(ROOT_PATH.'log','up_account_test_err','100,'.$str.date('Y-m-d H:i:s')."\r\n");
    exit;
}
if($id==''){
    echo '115';
    write_log(ROOT_PATH.'log','up_account_test_err','115,'.$str.date('Y-m-d H:i:s')."\r\n");
    exit;
}
//用户名前两位包含yk,hn提醒已被注册，作为内部使用
if(substr(trim(strtolower($username)),0,2)=='yk' || substr(trim(strtolower($username)),0,2)=='hn'){
    echo '111';
    write_log(ROOT_PATH.'log','up_account_test_err','111,'.$str.date('Y-m-d H:i:s')."\r\n");
    exit;
}
if($password==''){
    echo '101';
    write_log(ROOT_PATH.'log','up_account_test_err','101,'.$str.date('Y-m-d H:i:s')."\r\n");
    exit;
}
if($SuperPwd==''){
    echo '102';
    write_log(ROOT_PATH.'log','up_account_test_err','102,'.$str.date('Y-m-d H:i:s')."\r\n");
    exit;
}
if(!ereg("^[0-9a-zA-Z\]*$",$username)){
    echo('103');
    write_log(ROOT_PATH.'log','up_account_test_err','103,'.$str.date('Y-m-d H:i:s')."\r\n");
    exit;
}
if(strlen($username) > 13){
    echo('104');
    write_log(ROOT_PATH.'log','up_account_test_err','104,'.$str.date('Y-m-d H:i:s')."\r\n");
    exit;
}
if(!ereg("^[0-9a-zA-Z\]*$",$password)){
    echo('105');
    write_log(ROOT_PATH.'log','up_account_test_err','105,'.$str.date('Y-m-d H:i:s')."\r\n");
    exit;
}

if(strlen($password) < 6){
    echo('105');
    write_log(ROOT_PATH.'log','up_account_test_err','105,'.$str.date('Y-m-d H:i:s')."\r\n");
    exit;
}
if(strlen($password) > 10){
    echo('106');
    write_log(ROOT_PATH.'log','up_account_test_err','106,'.$str.date('Y-m-d H:i:s')."\r\n");
    exit;
}
//if($password == $SuperPwd) {
//    echo('107');
//    write_log(ROOT_PATH.'log','up_account_test_err','107,'.$str.date('Y-m-d H:i:s')."\r\n");
//    exit;
//}
if(!ereg("^[0-9a-zA-Z\]*$",$SuperPwd)){
    echo('107');
    write_log(ROOT_PATH.'log','up_account_test_err','107,'.$str.date('Y-m-d H:i:s')."\r\n");
    exit;
}
if($email==''){
    echo '108';
    write_log(ROOT_PATH.'log','up_account_test_err','108,'.$str.date('Y-m-d H:i:s')."\r\n");
    exit;
}


$SuperPwd=md5($SuperPwd.$mdString);
$game_pwd=md5($password.$mdString);//密码+加密串

SetConn(81);

$sql_c="select guest from account where id='$id'";
$query_c=mysql_query($sql_c);
if(!@mysql_result($query_c,0)>0)
{
    echo '120';     //非游客身份
    write_log(ROOT_PATH.'log','up_account_test_err','120,'.$str.date('Y-m-d H:i:s')."\r\n");
    exit;
}

$sql="select count(0) from account where NAME='$username'";
$query=mysql_query($sql);
$RowCount=mysql_result($query,0);
if ($RowCount == 0){
    //写入game表
    $sql="update account set NAME='$username',password='$game_pwd',superpasswd='$SuperPwd',email='$email',guest='0' where id='$id'";
    //echo $sql;
    //写入注册失败日志
    if (mysql_query($sql) == False){
        $str="id='$id',username=".$username.", game_pwd=".$game_pwd.",  ".date("Y-m-d H:i:s")."\r\n";
        write_log(ROOT_PATH.'log',"err_up_account",$str);
        echo '116';
        write(ROOT_PATH.'log','up_account_test_err','116,'.$str.date('Y-m-d H:i:s')."\r\n");
        exit;
    }else{
        echo "0";exit;
    }
}else{
    echo('111');//帐号已存在
    write_log(ROOT_PATH.'log','up_account_test_err','111,'.$str.date('Y-m-d H:i:s')."\r\n");
    exit;
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
