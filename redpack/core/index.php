<?php
error_reporting(0);
$act = $_GET['act'];
if($act){
	$act();
}
function getemploy(){
	try{
		$con = mysqli_connect("localhost","root","root",'redpeck');
		if (!$con)
		{
			die('Could not connect: ' . mysqli_error());
		}
		$querysql = 'select count(*) c from redpack';
		$result1 = mysqli_query($con,$querysql);
		$row=mysqli_fetch_assoc($result1);
		$result1->close();
		if($row['c']<6){
			$redlevel = 3;
		}elseif($row['c']<10){
			$redlevel = 2;
		}elseif($row['c']<12){
			$redlevel = 1;
		}elseif($row['c']<22){
			$redlevel = 4; //特等奖
		}else{
			$resultencode['code'] = 1;
			$resultencode['data'] = '抽奖已结束';
			echo json_encode($resultencode);die;
		}
		//mysqli_query("set names 'utf8'"); //数据库输出编码 应该与你的数据库编码保持一致.南昌网站建设公司百恒网络PHP工程师建议用UTF-8 国际标准编码.
		$sql ="select e.name ename,d.name dname from employ e,department d where e.depId=d.id"; //SQL语句
		if($redlevel == 4){
			$sql .= ' and isSpecial=1';
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
		$resultencode['code'] = 0;
		$resultencode['data'] = $data;
		echo json_encode($resultencode);
		// some code
	}catch (Exception $e){}
	}
	/**
	 * 抽奖
	 */
	function start(){
		//echo dirname(__FILE__).'/../config/config.php';die;
		$config = include dirname(__FILE__).'/../config/config.php'; 
		try{
			$con = mysqli_connect("localhost","root","root",'redpeck');
			if (!$con)
			{
				die('Could not connect: ' . mysqli_error());
			}
			$querysql = 'select count(*) c from redpack';
			$result1 = mysqli_query($con,$querysql);
			$row=mysqli_fetch_assoc($result1);
			$result1->close();
			if($row['c']<6){
				$redlevel = 3;
				$prizeid = $row['c'];
			}elseif($row['c']<10){
				$redlevel = 2;
				$prizeid = $row['c']-6;
			}elseif($row['c']<12){
				$redlevel = 1;
				$prizeid = $row['c']-10;
			}elseif($row['c']<22){
				$redlevel = 4; //特等奖
				$prizeid = $row['c']-12;
			}else{
				$resultencode['code'] = 1;
				$resultencode['data'] = '抽奖已结束';
				echo json_encode($resultencode);die;
			}
			$prizename = $config[$redlevel]['prize'][$prizeid];
			$sql ="select e.id,e.name ename,d.name dname from employ e,department d where e.depId=d.id"; //SQL语句
			if($redlevel!=4){
				$sql .= ' and e.id not in(select employid as id from redpack where redlevel!=4)';
			}else{
				$sql .= ' and isSpecial=1 and e.id not in(select employid as id from redpack where redlevel=4)';
			}
			$result = mysqli_query($con,$sql);
			$data= array();
			$datas= array();
			while ($row=mysqli_fetch_assoc($result))
			{
				$row['levelname'] = $config[$redlevel]['name'];
				$row['prizename'] = $prizename;
				$row['curid'] = $prizeid+1;
				$datas[] = $row;
			}
			$data[] = $datas[rand(0,count($datas)-1)];
			$result->close();
			//print_r($data);die;
			$time = time();
			$insertsql = "insert into redpack(name,department,redlevel,time,isGetStatus,prize,employid)values";
			foreach ($data as $v){
				$insertsql .= "('{$v['ename']}','{$v['dname']}',{$redlevel},{$time},0,'{$prizename}',{$v['id']}),";
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


