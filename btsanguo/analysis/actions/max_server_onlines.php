<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-7-4
 * Time: 下午4:32
 */
$data = array();
if (!empty($_GET['date1']) && !empty($_GET['date2'])){
    $dsn = "mysql:dbname=kdgamedata;host=localhost;port=3316";
    $pdo = new PDO($dsn, 'root', 't,i7.8fg6sh,5i');
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    set_time_limit(0);
    $date1 = date('ymd0000', strtotime($_GET['date1']));// .' '. $_GET['date1_1'].':00';
    $date2 = date('ymd2359', strtotime($_GET['date2']));// .' '. $_GET['date2_1'].':59';
//    1512010359
    $sql  = "SELECT max(online) as online,serverid FROM online WHERE daytime BETWEEN ? AND ? GROUP BY serverid";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($date1, $date2));
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>最大在线数统计</title>
    <style>
        table{border-spacing: 0;width: 90%;}
        td,th{border: 1px solid #000; padding:4px;}
    </style>
</head>
<body>
<p>

<form action="#" method="get">
    时间：<input type="date" name="date1" id=""/>
    至<input type="date" name="date2"id=""/>
    <input type="submit" value="提交"/>
</form>
</p>
<table>
    <thead>
    <tr>
        <th>区服ID</th>
        <th>最大在线</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($data as $d):?>
        <tr>
            <td><?=$d['serverid']?></td>
            <td><?=$d['online']?></td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>
</body>
</html>

