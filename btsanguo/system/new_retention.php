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
$fenbao_id = $_REQUEST['fenbao_id'];
$clienttype = $_REQUEST['clienttype'];
$str_time = $_REQUEST['str_time'];
$end_time = $_REQUEST['end_time'];

SetConn(88);
$sql_fenbao = " select * from user_fenbao ";
$query_fenbao = mysql_query($sql_fenbao);
while ($row = mysql_fetch_array($query_fenbao)){
    $result_fenbao[] = $row;
}


if($str_time&&$game_id){
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
    $time = strtotime($str_time);
    $time_begin = date("Ymd",$time);
    $time_end = date("Ymd",$time+3600*24);

    $time_begin_mac = date("ymdHi",$time);
    $time_end_mac = date("ymdHi",$time+3600*24);

    if($fenbao_id){
        $str_condition = $str_condition." and l1.fenbaoid in('$fenbao_id') ";
    }
    if($clienttype){
        $str_condition = $str_condition." and l1.clienttype = '$clienttype' ";
    }

    $sql = " select count(distinct l1.accountid) count from newmac l1  where l1.createtime>= '$time_begin_mac' and l1.createtime< '$time_end_mac'  ";
    $sql = $sql.$str_condition;
    $query = mysql_query($sql);
    $arr_count= mysql_fetch_array($query);
    $count = $arr_count['count'];

    $sql_qu_in = "select distinct accountid from  newmac l1 where    l1.createtime>='$time_begin_mac' and l1.createtime<'$time_end_mac'   $str_condition  ";
    $query_qu_in = mysql_query($sql_qu_in);
    while ($row = mysql_fetch_array($query_qu_in)){
        $result_qu_in[] = $row;
    }
    // print_r($result_qu_in);
    for($i=0;$i<count($result_qu_in);$i++){
        if($i==0){
            $result_qu_in_str = $result_qu_in[$i]['accountid'];
        }else{
            $result_qu_in_str = $result_qu_in_str.",".$result_qu_in[$i]['accountid'];
        }
    }

    $count_qu_2 = count_qu_time(date('Ymd',$time+3600*24),date('Ymd',$time+3600*24*2),$game_server,$result_qu_in_str);
    $count_qu_3 = count_qu_time(date('Ymd',$time+3600*24),date('Ymd', $time+3600*24*4),$game_server,$result_qu_in_str);
    $count_qu_7 = count_qu_time(date('Ymd',$time+3600*24),date('Ymd', $time+3600*24*8),$game_server,$result_qu_in_str);
    $count_qu_15 = count_qu_15_time($time,$game_server,$result_qu_in_str);
    // $count_qu_15 = count_qu_time(date('Ymd',$time+3600*24),date('Ymd', $time+3600*24*15),$game_server,$result_qu_in_str);
    // $count_qu_30 = count_qu_time(date('Ymd',$time+3600*24),date('Ymd', $time+3600*24*30),$game_server,$result_qu_in_str);

    //返回率计算

    //先计算出今天所有登陆账号
    $sql_today = " select distinct accountid  from loginmac l1  where l1.logintime>= '$time_begin' and l1.logintime< '$time_end' $str_condition ";
    $query_today = mysql_query($sql_today);
    while ($row = mysql_fetch_array($query_today)){
        $result_today[] =  $row['accountid'];
    }
    //  echo "当天登陆".count($result_today);echo "<br/>";
    //计算出当天首登账号
    $sql_shoudeng = " select distinct accountid   from newmac where  createtime>= '$time_begin_mac' and createtime< '$time_end_mac'  ";
    // echo "<br/>";
    $query_shoudeng = mysql_query($sql_shoudeng);
    while ($row = mysql_fetch_array($query_shoudeng)){
        $result_shoudeng[] = $row['accountid'];
    }
    // print_r($result_shoudeng);
    //  echo "首登".count($result_shoudeng);echo "<br/>";
    $result_feishoudeng = @array_diff($result_today, $result_shoudeng);
    //  echo "非首登".count($result_feishoudeng);echo "<br/>";


    $count_fh  = count($result_feishoudeng);

    $count_fh_2 = count_fh_time(date('Ymd',$time-3600*24),date('Ymd',$time),$str_condition,$game_server,$result_feishoudeng);
    $count_fh_3 = count_fh_time(date('Ymd',$time-3600*24*3),date('Ymd',$time),$str_condition,$game_server,$result_feishoudeng);
    $count_fh_7 = count_fh_time(date('Ymd',$time-3600*24*7),date('Ymd',$time),$str_condition,$game_server,$result_feishoudeng);
    $count_fh_15 = count_fh_time(date('Ymd',$time-3600*24*15),date('Ymd',$time),$str_condition,$game_server,$result_feishoudeng);
    //  $count_fh_30 = count_fh_time(date('Ymd',$time-3600*24*29),date('Ymd',$time),$str_condition,$game_server,$result_feishoudeng);

}

