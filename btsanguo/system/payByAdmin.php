<?
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
//$ip=getIP();
//$server_ip=gethostbyname($server_host);//获取域名IP
//
//if(!strstr($ip,$server_ip))
//{
//	echo"<script>alert('您的IP已被限制访问！');history.go(-1);</script>";exit;
//}


?>
<html>
<head>
<title>手工充值</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="CSS/Style.css" rel="stylesheet" type="text/css">
<script language="javascript" src="JS/jquery-1.3.2.min.js"></script>
</head>
<body class="main">
<?
$PayName=CheckStr($_REQUEST["PayName"]);
$player_name = trim($_REQUEST["player_name"]);
$sid = $_REQUEST["sid"];
$PayType = '8'; //充值类型
if($_POST)  //提交数据
{
    if(($player_name||$PayName) && $sid!='')
    {
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
          if(!$rs_player['account_id'])
         {
            echo"<script>alert('该角色不存在');history.go(-1);</script>";
            exit;
         }
           SetConn(81);
          $sql = "select id,NAME,dwFenBaoID from account where id='$account_id' limit 1";
          $res = mysql_query($sql);
          $rs = mysql_fetch_array($res);
        }


        if(!$rs)
        {
            echo"<script>alert('该账号不存在');history.go(-1);</script>";
            exit;
        }
        $PayID = $rs['id'];
        $PayName = $rs['NAME'];
        if($_REQUEST["action"]=='add')
        {
            $emoney=CheckStr($_REQUEST["emoney"]);
            if(!is_numeric($emoney)||strpos($emoney,".")!==false)
            {
                echo"<script>alert('金额格式错误,请用整数');history.go(-1);</script>";
                exit;
            }
            //充值业务...
            SetConn(88);//链接日志库
            $Add_Time=date('Y-m-d H:i:s');
            //生成定单,精确到毫秒
            $OrderNum=date("ymdHis").floor(microtime()*1000).rand(10000,99999);
            $sql="insert into pay_log (CPID,PayCode,PayID,PayName,ServerID,PayMoney,OrderID,PayType,dwFenBaoID,Add_Time,rpTime,game_id)";
            $sql=$sql." VALUES ('9','SGCZ',$PayID,'$PayName',$sid,$emoney,'$OrderNum','$PayType','0','$Add_Time','$Add_Time',$game_id)";

            write_log(ROOT_PATH."log","payByAdmin","ip=$ip  emoney=$emoney "."  ServerID=$sid "."  PayName=$PayName "."  game_id=$game_id "."  PayID=$PayID ".$AdminName.date("Y-m-d H:i:s")."\r\n");
            if (mysql_query($sql) == False){
                //写入失败日志
                $str="error: ".$sql.mysql_error()."  opera:$AdminName".date("Y-m-d H:i:s")."\r\n";
                write_log_pay_admin(ROOT_PATH."log","pay_admin_log",$str);
                echo"<script>alert('充值失败');history.go(-1);</script>";
                exit;
            }
            WriteCardAdmin(1,$sid,$PayType,$PayID,$OrderNum,$emoney,$AdminName);//充值成功写入游戏库
            updatePoints($PayID,$emoney,'f_dx',$OrderNum,'admin');  //加积分
            updateRankUp($PayID,'f_dx',"admin");//改等级
            WritePayMsg(0,$sid,$PayID,$OrderNum,$emoney,$game_id);//写入客户端消息
        }
        else //查询业务
        {
            SetConn($sid);
            $sql_player = "select id,name,serverid from u_player where account_id='".$rs['id']."'";
            $res_player = mysql_query($sql_player);
        }
    }
    else
    {
        echo"<script>alert('请填选服务区和账号或角色');history.go(-1);</script>";
        exit;
    }
}
?>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
  <form name="addForm" method="POST" action="<?=getPath()?>">
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
    </form>
</table>
    <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
  <form name="SearchForm" method="POST" action="<?=getPath()?>">
     <tr>
      <td align="right" class="forumRow">充值账号：</td>
      <td class="forumRow"><?=$PayName?></td>
      <td class="forumRow">&nbsp;</td>
    </tr>
         <tr>
      <td align="right" class="forumRow">充值角色/区/游戏：</td>
      <td class="forumRow"><?=$player_name?>/<?php  echo $game_arr[$game_id]['server_list'][$sid];?>/<?php  echo $game_arr[$game_id]['name'];?></td>
      <td class="forumRow">&nbsp;</td>
    </tr>
    <tr>
      <td align="right" class="forumRow">金额：</td>
      <td class="forumRow"><input name="emoney" type="text" value="<?=$emoney?>" size="30"></td>
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
  </form>
</table>
<?

?>
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
<?
while($rs_player=@mysql_fetch_array($res_player)){
?>
  <tr>
    <td  align="center" class="forumRow"><?=$rs['id']?></td>
    <td  align="center" class="forumRow"><?=$rs['NAME']?></td>
    <td  align="center" class="forumRow"><?=$rs_player['serverid']?></td>
    <td  align="center" class="forumRow"><?=$rs_player['id']?></td>
    <td  align="center" class="forumRow" ><?=iconv('GBK', 'UTF-8', $rs_player['name']);?></td>
    <td  align="center" class="forumRow" ><?=$rs['dwFenBaoID']?></td>
  </tr>
<?
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