<?php
include("inc/CheckUser.php");
include("inc/config.php");
include("../inc/config.php");
include("../inc/function.php");
include("inc/function.php");
include("inc/game_config.php");

if (!getFlag('2014',$_SESSION['u_flag'])){
    header("Location: Adm_Login.php");exit;
}

if($_REQUEST['action']=='doaction'){

    $player_name = $_POST['player_name'];
    $game_id = $_POST['game_id'];
    $SvrID = $_POST['SvrID'];
    $command_type = 8;
    $yuanbao = intval($_POST['yuanbao']);

    $request = serialize($_REQUEST);
    $ip = getIP_front();

    if(!$yuanbao){
        echo '<script language="javascript" >alert("元宝数不能为空!");</script>';exit;
    }

    if(!$SvrID){
        echo '<script language="javascript" >alert("server  is not null!");</script>';exit;
    }

    SetConn($SvrID);
    $sql = " select * from u_player where name='".iconv('UTF-8', 'GBK', $player_name) ."' ";
    $query = mysql_query($sql);
    $player_info= mysql_fetch_array($query);
    $player_id = $player_info['id'];
    if(!$player_id){
        echo '<script language="javascript" >alert("角色名不存在,角色名不能为空!");</script>';exit;
    }

    $sql = "insert into u_gmtool(type,message) values('$command_type','".mysql_escape_string("'getemoney $player_id $yuanbao'")."')";
    $query_result =   mysql_query($sql);

    write_log(ROOT_PATH."log","customer_command_view_","ip=$ip   , player_name=$player_name,game_id=$game_id,server_id=$SvrID,command_type=$command_type,prohibit_time=$prohibit_time,message=$message,yuanbao=$yuanbao,    ".$AdminName."  ".date("Y-m-d H:i:s")."\r\n");

    if($query_result){
        write_log(ROOT_PATH."log","customer_command_log_","ip=$ip   request=$request, ".$AdminName.date("Y-m-d H:i:s")."\r\n");
        echo '<script language="javascript" >alert("成功!");</script>';exit;
    }

    write_log(ROOT_PATH."log","customer_command_error","error: ".$sql.mysql_error()." ip=$ip   request=$request, ".$AdminName.date("Y-m-d H:i:s")."\r\n");
    echo '<script language="javascript" >alert(" fail !");</script>';exit;
}


?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=gbk">
        <title>发送元宝</title>
        <link href="CSS/Style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="JS/jquery-1.3.2.min.js"></script>

    </head>
    <body class="main">
        <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
            <form name="form1" method="POST" target="_blank" enctype="multipart/form-data" action="">
                <input type="hidden" name="action" value="doaction">
                <tr>
                    <th colspan="2" align="center">发送元宝</th>
                </tr>
                <tr>
                    <td align="right" class="forumRow">游戏</td>
                    <td class="forumRow">
                        <select name='game_id' onchange="change_game(this.value)">
                            <option value="">选择游戏</option>
                            <?php
                            if($game_arr){
                                foreach ($game_arr as $key=>$val){
                                    echo "<option value='$key' ".(($key==$_POST['game_id'])?"selected":"")." >".$val['name']."</option>";
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right" class="forumRow">服务器</td>
                    <td class="forumRow">
                        <select name="SvrID" id="ServerID">
                            <option value="" selected="selected">选择服务器</option>
                            <?php
                            foreach($game_arr[$_POST['game_id']]['server_list'] as $game_key=>$game_value){
                                echo "<option value=\"".$game_key."\" ".(($_POST['SvrID']==$game_key)?'selected="selected"':'')." >".$game_value."</option>";
                            }
                            ?>
                        </select>
                    </td>

                </tr>


                <tr><td align="right" class="forumRow"> 角色名 :</td><td><input name="player_name" value="<?php echo $_POST['player_name']?>"/></td></tr>


                <tr>
                    <td align="right" class="forumRow">元宝（只能填写数字）   :</td>
                    <td>
                        <input name="yuanbao" value="<?php echo $yuanbao; ?>"/>
                    </td>
                </tr>

                <tr align="right" class="forumRow"><td><input type="submit" name="submit" value="提交"/></td></tr>
            </form>
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
