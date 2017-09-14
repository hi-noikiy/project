<?php
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");
include("inc/game_config.php");
include("../inc/config.php");
if($_REQUEST['action']=="select"){

    if(check_select($_POST)){
        //连接账号库，获取账号id
        SetConn(81);
        $account_sql = " select id from account where name = '".$_POST['account']."' ";
        $account_conn = mysql_query($account_sql);
        $account_result = @mysql_fetch_array($account_conn) ;
        $account_id = intval($account_result['id']) ;
        if(!$account_id){
            echo "<script type=\"text/javascript\">alert(\"该账号不存在\");</script>";
        }else{
           SetConn($_POST['SvrID']);
            //   SetConn(88);
            $sql = " select u.* from u_card u  where u.account_id = $account_id";
            $conn = mysql_query($sql);
            $pay_arr;
            while($rs=mysql_fetch_array($conn)){
                if($rs['type']!=8){
                   $rs['PayMoney'] =  type_to_money($rs['type']);
                }else{
                    $rs['PayMoney'] =  $rs['data'];
                }
                $pay_arr[] = $rs;
            }
        }
    }
}
if($_REQUEST['action']=="update"){
    if(check_select($_GET)){
         SetConn($_GET['SvrID']);
       //   SetConn(88);
        $_GET['u_card_id'];
        $sql = " select * from u_card u  where id = ".intval($_GET['u_card_id']);
        $conn = mysql_query($sql);
        $result=mysql_fetch_array($conn);
        if($result['used']!=0){
            echo "<script type=\"text/javascript\">alert(\"该充值转移无法实现\");</script>"; exit;
        }
        $sql = " update u_card set status =0,used=1  where id = ".intval($_GET['u_card_id']);
        mysql_query($sql);

        SetConn($_GET['SvrID_New']);

        $sql = " insert into u_card(type,account_id,ref_id,chk_sum,time_stamp,used,used_time_stamp,time_reg,status,data) values('".$result['type']."','".$result['account_id']."','".$result['ref_id']."','".$result['chk_sum']."','".$result['time_stamp']."','".$result['used']."','".$result['used_time_stamp']."','".$result['time_reg']."','".$result['status']."','".$result['data']."')";
        $insert_rs =  mysql_query($sql);
        //转移log
        $str = "\n account = ".$_GET['account']." game_id=".$_GET['game_id']." SvrID=".$_GET['SvrID']." new_game_id=".$_GET['game_id']." SvrID_New=".$_GET['SvrID_New']." adminName=".$AdminName." time=".date("Ymd H:i:s");
        if($insert_rs){
            $str .= " true";
        }else{
            $str .= " flase";
        }
        write_log_admin("log","recharge_change",$str);

        //转移记录
        SetConn(88);
        $sql = " insert into change_pay_log(account,account_id,game_id,SvrID,new_game_id,SvrID_New,adminName,add_time)
                 values('".$_GET['account']."','".$result['account_id']."','".$_GET['game_id']."','".$_GET['SvrID']."','".$_GET['new_game_id']."','".$_GET['SvrID_New']."','".$AdminName."','".time()."') ";
        mysql_query($sql);
        if($insert_rs){
            echo "转移成功";exit;
        }else{
            echo "转移失败";exit;
        }

    }else{
        exit;
    }
}
function type_to_money($type){
    switch($type)
    {
        case "101"	: $PayMoney="1";	break;
        case "301"	: $PayMoney="10";	break;
        case "302"	: $PayMoney="20";	break;
        case "303"	: $PayMoney="30";	break;
        case "305"	: $PayMoney="50";	break;
        case "310"	: $PayMoney="100";	break;
        case "320"	: $PayMoney="200";	break;
        case "330"	: $PayMoney="300";	break;
        case "101"	: $PayMoney="1";	break;
        case "201"	: $PayMoney="10";	break;
        case "202"	: $PayMoney="20";	break;
        case "203"	: $PayMoney="30";	break;
        case "205"	: $PayMoney="50";	break;
        case "210"	: $PayMoney="100";	break;
        case "220"	: $PayMoney="200";	break;
        case "230"	: $PayMoney="300";	break;
        case "250"	: $PayMoney="500";	break;

        default		: $PayMoney="0";	break;//未定义
        }
     return $PayMoney;

      }
/**
 * 对验证的充值验证
 * @param <type> $info
 * @return <type>
 */
