<?php
define('ROOT_PATH', str_replace('interface/xiao7/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";
$key_arr = array(
    8=>array(
        'ios' => array(
            'payid'=>'10524',
            'appkey' =>'2918d7961abd9467e1b452200a231d22',
            'publickey'=>'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC5KYMU5wu6tR7CXAZmcxz0SL7lghfk9gl7gU5AFZHpI7QVncMBYDabq/QE1ac2YXn2AxyQGNupv7cGf1q8L2+z5WJ2c/jrhntOLePoAEpyt70QCTir3yXLANfhs8CIaw2f3WmydbbL929pZPqCqqhCf6LUbWfBXbpPhefgMOCjgQIDAQAB',
        ),
        'android' => array(
            'payid'=>'10453',
            'appkey' =>'0473816dc12bf9332f6b9f0d089abd12',
            'publickey'=>'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC66aYrhIuFmXCgGCJTu2c77EMDHL5xPeP60dkdc9Uf1JQBJm79LUhiEYIpLROylgqRSsGIJ2Srw/eJfX0R8yiqHMPFMg97sPck7+1jgkmo6PBgFkwnZxm08+1aGqOx3fl+wGXT/LmxdKpEQrRXHJTjwq7n6f6qiMfyJbqqlp/aYwIDAQAB',

    	),
    )
);
?>