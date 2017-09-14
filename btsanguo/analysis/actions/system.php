<?php
/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 14-6-15
 * Time: 下午5:17
 */
include '../config/config.php';

$controller = $_REQUEST['c'];
$action = $_REQUEST['a'];
if (!class_exists($controller)) {
    $cls = new $controller(db('analysis'));
}
//$system = new System(db('analysis'));

if ($_POST['token']!=$_SESSION['token']) {
    exit;
}
if (!$action) {
    exit;
}
$ret = $cls->$action();
echo json_encode($ret);
