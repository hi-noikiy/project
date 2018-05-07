<?php
/**
 * ==============================================
 * Copyright (c) 2015 All rights reserved.
 * ----------------------------------------------
 * 未读列表
 * ==============================================
 * @date: 2015-5-29
 * @author: Administrator
 * @return:
 */
$className='unread';
$class=new $className();

/*查询审批权限*/
function getPer($id){
	$sql="select id from _sys_group_perm where admin_id='$_SESSION[ADMIN_ID]' and perm_id='$id'";
	$result=mysql_fetch_assoc(mysql_query($sql)); 
	return ($result['id'])?true:false;
}
function uptList($ary,$tmp){
	$class=new $tmp;
	$now=date("Y-m-d H:i:s");
	if($ary){
		foreach ($ary as $val){
			$sql="select depId from _web_$tmp where id='$val'";
			$id=mysql_fetch_assoc(mysql_query($sql));
			if($id['depId']!='2'){//2为总办，不属于总办的只更新总经理审核
				$sql="update _web_$tmp set manTag='2',manTime='$now' where id='$val'";
				mysql_query($sql);
				//echo $sql,'<br/>';
			} else {
				$sql="update _web_$tmp set manTag='2',depTag='2',perTag='2',depTime='$now',perTime='$now',manTime='$now' where id='$val'";
				mysql_query($sql);
				//echo $sql,'<br/>';
			}
			if($id['depId']!='2'){
				if ($tmp=='overtime')
					$sql="select id,uid,depId,fromtime,totime,totalTime,hour_s,minute_s,hour_e,minute_e,addtag,addDate,manTag,available from _web_$tmp where id='$val'";
				else if($tmp=='oddtime')
					$sql="select id,uid,depId,amstart,amend,pmstart,pmend,addtime,supdate,addDate,manTag,available from _web_$tmp where id='$val'";
				else if($tmp=='hugh')
					$sql="select id,uid,depId,fromtime,totime,totalTime,hour_s,minute_s,hour_e,minute_e,addtag,addDate,manTag,available from _web_$tmp where id='$val'";
				else
					$sql="select id,uid,depId,addDate,manTag,available from _web_$tmp where id='$val'";
			}else
				$sql="select * from _web_$tmp where id='$val'";
			$list=mysql_fetch_assoc(mysql_query($sql));
			$class->edit($list,$val);
		}
	}
}
//财务一键审核
function aKeyFinance($ary,$tmp){
	$now=date("Y-m-d H:i:s");
	if($ary){
		foreach ($ary as $v){
			$sql="update _web_$tmp set perTag='2',perTime='$now' where id='$v'";
			mysql_query($sql);
		}
	}
}
$sign=getPer('78');
$outrecord=getPer('79');
$oddtime=getPer('80');
$overtime=getPer('81');
$leave=getPer('82');
$hugh=getPer('83');
/**/
if($_POST){
	if($_POST['sign']){//签呈
		uptList($_POST['sign'],'sign');
	}
	if($_POST['outrecord']){//公出
		uptList($_POST['outrecord'],'outrecord');
	}
	if($_POST['oddtime']){//异常
		uptList($_POST['oddtime'],'oddtime');
	}
	if($_POST['overtime']){//加班
		uptList($_POST['overtime'],'overtime');
	}
	if($_POST['leave']){//请假
		if($_SESSION['ADMIN_ID']==106)
			aKeyFinance($_POST['leave'], 'leave');
		else
			uptList($_POST['leave'],'leave');
	}
	if($_POST['hugh']){//调休
		if($_SESSION['ADMIN_ID']==106)
			aKeyFinance($_POST['hugh'], 'hugh');
		else 
			uptList($_POST['hugh'],'hugh');
	}
}

?>
<form action="" method="post">
<h1 class="title">
	<span>待处理&nbsp;&nbsp;&nbsp;
		<? if($sign||$outrecord||$oddtime||$overtime||$leave||$hugh||$_SESSION['ADMIN_ID']=='106'){?>
			<input type="submit" value="批量审批提交" onclick="return confirm('确认提交?')" />
		<? }?>
	</span>