function count_fh_time($time1_begin,$time1_end,$str_condition,$game_server,$result_feishoudeng){
    SetConn($game_server);
    $sql = "  select distinct accountid from loginmac l1  where  l1.logintime>='$time1_begin' and l1.logintime<'$time1_end'  $str_condition  ";
    //   echo "<br/>";
    $query = mysql_query($sql);
    while ($row = mysql_fetch_array($query)){
        $result[] =  $row['accountid'];
    }
    // print_r($result);
    count($result);
    $result_diff = @array_diff($result_feishoudeng,$result);
    return count($result_diff);
}
/**
 * 15天的留存率计算方法有所不同
 *  $time  用时间戳
 */
function count_qu_15_time($time,$game_server,$result_qu_in_str){
    SetConn($game_server);
    //先判断第15天是否有数据
    $sql_check = " select count(accountid) count  from loginmac where logintime='".date('Ymd', $time+3600*24*15)."' ";
    $query_check = mysql_query($sql_check);
    $arr_check= mysql_fetch_array($query_check);
    if(!$arr_check['count']){
        return 0;
    }

    //先取出7天内再次登录玩家的账号id
    $time7_begin = date('Ymd',$time+3600*24);
    $time7_end = date('Ymd', $time+3600*24*8);
    $sql_7 = " select distinct l2.accountid   from  loginmac l2 where   l2.logintime>='".($time7_begin)."' and l2.logintime<'".($time7_end)."' and  l2.accountid in($result_qu_in_str) ";
    $query_7 = mysql_query($sql_7);
    while ($row = mysql_fetch_array($query_7)){
        $result_7[] = $row;
    }
    //echo count($result_7);echo "<br/>";
    for($i=0;$i<count($result_7);$i++){
        if($i==0){
            $result_7_str = $result_7[$i]['accountid'];
        }else{
            $result_7_str = $result_7_str.",".$result_7[$i]['accountid'];
        }
    }

    $time15_begin = date('Ymd', $time+3600*24*8);
    $time15_end = date('Ymd', $time+3600*24*15);
    $sql_15 = " select count(distinct l2.accountid) count from  loginmac l2 where   l2.logintime>='".($time15_begin)."' and l2.logintime<'".($time15_end)."' and  l2.accountid in($result_7_str) ";
    $query_15 = mysql_query($sql_15);
    $arr_count_15= mysql_fetch_array($query_15);
    $count_15 = $arr_count_15['count'];
    return $count_15;

}

function count_qu_time($time2_begin,$time2_end,$game_server,$result_qu_in_str){//根据时间区计算留存率
    SetConn($game_server);
    $sql_2 = " select count(distinct l2.accountid) count from  loginmac l2 where   l2.logintime>='".($time2_begin)."' and l2.logintime<'".($time2_end)."' and  l2.accountid in($result_qu_in_str) ";
    $query_2 = mysql_query($sql_2);
    $arr_count_2= @mysql_fetch_array($query_2);
    $count_2 = $arr_count_2['count'];
    return $count_2;
}



