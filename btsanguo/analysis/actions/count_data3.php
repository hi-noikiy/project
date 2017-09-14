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
if (!empty($_GET['date1'])){
    $dsn2 = "mysql:dbname=kdgamedata;host=localhost;port=3316";
    $pdo2 = new PDO($dsn2, 'root', 't,i7.8fg6sh,5i');
    $sql = "SELECT accountid FROM loginmac WHERE logintime=?";
    $stmt = $pdo2->prepare($sql);
    $stmt->execute(array(
        date('Ymd', strtotime($_GET['date1']))
    ));

    $loginArr = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $loginCnt = count($loginArr);
    $idstr = implode(',', $loginArr);


    $year_month = intval($_GET['month']);
    $ctb = $year_month . '010000';
    $cte = $year_month . '310000';
    $sql2 = "SELECT COUNT(*) FROM newmac where accountid IN($idstr) AND createtime>=? AND createtime<=?";
    $stmt = $pdo2->prepare($sql2);
    $stmt->execute(array($ctb, $cte));
    $total = $stmt->fetchColumn(0);
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
<div>
    统计下6月26和6月27日有多少是6月份注册的活跃，以及7月26日和7月27日有多少是7月份注册
</div>
<p>
<form action="#" method="get">
    登录时间：<input type="date" name="date1" id=""/>
    注册月份： <input type="number" name="month" id="" placeholder="ym格式"/>
    <input type="submit" value="提交"/>
</form>
</p>
<table>
    <thead>
    <tr>
        <th>活跃数</th>
        <th>注册数</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td><?=$loginCnt;?></td>
        <td><?=$total;?></td>
    </tr>
    </tbody>
</table>
</body>
</html>

