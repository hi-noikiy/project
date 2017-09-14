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
$fenBaoID = $_REQUEST['fenBaoID'];

$clienttype = $_REQUEST['clienttype'];
$str_time = $_REQUEST['str_time'];
$SvrID = $_REQUEST['SvrID'];
$server_id = $_REQUEST['SvrID'];

$time = strtotime($str_time);

SetConn(88);
$sql_fenbao = " select * from user_fenbao ";
$query_fenbao = mysql_query($sql_fenbao);
while ($row = mysql_fetch_array($query_fenbao)){
    $result_fenbao[] = $row;
}


if($str_time&&$game_id){
    $result =   @get_data($time,$game_id,$server_id,$fenBaoID,$clienttype,$gameList);
    $result_1 = @get_data($time+3600*24*1,$game_id,$server_id,$fenBaoID,$clienttype,$gameList);
    $result_2 = @get_data($time+3600*24*2,$game_id,$server_id,$fenBaoID,$clienttype,$gameList);
    $result_3 = @get_data($time+3600*24*3,$game_id,$server_id,$fenBaoID,$clienttype,$gameList);
    $result_4 = @get_data($time+3600*24*4,$game_id,$server_id,$fenBaoID,$clienttype,$gameList);
    $result_5 = @get_data($time+3600*24*5,$game_id,$server_id,$fenBaoID,$clienttype,$gameList);
    $result_6 = @get_data($time+3600*24*6,$game_id,$server_id,$fenBaoID,$clienttype,$gameList);
    $result_7 = @get_data($time+3600*24*7,$game_id,$server_id,$fenBaoID,$clienttype,$gameList);
    $result_8 = @get_data($time+3600*24*8,$game_id,$server_id,$fenBaoID,$clienttype,$gameList);
    $result_9 = @get_data($time+3600*24*9,$game_id,$server_id,$fenBaoID,$clienttype,$gameList);

    // $result = array_merge_recursive($result,$result_1, $result_2,$result_3,$result_4,$result_5,$result_6,$result_7,$result_8,$result_9);
    if(is_array($result_1)){
        $result = array_merge_recursive($result,$result_1);
    }
    if(is_array($result_2)){
        $result = array_merge_recursive($result,$result_2);
    }
    if(is_array($result_3)){
        $result = array_merge_recursive($result,$result_3);
    }
    if(is_array($result_4)){
        $result = array_merge_recursive($result,$result_4);
    }
    if(is_array($result_5)){
        $result = array_merge_recursive($result,$result_5);
    }
    if(is_array($result_6)){
        $result = array_merge_recursive($result,$result_6);
    }
    if(is_array($result_7)){
        $result = array_merge_recursive($result,$result_7);
    }
    if(is_array($result_8)){
        $result = array_merge_recursive($result,$result_8);
    }
    if(is_array($result_9)){
        $result = array_merge_recursive($result,$result_9);
    }

    //  print_r($result);
}


/**
 *
 * @param <type> $time  为时间戳
 * @param <type> $game_id
 * @param <type> $server_id
 * @param <type> $fenBaoID
 * @param <type> $clienttype
 */
