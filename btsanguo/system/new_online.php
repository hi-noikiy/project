<?php
include("inc/config.php");
include("inc/CheckUser.php");
include("inc/function.php");
include("inc/page.php");
include("../inc/config.php");
include("../inc/function.php");
include("inc/game_config.php");
$gameList = $game_arr;

$game_id = $_REQUEST['game_id'];
$clienttype = $_REQUEST['clienttype'];
$str_time = $_REQUEST['str_time'];
$end_time = $_REQUEST['end_time'];
$SvrID = $_REQUEST['SvrID'];

SetConn(88);
$sql_fenbao = " select * from user_fenbao ";
$query_fenbao = mysql_query($sql_fenbao);
while ($row = mysql_fetch_array($query_fenbao)){
    $result_fenbao[] = $row;
}

if($_REQUEST['action']=='select'){

    $game_id = $_REQUEST['game_id'];
    $str_time = $_REQUEST['str_time'];
    $server_id = $_REQUEST['SvrID'];
    $str_time_int = strtotime($str_time);
    if($game_id&&$str_time){
        $result[$str_time_int] =   get_data($game_id,$server_id,$str_time_int,$gameList);
        $result[$str_time_int+3600*24*1] =   get_data($game_id,$server_id,$str_time_int+3600*24*1,$gameList);
        $result[$str_time_int+3600*24*2] =   get_data($game_id,$server_id,$str_time_int+3600*24*2,$gameList);
        $result[$str_time_int+3600*24*3] =   get_data($game_id,$server_id,$str_time_int+3600*24*3,$gameList);
        $result[$str_time_int+3600*24*4] =   get_data($game_id,$server_id,$str_time_int+3600*24*4,$gameList);
        $result[$str_time_int+3600*24*5] =   get_data($game_id,$server_id,$str_time_int+3600*24*5,$gameList);
        $result[$str_time_int+3600*24*6] =   get_data($game_id,$server_id,$str_time_int+3600*24*6,$gameList);
        $result[$str_time_int+3600*24*7] =   get_data($game_id,$server_id,$str_time_int+3600*24*7,$gameList);
        $result[$str_time_int+3600*24*8] =   get_data($game_id,$server_id,$str_time_int+3600*24*8,$gameList);
        $result[$str_time_int+3600*24*9] =   get_data($game_id,$server_id,$str_time_int+3600*24*9,$gameList);

    }
}

//查询当前在线
$game_id = $_REQUEST['game_id']?$_REQUEST['game_id']:5;
$game_server = $gameList[$game_id]['game_server_id'];
$server_name =  $gameList[$game_id]['server_list'][$server_id];
SetConn($game_server);
mysql_query("SET NAMES 'latin1'");
$sql_now_id  = " select max(id) id from  online ol group by servername  ";
$query_now_id = mysql_query($sql_now_id);

while ($row = mysql_fetch_array($query_now_id)){
    $array_id[] = $row['id'];
}
for($i=0;$i<count($array_id);$i++){
   if($i==0){
      $id_str = $array_id[$i];
   }else{
        $id_str =$id_str.",".$array_id[$i];
   }
}

$sql_now = " select *  from  online ol   where id in($id_str)  ";
$query_now = mysql_query($sql_now);
while ($row = mysql_fetch_array($query_now)){
    $array_now[] = $row;
}

function get_data($game_id,$server_id,$time_begin,$gameList){

    $game_server = $gameList[$game_id]['game_server_id'];
    $server_name =  $gameList[$game_id]['server_list'][$server_id];
    $time_begin_str = date("ymdHi", $time_begin);
    $time_end_str = date("ymdHi", $time_begin+3600*24);

    SetConn($game_server);
    mysql_query("SET NAMES 'latin1'");

    $str_condition = "  daytime>='$time_begin_str' and daytime<='$time_end_str' ";
    if($server_id){
        //$server_name = iconv("gb2312","UTF-8",$server_name);
        $str_condition = $str_condition." and servername='$server_name' ";
    }

    $sql_sum = " select sum(online) sum_online,sum(MaxOnline) sum_MaxOnline,sum(WorldOnline) sum_WorldOnline,sum(WorldMaxOnline) sum_WorldMaxOnline,max(online) max_online ,max(MaxOnline) max_MaxOnline ,max(WorldOnline) max_WorldOnline ,max(WorldMaxOnline) max_WorldMaxOnline ,count(id) count_id  from online where $str_condition ";

    $query_sum = mysql_query($sql_sum);
    $result = mysql_fetch_array($query_sum);
    return $result;
}


