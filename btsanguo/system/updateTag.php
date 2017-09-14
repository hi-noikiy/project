<?php
include("inc/CheckUser.php");
include("../inc/config.php");
include("inc/function.php");
include("../inc/function.php");

    SetConn(88);
    $upsql_p ="update `pay_log` set tag='1'";
    mysql_query($upsql_p);
    $upsql_d ="update `pay_sms` set tag='1'";
    mysql_query($upsql_d);
?>