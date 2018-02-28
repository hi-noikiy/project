<?php
error_reporting(0);
$act = $_GET['act'];
if($act){
	$act();
}

/**
 * 添加
 */
function add(){
	$name = $_GET['name'];
	$depId = $_GET['depId'];
	$isSpecial = $_GET['isSpecial'];
	try{
		$con = mysqli_connect("localhost","root","root",'redpeck');
		if (!$con)
		{
			die('Could not connect: ' . mysqli_error());
		}
		$querysql = "insert into employ(name,depId,isSpecial) values('$name','$depId','$isSpecial')";
		mysqli_query($con,$querysql);
	}catch (Exception $e){}
}
/**
 * 删除
 */
function del(){
	$id = $_GET['id'];
	try{
		$con = mysqli_connect("localhost","root","root",'redpeck');
		if (!$con)
		{
			die('Could not connect: ' . mysqli_error());
		}
		$querysql = "delete from employ where id='$id'";
		mysqli_query($con,$querysql);
	}catch (Exception $e){}
}

/**
 * 修改
 */
function edit(){
	$id = $_GET['id'];
	$isSpecial = $_GET['val'];
	try{
		$con = mysqli_connect("localhost","root","root",'redpeck');
		if (!$con)
		{
			die('Could not connect: ' . mysqli_error());
		}
		$querysql = "update employ set isSpecial='$isSpecial' where id='$id'";
		mysqli_query($con,$querysql);
	}catch (Exception $e){}
}


