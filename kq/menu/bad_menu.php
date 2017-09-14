失效菜单
<table style="margin-top:10px;" border="1" width="100%">
	<tr>
		<th>菜单名称</th>
		<th>链接</th>
		<th>排序</th>
		<th>是否隐藏下级菜单</th>
		<th>操作</th>
	</tr>
	<?php 
	$list=$webdb->getList("select * from _sys_section where parent_id<>0 ");
	$section_list = $webdb->getList("select id from _sys_section");
	foreach ($section_list as $val){
		$section_ids[]=$val["id"];
	}
	
	foreach ($list as $rs){
		if(!in_array($rs["parent_id"],$section_ids)){
	?>
	<tr>
		<td><?php echo $rs["name"];?></td>
		<td><?php echo $rs["link"]?$rs["link"]:"&nbsp;";?></td>
		<td><?php echo $rs["sort"]?$rs["sort"]:"&nbsp;";?></td>
		<td><?php echo get_hide_sub($rs["hide_sub"]);?></td>
		<td>
		<a href="javascript:void(0);" onclick="delete_menu('<?php echo $rs["id"];?>')">刪除</a> 
		<a href="index.php?action=menu_form&id=<?php echo $rs["id"]."&from=bad_menu";?>">修改</a></td>
	</tr>
	<?php 
		}
	}
	?>
</table>