<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/1/4
 * Time: 下午2:01
 * 港台
 */
define('ROOT_PATH', str_replace('interface/mofang/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";
$key_arr = array(
    8=>array(
        'ios' => array(
            'loginkey'  =>'RGF0ZT0yMDE2LzEyLzE5IDE1OjQ0OjUzJkNvcHlyaWdodD1AIDIwMTYgdWRmdW4uY29tIEdN5Zyo57ea6YGK5oiy5bmz5Y+wJkdBTUVOQU1FPeWPo+iii+WmluaAqnZzJlNpZ25hdHVyZT0wQkYzMTFCQTUyQTcwQUJDOTdGREI0NzcwQzU1Q0Q2QQ==',
            'paykey'    =>'RGF0ZT0yMDE2LzEyLzE5IDE1OjQ0OjUzJkNvcHlyaWdodD1AIDIwMTYgdWRmdW4uY29tIEdN5Zyo57ea6YGK5oiy5bmz5Y+wJkdBTUVOQU1FPeWPo+iii+WmluaAqnZzJlNpZ25hdHVyZT00M0VFOEYwRDg1RTk4ODU5OTA4RUQwQTAyRUE3NzgyOA==',
            'playerkey' =>'RGF0ZT0yMDE2LzEyLzE5IDE1OjQ0OjUzJkNvcHlyaWdodD1AIDIwMTYgdWRmdW4uY29tIEdN5Zyo57ea6YGK5oiy5bmz5Y+wJkdBTUVOQU1FPeWPo+iii+WmluaAqnZzJlNpZ25hdHVyZT01RjJCQTY5MEY2NTMyMzNDQTNGMkM5RTgyRUVENkMxQg==',
        ),
        'android' => array(
            'loginkey'  =>'RGF0ZT0yMDE2LzEyLzE5IDE1OjQ0OjUzJkNvcHlyaWdodD1AIDIwMTYgdWRmdW4uY29tIEdN5Zyo57ea6YGK5oiy5bmz5Y+wJkdBTUVOQU1FPeWPo+iii+WmluaAqnZzJlNpZ25hdHVyZT0wQkYzMTFCQTUyQTcwQUJDOTdGREI0NzcwQzU1Q0Q2QQ==',
            'paykey'    =>'RGF0ZT0yMDE2LzEyLzE5IDE1OjQ0OjUzJkNvcHlyaWdodD1AIDIwMTYgdWRmdW4uY29tIEdN5Zyo57ea6YGK5oiy5bmz5Y+wJkdBTUVOQU1FPeWPo+iii+WmluaAqnZzJlNpZ25hdHVyZT00M0VFOEYwRDg1RTk4ODU5OTA4RUQwQTAyRUE3NzgyOA==',
            'playerkey' =>'RGF0ZT0yMDE2LzEyLzE5IDE1OjQ0OjUzJkNvcHlyaWdodD1AIDIwMTYgdWRmdW4uY29tIEdN5Zyo57ea6YGK5oiy5bmz5Y+wJkdBTUVOQU1FPeWPo+iii+WmluaAqnZzJlNpZ25hdHVyZT01RjJCQTY5MEY2NTMyMzNDQTNGMkM5RTgyRUVENkMxQg==',
        ),
    )
);