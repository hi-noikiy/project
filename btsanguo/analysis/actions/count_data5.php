<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-7-4
 * Time: 下午4:32
 */
include '../inc/servers.php';
$data = array();

$dsn = "mysql:dbname=logs;host=localhost;port=3326";
$pdo = new PDO($dsn, 'root', 'root');
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
set_time_limit(0);
$pdo->exec("SET NAMES 'utf8';");
$bt = strtotime('2014-07-20');
$et = '2014-07-31';
$df = (strtotime($et)- $bt) / 86400;
$data = array();
$types = array();
$sqlTypes = "SELECT type,type_name FROM emoney_type";
$stmt = $pdo->prepare($sqlTypes);
$stmt->execute();
$typesTmp = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($typesTmp as $t) {
    $types[$t['type']] = $t['type_name'];
}
for ($i=0; $i<=$df; $i++) {
    $tm =  strtotime("+$i days", $bt);
    $date  = date('Y-m-d',$tm);
    $date1 = date('ymd0000', $tm);
    $date2 = date('ymd2359', $tm);
    $sql = "SELECT SUM(emoney) AS emoney,type FROM `rmb` WHERE daytime>=$date1 and daytime<=$date2 GROUP BY type";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data[$date] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Data</title>
    <style>
        table{border-spacing: 0;width: 90%;}
        td,th{border: 1px solid #000; padding:4px;}
    </style>
</head>
<body>
<table>
    <?php foreach($data as $date=>$lists):?>
        <thead>
            <tr>
                <th colspan="2">日期<?=$date?></th>
            </tr>
            <tr>
                <th>类型</th>
                <th>元宝数</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($lists as $list):?>
            <tr>
                <td>[<?=$list['type']?>]<?=$types[$list['type']]?></td>
                <td><?=$list['emoney']?></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    <?php endforeach;?>
</table>
</body>
</html>

