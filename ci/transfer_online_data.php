<?php
/**
 * Created by PhpStorm.
 * User: Guangpeng Chen
 * Date: 16/3/20
 * Time: 20:47
 *
 * 定时从4.0的数据库读取online表的数据到5.+的数据库里面
 *
 */
if (PHP_SAPI != 'cli') exit('AAA');
//,
$appid_list = array('10001','10002');
$fileds = array(
    'servername',
    'online',
    'MaxOnline',
    'WorldOnline',
    'WorldMaxOnline',
    'daytime',
    'gameid',
    'serverid',
    'appid',
    'remote_id',
);
$link_id_5 = db('local');
$db = array();
foreach ($appid_list as $appid) {
    $sql_max_id = "SELECT max(remote_id) as id FROM online where appid='$appid'";
    $q          = mysql_query($sql_max_id, $link_id_5);
    $res        = mysql_fetch_assoc($q);
    var_dump($res);
    $max_id = $res['id'] ? $res['id'] : 0;
    echo "Appid:".$appid ,';max_id=', $max_id,PHP_EOL;
    //exit;
    $db[$appid] = db($appid);
    $sql2 = "SELECT * FROM online WHERE id>$max_id LIMIT 1000";
    echo $sql2,"\n";
    $Query_id = mysql_query($sql2,$db[$appid]);
    var_dump(mysql_error());
    if (!$Query_id) {
        exit('Error');
    }

    $vals  ='';
    while ($_row = mysql_fetch_assoc($Query_id) ) {
        $server_name = iconv('gb2312', 'utf-8', $_row['servername']);
        $vals .= "('$server_name',{$_row['online']}, {$_row['MaxOnline']},{$_row['WorldOnline']},{$_row['WorldMaxOnline']},{$_row['daytime']},{$_row['gameid']},{$_row['serverid']},'$appid', {$_row['id']}),";
    }
    mysql_close($db[$appid]);
    if (empty($vals)) continue;
    $vals = rtrim($vals, ',');
    $insert = "INSERT INTO online(".implode(',', $fileds).") VALUES $vals";
    echo $insert;
    mysql_query($insert, $link_id_5);
}
mysql_close($link_id_5);

function db($dbName)
{
    $db_conf = array(
        'local'=>array(
            'host'=>'127.0.0.1',
            'user'=>'root',
            'pwd'=> 'u591,hainiu*',
            'db' => 'sdk',
        ),
        '10001'=>array(
            'host'=>'10.9.59.116:3316',
            'user'=>'totaluser',
            'pwd'=> 'rdg,80rt.yuyjj464j',
            'db' => 'gundata',
        ),
        '10002'=>array(
            'host'=>'123.59.144.183:3316',
            'user'=>'totaluser',
            'pwd'=> 'rdg,80rt.yuyjj464j',
            'db' => 'gundata',
        ),
    );
    $link_id =mysql_connect($db_conf[$dbName]['host'], $db_conf[$dbName]['user'],$db_conf[$dbName]['pwd']);
    mysql_select_db($db_conf[$dbName]['db']);
    mysql_query("SET NAMES 'utf8'",$link_id);
    return $link_id;
}

exit;

$host_40 = '10.9.59.116:3316';
$user_40 = 'totaluser';
$pwd_40  = 'rdg,80rt.yuyjj464j';
$link_id =mysql_connect($host_40, $user_40, $pwd_40);
var_dump($link_id);
mysql_select_db('gundata');
echo 'connect';

$sql2 = "SELECT * FROM online WHERE id>$max_id LIMIT 1000";
echo $sql2,"\n";
$Query_id = mysql_query($sql2,$link_id);
var_dump(mysql_error());
if (!$Query_id) {
    exit('Error');
}

$vals  ='';
while ($_row = mysql_fetch_assoc($Query_id) ) {
    $server_name = iconv('gb2312', 'utf-8', $_row['servername']);
    $vals .= "('$server_name',{$_row['online']}, {$_row['MaxOnline']},{$_row['WorldOnline']},{$_row['WorldMaxOnline']},{$_row['daytime']},{$_row['gameid']},{$_row['serverid']},'{$_row['appid']}'),";
}
mysql_close($link_id);
//echo $insert;
if (empty($vals)) exit;
$vals = rtrim($vals, ',');
$insert = "INSERT INTO online(".implode(',', $fileds).") VALUES $vals";
echo $insert;
mysql_query($insert, $link_id_5);
mysql_close($link_id_5);
