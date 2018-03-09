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
write_log(ROOT_PATH."log","uc_new_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
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
    $username = $ucid;
    $bindtable = getAccountTable($username,'token_bind');
    $bindwhere = 'token';
    $insertinfo = insertaccount($username,$bindtable,$bindwhere,$gameId);
    if($insertinfo['status'] == '1'){
    	write_log(ROOT_PATH."log","uc_new_login_error_",json_encode($insertinfo).",post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    	exit('3 0');
    }else{
    	$insert_id = $insertinfo['data'];
    	if($insertinfo['isNew'] == '1'){
    		exit("1 $insert_id");
    	}else{
    		exit("0 $insert_id");
    	}
    }
} catch (SDKException $e){
    $msg = $e->getCode().' '.$e->getMessage();
    write_log(ROOT_PATH."log","uc_new_result_log_","result=$msg ".date("Y-m-d H:i:s")."\r\n");
    exit('4 0');
}