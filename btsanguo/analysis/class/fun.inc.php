<?php
/**
 * 写记录到日志文件
 * @param string $word 记录文字
 * @param string $filepath 日志文件
 */
function writeLog($word, $filepath = 'web_logs.txt') {
    $maxsize  =  10485760;//1024*1024*10;
//    if(file_exists($filepath)){
//        $filesize = filesize($filepath);
//        $filetype = end(explode(".",$filepath)); //获取文件后缀名
//        $filename = substr($filepath, 0, strpos($filepath,'.'));
//        if($filesize>$maxsize) {
//            rename($filepath, $filename.'_'.date('Ymd').'.'.$filetype);
//        }
//    }
    $fp = fopen($filepath,"a");
    flock($fp, LOCK_EX) ;
    fwrite($fp,"\n" .date('Y-m-d H:i:s').'|'.$word);
    flock($fp, LOCK_UN);
    fclose($fp);
}
function to_utf8($str) {
    return iconv('gbk', 'utf-8', $str);
}
if (!function_exists('time_format')) {
    function time_format($time)
    {
        if ($time==0) {
            return 0;
        }
//        141231
        if ($time<201231) {
            return date('Y-m-d', strtotime('20'.$time));
        }
        if ($time<20381231) {
            //yyyymmdd格式
            return date('Y-m-d',strtotime($time));
        }
        if ($time > 10000000000) {
            return date('Y-m-d H:i:s', strtotime('20'.substr($time,1)));
        }
        if ($t2 = strtotime('20' . $time)) {
            return date('Y-m-d H:i:s', $t2);
        }
        else {
            return date('Y-m-d H:i:s', $time);
        }
    }
}
function halfSearch($array ,$search) {
    $len = count($array);
    $low = 0 ;
    $high= $len - 1 ;
    if($search <$array[$low] || $search > $array[$high]){
        return false ;
    }
    while ($low <= $high){
        $mid = floor(($high + $low)/2) ;
        //echo $mid . "\n";
        if($search>$array[$mid] && $search<=$array[$mid+1] ) {
            return $mid ;
        }
        else if ($array[$mid]>=$search){
            $high = $mid - 1 ;
        }
        else if($array[$mid]<$search){
            $low  = $mid + 1 ;
        }
        else {
            return 0 ;
        }
    }
    return false ;
}
if(!function_exists('placeholders')) {
    function placeholders($text, $count=0, $separator=","){
        $result = array();
        if($count > 0){
            for($x=0; $x<$count; $x++){
                $result[] = $text;
            }
        }
        return implode($separator, $result);
    }
}

function htmlSelect(Array $lists, $name, $selected, $default=array(-1,'--请选择--'), Array $attrs=array())
{
    $select = "<select name='$name'";
    if(count($attrs)) {
        foreach($attrs as $attr=>$val) {
            $select .= "$attr='$val'";
        }
    }
    $select .= '>';
    if (count($default)) {
        $select .= '<option value="'.$default[0].'">'.$default[1].'</option>';
    }

    foreach($lists as $key=>$value) {
        $select .= "<option value='$key'";
        if($key==$selected) {
            $select .= "selected";
        }
        $select .= ">$value</option>";

    }
    return $select .'</select>';
}
function htmlMulSelect(Array $lists, $name, $selected, Array $attrs=array(),
                       $isgrp=false, Array $grps=array())
{
    $select = "<select multiple='multiple' name='$name'";
    if(count($attrs)) {
        foreach($attrs as $attr=>$val) {
            $select .= "$attr='$val'";
        }
    }
    $select .= '>';

    if ($isgrp) {
        foreach ($lists as $grpid=>$srvs) {
            $select .= '<optgroup label="'.$grps[$grpid].'">';
            foreach ($srvs as $key=>$value) {
                $select .= "<option value='{$key}'";
                if( in_array($key,$selected)) {
                    $select .= "selected";
                }
                $select .= ">$value</option>";
            }
            $select .= '</optgroup>';
        }
    }
    else {
        foreach($lists as $key=>$value) {
            $select .= "<option value='$key'";
            if( in_array($key,$selected)) {
                $select .= "selected";
            }
            $select .= ">$value</option>";
        }
    }
    return $select .'</select>';
}