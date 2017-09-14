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

$str = 'a:3:{s:9:"transdata";s:704:"6Xc/Geq7GEcOS1AsVOzuamWjTniUsbWGN6eOAGHzLL+OlANxbctAvuqt0NS7cHdwRR5rVL19xXpZk0uxnMVvBLoYzJcRo3qOx76kDa6m/oh31ssAWxoGfq6wp58JZi/BN2DzuNfZAixTF2V1EJA8PSaCdFPReB40jR+dvpIHfuIuS7JtgXCamE8sQo8mfLGWwW9HKBDcOfG27L+qjNVSWsbtMXnv39QzXdUTzWklU+VjkFoNGY3mjuuHvhYSc7BD/DFMB8I7nCjp/JXGrHmTBv0fiVXOqnsoac7uKH93g2CH3bNshWzxhUY1FG1Motxher2dNC60pL66cHZwwUo5+30sxzDoZlHPTHuTHfGzHc8+pvugtMXKG7gTXPFRea8Ga6QWLeN8WCnpcH0MLqaMT1E8SkPGZQgGBklGDhwjfcNK3fImG3SVusR1lbVZeHxjG0qRKT4Ghx9PSoEBVqO/e3NhkL9RSDlTYDkzJqqn4CmCVdAZJA/EUCoZ1zZ4kPdZ2BbS7yY5xg+7BiTZ6VrXuWui0TA0ka0rASBIqQEfFbQRYn2gQP93qQhjnAYZ5uEqf5sFTGKfDB+zja6eF/A6/wfISSI+FcSpaL3cPATCPUO5zpokC5eJzuPOx2rnTd/fyCcElFi6cD4EbyInmAyOPiTSYp08IV/feCPV3iBk0IgTYB+qdT6O6wjPYkP0tQb9";s:4:"sign";s:16:"4116f310546c82d3";s:10:"extendinfo";s:81:"{"appid":"59d76eec3bde4e449bad1e237e17bca3","appname":"口袋妖怪VS-绿色服"}";}';
$_POST = unserialize($str);
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
        $payResult = $this->noxSDKServer->getNotifyResult($transdata, $sign);
        require_once 'config.php';
        if (strval($payResult->getErrNum()) === NoxConstant::SUCCESS) {// 正确接收到数据
            // TODO 根据支付状态进行相应处理
           //$payResultArr = objectToArray($payResult, true);
            $uid = $payResult->getUid();
            $exten = $payResult->getGoodsOrderId();
            $money = $payResult->getOrderMoney();
            $orderId =  $payResult->getOrderId();
            write_log(ROOT_PATH."log","yeshen_callback_result_", "uid=$uid,orderId=$orderId,exten=$exten, ".date("Y-m-d H:i:s")."\r\n");

            print_r($payResult);
            exit();

            return NoxConstant::MSG_SUCCESS;
        } else {// 收到异常信息
        	
            // TODO 查看errorNum和ErrorMsg进行相应处理
            print($payResult->getErrNum());
            print($payResult->getErrMsg());
            return NoxConstant::MSG_FAILURE;
        }
    }
}
?>