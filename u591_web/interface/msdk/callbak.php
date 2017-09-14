<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 应用宝支付回调
* ==============================================
* @date: 2016-4-15
* @author: Administrator
* @return:
*/
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);

write_log(ROOT_PATH."log","msdk_callback_log_", "post=$post, get=$get,"." ".date("Y-m-d H:i:s")."\r\n");
