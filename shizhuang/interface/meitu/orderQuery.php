<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/2/21
 * Time: 上午11:16
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","meitu_orderQuery_info_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
$extendsInfo = $_REQUEST['cp_order_id']; //提取拓展信息
$extendsInfoArr = explode('_', $extendsInfo);
$gameId = $extendsInfoArr[0];
$serverId = $extendsInfoArr[1];
$playerId = $extendsInfoArr[2];
$accountId = $extendsInfoArr[3];
$type = $extendsInfoArr[4];
if(!$serverId || !$gameId || !$accountId || !$playerId || !$type){
    echo json_encode(array('status'=>'1','msg'=>'参数错误.'));
    exit();
}
$data = $_REQUEST;
$data['timestamp'] = date('Y-m-d H:i:s');
$data['app_id'] = $key_arr[$gameId][$type]['appId'];
$data['notify_url'] = 'http://fhweb.u776.com:86/interface/meitu/callback.php';
$signKey = $key_arr[$gameId][$type]['appSecret'];
$data['sign'] = hmacSha1Sign($data,$signKey);
echo json_encode(array('status'=>0,'msg'=>'success','data'=>$data),JSON_UNESCAPED_SLASHES);
exit();






