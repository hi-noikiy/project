<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-7-4
 * Time: 下午4:32
 */
include '../inc/servers.php';
$data = array();

$dsn = "mysql:dbname=u591;host=localhost;port=3306";
$pdo = new PDO($dsn, 'root', 'root');
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
set_time_limit(0);
$pdo->exec("SET NAMES 'utf8';");
//$date1 = date('Y-m-d', strtotime($_GET['date1']));
//$date2 = date('Y-m-d', strtotime($_GET['date2']));
//$sql = "select PayID, SUM(PayMoney) AS summoney,MAX(Add_Time) AS add_time from pay_log where dwFenBaoID=60022 GROUP BY PayID HAVING summoney>1000";
$sql = "select PayID, SUM(PayMoney) AS summoney,MAX(Add_Time) AS add_time from pay_log where Add_Time>='2014-08-04 00:00:00' AND Add_Time<='2014-08-04 02:00:59' GROUP BY PayID";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
//    $pay = array();
foreach ($data as $dd) {
    $pay[$dd['PayID']] = array(
        $dd['summoney'],
        $dd['add_time'],
    );
}
//    print_r($pay);
//exit;
$idstr = implode(',', array_keys($pay));




$dsn2 = "mysql:dbname=kdgamedata;host=localhost;port=3316";
$pdo2 = new PDO($dsn2, 'root', 't,i7.8fg6sh,5i');
    $sql2 = <<<SQL
SELECT p.name,MAX(logintime) as logintime, p.serverid,l.accountid FROM player p LEFT JOIN loginmac l ON l.accountid=p.accountid
where l.accountid IN($idstr)
group by l.accountid
ORDER BY l.accountid asc
SQL;
    $stmt = $pdo2->prepare($sql2);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
<p>充值大于等于1000元以上的万普用户服务器、角色名、总充值金额以及最后的充值时间</p>
<table>
    <thead>
    <tr>
        <th>账号</th>
        <th>角色名</th>
        <th>充值总金额</th>
        <th>区服</th>
        <th>最后充值时间</th>
        <th>最后登录时间</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($data as $d):?>
        <tr>
            <td><?=$d['accountid']?></td>
            <td><?=iconv('gbk', 'utf-8',$d['name'])?></td>
            <td><?=$pay[$d['accountid']][0]?></td>
            <td>[<?=$d['serverid']?>]<?=$serversList[$d['serverid']]?></td>
            <td><?=$pay[$d['accountid']][1];?></td>
            <td><?=date('Y年m月d日', strtotime($d['logintime']));?></td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>
</body>
</html>

