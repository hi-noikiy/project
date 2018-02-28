<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/2/23
 * Time: 下午2:07
 */
define('ROOT_PATH', str_replace('interface/apple/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";

$appleIdVal = array(
		'yfe_yf1_6'                 =>array('6', 6	, 'CNY'),
		'yfe_yf1_18'                =>array('18', 18	, 'CNY'),
		'yfe_yf1_68'                =>array('68', 68	, 'CNY'),
		'yfe_yf1_128'                =>array('128', 128	, 'CNY'),
		'yfe_yf1_328'                =>array('328', 328	, 'CNY'),
		'yfe_yf1_648'                =>array('648', 648	, 'CNY'),
);