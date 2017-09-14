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
$str_time = $_REQUEST['str_time'];
$end_time = $_REQUEST['end_time'];
$SvrID = $_REQUEST['SvrID'];
$server_id = $_REQUEST['SvrID'];

$str_time_int = date('ymdHi', strtotime($str_time)) ;
$end_time_int = date('ymdHi', strtotime($end_time)) ;

if($_REQUEST['action']=='select'&&$game_id){

    $game_server = $gameList[$game_id]['game_server_id'];
    SetConn($game_server);

    $vip_sql = " select * from u_vipgoods  ";
    $vip_query = mysql_query($vip_sql);
    while ($row = mysql_fetch_array($vip_query)){
        $result_vip[$row['id']] = $row;
        $vip_id[] = $row['id'];
    }
    // print_r($result_qu_in);
    for($i=0;$i<count($vip_id);$i++){
        if($i==0){
            $vip_id_str = $vip_id[$i];
        }else{
            $vip_id_str = $vip_id_str.",".$vip_id[$i];
        }
    }
 
    $sql = " select itemtype,count(emoney) count_money from rmb  where itemtype in($vip_id_str)  ";
    if($server_id){
        $sql = $sql." and serverid='$server_id' ";
    }
    if($str_time){
        $sql = $sql." and daytime>='$str_time_int' ";
    }
    if($end_time){
        $sql = $sql." and daytime<='$end_time_int' ";
    }

    $sql = $sql."  group by itemtype ";
    $query = mysql_query($sql);
    while ($row = mysql_fetch_array($query)){
        $result[] = $row;
    }

    for($i=0;$i<count($result);$i++){
        $result[$i]['price'] = $result_vip[$result[$i]['itemtype']]['price'];
        $result[$i]['name'] = $result_vip[$result[$i]['itemtype']]['name'];
        $result[$i]['type'] = $result_vip[$result[$i]['itemtype']]['type'];
        $sum_money = $sum_money + $result[$i]['price']*$result[$i]['count_money'];
    }
    


}



?>
<html>
    <head>
        <title>list</title>
        <meta http-equiv="Content-Type" content="text/html; charset=gb2312">
        <link href="CSS/Style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="JS/calendar.js"></script>
        <script language="javascript" src="JS/jquery-1.3.2.min.js"></script>
        <script language="javascript" src="JS/ActionFrom.js"></script>
    </head>
    <body class="main">
        <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
            <form name="SearchForm" method="POST" action="">
                <input type="hidden" name="action" value="select">
                <tr>
                    <th height="22" colspan="2" align="center">仙晶销售查询</th>
                </tr>

                <tr>
                    <td width="15%" align="right" class="forumRow">游戏：</td>
                    <td width="85%" class="forumRow">
                        <select name="game_id" onchange="change_game(this.value)">
                            <option value="0">无</option>
                            <?php
                            if($gameList){
                                foreach ($gameList as $key=>$val){
                                    echo "<option value='$key' ".($game_id==$key?'selected':'')." >$val[name]</option>";
                                }
                            }
                            ?>
                        </select>
                        游戏不能为空
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
                        <input name="str_time" type="text" size="12" value="<?=$str_time?>" readonly onfocus="HS_setDate(this)">--
                        <input name="end_time" type="text" size="12" value="<?=$end_time?>" readonly onfocus="HS_setDate(this)">
                    </td>
                </tr>
                <tr>
                    <td align="right" class="forumRow"></td>
                    <td class="forumRow"><input type="submit" name="Submit2" class="bott01" value=" 确 定 ">
                        游戏、时间 为必选
                    </td>
                </tr>
            </form>
        </table>

        <table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">
            <tr height="22">
                <th  height="22" align="center">编号</th>
                <th  height="22" align="center">类型</th>
                <th  height="22" align="center">名称</th>
                <th  height="22" align="center">价格</th>
                <th  height="22" align="center">数量</th>
                <th  height="22" align="center">总额</th>
                <th  height="22" align="center">比重</th>


            </tr>
            <?php if(count($result)){ foreach($result as $key => $value){?>
            <tr>
                <td nowrap class="forumRow" align="center"><?php  echo $key; ?></td>
                <td nowrap class="forumRow" align="center"><?php  echo $value['type']; ?></td>
                <td nowrap class="forumRow" align="center"><?php  echo $value['name']; ?></td>
                <td nowrap class="forumRow" align="center"><?php  echo $value['price']; ?></td>
                <td nowrap class="forumRow" align="center"><?php  echo $value['count_money']; ?></td>
                <td nowrap class="forumRow" align="center"><?php  echo $value['price']*$value['count_money']; ?></td>
                <td nowrap class="forumRow" align="center"><?php  echo @number_format($value['price']*$value['count_money']*100/$sum_money,2)."%"; ?></td>
            </tr>
            <?php  } } ?>
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