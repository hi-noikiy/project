<table width="100%" border="0">
	<tr><td><a href="index.php?action=menu_form">添加菜单</a></td></tr>
</table>
<table style="margin-top:10px;" border="1" width="100%">
	<tr>
		<th>菜单名称</th>
		<th>数据库表</th>
		<th>链接</th>
		<th>排序</th>
		<th>是否隐藏下级菜单</th>
		<th>操作</th>
	</tr>
	<?php 
	$class = new section();
	foreach (resetArray(0,$class->getList()) as $rs){
	?>
	<tr onMouseOver="this.bgColor='#00CCFF'" onMouseOut="this.bgColor='#FFFFFF'">
		<td><?php echo $rs["indent"]."|- ".$rs["name"];?></td>
		<td><?php echo $rs["table_name"]?$rs["table_name"]:"&nbsp;";?></td>
		<td><?php echo $rs["link"]?$rs["link"]:"&nbsp;";?></td>
		<td><?php echo $rs["sort"]?$rs["sort"]:"&nbsp;";?></td>
		<td><?php echo get_hide_sub($rs["hide_sub"]);?></td>
		<td>
		<a href="javascript:void(0);" onclick="delete_menu('<?php echo $rs["id"];?>')">刪除</a> 
		<a href="index.php?action=menu_form&id=<?php echo $rs["id"];?>">修改</a></td>
	</tr>
	<?php 
	}
	?>
</table>