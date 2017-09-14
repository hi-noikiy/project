<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 修改
* ==============================================
* @date: 2015-5-25
* @author: Administrator
* @return:
*/
include('../common.inc.php');
/*
 * 用户登录信息
 */
if(!isset($_SESSION))
	@session_start();

if($_SESSION["ADMIN_ID"]=="" && !$noLocation){
   session_unset($_SESSION["ADMIN_ID"]);
   session_unset($_SESSION["role"]);
   session_unset($_SESSION["first"]);
   go('login.php');
}
$admin_folder=true;
//需要特殊显示的类
$ar = array(
		'leave',
		'hugh',
		'sign',
		'overtime',
		'outrecord',
		'oddtime',
		'leave_filing'
	);
//单子截止日期
define("C_DATE",'7');
//单子截止日期文字表达
define("C_LANG",'时间范围：若当日日期小于或等于'.C_DATE.'号可补上个月的调休时间，否则只能补当月调休时间<br/>&nbsp;&nbsp;调休时间,加班时间和指纹异常单时间段申请日期不能超过7天(不计算周末)');
?>