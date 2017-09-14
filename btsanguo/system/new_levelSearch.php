<?php
include("inc/config.php");
include("inc/CheckUser.php");
include("inc/function.php");
include("inc/page.php");
include("../inc/config.php");
include("../inc/function.php");
include("inc/game_config.php");
$gameList = $game_arr;

?>
<html>
    <head>
        <title>list</title>
        <meta http-equiv="Content-Type" content="text/html; charset=gb2312">
        <link href="CSS/Style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="JS/DateTime/DateDialog.js"></script>
        <script language="javascript" src="JS/ActionFrom.js"></script>

        <script language="javascript" src="JS/jquery-1.3.2.min.js"></script>

        <script language="javascript" src="JS/calendar.js"></script>

    </head>
    <body class="main">
        <?
        $StarTime=$_REQUEST["StarTime"];//开始时间
        $EndsTime=$_REQUEST["EndsTime"];//结束时间
        $SvrID = $_REQUEST['SvrID'];
        $fid=$_REQUEST["SvrID"];//区
        $game_id=$_REQUEST["game_id"];


        if(($_REQUEST['action']=='select')&&$game_id&&$StarTime&&$EndsTime){
            $game_server = $gameList[$game_id]['game_server_id'];
            SetConn($game_server);
            mysql_query("SET NAMES 'latin1'");


            $StarTime_int = date("ymdHi", strtotime($StarTime));
            $EndsTime_int = date("ymdHi", strtotime($EndsTime)+3600*24);
            $sql_account = " select distinct accountid from  newmac  where gameid='$game_id' and  createtime >= '$StarTime_int' and createtime <= '$EndsTime_int' ";

            $query_account = mysql_query($sql_account);
            while ($row = mysql_fetch_array($query_account)){
                $result_account[] =  $row['accountid'];
            }

            $account_str='';
            for($i=0;$i<count($result_account);$i++){
                if($i==0){
                    $account_str = $result_account[$i];
                }else{
                    $account_str = $account_str.",".$result_account[$i];
                }
            }
            if($account_str){

                $str_condition = " gameid='$game_id' ";
                if($fid){
                    $str_condition = $str_condition." and serverid='$fid' ";
                }

                //                 $sql=" select grouplevel,COUNT(*) num,SUM(`lev`) sum_lev from
                //                                        ( select
                //                                            case
                //                                            when `lev`=1 then '1'
                //                                            when `lev`>=2 and `lev`<=5 then '2-5'
                //                                            when `lev`>=6 and `lev`<=10 then '6-10'
                //                                            when `lev`>=11 and `lev`<=19 then '11-19'
                //                                            when `lev`>=20 and `lev`<=39 then '20-39'
                //                                            when `lev`>=40 and `lev`<=69 then '40-69'
                //                                            when `lev`>=70 and `lev`<=99 then '70-99'
                //                                            else '100' end 'grouplevel',`lev` from palyerday  where accountid in($account_str) and $str_condition ) a group by grouplevel ";
                //
                //                $query = mysql_query($sql);
                //
                //                while ($row = mysql_fetch_array($query)){
                //                    $result[$row['grouplevel']] =  $row;
                //                    $result['num_total'] =  $result['num_total'] + $row['num'];
                //                    $result['sum_lev'] =  $result['sum_lev'] + $row['sum_lev'];
                //                }

                $sql=" select
                                        case
                                                    when `lev`=1 then '1'
                                                    when `lev`>=2 and `lev`<=5 then '2-5'
                                                    when `lev`>=6 and `lev`<=10 then '6-10'
                                                    when `lev`>=11 and `lev`<=19 then '11-19'
                                                    when `lev`>=20 and `lev`<=39 then '20-39'
                                                    when `lev`>=40 and `lev`<=69 then '40-69'
                                                    when `lev`>=70 and `lev`<=99 then '70-99'
                                                    else '100' end 'grouplevel',`lev` from player  where accountid in($account_str) and $str_condition ";

                $query = mysql_query($sql);

                while ($row = mysql_fetch_array($query)){
                     $result_sql[] =   $row;
                }

                for($i=0;$i<count($result_sql);$i++){
                    
                    $result[$result_sql[$i]['grouplevel']] = $result[$result_sql[$i]['grouplevel']]+1;
                    $result['sum_lev'] = $result['sum_lev']+$result_sql[$i]['lev'];
                    $result['num_total'] =  $result['num_total'] + 1;

                }


            }else{
                echo '<script language="javascript">alert("该时间段无注册数据")</script>';

            }

        }

      //  print_r($result);

        ?>
        <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
            <form name="SearchForm" method="POST" action="">
                <input type="hidden" name="action" value="select">
                <tr>
                    <th height="22" colspan="2" align="center">职业等级分布查询</th>
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
                    <td align="right" class="forumRow">账号注册时间：</td>

                    <td width="85%" class="forumRow">
                        <input name="StarTime" type="text" size="12" value="<?=$StarTime?>" readonly onfocus="HS_setDate(this)">
                        --

                        <input name="EndsTime" type="text" size="12" value="<?=$EndsTime?>" readonly onfocus="HS_setDate(this)">

                    </td>

                </tr>
                <tr>
                    <td align="right" class="forumRow">&nbsp;</td>
                    <td class="forumRow"><input type="submit" name="Submit2" class="bott01" value=" 搜 索 ">游戏和注册时间不能为空</td>
                </tr>
            </form>
        </table>
        <DIV style="FONT-SIZE: 2px">&nbsp;</DIV>

        <table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">
            <?php if($result){?>
            <tr height="22">
                <th width="58" height="22" align="center">人数</th>
                <th width="96" height="22" align="center">平均等级</th>
                <th width="96" height="22" align="center">1</th>
                <th width="96" height="22" align="center">2-5</th>
                <th width="96" height="22" align="center">6-10</th>
                <th width="96" height="22" align="center">11-19</th>
                <th width="96" height="22" align="center">20-39</th>
                <th width="96" height="22" align="center">40-69</th>
                <th width="96" height="22" align="center">70-99</th>
                <th width="96" height="22" align="center">100级以上</th>
            </tr>
            <?php }?>
        <?php
        if($result){

            ?>
            <tr bgcolor="#ECECED">
                <td width="93" height="22" align="center"><?=$result['num_total']?></td>
                <td width="58" height="22" align="center"><?=round($result['sum_lev']/$result['num_total'],1) ?></td>
                <td width="96" height="22" align="center"><?php  echo $result['1'];?></td>
                <td width="96" height="22" align="center"><?php  echo $result['2-5'];?></td>
                <td width="96" height="22" align="center"><?php  echo $result['6-10'];?></td>
                <td width="96" height="22" align="center"><?php  echo $result['11-19'];?></td>
                <td width="96" height="22" align="center"><?php  echo $result['20-39'];?></td>
                <td width="96" height="22" align="center"><?php  echo $result['40-69'];?></td>
                <td width="96" height="22" align="center"><?php  echo $result['70-99'];?></td>
                <td width="96" height="22" align="center"><?php  echo $result['100'];?></td>
            </tr>

                <?php
            }
            ?>
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