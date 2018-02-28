<?php
error_reporting(0);
ini_set('memory_limit', '1024M');
$act = $_GET['act'];
session_start();
if($act){
	$act();
}
/**
 * 获取中奖者信息
 */
function getinfo(){
	$nper = $_SESSION['nper'];
	try{
		$con = mysqli_connect("localhost","root","root",'redpeck');
		if (!$con)
		{
			die('Could not connect: ' . mysqli_error());
		}
		$config = include dirname(__FILE__).'/../config/config.php';
		$querysql = "SELECT redlevel,GROUP_CONCAT(CONCAT('(',department,') ',name)) info FROM `redpack` where nper='$nper' GROUP BY redlevel desc";
		$result = mysqli_query($con,$querysql);
		$data = array();
		while($row=@mysqli_fetch_assoc($result)){
			$data[$row['redlevel']] = $config[$row['redlevel']].'获得者：'.$row['info'];
		}
		// 释放资源
		$result->close();
		// 关闭连接
		$con->close();
		if(!$data){
			$resultencode['code'] = 1;
			$resultencode['data'] = '';
		}else{
			$resultencode['code'] = 0;
			$resultencode['data'] = $data;
		}
		echo json_encode($resultencode);
		// some code
	}catch (Exception $e){}
}
/**
 * 写入中奖者信息
 */
