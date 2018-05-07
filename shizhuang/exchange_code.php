<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* ERP管理系统
* ==============================================
* @date: 2016-5-4
* @author: luoxue
* @version:
*/
$table = 'web_code_exchange';
$tableLog = 'web_code_exchange_log';
include("./inc/config.php");
include("./inc/function.php");
set_time_limit(10);
$post = serialize($_POST);
$get = serialize($_GET);
$ip = getIP_front();
write_log("log","exchange_code_all_log_"," post=$post, get=$get, ip=$ip, ".date("Y-m-d H:i:s")."\r\n");

$codeid = trim($_REQUEST['code_id']);
$account = trim($_REQUEST['account_id']);
$game_id = intval($_REQUEST['game_id'])? intval($_REQUEST['game_id']) : 1;
$register_time = intval(trim($_REQUEST['register_time']));
$dwFenBaoID = intval($_REQUEST['dwFenBaoID']);

if($codeid == '')
    exit("1 0 0");   //codeid is null
if($account == '')
    exit("2 0 0");   //accountid is null
$conn = SetConn(88);
$sql = "select * from $table where code_id='$codeid' limit 1";
if(false == $rt = mysqli_query($conn,$sql))
	exit('5 0 0');   //sql error
if(@$rs = mysqli_fetch_array($rt)){
	if($rs['dwFenBaoID'] != 0 ){
	    $dwFenBaoIDArr = explode(',', $rs['dwFenBaoID']);
        if(!in_array($dwFenBaoID, $dwFenBaoIDArr)){
            exit('12 0 0');
        }
	}
    if($rs['used'] == '1'){
        echo "4 0 0";     //already used
        exit;
    }
    if($rs['game_type'] !=0 && $rs['game_type'] != $game_id){
        echo "6 0 0";     //gameid is error
        exit;
    }
    if($rs['time_limit'] !=0 && $rs['time_limit'] < time()){
        echo "7 0 0";     //time expired
        exit;
    }
    if($rs['register_type'] == 1&& $rs['register_time'] > $register_time){
        echo "9 0 0";     //Players registered earlier time
        exit;
    }
    if($rs['register_type'] ==2 && $rs['register_time'] < $register_time){
        echo "10 0 0";     //Players registered too late
        exit;
    }
    if($rs['used_type'] >0 && $rs['is_limit_one'] == 0){
      $sql = "select * from $table where account_id ='$account' and used=1 and used_type='".$rs['used_type']."' limit 1";
      if(false == $result_type = mysqli_query($conn,$sql))
      	exit('5 0 0');
      $num_rows  = mysqli_num_rows($result_type);
      if($num_rows > 0){
        echo "11 0 0";     //codeid only used once!
        exit;
      }
    }
    
    if($rs['used']=='0') {
        $number = $rs['number'];
        $usedtime = date("ymdHi");
        if($number > 1){
        	//一个兑换码可以多次使用的时候
            if($number == $rs['number_used']){
                echo "4 0 0";     //already used
                exit;
            }
            $sql = "select count(id) as count from $tableLog where code_id='$codeid'";
            if(false == $query = mysqli_query($conn,$sql)){
                write_log("log","exchange_insert_error","$sql, ".date("Y-m-d H:i:s")."\r\n");
                exit('5 0 0');
            }
            $countRs = mysqli_fetch_assoc($query);
            if($countRs['count'] >= $number){
                echo "4 0 0";     //already used
                exit;
            }

        	$sql = "select * from $tableLog where account_id ='$account' and code_id='$codeid' limit 1";
        	if(false == $result_type = mysqli_query($conn,$sql)){
        		write_log("log","exchange_insert_error","$sql, ".date("Y-m-d H:i:s")."\r\n");
        		exit('5 0 0');
        	}
        	$num_rows  = mysqli_num_rows($result_type);
        	if($num_rows > 0){
        		echo "11 0 0";     //codeid only used once!
        		exit;
        	}
        	$numberUsed = $rs['number_used']+1;
        	if($numberUsed == $number){
        		$sql = "update $table set used='1', number_used=number_used+1,used_time_stamp='$usedtime' where code_id='$codeid'";
        	} else {
        		$sql = "update $table set  number_used=number_used+1 where code_id='$codeid'";
        	}
        	if(mysqli_query($conn,$sql) != FALSE) {
        		$user_time = date('ymdHi');
        		$sql = "insert into $tableLog (code_id, user_time, account_id) values ('$codeid', '$user_time', '$account');";
        		if(mysqli_query($conn,$sql) != FALSE){
        			echo "0 ".$rs['type']." ".$rs['param'];
        			exit;
        		} else {
        			write_log("log","exchange_insert_error","$sql".date("Y-m-d H:i:s")."\r\n");
        			echo "5 0 0";    //sql error
        			exit;
        		}
        	} else {
        		write_log("log","exchange_insert_error","$sql".date("Y-m-d H:i:s")."\r\n");
        		echo "5 0 0";    //sql error
        		exit;
        	}
        	
        } else {
        	$sql = "update $table set used='1',account_id='$account',used_time_stamp='$usedtime' where code_id='$codeid'";
        	if(mysqli_query($conn,$sql) != FALSE) {
        		echo "0 ".$rs['type']." ".$rs['param'];
        		exit;
        	} else {
        		write_log("log","exchange_insert_error","$sql".date("Y-m-d H:i:s")."\r\n");
        		echo "5 0 0";    //sql error
        		exit;
        	}
        }
    	
    }
} else {
    echo "3 0 0";  //codeid is not exist!
    exit;
}


?>