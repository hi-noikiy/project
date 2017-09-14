<?php
/**
 * ==============================================
 * Copyright (c) 2015 All rights reserved.
 * ----------------------------------------------
 * 爱普获取订单号
 * ==============================================
 * @date: 2016-4-27
 * @author: Administrator
 * @return:
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","union_getorderid_info_"," post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$array['userID'] = $_REQUEST['userID'];
$array['productID'] = $_REQUEST['productID'];
$array['productName'] = $_REQUEST['productName'];
$array['productDesc'] = $_REQUEST['productDesc'];
$array['money'] = $_REQUEST['money'];
$array['roleID'] = $_REQUEST['roleID'];
$array['roleName'] = $_REQUEST['roleName'];
$array['serverID'] = $_REQUEST['serverID'];
$array['serverName'] = $_REQUEST['serverName'];
$array['extension'] = $_REQUEST['extension'];



$url = 'http://123.207.248.208:8080/u8server/pay/getOrderID';
$extensionArr = explode("_", $array['extension']);
$game_id = $extensionArr[0];

$appSecret = $arr_key[$game_id]['appSecret'];
$mySignStr = "userID={$array['userID']}&productID={$array['productID']}&productName={$array['productName']}&productDesc={$array['productDesc']}&money={$array['money']}&roleID={$array['roleID']}&roleName={$array['roleName']}&serverID={$array['serverID']}&serverName={$array['serverName']}&extension={$array['extension']}".$appSecret;

$mySign = md5(urlencode($mySignStr));
$array['sign'] = $mySign;
$array['signType'] = 'md5';
$result = https_post($url, $array);
write_log(ROOT_PATH."log","union_getorderid_check_","result=$result,url=$url, signStr=$mySignStr, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$resultArr = json_decode($result, true);

exit($result);
?>