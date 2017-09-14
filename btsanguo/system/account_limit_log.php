<?php
include("inc/CheckUser.php");
include("inc/function.php");
include("inc/page.php");
include("../inc/config.php");
include("../inc/function.php");
include("inc/config.php");
function alert_back($content){
	echo '<script>alert(\''.$content.'\');</script>';
	echo '<script>history.back();</script>';exit;
}
?>
<html>
<head>
<title>list</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link href="CSS/Style.css" rel="stylesheet" type="text/css">
<script language="javascript" src="JS/DateTime/DateDialog.js"></script>
</head>
<body class="main">
<?php
SetConn(88);//u591
$StarTime = $_REQUEST['StarTime'];
$EndsTime = $_REQUEST['EndsTime'];
$account = strtolower($_REQUEST['account']);
$operator = $_REQUEST['operator'];
$type = $_REQUEST['type'];
$do = $_REQUEST['do'];
if($StarTime||$EndsTime||$account||$operator||$do){
	if($account){
		if(!$type){
			alert_back('请选择账号类型');
		}
	}
	if($StarTime){
		if(!$EndsTime){
			alert_back('请选择封号结束时间');
		}
	}
	if($EndsTime){
		if(!$StarTime){
			alert_back('请选择封号开始时间');
		}
	}
	$sql="select operator,addtime,reason,account,type,status from account_limit_log where ";
	if($StarTime&&$EndsTime){
		$sql.=" (addtime between '$StarTime 00:00:00' and '$EndsTime 23:59:59') and";
	}
	if($type=='1'){
        SetConn(81);//account
		$account_id=mysql_fetch_assoc(mysql_query("select id from account where Name='$account'"));
		$account_text=$account;//input控件记录值
		$account_id['id']=intval($account_id['id']);
        $account=$account_id['id'];
        if(!$account){
            alert_back('该账号不存在');
        }
        SetConn(88);//u591
	}
	if($account){
		$sql.=" account='$account' and";
	}
	if($operator){
		$sql.=" operator='$operator' and";
	}
	if($do){
		$sql.=" type='$do' and";
	}
	$sql=substr($sql,0,-3).' order by addtime desc';
	$query=mysql_query($sql);
	$list=array();
	while ($rs=mysql_fetch_assoc($query)){
		$list[]=$rs;
	}  
}
mysql_close();
?>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
  <form name="SearchForm" method="POST" action="<?=getPath()?>">
    <tr>
      <th height="22" colspan="2" align="center">封号查询</th>
    </tr>
     
    <tr>
        <td align="right" class="forumRow">
            账号类型:
        </td>
      <td width="85%" class="forumRow">
      	<select name="type">
                <option value="0">请选择</option>
                <option value="1" <?if($type=='1')echo 'selected'?>>账号</option>
                <option value="2" <?if($type=='2')echo 'selected'?>>账号ID</option>
            </select>
          <input name="account" type="text" size="30" value="<?php echo ($account_text)?$account_text:$account; ?>" >
          <?php if($account_id['id']){echo "账号ID:".$account_id['id'];} ?>
      </td>
    </tr>
    <tr>
        <td align="right" class="forumRow">
            封号人:
        </td>
      <td width="85%" class="forumRow">
          <input name="operator" type="text" size="30" value="<?php echo $operator; ?>" >(后台登录账号)
      </td>
    </tr>
     <tr>
        <td align="right" class="forumRow">
            账号操作类型:
        </td>
      <td width="85%" class="forumRow">
          <select name="do">
                <option value="0">请选择</option>
                <option value="1" <?if($do=='1')echo 'selected'?>>封号</option>
                <option value="2" <?if($do=='2')echo 'selected'?>>解号</option>
            </select>
      </td>
    </tr>
    <tr>
      <td align="right" class="forumRow">开始时间：</td>
      <td width="85%" class="forumRow"><input name="StarTime" type="text" size="12" value="<?php echo $StarTime; ?>"  onClick="javascript:toggleDatePicker(this)">
         ～结束时间:<input name="EndsTime" value="<?php echo $EndsTime; ?>" type="text" size="12"  onClick="javascript:toggleDatePicker(this)"></td>
    </tr>
    <tr>
      <td align="right" class="forumRow">&nbsp;</td>
      <td class="forumRow"><input type="submit" name="Submit2" class="bott01" value=" 确 定 ">
         </td>
    </tr>
  </form>
</table>
<DIV style="FONT-SIZE: 2px">&nbsp;</DIV>
<table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">
  <tr height="22">
  	<th width="5%" height="22" align="center">序列</th>
    <th width="10%" height="22" align="center">账号ID</th>
    <th width="45%" height="22" align="center">操作原因</th>
    <th width="15%" height="22" align="center">时间</th>
    <th width="10%" height="22" align="center">操作人</th>
    <th width="8%" height="22" align="center">操作类型</th>
    <th width="10%" height="22" align="center">账号目前状态</th>
  </tr>
<?php 
	if($list){
		foreach ($list as $key=>$val){
?>
  <tr>
    <td nowrap class="forumRow" align="center"><?php echo $key+1;?></td>
    <td nowrap class="forumRow" align="center"><?php echo $val['account'];?></td>
    <td nowrap class="forumRow" align="center"><?php echo $val['reason'];?></td>
    <td nowrap class="forumRow" align="center"><?php echo $val['addtime'];?></td>
    <td nowrap class="forumRow" align="center"><?php echo $val['operator'];?></td>
    <td nowrap class="forumRow" align="center"><?php echo strtr($val['type'],array('1'=>'封号','2'=>'解号'));?></td>
    <td nowrap class="forumRow" align="center"><?php echo strtr($val['status'],array('1'=>'封号','2'=>'解号'));?></td>
  </tr>
<?php }} ?>  
</table>
</body>
</html>