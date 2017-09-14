<?
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");
include("inc/page.php");
include("../inc/config.php");
include("../inc/function.php");

if($_POST['action']=="do"){

    set_time_limit(300);

    $houre = $_POST['houre'];
    $date = $_POST['date'];

    $date_int = strtotime($date);
    $date_int_str = date("Y-m-d",$date_int);
    $time_int = mktime(intval($houre), 0, 0, date("m",$date_int), date("d",$date_int), date("Y",$date_int));
    $time = date('Y-m-d H:i:s',$time_int);

    $time_int_next = mktime(intval($houre)+1, 0, 0, date("m",$date_int), date("d",$date_int), date("Y",$date_int));
    $time_next = date('Y-m-d H:i:s',$time_int_next);

    SetConn(88);
    $sql = " select ServerID,PayID,OrderID,PayMoney from pay_log where Add_Time>='$time' and Add_Time<='$time_next' and rpCode=1 ";
    $query=mysql_query($sql);

    $y=0;
    while($rs=mysql_fetch_array($query)){
        $result[$y]['OrderID'] = $rs['OrderID'];
        $result[$y]['PayID'] = $rs['PayID'];
        $result[$y]['ServerID'] = $rs['ServerID'];
        $result[$y]['PayMoney'] = $rs['PayMoney'];
        $y++;
    }

    for($i=0;$i<count($result);$i++){
        $ServerID = $result[$i]['ServerID'];
        $PayID = $result[$i]['PayID'];
        $OrderID = $result[$i]['OrderID'];
        $PayMoney = $result[$i]['PayMoney'];
        $write_card_result[] =  write_card($ServerID,$PayID,$OrderID,$PayMoney);
    }


    for($i=0;$i<count($write_card_result);$i++){
        if($write_card_result[$i]){
            $result_str =  $result_str."订单号：".$write_card_result[$i].",成功补库";echo "<br/>";
        }else{
            $result_str =  $result_str."订单号：".$write_card_result[$i].",";echo "<br/>";
        }
    }
    write_log(ROOT_PATH."log","admin_pay_get_time_log_","$result_str,  ".date("Y-m-d H:i:s")."\r\n");
    echo $result_str;
    exit("补库结束");
}


function write_card($ServerID,$PayID,$OrderID,$PayMoney,$type=8){
    SetConn($ServerID);
    $sql="select count(0) from u_card where ref_id='$OrderID'";
    $query=mysql_query($sql);
    $RowCount=mysql_result($query,0);
    if ($RowCount == 0){
        $time_stamp=date('ymdHi');
        $sql_insert="insert into u_card(data,account_id,ref_id,time_stamp,used,type,server_id)";
        $sql_insert=$sql_insert." values('$PayMoney',$PayID,'$OrderID',$time_stamp,0,'$type','$ServerID')";
        if(mysql_query($sql_insert)){
            return $OrderID;
        }
    }
    return null;
}



?>
<html>
    <head>
        <title>payget</title>
        <meta http-equiv="Content-Type" content="text/html; charset=gb2312">
        <link href="CSS/Style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="JS/calendar.js"></script>
        <script language="javascript" src="JS/jquery-1.3.2.min.js"></script>
    </head>
    <body class="main">

        <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
            <form name="SearchForm" method="POST" action="">
                <input name="action" type="hidden" value="do">
                <tr>
                    <th height="22" colspan="2" align="center">每次只能补一个小时</th>
                </tr>
                <tr>
                    <td width="15%" align="right" class="forumRow">日期：</td>
                    <td width="85%" class="forumRow"><input name="date" type="text" size="12" value="<?=$date?>" readonly onfocus="HS_setDate(this)"><?getHours($houre,'e')?></td>
                </tr>
                <tr>
                    <td align="right" class="forumRow"></td>
                    <td class="forumRow"><input type="submit" name="Submit2" class="bott01" value=" 确 定 "></td>
                </tr>
            </form>
        </table>

        <?
        mysql_close();
        ?>
    </body>
</html>