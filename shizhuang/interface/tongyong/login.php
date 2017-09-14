<?php
/**
 * ==============================================
 * Copyright (c) 2015 All rights reserved.
 * ----------------------------------------------
 * 通用登陆接口
 * ==============================================
 * @date: 2016-7-28
 * @author: Administrator
 * @return:
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","tongyong_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$sdkType = $_REQUEST['sdktype'];
$token = $_REQUEST['token'];
$p = $_REQUEST['p'];
$gameId = intval($_REQUEST['game_id']);

switch ($sdkType){
    case 4:
        $url = 'http://fhweb.u776.com:86/interface/uc/login.php';
        $data['sid'] = $token;
        $data['channel'] = $p;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 213:
        $url = 'http://fhweb.u776.com:86/interface/uc_new/login.php';
        $data['sid'] = $token;
        $data['channel'] = $p;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 224:
        $url = 'http://fhweb.u776.com:86/interface/ysdk/login.php';
        $data['token'] = $token;
        $data['channel'] = $p;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
}