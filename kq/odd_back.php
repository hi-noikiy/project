<?php
include_once('common.inc.php');
global $webdb;
$startdate = '2017-09-15';
$enddate = '2017-09-15';
$uid = '417';
$sql = "select uid,supdate,addtime from _web_oddtime where manTag=2 and supdate between '$startdate' and '$enddate'";
if($uid){
	$sql .= " and uid='$uid'";
}
//$webdb->query ( $sql );
$result = $webdb->getList ( $sql );
if(!$result){
	die;
}
foreach($result as $adts){
	$adt = $adts['addtime'];
	$ad = new admin ();
	$card_id = $ad->getInfo ( $adts ['uid'], 'card_id', 'pass' );
	$record = new record ();
	$record->wheres = " card_id='$card_id' and recorddate='" . $adts ['supdate'] . "'";
	$record->pageReNum = '1';
	$hastime = $record->getArray ( 'pass' );
			
	$time = '';
			
	$tmp = explode ( ',', $adt );
	foreach ( $tmp as $key => $val ) {
		$tmp [$key] = $val . "s";
	}
	$adt = implode ( ',', $tmp );
	if ($hastime) 			// 存在指纹打卡记录
	{
		$time = $hastime [0] ['addtime_ex'];
		if ($time) {
			$newtime = getArysort ( $adt . "," . $time ); // 加入的异常时间重新排序
			$record->editData ( array (
					'addtime_ex' => $newtime 
			), $hastime [0] ['id'], 'pass' );
			totaltime ( $adts ['supdate'], $adts ['supdate'], $hastime [0] ['id'] ); // 修改迟到和有效时间
		} else 				// 指纹打卡字段为空时，直接将异常时间点插入
		{
			$record->editData ( array (
					'addtime_ex' => $adt 
			), $hastime [0] ['id'], 'pass' );
			totaltime ( $adts ['supdate'], $adts ['supdate'], $hastime [0] ['id'] ); // 修改迟到和有效时间
		}
	} else 			// 不存在打卡记录，新增一条新的打卡记录
	{
		$res = $webdb->query ( "select employee_no,employee_name from employee_account where account_id='$card_id' limit 0,1" );
		if (@$rs = mysql_fetch_array ( $res )) {
			$gong_id = $rs ['employee_no'];
			$name = $rs ['employee_name'];
			$recordid = $record->addData ( array (
					'card_id' => $card_id,
					'gong_id' => $gong_id,
					'name' => $name,
					'addtime_ex' => $adt,
					'recorddate' => $adts ['supdate'] 
			) );
			totaltime ( $adts ['supdate'], $adts ['supdate'], $recordid ); // 修改迟到和有效时间
		} else {
			echo "<script>alert('该员工指纹号与门禁卡不匹配,请先校正')</script>";
		}
	}
}
function getArysort($str) {
	$ary = explode ( ',', $str );
	$tmpary = array ();
	$i = 10;
	foreach ( $ary as $v ) {
		// echo "2000-12-15 ".str_replace('s','',$v).":00";
		$tmpary [$v . $i] = strtotime ( "2000-12-15 " . str_replace ( 's', '', $v ) . ":00" );
		$i ++;
	}
	asort ( $tmpary, SORT_NUMERIC );
	foreach ( $tmpary as $k => $v ) {
		$tmpary [$k] = substr ( $k, 0, - 2 );
	}
	$str = implode ( ',', $tmpary );
	return $str;
}
?>