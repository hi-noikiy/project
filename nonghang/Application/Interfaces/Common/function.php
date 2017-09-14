<?php
// +----------------------------------------------------------------------
// | 系统公共库文件
// +----------------------------------------------------------------------
// | 中瑞卷管理系统
// +----------------------------------------------------------------------
// | Author: 嗄沬 280708784@qq.com
//

/**
 * 验证tokenId
 * @param  string $tokenId
 * @return bool 
 */
function checkToken($param){
    $tokenId = $param['tokenId']; 
    $cacheValue = S('APPINFOtokenId_' . $tokenId);
    return $cacheValue ? true : false;
}



function getCacheName($params, &$cacheName = '')
{
	foreach ($params as $key => $value) {
		if (is_array($value)) {
			getCacheName($value, $cacheName);
		}else if(mb_strlen($value) < 80){
			// wlog($key . '--' . $value, 'testLog');
			$cacheName .= $value;
		}
	}
	// wlog('ALL:' . $cacheName, 'testLog');
	return $cacheName;
}

function getKeyInfo($str,$length){
	$str=substr($str, $length);
	$str=substr($str, 0,-$length+1);
	$str = preg_replace("/\t/","",$str); //使用正则表达式替换内容，如：空格，换行，并将替换为空。
	$str = preg_replace("/\r\n/","",$str);
	$str = preg_replace("/\r/","",$str);
	$str = preg_replace("/\n/","",$str);
	$str = preg_replace("/ /","",$str);
	return $str;
}


function getAppInfo()
{	
	$tokenId = session('tokenId');
	$appInfo = S('APPINFOUserInfotokenId_' . $tokenId);
	return $appInfo;
}


//获取当前可用支付方式
function getNowPayWay($param, $type)
{


	$appInfo = S('APPINFOUserInfotokenId_' . $param['tokenId']);
	$groupPayWay = json_decode($appInfo['cinemaGroupInfo']['payWay'], true);
	$groupAppPayWay = $groupPayWay['appPayWay'];



	$cinemaAllPayWay = D('Cinema')->getCinemaInfoBycinemaCode('payWay', $param['cinemaCode']);
	$cinemaAllPayWay = json_decode($cinemaAllPayWay['payWay'], true);
	$cinemaAppPayWay = $cinemaAllPayWay['appPayWay'];


	$getNowPayWay = array_intersect($groupAppPayWay,$cinemaAppPayWay); 
	// print_r($getNowPayWay);
	if ($type == 'recharge') {
		$config = array('account','exchange','reduce','integral', 'sale');
		foreach ($getNowPayWay as $key => $value) {
			if (in_array($value, $config)) {
				unset($getNowPayWay[$key]);
			}
		}
	}else if ($type == 'film') {
		$config = array('sale');
		foreach ($getNowPayWay as $key => $value) {
			if (in_array($value, $config)) {
				unset($getNowPayWay[$key]);
			}
		}
	}else if ($type == 'goods') {
		$config = array('exchange', 'reduce');
		foreach ($getNowPayWay as $key => $value) {
			if (in_array($value, $config)) {
				unset($getNowPayWay[$key]);
			}
		}
	}


	return array_merge($getNowPayWay);
	
}