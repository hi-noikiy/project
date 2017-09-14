<?php
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");
include("../inc/config.php");
include("../inc/function.php");
include("inc/game_config.php");
global $activity_type;
$action = $_POST['action'];
$_type = $_POST['type'];
if($action == 'add'){
	$table = 'u_activity';
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
	
	foreach ($_POST['event'] as $k => $v){
		$field[] = $k;
		$value[] = "'".$v."'";
	}
	$field = implode(',', $field);
	$value = implode(',', $value);
	$sql = "insert into $table ($field) values ($value)";
	if(mysql_query($sql) == FALSE)
		ErrMsg ('新增活动配置失败！');
	goMsg('新增活动配置成功！', 'activity_add.php?type='.$_type);
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<title>新增</title>
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
<input type="hidden" name="sid" value="<?=$sid?>">
<input type="hidden" name="event[event_data]" value="0">
<input type="hidden" name="event[lock_type]" value="5004">
<input type="hidden" name="event[isnoshow]" value="0">
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
	<tr>
      	<th colspan="2" align="center">新增活动配置</th>
    </tr>
    <tr>
      	<td width="15%" align="right" class="forumRow">起始时间：</td>
      	<td width="85%" class="forumRow">
      		<input name="time_begin" type="text" size="12" value="<?=date('Y-m-d')?>" onClick="javascript:toggleDatePicker(this)">
      		<select name="hour_begin">
      		<?php 
      			for($i=0;$i<=23;$i++){
					$i = sprintf("%02d", $i);
      				echo "<option value='$i'>$i</option>";
      			}
      		?>
   			</select> 时
    		<select name="minute_begin">
    			<option value='00'>00</option>
    			<option value='30'>30</option>
    		</select> 分
      	</td>
    </tr>
    <tr>
      	<td align="right" class="forumRow">结束时间：</td>
      	<td class="forumRow">
      		<input name="time_end" type="text" size="12" value="<?=date("Y-m-d",strtotime("+1 day"))?>" onClick="javascript:toggleDatePicker(this)">
      		<select name="hour_end">
      		<?php 
      			for($i=0;$i<=23;$i++){
					$i = sprintf("%02d", $i);
      				echo "<option value='$i'>$i</option>";
      			}
      		?>
   			</select> 时
    		<select name="minute_end">
    			<option value='00'>00</option>
    			<option value='30'>30</option>
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
							$selected = ($type == $k)? 'selected' : '';
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
      		<input name="event[award_group]" type="text" size="28" onKeyUp="this.value=this.value.replace(/[^\.\d]/g,'');if(this.value.split('.').length>2){this.value=this.value.split('.')[0]+'.'+this.value.split('.')[1]}">
      	</td>
    </tr>   
    <tr>
      	<td width="15%" align="right" class="forumRow">名字：</td>
      	<td width="85%" class="forumRow">
      		<input name="event[name]" type="text" size="28">
      	</td>
    </tr>
    <tr>
      	<td width="15%" align="right" class="forumRow">描述：</td>
      	<td width="85%" class="forumRow">
      		<textarea name="event[descibe]" rows="3" cols="50"></textarea>
      	</td>
    </tr>
    
    <tr>
      	<td align="right" class="forumRow">&nbsp;</td>
      	<td class="forumRow">
      		<button name="action" value="add" class="bott01" style="cursor:hand;">&nbsp;增加&nbsp;</button>
      		<a href="activity.php" class="bott01" style="cursor:hand;">返回上一级</a>
        </td>
    </tr>
</table>
</form>
</body>
</html>