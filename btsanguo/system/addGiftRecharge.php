<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 手工加VIP经验
* ==============================================
* @date: 2016-4-5
* @author: Administrator
* @return:
*/
include("inc/config.php");
include("inc/CheckUser.php");
include("inc/function.php");
include("inc/page.php");
include("../inc/config.php");
include("../inc/function.php");
include("inc/game_config.php");
 $ip = getIP_front();
if (!getFlag('802',$_SESSION['u_flag'])){
	header("Location: Adm_Login.php");exit;
}
if (!getFlag('802',$uFlag)){
    header("Location: Adm_Login.php");
    exit;
}
$game_id = intval($_REQUEST["game_id"]);
?>
<html>
<head>
<title>手工加VIP经验</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link href="CSS/Style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="JS/jquery-1.3.2.min.js"></script>
</head>
<body class="main">
<?
$PayName=CheckStr($_REQUEST["PayName"]);
$player_name = trim($_REQUEST["player_name"]);
$sid = $_REQUEST["sid"];
$PayType = '8'; //充值类型
if($_POST){
	if(($player_name || $PayName) && $sid!=''){
        //校验账号   
        if($PayName){
          	SetConn(81);
          	$sql = "select id,NAME,dwFenBaoID from account where NAME='$PayName' limit 1";
          	$res = mysql_query($sql);
          	$rs = mysql_fetch_array($res);
        }else if($player_name&&$sid){
          	SetConn($sid);
          	$sql_player = " select * from u_player where name='$player_name' ";
          	$res_player = mysql_query($sql_player);
          	$rs_player = mysql_fetch_array($res_player);
          	$account_id =  $rs_player['account_id'];
          	if(!$rs_player['account_id']){
            	echo"<script>alert('该角色不存在');history.go(-1);</script>";
            	exit;
			}
           	SetConn(81);
          	$sql = "select id,NAME,dwFenBaoID from account where id='$account_id' limit 1";
          	$res = mysql_query($sql);
          	$rs = mysql_fetch_array($res);
        }
        if(!$rs){
            echo"<script>alert('该账号不存在');history.go(-1);</script>";
            exit;
        }
        $accountId = $rs['id'];
        $PayName = $rs['NAME'];
        if($_REQUEST["action"]=='add'){
            $recharge=CheckStr($_REQUEST["recharge"]);
            if(!is_numeric($recharge) || strpos($recharge,".")!==false) {
                echo"<script>alert('经验格式错误,请用整数');history.go(-1);</script>";
                exit;
            }
            SetConn($sid);
            $sql = "update u_gift_recharge set total_recharge_num=total_recharge_num+$recharge where server_id=$sid and account_id=$accountId";
            write_log(ROOT_PATH."log","add_gift_recharge","ip=$ip, sql=$sql, " .$AdminName.date("Y-m-d H:i:s")."\r\n");
            if (mysql_query($sql) == False){
                //写入失败日志
                $str="error: ".$sql.mysql_error()."  opera:$AdminName".date("Y-m-d H:i:s")."\r\n";
                write_log_pay_admin(ROOT_PATH."log","add_gift_recharge", $str);
                echo"<script>alert('增加经验失败');history.go(-1);</script>";
                exit;
            }
     
        } else {
			//查询业务
            SetConn($sid);
            $sql_player = "select id,name,serverid from u_player where account_id='".$rs['id']."'";
            $res_player = mysql_query($sql_player);
        }
    } else {
        echo"<script>alert('请填选服务区和账号或角色');history.go(-1);</script>";
        exit;
    }
}
?>
<form name="addForm" method="POST" action="<?=getPath()?>">
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
	<tr>
      <th height="22" colspan="3" align="center">手工充值</th>
    </tr>
    <tr>
		<td align="right" class="forumRow">游戏：</td>
		<td width="85%" class="forumRow">
			<select name="game_id" onchange="change_game(this.value)">
				<option value='' >选择游戏</option>
				<?php
					if($game_arr){
						foreach ($game_arr as $key=>$val){
							echo "<option value='$key' ".(($key==$game_id)?"selected":"")." >".$val['name']."</option>";
						}
					}
				?>
			</select>
		</td>
    </tr>
    <tr>
		<td align="right" class="forumRow">&nbsp;&nbsp;服务区：</td>
		<td class="forumRow">
			<select name="sid" id="ServerID">
				<?php
               		foreach($game_arr[$game_id]['server_list'] as $game_key=>$game_value){
                  		echo "<option value=\"".$game_key."\" ".(($game_key==$sid)?' selected="selected"':'').">".$game_value."</option>";
               		}
            	?>
      		</select>
      	</td>
      	<td class="forumRow">&nbsp;</td>
    </tr>
    <tr>
      	<td align="right" class="forumRow">帐号：</td>
      	<td class="forumRow"><input name="PayName" type="text" value="<?=$PayName?>" size="30"></td>
      	<td class="forumRow">&nbsp;</td>
    </tr>
    <tr>
      	<td align="right" class="forumRow">角色名：</td>
      	<td class="forumRow"><input name="player_name" type="text" value="<?=$player_name?>" size="30"></td>
      	<td class="forumRow">&nbsp;</td>
    </tr>
    <tr>
      	<td align="right" class="forumRow"></td>
      	<td class="forumRow"><input type="submit" name="addSubmit" class="bott01" value=" 校 验 "></td>
      	<td class="forumRow">&nbsp;</td>
    </tr>   
