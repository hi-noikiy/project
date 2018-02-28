<!DOCTYPE html>
<html>
<head>
<style>
</style>
</head>
<body>
<table>
<tr>
<td>名称</td> <td><input type='text' id='name'/></td> 
 </tr>
 <tr>
<td>部门</td> <td><select id='depId'>
<option value="2">总办</option>
<option value="6">服务端</option>
<option value="7">策划部</option>
<option value="8">美术部</option>
<option value="9">QA部</option>
<option value="10">运营部</option>
<option value="11">客服部</option>
<option value="12">web部</option>
<option value="13">客户端</option>
<option value="14">市场部</option>
<option></option>
</select></td> 
 </tr>
 <tr>
<td>是否额外抽奖</td> <td><input type="radio" value="1"  name='isSpecial'/>是
		<input type="radio" value="0" name='isSpecial'  checked/>否</td>
 </tr>
 <tr>
<td></td> <td><button onclick='add()'>提交</button></td> 
 </tr>
</table>
</body>
<script src='js/jquery.min.js'></script>
<script type="text/javascript">
function add(){
	var name=$('#name').val();
	var depId=$('#depId').val();
	var isSpecial=$('input[type="radio"]:checked').val();
	$.get('/core/operate.php',{name:name,depId:depId,isSpecial:isSpecial,act:'add'},function(json){
		location.reload();
	});
}
</script>
</html>
