<?php
set_time_limit(0);
$randomNum = array();
// $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
// $orderSn = $yCode[intval(date('Y')) - 2011] . 
// 		strtoupper(dechex(date('m'))) . 
// 		date('d') . substr(time(), -5) 
// 		. substr(microtime(), 2, 5) 
// 		. sprintf('%02d', rand(0, 99));

// echo $orderSn;

//16位
//
$dsn = "mysql:dbname=test;host=localhost;port=3316";
$pdo = new PDO($dsn, 'root', '225800');
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
$pdo->exec("SET NAMES 'utf8';");
if(php_sapi_name() == 'cli'){
  $query = $pdo->prepare("set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000");
  $query->execute();
}
$base32 = array (   
	'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H',   
	'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P',   
	'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X',   
	'Y', 'Z', 'a', 'b', 'c', 'd', 'e', 'f', 
	'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n',
	'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 
	'w', 'x', 'y', 'z', '0', '1', '2', '3',
	'4', '5', '6', '7', '8', '9'
);
for($cnts = 0; $cnts<2; $cnts++) {
	$snCodes = array();
	$len = count($base32)-1;
	for ($k=0; $k<100000; $k++) {
		$arr = array();
		for($i=0; $i<16; $i++) {
			$arr[] = $base32[mt_rand(0, $len)].mt_rand(0,99).microtime();
		}
		$snCodes[] = substr(md5(implode('', $arr)), 0, 16);
	}
	$sql = "INSERT INTO `sn_code`(`sn_code`) VALUES";
	$values = '';
	foreach ($snCodes as $code) {
		$values .= "('$code'),";
	}
	$values = rtrim($values, ',');
	$ret = $pdo->exec($sql.$values);
	var_dump($ret);
}

?>  