</table>
</form>
<form name="SearchForm" method="POST" action="<?=getPath()?>">
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
     <tr>
      	<td align="right" class="forumRow">账号：</td>
      	<td class="forumRow"><?=$PayName?></td>
      	<td class="forumRow">&nbsp;</td>
    </tr>
	<tr>
      	<td align="right" class="forumRow">角色/区/游戏：</td>
      	<td class="forumRow"><?=$player_name?>/<?=$game_arr[$game_id]['server_list'][$sid];?>/<?php  echo $game_arr[$game_id]['name'];?></td>
      	<td class="forumRow">&nbsp;</td>
    </tr>
    <tr>
       	<td align="right" class="forumRow">经验：</td>
       	<td class="forumRow"><input name="recharge" type="text" value="<?=$recharge?>" size="30"></td>
       	<td class="forumRow">&nbsp;</td>
    </tr>
    <tr>
      	<td align="right" class="forumRow"></td>
      	<td class="forumRow">
			<input type="hidden" name="action" value="add">
          	<input type="hidden" name="PayName" value="<?=$PayName?>">
          	<input type="hidden" name="player_name" value="<?=$player_name?>">
          	<input type="hidden" name="sid" value="<?=$sid?>">
          	<input type="hidden" name="game_id" value="<?=$game_id?>">
          	<input type="button" name="serSubmit" class="bott01" onclick="javascript:{this.disabled=true;document.SearchForm.submit();}" value=" 充 值 ">
      	</td>
      	<td class="forumRow">&nbsp;</td>
    </tr>
</table>
</form>

<DIV style="FONT-SIZE: 2px">&nbsp;</DIV>
<table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">
	<tr height="22">
    	<th width="20%"  height="22" align="center">账号ID</th>
    	<th width="20%"  height="22" align="center">账号</th>
    	<th width="20%"  height="22" align="center">服务器ID</th>
    	<th width="20%"  height="22" align="center">角色ID</th>
    	<th width="20%"  height="22" align="center">角色名称</th>
    	<th width="20%"  height="22" align="center">经销商ID</th>
  	</tr>
<?php
while(@$rs_player=mysql_fetch_array($res_player)){
?>
  	<tr>
    	<td align="center" class="forumRow"><?=$rs['id']?></td>
    	<td align="center" class="forumRow"><?=$rs['NAME']?></td>
    	<td align="center" class="forumRow"><?=$rs_player['serverid']?></td>
    	<td align="center" class="forumRow"><?=$rs_player['id']?></td>
    	<td align="center" class="forumRow" ><?=$rs_player['name']?></td>
    	<td align="center" class="forumRow" ><?=$rs['dwFenBaoID']?></td>
  	</tr>
<?php
}
?>
</table>
</body>
<script type="text/javascript">
   function change_game(game_id){
       $.post("ajax/game.php", { action: "change_game", game_id: game_id },
  function(data){
         $("#ServerID").html(data);
    });
   }
</script>
</html>
<?
mysql_close();
?>