?>
<html>
    <head>
        <title>list</title>
        <!-- <meta http-equiv="Content-Type" content="text/html; charset=gbk2312"> -->
        <link href="CSS/Style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="JS/calendar.js"></script>
        <script language="javascript" src="JS/jquery-1.3.2.min.js"></script>
    </head>
    <body class="main">
        <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
            <form name="SearchForm" method="POST" action="">
                <input type="hidden" name="action" value="select">
                <tr>
                    <th height="22" colspan="2" align="center">玩家留存率查询</th>
                </tr>
                <tr>
                    <td width="15%" align="right" class="forumRow">游戏：</td>
                    <td width="85%" class="forumRow">
                        <select name="game_id"  onchange="change_game(this.value)">
                            <option value="0">无</option>
                            <?php
                            if($gameList){
                                foreach ($gameList as $key=>$val){
                                    echo "<option value='$key' ".($game_id==$key?'selected':'')." >$val[name]</option>";
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
                            foreach($game_arr[$game_id]['server_list'] as $game_key=>$game_value){
                                echo "<option value=\"".$game_key."\" ".(($SvrID==$game_key)?'selected="selected"':'')." >".$game_value."</option>";
                            }
                            ?>
                        </select>
                    </td>
                    <td rowspan="2" class="forumRow">&nbsp;</td>
                </tr>


                <tr>
                    <td align="right" class="forumRow">查询时间：</td>
                    <td width="85%" class="forumRow">
                        <input name="str_time" type="text" size="12" value="<?=$str_time?>" readonly onfocus="HS_setDate(this)">
                        --
                        <!--
<input name="end_time" type="text" size="12" value="<?=$end_time?>" readonly onfocus="HS_setDate(this)">
                        -->
                    </td>
                </tr>
                <tr>
                    <td align="right" class="forumRow"></td>
                    <td class="forumRow"><input type="submit" name="Submit2" class="bott01" value=" 确 定 ">
                    </td>
                </tr>
            </form>
        </table>


        <DIV >&nbsp;最新在线</DIV>
        <table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">
            <tr height="22">
                <th  height="22" align="center">最新时间</th>
                <th  height="22" align="center">服务器</th>
                <th  height="22" align="center">在线</th>
                <th  height="22" align="center">最大在线</th>
                <th  height="22" align="center">世界在线</th>
                <th  height="22" align="center">最大世界在线</th>
            </tr>

  <?php for($i=0;$i<count($array_now);$i++){  ?>
            <tr>
                <td nowrap class="forumRow" align="center"><?php echo date("Y-m-d H:i:s", strtotime('20'.$array_now[$i]['daytime'])) ;?></td>
                <td nowrap class="forumRow" align="center"><?php echo $array_now[$i]['servername'];?></td>
                <td nowrap class="forumRow" align="center"><?php echo $array_now[$i]['online'];?></td>
                <td nowrap class="forumRow" align="center"><?php echo $array_now[$i]['MaxOnline']; ?></td>
                <td nowrap class="forumRow" align="center"><?php echo $array_now[$i]['WorldOnline']; ?></td>
                <td nowrap class="forumRow" align="center"><?php echo $array_now[$i]['WorldMaxOnline']; ?></td>
            </tr>
            <?php }  ?>

        </table>

        <DIV >&nbsp;在线人数</DIV>
        <table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">
            <tr height="22">
                <th  height="22" align="center">时间</th>
                <th  height="22" align="center">平均在线</th>
                <th  height="22" align="center">平均最大在线</th>
                <th  height="22" align="center">平均世界在线</th>
                <th  height="22" align="center">平均最大世界在线</th>
                <th  height="22" align="center">(最高)在线</th>
                <th  height="22" align="center">(最高)最大在线</th>
                <th  height="22" align="center">(最高)世界在线</th>
                <th  height="22" align="center">(最高)最大世界在线</th>
            </tr>
            <?php if(count($result)){foreach($result as $key=>$value){  ?>

            <tr>
                <td nowrap class="forumRow" align="center"><?php echo date("Y-m-d", $key);?></td>
                <td nowrap class="forumRow" align="center"><?php echo @number_format($value['sum_online']/$value['count_id'],2);?></td>
                <td nowrap class="forumRow" align="center"><?php echo @number_format($value['sum_MaxOnline']/$value['count_id'],2); ?></td>
                <td nowrap class="forumRow" align="center"><?php echo @number_format($value['sum_WorldOnline']/$value['count_id'],2); ?></td>
                <td nowrap class="forumRow" align="center"><?php echo @number_format($value['sum_WorldMaxOnline']/$value['count_id'],2); ?></td>

                <td nowrap class="forumRow" align="center"><?php echo $value['max_online'];?></td>
                <td nowrap class="forumRow" align="center"><?php echo $value['max_MaxOnline']; ?></td>
                <td nowrap class="forumRow" align="center"><?php echo $value['max_WorldOnline']; ?></td>
                <td nowrap class="forumRow" align="center"><?php echo $value['max_WorldMaxOnline']; ?></td>
            </tr>
            <?php } } ?>
        </table>
        <script type="text/javascript">
            function change_game(game_id){
                $.post("ajax/game.php", { action: "change_game", game_id: game_id },
                function(data){
                    $("#ServerID").html(data);
                });
            }
        </script>

    </body>

</html>