<?php
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
$file_in = file_get_contents("php://input");
$ip = getIP_front();
write_log(ROOT_PATH."log","gfan_callback_all_log_"," post=$post,get=$get,file_in=$file_in,ip=$ip ".date("Y-m-d H:i:s")."\r\n");

$time=$_REQUEST['time'];
$sign=$_REQUEST['sign'];
//创建解析器
$parser = xml_parser_create();
//解析到数组
xml_parse_into_struct($parser, $file_in, $values, $index);
//释放解析器
xml_parser_free($parser);
$order_id = $values[1]['value'];
$appkey = $values[2]['value'];
$cost = $values[3]['value'];
$create_time = $values[4]['value'];

$order_arr = explode("_", $order_id);
$account_id = $order_arr[2];
$game_id = $order_arr[0];
$server_id = $order_arr[1];

if(!$game_id || !$server_id || !$account_id){
	write_log(ROOT_PATH."log","gfan_callback_error_", "orderid format error. orderId=$order_id, ".date("Y-m-d H:i:s")."\r\n");
	echo_result();
}
global $key_arr;
$uid = $key_arr[$game_id]['uid'];
$encodeString = md5($uid.$time);
if($encodeString == $sign){
    //获取账号信息
    global $accountServer;
	$accountConn = $accountServer[$game_id];
	$conn = SetConn($accountConn);
    $sql_account = " select NAME,dwFenBaoID,clienttype from account where id ='$account_id' limit 1;";
    $query_account = mysqli_query($conn, $sql_account);
    $result_account = @mysqli_fetch_assoc($query_account);
    if(!$result_account['NAME']){
        write_log(ROOT_PATH."log","gfan_callback_error_", "account not exist.".date("Y-m-d H:i:s")."\r\n");
        echo_result();//账号不存在
    }
    $PayName = $result_account['NAME'];
    $dwFenBaoID = $result_account['dwFenBaoID'];
    $clienttype = $result_account['clienttype'];

    if(!empty($order_id) && !empty($appkey) && !empty($cost) && !empty($create_time)){
    	$conn = SetConn(88);
    	//判断订单id情况
    	$sql = " select id,rpCode from web_pay_log where OrderID = '$order_id' limit 1;";
    	$query = mysqli_query($conn, $sql);
    	$result_count = @mysqli_fetch_assoc($query);
    	if($result_count['id']){
    		write_log(ROOT_PATH."log","gfan_callback_error_", "order is exist. orderid=$order_id, ".date("Y-m-d H:i:s")."\r\n");
    		echo_result("success");//订单已存在
    	}
    	$PayMoney = intval($cost/10);
    	$Add_Time=date('Y-m-d H:i:s');
    	$sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
    	$sql=$sql." VALUES (27,$account_id,'$PayName','$server_id','$PayMoney','$order_id','$dwFenBaoID','$Add_Time','1','$game_id','$clienttype','1')";
    	if (mysqli_query($conn,$sql) == False){
    		write_log(ROOT_PATH."log","gfan_callback_error_", "sql error. sql=$sql, ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
    		echo_result();
    	}
    	//处理你的业务
    	WriteCard_money(1,$server_id, $PayMoney,$account_id, $order_id);
    	//统计数据
        global $tongjiServer;
    	$tjAppId = $tongjiServer[$game_id];
        sendTongjiData($game_id,$account_id,$server_id,$dwFenBaoID,0,$PayMoney,$order_id,1,$tjAppId);
    	echo_result("success");
    }
    echo_result();
}else{
    write_log(ROOT_PATH."log","gfan_callback_error_", "sign error, post=$post, get=$get,file_in=$file_in, ".date("Y-m-d H:i:s")."\r\n");
    echo_result();
}
function echo_result($result="fail"){
    if($result=="success"){
        echo $result='<response><ErrorCode>1</ErrorCode><ErrorDesc>Success</ErrorDesc></response>';
        exit;
    }else{
        echo "<response><ErrorCode>0</ErrorCode><ErrorDesc>Success</ErrorDesc></response>";
        exit;
    }
}
?>