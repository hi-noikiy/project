<?php
include "../common.inc.php";
include_once 'section.class.php';
$id=$_POST["id"];
$class = new section();
$class->del($id);
?>