function check_select($info){
    if($info['game_id']==''){
        echo "<script type=\"text/javascript\">alert(\"游戏类型(原)不能为空\");</script>";
        return false;
    }
    if($info['SvrID']==''){
        echo "<script type=\"text/javascript\">alert(\"游戏分区(原)不能为空\");</script>";
        return false;
    }
    if($info['account']==''){
        echo "<script type=\"text/javascript\">alert(\"游戏账号不能为空\");</script>";
        return false;
    }
    if($info['new_game_id']==''){
        echo "<script type=\"text/javascript\">alert(\"游戏类型(转入)不能为空\");</script>";
        return false;
    }
    if($info['SvrID_New']==''){
        echo "<script type=\"text/javascript\">alert(\"游戏分区(转入)不能为空\");</script>";
        return false;
    }
    if($info['game_id']==$info['new_game_id']&&$info['SvrID']==$info['SvrID_New']){
        echo "<script type=\"text/javascript\">alert(\"不能转入相同的分区\");</script>";
        return false;
    }
    return true;
}
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=gb2312">
        <title>充值修改</title>
        <link href="CSS/Style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="JS/jquery-1.3.2.min.js"></script>

    </head>
    <body class="main">
        <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
            <form name="form1" method="POST" enctype="multipart/form-data" action="recharge_change.php?action=select">
                <tr>
                    <th colspan="2" align="center">游戏充值修改</th>
                </tr>
                <tr>
                    <td align="right" class="forumRow">游戏（原）：</td>
                    <td class="forumRow">
                        <select name='game_id' onchange="change_game(this.value)">
                            <option value="">选择游戏</option>
                            <?php
                            if($game_arr){
                                foreach ($game_arr as $key=>$val){
                                    echo "<option value='$key' ".(($key==$_POST['game_id'])?"selected":"")." >".$val['name']."</option>";
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right" class="forumRow">服务区（原）：</td>
                    <td class="forumRow">
                        <select name="SvrID" id="ServerID">
                            <option value="" selected="selected">请选择分区</option>
                            <?php
                            foreach($game_arr[$_POST['game_id']]['server_list'] as $game_key=>$game_value){
                                echo "<option value=\"".$game_key."\" ".(($_POST['SvrID']==$game_key)?'selected="selected"':'')." >".$game_value."</option>";
                            }
                            ?>
                        </select>
                    </td>

                </tr>
                <tr><td align="right" class="forumRow"> 账号:</td><td><input name="account" value="<?php echo $_POST['account'] ?>"/></td></tr>
                <tr>
                    <td align="right" class="forumRow">游戏（转入）：</td>
                    <td class="forumRow">
                        <select name='new_game_id' onchange="change_game_new(this.value)">
                            <option value="">选择游戏</option>
                            <?php
                            if($game_arr){
                                foreach ($game_arr as $key=>$val){
                                    echo "<option value='$key' ".(($key==$_POST['new_game_id'])?"selected":"")." >".$val['name']."</option>";
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right" class="forumRow">服务区（转入）：</td>
                    <td class="forumRow">
                        <select name="SvrID_New" id="ServerID_New">
                            <option value="" selected="selected">请选择分区</option>
                            <?php
                            foreach($game_arr[$_POST['new_game_id']]['server_list'] as $game_key=>$game_value){
                                echo "<option value=\"".$game_key."\" ".(($_POST['SvrID_New']==$game_key)?'selected="selected"':'')." >".$game_value."</option>";
                            }
                            ?>
                        </select>
                    </td>

                </tr>
                <tr align="right" class="forumRow"><td><input type="submit" name="验证" value="验证"/></td></tr>
            </form>
        </table>
        <table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">
            <tr height="22">
                <th width="15%" height="22" align="center">原游戏</th>
                <th width="15%" height="22" align="center">原游戏分区</th>
                <th width="15%" height="22" align="center">账号</th>
                <th width="15%" height="22" align="center">金额</th>
                <th width="15%" height="22" align="center">转入游戏</th>
                <th width="15%" height="22" align="center">转入游戏分区</th>
                <th width="25%" height="22" align="center">操作</th>
            </tr>
            <?php
            for($i=0;$i<count($pay_arr);$i++){
                ?>
            <tr>
                <td nowrap class="forumRow" align="center"><?php echo $game_arr[$_POST['game_id']]['name'] ?></td>
                <td nowrap class="forumRow" align="center"><?php echo $game_arr[$_POST['game_id']]['server_list'][$_POST['SvrID']] ?></td>

                <td nowrap class="forumRow" align="center"><?php echo $_POST['account'] ?></td>
                <td nowrap class="forumRow" align="center"><?php echo $pay_arr[$i]['PayMoney'] ?></td>

                <td nowrap class="forumRow" align="center"><?php echo $game_arr[$_POST['new_game_id']]['name'] ?></td>
                <td nowrap class="forumRow" align="center"><?php echo $game_arr[$_POST['new_game_id']]['server_list'][$_POST['SvrID_New']] ?></td>

                <td nowrap class="forumRow" align="center"><?php if($pay_arr[$i]['used']==0){  ?> <a href="recharge_change.php?action=update&u_card_id=<?php echo $pay_arr[$i]['id'] ?>&game_id=<?php echo $_POST['game_id']; ?>&SvrID=<?php echo $_POST['SvrID']; ?>&account=<?php echo $_POST['account']; ?>&new_game_id=<?php echo $_POST['new_game_id']; ?>&SvrID_New=<?php echo $_POST['SvrID_New']; ?>" target="_blank">转移</a> <?php }else{ ?> 不可转移(已提取)<?php }?></td>
            </tr>
            <?php } ?>
        </table>
    </body>
    <script type="text/javascript">
        function change_game(game_id){
            $.post("ajax/game.php", { action: "change_game", game_id: game_id },
            function(data){
                $("#ServerID").html(data);
            });
        }
        function change_game_new(game_id){
            $.post("ajax/game.php", { action: "change_game", game_id: game_id },
            function(data){
                $("#ServerID_New").html(data);
            });
        }
    </script>

</html>