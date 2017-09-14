<?php
include("./inc/config.php");
include("./inc/function.php");

set_time_limit(10);

$post = serialize($_POST);
$get = serialize($_GET);
$file_in = file_get_contents("php://input");
$ip = getIP_front();
write_log("log","exchange_code_all_log_"," post=$post,get=$get,request=$request,$HTTP_RAW_POST_DATA,file_in=$file_in,ip=$ip, ".date("Y-m-d H:i:s")."\r\n");


SetConn(212);

$codeid = trim($_REQUEST['code_id']);
$account = trim($_REQUEST['account_id']);
$game_id = intval($_REQUEST['game_id'])?intval($_REQUEST['game_id']):1;
$register_time = intval(trim($_REQUEST['register_time']));


//echo $ip;
//if(!in_array($ip,$ipList))
//{
//    echo "8 0 0";    //ip限制 $ipList为合法IP
//    $ipstr = "codeid=$codeid,account=$account,$ip";
//    write_log("log","exchange_ip_log",$ipstr.date("Y-m-d H:i:s")."\r\n");
//    exit;
//}
if($codeid=='')
{
    echo "1 0 0";   //codeid不为空
    exit;
}
if($account=='')
{
    echo "2 0 0";   //账号ID不为空
    exit;
}
//连接数据库
//SetConn(88);
$sql = "select * from u_code_exchange where code_id='$codeid' limit 0,1";
//echo $sql;exit;
$rt = mysql_query($sql);
if($rs = mysql_fetch_array($rt))
{
    if($rs['used']=='1')
    {
        echo "4 0 0";     //已经使用过
        exit;
    }
    if($rs['game_type']!=0&&$rs['game_type']!=$game_id){
        echo "6 0 0";     //兑换码游戏类型不对
        exit;
    }
    if($rs['time_limit']!=0&&$rs['time_limit']<time()){
        echo "7 0 0";     //兑换码时间过期
          exit;
    }
    if($rs['register_type']==1&&$rs['register_time']>$register_time){
        echo "9 0 0";     //玩家注册时间较早，无法使用
             exit;
    }
    if($rs['register_type']==2&&$rs['register_time']<$register_time){
        echo "10 0 0";     //玩家注册时间过晚，无法使用
        exit;
    }
    if($rs['used_type']>0){
      $sql = "select * from u_code_exchange where account_id ='$account' and used=1 and used_type='".$rs['used_type']."' limit 0,1";
      $result_type = mysql_query($sql);
      $num_rows  = mysql_num_rows($result_type);
      if($num_rows>0){
        echo "11 0 0";     //该类型的兑换码，一个游戏账号只能兑换一次
        exit;
      }
    }




    if($rs['used']=='0')
    {
        $usedtime = date("ymdHi");
        $sql = "update u_code_exchange set used='1',account_id='$account',used_time_stamp='$usedtime' where code_id='$codeid'";
        if(mysql_query($sql)!=FALSE)
        {
            echo "0 ".$rs['type']." ".$rs['param'];
            exit;
        }
        else
        {
            write_log("log","exchange_insert_error","$sql".date("Y-m-d H:i:s")."\r\n");
            echo "5 0 0";    //sql执行失败.重试
            exit;
        }
    }
}
else
{
    echo "3 0 0";  //codeid不存在
    exit;
}


?>