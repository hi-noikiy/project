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
$depId=mysql_real_escape_string($_POST['depId']?$_POST['depId']:$_GET['depId']);
$uid = mysql_real_escape_string($_POST ['uid'] ? $_POST ['uid'] : $_GET ['uid']);


$className = $_GET ['cn'];
$classStr = $GLOBALS['_type'][$className];
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
	
	if ($is_search1 ['id'] || $is_search2 ['id'])
		$allow_show = true;
}
/*
 * role 1总经理、2主管、 3是员工
 * 审核流程：员工->主管->财务->总经理
 * 由于财务是role为3，所以要特殊处理
 */
$ar=$GLOBALS['ar'];


$dep = new department();
$user=new admin();

if($_SESSION['role']=='3'){
	//106为财务id，请假报备
	/* if($className!='record'){
		if($_SESSION ['ADMIN_ID']!='106' || $className!='leave_filing')
			$class->setWhere("uid='" . $_SESSION ['ADMIN_ID'] . "'");
	} */
	//record 打卡查询表没有uid
	if(!$uid && !$depId && $className!='record' &&$_SESSION ['ADMIN_ID']!='106'){
		$class->setWhere("uid='" . $_SESSION ['ADMIN_ID'] . "'");
	}
	
}elseif ($_SESSION['role']==2){

	$userInfo = $user->getInfo ($_SESSION ['ADMIN_ID'],'', 'pass');
	$dep->setWhere('id='.intval($userInfo['depId']));
	
	
}
if($_SESSION['role']!='3' || $_SESSION['ADMIN_ID']=='106')
	$depList=$dep->getArray('pass');
/*搜索
 * record 打卡查询 没有 fromTime字段，只有recorddate字段
 * sign 签呈管理 只有addDate
 * 
 */
if(!empty($fromTime) && !empty($toTime)){
	if($className=='sign')
		$class->setWhere("addDate between '$fromTime' and '$toTime'");	
	elseif($className=='record')
		$class->setWhere("recorddate between '$fromTime' and '$toTime'");		
	else
		$class->setWhere("fromTime>='$fromTime' and fromTime<='$toTime'");
}


if(!empty($uid) && $className!='record')
	$class->setWhere("uid='$uid'");
elseif($_SESSION['role']==2 && !$depId && $className!='record')
	$class->setWhere("uid='{$_SESSION ['ADMIN_ID']}'");
if(!empty($depId) && $className!='record'){
	$class->setWhere("depId='$depId'");
	$user->setWhere("depId='$depId'");
	$userList=$user->getList();
}
$class->setOrder('id desc');

if($className=='record'){
	$admin = new admin();
	$seartag = $admin->getInfo($_SESSION ['ADMIN_ID'], '', 'pass');
	
	if(empty($fromTime) && empty($toTime)){
		$fromTime = date('Y-m')."-01";
		$toTime = date('Y-m-d');
		$class->setWhere("recorddate between '$fromTime' and '$toTime'");
	}
	//record 搜索
	if(!empty($uid)){
		$user_info=$admin->getRowArray($uid);
		$class->setWhere("card_id='{$user_info['card_id']}'");
	}
	
	$user->setWhere("depId='{$user_info['depId']}'");
	$userList=$user->getList();
	// 普通员工
	$cardId=$_POST['card_id']?$_POST['card_id']:$_GET['card_id'];
	
	if ($seartag['seartag'] != '1' && empty($uid))	
		$class->setWhere("card_id='{$seartag ['card_id']}'");
		
	//5326068为老大卡
	$class->setWhere("gong_id != '0' and card_id!='5326068'");
	
	
	if(isset($cardId) && !empty($cardId))
		$class->setWhere("card_id='$cardId'");
	
	//print_r($class->wheres);
}
//请假报备leave_filing 按部门排列
if($className=='leave_filing'){
	if(!empty($fromTime) || !empty($toTime))
		$class->setOrder('depId asc,uid asc, id desc');
	else 
		$class->setOrder('id desc');
}

$list = $class->getList();
if($className=='record')
	$pageCtrl = $class->getPageInfoHTML();
else 
	$pageCtrl = $class->getPageInfoHTML('', "index.php?type=web&do=list&cn=$className&uid=$uid&depId=$depId&fromTime=$fromTime&toTime=$toTime&p=");
if(!$fromTime)
 	$fromTime=date ('Y-m')."-01";
if(!$toTime)
	$toTime=date('Y-m-d');

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

<script type="text/javascript">
$(function(){
	var $department=$("#departmentSelect");
	var $uidSelect=$("#uidSelect");
	$department.change(function(){
		var depId=$(this).val();
		$.ajax( { 
		    url:'ajax/getUser.php',  
		    data:{depId:depId},    
		    type:'post',    
		    cache:false,    
		    dataType:'json',    
		    success:function(data) {
			    var str="<option value=''>请选择...</option>";
			    for(var i=0; i<data.length; i++){
					str+="<option value='"+data[i].id+"'>"+data[i].real_name+"</option>";
				}
			    $uidSelect.html(str);
		     },    
		     error : function() {
		          alert("异常！");    
		     }    
		});  
	});
});
</script>