</h1>
<!-- 签呈申请列表  -->
<?
    $list_sign = getListByCN('sign',$_SESSION['role'],$_SESSION['ADMIN_ID']);
    if($list_sign){
?>
<h1 class="title"><span>签呈申请列表</span></h1>
<div class="pidding_5">
	<table cellspacing="0" cellpadding="0" class="Admin_L">
	    <tr>
		  	<? if($sign){?>
	      	<th scope="col" class="T_title">
	      		<input type="radio" name="sign_check" onclick="checkAll('sign[]')" />全选
	      		<input type="radio" name="sign_check" onclick="uncheckAll('sign[]')" />全不选
	      	</th>
	      	<? }?>
	      	<th scope="col" class="T_title">签呈人</th>
	      	<th scope="col" class="T_title">部门</th>
	      	<th scope="col">时间</th>
	      	<th scope="col">部门审核</th>
	      	<th scope="col">人事审核</th>
	      	<th scope="col" >总经理审核</th>
	      	<th scope="col">操作</th>
	    </tr>
	    <?
	        $dep = new department();
	        $admin = new admin();
	        foreach($list_sign as $val){
	    ?>
	    <tr class="Ls2">
		    <? if($sign){?>
		    <td class="N_title"><input type="checkbox" name="sign[]" value="<?=$val['id']?>" /></td>
		    <? }?>
	        <td class="N_title"><?=$admin->getInfo($val['uid'],'real_name','pass')?></td>
	        <td class="N_title"><?=$dep->getInfo($val['depId'],'name','pass')?></td>
	      	<td><?=$val['addDate']?></td>
	      	<td><?=$val['depTag']=='0'?'未审核':($val['depTag']=='1'?'不通过':'通过')?></td>
	      	<td><?=$val['perTag']=='0'?'未审核':($val['perTag']=='1'?'不通过':'通过')?></td>
	      	<td><?=$val['manTag']=='0'?'未审核':($val['manTag']=='1'?'不通过':'通过')?></td>
	      	<td class="E_bd">
	      		<a href="index.php?type=web&do=info&cn=sign&id=<?=$val['id']?>&issee=1">查看</a>
	      	</td>
	    </tr>
	    <? }?>
	</table>
  	<div class="news-viewpage"><?//=$pageCtrl?></div>
</div>
<?}?>
<!-- 公出单列表  -->
<?
	$list_out = getListByCN('outrecord',$_SESSION['role'],$_SESSION['ADMIN_ID']);
    if($list_out){
?>
<h1 class="title"><span>公出单列表</span></h1>
<div class="pidding_5">
  	<table cellspacing="0" cellpadding="0" class="Admin_L">
	    <tr>
	      	<? if($outrecord){?>
	      	<th scope="col" class="T_title">
	      		<input type="radio" name="outrecord_check" onclick="checkAll('outrecord[]')" />全选
	      		<input type="radio" name="outrecord_check" onclick="uncheckAll('outrecord[]')" />全不选
	      	</th>
	      	<? }?>
	      	<th scope="col" class="T_title">员工</th>
	      	<th scope="col" class="T_title">部门</th>
	      	<th scope="col">时间</th>
	      	<th scope="col">部门审核</th>
	      	<th scope="col">人事审核</th>
	      	<th scope="col" >总经理审核</th>
	      	<th scope="col">操作</th>
	    </tr>
	    <?
	        $dep = new department();
	        $admin = new admin();
	        foreach($list_out as $val){
	    ?>
	    <tr class="Ls2">
	    	<? if($outrecord){?>
	    		<td class="N_title"><input type="checkbox" name="outrecord[]" value="<?=$val['id']?>" /></td>
	    	<? }?>
	        <td class="N_title"><?=$admin->getInfo($val['uid'],'real_name','pass')?></td>
	        <td class="N_title"><?=$dep->getInfo($val['depId'],'name','pass')?></td>
	      	<td><?=$val['fromTime'].' '.$val['hour_s'].':'.$val['minute_s']."~".$val['toTime'].' '.$val['hour_e'].':'.$val['minute_e']?></td>
	      	<td><?=$val['depTag']=='0'?'未审核':($val['depTag']=='1'?'不通过':'通过')?></td>
	      	<td><?=$val['perTag']=='0'?'未审核':($val['perTag']=='1'?'不通过':'通过')?></td>
	      	<td><?=$val['manTag']=='0'?'未审核':($val['manTag']=='1'?'不通过':'通过')?></td>
	      	<td class="E_bd">
	      		<a href="index.php?type=web&do=info&cn=outrecord&id=<?=$val['id']?>&issee=1">查看</a>
	      	</td>
	    </tr>
    	<? }?>
  	</table>
  	<div class="news-viewpage"><?//=$pageCtrl?></div>
</div>
<? }?>
<!-- 异常单列表  -->
<?
    $list_odd = getListByCN('oddtime',$_SESSION['role'],$_SESSION['ADMIN_ID']);
    if($list_odd){
?>
<h1 class="title"><span>异常单列表</span></h1>
<div class="pidding_5">
  	<table cellspacing="0" cellpadding="0" class="Admin_L">
	    <tr>
	      	<? if($oddtime){?>	
      		<th scope="col" class="T_title">
      			<input type="radio" name="oddtime_check" onclick="checkAll('oddtime[]')" />全选
      			<input type="radio" name="oddtime_check" onclick="uncheckAll('oddtime[]')" />全不选
      		</th>
      		<? }?>
	      	<th scope="col" class="T_title">姓名</th>
	      	<th scope="col" class="T_title">部门</th>
	      	<th scope="col">时间</th>
	      	<th scope="col">部门审核</th>
	      	<th scope="col">人事审核</th>
	      	<th scope="col">总经理审核</th>
	      	<th scope="col">状态</th>
	      	<th scope="col">操作</th>
	    </tr>
	    <?
	        $dep = new department();
	        $admin = new admin();
	        foreach($list_odd as $val){
	    ?>
	    <tr class="Ls2">
	      	<? if($oddtime){?>
	      	<td class="N_title"><input type="checkbox" name="oddtime[]" value="<?=$val['id']?>" /></td>
	      	<? }?>
	      	<td class="N_title"><?=$admin->getInfo($val['uid'],'real_name','pass')?></td>
	      	<td class="N_title"><?=$dep->getInfo($val['depId'],'name','pass')?></td>
	      	<td><?=$val['supdate'].' '.$val['addtime']?></td>
	      	<td><?=$val['depTag']=='0'?'未审核':($val['depTag']=='1'?'不通过':'通过')?></td>
	      	<td><?=$val['perTag']=='0'?'未审核':($val['perTag']=='1'?'不通过':'通过')?></td>
	      	<td><?=$val['manTag']=='0'?'未审核':($val['manTag']=='1'?'不通过':'通过')?></td>
	      	<td><?=$val['available']=='1'?'有效':'<font color="red">无效</font>'?></td>
	      	<td class="E_bd">
	      		<a href="index.php?type=web&do=info&cn=oddtime&id=<?=$val['id']?>&issee=1">查看</a>
	      	</td>
	    </tr>
    	<? }?>
  	</table>
  	<div class="news-viewpage"><?//=$pageCtrl?></div>
</div>
<?}?>
<!-- 加班申请列表 -->
<?
	$list_overtime = getListByCN('overtime',$_SESSION['role'],$_SESSION['ADMIN_ID']);
    if($list_overtime){
?>
<h1 class="title"><span>加班申请列表</span></h1>
<div class="pidding_5">
  	<table cellspacing="0" cellpadding="0" class="Admin_L">
	    <tr>
	      	<? if($overtime){?>
	      	<th scope="col" class="T_title">
	      		<input type="radio" name="overtime_check" onclick="checkAll('overtime[]')" />全选
	      		<input type="radio" name="overtime_check" onclick="uncheckAll('overtime[]')" />全不选
	      	</th>
	      	<? }?>
	      	<th scope="col" class="T_title">姓名</th>
	      	<th scope="col" class="T_title">部门</th>
	      	<th scope="col">时间</th>
	      	<th scope="col">指纹时间</th>
			<th scope="col">理由</th>
	      	<th scope="col" >状态</th>
	      	<th scope="col">操作</th>
	    </tr>
	    <?
	        $dep = new department();
	        $admin = new admin();
	        foreach($list_overtime as $val){
	    ?>
<script>
function goth(id,perTag){
	$.get('ajax/goth.php',{id:id,perTag:perTag},function(json){location.reload()})
}
</script>
	    <tr class="Ls2">
	        <? if($overtime){?>
	    	<td class="N_title"><input type="checkbox" name="overtime[]" value="<?=$val['id']?>" /></td>
	    	<? }?>
	        <td class="N_title"><?=$admin->getInfo($val['uid'],'real_name','pass')?></td>
	        <td class="N_title"><?=$dep->getInfo($val['depId'],'name','pass')?></td>
	      	<td><?=$val['fromTime'].' '.$val['hour_s'].':'.$val['minute_s']."~".$val['toTime'].' '.$val['hour_e'].':'.$val['minute_e']?></td>
	      	<td><?php echo searzhiwentime($val['fromTime'],$val['toTime'],$val['uid']);?></td>
			<td><?=$val['reason']?></td>
	      	<td><?=$val['available']=='1'?'有效':'<font color="red">无效</font>'?></td>
	      	<td class="E_bd">
			<?php
              	if($_SESSION['ADMIN_ID'] == $personnelId){
          	?>
	      	<a href="javascript:;" onclick="goth(<?=$val['id']?>,'2')">通过</a>
			<?php
              	}
          	?>
			<a href="index.php?type=web&do=info&cn=overtime&id=<?=$val['id']?>&issee=1">查看</a>
	      	</td>
	    </tr>
    	<? }?>
	</table>
  	<div class="news-viewpage"><?//=$pageCtrl?></div>
</div>
<? }?>
<!-- 请假申请列表 -->
<?
	$list_leave = getListByCN('leave',$_SESSION['role'],$_SESSION['ADMIN_ID']);
	if($list_leave){
?>
<h1 class="title"><span>请假申请列表</span></h1>
<div class="pidding_5">
  	<table cellspacing="0" cellpadding="0" class="Admin_L">
	    <tr>
	      <? if($leave || $_SESSION['ADMIN_ID']==106){?>
	      <th scope="col" class="T_title">
	      	<input type="radio" name="leave_check" onclick="checkAll('leave[]')" />全选
	      	<input type="radio" name="leave_check" onclick="uncheckAll('leave[]')" />全不选
	      </th>
	      <? }?>
	      <th scope="col" class="T_title">姓名</th>
	      <th scope="col" class="T_title">部门</th>
	      <th scope="col">时间</th>
	      <th scope="col">部门审核</th>
	      <th scope="col">人事审核</th>
	      <th scope="col" >总经理审核</th>
	      <th scope="col" >状态</th>
	      <th scope="col">操作</th>
	    </tr>
	    <?
	        $dep = new department();
	        $admin = new admin();
	        foreach($list_leave as $val){
	    ?>
	    <tr class="Ls2">
	    	<? if($leave || $_SESSION['ADMIN_ID']==106){?>
			<td class="N_title"><input type="checkbox" name="leave[]" value="<?=$val['id']?>" /></td>
			<? }?>
	        <td class="N_title"><?=$admin->getInfo($val['uid'],'real_name','pass')?></td>
	        <td class="N_title"><?=$dep->getInfo($val['depId'],'name','pass')?></td>
	      	<td><?=$val['fromTime'].' '.$val['hour_s'].':'.$val['minute_s']."~".$val['toTime'].' '.$val['hour_e'].':'.$val['minute_e']?></td>
	      	<td><?=$val['depTag']=='0'?'未审核':($val['depTag']=='1'?'不通过':'通过')?></td>
	      	<td><?=$val['perTag']=='0'?'未审核':($val['perTag']=='1'?'不通过':'通过')?></td>
	      	<td><?=$val['manTag']=='0'?'未审核':($val['manTag']=='1'?'不通过':'通过')?></td>
	      	<td><?=$val['available']=='1'?'有效':'<font color="red">无效</font>'?></td>
	      	<td class="E_bd">
	      		<a href="index.php?type=web&do=info&cn=leave&id=<?=$val['id']?>&issee=1">查看</a>
	      	</td>
	    </tr>
    	<? }?>
	</table>
  	<div class="news-viewpage"><?//=$pageCtrl?></div>
</div>
<? }?>
<!-- 调休申请列表 --> 
<?

	$list_hugh = getListByCN('hugh',$_SESSION['role'],$_SESSION['ADMIN_ID']);
    if($list_hugh){
?>
<h1 class="title"><span>调休申请列表</span></h1>
<div class="pidding_5">
  	<table cellspacing="0" cellpadding="0" class="Admin_L">
	    <tr>
	    	<? if($hugh||$_SESSION['ADMIN_ID']==106){?>
		    <th scope="col" class="T_title">
		      	<input type="radio" name="hugh_check" onclick="checkAll('hugh[]')" />全选
		      	<input type="radio" name="hugh_check" onclick="uncheckAll('hugh[]')" />全不选
		      	<input type="radio" name="hugh_check" onclick="checkRed('1')" />选红
		      	<input type="radio" name="hugh_check" onclick="checkRed('0')" />不选红
		     </th>
	      	<? }?>
	      	<th scope="col" class="T_title">姓名</th>
	      	<th scope="col" class="T_title">部门</th>
	      	<th scope="col">时间</th>
	      	<th scope="col">打卡详细</th>
	      	<th scope="col">部门审核</th>
	      	<th scope="col">人事审核</th>
	      	<th scope="col" >总经理审核</th>
	      	<th scope="col" >状态</th>
	      	<th scope="col">操作</th>
	    </tr>
	    <?
	        $dep = new department();
	        $admin = new admin();
	        foreach($list_hugh as $key=>$val){
	        //调休单指纹昨天没有加班到9点加红
	        $lastdayred=getLasttodayzhiwentime(date('Y-m-d',strtotime($val['fromTime'].' -1 day')),date('Y-m-d',strtotime($val['toTime'].' -1 day')),$val['uid']);
	    ?>
	    <tr class="Ls2" <? if($lastdayred){ ?> style="color:red "<? }?>>
	      	<? if($hugh || $_SESSION['ADMIN_ID']==106){?>
	      	<td class="N_title">
	      		<input type="checkbox" id="<?=($lastdayred)?'red'.$key:'blue'.$key; ?>" name="hugh[]" value="<?=$val['id']?>" />
	      	</td>
	      	<? }?>
	      	<td class="N_title"><?=$admin->getInfo($val['uid'],'real_name','pass')?></td>
	      	<td class="N_title"><?=$dep->getInfo($val['depId'],'name','pass')?></td>
	      	<td><?=$val['fromTime'].' '.$val['hour_s'].':'.$val['minute_s']."~".$val['toTime'].' '.$val['hour_e'].':'.$val['minute_e']?></td>
	      	<td>	      	
			    <script language="javascript">
					$(document).ready(function(){
						//示例3，使用弹出层默认特效
						new PopupLayer({trigger:"#ele<?=$val['id']?>",popupBlk:"#blk<?=$val['id']?>",closeBtn:"#close<?=$val['id']?>",useFx:false});
			       	});
				</script>   
		      	<div id="ele<?=$val['id']?>" class="tigger">点击查看</div>
		      	<div id="blk<?=$val['id']?>" class="blk" style="display:none;">
		            <div class="head"><div class="head-right"></div></div>
		            <div class="main">
		                <a href="javascript:void(0)" id="close<?=$val['id']?>" class="closeBtn">关闭</a>
		                <table>
			                <tr>
			                	<td><?php echo '门禁时间:<br/>',seartime($val['fromTime'],$val['toTime'],$val['uid']);  ?></td>
			                	<td><?php echo '指纹时间:<br/>',searzhiwentime($val['fromTime'],$val['toTime'],$val['uid']);?></td>
			                </tr>
			                <tr>
			                	<td><?php echo '昨天门禁时间:<br/>',seartime(date('Y-m-d',strtotime($val['fromTime'].' -1 day')),date('Y-m-d',strtotime($val['toTime'].' -1 day')),$val['uid']); ?></td>
			                	<td><?php  echo '昨天指纹时间:<br/>',searzhiwentime(date('Y-m-d',strtotime($val['fromTime'].' -1 day')),date('Y-m-d',strtotime($val['toTime'].' -1 day')),$val['uid']); ?></td>
			                </tr>
		                	<tr><td colspan="2"><?php echo "注:s后缀表异常时间";?></td> </tr>
		                </table> 
		            </div>
		            <div class="foot"><div class="foot-right"></div></div>
		      	</div>
	      	</td>
	      	<td><?=$val['depTag']=='0'?'未审核':($val['depTag']=='1'?'不通过':'通过')?></td>
	      	<td><?=$val['perTag']=='0'?'未审核':($val['perTag']=='1'?'不通过':'通过')?></td>
	      	<td><?=$val['manTag']=='0'?'未审核':($val['manTag']=='1'?'不通过':'通过')?></td>
	      	<td><?=$val['available']=='1'?'有效':'<font color="red">无效</font>'?></td>
	      	<td class="E_bd">
	      		<a href="index.php?type=web&do=info&cn=hugh&id=<?=$val['id']?>&issee=1">查看</a>
	      	</td>
	    </tr>
		<? }?>
	</table>
  	<div class="news-viewpage"><?//=$pageCtrl?></div>
</div>
<? }?>
</form>
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
function checkAll(val)
{
	var str=document.getElementsByName(val);
    for(var   i=0;i <str.length;i++)  
    {
    str[i].checked=true;  
    } 
}
function uncheckAll(val){
	var str=document.getElementsByName(val);
	for(var   i=0;i <str.length;i++){
		str[i].checked=false;
	}   
}
function checkRed(isRed){
	var id='';
	var another='';
	if(isRed==1){
		id='red';
		another='blue';
	}else{
		id='blue';
		another='red';
	}
	var arr=document.getElementsByTagName("input");
	for(var i=0;i<arr.length;i++){
		if(arr[i].id.indexOf(id)>-1){
			arr[i].checked=true;
		}
	   	if(arr[i].id.indexOf(another)>-1){
		   	arr[i].checked=false;
	   	}
	}
} 
</script>
<script>
$('input[postType]').blur(function (){
	var param={};
	param[$(this).attr('name')]=$(this).val();
	$.post('command.php?action=edit&type='+$(this).attr('postType')+'&id='+$(this).attr('postId'),param,function (){ })
})
</script>