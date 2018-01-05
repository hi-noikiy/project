<?php
require_once(dirname(__FILE__).'/manager.php');
require_once(dirname(__FILE__).'/department.php');
//过滤字符串
function gjj($str){
	$farr = array(
			"/\\s+/",
			"/<(\\/?)(script|i?frame|style|html|body|title|link|meta|object|\\?|\\%)([^>]*?)>/isU",
			"/(<[^>]*)on[a-zA-Z]+\\s*=([^>]*>)/isU",
	);
	$str = preg_replace($farr,"",$str);
	return addslashes($str);
}
function DangerInput($array){
	if(get_magic_quotes_gpc()) return $array;
	if (is_array($array)){
		foreach($array AS $k => $v){
			$array[$k] = DangerInput($v);
		}
	}else{
		$array = gjj($array);
	}
	return $array;
}
//获取上个月
function PreMonth($num=1){
	if (date("n") == 1) {
		$tmpMonth = 12;
		$tmpYear = date ("Y") - 1;
	}else{
		$tmpMonth = date ("n") - $num;
		if($tmpMonth<10)
			$tmpMonth='0'.$tmpMonth;
		$tmpYear = date ("Y");
	}
	$tmpDate = "$tmpYear-$tmpMonth";
	return $tmpDate;
}
//删除文件夹
function deldir($dir) {
	$dh=opendir($dir);
	while (@$file=readdir($dh)) {
		if($file!="." && $file!="..") {
			$fullpath=$dir."/".$file;
			if(!is_dir($fullpath)) {
				unlink($fullpath);
			} else {
				deldir($fullpath);
			}
		}
	}
	closedir($dh);
	if(rmdir($dir)) {
		return true;
	} else {
		return false;
	}
}
//密码加密
function md5_10($str){
	$str=md5(strrev($str));
	$strLeft=substr($str,0,16);
	$strRight=substr($str,16,16);
	$str=md5($strLeft).md5($strRight);
	return md5($str);
}
//获取操作
function operateName($action){
	$needs="";
	$arr=explode('/',$_SERVER['REQUEST_URI']);	
	foreach ($arr as $k => $v){
		if(preg_match("/$action/",$v)){
			$needs=$k;
		    break;
		}
	}
	if(empty($arr[$needs+1]))
		$arr='index';
	else
		$arr=$arr[$needs+1];
	$arr=explode('&', $arr);
	$operate = str_replace('.html', '', $arr[0]);
	
	return $operate;
	
	
}
//前台处理缩略图
function setThumb($string){
	$str=str_replace("./assets", ASSETS_URL, $string);
	return $str;
}

function htmlFilter($val,$len){
	$str=htmlspecialchars_decode($val);
	$strlen=strlen($str);
	$str= preg_replace("/<(.*?)>/","",$str);
	if ($len<$strlen)
		return mb_substr($str, 0,$len)."...";
	else
		return $str;
}
//冒泡排序
function BubbleSort($arr,$key=null){
	$len=count($arr);
	for($i=0;$i<$len-1;$i++){
		for($j=0;$j<$len-1;$j++){
			if(empty($key)){
				if($arr[$j]<$arr[$j+1]){
					$tmp=$arr[$j+1];
					$arr[$j+1]=$arr[$j];
					$arr[$j]=$tmp;
				}
			}else{
				if($arr[$j][$key]<$arr[$j+1][$key]){
					$tmp=$arr[$j+1];
					$arr[$j+1]=$arr[$j];
					$arr[$j]=$tmp;
				}
			}
		}
	}
	return $arr;
}
//object to array
function objectToArray($object){
	$_array = is_object($object) ? get_object_vars($object) : $object;
	foreach ($_array as $key => $value) {
		$value = (is_array($value) || is_object($value)) ? objectToArray($value) : $value;
		$array[$key] = $value;
	}
	return $array;
}
//菜单显示 $val 以INDEX.INDEX
function showMenu($str){
	if(isset(Yii::app()->session['administrator']))
		return true;
	$arr=explode('.', $str);
	$num=count($arr);
	$menu=Yii::app()->session['_ACCESS_LIST'];
	if($num==1){
		if(!isset($menu['ADMIN']["$arr[0]"]))
			return false;
	} elseif($num==2){
	    if(!isset($menu['ADMIN']["$arr[0]"]))
	    	return false;
	    elseif(!isset($menu["ADMIN"]["$arr[0]"]["$arr[1]"]))
	        return false;
	}
	return true;
}
//快速排序法
function quickSort($arr){
	$len    = count($arr);
	if($len <= 1) 
		return $arr;
	$key 		  = $arr[0];
	$keywhere 	  = $arr[0]['overtime'];
	$left_arr     = array();
	$right_arr    = array();
	for($i=1; $i<$len; $i++){
		if($arr[$i]['overtime'] >= $keywhere){
			$left_arr[] = $arr[$i];
		} else {
			$right_arr[] = $arr[$i];
		}
	} 
	$left_arr		= quickSort($left_arr);
	$right_arr    	= quickSort($right_arr);
	return array_merge($left_arr, array($key), $right_arr);
}

