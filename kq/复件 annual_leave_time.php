<?php
include_once('common.inc.php');
global $webdb;
$sql = "select id,inTime from _sys_admin";
//$webdb->query ( $sql );
$result = $webdb->getList ( $sql );
$y = date('Y');
$lasty = date('Y',strtotime('-1 year'));
$ydate = $y .'0101';//当年度元日
$date = date('Ymd');
$mysql = 'insert into _web_annual_leave(uid,useYear,allTime)values';
foreach($result as $v){
	$intime = $v['inTime'];
	$n = 0;//今年入职的无年假(记录总年假时间)
	if($intime && $intime<$ydate){ //至少去年入职的计算年假
		$ld = $webdb->getValue("select sum(totalTime) as ld from _web_leave where uid='".$v['id']."' and available='1' and manTag='2' and fromTime like '".$lasty."%' and leaveType='产假'",'ld');
		if($ld>0){//请产假则不计算年假
			$n=0;
		}else{
			//请假太长时间则不计算年假
			$le = $webdb->getValue("select sum(totalTime) as le from _web_leave where uid='".$v['id']."' and available='1' and manTag='2' and fromTime like '".$lasty."%' and leaveType not in('年假','产假')",'le');
			$month = floor($le/8/30);
			$rate = (strtotime($ydate)-strtotime($intime))/(24*60*60*365);//计算入职几年
			if($rate>=20){
				$n = 15*8;
				if($month>3){//超过4个月
					$n=0;
				}
			}elseif($rate>=10){
				$n = 10*8;
				if($month>2){//超过3个月
					$n=0;
				}
			}elseif($rate>=1){
				$n = 5*8;
				if($month>1){//超过2个月
					$n=0;
				}
			}else{
				$n = floor($rate*5*8);
			}
		}
		
	}
	$mysql .= "(".$v['id'].",".$y.",".$n."),";
}
$mysql = rtrim($mysql,',') . 'on duplicate key update allTime=values(allTime)';
$webdb->query($mysql);
		
?>