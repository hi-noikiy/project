<?php
include_once 'init.php';

$post = serialize($_POST);
$get = serialize($_GET);
$file_in = file_get_contents("php://input");
write_log(ROOT_PATH."log","new_user_up_account_log_"," post=$post,get=$get,$HTTP_RAW_POST_DATA,file_in=$file_in, ".date("Y-m-d H:i:s")."\r\n");

$username = trim($_REQUEST['username']);
$password = trim($_REQUEST['password']);
$old_password = trim($_REQUEST['old_password']);

if($username==''){
    echo '100';
    exit;
}

if($password==''){
    echo '101';
    exit;
}
if($old_password==''){
    echo '102';
    exit;
}

if(!ereg("^[0-9a-zA-Z\]*$",$password)){
    echo('105');
    exit;
}

if(strlen($password) < 6){
    echo('106');
    exit;
}
if(strlen($password) > 10){
    echo('106');
    exit;
}

//if(!ereg("^[0-9a-zA-Z\]*$",$SuperPwd)){
//    echo('107');
//    exit;
//}

$old_password=md5($old_password.$mdString);
$game_pwd=md5($password.$mdString);//密码+加密串

SetConn(81);

$sql="select id,password from account where NAME='$username'";
$query=mysql_query($sql);
$Row=mysql_fetch_assoc($query);

if (!$Row['id']){
    //账号不存在
    exit('110');
}else{
    if ($Row['password'] != $old_password)
    {
        exit('111');
    }else{
         SetConn(81);
            $update_sql = " update account set password='$game_pwd' where NAME='$username'";
            mysql_query($update_sql);
            exit('0');
    }
    exit;
}

exit('999');



?>
