<?php
/**
 * 微信支付异步通知结果
 */
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);
include_once 'config.php';
require_once "./lib/WxPay.Api.php";
require_once './lib/WxPay.Notify.php';
require_once 'log.php';
//初始化日志
$logHandler= new CLogFileHandler("./logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);
//{"appid":"wx345d962ebbaeb33c","attach":"60\u94bb\u77f3","bank_type":"CCB_DEBIT","cash_fee":"1","fee_type":"CNY","is_subscribe":"N","mch_id":"1415707102","nonce_str":"RU5HGHQKxExheKI0","openid":"oaUFuvz52oVcXsgG55k_X5L4lNTk","out_trade_no":"141570710220161127230411","result_code":"SUCCESS","return_code":"SUCCESS","return_msg":"OK","sign":"055BC830E2383A5B6C0815E84B886632","time_end":"20161127230515","total_fee":"1","trade_state":"SUCCESS","trade_type":"APP","transaction_id":"4001742001201611271032553971"}
class PayNotifyCallBack extends WxPayNotify
{
	//查询订单
	public function Queryorder($transaction_id)
	{
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		Log::DEBUG("query:" . json_encode($result));
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
				Log::DEBUG("account is not exist!:" . mysqli_error($conn).$game_id);
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
				Log::DEBUG("order is exist!:" . json_encode($result));
				return false;
			}
			$Add_Time=date('Y-m-d H:i:s');
			$sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
			$sql=$sql." VALUES (129,$account_id,'$PayName','$server_id','$money','$out_trade_no','$dwFenBaoID','$Add_Time','1','$game_id','$clienttype',1)";
			if (mysqli_query($conn,$sql) == False){
				Log::DEBUG("mysql error!:" . $sql. mysqli_error($conn));
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
	public function NotifyProcess($data, &$msg)
	{
		Log::DEBUG("call back:" . json_encode($data));
		$notfiyOutput = array();
		
		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			return false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
			return false;
		}
		//
		return true;
	}
}
Log::DEBUG("begin notify");
$notify = new PayNotifyCallBack();
$result = $notify->Handle(false);