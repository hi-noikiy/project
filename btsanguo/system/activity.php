<?php
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");
include("../inc/config.php");
include("../inc/function.php");
include("inc/page.php");
include("inc/game_config.php");

$table = 'u_activity';
$event = 'u_wonderfulevent';
$award = 'u_eventaward';
$type = $_POST['type'];
$action = $_REQUEST['action'];
$id = intval($_REQUEST['id']);
if($action == 'del'){
	//开启事务
	$page = $_REQUEST['page'];
	mysql_query('START TRANSACTION') or exit(mysql_error());
	$sql1 ="delete from $table where id=$id";
	if(!mysql_query($sql1)){
		mysql_query('ROLLBACK') or exit(mysql_error());
		ErrMsg('删除失败(event)！');
	}
	$sql = "select * from $event where activity_id=$id";
	$query = mysql_query($sql);
	while (@$row = mysql_fetch_assoc($query)){
		$new_id = $row['id'];
		$sql3 = "delete from $award where id=$new_id";
		if(!mysql_query($sql3)){
			mysql_query('ROLLBACK') or exit(mysql_error());
			ErrMsg('删除失败(award)！');
		}
	}
	$sql2 ="delete from $event where activity_id=$id";
	if(!mysql_query($sql2)){
		mysql_query('ROLLBACK') or exit(mysql_error());
		ErrMsg('删除失败(event)！');
	}
	mysql_query('COMMIT') or exit(mysql_error());//执行事务
	mysql_close();
	goMsg('删除成功！', 'activity.php?page='.$page);
}

if($action == 'down'){
	$sql = "select * from $event where activity_id=$id";
	$row = array();
	$downSql = '';
	if(@$query = mysql_query($sql)){	
		while (@$row = mysql_fetch_assoc($query)){
			$new_id = $row['id'];
			$field = array();
			$val = array();
			foreach ($row  as $k =>$v){
				if($k != 'activity_id'){
					$field[] = $k;
					$val[]="'$v'";
				}
			}
			$field = implode(',', $field);
			$val = implode(',', $val);
			$downSql .= "insert into $table ($field) values ($val);"."\r\n";
			
			$eSql = "select * from $award where id=$new_id";
			$re = array();
			if(@$eQuery = mysql_query($eSql))
				$re = mysql_fetch_assoc($eQuery);
			if(!empty($re)){
				$eField = array();
				$eVal = array();
				foreach ($re  as $k =>$v){
					$eField[] = $k;
					$eVal[]="'$v'";
				}
				$eField = implode(',', $eField);
				$eVal = implode(',', $eVal);
				$downSql .= "insert into $award ($eField) values ($eVal);"."\r\n";
			}
		}
	}
		
	$sql = "select name from $table where id=$id limit 1";
	$query = mysql_query($sql);
	$name = mysql_result($query, 0);
	$date = date('ymdHi');
	echo $downSql;
	$filename = $name.'_'.$date.'.txt';	
	header('Content-type: application/x-txt');
	header('Content-Disposition: attachment; filename='.$filename);
	exit();
}
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
		window.location.href ='activity.php?id='+id+'&action=del&page='+page;
	}
}
</script>
</head>
<body class="main">
<?php
$num = intval($_REQUEST["num"]);
$sid = intval($_REQUEST['sid']);
$name = CheckStr($_REQUEST["name"]);
$type = CheckStr($_REQUEST["type"]);

if(!empty($num)){
	$sql=$sql." And id=$num";
	$PURL=$PURL."num=$num&";
}
if(!empty($name)){
	$sql=$sql." And name like '$name%'";
	$PURL=$PURL."name=$name&";
}
if(!empty($type)){
	$sql=$sql." And event_type='$type'";
	$PURL=$PURL."type=$type&";
}

$SqlStr="select * from ".$table." where 1=1".$sql." order by id desc";
//echo $SqlStr;
//分页参数
$pagesize=15;
$page=new page($SqlStr,$pagesize,getPath()."?".$PURL);//分页类
$SqlStr=$page->PageSql();//格式化sql语句
$result=mysql_query($SqlStr);

?>
<form name="SearchForm" method="POST" action="<?=getPath()?>">
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
   	<tr> 
      	<th height="22" colspan="2" align="center">活动配置</th>
    </tr>
    <tr>
		<td align="right" class="forumRow">游戏：</td>
		<td width="85%" class="forumRow">
			<select name="game_id" onchange="change_game(this.value)">
				<option value='5'>三国</option>
			</select>
		</td>
		<td class="forumRow">&nbsp;</td>
	</tr>
    <tr> 
      	<td width="15%" align="right" class="forumRow">活动ID：</td>
      	<td width="85%" class="forumRow"><input name="num" type="text" value="<?=$num ? $num : ''?>"></td>
    </tr>
    <tr> 
      	<td align="right" class="forumRow">名称：</td>
      	<td width="85%" class="forumRow"><input name="name" type="text" value="<?=$name?>"></td>
    </tr>
    <tr> 
      	<td align="right" class="forumRow">活动类型：</td>
      	<td width="85%" class="forumRow">
	      	<select name="type">
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
      	<td align="right" class="forumRow">&nbsp;</td>
      	<td class="forumRow"><input type="submit" name="Submit2" class="bott01" value=" 搜 索 "></td>
    </tr>
</table>
</form>
<div style="font-size: 2px">&nbsp;</div>
<table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">
  	<tr height="22"> 
    	<th width="60" height="22" align="center">编号</th>
    	<th width="200" height="22" align="center">名称</th>
    	<th width="150" height="22">类型</th>
    	<th width="100" height="22">开始时间</th>
    	<th width="100">结束时间</th>
    	<th width="100">操作</th>
  	</tr>
<?php
$Ipage = $_REQUEST["page"];
if (empty($Ipage) || $Ipage == 0) 
	$Ipage=1;
$I=1;
while(@$rs=mysql_fetch_array($result)){
?>
	<tr bgcolor="#ECECED">
    	<td width="60" height="22" align="center" nowrap><?=$rs[0]?></td>
    	<td width="200" nowrap><a href="activity_config.php?aid=<?=$rs[0]?>&page=<?=$Ipage?>"><?=$rs["name"]?></a></td>
    	<td width="150" nowrap><?=$activity_type[$rs["event_type"]]['type']?></td>
    	<td width="100" nowrap><?=$rs["time_begin"]?></td>
	 	<td width="100" nowrap><?=$rs["time_end"]?></td>
	 	<td width="100" align="center">
	 		<a href="activity_edit.php?id=<?=$rs[0]?>">修改</a>&nbsp; 		
	 		<a href="javascript:;" onclick="isConfirm(<?=$rs[0]?>, <?=$Ipage?>)">删除</a>&nbsp;
	 		<a href="activity.php?id=<?=$rs[0]?>&action=down">生成SQL</a>
	 	</td>
  	</tr>
<?php
		$I++;
	}
?>
    <tr> 
    	<td height="25" colspan="6" class="forumRow">
      		<input class="bott01" type="button" name="add_prod" value=" 新 增 " onClick="window.location='activity_add.php?type=<?=$type?>'">
      	</td>
  	</tr>
  	<tr> 
    	<td height="25" colspan="6" align="center" class="forumRow"><?=$page->show();?></td>
  	</tr>
</table>
</body>
</html>