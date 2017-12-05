<?php
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
$request = serialize($_REQUEST);

$str = "post=$post,get=$get,request=$request,";
write_log(ROOT_PATH."log","360_callback_log_",$str." ".date("Y-m-d H:i:s")."\r\n");

$app_key = $_REQUEST['app_key'];
$product_id = $_REQUEST['product_id'];
$amount = $_REQUEST['amount'];
$app_uid = $_REQUEST['app_uid'];
$app_ext1 = $_REQUEST['app_ext1'];
$app_ext2 = $_REQUEST['app_ext2'];
$user_id = $_REQUEST['user_id'];
$orderId = $_REQUEST['order_id'];
$gateway_flag = $_REQUEST['gateway_flag'];
$sign_type = $_REQUEST['sign_type'];
$app_order_id = $_REQUEST['app_order_id'];
$sign_return = $_REQUEST['sign_return'];
$sign = $_REQUEST['sign'];


$app_order_id_arr = explode("_", $app_order_id);
$game_id = $app_order_id_arr[0];
$server_id = $app_order_id_arr[1];
$account_id = $app_order_id_arr[2];
$type = $app_order_id_arr[3];

$app_key = $key_arr[$game_id][$type]['app_key'];
$app_secret = $key_arr[$game_id][$type]['app_secret'];

$sign_arr = array("app_key"=>$app_key,"product_id"=>$product_id,"amount"=>$amount,"app_uid"=>$app_uid,
                  "app_ext1"=>$app_ext1,"app_ext2"=>$app_ext2,"user_id"=>$user_id,"order_id"=>$orderId,"gateway_flag"=>$gateway_flag,
                   "sign_type"=>$sign_type,"app_order_id"=>$app_order_id);
$sign_my = str_secret($sign_arr,$app_secret);

if($sign_my==$sign){//验证成功
	$orderId = $app_order_id;
	$conn = SetConn(88);
	$sql = "select rpCode from web_pay_log where OrderID = '$orderId' limit 1;";
	$query = mysqli_query($conn, $sql);
	$result = @mysqli_fetch_array($query);
	if($result['rpCode']==1 || $result['rpCode']==10){
    	write_log(ROOT_PATH."log","360_callback_error_",$orderId."is pay success,  ".date("Y-m-d H:i:s")."\r\n");
    	exit($okjson);//订单已存在
    }
    $PayMoney = intval($amount/100);

    SetConn(81);
    $sql_account = " select  NAME,dwFenBaoID,clienttype  from account where id = '$account_id'";
    $query_account=mysql_query($sql_account);
    $result_account=@mysql_fetch_assoc($query_account);

    if(!$result_account['NAME']){
        write_log(ROOT_PATH."log","360_callback_error_log_","账号不存在 get=$get,account_id=".$account_id."\r\n");
        exit("fail");//账号不存在
    }else{
        $PayName = $result_account['NAME'];
        $dwFenBaoID = $result_account['dwFenBaoID'];
        $clienttype = $result_account['clienttype'];
    }

    SetConn(88);
    $sql = " select * from pay_log where OrderID = '$orderId' ";
    $query=mysql_query($sql);
    $result_count=@mysql_fetch_assoc($query);
    if($result_count['rpCode']==1){
        write_log(ROOT_PATH."log","360_callback_error_log_"," 订单已存在 order_id=$orderId,".date("Y-m-d H:i:s")."\r\n");
        exit($okjson);//订单已存在
    }

    SetConn(88);
    $Add_Time=date('Y-m-d H:i:s');
    $sql="insert into pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype)";
    $sql=$sql." VALUES (43,$account_id,'$PayName','$server_id','$PayMoney','$orderId','$dwFenBaoID','$Add_Time','1','$game_id','$clienttype')";
    if (mysql_query($sql) == False){
        $str="sql错误，".$sql."  ".date("Y-m-d H:i:s")."\r\n";
        write_log(ROOT_PATH."log","360_callback_error_log_",$str);
        exit;
    }
    if($gateway_flag=="success"){
        $rpCode =1;
        PayLog360($orderId,$rpCode,$account_id,$PayMoney);//更新充值记录
        updatePoints($account_id,$PayMoney,'f_dx',$orderId);//修改积分
        updateRankUp($account_id,'f_dx');//修改等级
        // WriteCard(1,$server_id,8,$pay_id,$orderId);//充值成功写入游戏库
        WriteCard_money(1,$server_id, $PayMoney,$account_id, $orderId);
        WritePayMsg(0,$server_id,$account_id,$orderId,$PayMoney,$game_id);
    }else{
        $rpCode =2;
        PayLog360($orderId,$rpCode,$account_id,$PayMoney);//更新充值记录
        WritePayMsg(1,$server_id,$account_id,$orderId,$PayMoney,$game_id);
    }
    echo $okjson ;exit;

}else{
    $str=" 验证失败，get=$get,  ".date("Y-m-d H:i:s")."\r\n";
    write_log(ROOT_PATH."log","360_callback_error_log_",$str);
}

function PayLog360($OrderID,$rpCode,$PayID,$PayMoney){
    SetConn(88);
    $rpTime=date('Y-m-d H:i:s');
    $sql="update pay_log set PayMoney='$PayMoney',rpCode='$rpCode', rpTime='$rpTime'";
    $sql=$sql." where PayID=$PayID and OrderID='$OrderID'";
    //echo $sql;
    if (mysql_query($sql) == False){
        //写入失败日志
        $str=$sql."  ".date("Y-m-d H:i:s")."\r\n";
        write_log(ROOT_PATH."log","360_callback_error_log_",$str);
    }
}




function str_secret($input,$sign_key){
    foreach($input as $k=>$v)
    {
        if(empty($v)){
            unset($input[$k]);
        }
    }
    ksort($input);//对参数按照 key 进行排序
    $sign_str = implode('#',$input);//第四步
    $sign_str = $sign_str.'#'.$sign_key;//拼装密钥（如果是签名，密钥为约定处理后的密钥）
    return  $sign = md5($sign_str);
    // $input['sign'] = $sign;//得到签名
}


?>
