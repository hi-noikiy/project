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
$token = $_GET['token'];
$my_token = md5(md5('u591_sgkd'));//7fcc4a132689e5acc5830a45d732baf0
if ($token != $my_token) {
    exit('非常访问！');
}
//9月份新增注册用户累充500+用户的人数
if (!empty($_GET['bt']) && !empty($_GET['et'])){
    $bt = $_GET['bt'];
    $et = $_GET['et'];
    $px = $_GET['px'];
    $server_id1 = intval($_GET['server_id1']);
    $server_id2 = intval($_GET['server_id2']);
    $dsn = "mysql:dbname=u591;host=localhost;port=3306";
    $pdo = new PDO($dsn, 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES 'utf8';");
    if ($server_id1 && $server_id2) {
        $where = " AND ServerID >=$server_id1 AND ServerID <=$server_id2";
    }
    $sql = "select SUM(PayMoney) AS money,ServerID from pay_log "
	." where Add_Time > '{$bt} 00:00:00' AND Add_Time<='{$et} 25:59:59'"
        . $where
	." GROUP BY ServerID ORDER BY $px";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>国服各区服充值总额统计</title>
    <style>
        table{border-spacing: 0;width: 90%;}
        td,th{border: 1px solid #000; padding:4px;}
    </style>
</head>
<body>
<form action="#" method="get">
    <input name="token" type="hidden" value="<?=$token?>"/>
    <p>
        充值时间： <input type="date" name="bt" placeholder="Y-m-d格式" value="<?=$bt?>"/>
    ~
    <input type="date" name="et" placeholder="Y-m-d" value="<?=$et?>"/>
        区服ID： <input type="number"  value="<?=$_GET['server_id1']?>" name="server_id1" id=""/>~ <input type="number" name="server_id2" value="<?=$_GET['server_id2']?>" id=""/>
        排序: <select name="px" id="">
            <option value="money DESC">按充值总金额降序</option>
            <option value="money ASC">按充值总金额升序</option>
            <option value="ServerID DESC">按区服ID降序</option>
            <option value="ServerID ASC">按区服ID升序</option>
        </select>

        <input type="submit" value="提交"/>
    </p>
</form>
<table>
    <thead>
        <tr>
            <th>充值总金额</th>
            <th>区服ID</th>
        </tr>
    </thead>
    <tbody>
    <?foreach($data as $d):?>
        <tr>
            <td><?=$d['money']?></td>
            <td><?=$d['ServerID']?></td>
        </tr>
    <?endforeach;?>
    </tbody>
</table>
</body>
</html>