<?php
/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 14-6-15
 * Time: 下午5:22
 */
define('ROOT_PATH', str_replace('analysis/config/config.php', '', str_replace('\\', '/', __FILE__)));
define('A_ROOT', ROOT_PATH.'analysis/');
date_default_timezone_set('Asia/Shanghai');//设置北京时间
session_start();
include A_ROOT.'inc/game_config.php';

include A_ROOT.'class/fun.inc.php';
include A_ROOT.'class/page.inc.php';
include A_ROOT.'class/autoload.php';

include 'db.conf.php';

//菜单栏分组
$navGrp = array(
    1=>'n_datacount',
    2=>'n_apple',
    3=>'n_log',
    4=>'n_user_manage',
    5=>'n_op',
    6=>'n_pannel',
    7=>'n_pay',
    8=>'n_analysis',
	9=>'n_develope_statistical',//养成统计
);

//多语言
if (!isset($_COOKIE['lang'])) {
    $lang = 'zh_CN';
    setcookie('lang', 'zh_CN', $_SERVER['REQUEST_TIME']+strtotime('+1 years'));
}
else {
    $lang = $_COOKIE['lang'];
}

//print_r($_COOKIE);
//echo $lang;exit;
include A_ROOT.'language/'.$lang.'/allview_lan.php';

//$token = System::GenerateToken();