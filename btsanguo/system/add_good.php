<?php
include("inc/CheckUser.php");
include("inc/config.php");
include("../inc/config.php");
include("../inc/function.php");
include("inc/function.php");
include("inc/game_config.php");

if($_REQUEST['action']=='doaction'){
    $game_id = $_POST['game_id'];
    $SvrID = $_POST['SvrID'];
    $player_name = $_POST['player_name'];
    $itemtype_id = $_POST['itemtype_id'];
    $itemtype_num = $_POST['itemtype_num'];
    $email_message = $_POST['email_message'];
   
    add_good($game_id,$SvrID,$player_name,$itemtype_id,$itemtype_num,$email_message);


}
function  add_good($game_id,$SvrID,$player_name,$itemtype_id,$itemtype_num,$email_message){

    if(!$game_id||!$SvrID||!$player_name||!$itemtype_id||!$email_message){
        echo '<script language="javascript" >alert("game ,server,player_name,itemtype_id,email_message  is not null!");</script>';
        return;
    }
    SetConn(88);
    $sql = " select * from add_good where itemtype_id='$itemtype_id' ";
    $query = mysql_query($sql);
    $itemtype_info= mysql_fetch_array($query);
    $amount_limit = $itemtype_info['amount_limit'];
    if(!$itemtype_info['id']){
        echo '<script language="javascript" >alert(" 不能添加该产品 !");</script>';return;
    }
    if(($itemtype_info['amount_limit']<$itemtype_num)&&$itemtype_info['amount_limit']!=0){
        echo '<script language="javascript" >alert(" 数量限制为 '.$itemtype_info['amount_limit'].' !");</script>';return;
    }
    $sql_string_1 = $itemtype_info['sql_string_1'];
    $sql_string_2 = $itemtype_info['sql_string_2'];
    $sql_string_3 = $itemtype_info['sql_string_3'];
    

    SetConn($SvrID);
    $sql = " select * from u_player where name='".iconv('UTF-8', 'GBK', $player_name) ."' ";
    $query = mysql_query($sql);
    $player_info= mysql_fetch_array($query);
    $player_id = $player_info['id'];
    if(!$player_id){
        echo '<script language="javascript" >alert("角色不存在!");</script>';return;
    }
 
    if($sql_string_1){
        $sql_string_1 = str_ireplace('#player_id',$player_id,$sql_string_1);
        $sql_string_1 = str_ireplace('#player_id',$player_id,$sql_string_1);
        $sql_string_1 = str_ireplace('#player_id',$player_id,$sql_string_1);
        $sql_string_1 = str_ireplace('#player_name',$player_name,$sql_string_1);
        $sql_string_1 = str_ireplace('#player_name',$player_name,$sql_string_1);
        $sql_string_1 = str_ireplace('#amount_limit',$amount_limit,$sql_string_1);
        $sql_string_1 = str_ireplace('#amount',$itemtype_num,$sql_string_1);
        $sql_string_1 = str_ireplace('#itemtype_id',$itemtype_id,$sql_string_1);
        $sql_string_1 = str_ireplace('#email_message',$email_message,$sql_string_1);
        $sql_string_1_result =  mysql_query($sql_string_1);
     //   mysql_error();
      //  write_log_admin(ROOT_PATH."log","add_good_log_",mysql_error()." 1 result1=$sql_string_1_result,result2=$sql_string_2_result,result3=$sql_string_2_result,sql_string_1=$sql_string_1,sql_string_2=$sql_string_2,sql_string_3=$sql_string_3,  ".date("Y-m-d H:i:s")."\r\n");
        
    }
    if($sql_string_2){
        $sql_string_2 = str_ireplace('#player_id',$player_id,$sql_string_2);
        $sql_string_2 = str_ireplace('#player_id',$player_id,$sql_string_2);
        $sql_string_2 = str_ireplace('#player_id',$player_id,$sql_string_2);
        $sql_string_2 = str_ireplace('#player_name',$player_name,$sql_string_2);
        $sql_string_2 = str_ireplace('#player_name',$player_name,$sql_string_2);
        $sql_string_2 = str_ireplace('#amount_limit',$amount_limit,$sql_string_2);
        $sql_string_2 = str_ireplace('#amount',$itemtype_num,$sql_string_2);
        $sql_string_2 = str_ireplace('#itemtype_id',$itemtype_id,$sql_string_2);
        $sql_string_2 = str_ireplace('#email_message',$email_message,$sql_string_2);
        $sql_string_2 = str_ireplace('#time',time(),$sql_string_2);
        $sql_string_2_result =  mysql_query($sql_string_2);
        // write_log_admin(ROOT_PATH."log","add_good_log_",mysql_error()." 2 result1=$sql_string_1_result,result2=$sql_string_2_result,result3=$sql_string_2_result,sql_string_1=$sql_string_1,sql_string_2=$sql_string_2,sql_string_3=$sql_string_3,  ".date("Y-m-d H:i:s")."\r\n");
    }
    if($sql_string_3){
        
        $sql_string_3 = str_ireplace('#player_id',$player_id,$sql_string_3);
        $sql_string_3 = str_ireplace('#player_id',$player_id,$sql_string_3);
        $sql_string_3 = str_ireplace('#player_id',$player_id,$sql_string_3);
        $sql_string_3 = str_ireplace('#player_name',$player_name,$sql_string_3);
        $sql_string_3 = str_ireplace('#player_name',$player_name,$sql_string_3);
        $sql_string_3 = str_ireplace('#amount_limit',$amount_limit,$sql_string_3);
        $sql_string_3 = str_ireplace('#amount',$itemtype_num,$sql_string_3);
        $sql_string_3 = str_ireplace('#itemtype_id',$itemtype_id,$sql_string_3);
        $sql_string_3 = str_ireplace('#email_message',$email_message,$sql_string_3);

        $sql_string_3_result =  mysql_query($sql_string_3);
    }
    write_log_admin(ROOT_PATH."log","add_good_view_"," game_id=$game_id  ,server_id=$SvrID,player_name=$player_name,itemtype_id=$itemtype_id,itemtype_num=$itemtype_num,email_message=$email_message,  ".$_SESSION['admin_name']."  ".date("Y-m-d H:i:s")."\r\n");
    write_log_admin(ROOT_PATH."log","add_good_log_"," result1=$sql_string_1_result,result2=$sql_string_2_result,result3=$sql_string_2_result,sql_string_1=$sql_string_1,sql_string_2=$sql_string_2,sql_string_3=$sql_string_3,  ".$_SESSION['admin_name']."  ".date("Y-m-d H:i:s")."\r\n");
    if($sql_string_1_result){
        echo '<script language="javascript" >alert("成功!");</script>';return;
    }else{
         echo '<script language="javascript" >alert("失败!");</script>';return;
    }

}

