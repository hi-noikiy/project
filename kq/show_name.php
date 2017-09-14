<?php

include_once('common.inc.php');
ini_set('date.timezone','Asia/Shanghai');

$table = 'game_name';
$sql = "select * from $table order by id asc";
$list = $webdb->getList($sql);
$ip = getIp();
//var_dump($ip);
$action = $_REQUEST['action'];
if($action == 'vote'){
	$id = intval($_REQUEST['id']);
	$sql = "select id from $table where id='$id'";
	if(!$webdb->getValue($sql, 'id')){
		echo"<script>alert('投票名称不存在！');history.go(-1);</script>";
		exit();
	}
	
	$sql = "select id from game_name_vote where name_id=$id and ip='$ip'";
	if($webdb->getValue($sql)){
		echo"<script>alert('不能重复投票！');history.go(-1);</script>";
		exit();
	}
	$sql = "select count(id) as count from game_name_vote where ip='$ip'";
	if($webdb->getValue($sql, 'count') >= 3){
		echo"<script>alert('三次机会已用，不能再投票了！');history.go(-1);</script>";
		exit();
	}
	$addtime = date('Y-m-d H:i:s', time());
	$sql = "insert into game_name_vote(name_id, ip, addtime) values('$id', '$ip', '$addtime')";
	if($webdb->query($sql)){
		echo"<script>alert('操作成功！');history.go(-1);</script>";
		exit();
	}
	echo"<script>alert('操作失败！');history.go(-1);</script>";
}
function getIp(){
	$ip = ($_SERVER["HTTP_VIA"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER["REMOTE_ADDR"];
	$ip = ($ip) ? $ip : $_SERVER["REMOTE_ADDR"];
	return $ip;
}


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>列表</title>
<style>
* { margin:0; padding:0;}
table { border-collapse:collapse; width:800px; line-height:28px; font-size:14px;}
th, tr,td { border:1px solid #999;}
td { padding:1px 2px;}
</style>
<script type="text/javascript">
function vote(id){
	if(confirm(" 您确定要投票给他吗？")){
		window.location.href="show_name.php?action=vote&id="+id;
	 }
}
</script>
</head>
<body>
	<div>
		<table cellspacing="0" cellpadding="0" class="Admin_L">
			<tr>
				<th width="150">名称</th>
				<th width="500">描述</th>
				<th width="100">真实姓名</th>
				<th width="100">投票次数</th>
				<th width="100">操作</th>
			</tr>
			<?php foreach ($list as $v) {?>
    		<tr>
				<td><?=$v['name']?></td>
				<td><?=$v['remark']?></td>
				<td>****<? //=$v['real_name']?></td>
				<td>
					<?php 
						$sql = "select count(id) as count from game_name_vote where name_id={$v['id']}";
						$rs = $webdb->getValue($sql, 'count');
						echo $rs;
					?>
				</td>
				<td>
					<?php 
						$sql="select id from game_name_vote where ip='$ip' and name_id='{$v['id']}'";
						if(!$webdb->getValue($sql, 'id')){
					?>
							<span style="cursor:pointer;" onclick="vote(<?=$v['id']?>)">投票</span></td>
					<?php 	
						} else 
							echo '<span>已投票</span>';
					?>
			</tr>
    		<?php } ?>
  		</table>	
	</div>
	<p style="margin-top:20px;">每个人有三次的投票机会，不能重复投票。</p>
</body>
</html>