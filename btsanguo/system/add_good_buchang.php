<?php
include("inc/CheckUser.php");
include("inc/config.php");
include("../inc/config.php");
include("../inc/function.php");
include("inc/function.php");
include("inc/game_config.php");

if($_REQUEST['action']=='sub'){
    $sid_arr = $_POST['sid'];
    $server_list =  $game_arr[5]['server_list'];
    for($i=0;$i<count($sid_arr);$i++){
        add_good($sid_arr[$i],$server_list);
    }

}
function  add_good($SvrID,$server_list){


    $buchang_begin_time =  $_POST['buchang_begin_time'];
    $buchang_end_time =  $_POST['buchang_end_time'];
    $create_begin_time =  $_POST['create_begin_time'];
    $create_end_time =  $_POST['create_end_time'];

    if($buchang_begin_time){
        $time_begin = date("ymdHi",strtotime($buchang_begin_time));
    }

    if($buchang_end_time){
        $time_end = date("ymdHi",strtotime($buchang_end_time)+3600*24);
    }

    if($create_begin_time){
        $create_mintime = date("ymdHi",strtotime($create_begin_time));
    }

    if($create_end_time){
        $create_maxtime = date("ymdHi",strtotime($create_end_time)+3600*24);
    }


    $description = mysql_escape_string($_POST['description']);
    $mail_flag = intval($_POST['mail_flag']);
    $level_min =  intval($_POST['level_min']);
    $level_max =  intval($_POST['level_max']);
    $reputation =  intval($_POST['reputation']);
    $tiredness =  intval($_POST['tiredness']);
    $currency1 =  intval($_POST['currency1']);
    $money =  intval($_POST['money']);
    $emoney =  intval($_POST['emoney']);
    $itemtype1 =  $_POST['itemtype1'];
    $amount1 =  intval($_POST['amount1']);
    $itemtype2 =  $_POST['itemtype2'];
    $amount2 =  intval($_POST['amount2']);
    $itemtype3 =  $_POST['itemtype3'];
    $amount3 =  intval($_POST['amount3']);
    $itemtype4 =  $_POST['itemtype4'];
    $amount4 =  intval($_POST['amount4']);
    $itemtype5 =  $_POST['itemtype5'];
    $amount5 =  intval($_POST['amount5']);
    $itemtype6 = $_POST['itemtype6'];
    $amount6 =  intval($_POST['amount6']);

    if(!check_itemtype($itemtype1,$amount1)){
        return false;
    }
    if(!check_itemtype($itemtype2,$amount2)){
        return false;
    }
    if(!check_itemtype($itemtype3,$amount3)){
        return false;
    }
    if(!check_itemtype($itemtype4,$amount4)){
        return false;
    }
    if(!check_itemtype($itemtype5,$amount5)){
        return false;
    }
    if(!check_itemtype($itemtype6,$amount6)){
        return false;
    }

    SetConn($SvrID);

    //版本号控制在 20000 到 30000之间
    $sql_max = " select max(account_status) account_status_max from u_compensate where account_status >20000 ";
    $sql_result_max = mysql_query($sql_max);
    $result_max = mysql_fetch_array($sql_result_max);
    $account_status_max = $result_max['account_status_max'];
    $account_status = ($account_status_max?$account_status_max:20001)+1;
    if($account_status>30000){
        echo '<script language="javascript" >alert("版本号超过预定数量，请与技术人员联系!");</script>';return;
    }


    //$sql = " insert into u_compensate(mail_flag, time_begin,time_end,account_status,create_mintime,create_maxtime,level_min,level_max,description,money,reputation,tiredness,currency1,emoney,itemtype1,amount1,itemtype2,amount2,itemtype3,amount3,itemtype4,amount4,itemtype5,amount5,itemtype6,amount6,server_id)
    //                    values('$mail_flag', '$time_begin','$time_end','$account_status','$create_mintime','$create_maxtime','$level_min','$level_max','$description','$money','$reputation','$tiredness','$currency1','$emoney','$itemtype1','$amount1','$itemtype2','$amount2','$itemtype3','$amount3','$itemtype4','$amount4','$itemtype5','$amount5','$itemtype6','$amount6','$SvrID') ";
	$sql = " insert into u_compensate(time_begin,time_end,account_status,create_mintime,create_maxtime,level_min,level_max,description,money,reputation,tiredness,currency1,emoney,itemtype1,amount1,itemtype2,amount2,itemtype3,amount3,itemtype4,amount4,itemtype5,amount5,itemtype6,amount6,server_id)
                        values('$time_begin','$time_end','$account_status','$create_mintime','$create_maxtime','$level_min','$level_max','$description','$money','$reputation','$tiredness','$currency1','$emoney','$itemtype1','$amount1','$itemtype2','$amount2','$itemtype3','$amount3','$itemtype4','$amount4','$itemtype5','$amount5','$itemtype6','$amount6','$SvrID') ";

    
    
	$sql_result = mysql_query($sql);
    $request = serialize($_REQUEST);
    $post = serialize($_POST);
    write_log_admin(ROOT_PATH."log","add_good_buchang_view_"," server_id= $SvrID, $post, ".$_SESSION['admin_name']." ".date("Y-m-d H:i:s")."\r\n");
    write_log_admin(ROOT_PATH."log","add_good_buchang_log_"," sql_result=$sql_result,sql=$sql, $request, ".$_SESSION['admin_name']." ".date("Y-m-d H:i:s")."\r\n");
    if($sql_result){
        echo '<script language="javascript" >alert("'.$server_list[$SvrID].'提交成功!");</script>';return;
    }else{
        echo '<script language="javascript" >alert("'.$server_list[$SvrID].'提交失败!");</script>';return;
    }

}

