<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/2/21
 * Time: 上午10:20
 */
include_once 'config.php';
include_once 'ucGameServer/service/SDKServerService.php';
include_once 'ucGameServer/model/SDKException.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","wdj_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
$sid = $_REQUEST['sid'];
$gameId = $_REQUEST['game_id'];
if(!$sid || !$gameId){
    exit('2 0');
}
try{
    $sidInfo = SDKServerService::verifySession($sid);
    $ucid = $sidInfo->accountId;
    $creator = $sidInfo->creator;
    if($creator == 'PP'){
        //pp助手
        $ucid .='@pp';
    } else if($creator == 'WDJ'){
        //豌豆荚 未接过
        $ucid .='@wdj';
    } else if($creator == 'JY') {
        //九游
        $ucid .='@uc';
    } else {
        //阿里 未接过
        $ucid .='@ali';
    }
    $accountConn = $accountServer[$gameId];
    $conn = SetConn($accountConn);
    $channel_account = mysqli_real_escape_string($conn,$ucid);
    $sql = "select id from account where channel_account='$channel_account' limit 1";
    if(false == $query = mysqli_query($conn,$sql)){
        write_log(ROOT_PATH."log","wdj_login_error_","$accountConn, sql=$sql, mysql error, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
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
    mysqli_query($conn, $sql_game);
    $insert_id = mysqli_insert_id($conn);
    if($insert_id){
        write_log(ROOT_PATH."log","new_account_wdj_log_","return=1 $insert_id,post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
        exit("1 $insert_id");
    }
} catch (SDKException $e){
    $msg = $e->getCode().' '.$e->getMessage();
    write_log(ROOT_PATH."log","wdj_result_log_","result=$msg ".date("Y-m-d H:i:s")."\r\n");
    exit('4 0');
}