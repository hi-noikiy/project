<?php
global $tongjiServer;

$tongjiServer = array(
		9=>'http://fhtj.u776.com:8088/index.php/',  //旅行
);
function isOwnWay($PayName,$loginname){
	$ch = explode('@', $PayName);
	$chname = $ch[count($ch)-1];
	if($chname != "$loginname"){
		return 1;
	}else
		return 0;
}
?>