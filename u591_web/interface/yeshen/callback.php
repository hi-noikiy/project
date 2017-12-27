<?php

/**
 * 接收夜神SDK服务端交易结果通知示例代码
 *
 * @author panwanghai
 *
 */
require_once 'config.php';
require_once 'noxsdk/client/NoxSDKServer.php';
require_once 'noxsdk/utils/NoxConstant.php';
$post = serialize($_POST);
$get = serialize($_GET);
//$str = 'a:3:{s:9:"transdata";s:704:"6Xc/Geq7GEcOS1AsVOzuamWjTniUsbWGN6eOAGHzLL+OlANxbctAvuqt0NS7cHdwRR5rVL19xXpZk0uxnMVvBLoYzJcRo3qOx76kDa6m/ogX4oq2LnRTwIKGG7Il4Zi/HFNykXR1RL4m5J2/iF3U8NFI6KZ5oso9NYgS7rSmrSUmLSKtb7Y8ItWKVkcYydmqMkc5Mj0v/NZsxA/MN+ZhMXo+tzC2sc9dqvVaIRUlt1XMvydmyajNArQx+ATky34725TKjcQsGGuNdypHrJ9fld2jMQWeKJMUBI0jPYsZs7S8FkzOxWWNy9d30DSTqIkcaJXNwCBgbYVmzBrfUcX/o7yYnCTg2L9GvSpFeb0JZIwj3zw1xwQ6O22rOwI+t2jsqtATAw9F6l10p2bwSNI5FkuGA5bVsG8A1qPtFRyuraknAKhZVqEtCwfmnUnrXUhymGHRnZXbM4Ea1JJ1uVloasCwrTsa58oLZTKYRXbPpGpSkVzxdRPquZkwyCNXFpY7b0Ph99E4GEtLLSM9IJdjXKwNoaoDq9Pe+SvuCU1fOouHwc7Y36757AtbCZSXoJeIfofaDq6KnuRaHzQk0FsYzsW/oRFXCthMFqEAtSmhGsoBCnxXfNooZBbdJ3tZ7guI5aOrCrDbuAapaaCWaH1h/zRmKZe0a7IpCewNAuok1a6P2Ym/LtABoeZgwMS4w4dl";s:4:"sign";s:16:"6752668999d30a8d";s:10:"extendinfo";s:81:"{"appid":"59d76eec3bde4e449bad1e237e17bca3","appname":"口袋妖怪VS-绿色服"}";}';
//$_POST = unserialize($str);
write_log(ROOT_PATH."log","yeshen_callback_info_all_","post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
$appId = '59d76eec3bde4e449bad1e237e17bca3';
$appKey = '0883420f249b4972bfbb771fcec3790c';
$model = new TestNotifyPayResult($appId, $appKey);

$model->notifyFromPOST();

class TestNotifyPayResult {
    private $noxSDKServer;
    /**
     *
     * appId和appKey是和夜神SDK服务端交互的认证凭证，请妥善保管，并在任何交互前必须先初始化这2个参数，建议进行全局初始化一次即可
     *
     */
    public function __construct($appId, $appKey) {
        $this->noxSDKServer = new NoxSDKServer($appId, $appKey);
    }


    public function notifyFromPOST()
    {
        $transdata = $_POST[NoxConstant::PARAM_TRANS_DATA];
        $sign = $_POST[NoxConstant::PARAM_SIGN];
        return $this->notify($transdata, $sign);
    }

    /**
     *
     * @param transdata
     *            从$_POST中获取transdata
     * @param sign
     *            从$_POST中获取sign
     * @return string 接收成功则返回SUCCESS ，接收失败则返回FAILURE(成功或失败均需返回固定字符串，夜神服务端接收到返回 SUCCESS
     *         则不在重发，其他则重发一定次数为止)
     */
    public function notify($transdata, $sign) {
        if(!$transdata || !$sign)
            return NoxConstant::MSG_FAILURE;
        $payResult = $this->noxSDKServer->getNotifyResult($transdata, $sign);
        require_once 'config.php';
        if (strval($payResult->getErrNum()) === NoxConstant::SUCCESS) {
            // 正确接收到数据
            // TODO 根据支付状态进行相应处理
            //$payResultArr = objectToArray($payResult, true);
            $uid = $payResult->getUid();
            $exten = $payResult->getGoodsOrderId();
            $money = $payResult->getOrderMoney();
            $orderId =  $payResult->getOrderId();
            write_log(ROOT_PATH."log","yeshen_callback_result_", "uid=$uid,orderId=$orderId,exten=$exten, ".date("Y-m-d H:i:s")."\r\n");
            $extenArr = explode("_",$exten);
            $gameId = $extenArr[0];
            $serverId = $extenArr[1];
            $accountId = $extenArr[2];
            $isgoods = intval($extenArr[4]);
            $conn = SetConn(88);
            $sql = "select rpCode from web_pay_log where OrderID = '$orderId' limit 1;";
            $query = @mysqli_query($conn, $sql);
            $result = @mysqli_fetch_array($query);
            
            if($result['rpCode']==1 || $result['rpCode']==10){
                write_log(ROOT_PATH."log","yeshen_callback_error_", "order is exist. orderId=$orderId,  ".date("Y-m-d H:i:s")."\r\n");
            	return NoxConstant::MSG_SUCCESS;
            }
            $PayMoney = intval($money/100);
            if(!$result){
            	global $accountServer;
            	$accountConn = $accountServer[$gameId];	
            	$conn = SetConn($accountConn);
            	$sql_account = "select NAME,dwFenBaoID,clienttype from account where id = '$accountId' limit 1;";
            	$query_account = @mysqli_query($conn, $sql_account);
            	$result_account = @mysqli_fetch_assoc($query_account);
            	if(!$result_account['NAME']){
            		write_log(ROOT_PATH."log","yeshen_callback_error_", "account is not exist.  ".date("Y-m-d H:i:s")."\r\n");
            		return NoxConstant::MSG_FAILURE;
            	}else{
            		$PayName = $result_account['NAME'];
            		$dwFenBaoID = $result_account['dwFenBaoID'];
            		$clienttype = $result_account['clienttype'];
            	} 	
            	$conn = SetConn(88);
            	$Add_Time=date('Y-m-d H:i:s');
            	$sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype, rpCode)";
            	$sql=$sql." VALUES (130, $accountId,'$PayName','$serverId','$PayMoney','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype', '1')";
            
            	if (mysqli_query($conn,$sql) == False){
            		write_log(ROOT_PATH."log","yeshen_callback_error_","sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
            		return NoxConstant::MSG_FAILURE;
            	}
            	
            	WriteCard_money(1,$serverId, $PayMoney,$accountId, $orderId,8,0,0,$isgoods);
                //统计数据
                global $tongjiServer;
                $tjAppId = $tongjiServer[$gameId];
                sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$PayMoney,$orderId,1,$tjAppId);
            }   
            return NoxConstant::MSG_SUCCESS;
        } else {// 收到异常信息
            // TODO 查看errorNum和ErrorMsg进行相应处理
            return NoxConstant::MSG_FAILURE;
        }
    }
}
?>