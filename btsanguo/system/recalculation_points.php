<?
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");
include("inc/page.php");
include("../inc/config.php");
include("../inc/function.php");

if($_REQUEST['action']=='recalculation'){
    $account_name = trim($_REQUEST['account_name']);
    $account_id = trim($_REQUEST['account_id']);
    recalculation($account_name,$account_id);
}
function recalculation($account_name,$account_id){
    $account_name = trim($account_name);
    if(!$account_name&&!$account_id){
        echo "<script> alert(\"账号或者账号id不能为空\"); </script>";
        return;
    }
     SetConn(81);
    if($account_name){
        $sql = " select id from account where NAME = '$account_name' ";
        $conn=mysql_query($sql);
        $rs=mysql_fetch_array($conn);
        if(!$rs){
            echo "<script> alert(\"账号不存在\"); </script>";return;
        }else{
            $pay_id = $rs['id'];
        }
    }else if($account_id){
        $pay_id = $account_id;
    }
    $mypointsql = "select points,pointstotal from vipPoints where account_id='".$pay_id."' limit 0,1";
    $mypoint = mysql_query($mypointsql);
    $pointmy_result =  mysql_fetch_array($mypoint);
     $pointmy = $pointmy_result['points'];
     $pointstotal = $pointmy_result['pointstotal'];
        $newtag = 0;                     //新等级
        $date = date('Y-m-d');
        if($pointstotal>0 && $pointstotal<500)
        {
            $newtag = 0;
        }
        elseif($pointstotal>=500 && $pointstotal<1500)
        {
            $newtag = 1;
        }
        elseif($pointstotal>=1500 && $pointstotal<3000)
        {
            $newtag = 2;
        }
        elseif($pointstotal>=3000 && $pointstotal<5000)
        {
            $newtag = 3;
        }
        elseif($pointstotal>=5000&& $pointstotal<10000)
        {
            $newtag = 4;
        }

        elseif($pointstotal>=10000 && $pointstotal<20000)
        {
            $newtag = 5;
        }
        elseif($pointstotal>=20000 )
        {
            $newtag = 6;
        }

    if($pointstotal){
        $addsql_a = "update account set points='$pointmy',vip='$newtag' where id='".$pay_id."'";
        $ret_a = mysql_query($addsql_a);
        if($ret_a){
            updateRankUp($pay_id,'f_dx',"admin");//改等级
            echo "<script> alert(\"处理完成\"); </script>";return;
        }else{
            echo "<script> alert(\"数据库处理异常\"); </script>";
        }
    }else{
        echo "<script> alert(\"该账号无须重算\"); </script>";
    }
}


?>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=gb2312">
        <link href="CSS/Style.css" rel="stylesheet" type="text/css">

    </head>
    <body class="main">

        <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
            <form name="SearchForm" method="POST" action="?action=recalculation">
                <tr>
                    <th height="22" colspan="2" align="center">账号积分等级弥补</th>
                </tr>
                <tr>
                    <td width="15%" align="right" class="forumRow">账号：</td>
                    <td width="85%" class="forumRow"><input name="account_name" type="text" value="<?=$account_name?>" size="30"> 输入账号的情况下，不考虑账号id </td>
                </tr>
                <tr>
                    <td width="15%" align="right" class="forumRow">账号id：</td>
                    <td width="85%" class="forumRow"><input name="account_id" type="text" value="<?=$account_id?>" size="30">   </td>
                </tr>
                <tr>
                    <td align="right" class="forumRow"></td>
                    <td class="forumRow"><input type="submit" name="Submit2" class="bott01" value=" 确 定 "></td>
                </tr>
            </form>
        </table>

    </body>
</html>