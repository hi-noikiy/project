<?php
define('ROOT_PATH', str_replace('interface/huawei/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";

$key_arr = array(
    8=>array(
    	'android'=>array(
    				'appKey'    =>'-----BEGIN PUBLIC KEY-----
MFwwDQYJKoZIhvcNAQEBBQADSwAwSAJBAOAZ4rFUbVmfeoLvy7Fv6rQfo8Mqg7mE
ZnF5v0jq8MNOQ1YFqISUFoMQM6Z+zbJYJUFWBGv8Qd0R/js24wrExOECAwEAAQ==
-----END PUBLIC KEY-----',
    	),
    	'android1'=>array(
    			'appKey'    =>'-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAjxIdCZv3NALmKmP0F7+O
ysvvijphsxRD++BhS7Sl97YhWRemVjpMSBaA/w6DDGCZrpIQdolyDU3JUqlkLfyo
ZE/V9qzroJtbcbwinOWvOWD/EcOKsKt8i2AWXDzwkktpHxTJPe8P4wtfYX4chD+3
wh9I3NePsQZnilnrgBxVmNBU2xpvU+vFmMOev93AR6zzn/YJegzopgzYz/+35qGZ
/3XD0bUqy93iHLYsX0UEuUa+Q2+WKa1INmDwrVl9l6Su35dDekBgyjM8P+8GXDOK
RdvsiFbT+IMPDWIod7zSiMM9qkXmhD340k6zr0rOkb2cEWvcZUgL6M24eSSSMfec
VwIDAQAB
-----END PUBLIC KEY-----',
    	),	
    ),
);
?>
