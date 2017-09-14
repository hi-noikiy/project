<?php
define('ROOT_PATH', str_replace('system/init.php', '', str_replace('\\', '/', __FILE__)));
//header('Content-type: text/html; charset=gbk');

array_walk($_GET, 'trim_value');
array_walk($_POST, 'trim_value');
function trim_value(&$value)
{
    if(is_array($value)){
       array_walk($value, 'trim_value');
    }else{
       $value = trim($value);
    }
}

require_once ROOT_PATH.'system/inc/config.php';
require_once ROOT_PATH.'system/inc/CheckUser.php';
?>