function showCheck($v){
	switch ($v){
		case 0:
			$str = '未审核';
			break;
		case 1:
			$str = '不通过';
			break;
		case 2:
			$str = '通过';
			break;	
	}
	return $str;
}
//万年历排列
function calendar($data, $month, $year){
	$str = '';
	$week = date('w', mktime(0, 0, 0, $month, 1, $year));//得到给定的月份的 1号
	
	$nums=$week+1;
	for ($i=1; $i<=$week; $i++){//输出1号之前的空白日期
		$str.="<td> </td>";
	}
	$num =  cal_days_in_month(CAL_GREGORIAN, $month, $year);
	
	for ($i=1; $i<=$num; $i++){//输出天数信息
		$day = $year.'-'.$month.'-'.sprintf('%02d', $i);
		
		foreach ($data as $v){
			if($v->workday == $day){
				$selected = ($v->tag == 1) ? 'checked' : '';	
				$id = $v->id;
				$tag = $v->tag == 1 ? '0' : '1';
				break;
			}
		}
		$day .= '&nbsp;<input type="checkbox" name="work[]" data="'.$tag.'" value="'.$id.'" '.$selected.'>';
		if ($nums%7==0){//换行处理：7个一行
			$str.="<td>$day</td></tr><tr>";
		}else{
			$str.="<td>$day</td>";
		}
		$nums++;
	}
	return $str;
}

function RecursionRecordTime($newArr, $date, $time_09, $time_12, $time_13, $time_18, &$total){
	sort($newArr);
	if(empty($newArr))
		return ;
	$startTime = strtotime($date.' '.$newArr[0]);
	unset($newArr[0]);
	
	if($startTime <$time_12 && $startTime > $time_09){
		$endTime = $time_12;
		if(isset($newArr[1])){
			$newTime = strtotime($date.' '.$newArr[1]);
			if($newTime < $time_12){
				$endTime = $newTime;
				unset($newArr[1]);
			}
		}
	} else {
		$endTime = $time_18;
		if(isset($newArr[1])){
			$endTime = strtotime($date.' '.$newArr[1]);
			unset($newArr[1]);
		}	
	}
	
	$total -= ($endTime - $startTime)/60;
		
	RecursionRecordTime($newArr, $date, $time_09, $time_12, $time_13, $time_18, $total);
}
//分表
function subTable($serverId, $table, $sum){
    if($serverId == 0)
        return $table;
	$suffix = $serverId%$sum;
	$s = sprintf('%03d', $suffix);
	return $table.$s;
}
//相加
function addTogether($info){
    if(is_numeric($info))
        return sprintf("%.2f", $info);;
    if(empty($info) && !is_array($info))
        return "0.00";
    $val = 0;
    foreach ($info as $v){
        $val += $v;
    }
    return sprintf("%.2f", $val);
}
//数组排序输出
function sortOutput($info){
    if(!is_array($info))
        return $info;
    sort($info);
    return implode('；', $info);
}
