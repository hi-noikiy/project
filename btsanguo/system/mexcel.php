<?php
ini_set('display_errors',true);
include("inc/CheckUser.php");
include("../inc/config.php");
include("inc/function.php");
include("../inc/function.php");

Header("Content-type:   application/octet-stream");   
Header("Accept-Ranges:   bytes");   
Header("Content-type:application/vnd.ms-excel");   
Header("Content-Disposition:attachment;filename=".date("Y-m-d h:i:s").".xls"); 
 
	$prints =  "<table border=1><tr> <td >编号</td>
				 <td>玩家账号</td>
                           <td>推广商ID</td>
                  <td>金额</td>
                  <td>时间段</td>
          </tr>";
$bw = $_REQUEST['bw'];

    $StarTime=CheckStr($_REQUEST["StarTime"]);
    $EndsTime=CheckStr($_REQUEST["EndsTime"]);
    $game_id=CheckStr($_REQUEST["game_id"]);
    //默认时间
    if ($StarTime=="" && $EndsTime==""){
            //$StarTime=date("Y")."-".date("m")."-01";
            $StarTime=date("Y-m-d");
            $EndsTime=date("Y-m-d");
    }
    #在数据库时间条件加1天
    $sql=" And Add_Time >= '$StarTime' And Add_Time < DATE_ADD('$EndsTime',INTERVAL 1 DAY)";
    if($game_id){
        $sql .= " and game_id=$game_id ";
    }
    if($bw)
    {
        $sql .= " and dwFenBaoID ='".$bw."'";
    }
    else
    {
        $bw = '';
    }
		$sqlstr1 = "SELECT PayName,dwFenBaoID,sum(PayMoney) as c FROM pay_log where 1".$sql." And rpCode in ('1','10') group by PayName order by sum(PayMoney) desc";
//echo $sqlstr1;
//exit;
SetConn(88);

$res1 = mysql_query($sqlstr1);
$num1 = mysql_num_rows($res1);
$num2 = $num1;

while ($num2 > 0)
{

		      $row=mysql_fetch_array($res1);	
	   $j++;         
			  $prints .= "<tr>";
			  $prints .= "<td  align=left>".$j."</td>";
			  $prints .= "<td  align=left>".$row['PayName']."</td>";
			  $prints .= "<td  align=left>".$row['dwFenBaoID']."</td>";
                          $prints .= "<td  align=left>".$row['c']."</td>";
			  $prints .= "<td  align=left>".$StarTime. "~ ".$EndsTime ."</td>";
			  $prints .= "</tr>";
	 $num2--;      
}

$prints .= "</table>";
print $prints;

?>
