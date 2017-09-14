<?php
/* *
 * 功能：支付宝服务器异步通知页面
 * 版本：3.5
 * 日期：2016-06-25
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。


 *************************页面功能说明*************************
 * 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
 * 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
 * 该页面调试工具请使用写文本函数logResult，该函数已被默认关闭，见alipayweb_notify_class.php中的函数verifyNotify
 * 如果没有收到该页面返回的 success 信息，支付宝会在24小时内按一定的时间策略重发通知
 */
include_once 'config.php';
require_once("alipay.config.php");
require_once("lib/alipay_notify.class.php");

$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","alipayweb_callback_all_"," post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
//$str ='a:22:{s:8:"discount";s:4:"0.00";s:12:"payment_type";s:1:"1";s:7:"subject";s:12:"官网充值";s:8:"trade_no";s:28:"2017020821001004510272621247";s:11:"buyer_email";s:11:"13459405424";s:10:"gmt_create";s:19:"2017-02-08 19:46:17";s:11:"notify_type";s:17:"trade_status_sync";s:8:"quantity";s:1:"1";s:12:"out_trade_no";s:30:"8_8002_141394_2017020853991015";s:9:"seller_id";s:16:"2088801186307426";s:11:"notify_time";s:19:"2017-02-08 19:52:20";s:12:"trade_status";s:13:"TRADE_SUCCESS";s:19:"is_total_fee_adjust";s:1:"N";s:9:"total_fee";s:4:"6.00";s:11:"gmt_payment";s:19:"2017-02-08 19:46:27";s:12:"seller_email";s:14:"linhq@u591.com";s:5:"price";s:4:"6.00";s:8:"buyer_id";s:16:"2088802080560517";s:9:"notify_id";s:34:"d064dfe5cd8b801f8a3dc931f86d5b1jxq";s:10:"use_coupon";s:1:"N";s:9:"sign_type";s:3:"RSA";s:4:"sign";s:172:"kVxDn4LRpFVopKZvzpfp4nVHOQqR9tzBa7vnNFWENBF21CSMs7KPEkvSoBYtyRb79uOr174j92nztRIL9rNcb47hdi3qXmP1DSOA2gsALBLzL88l2J9f2b6h9bDko+BDIrbF3VdHgw/hfsx7fszU5pvBIZbuZU4hAo3O8ypiX9w=";}';
//$_POST = unserialize($str);
//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyNotify();
if($verify_result) {//验证成功
	$out_trade_no = $_POST['out_trade_no'];
	//支付宝交易号
	$trade_no = $_POST['trade_no'];
	//交易状态
	$trade_status = $_POST['trade_status'];

    if($_POST['trade_status'] == 'TRADE_FINISHED') {

    } else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
        $outTradeNoArr = explode('_', $out_trade_no);
        $game_id = $outTradeNoArr[0];
        $server_id = $outTradeNoArr[1];
        $account_id = $outTradeNoArr[2];
        $money = intval($_POST['total_fee']);
        global $accountServer;
        $accountConn = $accountServer[$game_id];
        $conn = SetConn($accountConn);
        $sql_account = "select NAME,dwFenBaoID,clienttype from account where id = '$account_id' limit 1";
        $query_account = mysqli_query($conn,$sql_account);
        $result_account = mysqli_fetch_assoc($query_account);
        if(!$result_account['NAME']){
            write_log(ROOT_PATH."log","alipayweb_callback_error_", "account is not exist! post=$post,get=$get,".date("Y-m-d H:i:s")."\r\n");
            exit("fail");//账号不存在
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
            write_log(ROOT_PATH."log","alipayweb_callback_error_", "order is exist! post=$post,get=$get,".date("Y-m-d H:i:s")."\r\n");
            exit("success");//订单已存在
        }
        $Add_Time=date('Y-m-d H:i:s');
        $sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
        $sql=$sql." VALUES (128,$account_id,'$PayName','$server_id','$money','$out_trade_no','$dwFenBaoID','$Add_Time','1','$game_id','$clienttype',1)";
        if (mysqli_query($conn,$sql) == False){
            write_log(ROOT_PATH."log","alipayweb_callback_error_", $sql." ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
            exit("failure");
        }
        WriteCard_money(1,$server_id, $money,$account_id, $out_trade_no);
        //统计数据
        global $tongjiServer;
        $tjAppId = $tongjiServer[$game_id];
        sendTongjiData($game_id,$account_id,$server_id,$dwFenBaoID,0,$money,$out_trade_no,1,$tjAppId);
    }
	echo "success";
    exit();
} else {
    write_log(ROOT_PATH."log","alipayweb_callback_error_", "sign error! result=$verify_result, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    echo "fail";
    exit();
}
?>