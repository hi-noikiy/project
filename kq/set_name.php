<?php
include_once('common.inc.php');
ini_set('date.timezone','Asia/Shanghai');
$action = $_POST['action'];
if($action == 'add'){
	$table = 'game_name';
	$name = $_POST['name'];
	$realName = $_POST['real_name'];
	$remark = $_POST['remark'];
	$addtime = date('Y-m-d H:i:s', time());
	
	if(empty($name)){
		echo"<script>alert('时尚名称不能为空！');history.go(-1);</script>";
		exit();
	}
	if(empty($realName)){
		echo"<script>alert('真实姓名不能为空！');history.go(-1);</script>";
		exit();
	}
	$sql = "select id from $table where name='$name'";
	if($webdb->getValue($sql)){
		echo"<script>alert('改名称已被人命名了！');history.go(-1);</script>";
		exit();
	}
	
	$sql = "insert into $table(name, real_name, remark, addtime) values('$name', '$realName', '$remark', '$addtime')";
	if($webdb->query($sql)){
		echo"<script>alert('操作成功！');history.go(-1);</script>";
		exit();
	}
		
	echo"<script>alert('操作失败！');history.go(-1);</script>";
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Insert title here</title>
</head>
<body>
	<div>
		<form action="<?=$rooturl.'set_name.php'?>" method="post">
			<p>时尚名称：<input name="name" type="text" value=""></p>
			<p>真实姓名：<input name="real_name" type="text" value=""></p>
			<p>想法描述：<textarea name="remark" cols="38" rows="5"></textarea></p>
			
			<p><button type="submit" name="action" value="add">添加</button></p>
		</form>
	</div>
	
</body>
</html>
