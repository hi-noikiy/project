<?php
include("inc/config.php");
include(ROOT_PATH."inc/config.php");
include(ROOT_PATH."system/inc/CheckUser.php");
include("inc/game_config.php");

if($_POST['action']=='select'){
    $game_id = $_POST['game_id'];
    $sid = $_POST['sid'];
    $player_name = $_POST['player_name'];
    $player_id = $_POST['player_id'];
    $type = $_POST['type'];
    $account_info = trim($_POST['account_info']);
    if($sid&&($player_name||$player_id)){
        SetConn($sid);
        if($player_name){

            $player_name = mysql_escape_string($player_name);
            $sql_player = " select * from u_player where name='".iconv('UTF-8', 'GBK', $player_name) ."' ";
            $res_player = mysql_query($sql_player);
            $rs_player = mysql_fetch_array($res_player);
        }elseif($player_id){

            $player_id = mysql_escape_string($player_id);
            $sql_player = " select * from u_player where id='$player_id' ";
            $res_player = mysql_query($sql_player);
            $rs_player = mysql_fetch_array($res_player);
            
        }

        $type =2;
        $account_info = $rs_player['account_id'];
    }


    SetConn(81);

    if($type==1){
        $sql = " select * from account where NAME='$account_info' ";
    }elseif($type==3){
        $sql = " select * from account where channel_account='$account_info' ";
    }else{
        $sql = " select * from account where id='$account_info' ";
    }
    $account_conn = mysql_query($sql);
    $account_result = @mysql_fetch_array($account_conn) ;
    $account_id = intval($account_result['id']) ;
    $sql_vip = " select * from vippoints where account_id='$account_id' ";
    $vip_conn = mysql_query($sql_vip);
    $vip_result = @mysql_fetch_array($vip_conn) ;
}

?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=gb2312">
        <title>账号查询</title>
        <link href="CSS/Style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="JS/jquery-1.3.2.min.js"></script>

    </head>
    <body class="main">
        <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
            <form name="form1" method="POST" enctype="multipart/form-data" action="">
                <input type="hidden" name="action" value="select">
                <tr>
                    <th colspan="2" align="center">游戏账号查询</th>
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
                    <td align="right" class="forumRow">&nbsp;&nbsp;角色名：</td>
                    <td class="forumRow">
                        <input type="text" name="player_name" value="<?php echo $_POST['player_name']?>" />优先根据角色名查询账号信息
                    </td>
                    <td class="forumRow">&nbsp;</td>
                </tr>
                <tr>
                    <td align="right" class="forumRow">&nbsp;&nbsp;角色id：</td>
                    <td class="forumRow">
                        <input type="text" name="player_id" value="<?php echo $_POST['player_id']?>" />优先根据角色id查询账号信息
                    </td>
                    <td class="forumRow">&nbsp;</td>
                </tr>


                <tr>
                    <td align="right" class="forumRow">游戏分类：</td>
                    <td class="forumRow">
                        <select name='type'>
                            <option value="1" <? if($_POST['type']==1){echo "selected";} ?>  >账号</option>
                            <option value="2" <? if($_POST['type']==2){echo "selected";} ?> >账号id</option>
                            <option value="3" <? if($_POST['type']==3){echo "selected";} ?> >渠道账号</option>
                        </select>
                    </td>
                </tr>
                <tr><td align="right" class="forumRow"> 游戏账号或者id:</td><td><input name="account_info" value="<?php echo $_POST['account_info']?>"/></td></tr>
                <tr align="right" class="forumRow"><td><input type="submit" name="提交" value="提交"/></td></tr>
            </form>
        </table>
        <table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">
            <tr height="22">
                <th  height="22" align="center">账号id</th>
                <th  height="22" align="center">账号</th>
                <th  height="22" align="center">vip等级</th>
                <th  height="22" align="center">总积分</th>
                <th  height="22" align="center">剩余积分</th>
                <th  height="22" align="center">分包id</th>
                <th  height="22" align="center">渠道账号</th>
            </tr>

            <tr>
                <td nowrap class="forumRow" align="center"><?php echo $account_result['id']; ?></td>
                <td nowrap class="forumRow" align="center"><?php echo $account_result['NAME']; ?></td>
                <td nowrap class="forumRow" align="center"><?php echo intval($vip_result['vip']); ?></td>
                <td nowrap class="forumRow" align="center"><?php echo intval($vip_result['pointstotal']); ?></td>
                <td nowrap class="forumRow" align="center"><?php echo intval($vip_result['points']); ?></td>
                <td nowrap class="forumRow" align="center"><?php echo $account_result['dwFenBaoID']; ?></td>
                <td nowrap class="forumRow" align="center"><?php echo $account_result['channel_account']; ?></td>
            </tr>

        </table>
    </body>


</html>
<script type="text/javascript">
    function change_game(game_id){
        $.post("ajax/game.php", { action: "change_game", game_id: game_id },
        function(data){
            $("#ServerID").html(data);
        });
    }
</script>