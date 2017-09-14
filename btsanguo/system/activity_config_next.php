<?php
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");
include("../inc/config.php");
include("../inc/function.php");
include("inc/page.php");
include("inc/game_config.php");
$id = intval($_REQUEST['id']);
$sid = intval($_REQUEST['sid']);
$table = 'u_eventaward';
$wonderfulevent = 'u_wonderfulevent';
$wSql = "select * from $wonderfulevent where id=$id limit 1";
$wResult = array();
if(@$query = mysql_query($wSql))
	$wResult = mysql_fetch_assoc($query);
if(empty($wResult))
	ErrMsg('改活动不存在！');

$sql = "select * from $table where id=$id limit 1";
$result = array();
if(@$query = mysql_query($sql))
	$result = mysql_fetch_assoc($query);
$action = $_POST['action'];
function verificationAddgood($itemtype_id, $num, $msg){
	if(!$num)
		ErrMsg($msg.'数量不能为空！');
	$sql = "select amount_limit from add_good where itemtype_id='$itemtype_id' limit 1;";
	$row = array();
	$query = mysql_query($sql);
	if($query != false)
		$row = @mysql_fetch_assoc($query);
	if(!$row['amount_limit'])
		ErrMsg($msg.'不存在！');
	if($num > $row['amount_limit'])
		ErrMsg($msg.'最大数量为：'.$row['amount_limit']);
}

function getAddgoodName($itemtype_id){
	if(empty($itemtype_id))
		return;
	$goodQuery = mysql_query("select name,remark from add_good where itemtype_id='$itemtype_id' limit 1;");
	$goodAssoc = array();
	if($goodQuery != false)
		$goodAssoc = mysql_fetch_assoc($goodQuery);

	if(!empty($goodAssoc))
		return "<span title='{$goodAssoc['remark']}'>{$goodAssoc['name']}<font color='red'>({$goodAssoc['remark']})</font></span>";
}

