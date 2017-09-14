<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 账号管理优化
* ==============================================
* @date: 2015-6-23
* @author: Administrator
* @return:
*/
$dep=new department();
$depList=$dep->getArray('pass');
$depId=$_REQUEST['depId'];
$uid=$_REQUEST['uid'];

$admin=new admin();

if($depId){
	$adminS = new admin();
	$adminS->wheres="depId='$depId'";
	$userList=$adminS->getList();
	$admin->setWhere("_sys_admin.depId='$depId'");
}
if($uid)
	$admin->setWhere("_sys_admin.id='$uid'");

$admin->setOrder('_sys_admin.depId');
$admin->p=$_GET['p'];
$userlist=$admin->getList();
$pageCtrl=$admin->getPageInfoHTML();

?>
<h1 class="title"><span>用户列表</span></h1>
<div class="pidding_5">
	<form action="index.php?type=system&do=user" method="post">
  	<div class="search">
   		<a href="index.php?type=system&do=userinfo">添加用户</a>&nbsp;&nbsp;&nbsp;&nbsp;
   		<span>部门:</span>
		<select name="depId" id="departmentSelect">
			<option value=''>请选择...</option>
	        <? foreach ($depList as $v){?>
				<option value='<?=$v['id'] ?>' <? if($depId==$v['id']){ echo 'selected';} ?>><?=$v['name']?></option>
			<? }?>
		</select>
		<span>姓名:</span> 
	    <select name="uid" id="uidSelect">
	        <option value=''>请选择...</option>
	       	<? foreach ($userList as $v){ ?>
	        	<option value='<?=$v['id']?>' <? if($uid==$v['id']){ echo 'selected';} ?>><?=$v['real_name'] ?></option>
	        <? } ?>
		</select>
		<input type="submit" value="查询" class="sub2" name="sel"/>
  </div>
  </form>
  <table cellspacing="0" cellpadding="0" class="Admin_L">
    <tr>	
      	<th scope="col" class="T_title">用户名</th>
      	<th scope="col">姓名</th>
      	<th scope="col">所属部门</th>
      	<th scope="col">岗位</th>
      	<th scope="col">卡号</th>
      	<th scope="col">部门主管</th>
      	<th scope="col">查询所有审核</th>
      	<th scope="col">权限群组</th>
      	<th scope="col">操作</th>
    </tr>
    <?foreach($userlist as $user){?>
    <tr class="Ls2">
      <td class="N_title"><?=$user['login_name']?></td>
      <td><?=$user['real_name']?></td>
      <td><?php
              $dep= new department();
              echo $dep->getInfo($user['depId'],"name",'pass');
              ?>
      </td>
      <td><?php
              $job= new job();
              echo $job->getInfo($user['jobId'],"name",'pass');
          ?>
      </td>
      <td><?=$user['card_id']?></td>
      <td><?php
              echo $user['depMax']?'是':'否';
              ?>
      </td>
      <td><?php
              echo $user['seartag']?'是':'否';
              ?>
      </td>
      <td><?=$user['gp_name']?></td>
      <td class="E_bd"><a target="_blank" href="kaohe_qx.php?admin_user=<?=$user['id']?>">考核权限</a> |<a href="index.php?type=system&do=userinfo&id=<?=$user['id']?>">编辑</a> | <a href="index.php?type=system&do=user_perm&admin_id=<?=$user['id']?>">私有權限</a><?if($user['id']>99){?> | <a href="javascript:;" onclick="delFun('admin','<?=$user['id']?>')">删除</a><?}?></td>
    </tr>
    <?}?>
  </table>
  <div class="news-viewpage"><?=$pageCtrl?></div>
  </div>
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
