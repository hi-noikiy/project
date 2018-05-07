<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 官网账号绑定
* 先支持邮箱绑定
* ==============================================
* @date: 2016-7-19
* @author: luoxue
* @version:
*/
include_once 'config.php';
$id = $_REQUEST['id'];
$ids = explode('_', $id);
$id = 'SDK_'.$ids[1];
$url = $share_url[$id]?$share_url[$id]:$share_url[1];
header("Location:$url");
exit();