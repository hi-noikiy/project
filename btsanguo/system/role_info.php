<?php
include("inc/config.php");
include(ROOT_PATH."inc/config.php");
include(ROOT_PATH."system/inc/CheckUser.php");
include("inc/game_config.php");

if($_POST['action']=='select'){
    $game_id = $_POST['game_id'];
    $sid = $_POST['sid'];    
	$where = '';
	if (!empty($_POST['account'])) {
		$NAME = trim($_POST['account']);
		SetConn('81');
		$asql = "select id from account where NAME='$NAME'";
		$aquery = mysql_query($asql);
		$aInfo = mysql_fetch_assoc($aquery);	
		$account_id = intval($aInfo['id']);		
		$where .= " AND account_id=$account_id and serverid=$sid";
	}
	if (!empty($_POST['account_id'])) {
		$account_id = intval($_POST['account_id']);
		$where .= " AND account_id='$account_id' and serverid=$sid";
	}
	SetConn($sid);
    $sql_player = " select * from u_player where 1=1 $where";
    $res_player = mysql_query($sql_player);
    $rs_player = mysql_fetch_array($res_player);
}

?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=gb2312">
        <title>角色查询</title>
        <link href="CSS/Style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="JS/jquery-1.3.2.min.js"></script>

    </head>
    <body class="main">
        <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
            <form name="form1" method="POST" enctype="multipart/form-data" action="">
                <input type="hidden" name="action" value="select">
                <tr>
                    <th colspan="2" align="center">游戏角色查询</th>
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

                <tr><td align="right" class="forumRow"> 游戏账号id:</td><td><input name="account_id" value="<?php echo $_POST['account_id']?>"/></td></tr>
                <tr><td align="right" class="forumRow"> 账号:</td><td><input name="account" value="<?php echo $_POST['account']?>"/></td></tr>
                <tr align="right" class="forumRow"><td><input type="submit" name="提交" value="提交"/></td></tr>
            </form>
        </table>
        <table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">
            <tr height="22">
                <th  height="22" align="center">账号id</th>
                <th  height="22" align="center">服务器</th>
                <th  height="22" align="center">角色名</th>
                <th  height="22" align="center">角色id</th>
            </tr>

            <tr>
                <td nowrap class="forumRow" align="center"><?php echo $account_id; ?></td>
                <td nowrap class="forumRow" align="center"><?php echo $game_arr[$game_id]['server_list'][$sid]; ?></td>
                <td nowrap class="forumRow" align="center"><?php echo $rs_player['name']; ?></td>
                <td nowrap class="forumRow" align="center"><?php echo $rs_player['id']; ?></td>
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