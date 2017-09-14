<?php

include_once 'init.php';

$s_Time = '2014-11-28 23:30:00';

$e_Time = '2014-11-29 00:30:00';

SetConn(88);
$sql = " select ServerID,PayID,OrderID,PayMoney,Add_Time from pay_log where Add_Time>='$s_Time' and Add_Time<='$e_Time' and rpCode=1 ";
$query=mysql_query($sql);

$y=0;
while($rs=mysql_fetch_array($query)){
    $result[$y]['OrderID'] = $rs['OrderID'];
    $result[$y]['PayID'] = $rs['PayID'];
    $result[$y]['ServerID'] = $rs['ServerID'];
    $result[$y]['PayMoney'] = $rs['PayMoney'];
    $result[$y]['Add_Time'] = $rs['Add_Time'];
    $y++;
}


for($i=0;$i<count($result);$i++){
    $ServerID = $result[$i]['ServerID'];
    $PayID = $result[$i]['PayID'];
    $OrderID = $result[$i]['OrderID'];
    $PayMoney = $result[$i]['PayMoney'];
    $Add_Time = $result[$i]['Add_Time'];
    
    SetConn($ServerID);
    $sql="select * from u_card where ref_id='$OrderID'";
    $query = mysql_query($sql);
    $rs=mysql_fetch_array($query);
    
    $game_time_stamp =  $rs['time_stamp'];
    $web_time_stamp=date('ymdHi',strtotime($Add_Time));

    if(($game_time_stamp-$web_time_stamp)>100){
       echo "OrderID=$OrderID,ServerID=$ServerID,PayMoney=$PayMoney,PayID=$PayID,";echo "<br/>";
    }

}






?>
