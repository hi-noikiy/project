<?php 
$con = mysqli_connect("localhost","root","root",'redpeck');
$sql ="select e.id,e.name ename,d.name dname,isSpecial from employ e,department d where e.depId=d.id order by depId"; //SQL语句
$result = mysqli_query($con,$sql);
$data= array();
?>
<!DOCTYPE html>
<html>
<head>
<style>
</style>
</head>
<a target="_blank" href='add.php'>新增</a>
<body>
<table>
<tr>
  <th>编号</th><th>名称</th> <th>部门</th><th>是否额外抽奖</th><th>操作</th>
 </tr>
<?php 
$i = 1;
while ($row=mysqli_fetch_assoc($result))
{
	echo '<tr>'.
			'<td>'.$i++.'</td>'.
		'<td>'.$row['ename'].'</td>'.
		'<td>'.$row['dname'].'</td>'.
		'<td><input type="radio" value="1" name='.$row['id'].' '.($row['isSpecial']?'checked':'').'/>是'.
		'<input type="radio" value="0" name='.$row['id'].' '.(!$row['isSpecial']?'checked':'').'/>否'.'</td>'.
		'<td><a href="javascript:;" onclick="del('.$row['id'].')">删除</a></td>'.
		'</tr>';
}
?>
</table>
</body>
<script src='js/jquery.min.js'></script>
<script type="text/javascript">
function del(id){
	$.get('/core/operate.php',{id:id,act:'del'},function(json){
		location.reload();
	});
}
$('input[type="radio"]').change(function(){
	var id=$(this).attr('name');
	var val=$(this).val();
	$.get('/core/operate.php',{id:id,act:'edit',val:val},function(json){
		//location.reload();
	});
});
</script>
</html>
