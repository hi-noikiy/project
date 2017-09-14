<?php
include("inc/CheckUser.php");
include("inc/config.php");
include("../inc/config.php");
include("../inc/function.php");
include("inc/function.php");
include("inc/game_config.php");

if($_REQUEST['action']=='doaction'){
    
    $sid_arr = $_POST['sid'];
    $server_list =  $game_arr[5]['server_list'];
    for($i=0;$i<count($sid_arr);$i++){
        add($sid_arr[$i],$server_list);
    }


}
function add($SvrID,$server_list){

    $game_id = $_POST['game_id'];
    
    $photo_1 = intval($_POST['photo_1']);
    $photo_2 = intval($_POST['photo_2']);
    $photo_3 = intval($_POST['photo_3']);
    
    $site_1 = mysql_escape_string($_POST['site_1']);
    $site_2 = mysql_escape_string($_POST['site_2']);
    $site_3 = mysql_escape_string($_POST['site_3']);
    $site_4 = mysql_escape_string($_POST['site_4']);
    $site_5 = mysql_escape_string($_POST['site_5']);
    $site_6 = mysql_escape_string($_POST['site_6']);
    $message = mysql_escape_string($_POST['message']);
    $request = serialize($_REQUEST);

    if(!$SvrID){
        echo '<script language="javascript" >alert("服务器不能为空!");</script>';exit;
    }
    if(!$message){
        echo '<script language="javascript" >alert("公告信息不能为空!");</script>';exit;
    }

    SetConn($SvrID);

    //版本号控制在 20000 到 30000之间
    $sql_max = " select max(version) version_max from u_edition  ";
    $sql_result_max = mysql_query($sql_max);
    $result_max = mysql_fetch_array($sql_result_max);
    $version_max = $result_max['version_max'];
    $version = intval($version_max)+1;

    $sql = " insert into u_edition(version,Context_01,photo_01,active_name_01,describe_01,photo_02,active_name_02,describe_02,photo_03,active_name_03,describe_03)
             values('$version','$message','$photo_1','$site_1','$site_2','$photo_2','$site_3','$site_4','$photo_3','$site_5','$site_6') ";
    $sql_result = mysql_query($sql);
    write_log_admin(ROOT_PATH."log","customer_bulletin_log_"," sql_result=$sql_result,sql=$sql, $request, ".$_SESSION['admin_name']." ".date("Y-m-d H:i:s")."\r\n");
    if($sql_result){
        echo '<script language="javascript" >alert("'.$server_list[$SvrID].'提交成功!");</script>';return;
    }else{
        echo '<script language="javascript" >alert("'.$server_list[$SvrID].'提交失败!");</script>';return;
    }
    
}

?>

<html>
    <head>
        <!-- <meta http-equiv="Content-Type" content="text/html; charset=gbk"> -->
        <title>客服命令</title>
        <link href="CSS/Style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="JS/jquery-1.3.2.min.js"></script>

    </head>
    <body class="main">
        <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
            <form name="form1" method="POST" target="_blank" enctype="multipart/form-data" action="">
                <input type="hidden" name="action" value="doaction">
                <tr>
                    <th colspan="2" align="center">公告</th>
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
                
                <tr><td align="right" class="forumRow"> 图片一 :</td>
                    <td>
                    <select name="photo_1">
                       <option value="5004">活动</option>
                       <option value="5006">锦囊</option>
                    </select>
                    </td>
                </tr>

                <tr><td align="right" class="forumRow"> 位置一 :</td>
                    <td><input name="site_1" value=""/></td>
                </tr>

                <tr><td align="right" class="forumRow"> 位置二:</td>
                    <td><input name="site_2" value=""/></td>
                </tr>
                <tr><td align="right" class="forumRow"> 图片二:</td>
                    <td>
                    <select name="photo_2">
                       <option value="5004">活动</option>
                       <option value="5006">锦囊</option>
                    </select>
                    </td>
                </tr>
                <tr><td align="right" class="forumRow"> 位置三 :</td>
                    <td><input name="site_3" value=""/></td>
                </tr>
                <tr><td align="right" class="forumRow"> 位置四 :</td>
                    <td><input name="site_4" value=""/></td>
                </tr>

                <tr><td align="right" class="forumRow"> 图片三:</td>
                    <td>
                    <select name="photo_3">
                       <option value="5004">活动</option>
                       <option value="5006">锦囊</option>
                    </select>
                    </td>
                </tr>
                <tr><td align="right" class="forumRow"> 位置五 :</td>
                    <td><input name="site_5" value=""/></td>
                </tr>
                <tr><td align="right" class="forumRow"> 位置六 :</td>
                    <td><input name="site_6" value="" /></td>
                </tr>

                <tr>
                    <td align="right" class="forumRow">公告消息(最大120长度,字符不超过240)   :</td>
                    <td>
                        <textarea name="message" cols="50" rows="10" ><?php echo $message;?></textarea>
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
