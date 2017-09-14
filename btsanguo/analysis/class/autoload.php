<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cgp
 * Date: 13-4-6
 * To change this template use File | Settings | File Templates.
 */
define('CLS_PATH', A_ROOT.'/class/');
function cgp_autoload($class) {
    $parts = explode('\\', $class);
    $class = end($parts);
    //require end($parts) . '.php';
    if(is_file(CLS_PATH.$class.'.class.php')) {
        //echo CLASSPATH.$class.'.class.php';
        include(CLS_PATH.$class.'.class.php');
        return true;
    }
    return false;
}
spl_autoload_register('cgp_autoload');
