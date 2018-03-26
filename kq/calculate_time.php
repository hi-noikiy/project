<?php
include_once ('common.inc.php');
$last2day = date ( 'Y-m-d', strtotime('-1 days') );
$yestoday = date ( 'Y-m-d', strtotime('-1 days') );
totaltime ( $last2day, $yestoday, '' ); // 计算上班有效时间


?>