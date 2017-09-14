<?php
include_once 'config.php';

$conn = SetConn(8002);


/*$sql = "select count(*) from u_accountlimit  limit 1";
$query = mysqli_query($conn, $sql);
$RowCount = mysqli_fetch_assoc($query);*/
$sql = "select count(*) as count from u_accountlimit where account_id='7121'";
$query = mysqli_query($conn, $sql);
$RowCount = @mysql_result($query,0);


echo '<pre>';
print_r($RowCount);
