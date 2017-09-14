<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/2/21
 * Time: 上午10:51
 * 暂时无用
 */
include_once 'config.php';
include_once 'ucGameServer/service/SDKServerService.php';
include_once 'ucGameServer/model/SDKException.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","uc_gameData_info_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
$sid = $_REQUEST['sid'];
$gameData = $_REQUEST['game_data'];
try {
    $gameData = json_decode($gameData, true);
    $info = SDKServerService::gameData($sid, $gameData);
    exit('true');
} catch (SDKException $e){
    $msg = $e->getCode().' '.$e->getMessage();
    write_log(ROOT_PATH."log","uc_gameData_error_","result=$msg ".date("Y-m-d H:i:s")."\r\n");
    exit('false');
}
