<?php
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");
include("../inc/config.php");
include("../inc/function.php");
include("inc/page.php");
include("inc/game_config.php");
$activityId = intval($_REQUEST['aid']);
$page = intval($_REQUEST['page']);
$action = $_REQUEST['action'];
$wonderfulevent = 'u_wonderfulevent';
$eventaward = 'u_eventaward';
if($action == 'del'){
	$id = intval($_REQUEST['id']);
	//开启事务
	mysql_query('START TRANSACTION') or exit(mysql_error());
	$sql1 ="delete from $wonderfulevent where id=$id";
	if(!mysql_query($sql1)){
		mysql_query('ROLLBACK') or exit(mysql_error());		
		ErrMsg('删除失败(event)！');
	}
	$sql2 ="delete from $eventaward where id=$id";
	if(!mysql_query($sql2)){
		mysql_query('ROLLBACK') or exit(mysql_error());
		ErrMsg('删除失败(award)！');
	}
	mysql_query('COMMIT') or exit(mysql_error());//执行事务
	mysql_close();
	goMsg('删除成功！', 'activity_config.php?aid='.$activityId.'&page='.$page);
}

$SqlStr="select * from ".$wonderfulevent." where 1=1 and activity_id=$activityId order by id desc";
$result=mysql_query($SqlStr);

?>
<html>
<head>
<title>list</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link href="CSS/Style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="JS/DateTime/DateDialog.js"></script>
<script type="text/javascript" src="JS/ActionFrom.js"></script>
<script type="text/javascript">
function isConfirm(id, page){
	if(confirm("你确定要删除吗？")){
		window.location.href ='activity_config.php?id='+id+'&action=del&page='+page+'&aid=<?=$activityId?>';
	}
}
</script>
</head>
<body class="main">
<div style="font-size: 2px">&nbsp;</div>
<table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">
  	<tr height="22"> 
    	<th width="60" height="22" align="center">活动ID</th>
    	<th width="100" height="22" align="center">名称</th>
    	<th width="200" height="22" align="center">描述</th>
    	<th width="350" height="22">活动物品</th>
    	<th width="100">操作</th>
  	</tr>
<?php
function getAddgoodName($itemtype_id, $num){
	$goodQuery = mysql_query("select name,remark from add_good where itemtype_id='$itemtype_id' limit 1;");
	$goodAssoc = array();
	if($goodQuery != false)
		$goodAssoc = mysql_fetch_assoc($goodQuery);
	 
	if(!empty($goodAssoc))
		return "<span title='{$goodAssoc['remark']}'>{$goodAssoc['name']}*$num</span>&nbsp;";
}
while(@$rs=mysql_fetch_array($result)){
?>
	<tr bgcolor="#ECECED">
    	<td height="22" align="center" nowrap><?=$rs[0]?></td>
    	<td nowrap><a href="activity_config_next.php?id=<?=$rs[0]?>"><?=$rs["name"]?></a></td>
    	<td nowrap><?=$rs["descibe"]?></td>
    	<td nowrap>
    		<?php
    			$id = intval($rs['id']);
    			$awardQuery = mysql_query("select * from u_eventaward where id='$id' limit 1");
    			$awardRow = array();
    			if($awardQuery != false)
    				$awardRow = mysql_fetch_assoc($awardQuery);
    			if(!empty($awardRow)){
	    			if($awardRow['money'])
	    				echo '金钱:'.$awardRow['money'].'&nbsp;';
	    			if($awardRow['reputation'])
	    				echo '声望:'.$awardRow['reputation'].'&nbsp;';
	    			if($awardRow['tiredness'])
	    				echo '体力:'.$awardRow['tiredness'].'&nbsp;';
	    			if($awardRow['currency1'])
	    				echo '战功:'.$awardRow['currency1'].'&nbsp;';
	    			if($awardRow['emoney'])
	    				echo '元宝:'.$awardRow['emoney'].'&nbsp;';
	    			if($awardRow['reputation'])
	    				echo '神玉:'.$awardRow['currency4'].'&nbsp;';
	    			
					if($awardRow['itemtype1'])
						echo getAddgoodName($awardRow['itemtype1'], $awardRow['itemnum1']);
					if($awardRow['itemtype2'])
						echo getAddgoodName($awardRow['itemtype2'], $awardRow['itemnum2']);
					if($awardRow['itemtype3'])
						echo getAddgoodName($awardRow['itemtype3'], $awardRow['itemnum3']);
					if($awardRow['itemtype4'])
						echo getAddgoodName($awardRow['itemtype4'], $awardRow['itemnum4']);
					if($awardRow['itemtype5'])
						echo getAddgoodName($awardRow['itemtype5'], $awardRow['itemnum5']);
				}
    		?>
    	</td>
	 	<td align="center">
	 		<a href="activity_config_edit.php?aid=<?=$rs[0]?>">修改</a>&nbsp;
	 		<a href="javascript:;" onclick="isConfirm(<?=$rs[0]?>, <?=$page?>)">删除</a>&nbsp;
	 	</td>
  	</tr>
<?php
}
?>
    <tr> 
    	<td height="25" colspan="6" class="forumRow">
      		<input class="bott01" type="button" name="add_prod" value=" 新 增 " onClick="window.location='activity_config_add.php?aid=<?=$activityId?>'">
      		<a href="activity.php?page=<?=$page?>">返回上一级</a>
      	</td>
  	</tr>
  	<tr> 
    	<td height="25" colspan="6" align="center" class="forumRow">&nbsp;</td>
  	</tr>
</table>
</body>
</html>