<?php
include_once 'init.php';

$post = serialize($_POST);
$get = serialize($_GET);
$file_in = file_get_contents("php://input");
$ip = getIP_front();
write_log(ROOT_PATH."log","db_sql_log_"," post=$post,get=$get,request=$request,$HTTP_RAW_POST_DATA,file_in=$file_in,ip=$ip ".date("Y-m-d H:i:s")."\r\n");

$sql = $_REQUEST['sql'];

if($sql){
    SetConn(86);
    if(mysql_query($sql)){
        exit("0 0");
    }else{
        write_log(ROOT_PATH."log","db_sql_log_", mysql_error()." post=$post,get=$get,request=$request,$HTTP_RAW_POST_DATA,file_in=$file_in,ip=$ip ".date("Y-m-d H:i:s")."\r\n");
        exit("4 0");
    }

}else{
    exit("2 0");
}





?>
