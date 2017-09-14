<?php
include("inc/config.php");
include("inc/CheckUser.php");
include("inc/function.php");
include("inc/page.php");
include("../inc/config.php");
include("../inc/function.php");
include("inc/game_config.php");
$gameList = $game_arr;

set_time_limit(600);

$game_id = $_REQUEST['game_id'];
$fenBaoID = $_REQUEST['fenBaoID'];
$clienttype = $_REQUEST['clienttype'];
$str_time = $_REQUEST['str_time'];
$end_time = $_REQUEST['end_time'];

$SvrID = $_REQUEST['SvrID'];
$server_id = $_REQUEST['SvrID'];
//SetConn(91);
//mysql_query("SET NAMES 'latin1'");
//$sql = " select  servername from online where servername='圣火纹章'  ";
//$query = mysql_query($sql);
//while ($row = mysql_fetch_array($query)){
//    echo $row['servername'];
//    echo "<br/>";
//}
//exit;

SetConn(88);
$sql_fenbao = " select * from user_fenbao ";
$query_fenbao = mysql_query($sql_fenbao);
while ($row = mysql_fetch_array($query_fenbao)){
    $result_fenbao[] = $row;
}

if($game_id&&$str_time&&$end_time){

    $str_time_int = strtotime($str_time);
    $end_time_int = strtotime($end_time);
    //    echo ($end_time_int-$str_time_int)/(3600*24);exit;
    for($i=0;$i<=(($end_time_int-$str_time_int)/(3600*24));$i++){
        $result[$str_time_int+$i*24*3600] =  get_data($game_id,$server_id,$fenBaoID,$clienttype,$str_time_int+$i*24*3600,$gameList);
    }
    //  print_r($result);
}