?>
<html>
    <head>
        <title>list</title>
        <meta http-equiv="Content-Type" content="text/html; charset=gb2312">
        <link href="CSS/Style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="JS/calendar.js"></script>
        <script language="javascript" src="JS/jquery-1.3.2.min.js"></script>
    </head>
    <body class="main">
        <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
            <form name="SearchForm" method="POST" action="">
                <tr>
                    <th height="22" colspan="2" align="center">玩家留存率查询</th>
                </tr>
                <tr>
                    <td width="15%" align="right" class="forumRow">游戏：</td>
                    <td width="85%" class="forumRow">
                        <select name="game_id" onchange="getArea(this.value)">
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
                    <td align="right" class="forumRow">分包渠道：</td>
                    <td width="85%" class="forumRow">
                        <select name="fenbao_id">
                            <option value="">选择分包渠道</option>
                            <?php  for($i=0;$i<count($result_fenbao);$i++){    ?>
                            <option value="<?php echo $result_fenbao[$i]['fenbao_id']; ?>"  <?php if($fenbao_id==$result_fenbao[$i]['fenbao_id']){echo "selected";} ?>   ><?php echo $result_fenbao[$i]['fenbao_name']; ?></option>
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


        <DIV >&nbsp;区间留存率</DIV>
        <table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">
            <tr height="22">
                <th  height="22" align="center">时间</th>
                <th  height="22" align="center">新增</th>
                <th  height="22" align="center">二天</th>
                <th  height="22" align="center">三天</th>
                <th  height="22" align="center">七天</th>
                <th  height="22" align="center">十五天</th>

            </tr>
            <tr>
                <td nowrap class="forumRow" align="center"><?php echo $str_time;?></td>
                <td nowrap class="forumRow" align="center"><?php echo $count;?></td>
                <td nowrap class="forumRow" align="center"><?php echo "$count_qu_2(".@number_format($count_qu_2*100/$count,2)."%)";?></td>
                <td nowrap class="forumRow" align="center"><?php echo "$count_qu_3(".@number_format($count_qu_3*100/$count,2)."%)"; ?></td>
                <td nowrap class="forumRow" align="center"><?php echo "$count_qu_7(".@number_format($count_qu_7*100/$count,2)."%)"; ?></td>
                <td nowrap class="forumRow" align="center"><?php echo "$count_qu_15(".@number_format($count_qu_15*100/$count,2)."%)"; ?></td>
            </tr>
        </table>

        <DIV >&nbsp;区间返回率</DIV>
        <table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">
            <tr height="22">
                <th  height="22" align="center">时间</th>
                <th  height="22" align="center">登陆人数</th>
                <th  height="22" align="center">二天</th>
                <th  height="22" align="center">三天</th>
                <th  height="22" align="center">七天</th>
                <th  height="22" align="center">十五天</th>
            </tr>
            <tr>
                <td nowrap class="forumRow" align="center"><?php echo $str_time;?></td>
                <td nowrap class="forumRow" align="center"><?php echo $count_fh;?></td>
                <td nowrap class="forumRow" align="center"><?php echo $count_fh_2."(".@number_format($count_fh_2*100/$count_fh,2)."%)";?></td>
                <td nowrap class="forumRow" align="center"><?php echo $count_fh_3."(".@number_format($count_fh_3*100/$count_fh,2)."%)";?></td>
                <td nowrap class="forumRow" align="center"><?php echo $count_fh_7."(".@number_format($count_fh_7*100/$count_fh,2)."%)";?></td>
                <td nowrap class="forumRow" align="center"><?php echo $count_fh_15."(".@number_format($count_fh_15*100/$count_fh,2)."%)";?></td>
            </tr>
        </table>

    </body>

</html>