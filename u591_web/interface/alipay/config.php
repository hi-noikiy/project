<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 支付宝支付
* ==============================================
* @date: 2016-11-24
* @author: luoxue
* @version:
*/
define('ROOT_PATH', str_replace('interface/alipay/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";

$key_arr = array(
		8=>array(
				'appId'=>'2016110802629965',
				'publicKey'=>'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDDI6d306Q8fIfCOaTXyiUeJHkrIvYISRcc73s3vF1ZT7XN8RNPwJxo8pWaJMmvyTn9N4HQ632qJBVHf8sxHi/fEsraprwCtzvzQETrNRwVxLO5jVmRGi60j8Ue1efIlzPXV9je9mkjzOmdssymZkh2QhUrCmZYI/FCEa3/cNMW0QIDAQAB',
				'privateKey'=>'MIICXgIBAAKBgQDsHHAUHdDNsJ3+G1J6rfZV6esAZ5KC8h4ggjhD2SENLCvFLZay
qRdnY2C3YXNTX5Kp1CEWG7rg42g4Ml7i4F3rbgezlFMrNUGpMkWeT50Ws0mbsGGL
Yqz6IutS9bftUU8CjJNDn6FxVJwesiW5rS7C6qyzQqJ+ms1IlMeH4BtpRQIDAQAB
AoGBAIlMqCgqhmd2EwnXHYlVa+/dzNg5YktfPAGvd8SMmNmuG+3XatCN35wxf+Zj
dsq+wuya/Hp8uZlY5Bv5vo+z5SiKq24z/z2cSO4efktJVJazd/Z6yyEbtQWqP0Yc
6YVaQ39kdYvcnKEd/Zb0GksnnB1qlAyDS6aFtQbZQa2b2+/BAkEA9+tJBpjXUvrz
W4nX6wqUGaz2T1p5M9ePqPM0HZz2OhmD+uPb7tUhMykZE1mehq3vUfRHfRNtUbxg
695xQVDAWQJBAPPOn3a0T7/JFC6KYHBQrfC3+QTaqACMxzjDjDcvkohdUURqVaj5
yqbdkSSIQ+z3h40MlSfArvXhfWOAuJfgMs0CQCIDDnawO0IwGXjidVPSlLTdDMGg
OOaK2TOlge3aHdGktH2UCxU9+hsJtV35Oo1hiWal67TWGHZRML7LOqBqUqkCQQCV
eqhwSgrfJjSWeEa6dey/OryVik4Y0DdRCSuNpkAsFTK/RIaybDwgZJYZlOY4gmb8
RquoTM24eQC8oe4LR+09AkEAzTQbBWZRsB96cJxz3R9K+uTUnGreNzj4adsj6zls
uQp/0O07RN5BEU9+EvulVHZ/yFQrBGKWySnWcGrZ0AQoFw==',
				
		),
);