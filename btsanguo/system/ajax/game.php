<?php
require_once '../inc/game_config.php';
if($_POST['action']=='change_game'&&$_POST['game_id']){//改变游戏id获取游戏区相关情况
    $aStr = '<option value="">请选择分区</option><option value="all">全服</option>';
    foreach ($game_arr[intval($_POST['game_id'])]['server_list'] as $aKey=>$aValue){
        $aStr=$aStr."<option value=\"$aKey\">".$aValue."</option>\n";
    }
    echo $aStr;exit;
}
if($_POST['action']=='game_no'&&$_POST['game_id']){
    require_once '../inc/config.php';
    $SqlMain="select VersionNO from client_ver where game_id=".intval($_POST['game_id']);
    $conn=mysql_query($SqlMain);
    $rs=mysql_fetch_array($conn);
    echo $rs['VersionNO'];exit;
}
?>