function setWinner(){
	$nper = $_SESSION['nper'];
	$config = include dirname(__FILE__).'/../config/config.php';
	try{
		$con = mysqli_connect("localhost","root","root",'redpeck');
		if (!$con)
		{
			die('Could not connect: ' . mysqli_error());
		}
		$eid = $_GET['eid'];
		$redlevel = $_GET['type'];
		$sql ="select e.id,e.name ename,d.name dname from employ e,department d where e.depId=d.id and e.id=$eid"; //SQL语句
		$result = mysqli_query($con,$sql);
		$data[]=mysqli_fetch_assoc($result);
		$result->close();
		$time = time();
		$insertsql = "insert into redpack(name,department,redlevel,time,isGetStatus,prize,employid,nper)values";
		foreach ($data as $v){
			$insertsql .= "('{$v['ename']}','{$v['dname']}',{$redlevel},{$time},0,'{$prizename}',{$v['id']},'$nper'),";
		}
		$insertsql = rtrim($insertsql, ',');
		$result2 = mysqli_query($con,$insertsql);
		// 关闭连接
		$con->close();
		$resultencode['code'] = 0;
		$resultencode['data'] = $data;
		echo json_encode($resultencode);
		// some code
	}catch (Exception $e){}
}
function getemploy(){
	$nper = $_SESSION['nper'];
	try{
		$con = mysqli_connect("localhost","root","root",'redpeck');
		if (!$con)
		{
			die('Could not connect: ' . mysqli_error());
		}
		$config = include dirname(__FILE__).'/../config/config.php';
		$querysql = 'select count(*) c from redpack';
		$result1 = mysqli_query($con,$querysql);
		$row=mysqli_fetch_assoc($result1);
		$result1->close();
		$redlevel = $_GET['type'];
		$sql ="select e.id,e.name ename,d.name dname from employ e,department d where e.depId=d.id"; //SQL语句
		if($redlevel!='0'){
			$sql .= " and e.id not in(select employid as id from redpack where redlevel!='0' and nper='$nper' )";
		}else{
			$sql .= " and isSpecial=1 and e.id not in(select employid as id from redpack where redlevel='0' and nper='$nper' )";
		}
		$result = mysqli_query($con,$sql);
		$data= array();
		while ($row=mysqli_fetch_assoc($result))
		{
			$data[] = $row;
		}
		// 释放资源
		$result->close();
		// 关闭连接
		$con->close();
		if(!$data){
			$resultencode['code'] = 1;
			$resultencode['data'] = '员工数据获取失败';
		}else{
			$resultencode['code'] = 0;
			$resultencode['data'] = $data;
		}
		echo json_encode($resultencode);
		// some code
	}catch (Exception $e){}
	}
	/**
	 * 抽奖
	 */
	function start(){
		$nper = $_SESSION['nper'];
		$config = include dirname(__FILE__).'/../config/config.php'; 
		try{
			$con = mysqli_connect("localhost","root","root",'redpeck');
			if (!$con)
			{
				die('Could not connect: ' . mysqli_error());
			}
			$redlevel = $_GET['type'];
			$sql ="select e.id,e.name ename,d.name dname from employ e,department d where e.depId=d.id"; //SQL语句
			if($redlevel!='0'){
				$sql .= " and e.id not in(select employid as id from redpack where redlevel!='0' and nper='$nper' )";
			}else{
				$sql .= " and isSpecial=1 and e.id not in(select employid as id from redpack where redlevel='0' and nper='$nper')";
			}
			$result = mysqli_query($con,$sql);
			$data= array();
			$datas= array();
			$datass= array();
			while ($row=mysqli_fetch_assoc($result))
			{
				$row['levelname'] = $config[$redlevel];
				$datas[] = $row;
			}
			$datass[] = $datas[rand(0,count($datas)-1)];
			$datass[] = $datas[rand(0,count($datas)-1)];
			$datass[] = $datas[rand(0,count($datas)-1)];
			$datass[] = $datas[rand(0,count($datas)-1)];
			$datass[] = $datas[rand(0,count($datas)-1)];
			$data[] = $datass[rand(0,count($datass)-1)];
			$result->close();
			$time = time();
			$insertsql = "insert into redpack(name,department,redlevel,time,isGetStatus,prize,employid,nper)values";
			foreach ($data as $v){
				$insertsql .= "('{$v['ename']}','{$v['dname']}',{$redlevel},{$time},0,'{$prizename}',{$v['id']},'$nper'),";
			}
			$insertsql = rtrim($insertsql, ',');
			$result2 = mysqli_query($con,$insertsql);
			// 关闭连接
			$con->close();
			$resultencode['code'] = 0;
			$resultencode['data'] = $data;
			echo json_encode($resultencode);
			// some code
		}catch (Exception $e){}
	}

	/**
	 * 候选名单获取中奖者
	 */
	function start_end(){
		$nper = $_SESSION['nper'];
		$config = include dirname(__FILE__).'/../config/config.php';
		try{
			$con = mysqli_connect("localhost","root","root",'redpeck');
			if (!$con)
			{
				die('Could not connect: ' . mysqli_error());
			}
			$redlevel = $_GET['type'];
			$sql ="select e.id,e.name ename,d.name dname from employ e,department d where e.depId=d.id"; //SQL语句
			if($redlevel!='0'){
				$sql .= " and e.id not in(select employid as id from redpack where redlevel!='0' and nper='$nper' )";
			}else{
				$sql .= " and isSpecial=1 and e.id not in(select employid as id from redpack where redlevel='0' and nper='$nper')";
			}
			$result = mysqli_query($con,$sql);
			$data= array();
			$datas= array();
			$datass= array();
			while ($row=mysqli_fetch_assoc($result))
			{
				$row['levelname'] = $config[$redlevel];
				$datas[] = $row;
			}
			$datass[] = $datas[rand(0,count($datas)-1)];
			$datass[] = $datas[rand(0,count($datas)-1)];
			$datass[] = $datas[rand(0,count($datas)-1)];
			$datass[] = $datas[rand(0,count($datas)-1)];
			$datass[] = $datas[rand(0,count($datas)-1)];
			$data[] = $datass[rand(0,count($datass)-1)];
			$result->close();
			$time = time();
			$insertsql = "insert into redpack(name,department,redlevel,time,isGetStatus,prize,employid,nper)values";
			foreach ($data as $v){
				$insertsql .= "('{$v['ename']}','{$v['dname']}',{$redlevel},{$time},0,'{$prizename}',{$v['id']},'$nper'),";
			}
			$insertsql = rtrim($insertsql, ',');
			$result2 = mysqli_query($con,$insertsql);
			// 关闭连接
			$con->close();
			$resultencode['code'] = 0;
			$resultencode['data'] = $data;
			echo json_encode($resultencode);
			// some code
		}catch (Exception $e){}
	}
	/**
	 * 获取候选名额
	 */
	function start_pre(){
		$nper = $_SESSION['nper'];
		$config = include dirname(__FILE__).'/../config/config.php';
		try{
			$con = mysqli_connect("localhost","root","root",'redpeck');
			if (!$con)
			{
				die('Could not connect: ' . mysqli_error());
			}
			$redlevel = $_GET['type'];
			$sql ="select e.id,e.name ename,d.name dname from employ e,department d where e.depId=d.id"; //SQL语句
			if($redlevel!='0'){
				$sql .= " and e.id not in(select employid as id from redpack where redlevel!='0' and nper='$nper' )";
			}else{
				$sql .= " and isSpecial=1 and e.id not in(select employid as id from redpack where redlevel='0' and nper='$nper')";
			}
			$result = mysqli_query($con,$sql);
			$datas= array();
			$datass= array();
			while ($row=mysqli_fetch_assoc($result))
			{
				$row['levelname'] = $config[$redlevel];
				$datas[] = $row;
			}
			$datass[] = $datas[rand(0,count($datas)-1)];
			$datass[] = $datas[rand(0,count($datas)-1)];
			$datass[] = $datas[rand(0,count($datas)-1)];
			$datass[] = $datas[rand(0,count($datas)-1)];
			$datass[] = $datas[rand(0,count($datas)-1)];
			$_SESSION['datass'] = $datass;
			$result->close();
			$resultencode['code'] = 0;
			$resultencode['data'] = $datass;
			echo json_encode($resultencode);
		}catch (Exception $e){}
	}

