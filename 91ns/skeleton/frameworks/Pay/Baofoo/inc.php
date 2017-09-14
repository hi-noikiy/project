<?php 
	header("Content-type: text/html; charset=utf-8");

	require "baofoosdk/baofoofisdk.php";

	// 测试用商户信息，秘钥信息为测试用
	// 正式环境下使用秘钥，可自行加密保存读取至变量操作，防止代码泄露导致秘钥流外造成损失
        
        //测试环境
	//$baofooFiService = new BaofooFiService(100000178, 10000001, 'abcdefg', true);
        
        //正式环境
        $baofooFiService = new BaofooFiService(686839, 28047, 'wy9l26x69nctlfbu', false);

?>