<?php
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");
include("inc/page.php");
include("../inc/config.php");
include("../inc/function.php");
include("inc/game_config.php");

$action = $_REQUEST['action'];
$game_id = $_REQUEST['game_id'];
$SvrID = $_REQUEST['SvrID'];
$account = $_REQUEST['account'];
$player_name = $_REQUEST['player_name'];
$StarTime = $_REQUEST['StarTime'];
$EndsTime = $_REQUEST['EndsTime'];
$status =  $_REQUEST['status'];
if($action=='select'){
    $PURL=$PURL."action=$action&game_id=$game_id&SvrID=$SvrID&account=$account&player_name=$player_name&StarTime=$StarTime&EndsTime=$EndsTime&status=$status&";
    $sql = " select *  from kf_message where 1=1 ";
    if ($game_id != ""){
        $sql=$sql." And game_id='$game_id' ";
    }
    if ($SvrID != ""){
        $sql=$sql." And server_id='$SvrID' ";
    }
    if ($account != ""){
        $sql=$sql." And account_name='$account' ";
    }
    if ($player_name != ""){
        $sql=$sql." And player_name='".mysql_escape_string($player_name)."' ";
    }
    if ($StarTime != ""){
        $sql=$sql." And time>='".strtotime($StarTime)."' ";
    }
    if ($EndsTime != ""){
        $sql=$sql." And time<='".(strtotime($EndsTime)+3600*24)."' ";
    }
    if ($status != 3&&$status != ""){
        $sql=$sql." And status='$status' ";
    }
    $sql = $sql."  order by id desc  " ;
    $pagesize=20;
    $page=new page($sql,$pagesize,getPath()."?".$PURL);//分页类
    $SqlStr=$page->pageSql();//格式化sql语句
    
    $sonn=mysql_query($SqlStr);
    while($rs=@mysql_fetch_array($sonn)){
        $result[] = $rs;
    }
}



?>


<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=gb2312">
        <title>玩家留言管理</title>
        <link href="CSS/Style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="JS/jquery-1.3.2.min.js"></script>
        <script language="javascript" src="JS/ActionFrom.js"></script>
        <script language="javascript" src="JS/calendar.js"></script>
    </head>
    <body class="main">

        <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
            <form name="form1" method="POST" enctype="multipart/form-data" action="?action=select">
                <tr>
                    <th colspan="2" align="center">玩家留言管理</th>
                </tr>
                <tr>
                    <td align="right" class="forumRow">游戏分类：</td>
                    <td class="forumRow">
                        <select name='game_id' onchange="change_game(this.value)">
                            <option value="">选择游戏</option>
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
                    <td align="right" class="forumRow">服务区：</td>
                    <td class="forumRow">
                        <select name="SvrID" id="ServerID">
                            <option value="" selected="selected">请选择分区</option>
                            <?php
                            foreach($game_arr[$game_id]['server_list'] as $game_key=>$game_value){
                                echo "<option value=\"".$game_key."\" ".(($SvrID==$game_key)?'selected="selected"':'')." >".$game_value."</option>";
                            }
                            ?>
                        </select>
                    </td>
                    <td rowspan="2" class="forumRow">&nbsp;</td>
                </tr>
                <tr>
                    <td align="right" class="forumRow">账号：</td>
                    <td class="forumRow">
                        <input name="account" value="<?php echo $account; ?>">
                    </td>
                    <td rowspan="2" class="forumRow">&nbsp;</td>
                </tr>
                <tr>
                    <td align="right" class="forumRow">角色名：</td>
                    <td class="forumRow">
                        <input name="player_name" value="<?php echo $player_name; ?>">
                    </td>
                    <td rowspan="2" class="forumRow">&nbsp;</td>
                </tr>
                <tr>
                    <td align="right" class="forumRow">状态：</td>
                    <td class="forumRow">
                        <select name="status">
                            <option value="3">不限</option>
                            <option value="1" <?php if($status==1){echo "selected";} ?>>已经回复</option>
                            <option value="2" <?php if($status==2){echo "selected";} ?>  <?php if(!$status){echo "selected";} ?>>未回复</option>
                        </select>
                    </td>
                    <td rowspan="2" class="forumRow">&nbsp;</td>
                </tr>
                <tr>
                    <td align="right" class="forumRow">时间范围：</td>
                    <td class="forumRow">
                        <input name="StarTime" type="text" size="12" value="<?=$StarTime?>"  readonly onfocus="HS_setDate(this)">
                        ～
                        <input name="EndsTime" type="text" size="12" value="<?=$EndsTime?>"  readonly onfocus="HS_setDate(this)">
                    </td>
                    <td rowspan="2" class="forumRow">&nbsp;</td>
                </tr>

                <tr align="right" class="forumRow"><td><input type="submit" name="查询" value="查询"/>  </td></tr>
            </form>
        </table>
        <table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">
            <tr height="22">
                <th  height="22" align="center">游戏</th>
                <th  height="22" align="center">分区</th>
                <th  height="22" align="center">账号</th>
                <th  height="22" align="center">角色名</th>
                <th  height="22" align="center">信息</th>
                <th  height="22" align="center">状态</th>
                <th  height="22" align="center">时间</th>
                <th  height="22" align="center">操作</th>
            </tr>

           <?php  for($i=0;$i<count($result);$i++){ ?>
            <tr>
                <td nowrap class="forumRow" align="center"><?php echo $game_arr[$result[$i]['game_id']]['name'] ; ?></td>
                <td nowrap class="forumRow" align="center"><?php echo $game_arr[$result[$i]['game_id']]['server_list'][$result[$i]['server_id']] ; ?></td>
                <td nowrap class="forumRow" align="center"><?php echo $result[$i]['account_name']; ?></td>
                <td nowrap class="forumRow" align="center"><?php echo $result[$i]['player_name']; ?></td>
                <td nowrap class="forumRow" align="center"><?php echo substr ($result[$i]['message'], 0 ,50);?></td>
                <td nowrap class="forumRow" align="center">
                
                <?php if($result[$i]['status']==1){echo "完成";}else{echo "未完成";}   ?>

                <?php if($result[$i]['status']==1){?>
                   <a target="_blank" href="kf_reply.php?action=update&id=<?php echo $result[$i]['id']; ?>&stauts=2">修改为"未完成"</a>
                <?php }else{  ?>
                   <a target="_blank" href="kf_reply.php?action=update&id=<?php echo $result[$i]['id']; ?>&stauts=1">修改为"完成"</a>
                <?php } ?>
                </td>
                


                <td nowrap class="forumRow" align="center"><?php echo date('Y-m-d H:i:s',$result[$i]['time']) ; ?></td>
                <td nowrap class="forumRow" align="center">
                <a target="_blank" href="kf_reply.php?player_id=<?php echo $result[$i]['player_id']; ?>&server_id=<?php echo $result[$i]['server_id']; ?>">回复</a>
                </td>
            </tr>
           <?php  } ?>


            <tr>
                <td height="25" colspan="13" align="center" class="forumRow">
                    <?
                    echo @$page->show();
                    ?></td>
            </tr>
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