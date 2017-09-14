<?php
include("inc/config.php");
include(ROOT_PATH."inc/config.php");
include(ROOT_PATH."system/inc/CheckUser.php");
include("inc/game_config.php");


$game_id = $_POST['game_id'];
$account_id = $_POST['account_id'];
$sid = $_POST['sid'];
$message = $_POST['message'];

if($_POST['action']=='select'){

    SetConn(81);
    $where_account = '';
    if (!empty($_POST['account'])) {
        $where_account .= " AND name='" . trim($_POST['account']) . "'";
    }
    if (!empty($_POST['account_id'])) {
        $account_id = intval($_POST['account_id']);
        $where_account .= " AND id='$account_id' ";
    }
    $sql_account = "SELECT id FROM account WHERE 1=1 $where_account LIMIT 1";
    $res_account = @mysql_query($sql_account);
    $rs_account  = @mysql_fetch_array($res_account);

    $account_id = $rs_account['id'];
    if(!$account_id){
        echo '发送失败,未找到角色';
        exit('<script >alert("发送失败,未找到角色1");</script>');
    }
    mysql_close();

    SetConn($sid);
    $where = '';
    $sql_player = " select id,name from u_player where account_id='$account_id' and serverid='$sid' LIMIT 1";
    echo $sql_player;
    $res_player = @mysql_query($sql_player);
    $rs_player  = @mysql_fetch_array($res_player);

    $player_id = $rs_player['id'];
    $name = $rs_player['name'];
    if(!$player_id){
        echo '发送失败,未找到角色';
        exit('<script >alert("发送失败,未找到角色2");</script>');
    }

    $message = mysql_real_escape_string($message);
    $time = time();

    $sql = "INSERT INTO `u_mailing` (player_id, player_name, type, obj_id, obj_name, time, sender_name, obj_num, words)
             VALUES ($player_id,'$name',0,@@identity,'系统发送',$time,'系统',1,'$message')";


    if(mysql_query($sql)){
        exit('<script >alert("发送成功");</script>');
    }else{
        exit('<script >alert("发送失败");</script>');
    }
}

?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=gb2312">
    <title>发送邮件</title>
    <link href="CSS/Style.css" rel="stylesheet" type="text/css">
    <script language="javascript" src="JS/jquery-1.3.2.min.js"></script>

</head>
<body class="main">
<form name="form1" method="POST" target="hideFrame"  action="">
<input type="hidden" name="action" value="select">	
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
    
        
        <tr>
            <th colspan="2" align="center">游戏角色发送邮件</th>
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

        <tr><td align="right" class="forumRow"> 邮件内容:</td><td><textarea name="message" style="height:200px; width:300px;"></textarea>   </td></tr>

        <tr align="right" class="forumRow"><td><input type="submit" name="提交" value="提交"/></td></tr>
    
</table>
</form>
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