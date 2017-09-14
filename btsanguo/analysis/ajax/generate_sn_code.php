<?php
define('ROOT_PATH', str_replace('analysis/ajax/generate_sn_code.php', '', str_replace('\\', '/', __FILE__)));
define('A_ROOT', ROOT_PATH.'analysis/');
set_time_limit(0);
include A_ROOT.'config/config.php';
include A_ROOT.'inc/func.inc.php';

set_time_limit(60000);
ini_set('memory_limit', '1024M');
$generate_limit = 10000;
$game_type = intval($_POST['game_id']);
$time_stamp = date('ymdHi');
// echo $time_stamp;exit;
$sn_base_codes = array (
    'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H',
    'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P',
    'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X',
    'Y', 'Z', 'a', 'b', 'c', 'd', 'e', 'f',
    'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n',
    'o', 'p', 'q', 'r', 's', 't', 'u', 'v',
    'w', 'x', 'y', 'z', '0', '1', '2', '3',
    '4', '5', '6', '7', '8', '9'
);

/**
 * 1、批次
 * 2、每个批次 单个玩家只能用一次
 * 2000 开头是 龙威  3000开头是圣纹  4000开头是法则5000开头是 三国
 */
$db = db('analysis');
if (isset($_POST['action']) && $_POST['action']=='UPDATE_USED_TYPE') {
    $old_used_type = intval($_POST['old_used_type']);
    $new_used_type = intval($_POST['new_used_type']);
    if (!$new_used_type || !$old_used_type) {
        echo '{"status":"fail","msg":"批次号不能为空"}';
        exit;
    }
    $sql = "UPDATE u_code_exchange SET used_type=? WHERE used_type=?";
    $stmt = $db->prepare($sql);
    if ($stmt->execute(array($new_used_type, $old_used_type))) {
        exit('{"status":"ok","msg":"更新成功"}');
    }
    exit('{"status":"fail","msg":"更新失败"}');
}
$sqlGetMaxUsedType = "SELECT MAX(used_type) as used_type from u_code_exchange where game_type=$game_type";
$stmt = $db->prepare($sqlGetMaxUsedType);
$stmt->execute();
$used_type = $stmt->fetchColumn();
if (!$used_type) {
    $used_type = $game_type * 1000;
}
else {
    $used_type = $used_type + 1;
}
$nums = 0;
$sql = "INSERT INTO `u_code_exchange`(`code_id`, `type`,`param`, `time_stamp`, `time_limit`, `game_type`, `register_type`, `register_time`, `used_type`) VALUES";
foreach($_POST['codes'] as $code) {
    $cnt = intval($code['sn_nums']);
    if ($cnt> $generate_limit) {
        exit('{"status":"fail","msg":"生成数量超出"}');
    }
    $time_limit = $code['time_limit']!=0 ? strtotime($code['time_limit']) : 0;
    $register_type = intval($code['register_type']);
    $register_time = 0;
    if ($register_type) {
        $register_time = date('Ymd', strtotime($code['register_time']));
    }
    $param = trim($code['param']);
    $sqlMain = "SELECT id FROM s_code WHERE item_id='$param' AND createtime=$time_stamp LIMIT 1";
    $stmt = $db->prepare($sqlMain);
    $stmt->execute();
    if (!$stmt->fetchColumn()) {
        $itemName = trim($code['item_name']);
        $sqlInsert = "INSERT INTO s_code(item_id,item_name,createtime,endtime) VALUES('$param','$itemName',$time_stamp, $time_limit)";
        $db->exec($sqlInsert);
    }
    $len = count($sn_base_codes)-1;
    $snCodes = array();
    for ($k=0; $k<$cnt; $k++) {
        $arr = array();
        for($i=0; $i<16; $i++) {
            $arr[] = $sn_base_codes[mt_rand(0, $len)].mt_rand(0,99).microtime();
        }
        $snCodes[] = substr(md5(implode('', $arr)), 0, 16);
    }
    $values = '';
    foreach ($snCodes as $c) {
        $values .= "('$c', 1, '$param', $time_stamp, $time_limit, $game_type, $register_type, $register_time, $used_type),";
    }
//    echo $sql.rtrim($values,',');
    if ($db->exec($sql.rtrim($values,','))!==false) {
        $nums += $cnt;
    }
}
//$values = rtrim($values, ',');
//$nums   =  $db->exec($sql.$values);
if ($nums>0) {
    echo '{"status":"ok","msg":"成功生成了'.$nums.'个激活码！","stype":'.$used_type.'}';
}
else {
    echo '{"status":"fail","msg":"生成失败，联系技术人员吧。错误信息：'.$db->errorInfo().'"}';
}
$db = null;
unset($db);
?>  