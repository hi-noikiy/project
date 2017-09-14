<?php
include 'common.inc.php';
$class = new product();
$list=$webdb->getList("select id from _web_product");
foreach ($list as $rs){
	echo $rs["id"]."<br />";
}
?>