<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2016/12/9
 * Time: 上午11:24
 */
define('ROOT_PATH', str_replace('interface/play800_android/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH.'inc/function.php';
$key_arr = array(
    8=>array(
        'android'=>array(
            'gid'       =>'581',
            'site'      =>'kdyg_data',
            'key'       =>'82ae32f271708ecbcbf6168a896af776',
        ),
    ),
);

$fenbao_arr = array(
    '817001'=>'kdyg_ios2',
    '818001'=>'kdyg_ios3',
    '839001'=>'kdyg_ios22',
    '859008'=>'kdyg_ios63',
    '859014'=>'kdyg_ios68',
    '859015'=>'kdyg_ios72',
    '859022'=>'kdyg_ios77',
);