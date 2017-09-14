<?php
if($_POST){
	$class = new section();
	if($_GET["id"]){
		$class->edit($_POST,$_GET["id"]);
		$msg="修改成功";
		echo "<script>alert('".$msg."');location.href='index.php?action=menu';</script>";
	}else{
		$class->add($_POST);
		$msg="添加成功";
		echo "<script>alert('".$msg."');location.href='index.php?action=menu_form';</script>";
	}
	exit;
}
$info=array();
if($_GET["id"]){
	$class = new section();
	$info=$class->getInfo($_GET["id"]);
}
?>
<form method="post" action="">
<table width="100%" border="1">
	<tr>
		<td width="160">菜单名称</td>
		<td><input type="text" name="name" value="<?php echo $info["name"];?>" size="40">*</td>
	</tr>
	<tr>
		<td width="160">上级菜单</td>
		<td>
			<select name="parent_id">
				<option value="0">第一级菜单</option>
				<?php echo arrayOption(dgArray("_sys_section"),$info["parent_id"]);?>
			</select>*
		</td>
	</tr>
	<tr>
		<td width="160">链接</td>
		<td><input type="text" name="link" value="<?php echo $info["link"];?>" size="40">*</td>
	</tr>
	<tr>
		<td width="160">排序</td>
		<td><input type="text" name="sort" value="<?php echo $info["sort"];?>" size="10"></td>
	</tr>
	<tr>
		<td width="160">数据库表</td>
		<td><input type="text" name="table_name" value="<?php echo $info["table_name"];?>" size="40"></td>
	</tr>
	<tr>
		<td width="160">字段名</td>
		<td><input type="text" name="field_name" value="<?php echo $info["field_name"];?>" >全表请留空</td>
	</tr>
	<tr>
		<td width="160">字段值</td>
		<td><input type="text" name="field_value" value="<?php echo $info["field_value"];?>" ></td>
	</tr>
	<tr>
		<td width="160">拥有权限</td>
		<td><input type="checkbox" name="Slist" value="1" <?php echo ($info["Slist"]||empty($info))? 'checked="checked"':'';?>>列表
		<input type="checkbox" name="Sadd" value="1" <?php echo ($info["Sadd"]||empty($info))? 'checked="checked"':'';?>>新增
		<input type="checkbox" name="Sedit" value="1" <?php echo ($info["Sedit"]||empty($info))? 'checked="checked"':'';?>>修改
		<input type="checkbox" name="Sdelete" value="1" <?php echo ($info["Sdelete"]||empty($info))? 'checked="checked"':'';?>>刪除</td>
	</tr>
	<tr>
		<td width="160">是否隐藏下级菜单</td>
		<td><input type="radio" name="hide_sub" value="0" <?php if($info["hide_sub"]=="0"||!$_GET["id"])echo 'checked="checked"';?>>否
		<input type="radio" name="hide_sub" value="1" <?php if($info["hide_sub"]=="1")echo 'checked="checked"';?>>是</td>
	</tr>
	<tr>
		<td width="160"><input type="button" value="返回列表" onclick="location.href='index.php?action=menu';"></td>
		<td><input type="submit" name="submit" value="提效"></td>
	</tr>
</table>
</form>