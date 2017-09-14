<?php
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");
include("inc/page.php");
include("../inc/config.php");
include("../inc/function.php");
include("inc/game_config.php");

if($_REQUEST['action']=='update'){
    $id = intval($_REQUEST['id']);
    $stauts = intval($_REQUEST['stauts']);
    if($id&&$stauts){
        $update_sql = " update kf_message set status='$stauts' where id='$id' ";
        if(mysql_query($update_sql)){
            echo '<script language="javascript">alert("修改状态已经完成，请刷新页面");</script>';
           exit;
        }
    }
}


if($_REQUEST['action']=='save'){
    $game_id = $_REQUEST['game_id'];
    $server_id = $_REQUEST['server_id'];
    $player_id = $_REQUEST['player_id'];
    $account_id = $_REQUEST['account_id'];
    $player_name = $_REQUEST['player_name'];
    //$account = $_REQUEST['account'];
    $reply_content =  $_REQUEST['reply_content'];
    $reply_content = substr($reply_content, 0, 80);
    $admin_name = $_SESSION['admin_name'];
    if($reply_content&&$admin_name){
        $reply_content = mysql_escape_string($reply_content);
        $time = time();
        $sql = " insert into kf_reply(server_id,player_id,admin_name,reply_content,time) values('$server_id','$player_id','$admin_name','$reply_content','$time') ";
        if(mysql_query($sql)){
            $update_sql = " update kf_message set status=1 where server_id='$server_id' and player_id='$player_id' ";
            mysql_query($update_sql);

            SetConn($server_id);
            $time_stamp=date('ymdHi');
            mysql_query("set names latin1");
            $sql="insert into u_leaveword(user_name,send_name,time,words)";
            $sql=$sql." values('$player_name','GM',$time_stamp,'$reply_content')";
            if(!mysql_query($sql)){
                echo '<script language="javascript">alert("游戏数据库写入异常'.$server_id.'");</script>';
            }

        }
    }
}


SetConn(88);
$player_id = $_REQUEST['player_id'];
$server_id = $_REQUEST['server_id'];

$sql = " select * from kf_message where server_id = '$server_id' and player_id='$player_id' ";
$query=mysql_query($sql);
while ($row = mysql_fetch_array($query)){
    $result[] =$row;
}

$sql_reply = " select kf.* from kf_reply kf  where kf.player_id = '$player_id' and  kf.server_id = '$server_id' ";
$query_reply=mysql_query($sql_reply);

while ($row = mysql_fetch_array($query_reply)){
    $reply_arr[] = $row;
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
            <tr>
                <th colspan="2" align="center">玩家留言管理</th>
            </tr>
            <tr>
                <td align="right" class="forumRow">游戏分类：</td>
                <td class="forumRow">
                    <?php echo $game_arr[$result[0]['game_id']]['name'];  ?>
                </td>
            </tr>
            <tr>
                <td align="right" class="forumRow">服务区：</td>
                <td class="forumRow">
                    <?php echo $game_arr[$result[0]['game_id']]['server_list'][$result[0]['server_id']];  ?>
                </td>
            </tr>
            <tr>
                <td align="right" class="forumRow">账号/id：</td>
                <td class="forumRow">
                    <?php echo $result[0]['account']."/".$result[0]['account_id']; ?>
                </td>
            </tr>
            <tr>
                <td align="right" class="forumRow">角色名/id：</td>
                <td class="forumRow">
                    <?php echo $result[0]['player_id']."/".$result[0]['player_name']; ?>
                </td>
            </tr>

            <?php for($i=0;$i<count($result);$i++){ ?>
            <tr>
                <td align="right" class="forumRow">玩家留言：</td>
                <td class="forumRow">
                    <?php  echo $result[$i]['message']; ?> <?php  echo date("Y-m-d H:i:s", $result[$i]['time']); ?>
                </td>
            </tr>
            <?php } ?>
        <?php for($i=0;$i<count($reply_arr);$i++){ ?>
            <tr>
                <td align="right" class="forumRow"><?php echo $reply_arr[$i]['admin_name']; ?>回复(<?php echo date("Y-m-d H:i:s", $reply_arr[$i]['time']) ; ?>)：</td>
                <td class="forumRow">
                    <?php echo $reply_arr[$i]['reply_content']; ?>
                </td>
            </tr>
            <?php } ?>
        </table>
        <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
            <form name="form1" method="POST" enctype="multipart/form-data" action="?action=save">
                <input name="id" value="<?php echo $id; ?>" type="hidden">
                <input name="game_id" value="<?php echo $result[0]['game_id']; ?>" type="hidden">
                <input name="server_id" value="<?php echo $result[0]['server_id']; ?>" type="hidden">
                <input name="account_id" value="<?php echo $result[0]['account_id']; ?>" type="hidden">
                <input name="account" value="<?php echo $result[0]['account']; ?>" type="hidden">
                <input name="player_id" value="<?php echo $result[0]['player_id']; ?>" type="hidden">
                <input name="player_name" value="<?php echo $result[0]['player_name']; ?>" type="hidden">
                <tr>
                    <td align="right" class="forumRow">回复(不超过80个字，超过自动截取)：</td>
                    <td class="forumRow">
                        <textarea name="reply_content" cols="50" rows="10"></textarea>
                    </td>
                </tr>
                <tr align="right" class="forumRow">
                    <td  class="forumRow">&nbsp; </td>
                <td align="left"><input type="submit" name="submit" value="提交"/>  </td></tr>
            </form>
        </table>

    </body>


</html>