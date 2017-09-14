<?php
include("inc/config.php");
include("inc/function.php");
include("inc/game_config.php");
$action = $_REQUEST['action'];
$action = $action?$action:"list";
if($action=='save'){
    $sql = " insert into down_class(ClassName,IsHide,game_id,VersionNO) values('".$_POST['ClassName']."','".$_POST['IsHide']."','".$_POST['game_id']."','".$_POST['VersionNO']."') ";
    mysql_query($sql);
    echo "<script type=\"text/javascript\">alert(\"添加成功\");</script>";
    $action='list';
}
if($action=='update'){
    $sql = " update down_class set ClassName='".$_POST['ClassName']."',IsHide='".$_POST['IsHide']."',game_id='".$_POST['game_id']."',VersionNO='".$_POST['VersionNO']."' where ClassID = ".$_POST['ClassID'];
    mysql_query($sql);
    echo "<script type=\"text/javascript\">alert(\"修改成功\");</script>";
    $action='list';
}
if($action=='list'){
    if($_REQUEST['game_id']){
        $where = " where game_id =".$_REQUEST['game_id'];
    }
    $sql=" select * from down_class  ".$where;
    $conn=mysql_query($sql);
}


?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=gb2312">
        <title>手机型号管理</title>
        <link href="CSS/Style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="JS/jquery-1.3.2.min.js"></script>

    </head>
    <body class="main">
        <?php if($action=="edit"){
            $sql = " select * from down_class where ClassID =  ".$_REQUEST['class_id'];
            $conn = mysql_query($sql);
            $result = mysql_fetch_array($conn);
            
            ?>
        
        <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
            <form name="form1" method="POST" enctype="multipart/form-data" action="ctype.php?action=update">
                <input type="hidden" name="ClassID" value="<?php echo $result['ClassID']; ?>"/>
                <tr>
                    <th colspan="2" align="center">手机品牌管理</th>
                </tr>
                <tr>
                    <td align="right" class="forumRow">游戏分类：</td>
                    <td class="forumRow">
                        <select name='game_id' onchange="change_game(this.value)">
                            <?php
                            if($game_arr){
                                foreach ($game_arr as $key=>$val){
                                    echo "<option value='$key'".(($key==$result['game_id'])?"selected":"")." >".$val['name']."</option>";
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right" class="forumRow">品牌：</td>
                    <td class="forumRow">
                        <input name="ClassName" value="<?php echo $result['ClassName']; ?>" />
                    </td>
                </tr>
                <tr>
                    <td align="right" class="forumRow">版本：</td>
                    <td class="forumRow">
                        <input name="VersionNO" value="<?php echo $result['VersionNO']; ?>" />
                    </td>
                </tr>
                <tr>
                    <td align="right" class="forumRow">状态：</td>
                    <td class="forumRow">
                        <input type="radio" name="IsHide" value="0" <?php if($result['IsHide']==0){echo 'checked';}  ?> />显示
                        <input type="radio" name="IsHide" value="1"  <?php if($result['IsHide']==1){echo 'checked';}  ?> />屏蔽
                    </td>
                </tr>
                <tr align="right" class="forumRow"><td><input type="submit" name="保存" value="保存"/></td><td></td></tr>
            </form>
        </table>
        <?php } ?>
    <?php if($action=="add"){ ?>
        <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
            <form name="form1" method="POST" enctype="multipart/form-data" action="ctype.php?action=save">
                <tr>
                    <th colspan="2" align="center">手机品牌管理</th>
                </tr>
                <tr>
                    <td align="right" class="forumRow">游戏分类：</td>
                    <td class="forumRow">
                        <select name='game_id' onchange="change_game(this.value)">
                            <?php
                            if($game_arr){
                                foreach ($game_arr as $key=>$val){
                                    echo "<option value='$key' >".$val['name']."</option>";
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right" class="forumRow">品牌：</td>
                    <td class="forumRow">
                        <input name="ClassName" value="" />
                    </td>
                </tr>
                <tr>
                    <td align="right" class="forumRow">版本：</td>
                    <td class="forumRow">
                        <input name="VersionNO" value="" />
                    </td>
                </tr>
                <tr>
                    <td align="right" class="forumRow">状态：</td>
                    <td class="forumRow">
                        <input type="radio" name="IsHide" value="0" checked />显示
                        <input type="radio" name="IsHide" value="1"  />屏蔽
                    </td>
                </tr>
                <tr align="right" class="forumRow"><td><input type="submit" name="保存" value="保存"/></td><td></td></tr>
            </form>
        </table>
        <?php } ?>

    <?php if($action=="list"){ ?>
        <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
            <form name="form1" method="POST" enctype="multipart/form-data" action="ctype.php?action=list">
                <tr>
                    <th colspan="2" align="center">手机型号管理</th>
                </tr>
                <tr>
                    <td align="right" class="forumRow">游戏分类：</td>
                    <td class="forumRow">
                        <select name='game_id' onchange="change_game(this.value)">
                            <option value="">选择游戏</option>
                            <?php
                            if($game_arr){
                                foreach ($game_arr as $key=>$val){
                                    echo "<option value='$key' ".(($key==$_REQUEST['game_id'])?"selected":"")." >".$val['name']."</option>";
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>

                <tr align="right" class="forumRow"><td><input type="submit" name="查询" value="查询"/>  <a href="?action=add">添加</a></td></tr>
            </form>
        </table>
        <table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">
            <tr height="22">
                <th width="15%" height="22" align="center">游戏</th>
                <th width="15%" height="22" align="center">品牌</th>
                <th width="15%" height="22" align="center">版本</th>
                <th width="15%" height="22" align="center">状态</th>
                <th width="25%" height="22" align="center">操作</th>
            </tr>
            <?php
            while($rs=mysql_fetch_array($conn)){
                ?>
            <tr>
                <td nowrap class="forumRow" align="center"><?php echo $game_arr[$rs['game_id']]['name'] ?></td>
                <td nowrap class="forumRow" align="center"><?php echo $rs['ClassName'] ?></td>
                <td nowrap class="forumRow" align="center"><?php echo $rs['VersionNO'] ?></td>
                <td nowrap class="forumRow" align="center"><?php if($rs['IsHide']==0){ echo "显示"; }else{ echo "屏蔽";} ?></td>
                <td nowrap class="forumRow" align="center"><a href="?action=edit&class_id=<?php echo $rs['ClassID'] ?>" >编辑</a></td>
            </tr>
            <?php } ?>
        </table>
        <?php } ?>
    </body>


</html>