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
    		'android2'=>array(
    				'appKey'    =>'-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAkez3voODnrTgSswdvVzS
0FmMaFXaB0i+8nblq7vQn4eFbG6rhuqdEwaSOjSfbUrJIOz61b5lhKP8EXrbSEAz
JeDkSVdTyTIL3hNX1gaYdig2Rj3jLoRcIqqTEuwNgjWB0tK8iIwr6TZQTDv/usyn
otnvqv5x1QnJhh4nWjxNQvAWNjxU2cz5K7z+AjbBXCKK4HAv30J99PborWNLARGL
pBdP1wjYSQdPlBCuOeSDdBiF8G9tG+DgohMdsFaTb0IU+KnuywTI+esZ4Knt/dz2
5ScTRx+Ic/lX4f0Xg3kuoa+lVe9Bsw8+BhaWcoJs58HmArrQVM5wYWL++dgl1wAE
1wIDAQAB
-----END PUBLIC KEY-----',
    		),
    ),
);
?>
