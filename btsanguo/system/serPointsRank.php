<?php
include("inc/CheckUser.php");
include("../inc/config.php");
include("inc/function.php");
include("../inc/function.php");
include("inc/page.php");
include("inc/game_config.php");

$action = $_REQUEST['action'];
$account = $_REQUEST['account'];
$game_id = $_REQUEST['game_id'];
$player_name = $_REQUEST['player_name'];
$SvrID = intval($_REQUEST['SvrID']);
if($action=="select"&&$SvrID){

    if($player_name){
        SetConn($SvrID);
        mysql_escape_string($unescaped_string);
        $sql_player = " select * from u_player where name='".mysql_escape_string($player_name)."' ";
        $res_player = mysql_query($sql_player);
        $rs_player = mysql_fetch_array($res_player);
        $account_id =  $rs_player['account_id'];
    }
    $str = "";
    if($account_id){
       $str = $str." and PayId='$account_id' ";
    }
    if($account){
       $str = $str." and PayName='".mysql_escape_string($account)."' ";
    }
    
    SetConn(88);
    $sql = " select sum(PayMoney) sum_money ,pl.* from pay_log pl where ServerID='$SvrID' $str group by PayId  order by sum_money desc  limit 50  ";
    $query = mysql_query($sql);
    while($rs=@mysql_fetch_array($query)){
       $result[] =  $rs;
    }

}


?>
<html>
    <head>
        <title>list</title>
        <meta http-equiv="Content-Type" content="text/html; charset=gb2312">
        <link href="CSS/Style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="JS/jquery-1.3.2.min.js"></script>
    </head>
    <body class="main">

        <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
            <form name="SearchForm" method="POST" action="?action=select">
                <tr>
                    <th height="22" colspan="2" align="center">积分和等级查询</th>
                </tr>
                <tr>
                    <td align="right" class="forumRow">账号：</td>
                    <td width="85%" class="forumRow"><input name="account" type="text" size="30" value="<?php echo $account; ?>" ></td>
                </tr>
                <tr>
                    <td align="right" class="forumRow">游戏：</td>
                    <td class="forumRow">
                        <select name="game_id" onchange="change_game(this.value)">
                            <option value='' >选择游戏</option>
                            <?php
                            //               for($i=1;$i<=count($game_arr);$i++){
                            //                  echo "<option value=\"".$i." \" ".(($i==$game_id)?' selected="selected"':'')." >".$game_arr[$i]['name']."</option>";
                            //               }

                            if($game_arr){
                                foreach ($game_arr as $key=>$val){
                                    echo "<option value='$key' ".($game_id==$key?'selected':'')." >$val[name]</option>";
                                }
                            }

                            ?>
                        </select>
                    </td>
                    <td class="forumRow">&nbsp;</td>
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
                        服务器分区不能为空
                    </td>
                    <td rowspan="2" class="forumRow">&nbsp;</td>
                </tr>
                <tr>
                    <td align="right" class="forumRow">角色名：</td>
                    <td width="85%" class="forumRow"><input name="player_name" type="text" size="30" value="<?php echo $player_name; ?>" ></td>
                </tr>
                <tr>
                    <td align="right" class="forumRow">&nbsp;</td>
                    <td class="forumRow"><input type="submit" name="Submit2" class="bott01" value=" 提交 "> </td>
                </tr>
            </form>
        </table>

        <DIV style="FONT-SIZE: 2px">&nbsp;</DIV>

        <table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">

            <tr>
                <th width="60"  height="22" align="center">账号id</th>
                <th width="100" height="22" align="center">账号</th>
                <th width="93" height="22" align="center">服务器</th>
                <th width="93" height="22" align="center">玩家积分</th>
                <th width="48" height="22" align="center">玩家等级</th>
            </tr>
           <?php for($i=0;$i<count($result);$i++){   ?>
            <tr>
                <td><?php echo $result[$i]['PayID']; ?></td>
                <td><?php echo $result[$i]['PayName']; ?></td>
                <td><?php echo $game_arr[$game_id]['server_list'][$result[$i]['ServerID']]; ?></td>
                <td><?php echo $result[$i]['sum_money']*10; ?></td>
                <td><?php
                if($result[$i]['sum_money']>=200000){
                    echo "VIP14";
                }else if($result[$i]['sum_money']>=100000){
                    echo "VIP13";
                }else if($result[$i]['sum_money']>=50000){
                    echo "VIP12";
                }else if($result[$i]['sum_money']>=20000){
                    echo "VIP11";
                }else if($result[$i]['sum_money']>=10000){
                    echo "VIP10";
                }else if($result[$i]['sum_money']>=5000){
                    echo "VIP9";
                }else if($result[$i]['sum_money']>=2000){
                    echo "VIP8";
                }else if($result[$i]['sum_money']>=1000){
                    echo "VIP7";
                }else if($result[$i]['sum_money']>=500){
                    echo "VIP6";
                }else if($result[$i]['sum_money']>=200){
                    echo "VIP5";
                }else if($result[$i]['sum_money']>=100){
                    echo "VIP4";
                }else if($result[$i]['sum_money']>=40){
                    echo "VIP3";
                }else if($result[$i]['sum_money']>=20){
                    echo "VIP2";
                }else if($result[$i]['sum_money']>=5){
                    echo "VIP1";
                }else {
                    echo "VIP0";
                }
                
                ?>


                </td>
            </tr>
          <?php }?>


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