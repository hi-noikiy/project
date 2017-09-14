<?php
//header('Content-type: text/html; charset=gbk');
require_once '../inc/game_config.php';
include("../inc/config.php");
if($_REQUEST['action']=='change_ctype'&&$_REQUEST['game_id']){//改变游戏id获取游戏区相关情况
    $aStr = '<option value="">&mdash;&mdash;请选择分类&mdash;&mdash;</option>\n';

    $sql = " select * from down_class where game_id=".$_REQUEST['game_id']." and IsHide =0 order by OrderID ";
    $conn = mysql_query($sql);
    while($rs=mysql_fetch_array($conn)){
          $aStr=$aStr."<option value=\"".$rs['ClassID']."\">".$rs['ClassName']."</option>\n";
       }
    echo $aStr;exit;
}
?>
