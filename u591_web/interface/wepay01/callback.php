<?php
/**
 * @created by PhpStorm.
 * @user: luoxue
 * @date: 2017/4/14 下午2:15
 * @desc: 微信支付回调
 * @param:
 * @return:
 */
include_once 'config.php';
include_once './lib/appPayRequest.php';
include_once './lib/WxPay.Notify.php';
//$GLOBALS['HTTP_RAW_POST_DATA'] = "<xml><appid><![CDATA[wxe9ab07793643ad98]]></appid><attach><![CDATA[9501]]></attach><bank_type><![CDATA[CEB_DEBIT]]></bank_type><cash_fee><![CDATA[100]]></cash_fee><fee_type><![CDATA[CNY]]></fee_type><is_subscribe><![CDATA[N]]></is_subscribe><mch_id><![CDATA[1460030502]]></mch_id><nonce_str><![CDATA[2i5odiwfdllddggdm2462qjno3fiod8g]]></nonce_str><openid><![CDATA[oNxQp1mpsdemvt9PLMiym15Mbzpc]]></openid><out_trade_no><![CDATA[8_9001_56104_2017041850501021]]></out_trade_no><result_code><![CDATA[SUCCESS]]></result_code><return_code><![CDATA[SUCCESS]]></return_code><sign><![CDATA[ED58E977DAE941A713E8FA596C604754]]></sign><time_end><![CDATA[20170418160811]]></time_end><total_fee>100</total_fee><trade_type><![CDATA[APP]]></trade_type><transaction_id><![CDATA[4007802001201704187457335355]]></transaction_id></xml>";
class PayNotifyCallBack extends WxPayNotify {
    //查询订单
    public function Queryorder($transaction_id, $app_id, $mch_id, $app_key) {
        $input = new WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $input->SetAppid($app_id);
        $input->SetMch_id($mch_id);

        $result = appPayRequest::orderQuery($input, $app_key);
        write_log(ROOT_PATH."log","wepay_callback_log_","query=".json_encode($result).", ".date("Y-m-d H:i:s")."\r\n");
        if(array_key_exists("return_code", $result) && array_key_exists("result_code", $result) && $result["return_code"] == "SUCCESS" && $result["result_code"] == "SUCCESS")
        {
            $out_trade_no = $result['out_trade_no'];
            $outTradeNoArr = explode('_', $out_trade_no);
            $game_id = $outTradeNoArr[0];
            $server_id = $outTradeNoArr[1];
            $account_id = $outTradeNoArr[2];

            $money = intval($result['total_fee']/100);
            global $accountServer;
            $accountConn = $accountServer[$game_id];
            $conn = SetConn($accountConn);
            $sql_account = "select NAME,dwFenBaoID,clienttype from account where id = '$account_id' limit 1";
            $query_account = @mysqli_query($conn,$sql_account);
            $result_account = @mysqli_fetch_assoc($query_account);
            if(!$result_account['NAME']){
                write_log(ROOT_PATH."log","wepay_callback_error_","account is not exist. accountId=$account_id, ".date("Y-m-d H:i:s")."\r\n");
                return false;
            }else{
                $PayName = $result_account['NAME'];
                $dwFenBaoID = $result_account['dwFenBaoID'];
                $clienttype = $result_account['clienttype'];
            }
            $conn = SetConn(88);
            //判断订单id情况
            $sql = "select id,rpCode from web_pay_log where OrderID ='$out_trade_no' limit 1";
            $query = @mysqli_query($conn,$sql);
            $result_count = @mysqli_fetch_assoc($query);
            if($result_count['id']){
                write_log(ROOT_PATH."log","wepay_callback_error_","order is exist=".json_encode($result).", ".date("Y-m-d H:i:s")."\r\n");
                return false;
            }
            $Add_Time=date('Y-m-d H:i:s');
            $sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
            $sql=$sql." VALUES (129,$account_id,'$PayName','$server_id','$money','$out_trade_no','$dwFenBaoID','$Add_Time','1','$game_id','$clienttype',1)";
            if (mysqli_query($conn,$sql) == False){
                write_log(ROOT_PATH."log","wepay_callback_error_","sql=$sql,mysql error:".mysqli_error($conn).", ".date("Y-m-d H:i:s")."\r\n");
                return false;
            }
            WriteCard_money(1,$server_id, $money,$account_id, $out_trade_no);
            //统计数据
            global $tongjiServer;
            $tjAppId = $tongjiServer[$game_id];
            sendTongjiData($game_id,$account_id,$server_id,$dwFenBaoID,0,$money,$out_trade_no,1,$tjAppId);
            return true;
        }
        return false;
    }
    //重写回调处理函数
    public function NotifyProcess($data, &$msg) {
        $key = $this->key;
        write_log(ROOT_PATH."log","wepay_callback_log_","callback=".json_encode($data).", ".date("Y-m-d H:i:s")."\r\n");
        if(!array_key_exists("transaction_id", $data)){
            $msg = "输入参数不正确";
            return false;
        }
        //查询订单，判断订单真实性
        if(!$this->Queryorder($data["transaction_id"], $data['appid'], $data['mch_id'], $key)){
            $msg = "订单查询失败";
            return false;
        }
        return true;
    }
}
global $fenbao_arr;
$notify = new PayNotifyCallBack();
$notify->SetKey($fenbao_arr);
$result = $notify->Handle(false);