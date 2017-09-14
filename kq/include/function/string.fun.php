<?php
/*
 * 字符集转换
 */
function toutf8($str){
	return iconv('GB18030','UTF-8',$str);
}
function togb2312($str){
	return iconv('UTF-8','GB18030',$str);
}
/*
 * 截取字符串
 */
function substrs($content,$length){
	mb_internal_encoding('utf-8');
	if($length && mb_strlen($content)>($length)){
		$content=mb_substr($content,0,$length).'..';
	}
	return $content;
}
/*
 function substrs($content,$length){
	mb_internal_encoding('utf-8');
	$str=$content;
	$content=strip_tags($content);
	if($length && mb_strlen($content)>($length)){
	$newcontent=mb_substr($content,0,$length).'..';
	}
	$restr=str_replace($content,$newcontent,$str);
	return $restr;
	}
	*/
function subHtml($str,$num,$more=false){
	$leng=strlen($str);
	if($num>=$leng) return $str;
	$word=0;
	$i=0;                        /** 字符串指针 **/
	$stag=array(array());        /** 存放开始HTML的标志 **/
	$etag=array(array());        /** 存放结束HTML的标志 **/
	$sp = 0;
	$ep = 0;
	while($word!=$num){
		if(ord($str[$i])>128){
			//$re.=substr($str,$i,3);
			$i+=3;
			$word++;
		}else if ($str[$i]=='<'){
			if ($str[$i+1] == '!'){
				$i++;
				continue;
			}

			if ($str[$i+1]=='/'){
				$ptag=&$etag ;
				$k=&$ep;
				$i+=2;
			}else{
				$ptag=&$stag;
				$i+=1;
				$k=&$sp;
			}

			for(;$i<$leng;$i++){
				if ($str[$i] == ' '){
					$ptag[$k] = implode('',$ptag[$k]);
					$k++;
					break;
				}
				if ($str[$i] != '>'){
					$ptag[$k][]=$str[$i];
					continue;
				}else{
					$ptag[$k] = implode('',$ptag[$k]);
					$k++;
					break;
				}
			}
			$i++;
			continue;
		}else{
			//$re.=substr($str,$i,1);
			$word++;
			$i++;
		}
	}
	foreach ($etag as $val){
		$key1=array_search($val,$stag);
		if ($key1 !== false) unset($stag[$key]);
	}
	foreach ($stag as $key => $val){
		if (in_array($val,array('br','img'))) unset($stag[$key1]);
	}
	array_reverse($stag);
	$ends = '</'.implode('></',$stag).'>';
	$re = substr($str,0,$i).$ends;
	if($more) $re.='...';
	return $re;
}
?>