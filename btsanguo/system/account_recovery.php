<?php
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");
include("inc/game_config.php");
include("../inc/config.php");
if($_REQUEST['action']=="reply"){//恢复账号
    if($_GET['game_id']==''||$_GET['SvrID']==''||$_GET['name']==''||$_GET['u_id']==''){
        echo "<script type=\"text/javascript\">alert(\"参数异常\");</script>";exit;
    }
    $name = mysql_escape_string(urldecode(strtolower($_GET['name'])));
    $u_id = $_GET['u_id'];
    SetConn($_GET['SvrID']);
    $sql = " select id from u_player where name = '".$name."' ";

    $conn = mysql_query($sql);
    $num_rows = mysql_num_rows($conn);
    if($num_rows==0){
        $sql = " insert into u_player select * from u_delplayer  where id = '".$u_id."' ";
        if(mysql_query($sql)){
            $sql = " delete from u_delplayer  where id = '".$u_id."' ";
            if(mysql_query($sql)){
                 echo "<script type=\"text/javascript\">alert(\"恢复成功\");</script>";
            }else{
                 echo "<script type=\"text/javascript\">alert(\"恢复成功,u_delplayer中数据无法删除\");</script>";
            }
            
        }else{
            echo "<script type=\"text/javascript\">alert(\"恢复失败，可能角色位置已经被其他角色占用\");</script>";
        }
    }else{
        echo "<script type=\"text/javascript\">alert(\"您输入的游戏人物名称可能已经重复\");</script>";
    }
    exit;

}
if($_REQUEST['action']=="check"){
    if(check($_POST)){
        //连接账号库,获取账号id
        SetConn(81);
        $account_sql = " select id from account where name = '".$_POST['account_name']."' ";
        $account_conn = mysql_query($account_sql);
        $account_result = mysql_fetch_array($account_conn) ;
        $account_id = intval($account_result['id']) ;

        SetConn($_POST['SvrID']);
        $del_sql = " select * from u_delplayer where account_id = $account_id";
        //  $del_sql = " select * from u_delplayer1 where account_id = $account_id";
        $del_conn = mysql_query($del_sql);
        $del_arr;
        while($rs=mysql_fetch_array($del_conn)){
            $del_arr[] = $rs;
        }

    }
}
function check($info){
    if($info['game_id']==''){
        echo "<script type=\"text/javascript\">alert(\"游戏类型不能为空\");</script>";
        return false;
    }
    if($info['SvrID']==''){
        echo "<script type=\"text/javascript\">alert(\"游戏分区不能为空\");</script>";
        return false;
    }
    if($info['account_name']==''){
        echo "<script type=\"text/javascript\">alert(\"请输入游戏账号\");</script>";
        return false;
    }
    return true;
}
?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=gb2312">
        <title>账号恢复</title>
        <link href="CSS/Style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="JS/jquery-1.3.2.min.js"></script>

    </head>
    <body class="main">
        <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
            <form name="form1" method="POST" enctype="multipart/form-data" action="account_recovery.php?action=check">
                <tr>
                    <th colspan="2" align="center">游戏账号恢复</th>
                </tr>
                <tr>
                    <td align="right" class="forumRow">游戏分类：</td>
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
                    <td align="right" class="forumRow">服务区：</td>
                    <td class="forumRow">
                        <select name="SvrID" id="ServerID">
                            <option value="" selected="selected">请选择分区</option>
                            <?php
                            foreach($game_arr[$_POST['game_id']]['server_list'] as $game_key=>$game_value){
                                echo "<option value=\"".$game_key."\" ".(($_POST['SvrID']==$game_key)?'selected="selected"':'')." >".$game_value."</option>";
                            }
                            ?>
                        </select>
                    </td>

                </tr>
                <tr><td align="right" class="forumRow"> 游戏账号:</td><td><input name="account_name" value="<?php echo $_POST['account_name']?>"/></td></tr>
                <tr align="right" class="forumRow"><td><input type="submit" name="提交" value="提交"/></td></tr>
            </form>
        </table>
        <table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">
            <tr height="22">
                <th width="15%" height="22" align="center">游戏</th>
                <th width="15%" height="22" align="center">分区</th>
                <th width="15%" height="22" align="center">角色名称</th>
                <th width="15%" height="22" align="center">角色等级</th>
                <th width="25%" height="22" align="center">操作</th>
            </tr>
            <?php
            for($i=0;$i<count($del_arr);$i++){
                ?>
            <tr>
                <td nowrap class="forumRow" align="center"><?php echo $game_arr[$_POST['game_id']]['name'] ?></td>
                <td nowrap class="forumRow" align="center"><?php echo $game_arr[$_POST['game_id']]['server_list'][$_POST['SvrID']] ?></td>
                <td nowrap class="forumRow" align="center"><?php echo $del_arr[$i]['name'] ?></td>
                <td nowrap class="forumRow" align="center"><?php echo $del_arr[$i]['level'] ?></td>
                <td nowrap class="forumRow" align="center"><a href="?action=reply&game_id=<?php echo $_POST['game_id'] ?>&SvrID=<?php echo $_POST['SvrID'] ?>&name=<?php echo urlencode($del_arr[$i]['name']) ?>&u_id=<?php echo urlencode($del_arr[$i]['id']) ?>" target="_blank">恢复</a></td>
            </tr>
            <?php } ?>
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
