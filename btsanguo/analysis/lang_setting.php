<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-12
 * Time: 下午1:57
 */
$lang = trim($_GET['lang']);
//echo $lang;
//exit;
$langArr = array('zh_CN', 'en_US');
if (!in_array($lang, $langArr)) {
    echo '<script>alert("Wrong Language!不存在此种语言！");history.go(-1);</script>';
    exit;
}
if(!empty($_COOKIE['lang'])) {
    setcookie('lang', '', $_SERVER['REQUEST_TIME']-strtotime('+1 year'),'/');
}

setcookie('lang', trim($_GET['lang']), $_SERVER['REQUEST_TIME']+strtotime('+1 year'),'/');

//exit;
$ref = $_SERVER['HTTP_REFERER'];
header('location:'.$ref);
exit;