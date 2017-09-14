<?php
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");
include("../inc/config.php");
include("../inc/function.php");
include("inc/game_config.php");
global $activity_type;
$action = $_POST['action'];
$activityId = intval($_REQUEST['aid']);
$table = 'u_activity';
$table2 = 'u_wonderfulevent';

$sql = "select * from $table where id=$activityId limit 1;";
$query = mysql_query($sql);
$rs = mysql_fetch_assoc($query);

$sql = "select max(id) from $table2 where activity_id=$activityId limit 1";
$query = mysql_query($sql);
$id = mysql_result($query, 0);
$event_id = $id ? $id+1 : $activityId;

if($action == 'add'){	
	
	$type = intval($_POST['event']['event_type']);
	$_POST['event']['icon'] = $activity_type[$type]['icon'];
	if(empty($_POST['event']['icon']))
		ErrMsg('活动类型对应的活动数值不存在！');
	
	foreach ($_POST['event'] as $k => $v){
		$field[] = $k;
		$value[] = "'".$v."'";
	}
	$field = implode(',', $field);
	$value = implode(',', $value);
	$sql = "insert into $table2 ($field) values ($value)";
	if(mysql_query($sql) == FALSE)
		ErrMsg ('新增活动配置失败！');
	goMsg('新增活动配置成功！', 'activity_config.php?aid='.$activityId);
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
<input type="hidden" name="aid" value="<?=$activityId?>">
<input type="hidden" name="event[id]" value="<?=$event_id?>">
<input type="hidden" name="event[activity_id]" value="<?=$activityId?>">
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
      		<input name="event[time_begin]" type="hidden" value="<?=$rs['time_begin']?>">
      		<?=$rs['time_begin']?>
      	</td>
    </tr>
    <tr>
      	<td align="right" class="forumRow">结束时间：</td>
      	<td class="forumRow">
      		<input name="event[time_end]" type="hidden" value="<?=$rs['time_end']?>">
      		<?=$rs['time_end']?>
      	</td>
    </tr>
    <tr>
      	<td align="right" class="forumRow">活动类型：</td>
      	<td class="forumRow">
      		<input name="event[event_type]" type="hidden" value="<?=$rs['event_type']?>">
      		<?=$activity_type[$rs['event_type']]['type']?>
      	</td>
    </tr>
    <tr>
      	<td width="15%" align="right" class="forumRow">需求条件：</td>
      	<td width="85%" class="forumRow">
      		<input type="text" name="event[award_group]" value="<?=$rs['award_group']?>">
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
        	<input name="Cancel" class="bott01" type="button" value=" 取 消 " onClick="javascript:history.back()" style="cursor:hand;">
        </td>
    </tr>
</table>
</form>
</body>
</html>