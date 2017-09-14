<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 修改
* ==============================================
* @date: 2015-5-25
* @author: luoxue
* @return:
*/
$fromTime = mysql_real_escape_string($_POST['fromTime'] ? $_POST ['fromTime'] : $_GET ['fromTime'] );
$toTime = mysql_real_escape_string ($_POST ['toTime'] ? $_POST ['toTime'] : $_GET ['toTime']);
$uid = mysql_real_escape_string($_POST ['uid'] ? $_POST ['uid'] : $_GET ['uid']);
$className = $_GET ['cn'];
$classStr = $GLOBALS['_type'][$className];
if($className=='record') {
	if(!$_GET['fromTime'])
		$_GET['fromTime']=date ('Y-m')."-01";
	if(!$_GET['toTime'])
		$_GET['toTime']=date('Y-m-d');
}
$class = new $className();
//$class->setKw($_GET);
$class->p=$_GET['p'];
$personalArr=array('hugh','overtime','leave','sign','outrecord','leave_filing');
$personalIdArr=array(
		'hugh' 		=>'73',
		'overtime' 	=>'72',
		'leave' 	=>'74',
		'sign' 		=>'75',
		'outrecord' =>'76' 
);
if(in_array($className, $personalArr)) {
	$no = strtr($className, $personalIdArr);
	$is_search1 = mysql_fetch_assoc(mysql_query ("select id from _sys_group_perm where perm_id='$no' and admin_id='" . $_SESSION ['ADMIN_ID'] . "'"));
	$is_search2 = mysql_fetch_assoc(mysql_query ("select id from _sys_group_perm where group_id='" . $_SESSION ['gpid'] . "' and perm_id='$no'"));
	if ($is_search1 ['id'] || $is_search2 ['id']) {
		$allow_show = true;
		if (!$fromTime)
			$fromTime=date('Y-m-').'01';
		if (!$toTime)
			$toTime=date('Y-m-d');
	}
}
if(in_array($className, $personalArr) && ($_POST ['uid'] || $_GET ['uid'])) {
	$fromTime = mysql_real_escape_string($_POST['fromTime'] ? $_POST ['fromTime'] : $_GET ['fromTime'] );
	$toTime = mysql_real_escape_string ($_POST ['toTime'] ? $_POST ['toTime'] : $_GET ['toTime']);
	$uid = mysql_real_escape_string($_POST ['uid'] ? $_POST ['uid'] : $_GET ['uid']);
	if ($className != 'sign')
		$class->setWhere (" uid='$uid' and fromTime>='$fromTime' and toTime<='$toTime'");
	else
		$class->setWhere (" uid='$uid' and addDate between '$fromTime' and '$toTime'");
	$class->setOrder (' id desc');
	$list = $class->getList();
	$pageCtrl = $class->getPageInfoHTML ( '', "index.php?type=web&do=list&cn=$className&uid=$uid&fromTime=$fromTime&toTime=$toTime&p=" );
	if ($className != 'sign') {
		$sql = "select sum(totalTime) as total from _web_$className where uid='$uid' and fromTime>='$fromTime' and toTime<='$toTime'";
		$result = mysql_fetch_assoc(mysql_query($sql));
		$total = $result ['total'];
		if ($uid == 364 && $className=='overtime')
			$total += 190.2;
		unset ( $result );
	}
} else {
	
	// 需要特殊显示的类 配置文件admin/common.inc.php
	$ar=$GLOBALS['ar'];
	if (in_array($className, $ar) && $_SESSION ['role'] == '3'){
		//106为财务id，请假报备
		if($_SESSION ['ADMIN_ID']!='106' || $className!='leave_filing')
			$class->setWhere(" uid='" . $_SESSION ['ADMIN_ID'] . "'");
		
	}elseif(in_array($className, $ar) && $_SESSION ['role'] == '2') {
		$admin = new admin();
		$depIds = $admin->getInfo($_SESSION ['ADMIN_ID'], 'depId', 'pass');
		$class->setWhere(" uid='" . $_SESSION ['ADMIN_ID'] . "' or depId='$depIds'");
	}
	$where='';
	if(!empty($_POST['fromTime']) && !empty($_POST['toTime']))
		$where.=" fromTime>='$fromTime' and fromTime<='$toTime'";
	if(!empty($_POST['uid']))
		$where.=" and uid='$uid'";
	$class->setWhere($where);
	if ($className == 'record') {
		$admin = new admin();
		$seartag = $admin->getInfo($_SESSION ['ADMIN_ID'], '', 'pass');
		$is_sear = mysql_fetch_assoc(mysql_query( "select id from _sys_group_perm where perm_id='77' and admin_id='" . $_SESSION ['ADMIN_ID'] . "'"));
		// 普通员工
		if ($seartag['seartag'] != '1' && ! $is_sear ['id']){
			$condition="card_id = '" . $seartag ['card_id'] . "'";
			if(!empty($fromTime) && !empty($toTime))
				$condition.=" and recorddate>='$fromTime' and recorddate<='$toTime'";
			$class->setWhere($condition);
		}	
		if ($is_sear ['id']) {
			$query = mysql_query ( "select real_name from _sys_admin where depId='" . $_SESSION ['depId'] . "'" );
			while (@$rs = mysql_fetch_assoc($query)) {
				$depname [] = $rs;
			}
			
			foreach ( $depname as $val ) {
				$str .= "'" . $val ['real_name'] . "',";
			}
			$str = substr ( $str, 0, - 1 );
			$class->setWhere ( "name in ($str)" );
		}
		//$class->setWhere("gong_id != '0' and card_id!='5326068'"); // 5326068为老大卡
		if(!empty($_GET['card_id']))
			$class->setWhere (" card_id='{$_GET['card_id']}' and recorddate>='$fromTime' and recorddate<='$toTime'");
		else 
			$class->setWhere("gong_id != '0' and card_id!='5326068' and recorddate>='$fromTime' and recorddate<='$toTime'"); // 5326068为老大卡
	}
	
	
	
	if ($_GET ['order'])
		$class->setOrder ( $_GET ['order'] );
	$list = $class->getList();
	$pageCtrl = $class->getPageInfoHTML ();
}
?>
<h1 class="title">
	<span><?=$classStr?>列表</span>
</h1>
<div class="pidding_5">
  <?include($_GET['type'].'/'.$className.'.list.php');?>
  <div class="news-viewpage"><?=$pageCtrl?></div>
</div>
<script>
function searchFun(){
	var url=$('#searchForm').attr('action');
	$('#searchForm').find(':input[name]').each(function (){
		if($(this).val()){
			url+='&'+$(this).attr('name')+'='+$(this).val();
		}
	});
	window.location.href=url;
	return false;
}
</script>
<script>
$('input[postType]').blur(function (){
	var param={};
	param[$(this).attr('name')]=$(this).val();
	$.post('command.php?action=edit&type='+$(this).attr('postType')+'&id='+$(this).attr('postId'),param,function (){ })
})
</script>