?>
<html>
<head>
	<!-- <meta http-equiv="Content-Type" content="text/html; charset=gbk"> -->
	<title>添加物品</title>
	<link href="CSS/Style.css" rel="stylesheet" type="text/css">
	<script language="javascript" src="JS/jquery-1.3.2.min.js"></script>
</head>
<body class="main">
	<form id="myform" name="form1" method="POST"  enctype="multipart/form-data" action="" onsubmit="return CheckForm();">
		<input type="hidden" name="action" value="doaction">
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
					<select name="SvrID" id="ServerID">
						<option value="" selected="selected">选择服务器</option>
						<?php
                            foreach($game_arr[$_POST['game_id']]['server_list'] as $game_key=>$game_value){
                                echo "<option value=\"".$game_key."\" ".(($_POST['SvrID']==$game_key)?'selected="selected"':'')." >".$game_value."</option>";
							}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right" class="forumRow"> 角色名 :</td>
				<td><input name="player_name" value="<?php echo $_POST['player_name']?>"/></td>
			</tr>
			<tr>
				<td align="right" class="forumRow">物品id   :</td>
				<td>
					<input name="itemtype_id" value="<?php echo $itemtype_id; ?>"/>
				</td>
			</tr>
			<tr>
				<td align="right" class="forumRow">添加物品数量   :</td>
				<td>
					<input name="itemtype_num" value="<?php echo $itemtype_num?$itemtype_num:'1'; ?>"/>
				</td>
			</tr>
			<tr>
				<td align="right" class="forumRow">发送邮件信息   :</td>
				<td>
					<input name="email_message" value="<?php echo $email_message?$email_message:'email message'; ?>"/>
				</td>
			</tr>
			<tr align="right" class="forumRow"><td><input type="submit" name="submit" value="submit"/></td></tr> 
        </table>
	</form>
</body>
<script type="text/javascript">
function change_game(game_id){
	$.post("ajax/game.php", { action: "change_game", game_id: game_id },
	function(data){
		$("#ServerID").html(data);
	});
}
function CheckForm(){
	var $myform = $("#myform");
	var $name = $myform.find("input[name='player_name']");
	if($name.val() == ''){
		alert('角色名不能为空！');
		$name.focus();
		return false;
	}
	var $itemtypeId = $myform.find("input[name='itemtype_id']");
	if($itemtypeId.val() == ''){
		alert('物品id不能为空！');
		$itemtypeId.focus();
		return false;
	}

	var $itemtypeNum = $myform.find("input[name='itemtype_num']");
	if($itemtypeNum.val() == ''){
		alert('添加物品数量不能为空！');
		$itemtypeNum.focus();
		return false;
	}
	var msg = '你确定要为角色名为：'+$name.val()+'发放'+$itemtypeNum.val()+'个物品(物品id:'+$itemtypeId.val()+')吗？'
	if(confirm(msg)){
		return true;
	}
	return false;	
}
</script>
</html>