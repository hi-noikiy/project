<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 7/21/14
 * Time: 2:29 PM
 */

$dsn = "mysql:dbname=u591;host=localhost;port=3306";
$pdo = new PDO($dsn, 'root', 'root');
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
set_time_limit(0);
$sql1 = <<<SQL
SELECT sday,SUM(day3) as day3, SUM(day7) as day7,sum(day15) as day15, sum(day30) as day30
FROM sum_reserveusers_daily
WHERE sday BETWEEN ? AND ?
GROUP BY sday
SQL;
$t1 = isset($_GET['month1']) ? strval($_GET['month1']).'01' : date('Ym01');
$t2 = isset($_GET['month2']) ? strval($_GET['month2']).'31' : date('Ym31');
$stmt = $pdo->prepare($sql1);
$stmt->execute(array($t1, $t2));
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>留存汇总</title>
    <style>
        table{border-spacing: 0;width: 90%;}
        td,th{border: 1px solid #000; padding:4px;}
    </style>
</head>
<body>
<form action="#" method="get">
    月份：<input type="number" name="month1" id="" placeholder="Ym格式,如:201511"/>
    至： <input type="number" name="month2" id="" placeholder="Ym格式,如:201601"/>
    <input type="submit" value="提交"/>
</form>
<table>
    <thead>
    <tr>
        <th>日期</th>
        <th>3日留存</th>
        <th>7日留存</th>
        <th>15日留存</th>
        <th>30日留存</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($data as $key=>$val):?>
        <tr>
            <td><?=$val['sday']?></td>
            <td><?=$val['day3']?></td>
            <td><?=$val['day7']?></td>
            <td><?=$val['day15']?></td>
            <td><?=$val['day30']?></td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>
</body>
</html>