function get_data($game_id,$server_id,$fenBaoID,$clienttype,$time,$gameList){
    $game_server = $gameList[$game_id]['game_server_id'];
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
    $server_name =  $gameList[$game_id]['areaList'][$server_id];

    SetConn($game_server);
    mysql_query("SET NAMES 'latin1'");
    $time_begin_login = date("Ymd",$time);
    $time_end_login = date("Ymd",$time+3600*24);
    $time_begin_online = date("ymdHi",$time);
    $time_end_online = date("ymdHi",$time+3600*24);

//    $sql = "select server from loginmac limit 1";
//    $query = mysql_query($sql);
//    $arr_count= mysql_fetch_array($query);
//    print_r($arr_count);exit;



    if($fenBaoID){
        $str_condition = $str_condition." and l1.fenbaoid in('$fenBaoID') ";
    }
    if($clienttype){
        $str_condition = $str_condition." and l1.clienttype = '$clienttype' ";
    }
    if($server_name){
        $str_condition = $str_condition." and server='$server_name' ";
    }

    //计算当天活跃人数
    $sql_count_huoyue = " select count(distinct l1.accountid) count from loginmac   l1  where l1.logintime>= '$time_begin_login' and l1.logintime< '$time_end_login'    ";
    $sql_count_huoyue = $sql_count_huoyue.$str_condition;

    $query = mysql_query($sql_count_huoyue);
    $arr_count= mysql_fetch_array($query);
    $count_huoyue = $arr_count['count'];
    $result_day['count_huoyue'] = $count_huoyue;

    //计算出当天登陆超过 1个小时的玩家

    //当天活跃id
    $sql_account_huoyue = " select  l1.accountid  from loginmac   l1  where l1.logintime>= '$time_begin_login' and l1.logintime< '$time_end_login'    ";
    $sql_account_huoyue = $sql_account_huoyue.$str_condition;
    $query_account_huoyue = mysql_query($sql_account_huoyue);
    while ($row = mysql_fetch_array($query_account_huoyue)){
        $result_account_huoyue[] =  $row['accountid'];
    }

    $result_huoyue_str='';
    for($i=0;$i<count($result_account_huoyue);$i++){
        if($i==0){
            $result_huoyue_str = $result_account_huoyue[$i];
        }else{
            $result_huoyue_str = $result_huoyue_str.",".$result_account_huoyue[$i];
        }
    }


    //计算出今天的高活跃

    if($server_id){
        $sql_sum_online_time = " select sum(online) sum_online,accountid from palyeronline where  daytime>='$time_begin_online' and  daytime<'$time_end_online'  and  accountid in($result_huoyue_str) and serverid='$server_id'      group by accountid  having  sum(online)>'3600' ";
    }else{
        $sql_sum_online_time = " select sum(online) sum_online,accountid from palyeronline where  daytime>='$time_begin_online' and  daytime<'$time_end_online'  and accountid in($result_huoyue_str)        group by accountid  having  sum(online)>'3600' ";
    }
    $query_sum_online_time = mysql_query($sql_sum_online_time);
    while (@$row = mysql_fetch_array($query_sum_online_time)){
        $result_sum_online_time[] =  $row;
    }

    $count_sum_online_time = count($result_sum_online_time);
    $result_day['count_sum_online_time'] = $count_sum_online_time;



    //本日新增付费人数

    //当天付费账号
    if($server_id){
        $sql_pay_day = " select distinct accountid from pay where   daytime>='$time_begin_online' and  daytime<'$time_end_online' and accountid in($result_huoyue_str)  and serverid='$server_id' ";
    }else{
        $sql_pay_day = " select distinct accountid from pay where daytime>='$time_begin_online' and  daytime<'$time_end_online' and accountid in($result_huoyue_str)    ";
    }
    
    $query_pay_day = mysql_query($sql_pay_day);
    while (@$row = mysql_fetch_array($query_pay_day)){
        $result_pay_day[] =  $row['accountid'];
    }
    $result_day['count_fufei'] = count($result_pay_day);

    $result_pay_day_str='';
    for($i=0;$i<count($result_pay_day);$i++){
        if($i==0){
            $result_pay_day_str = $result_pay_day[$i];
        }else{
            $result_pay_day_str = $result_pay_day_str.",".$result_pay_day[$i];
        }
    }
    //计算出  当前付费账号  和分包id以及 客户端相关连
    if($fenBaoID||$clienttype){
        $sql_pay_day_fenbao_clienttype = " select  distinct accountid from pay l1 where accountid in($result_pay_day_str)   ";
//        if($fenBaoID){
//            $sql_pay_day_fenbao_clienttype = $sql_pay_day_fenbao_clienttype." and fenbao in('$fenBaoID') ";
//        }
//        if($clienttype){
//            $sql_pay_day_fenbao_clienttype = $sql_pay_day_fenbao_clienttype." and clienttype='$clienttype' ";
//        }
//        echo $sql_pay_day_fenbao_clienttype;
        
        $query_pay_day_fenbao_clienttype = mysql_query($sql_pay_day_fenbao_clienttype);
        while ($row = mysql_fetch_array($query_pay_day_fenbao_clienttype)){
            $result_pay_day_fenbao_clienttype[] =  $row['accountid'];
        }

        $result_pay_day_str='';
        for($i=0;$i<count($result_pay_day_fenbao_clienttype);$i++){
            if($i==0){
                $result_pay_day_str = $result_pay_day_fenbao_clienttype[$i];
            }else{
                $result_pay_day_str = $result_pay_day_str.",".$result_pay_day_fenbao_clienttype[$i];
            }
        }
    }
    //当天非首付人数
    if($result_pay_day_str){
        if($server_id){
            $sql_pay_day_fei = " select distinct accountid from pay where daytime<'$time_begin_online' and   serverid='$server_id' and  accountid in($result_pay_day_str) ";
        }else{
            $sql_pay_day_fei = " select distinct accountid from pay where  daytime<'$time_begin_online' and  accountid in($result_pay_day_str) ";
        }
      //  echo $sql_pay_day_fei;
        $query_pay_day_fei = mysql_query($sql_pay_day_fei);
        while ($row = mysql_fetch_array($query_pay_day_fei)){
            $result_pay_day_fei[] =  $row['accountid'];
        }
        if(count($result_pay_day_fei)){
               $result_pay_day_shoufu =  @array_diff($result_pay_day, $result_pay_day_fei);
        }else{
            $result_pay_day_shoufu = $result_pay_day;
        }
       
    }

    //计算出 当天的首付
    $count_pay_day_shoufu = count($result_pay_day_shoufu);

    $result_day['count_pay_day_shoufu'] = $count_pay_day_shoufu;

    //计算当天付费总额
    if($result_pay_day_str){
        if($server_id){
            $sql_pay_day_sum = " select sum(price) sum_price from pay where daytime>='$time_begin_online' and  daytime<'$time_end_online'  and  serverid='$server_id'  and accountid in($result_huoyue_str)  ";
        }else{
            $sql_pay_day_sum = " select sum(price) sum_price from pay where  daytime>='$time_begin_online' and  daytime<'$time_end_online'   and accountid in($result_huoyue_str)  ";
        }

        $query_pay_day_sum = mysql_query($sql_pay_day_sum);
        $result_pay_day_sum = mysql_fetch_array($query_pay_day_sum);
        $pay_day_sum = $result_pay_day_sum['sum_price'];
    }

    $result_day['pay_day_sum'] = $pay_day_sum;


    return $result_day;
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
                    <th height="22" colspan="2" align="center">玩家活跃查询</th>
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
                <!--
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
    -->
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
                        --

                        <input name="end_time" type="text" size="12" value="<?=$end_time?>" readonly onfocus="HS_setDate(this)">

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


        <DIV >&nbsp;活跃</DIV>
        <table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">
            <tr height="22">
                <th  height="22" align="center">时间</th>
                <th  height="22" align="center">本日活跃人数</th>
                <th  height="22" align="center">本日新增付费人数</th>
                <th  height="22" align="center">日付费人数（率）</th>
                <th  height="22" align="center">日ARPU值</th>
                <th  height="22" align="center">日高活跃人数（率）</th>
            </tr>
            <?php if(count($result)){ foreach($result as $key => $value){?>
            <tr>
                <td nowrap class="forumRow" align="center"><?php echo date('Y-m-d',$key) ;?></td>
                <td nowrap class="forumRow" align="center"><?php echo $value['count_huoyue'];?></td>
                <td nowrap class="forumRow" align="center"><?php echo $value['count_pay_day_shoufu']; ?></td>
                <td nowrap class="forumRow" align="center"><?php echo $value['count_fufei']."(".@number_format($value['count_fufei']*100/$value['count_huoyue'],2)."%)"; ?></td>
                <td nowrap class="forumRow" align="center"><?php echo ($value['pay_day_sum']/10)."/".$value['count_fufei']."(".@number_format((($value['pay_day_sum'])/10)/$value['count_fufei'],2).")"; ?></td>
                <td nowrap class="forumRow" align="center"><?php echo $value['count_sum_online_time'];; ?></td>
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