<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-7-4
 * Time: 下午4:32
 */
include '../inc/servers.php';
$data = array();

    $dsn = "mysql:dbname=u591_new;host=localhost;port=3306";
    $pdo = new PDO($dsn, 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    set_time_limit(0);
    $pdo->exec("SET NAMES 'utf8';");
    $date1 = date('Y-m-d', strtotime($_GET['date1']));
    $date2 = date('Y-m-d', strtotime($_GET['date2']));
    $sql = "select PayID, SUM(PayMoney) AS summoney from pay_log where ServerID IN(501,502,503) GROUP BY PayID HAVING summoney>2000";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
//    $pay = array();
    foreach ($data as $dd) {
        $pay[$dd['PayID']] = $dd['summoney'];
    }
//    print_r($pay);
    $idstr = implode(',', array_keys($pay));
    $sql2 = <<<SQL
SELECT p.name,MAX(logintime) as logintime, p.serverid,l.accountid FROM player p LEFT JOIN loginmac l ON l.accountid=p.accountid
where l.accountid IN($idstr)
group by l.accountid
ORDER BY l.accountid asc
SQL;
    $stmt = $pdo->prepare($sql2);
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
<p>数据范围：专服1服、专服2服、专服3服</p>
<p>数据内容：充值大于等于2000元的玩家服务器和角色名
</p>
<table>
    <thead>
    <tr>
        <th>账号</th>
        <th>角色名</th>
        <th>充值总金额</th>
        <th>区服</th>
        <th>最后登录时间</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($data as $d):?>
        <tr>
            <td><?=$d['accountid']?></td>
            <td><?=$d['name']?></td>
            <td><?=$pay[$d['accountid']]?></td>
            <td>[<?=$d['serverid']?>]<?=$serversList[$d['serverid']]?></td>
            <td><?=date('Y年m月d日', strtotime($d['logintime']));?></td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>
</body>
</html>

