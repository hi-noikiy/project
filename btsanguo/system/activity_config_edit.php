<?php
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");
include("../inc/config.php");
include("../inc/function.php");
include("inc/game_config.php");
global $activity_type;

$action = $_POST['action'];
$id = intval($_REQUEST['aid']);
$table2 = 'u_wonderfulevent';

$sql = "select * from $table2 where id=$id limit 1;";
$query = mysql_query($sql);
$rs = mysql_fetch_assoc($query);

if($action == 'edit'){
	$updateArr = array();
	foreach ($_POST['event'] as $k => $v){
		$updateArr[] = "$k='$v'";
	}
	$field = implode(',', $updateArr);
	$sql = "update $table2 set $field where id='$id'";
	if(mysql_query($sql) == FALSE)
		ErrMsg ('编辑活动配置失败！');
	goMsg('编辑活动配置成功！', 'activity_config_edit.php?aid='.$id);
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
<input type="hidden" name="aid" value="<?=$rs['id']?>">
<input type="hidden" name="event[activity_id]" value="<?=$rs['activity_id']?>">
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
      		<a href="activity_config.php?aid=<?=$rs['activity_id']?>" class="bott01" style="cursor:hand;">返回上一级</a>
        </td>
    </tr>
</table>
</form>
</body>
</html>