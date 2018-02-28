<?php
define('ROOT_PATH', str_replace('interface/yijie/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";
$key_arr = array(
    8=>array(
    		'android'=>array('app_id'=>'B14E9A7B-07AB3074','app_key'=>'4b107bda256a474d8e96a277340d139c'),
    		'ios'=>array('app_id'=>'941C8962A3C1EE1D','app_key'=>'GKYSKJEMPRVAMM9NOTV98RYEVODYLMTY')
    )
);

?>
