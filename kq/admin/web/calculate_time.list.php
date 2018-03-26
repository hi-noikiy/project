﻿<?php
$from = $_POST ['fromTime'];
$to = $_POST ['toTime'];

if (! $_POST ['fromTime']) {
	$from = mktime ( 0, 0, 0, date ( "m" ) - 1, '01', date ( "Y" ) );
	$from = date ( 'Y-m-d', $from );
}
if (! $_POST ['toTime']) {
	$lastdate = date ( 't', $from ); // 计算当月天数
	$to = mktime ( 0, 0, 0, date ( "m" ) - 1, $lastdate, date ( "Y" ) );
	$to = date ( 'Y-m-d', $to );
}
if (!empty ( $_POST )) {
	include_once ('common.inc.php');
	$astart = '';
	$bstart = '';
	$cstart = '';
	$dstart = '';
	$estart = '';
	$aend = '';
	$bend = '';
	$cend = '';
	$dend = '';
	$eend = '';
	// totaltime('2011-02-01','2011-02-14','');
	// 计算上2天考勤情况
	$last2day = date ( 'Y-m-d', strtotime ( "-2 days" ) );
	$yestoday = date ( 'Y-m-d', strtotime ( "-1 days" ) );
	$last2day = $from;
	$yestoday = $to;
	
	totaltime ( $last2day, $yestoday, '' ); // 计算上班有效时间
	                                   // 计算上班总时间
	/*$startday = date ( 'Y-m-d', strtotime ( "-2 days" ) );
	$endday = date ( 'Y-m-d', strtotime ( "-1 days" ) );
	
	$starttime = strtotime ( $startday . " 00:00:00" );
	$endtime = strtotime ( $endday . " 00:00:00" );
	
	$admin = new admin ();
	$admin->wheres = " id!='99' and id!='145' and (depId!='11' or depMax='1')";
	$admin->pageReNum = '500';
	$adlist = $admin->getArray ( "pass" );
	
	foreach ( $adlist as $ad ) {
		for($t = $starttime; $t <= $endtime; $t = $t + 86400) 		// 计算开始到结束时间的每天的总上班时间
		{
			$to = date ( 'Y-m-d', $t );
			acAllTotalTime ( $ad ['card_id'], $to );
		}
	}*/
	
	$workClass = new workday ();
	for($i = 1; $i < 2; $i ++) 	// 定制下$i个月的工作日
	{
		$time = mktime ( 0, 0, 0, date ( "m" ) + $i, '01', date ( "Y" ) );
		$year = date ( 'Y', $time );
		$month = date ( 'm', $time );
		$date = date ( 'Y-m-d', $time );
		$lastdate = date ( 't', $time ); // 计算当月天数
		$workClass->wheres = "workday='$date'";
		$workClass->orders = 'id desc';
		$workClass->pageReNum = 1;
		$list = $workClass->getList ();
		// echo $workClass->querySql;
		if (! $list) {
			for($m = 1; $m <= $lastdate; $m ++) {
				$day = $year . "-" . $month . "-" . $m; // 生成日期
				$today = date ( 'N', strtotime ( $day ) ); // 找出星期几
				if ($today < 6) 				// 周一到周五
				{
					$workClass->add ( array (
							"workday" => $day,
							"tag" => "1" 
					) );
				} else 				// 周六\日
				{
					$workClass->add ( array (
							"workday" => $day,
							"tag" => "0" 
					) );
				}
			}
		}
	}
	exit('计算完成！');
}

?>
<h1 class="title">
	<span>考勤统计查询</span>
</h1>
<div class="pidding_5">
	<div class="search">
		<form action="" method="post">
			时间：<input type="text" name="fromTime" size="10" id="date_s"
				value="<?=$from?>" readonly> 到： <input type="text" name="toTime"
				size="10" id="date_e" value="<?=$to?>" readonly> <input
				type="submit" name="sub" value="查 询" class="sub2">

		</form>
	</div>
</div>