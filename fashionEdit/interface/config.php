<?php
date_default_timezone_set('Etc/GMT-8');
function setConn() {
	return mysqli_connect('127.0.0.1','root','root', 'shishang');
}
function write_log($dirName, $logName, $str) {
	$path_name = $dirName . "/" . date("ym");
	if (!is_dir($path_name))
		@mkdir ( $path_name, 0777);
	$fs = fopen ( $path_name . "/" . $logName . date ("ymd") . ".txt", "a" );
	fwrite ( $fs, $str );
	fclose ( $fs );
}
function return_json($data){
	return json_encode($data,JSON_UNESCAPED_UNICODE);
}
?>
