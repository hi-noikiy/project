<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-20
 * Time: 上午10:38
 */
$arr1 = array(
    '1_1'=>array('name'=>'ddd','age'=>223),
    '1_2'=>array('name'=>'bd','age'=>233),
    '1_4'=>array('name'=>'ad','age'=>2),
);
$arr2 = array(
    '1_1'=>array('lev'=> 20,'vip'=>1),
    '1_2'=>array('lev'=> 40,'vip'=>1),
    '1_6'=>array('lev'=> 90,'vip'=>2),
);

$arr = array_merge_recursive($arr1, $arr2);
print_r($arr);