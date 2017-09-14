<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/2/21
 * Time: 上午11:16
 */
include_once 'config.php';
include_once 'ucGameServer/service/BaseSDKService.php';
//include_once 'ucGameServer/util/ConfigHelper.php';
$post = serialize($_POST);
$get = serialize($_GET);
$gameId = $_REQUEST['game_id'];
$serverId = $_REQUEST['server_id'];
$accountId = $_REQUEST['account_id'];
$amount = $_REQUEST['amount'];
$callbackInfo = $_REQUEST['callbackInfo'];

write_log(ROOT_PATH."log","wdj_orderQuery_info_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

if(!$serverId || !$gameId || !$accountId || !$amount || !$callbackInfo){
    echo json_encode(array('status'=>'1','msg'=>'参数错误.'));
    exit();
}
$accountConn = $accountServer[$gameId];
$conn = SetConn($accountConn);
if($conn == false){
    echo json_encode(array('status'=>'1','msg'=>'数据库异常.'));
    exit();
}

$sql = "select channel_account from account where id='$accountId' limit 1";
$query = @mysqli_query($conn, $sql);
$result = @mysqli_fetch_assoc($query);
if(!isset($result['channel_account'])){
    echo json_encode(array('status'=>'1','msg'=>'账号不存在.'));
    exit();
}

$appAccountId = mb_substr($result['channel_account'], 0, stripos($result['channel_account'],'@'));

$cpOrderId = $gameId.'_'.$serverId.'_'.$accountId.'_'.date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);

$param = array(
    'callbackInfo'=>$callbackInfo,
    'amount'=>$amount,
    'notifyUrl'=>'http://gunweb.u591.com:83/interface/ali_wdj/callback.php',
    'cpOrderId'=>$cpOrderId,
    'accountId'=>$appAccountId,
);

$baseInfo = BaseSDKService::getSignData($param);
$appkey = ConfigHelper::getStrVal("sdkserver.game.apikey");

write_log(ROOT_PATH."log","wdj_orderQuery_info_","info=$baseInfo,key=$appkey, ".date("Y-m-d H:i:s")."\r\n");
$sign = md5($baseInfo.$appkey);
$param['signType'] = 'MD5';
$param['sign'] = $sign;

echo json_encode(array('status'=>0,'msg'=>'success','data'=>array('orderInfo'=>$param)));
exit();






