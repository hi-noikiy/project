<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-7-8
 * Time: 下午4:13
 */
error_reporting(0);
set_time_limit(6000);
ini_set ('memory_limit', '1024M');

include 'config/config.php';
include 'inc/files.inc.php';
//数据库连接
$db_sum  = db('analysis');
//登陆判断
if (System::UserLoginCheck()===false) {
    header('location:login.php');
    exit;
}
//页面权限判断
$filename = $_SERVER['SCRIPT_NAME'];
$filename = substr($filename, strrpos ($filename,'/')+1);
$fileLev  = System::UserRightsChk($db_sum, $filename);
$urightsId = $_SESSION['urights']!=='all' ? explode(',', $_SESSION['urights']) : 'all';
if ($fileLev===false) {
    header("Content-type: text/html; charset=utf-8");
    exit('您没有权限访问此页面！');
}
if (!empty($_GET)) {
    $where = '1=1';
    if (!empty($_GET['bt'])) {
        $bt1 = date('ymd0000', strtotime($_GET['bt']));
        $bt2 = date('ymd2359', strtotime($_GET['bt']));
        $where .= " AND time_stamp>$bt1 and time_stamp<$bt2";
    }
    if (!empty($_GET['et'])) {
        $tm = strtotime($_GET['et']);
        $where .= " AND time_limit=$tm";
    }
    if ($_GET['used']>-1) {
        $where .= " AND used=".intval($_GET['used']);
    }
    if ($_GET['used_type']) {
        $used_type = intval($_GET['used_type']);
        $where .= " AND used_type=$used_type";
    }
    if ($_GET['param']) {
            $where .= " AND param=".trim($_GET['param']);
    }
    $sql_param = "SELECT param from u_code_exchange WHERE " . $where ." LIMIT 1";
    $stmt = $db_sum->prepare($sql_param);
    $stmt->execute();
    $param = $stmt->fetchColumn(0);


    $sql = "SELECT code_id,param FROM u_code_exchange WHERE " . $where;
//    echo $sql;exit;
    $stmt = $db_sum->prepare($sql);
    $stmt->execute();
    $codes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header("Content-type: application/octet-stream");

    $file = "激活码_{$param}_{$used_type}.txt";
    $filename = basename($file);

    //处理中文文件名
    $ua = $_SERVER["HTTP_USER_AGENT"];
    $encoded_filename = rawurlencode($filename);
    if (preg_match("/MSIE/", $ua)) {
        header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
    } else if (preg_match("/Firefox/", $ua)) {
        header("Content-Disposition: attachment; filename*=\"utf8''" . $filename . '"');
    } else {
        header('Content-Disposition: attachment; filename="' . $filename . '"');
    }
    //让Xsendfile发送文件
    header("X-Sendfile: $file");
    foreach ($codes as $code) {
        echo $code['code_id']. PHP_EOL;
    }
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>礼包激活码导出</title>
</head>
<body>
<table>
    <?php foreach($codes as $code):?>
        <tr>
            <td><?=$code['code_id']?></td>
        </tr>
    <?php endforeach;?>
</table>
</body>
</html>
