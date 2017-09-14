<?php
header("Content-type: text/html; charset=utf-8");
$redis = new redis();
$redis->connect('192.168.10.239', 6379);
$keyWithUserPrefix = $redis->keys('zmaxfilmCacheName123_*');


foreach ($keyWithUserPrefix as $key => $value) {
	echo '键名:' . $value;
	echo '值:' . $redis->get($value);
	echo '<br />==============================================================<br />';
	# code...
}

echo $redis->dbSize();

?>