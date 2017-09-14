<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-7-4
 * Time: 下午4:32
 */
include '../inc/servers.php';
$data = array();
if (!empty($_GET['date1']) && !empty($_GET['date2'])){
    $dsn = "mysql:dbname=u591;host=localhost;port=3306";
    $pdo = new PDO($dsn, 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    set_time_limit(0);
    $pdo->exec("SET NAMES 'utf8';");
//if($port!==3316 && !is_numeric($dbTypeId)) {
//    $pdo->exec("SET NAMES 'utf8';");
//}
//$sql = "select DISTINCT PayID from pay_log where Add_Time>='2014-06-19 00:00:00' AND Add_Time<='2014-06-19 23:59:59'";
    $date1 = $_GET['date1'] .' '. $_GET['date1_1'].':00';
    $date2 = $_GET['date2'] .' '. $_GET['date2_1'].':59';
//    echo $date1;
//    exit;
//    $sql = "select DISTINCT PayID from pay_log where Add_Time>='{$date1} 00:00:00' AND Add_Time<='{$date2} 23:59:59' GROUP BY PayID";
    $sql = "select DISTINCT PayID from pay_log where Add_Time>='{$date1}' AND Add_Time<='{$date2}'";
    echo $sql;
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
   // print_r($ids);
    $idstr = implode(',', $ids);
//    echo $idstr;
//    exit;
// and l.serverid=p.serverid
    $dsn2 = "mysql:dbname=kdgamedata;host=localhost;port=3316";
    $pdo2 = new PDO($dsn2, 'root', 't,i7.8fg6sh,5i');
    $sql2 = <<<SQL
SELECT p.name,MAX(logintime) as logintime, p.serverid,l.accountid FROM player p LEFT JOIN loginmac l ON l.accountid=p.accountid
where l.accountid IN($idstr)
group by l.accountid
ORDER BY l.accountid asc
SQL;

//$sql2 = "SELECT COUNT(DISTINCT accountid) as cnt,MAX(logintime) FROM loginmac WHERE accountid IN(".implode(',', $ids).") AND logintime>20140615 GROUP BY logintime";
    $stmt = $pdo2->prepare($sql2);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
//print_r( $stmt->fetchAll(PDO::FETCH_ASSOC) );
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>XNM</title>
    <style>
        table{border-spacing: 0;width: 90%;}
        td,th{border: 1px solid #000; padding:4px;}
    </style>
</head>
<body>
<p>

<form action="#" method="get">
    时间：<input type="date" name="date1" id=""/><input type="time" name="date1_1" id=""/>
    至<input type="date" name="date2"id=""/><input type="time" name="date2_1"id=""/>
    <input type="submit" value="提交"/>
</form>
</p>
<table>
    <thead>
    <tr>
        <th>账号</th>
        <th>角色名</th>
        <th>区服</th>
        <th>最后登录时间</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($data as $d):?>
        <tr>
            <td><?=$d['accountid']?></td>
            <td><?=iconv('gbk', 'utf-8', $d['name'])?></td>
            <td>[<?=$d['serverid']?>]<?=$serversList[$d['serverid']]?></td>
            <td><?=date('Y年m月d日', strtotime($d['logintime']));?></td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>
</body>
</html>

