<?php
include("inc/CheckUser.php");
include("inc/function.php");
include("inc/page.php");
include("inc/config.php");
include("../inc/config.php");
include("../inc/function.php");
// 采用外网的 游戏分区配置
include("../inc/game_config.php");
//封号写入游戏库
//参数说明：服务区ID,帐号ID
function WritePayId($ServerID,$PayID){
    $PayID = intval($PayID);
	SetConn($ServerID);//根据SvrID连接服务器
	$sql="select count(0) from u_accountlimit where id='$PayID'";
	$query=mysql_query($sql);
	$RowCount=mysql_result($query,0);
	if ($RowCount == 0){
		$sql="insert into u_accountlimit(id)";
		$sql=$sql." values($PayID)";
                $str="ServerID=".$ServerID."  PayID=".$PayID ." ".date("Y-m-d H:i:s")."\r\n";
		if(mysql_query($sql) == False){
			echo "<script>alert('$ServerID 区未下架成功，请重试 $ServerID 区')</script>";
            write_log(ROOT_PATH."log","accountlimit_log_err",$str."  , sql=$sql,   ".mysql_error());
		}
        else{
        	write_log(ROOT_PATH."log","accountlimit_log_s",$str);
        }
	}
}
?>
<html>
<head>
<title>list</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link href="CSS/Style.css" rel="stylesheet" type="text/css">
</head>
<body class="main">
<?php
SetConn(81);
$type = $_REQUEST['type'];
$account = strtolower($_REQUEST['account']);
$sid = $_REQUEST['sid'];
$upto = $_REQUEST['upto'];
$limit_reason = $_REQUEST['limit_reason'];
$sqlac = '';
if($_POST)
{
    if($account)
    {
        if($type=='0')
        ErrMsg("请选择账号类型");
        elseif($type=='1')
        $sqlac = " NAME='".$account."' ";
        elseif($type=='2')
        $sqlac = " id='".$account."' ";
        $sql = "SELECT * FROM account where $sqlac limit 0,1";
        $res=mysql_query($sql);
        if(!$info = mysql_fetch_assoc($res))
        {
            ErrMsg("账号有误，请核实账号类型！");
        }
        else
        {
            if($upto!='2')
            {
            	if($upto=='1'||$upto=='0'){//封号操作记录到数据库
            		$account_id=intval($info['id']);
            		$status='';//记录账号目前状态
            		$log_type='';//记录此次操作状态
            		switch ($upto){
            			case '1':$status='1';$log_type='1';break;//封号
            			case '0':$status='2';$log_type='2';break;//解号
            			default:break;
            		}
            		SetConn(88);
            		mysql_query("update account_limit_log set status='$status' where account='$account_id'");
            		mysql_query("insert into account_limit_log(operator,addtime,reason,account,type,status) 
            		values('$AdminName','".date('Y-m-d H:i:s')."','$limit_reason','$account_id','$log_type','$status')");
            		SetConn(81);
            	}
                $upsql = "update account set limitType='$upto' where $sqlac ";
                if(mysql_query($upsql)==false)
                {
                    $strlog = "$upsql,who='$AdminName', date=".date("Y-m-d H:i:s")."\r\n";
                    write_log(ROOT_PATH.'log','limit_account_err',$strlog);
                    ErrMsg("执行失败，请重试！");
                }
                else
                {
                    $info['limitType'] = $upto;
                    $strlog = ($upto=='1'?'停用了':'启用了')." $sqlac,who='$AdminName', date=".date("Y-m-d H:i:s")."\r\n";
                    write_log(ROOT_PATH.'log','limit_account',$strlog);
                    echo "<script>alert('操作成功!')</script>";
                }
                //封号写入下架标识
                if($upto=='1')
                {
                	if($sid){
	                	foreach($sid as $val)
	                    {
	                        WritePayId($val,$info['id']);
	                    }
                	}
                }
            }
        }
    }
    else
    {
        ErrMsg("请填写账号或账号ID");
    }
}
?>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
  <form name="SearchForm" method="POST" action="<?=getPath()?>">
    <tr>
      <th height="22" colspan="2" align="center">账号封解</th>
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
          <input name="account" type="text" size="30" value="<?=$account?>" >
      </td>
    </tr>
    <tr>
      <td align="right" class="forumRow">操作：</td>
      <td width="85%" class="forumRow">
          <input type="radio" name="upto" value="1" onclick="setReason(this.value)" <?if($upto=='1')echo 'checked'?>>封号
          <input type="radio" name="upto" value="0" onclick="setReason(this.value)" <?if($upto=='0')echo 'checked'?>>解号 (注:仅当封号时选择下架区有效)
          <input type="radio" name="upto" value="2" onclick="setReason(this.value)" <?if($upto=='2')echo 'checked'?>>查询
      </td>
    </tr>
    <tr>
      <td align="right" class="forumRow">下架区：</td>
      <td width="85%" class="forumRow">
          <input type="checkbox" name="all" onclick="checkAll(this.checked)" >全选
          <?
            $i=0;
            foreach ($game_arr[5]['server_list'] as $aKey=>$aValue){
                $i++;
                echo "<input type='checkbox' name='sid[]' value=\"$aKey\" >".$aValue;
                if($i%6==0)echo "<br/>";
            }
          
	   ?>
      </td>
    </tr>
    <tr id="limit_reason" style="display: none">
      <td align="right" class="forumRow">封号/解号原因：</td>
      <td width="85%" class="forumRow">
          <textarea rows="5" cols="100" name="limit_reason"></textarea>
      </td>
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
    <th width="15%" height="22" align="center">账号</th>
    <th width="25%" height="22" align="center">当前状态</th>
  </tr>
<?
    if($info)
    {
       ?>

  <tr>
    <td nowrap class="forumRow" align="center"><?=$info['NAME']?></td>
    <td nowrap class="forumRow" align="center"><?=$info['limitType']=='0'?'使用中':'停用中'?></td>
  </tr>
  <? }?>
</table>
<script>
function checkAll(val)
{
    var code_Values = document.getElementsByTagName("input");
    for(i = 0;i < code_Values.length;i++)
    {
        if(code_Values[i].type == "checkbox")
        {
            code_Values[i].checked = val;
        }
    }
}
function setReason(val){
	if(val=='1'||val=='0'){
		document.getElementById('limit_reason').style.display='block';
	}
	else{
		document.getElementById('limit_reason').style.display='none';
	}
}
</script>
</body>
</html>