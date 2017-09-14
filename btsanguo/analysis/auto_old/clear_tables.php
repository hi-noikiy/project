<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-5-28
 * Time: 上午10:05
 */
include 'path.php';
$db_sum    = db('u591_new');
$tablesSql = "SHOW TABLES";
$stmt = $db_sum->prepare($tablesSql);
$stmt->execute();
$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

foreach($tables as $table) {
    if (strpos($table, 'sum_')!==false) {
        echo "TRUNCATE TABLE " . $table . " runing..." . PHP_EOL;
        $clear = "TRUNCATE TABLE {$table}";
        if($db_sum->exec($clear)!==false){
            echo "TRUNCATE TABLE [" . $table ."] OK". PHP_EOL;
        }
    }

}