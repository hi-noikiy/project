<?php
/**
 * @created by PhpStorm.
 * @user: luoxue
 * @date: 2017/4/14 上午11:22
 * @desc: 清除google
 * @param:
 * @return:
 */
include_once 'config.php';


$gameId = 8;

global $accountServer;
$accountConn = $accountServer[$gameId];
$conn = SetConn($accountConn);

//$insert_sql = "insert into account_ggp (ggp_account,account_id,bind_time) VALUES ('$channel_account','$accountId','$bind_time')";


//$sql = "select * from account_ggp";
//$query = mysqli_query($conn, $sql);
//while ($row = mysqli_fetch_assoc($query)){
//
//
//    print_r($row['ggp_account'].'=='.$row['account_id']);
//    echo '<br>';
//}
//$delSql = "delete from account_ggp where ggp_account like '%@fb%'";
//
//if(mysqli_query($conn, $delSql))
//    echo 'success';
//else
//    echo mysqli_error($conn);

