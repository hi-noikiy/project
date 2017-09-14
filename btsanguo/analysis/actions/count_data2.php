<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-7-4
 * Time: 下午4:32
 */
include '../inc/servers.php';
$data = array();
set_time_limit(6000);
if (!empty($_GET['date1']) && !empty($_GET['date2'])){
    $dsn = "mysql:dbname=u591;host=localhost;port=3306";
    $pdo = new PDO($dsn, 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES 'utf8';");
    $date1 = date('Y-m-d 00:00:00', strtotime($_GET['date1']));
    $date2 = date('Y-m-d 23:59:59', strtotime($_GET['date2']));
    $sql = "select DISTINCT PayID from pay_log where Add_Time BETWEEN ? AND ?";
    $stmt = $pdo->prepare($sql);
//    print_r(array($date1, $date2));
    $stmt->execute(array($date1, $date2));
    $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
//    print_r($ids);
    $idstr = implode(',', $ids);

    $dsn2 = "mysql:dbname=kdgamedata;host=localhost;port=3316";
    $pdo2 = new PDO($dsn2, 'root', 't,i7.8fg6sh,5i');
    $sql2 = <<<SQL
    SELECT accountid,createtime FROM newmac where accountid IN($idstr)
SQL;
    $stmt = $pdo2->prepare($sql2);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
//    print_r($data);
//    $payCount = $stmt->fetchColumn(0);
//    $payMoney = $stmt->fetchColumn(1);

//print_r( $stmt->fetchAll(PDO::FETCH_ASSOC) );
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>根据注册时间统计充值人数和充值金额</title>
    <style>
        table{border-spacing: 0;width: 90%;}
        td,th{border: 1px solid #000; padding:4px;}
    </style>
</head>
<body>
<p>

<form action="#" method="get">
    注册时间：<input type="date" name="date1" id=""/>
    至<input type="date" name="date2"id=""/>
    <input type="submit" value="提交"/>
</form>
</p>
<table>
    <thead>
    <tr>
        <th>账号</th>
        <th>注册时间</th>
    </tr>
    </thead>
    <tbody>
    <?foreach($data as $d):?>
        <tr>
            <td><?=$d['accountid']?></td>
            <td><?=date('Y-m-d H:i', strtotime('20'.$d['createtime']));?></td>
        </tr>
    <?endforeach;?>
    </tbody>
</table>
</body>
</html>

