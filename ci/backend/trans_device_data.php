<?php
/**
 * Created by PhpStorm.
 * User: Guangpeng Chen
 * Date: 8/7-007
 * Time: 10:59
 */
if (!isset($_GET['token']) && $_GET['token']!='u591_debug') {
    if (PHP_SAPI !='cli') exit('run error');
}
include 'common.php';
include BASEPATH . '/app/config/database.php';
$dsn = "mysql:dbname={$db['sdk']['database']};host={$db['sdk']['hostname']};port=3306";
$pdo = new PDO($dsn, $db['sdk']['username'],  $db['sdk']['password']);
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
set_time_limit(0);
$pdo->exec("SET NAMES 'utf8';");
$sql = "SELECT id FROM u_device_unique ORDER BY id DESC LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$max_id = $stmt->fetchColumn(0);

$sql_trans = "SELECT COUNT(*) as cnt FROM u_device_active WHERE id>$max_id";
$stmt = $pdo->prepare($sql_trans);
$stmt->execute();
$cnt = $stmt->fetchColumn(0);

if (!$cnt) exit('no data');
$query = "SELECT * FROM u_device_active WHERE id>$max_id GROUP BY mac,appid ORDER BY id ASC";
$stmt = $pdo->prepare($query);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($data as $item) {
    $save_data[$item['appid'].'_'.$item['mac']] = $item;
}

$sql_chk = "SELECT mac,appid FROM u_device_unique";


$sql_add = "INSERT INTO u_device_unique ( SELECT * FROM u_device_active WHERE id>$max_id GROUP BY mac,appid ORDER BY id ASC )";
echo $sql_add;
$ret = $pdo->exec($sql_add);
pft_log('backend_run', "sql:$sql_add;ret:$ret");