<?php
define('ROOT_PATH', str_replace('interface/TTyuyin/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";
$key_arr = array(
		'android'=>array(
				'baseUrl'       =>'http://123.59.74.32/server/rest/user/loginstatus.view',
				'gameId'       =>'201611021',
				'apikey'       =>'4e8dfd36fd556dd2c829fba0650986ab',
				'chargekey'       =>'3340aa079dc47e3fd01b86715f289a38',
		),
		'android1'=>array(
				'baseUrl'       =>'http://123.59.74.32/server/rest/user/loginstatus.view',
				'gameId'       =>'201702324',
				'apikey'       =>'56de8fa0f6709a16828a2a5ec898f606',
				'chargekey'       =>'fe7fbdeb5ddbcc3ef41232ec8d04ee5f',
		),
		'ios'=>array(
				'baseUrl'       =>'http://sdk.52tt.com/sdk.server/rest/user/loginstatus.view',
				'gameId'       =>'201621122',
				'apikey'       =>'0ab24cc1dcd498eda6bc4a755c314a6b',
				'chargekey'       =>'f098313567c11f1f64d7427ccd48ba3e',
		),
		'ios1'=>array(
				'baseUrl'       =>'http://sdk.52tt.com/sdk.server/rest/user/loginstatus.view',
				'gameId'       =>'207707095',
				'apikey'       =>'f114e3d592d2a4e296590dc47d7b113e',
				'chargekey'       =>'412af66197b25fd6cfa553f71814e218',
		),
);
?>