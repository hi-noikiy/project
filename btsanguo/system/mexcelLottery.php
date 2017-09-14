<?php
ini_set('display_errors',false);
//include("inc/CheckUser.php");
include("../inc/config.php");
include("inc/function.php");
include("../inc/function.php");

$StarTime=CheckStr($_REQUEST["StarTime"]);

    //默认时间
    if ($StarTime==""){
         exit;
    }

Header("Content-type:   application/octet-stream");   
Header("Accept-Ranges:   bytes");
Header("Content-type:application/vnd.ms-excel");   
Header("Content-Disposition:attachment;filename=luck_".$StarTime.".xls");
 
	$prints =  "<table border=1><tr> <td >编号</td>
				 <td>玩家账号</td>
                           <td>金额</td>
                  <td width='200'>订单号</td>
                  <td>日期</td>
          </tr>";
    
    #在数据库时间条件加1天
    $sql=" And Add_Time >= '$StarTime' And Add_Time < DATE_ADD('$StarTime',INTERVAL 1 DAY)";
    $sqlstr1="SELECT PayName,PayMoney,OrderID FROM pay_log where 1".$sql." And rpCode in ('1','10') and PayMoney>='50' and  mod(cast(OrderID AS UNSIGNED),60)=0 order by id desc";
	//echo $SqlStr2;
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
			  $prints .= "<td  align=left>".$row['PayMoney']."</td>";
                          $prints .= "<td  align=left>id".$row['OrderID']."</td>";
			  $prints .= "<td  align=left>".$StarTime."</td>";
			  $prints .= "</tr>";
	 $num2--;      
}

$prints .= "</table>";
print $prints;

?>
