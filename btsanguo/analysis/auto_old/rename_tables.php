<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 8/14/14
 * Time: 4:53 PM
 * 重命名表，新建表，没3个月执行一次
 */
include 'path.php';
$db_source   = db('gamedata');
$showTables  = "SHOW TABLES";
$t1 = $db_source->prepare($showTables);
$t1->execute();
$tables = $t1->fetchAll(PDO::FETCH_COLUMN);
$date = date('Ymd');
//print_r($tables);
//exit;
foreach( $tables as $table ) {
    //获取表结构
    $creSql = "show create table `$table`";
    $stmt = $db_source->prepare($creSql);
    $stmt->execute();
    $tableArr = $stmt->fetch(PDO::FETCH_NUM);
    // print_r($tableArr);
    $tableName = $tableArr['0'];
    $sqlSource = $tableArr['1'];
    echo $sqlSource . "<br/>";
    //重命名旧表
    $db_source->exec("RENAME TABLE `$table` TO {$table}_{$date}");
    //创建新表
    $db_source->exec($sqlSource);
}
writeLog('OK|Rename Table AND Recreate Table Finish', LOG_PATH.'/rename_tables.log');