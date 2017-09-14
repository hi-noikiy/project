<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2016/12/19
 */
include 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","cgame_callback_info_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
$requestSign = $_GET['sign'];//请求链接的sign
$customData_arr = explode('_', $_GET['ext1']);
$game_id = $customData_arr[0];
$server_id = $customData_arr[1];
$account_id = $customData_arr[2];
$key = $key_arr[$game_id]['paykey'];
$params = $_GET;
unset($params['sign']);
ksort($params);//对参数字母排序
reset($params);
$source = urldecode(http_build_query($params)).'&'.$key;//拼接出源串
$sign = md5($source);
if(strtolower($requestSign) != $sign){
	//流程处理
	write_log(ROOT_PATH."log","cgame_callback_error_","sign error, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit('sign fail');
}

$order_id = $params['outorderid'];
$money = $params['money']/100;
global $money_rate;
$gamemoney = $money*$money_rate;
SetConn(81);//连接数据库
//判断帐号
$sql="select NAME,dwFenBaoID,clienttype from account where ID=$account_id";
//echo $sql;
$conn=mysql_query($sql);
$rs=@mysql_fetch_array($conn);
if(!$rs) {
    write_log(ROOT_PATH."log","cgame_callback_error_"," error=账号不存在, ".date("Y-m-d H:i:s")."\r\n");
    exit("success");
}else{
    $PayName=$rs["NAME"];
    $dwFenBaoID=$rs["dwFenBaoID"];
    $clienttype=$rs['clienttype'];
}

SetConn(88);
    $sql = " select * from pay_log where OrderID = '$order_id' ";
    $query=mysql_query($sql);
    $result_count=@mysql_fetch_assoc($query);
    if($result_count['rpCode']==1){
        write_log(ROOT_PATH."log","cgame_callback_error_"," 订单已存在 order_id=$order_id,".date("Y-m-d H:i:s")."\r\n");
        exit("success");//订单已存在
    }

if($money){
    $Add_Time = date("Y-m-d H:i:s");
    $rpCode = 1;
    $insert_sql = " insert into pay_log(dwFenBaoID,CPID,ServerID,PayMoney,PayName,Add_Time,rpCode,PayID,OrderID,game_id) values('$dwFenBaoID','73','$server_id','$money','$PayName','$Add_Time','$rpCode','$account_id','$order_id','$game_id') ";
    if(mysql_query($insert_sql)){
    	try{
    		WriteCard_money(1,$server_id, $gamemoney,$account_id, $order_id);//写入游戏库
    		updatePoints($account_id,$money,'f_dx',$order_id);//修改积分
    		updateRankUp($account_id,'f_dx');//修改等级
    		WritePayMsg(0,$server_id,$account_id,$order_id,$money,$game_id);
    		exit("success");
    	}catch(Exception $e){
    		// $e->getMessage();
    		write_log(ROOT_PATH."log","cgame_callback_error_",$e->getMessage()." ,error=系统异常, ".date("Y-m-d H:i:s")."\r\n");
    	}
    }else{
        write_log(ROOT_PATH."log","cgame_callback_error_"," error=数据库错误,insert_sql=$insert_sql, ".date("Y-m-d H:i:s")."\r\n");
    }
}
write_log(ROOT_PATH."log","cgame_callback_error_","money:$money, error=数据异常，需查看日志, ".date("Y-m-d H:i:s")."\r\n");
exit("fail");

