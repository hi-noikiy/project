<?php
error_reporting(0);
$act = $_POST['act'];
if($act){
	$act();
}
function toip(){
	$num = $_POST['num'];
	$nums = explode(';', $num);
	$show = '';
	foreach ($nums as $v){
		$show .= long2ip($v).';';
	}
	echo $show;
}

function tonum(){
	$ip = $_POST['ip'];
	$ips = explode(';', $ip);
	$show = '';
	foreach ($ips as $v){
		$v = str_replace(array(" ","　","\t","\n","\r"),array("","","","",""), $v);
		$show .= bindec( decbin( ip2long($v ) ) ).';';
	}
	echo $show;
}