function get_data($time,$game_id,$server_id,$fenBaoID,$clienttype,$gameList){
    //    if($game_id==1){
    //        $game_server = 91;
    //    }elseif($game_id==2){
    //        $game_server = 92;
    //    }elseif($game_id==3){
    //        $game_server = 93;
    //    }elseif($game_id==4){
    //        $game_server = 94;
    //    }elseif($game_id==10){
    //        $game_server = 95;
    //    }
    $game_server = $gameList[$game_id]['game_server_id'];
    SetConn($game_server);
    mysql_query("SET NAMES 'latin1'");

    //先判断 当前时间对不对
    $check_time_sql = " select id  from   loginmac   l1  where  l1.logintime ='".date("Ymd", $time)."' limit 1  ";
    $query_check_time = mysql_query($check_time_sql);
    $check_result = mysql_fetch_array($query_check_time);
    if(!$check_result['id']){
        return $array;
    }



    //先计算出 8天前 登陆过的玩家

    $server_name =  $gameList[$game_id]['areaList'][$server_id];

    if($server_id){
        $str_condition = $str_condition." and l1.server = '$server_name' ";
    }
    if($fenBaoID){
        $str_condition = $str_condition." and l1.fenbaoid in ('$fenBaoID') ";
    }
    if($clienttype){
        $str_condition = $str_condition." and l1.clienttype = '$clienttype' ";
    }

    $login_time = date("Ymd",$time-3600*24*8);
    $login_sql = "  select distinct l1.accountid  from loginmac   l1  where  logintime ='$login_time' $str_condition ";

    $query_login = mysql_query($login_sql);
    while ($row = mysql_fetch_array($query_login)){
        $result_login[] =  $row['accountid'];
    }

    $result_login_str='';
    for($i=0;$i<count($result_login);$i++){
        if($i==0){
            $result_login_str = $result_login[$i];
        }else{
            $result_login_str = $result_login_str.",".$result_login[$i];
        }
    }

    //计算前8天登陆过的玩家 在7天内的登陆情况
    $login_begin_time = date("Ymd",$time-3600*24*7);
    $login_end_time = date("Ymd",$time-3600*24*1);

    $login_7_sql = "  select distinct l1.accountid  from loginmac   l1  where  logintime >='$login_begin_time' and  logintime <='$login_end_time' and l1.accountid in($result_login_str) ";

    $query_7_sql = mysql_query($login_7_sql);
    while ($row = mysql_fetch_array($query_7_sql)){
        $result_7_login[] =  $row['accountid'];
    }

    //得出流失账号
    $result_account = array_diff($result_login, $result_7_login);
    $result_str='';
    $i=0;
    foreach($result_account as $key=>$value){
        if($i==0){
            $result_str = $result_account[$key];
        }else{
            $result_str = $result_str.",".$result_account[$key];
        }
        $i = $i+1;
    }

    //  echo $result_str;

    //计算 流失账号的等级情况
    $sql_lev = " select lev ,count(accountid) count_id from player where accountid in($result_str) group by  lev order by lev asc ";
    $query_lev  = mysql_query($sql_lev);
    while ($row = mysql_fetch_array($query_lev)){
        $result_lev["等级".$row['lev'].""][date("Y-m-d", $time)] =  $row['count_id'];
    }
    return $result_lev;
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
                <tr>
                    <th height="22" colspan="2" align="center">玩家流失查询</th>
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
                    <td align="right" class="forumRow">分包渠道：</td>
                    <td width="85%" class="forumRow">
                        <select name="fenBaoID">
                            <option value="">选择分包渠道</option>
                            <?php  for($i=0;$i<count($result_fenbao);$i++){    ?>
                            <option value="<?php echo $result_fenbao[$i]['fenbao_id']; ?>"  <?php if($fenBaoID==$result_fenbao[$i]['fenbao_id']){echo "selected";} ?>   ><?php echo $result_fenbao[$i]['fenbao_name']; ?></option>
                            <?php }?>
                        </select>


                    </td>
                </tr>
                <tr>
                    <td align="right" class="forumRow">客户端类型：</td>
                    <td width="85%" class="forumRow"><input name="clienttype" type="text" size="12" value="<?=$clienttype?>" >
                    </td>
                </tr>
                <tr>
                    <td align="right" class="forumRow">查询时间：</td>
                    <td width="85%" class="forumRow">
                        <input name="str_time" type="text" size="12" value="<?=$str_time?>" readonly onfocus="HS_setDate(this)">
                        计算选择的时间 以及 顺推的9天数据
                    </td>
                </tr>
                <tr>
                    <td align="right" class="forumRow"></td>
                    <td class="forumRow"><input type="submit" name="Submit2" class="bott01" value=" 确 定 ">
                        游戏、服务器、时间 为必选
                    </td>
                </tr>
            </form>
        </table>


        <DIV >&nbsp;流失</DIV>
        <table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">
            <tr height="22">
                <th  height="22" align="center">时间</th>
                <th  height="22" align="center"><?php echo date('Y-m-d',$time) ?></th>
                <th  height="22" align="center"><?php echo date('Y-m-d',$time+3600*24*1) ?></th>
                <th  height="22" align="center"><?php echo date('Y-m-d',$time+3600*24*2) ?></th>
                <th  height="22" align="center"><?php echo date('Y-m-d',$time+3600*24*3) ?></th>
                <th  height="22" align="center"><?php echo date('Y-m-d',$time+3600*24*4) ?></th>
                <th  height="22" align="center"><?php echo date('Y-m-d',$time+3600*24*5) ?></th>
                <th  height="22" align="center"><?php echo date('Y-m-d',$time+3600*24*6) ?></th>
                <th  height="22" align="center"><?php echo date('Y-m-d',$time+3600*24*7) ?></th>
                <th  height="22" align="center"><?php echo date('Y-m-d',$time+3600*24*8) ?></th>
                <th  height="22" align="center"><?php echo date('Y-m-d',$time+3600*24*9) ?></th>



            </tr>
            <?php if(count($result)){ foreach($result as $key => $value){?>
            <tr>
                <td nowrap class="forumRow" align="center"><?php  echo $key; ?></td>
                <td nowrap class="forumRow" align="center"><?php  echo $value[date("Y-m-d",$time)]; ?></td>
                <td nowrap class="forumRow" align="center"><?php  echo $value[date("Y-m-d",$time+3600*24*1)]; ?></td>
                <td nowrap class="forumRow" align="center"><?php  echo $value[date("Y-m-d",$time+3600*24*2)]; ?></td>
                <td nowrap class="forumRow" align="center"><?php  echo $value[date("Y-m-d",$time+3600*24*3)]; ?></td>
                <td nowrap class="forumRow" align="center"><?php  echo $value[date("Y-m-d",$time+3600*24*4)]; ?></td>
                <td nowrap class="forumRow" align="center"><?php  echo $value[date("Y-m-d",$time+3600*24*5)]; ?></td>
                <td nowrap class="forumRow" align="center"><?php  echo $value[date("Y-m-d",$time+3600*24*6)]; ?></td>
                <td nowrap class="forumRow" align="center"><?php  echo $value[date("Y-m-d",$time+3600*24*7)]; ?></td>
                <td nowrap class="forumRow" align="center"><?php  echo $value[date("Y-m-d",$time+3600*24*8)]; ?></td>
                <td nowrap class="forumRow" align="center"><?php  echo $value[date("Y-m-d",$time+3600*24*9)]; ?></td>
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