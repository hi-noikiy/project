<?php
/**
 * JS公用代码输出类
 * @author 李鹏飞
 * 2007-09-20 最后更新
 */
class string {
	
	/**
	 * 双字节字符串截取函数
	 *
	 * @param string $str 要截取的字符串
	 * @param integer $start 开始截取的位置
	 * @param integer $len 要截取的长度
	 * @return string 返回截取的字符串
	 */
	public static function csubstr($str,$start,$len){
		$len=$len/2;
		preg_match_all("/[\\x80-\\xff]?./",$str,$arr);
		return  implode(array_slice($arr[0],$start,$len),"");
	}
	
	/**
	 * 将字符串转换为SQL语句中的字符串
	 *
	 * @param string $str 如果PHP 指令 magic_quotes_gpc 为 off时，才处理
	 * @return string
	 */
	public static function quoted($str){
		if (get_magic_quotes_gpc()){
			return $str;
		}else{
			return self::qs($str);
		}
	}
	
	public static function qs($str){
		return addslashes($str);
	}
	
	/**
	 * 将指定数字转为字符串，并加上指定长度的前导0
	 *
	 * @param unknown_type $int
	 * @param unknown_type $length
	 * @return unknown
	 */
	public static function inttostr($int,$length = 6){
		$int = $int.'';
		$length = $length - strlen($int);
		for ($i=1; $i<=$length; $i++){
			$int = '0'.$int;
		}
		return $int;
	}
}


?>