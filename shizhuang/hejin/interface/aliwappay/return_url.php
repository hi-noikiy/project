<?php
/* *
 * 功能：支付宝页面跳转同步通知页面
 * 版本：2.0
 * 修改日期：2016-11-01
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。

 *************************页面功能说明*************************
 * 该页面可在本机电脑测试
 * 可放入HTML等美化页面的代码、商户业务逻辑程序代码
 */
require_once("config.php");
require_once 'service/AlipayTradeService.php';

$arr=$_GET;
$alipaySevice = new AlipayTradeService($config); 
$result = $alipaySevice->check($arr);
if($result) {//验证成功
	$out_trade_no = htmlspecialchars($_GET['out_trade_no']);

	$trade_no = htmlspecialchars($_GET['trade_no']);
	$conn = SetConn(88);
	//判断订单id情况
	$sql = "select id,rpCode from web_pay_log where OrderID ='$out_trade_no' limit 1";
	$query = @mysqli_query($conn,$sql);
	$result_count = @mysqli_fetch_assoc($query);
	if($result_count['id']){
		echo "支付成功<br />外部订单号：".$out_trade_no;
	}else{
		echo "支付失败<br />外部订单号：".$out_trade_no;
	}
}
else {
    echo "验证失败";
}
?>
<title>口袋妖怪vs充值结果</title>
	</head>
    <body>
    </body>
</html>