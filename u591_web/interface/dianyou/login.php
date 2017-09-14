<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2016/12/19
 * Time: 上午10:02
 */
include 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);

$userId = $_REQUEST['userid'];
$userCertificate = $_REQUEST['userCertificate'];
$gameId = $_REQUEST['game_id'];

write_log(ROOT_PATH."log","dianyou_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
if(!$userId  || !$userCertificate || !$gameId){
    write_log(ROOT_PATH."log","dianyou_login_error_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('2 0');
}

$url="http://cpa.idianyou.cn/cpa_center/user/check.do?userid=".$userId.'&userCertificate='.$userCertificate;

$rs = file_get_contents($url);
write_log(ROOT_PATH."log","dianyou_login_result_","result=$rs,url=$url, ".date("Y-m-d H:i:s")."\r\n");
if($rs == 'true'){
    $accountConn = $accountServer[$gameId];
    $conn = SetConn($accountConn);
    $channel_account = mysqli_real_escape_string($conn,$userId.'@dianyou');
    $sql = "select id from account where channel_account='$channel_account' limit 1";
    if(false == $query = mysqli_query($conn,$sql)){
        write_log(ROOT_PATH."log","dianyou_login_error_","$accountConn, sql=$sql, mysql error, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
        exit('3 0');
    }
    $result = @mysqli_fetch_assoc($query);
    if($result){
        $insert_id = $result['id'];
        exit("0 $insert_id");
    }
    $insert_id = '';
    $password = random_common();
    $reg_time = date("ymdHi");
    $sql_game = "insert into account (NAME,password,reg_date, channel_account) VALUES ('$channel_account','$password','$reg_time', '$channel_account')";
    @mysqli_query($conn, $sql_game);
    $insert_id = @mysqli_insert_id($conn);
    if($insert_id){
        write_log(ROOT_PATH."log","new_account_dianyou_log_","dianyou new account login, post=$post,get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
        exit("1 $insert_id");
    }
} else {
    write_log(ROOT_PATH."log","dianyou_login_error_","sign error!, result=$rs, ".date("Y-m-d H:i:s")."\r\n");
    exit("4 0");
}
exit('999 0');