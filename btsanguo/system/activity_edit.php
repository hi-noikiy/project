<?php
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");
include("../inc/config.php");
include("../inc/function.php");
include("inc/game_config.php");
global $activity_type;
$table = 'u_activity';
$event = 'u_wonderfulevent';
$award = 'u_eventaward';

$action = $_POST['action'];
$id = intval($_REQUEST['id']);

$sql = "select * from $table where id=$id";
$rs = array();
$query = @mysql_query($sql);
if($query != false)
	$rs = mysql_fetch_assoc($query);
if($action == 'edit'){
	$type = intval($_POST['event']['event_type']);
	$_POST['event']['icon'] = $activity_type[$type]['icon'];
	if(empty($_POST['event']['icon']))
		ErrMsg('活动类型对应的活动数值不存在！');
	$begin = date('ymd', strtotime($_POST['time_begin']));
	$end = date('ymd', strtotime($_POST['time_end']));
	$time_begin = $begin.$_POST['hour_begin'].$_POST['minute_begin'];
	$time_end = $end.$_POST['hour_end'].$_POST['minute_end'];
	$_POST['event']['time_begin'] = $time_begin;
	$_POST['event']['time_end'] = $time_end;
	
	if($type != $rs['event_type']){
		$sql = "select id from $table where event_type='$type' and time_begin='$time_begin' order by id desc limit 1";
		$query = mysql_query($sql);
		if($query == false)
			ErrMsg('sql执行失败！');
		$rs = mysql_fetch_assoc($query);
		$num = mb_substr($rs['id'], 8);
		$number = $num ? $num+1 : '1';
		if($number > 100)
			ErrMsg('滚号大于100！');
		$_POST['event']['id'] = $begin.sprintf('%02d', $type).sprintf('%02d', $number);
	} else 
		$_POST['event']['id'] = $_POST['id'];
	
	$updateArr = array();
	foreach ($_POST['event'] as $k => $v){
		$updateArr[] = "$k='$v'";
	}
	$field = implode(',', $updateArr);
	$sql = "update $table set $field where id='$id'";
	//开启事务
	mysql_query('START TRANSACTION') or exit(mysql_error());
	if(!mysql_query($sql)){
		mysql_query('ROLLBACK') or exit(mysql_error());
		ErrMsg('活动配置更新失败(activity)！');
	}
	
	$event_sql = "select * from $event where activity_id=$id";
	$event_query = mysql_query($event_sql);
	
	$new_activity_id = $_POST['event']['id'];
	$group = $_POST['event']['award_group'];
	$icon = $_POST['event']['icon'];
	$lockType = $_POST['event']['lock_type'];
	$stepThree = array();
	while (@$row = mysql_fetch_assoc($event_query)){
		$old_event_id = $row['id'];
		$stepThree[] = $old_event_id;
		$event_id = $begin.sprintf('%02d', $type).mb_substr($old_event_id, 8);
		$sql2 = "update $event set 
						id=$event_id, activity_id=$new_activity_id, award_group='$group', 
						event_type='$type', time_begin='$time_begin', time_end='$time_end', 
						icon='$icon', lock_type='$lockType' where id=$old_event_id";
		if(!mysql_query($sql2)){
			mysql_query('ROLLBACK') or exit(mysql_error());
			
			ErrMsg('活动配置更新失败(event)！');
		}
		
		$sql3  = "update $award set id=$event_id, award_group='$group', event_type='$type' where id=$old_event_id";
		
		if(!mysql_query($sql3)){
			mysql_query('ROLLBACK') or exit(mysql_error());
			ErrMsg('活动配置更新失败(award)！');
		}
	}	
	mysql_query('COMMIT') or exit(mysql_error());//执行事务	
	mysql_close();
	goMsg('编辑活动配置成功！', 'activity_edit.php?id='.$new_activity_id);
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<title>编辑</title>
<link href="CSS/Style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="JS/DateTime/DateDialog.js"></script>
<script type="text/javascript" src="JS/jquery-1.3.2.min.js"></script>
<script type="text/javascript">
function CheckForm(){
	var $eventType = $("select[name='event_type']");
	if($eventType.val() == ""){
		alert("活动类型不能为空！");
		$eventType.focus();
		return false;
	}
	var $awardGroup = $("input[name='award_group']");
	if($awardGroup.val() == ''){
		alert("需求条件不能为空！");
		$awardGroup.focus();
		return false;
	}
	var $name = $("input[name='name']");
	if($name.val() == ''){
		alert("名字不能为空！");
		$name.focus();
		return false;
	}
	var $descibe = $("textarea[name='descibe']");
	if($descibe.val() == ''){
		alert("描述不能为空！");
		$descibe.focus();
		return false;
	}
}
</script>
</head>
<body class="main">
<form name="form1" method="POST" onSubmit="return CheckForm();" action="<?=getPath()?>">
<input type="hidden" name="id" value="<?=$rs['id']?>">
<input type="hidden" name="sid" value="<?=$sid?>">
<input type="hidden" name="event[event_data]" value="<?=$rs['event_data']?>">
<input type="hidden" name="event[lock_type]" value="<?=$rs['lock_type']?>">
<input type="hidden" name="event[isnoshow]" value="<?=$rs['isnoshow']?>">
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
	<tr>
      	<th colspan="2" align="center">编辑活动配置</th>
    </tr>
    <tr>
      	<td width="15%" align="right" class="forumRow">起始时间：</td>
      	<td width="85%" class="forumRow">
      		<input name="time_begin" type="text" size="12" value="<?=date('Y-m-d', strtotime('20'.$rs['time_begin'].'00'))?>" onClick="javascript:toggleDatePicker(this)">
      		<select name="hour_begin">
      		<?php
      			$hour = mb_substr($rs['time_begin'], 6, 2);
      			$minute = mb_substr($rs['time_begin'], 8);
      			for($i=0;$i<=23;$i++){
					$i = sprintf("%02d", $i);
					$selected = ($hour == $i) ? 'selected' : '';
      				echo "<option value='$i' $selected>$i</option>";
      			}
      		?>
   			</select> 时
    		<select name="minute_begin">
    			<option value='00' <?=($minute == '00') ? 'selected' : ''?>>00</option>
    			<option value='30' <?=($minute == '30') ? 'selected' : ''?>>30</option>
    		</select> 分
      	</td>
    </tr>
    <tr>
      	<td align="right" class="forumRow">结束时间：</td>
      	<td class="forumRow">
      		<input name="time_end" type="text" size="12" value="<?=date('Y-m-d', strtotime('20'.$rs['time_end'].'00'))?>" onClick="javascript:toggleDatePicker(this)">
      		<select name="hour_end">
      		<?php
      			$hour = mb_substr($rs['time_end'], 6, 2);
      			$minute = mb_substr($rs['time_end'], 8);
      			for($i=0;$i<=23;$i++){
					$i = sprintf("%02d", $i);
					$selected = ($hour == $i) ? 'selected' : '';
      				echo "<option value='$i' $selected>$i</option>";
      			}
      		?>
   			</select> 时
    		<select name="minute_end">
    			<option value='00' <?=($minute == '00') ? 'selected' : ''?>>00</option>
    			<option value='30' <?=($minute == '30') ? 'selected' : ''?>>30</option>
    		</select> 分
      	</td>
    </tr>
    <tr>
      	<td align="right" class="forumRow">活动类型：</td>
      	<td class="forumRow">
      		<select name="event[event_type]">
	      		<option value="">请选择...</option>
	      		<?php 
	      			if(isset($activity_type)){
	      				foreach ($activity_type as $k => $v){
							$selected = ($rs['event_type'] == $k)? 'selected' : '';
	      					echo "<option value='$k' $selected>".$v['type']."</option>";
	      				}
	      			}
	      		?>
	      	</select>
      	</td>
    </tr>
    <tr>
      	<td width="15%" align="right" class="forumRow">需求条件：</td>
      	<td width="85%" class="forumRow">
      		<input name="event[award_group]" type="text" size="28" value="<?=$rs['award_group']?>" onKeyUp="this.value=this.value.replace(/[^\.\d]/g,'');if(this.value.split('.').length>2){this.value=this.value.split('.')[0]+'.'+this.value.split('.')[1]}">
      	</td>
    </tr>   
    <tr>
      	<td width="15%" align="right" class="forumRow">名字：</td>
      	<td width="85%" class="forumRow">
      		<input name="event[name]" type="text" size="28" value="<?=$rs['name']?>">
      	</td>
    </tr>
    <tr>
      	<td width="15%" align="right" class="forumRow">描述：</td>
      	<td width="85%" class="forumRow">
      		<textarea name="event[descibe]" rows="3" cols="50"><?=$rs['descibe']?></textarea>
      	</td>
    </tr>
    
    <tr>
      	<td align="right" class="forumRow">&nbsp;</td>
      	<td class="forumRow">
      		<button name="action" value="edit" class="bott01" style="cursor:hand;">&nbsp;编辑&nbsp;</button>
        </td>
    </tr>
</table>
</form>
</body>
</html>