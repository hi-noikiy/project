<?php
include("inc/CheckUser.php");
include("inc/config.php");
include("../inc/config.php");
include("../inc/function.php");
include("inc/function.php");
include("inc/game_config.php");

if($_REQUEST['action']=='doaction'&&$_REQUEST['old_account']&&$_REQUEST['new_account']){
    $old_account = $_REQUEST['old_account'];
    $new_account = $_REQUEST['new_account'];
    
    account_change($old_account,$new_account);


}

function account_change($old_account,$new_account){
    SetConn(81);
    $old_account = mysql_real_escape_string($old_account);
    $new_account = mysql_real_escape_string($new_account);

    $sql_old = " select * from account where NAME='$old_account' ";
    $query_old = mysql_query($sql_old);
    $old_info= mysql_fetch_array($query_old);
    $old_id = $old_info['id'];
    $old_NAME = $old_info['NAME'];
    $old_channel_account = mysql_real_escape_string($old_info['channel_account']);


    $sql_new = " select * from account where NAME='$new_account' ";
    $query_new = mysql_query($sql_new);
    $new_info= mysql_fetch_array($query_new);
    $new_id =  $new_info['id'];
    $new_NAME = $new_info['NAME'];
	$new_dwFenBaoID=$new_info['dwFenBaoID'];
    $new_channel_account = mysql_real_escape_string($new_info['channel_account']);

    $rand = time().rand(100000,1000000);
     $ip = getIP_front();

    if($old_id&&$new_id){
        $sql_1 = " update account set  name='$old_NAME@tingyong',channel_account='".$old_channel_account.$rand."@tingyong'  where id ='$new_id';";
        $sql_2 = " update account set  name='$new_NAME',channel_account='$new_channel_account',dwFenBaoID='$new_dwFenBaoID'  where id ='$old_id'; ";
        if(mysql_query($sql_1)&&mysql_query($sql_2)){
           echo '<script language="javascript" >alert("成功!");</script>';return;
        }
        $_REQUEST_str =  serialize($_REQUEST);
        write_log_admin(ROOT_PATH."log","account_change_log_","  request = $_REQUEST_str, $sql_1,$sql_2,ip=$ip,$AdminName  ".date("Y-m-d H:i:s")."\r\n");
    }else{
        echo '<script language="javascript" >alert("失败!账号不存在!");</script>';return;
    }
}

?>

<html>
    <head>
        <!-- <meta http-equiv="Content-Type" content="text/html; charset=gbk"> -->
        <title>账号转换</title>
        <link href="CSS/Style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="JS/jquery-1.3.2.min.js"></script>

    </head>
    <body class="main">
        <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
            <form name="form1" method="POST"  enctype="multipart/form-data" action="">
                <input type="hidden" name="action" value="doaction">
                <tr>
                    <th colspan="2" align="center">账号转换(本功能提示“失败”的情况下，联系技术人员)</th>
                </tr>

                <tr>
                    <td align="right" class="forumRow">停用账号   :</td>
                    <td>
                        <input name="old_account" value=""/>
                    </td>
                </tr>
                <tr>
                    <td align="right" class="forumRow">启用账号   :</td>
                    <td>
                        <input name="new_account" value=""/>
                    </td>
                </tr>

                <tr align="right" class="forumRow"><td><input type="submit" name="submit" value="submit"/></td></tr>
            </form>
        </table>

    </body>


</html>
