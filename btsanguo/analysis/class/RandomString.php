<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-20
 * Time: 下午6:03
 */

class RandomString {
    public function GetRandString($len, $cnt)
    {
        $s = array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b',
            'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o',
            'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', );

    }
    function getOptions()
    {
        $options = array();
        $result = array();
        for($i=48; $i<=57; $i++)
        {
            array_push($options,chr($i));
        }
        for($i=65; $i<=90; $i++)
        {
            $j = 32;
            $small = $i + $j;
            array_push($options,chr($small));
        }
        return $options;
    }
}

function getOptions()
{
    $options = array();
    for($i=48; $i<=57; $i++)
    {
        array_push($options,chr($i));
    }
    for($i=65; $i<=90; $i++)
    {
        $j = 32;
        $small = $i + $j;
        array_push($options,chr($small));
    }
    return $options;
}
/*
$e = getOptions();
for($j=0; $j<150; $j++)
{
	echo $e[$j];
}
*/
$len = 16;
// 随机生成数组索引，从而实现随机数
for($j=0; $j<100000; $j++)
{
    $result = "";
    $options = getOptions();
    $lastIndex = 35;
    while (strlen($result)<$len)
    {
        // 从0到35中随机取一个作为索引
        $index = rand(0,$lastIndex);
        // 将随机数赋给变量 $chr
        $chr = $options[$index];
        // 随机数作为 $result 的一部分
        $result .= $chr;
        $lastIndex = $lastIndex-1;
        // 最后一个索引将不会参与下一次随机抽奖
        $options[$index] = $options[$lastIndex];
    }
    echo $result."\n";
}