<?php
define('ROOT_PATH', str_replace('interface/yijie/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH."inc/function.php";
include_once ROOT_PATH.'inc/moneyconfig.php';
$key_arr = array(
    5=>array(
    		'Android'=>array('app_id'=>'9FE828504AA8115B','app_key'=>'BYJTQOA5SO2R2OO8FJQVBG72PUW3Q2GS'),
    		'ios'=>array('app_id'=>'941C8962A3C1EE1D','app_key'=>'GKYSKJEMPRVAMM9NOTV98RYEVODYLMTY')
    )
);

?>