if($action == 'save'){
	//判断是否存在物品
	$item1 = $_POST['award']['itemtype1'];
	$item2 = $_POST['award']['itemtype2'];
	$item3 = $_POST['award']['itemtype3'];
	$item4 = $_POST['award']['itemtype4'];
	$item5 = $_POST['award']['itemtype5'];
	if(!empty($item1)){
		$num1 = $_POST['award']['itemnum1'];
		verificationAddgood($item1, $num1, '奖励物品1');
	}
	if(!empty($item2)){
		$num2 = $_POST['award']['itemnum2'];
		verificationAddgood($item2, $num2, '奖励物品2');
	}
	if(!empty($item3)){
		$num3 = $_POST['award']['itemnum3'];
		verificationAddgood($item3, $num3, '奖励物品3');
	}
	if(!empty($item4)){
		$num4 = $_POST['award']['itemnum4'];
		verificationAddgood($item4, $num4, '奖励物品4');
	}
	if(!empty($item5)){
		$num5 = $_POST['award']['itemnum5'];
		verificationAddgood($item5, $num5, '奖励物品5');
	}
	if($result){
		$updateArr = array();
		foreach ($_POST['award'] as $k => $v){
			$updateArr[] = "$k='$v'";
		}		
		$field = implode(',', $updateArr);
		$sql = "update $table set $field where id='$id'";
	} else {
		$field = array();
		$value = array();
		foreach ($_POST['award'] as $k => $v){
			$field[] = $k;		
			$v = ($k == 'descibe') ? trim($v) : intval($v);
			$value[] = "'".$v."'";
		}
		$field = implode(',', $field);
		$value = implode(',', $value);
		$sql = "insert into $table ($field) values ($value)";
	}
	$query = mysql_query($sql);
	
	if($query == false)
		goMsg('编辑失败', 'activity_config_next.php?id='.$id);
	goMsg('编辑成功', 'activity_config_next.php?id='.$id);
}
?>
<html>
<head>
<title>list</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link href="CSS/Style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="JS/DateTime/DateDialog.js"></script>
<script type="text/javascript" src="JS/jquery-1.3.2.min.js"></script>
<script type="text/javascript">
function CheckForm(){
	var $itemtype1 = $("input[name='itemtype1']");
	if($awardGroup.val() != ''){
		var $itemnum1 = $("input[name='itemnum1']");
		if($itemnum1 == ""){
			alert("奖励物品1数量不能为空！");
			$itemnum1.focus();
			return false;
		}
	}
}
</script>
</head>
<body class="main">
<div style="font-size: 2px">&nbsp;</div>
<form name="form1" method="POST" onSubmit="return CheckForm();" action="<?=getPath()?>">
<input type="hidden" name="id" value="<?=$wResult['id']?>">
<input type="hidden" name="sid" value="<?=$sid?>">
<input type="hidden" name="award[id]" value="<?=$wResult['id']?>">
<input type="hidden" name="award[emoney_scale]" value="0">
<table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">
	<tr>
      	<th colspan="2" align="center">新增活动配置</th>
    </tr>
    
    <tr>
    	<td width="15%" align="right" class="forumRow">活动名字：</td>
    	<td width="85%" class="forumRow"><?=$wResult['name']?></td>
    </tr>
    <tr> 
      	<td align="right" class="forumRow">活动类型：</td>
      	<td width="85%" class="forumRow">
      		<input type="hidden" name="award[event_type]" value="<?=$wResult['event_type']?>">
      		<?=$activity_type[$wResult['event_type']]['type']?>
      	</td>
    </tr>
    <tr>
    	<td width="15%" align="right" class="forumRow">需求条件：</td>
    	<td width="85%" class="forumRow">
    		<input type="hidden" name="award[award_group]" value="<?=$wResult['award_group']?>">
    		<?=$wResult['award_group']?>
    	</td>
    </tr>
    <tr>
      	<td width="15%" align="right" class="forumRow">起始时间：</td>
      	<td width="85%" class="forumRow">
      		<input type="hidden" name="award[begin_time]" value="<?=$wResult['time_begin']?>">
      		<?=$wResult['time_begin']?>
      	</td>
    </tr>
   	<tr>
      	<td width="15%" align="right" class="forumRow">结束时间：</td>
      	<td width="85%" class="forumRow">
      		<input type="hidden" name="award[end_time]" value="<?=$wResult['time_end']?>">
      		<?=$wResult['time_end']?>
      	</td>
    </tr>
    <tr>
      	<td width="15%" align="right" class="forumRow">奖励金钱：</td>
      	<td width="85%" class="forumRow">
      		<input type="text" name="award[money]" value="<?=$result['money']?>" size="30" onkeyup="value=value.replace(/[^\d]/g,'')">
      	</td>
    </tr>
    <tr>
      	<td width="15%" align="right" class="forumRow">奖励声望：</td>
      	<td width="85%" class="forumRow">
      		<input type="text" name="award[reputation]" value="<?=$result['reputation']?>" size="30" onkeyup="value=value.replace(/[^\d]/g,'')">
      	</td>
    </tr>
    <tr>
      	<td width="15%" align="right" class="forumRow">奖励体力：</td>
      	<td width="85%" class="forumRow">
      		<input type="text" name="award[tiredness]" value="<?=$result['tiredness']?>" size="30" onkeyup="value=value.replace(/[^\d]/g,'')">
      	</td>
    </tr>
    <tr>
      	<td width="15%" align="right" class="forumRow">奖励战功：</td>
      	<td width="85%" class="forumRow">
      		<input type="text" name="award[currency1]" value="<?=$result['currency1']?>" size="30" onkeyup="value=value.replace(/[^\d]/g,'')">
      	</td>
    </tr>
    <tr>
      	<td width="15%" align="right" class="forumRow">奖励元宝：</td>
      	<td width="85%" class="forumRow">
      		<input type="text" name="award[emoney]" value="<?=$result['emoney']?>" size="30" onkeyup="value=value.replace(/[^\d]/g,'')">
      	</td>
    </tr>
    <tr>
      	<td width="15%" align="right" class="forumRow">奖励物品1：</td>
      	<td width="85%" class="forumRow">
      		<input type="text" name="award[itemtype1]" value="<?=$result['itemtype1']?>" onkeyup="value=value.replace(/[^\d]/g,'')">
      		&nbsp;数量： <input type="text" size="5" name="award[itemnum1]" value="<?=$result['itemnum1']?>" onkeyup="value=value.replace(/[^\d]/g,'')">   	
      		<?=getAddgoodName($result['itemtype1'])?>
      	</td>
    </tr>
    <tr>
      	<td width="15%" align="right" class="forumRow">奖励物品2：</td>
      	<td width="85%" class="forumRow">
      		<input type="text" name="award[itemtype2]" value="<?=$result['itemtype2']?>" onkeyup="value=value.replace(/[^\d]/g,'')">
      		&nbsp;数量： <input type="text" size="5" name="award[itemnum2]" value="<?=$result['itemnum2']?>" onkeyup="value=value.replace(/[^\d]/g,'')">
      		<?=getAddgoodName($result['itemtype2'])?>
      	</td>
    </tr>
    <tr>
      	<td width="15%" align="right" class="forumRow">奖励物品3：</td>
      	<td width="85%" class="forumRow">
      		<input type="text" name="award[itemtype3]" value="<?=$result['itemtype3']?>" onkeyup="value=value.replace(/[^\d]/g,'')">
      		&nbsp;数量： <input type="text" size="5" name="award[itemnum3]" value="<?=$result['itemnum3']?>" onkeyup="value=value.replace(/[^\d]/g,'')">
      		<?=getAddgoodName($result['itemtype3'])?>
      	</td>
    </tr>
    <tr>
      	<td width="15%" align="right" class="forumRow">奖励物品4：</td>
      	<td width="85%" class="forumRow">
      		<input type="text" name="award[itemtype4]" value="<?=$result['itemtype4']?>" onkeyup="value=value.replace(/[^\d]/g,'')">
      		&nbsp;数量： <input type="text" size="5" name="award[itemnum4]" value="<?=$result['itemnum4']?>" onkeyup="value=value.replace(/[^\d]/g,'')">
      		<?=getAddgoodName($result['itemtype4'])?>
      	</td>
    </tr>
    <tr>
      	<td width="15%" align="right" class="forumRow">领取奖励描述：</td>
      	<td width="85%" class="forumRow">
      		<textarea rows="3" cols="50" name="award[descibe]"><?=$result['descibe']?></textarea>
      	</td>
    </tr>
    <tr>
      	<td width="15%" align="right" class="forumRow">奖励神玉：</td>
      	<td width="85%" class="forumRow">
      		<input type="text" name="award[currency4]" value="<?=$result['currency4']?>" size="30" onkeyup="value=value.replace(/[^\d]/g,'')">
      	</td>
    </tr>
    
    <tr>
      	<td width="15%" align="right" class="forumRow">奖励物品5：</td>
      	<td width="85%" class="forumRow">
      		<input type="text" name="award[itemtype5]" value="<?=$result['itemtype5']?>" onkeyup="value=value.replace(/[^\d]/g,'')">
      		&nbsp;数量： <input type="text" size="5" name="award[itemnum5]" value="<?=$result['itemnum5']?>" onkeyup="value=value.replace(/[^\d]/g,'')">	
      		<?=getAddgoodName($result['itemtype5'])?>
      	</td>
    </tr>
  	<tr> 
    	<td height="25" colspan="6" align="center" class="forumRow">
    		<button name="action" value="save" class="bott01" style="cursor:hand;">&nbsp;保存&nbsp;</button>
    		<a href="activity_config.php?aid=<?=$wResult['activity_id']?>" class="bott01" style="cursor:hand;">返回上一级</a>
    	</td>
  	</tr>
</table>
</form>
</body>
</html>