function check_itemtype($itemtype_id,$itemtype_num){
    if(!$itemtype_id){
        return true;
    }
    if(!$itemtype_num){
        $itemtype_num =1;
    }
    SetConn(88);
    $sql = " select * from add_good where itemtype_id='$itemtype_id' ";
    $query = mysql_query($sql);
    $itemtype_info= mysql_fetch_array($query);
    $amount_limit = $itemtype_info['amount_limit'];
    if(!$itemtype_info['id']){
        echo '<script language="javascript" >alert(" 不能添加产品 '.$itemtype_id. '!");</script>';return false;
    }
    if(($itemtype_info['amount_limit']<$itemtype_num)&&$itemtype_info['amount_limit']!=0){
        echo '<script language="javascript" >alert( "'.$itemtype_id.'  数量限制为 '.$itemtype_info['amount_limit'].' !");</script>';return false;
    }
    return true;
}


?>

<html>
    <head>
        <!-- <meta http-equiv="Content-Type" content="text/html; charset=gbk"> -->
        <title>添加物品</title>
        <link href="CSS/Style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="JS/jquery-1.3.2.min.js"></script>
        <script language="javascript" src="JS/calendar.js"></script>
        <script language="javascript" src="JS/ActionFrom.js"></script>
        <script src="JS/FormValid.js" type="text/javascript"></script>
        <script src="JS/common.js" type="text/javascript"></script>

    </head>
    <form name="from1" id="from1" method="POST"  onsubmit=""   action="add_good_buchang.php">
        <body class="main">

            <input type="hidden" name="action" id="action">
            <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">

                <tr>
                    <th colspan="2" align="center">添加物品</th>
                </tr>
                <tr>
                    <td align="right" class="forumRow">游戏</td>
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
                    <td align="right" class="forumRow">服务器</td>
                    <td class="forumRow">
						<input type='checkbox' id="checkAll">全选
                        <?
                        $i=0;
                        foreach ($game_arr[5]['server_list'] as $aKey=>$aValue){
                            $i++;
                            echo "<input type='checkbox' name='sid[]' value=\"$aKey\" >".$aValue;
                            if($i%6==0)echo "<br/>";
                        }
                        ?>
                    </td>
                </tr>
				<script type="text/javascript">
	                $(function(){
	        			var $checkall = $("#checkAll");
	        			var $serverInp = $("input[name='sid[]']");
	        			$checkall.click(function(){
	        				var $this = $(this);
	        				if($this.is(':checked')) {
	        					$serverInp.attr("checked", true);
	        				} else {
	        					$serverInp.attr("checked", false);
	        				}
	        			});
	        		})
                </script>
                <tr>
                    <td align="right" class="forumRow">补偿活动领取时间：</td>
                    <td width="85%" class="forumRow">
                        <input name="buchang_begin_time" type="text" size="12" value=""  valid="required" errmsg="领取开始时间不能为空!" readonly onfocus="HS_setDate(this)">
                        --
                        <input name="buchang_end_time" type="text" size="12" value=""  valid="required" errmsg="领取结束时间不能为空!" readonly onfocus="HS_setDate(this)">
                    </td>
                </tr>

                <tr>
                    <td align="right" class="forumRow">角色创建的时间限制：</td>
                    <td width="85%" class="forumRow">
                        <input name="create_begin_time" type="text" size="12" value="" readonly onfocus="HS_setDate(this)">
                        --
                        <input name="create_end_time" type="text" size="12" value="" readonly onfocus="HS_setDate(this)">

                    </td>
                </tr>

                <tr>
                    <td align="right" class="forumRow">角色等级限制(留空为不限制)：</td>
                    <td width="85%" class="forumRow">
                        <input name="level_min" type="text" valid="limit|isInt" errmsg="角色长度只能在1到5位之间|等级只能数字!"  min="1"  max="5"  size="12" value="" >
                        --
                        <input name="level_max" type="text" size="12" value="" >

                    </td>
                </tr>


                <tr>
                    <td align="right" class="forumRow">金钱   :</td>
                    <td>
                        <input name="money" valid="limit|isInt" errmsg="金钱长度只能在1到10位之间|金钱只能数字!"  min="1"  max="10"  value=""/>
                    </td>
                </tr>

                <tr>
                    <td align="right" class="forumRow">声望   :</td>
                    <td>
                        <input name="reputation" valid="limit|isInt" errmsg="声望长度只能在1到10位之间|声望只能数字!"  min="1"  max="10"  value=""/>
                    </td>
                </tr>

                <tr>
                    <td align="right" class="forumRow">体力   :</td>
                    <td>
                        <input name="tiredness" valid="limit|isInt" errmsg="体力长度只能在1到10位之间|体力只能数字!"  min="1"  max="10"   value=""/>
                    </td>
                </tr>

                <tr>
                    <td align="right" class="forumRow">战功   :</td>
                    <td>
                        <input name="currency1" valid="limit|isInt" errmsg="战功长度只能在1到10位之间|战功只能数字!"  min="1"  max="10"   value=""/>
                    </td>
                </tr>
                <tr>
                    <td align="right" class="forumRow">元宝   :</td>
                    <td>
                        <input name="emoney" valid="limit|isInt" errmsg="元宝长度只能在1到4位之间|元宝只能数字!"  min="1"  max="4"   value=""/>
                    </td>
                </tr>


                <tr>
                    <td align="right" class="forumRow">物品1  :</td>
                    <td>
                        物品id: <input name="itemtype1" valid="isInt" errmsg="物品id只能数字!" value=""/>
                        --
                        物品数量: <input name="amount1" valid="isInt" errmsg="物品数量只能数字!" value=""/>
                    </td>
                </tr>

                <tr>
                    <td align="right" class="forumRow">物品2  :</td>
                    <td>
                        物品id: <input name="itemtype2"  valid="isInt" errmsg="物品id只能数字!" value=""/>
                        --
                        物品数量: <input name="amount2" valid="isInt" errmsg="物品数量只能数字!" value=""/>
                    </td>
                </tr>

                <tr>
                    <td align="right" class="forumRow">物品3  :</td>
                    <td>
                        物品id: <input name="itemtype3"  valid="isInt" errmsg="物品id只能数字!" value=""/>
                        --
                        物品数量: <input name="amount3" valid="isInt" errmsg="物品数量只能数字!" value=""/>
                    </td>
                </tr>

                <tr>
                    <td align="right" class="forumRow">物品4  :</td>
                    <td>
                        物品id: <input name="itemtype4"  valid="isInt" errmsg="物品id只能数字!" value=""/>
                        --
                        物品数量: <input name="amount4" valid="isInt" errmsg="物品数量只能数字!" value=""/>
                    </td>
                </tr>

                <tr>
                    <td align="right" class="forumRow">物品5  :</td>
                    <td>
                        物品id: <input name="itemtype5"  valid="isInt" errmsg="物品id只能数字!" value=""/>
                        --
                        物品数量: <input name="amount5" valid="isInt" errmsg="物品数量只能数字!" value=""/>
                    </td>
                </tr>

                <tr>
                    <td align="right" class="forumRow">物品6  :</td>
                    <td>
                        物品id: <input name="itemtype6"  valid="isInt" errmsg="物品id只能数字!" value=""/>
                        --
                        物品数量: <input name="amount6" valid="isInt" errmsg="物品数量只能数字!" value=""/>
                    </td>
                </tr>

                <tr>
                    <td align="right" class="forumRow">领取物品提示信息   :</td>
                    <td>
                        <textarea name="description" cols="50" rows="5"   valid="required" errmsg="领取物品提示信息不能为空!">


                        </textarea>
                    </td>
                </tr>
                
                <tr>
                    <td align="right" class="forumRow">发送邮件:</td>
                    <td>
                    	<select name="mail_flag">
                    		<option value="0">不发送</option>
                    		<option value="1">发送</option>
                    	</select>
                    
                    </td>
                </tr>
                
                
                <tr align="right" class="forumRow">
                    <td>
                        <input type="button"  onclick=" return submitCommonForm(document.getElementById('from1'), 'sub')" value="提交"/>
                </td></tr>

            </table>

        </body>
    </form>
    <script type="text/javascript">
        function change_game(game_id){
            $.post("ajax/game.php", { action: "change_game", game_id: game_id },
            function(data){
                $("#ServerID").html(data);
            });
        }
    </script>

</html>
