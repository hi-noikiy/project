<table style="margin-top:10px;" border="1" width="100%">
	<tr>
		<th><input type="checkbox" name="menu_selectAll" value="1" onclick="power_selectAll('menu_selectAll','menu_item_')" >選單</th>
		<th><input type="checkbox" name="list_selectAll" value="1" onclick="power_selectAll('list_selectAll','list_')">列表</th>
		<th><input type="checkbox" name="add_selectAll" value="1" onclick="power_selectAll('add_selectAll','add_')" >新增</th>
		<th><input type="checkbox" name="edit_selectAll" value="1" onclick="power_selectAll('edit_selectAll','edit_')" >修改</th>
		<th><input type="checkbox" name="delete_selectAll" value="1" onclick="power_selectAll('delete_selectAll','delete_')" >刪除</th>
	</tr>
	<?php 
	$class = new section();
	foreach (resetArray(0,$class->getList()) as $rs){
	?>
	<tr onMouseOver="this.bgColor='#00CCFF'" onMouseOut="this.bgColor='#FFFFFF'">
		<td><input type="checkbox" name="menu_item_<?php echo $rs["id"];?>_END" value="1" onclick="power_selectAll('menu_item_<?php echo $rs["id"];?>_END','_item_<?php echo $rs["id"];?>_END')" ><?php echo $rs["indent"]."|- ".$rs["name"];?></td>
		<td><?php if($rs["Slist"]=="0"){ echo '&nbsp;';}else{?><input type="checkbox" onclick="menu_item_list_select(<?php echo $rs["id"];?>,'list')" name="list_item_<?php echo $rs["id"];?>_END" value="1" <?php if($rs["Slist"]=="0") echo 'disabled="disabled"';?> >列表<?php }?></td>
		<td><?php if($rs["Sadd"]=="0"){ echo '&nbsp;';}else{?><input type="checkbox" onclick="menu_item_list_select(<?php echo $rs["id"];?>,'add')" name="add_item_<?php echo $rs["id"];?>_END" value="1">新增<?php }?></td>
		<td><?php if($rs["Sedit"]=="0"){ echo '&nbsp;';}else{?><input type="checkbox" onclick="menu_item_list_select(<?php echo $rs["id"];?>,'edit')" name="edit_item_<?php echo $rs["id"];?>_END" value="1">修改<?php }?></td>
		<td><?php if($rs["Sdelete"]=="0"){ echo '&nbsp;';}else{?><input type="checkbox" onclick="menu_item_list_select(<?php echo $rs["id"];?>,'delete')" name="delete_item_<?php echo $rs["id"];?>_END" value="1">刪除<?php }?></td>
	</tr>
	<?php 
	}
	?>
	<!-- 
	<tr onMouseOver="this.bgColor='#00CCFF'" onMouseOut="this.bgColor='#FFFFFF'">
		<td><input type="checkbox" name="" value="1" ><?php echo $rs["indent"]."|- ".$rs["name"];?></td>
		<td><input type="checkbox" name="" value="1" <?php if($rs["Slist"]=="0") echo 'disabled="disabled"';?> >列表</td>
		<td><input type="checkbox" name="" value="1" <?php if($rs["Sadd"]=="0") echo 'disabled="disabled"';?> >新增</td>
		<td><input type="checkbox" name="" value="1" <?php if($rs["Sedit"]=="0") echo 'disabled="disabled"';?> >修改</td>
		<td><input type="checkbox" name="" value="1" <?php if($rs["Sdelete"]=="0") echo 'disabled="disabled"';?> >刪除</td>
	</tr>
	 -->
</table>
<script language="javascript">

function power_selectAll(select_name,input_name){
	
	$("input[name='"+select_name+"']").click( function (){
		$("input[name*='"+input_name+"']").attr("checked",this.checked); 
	}); 
	
	if(select_name.substr(0,10)=="menu_item_"){
		var allCheck=true;
		$('input[name*=menu_item_]').each(function (){
			if(!$(this).attr('checked')){
				allCheck=false;
			}
		});
		$("input[name='menu_selectAll']").attr("checked",allCheck);
		item_select("list",input_name,$("input[name='"+select_name+"']").attr('checked'));
		item_select("add",input_name,$("input[name='"+select_name+"']").attr('checked'));
		item_select("edit",input_name,$("input[name='"+select_name+"']").attr('checked'));
		item_select("delete",input_name,$("input[name='"+select_name+"']").attr('checked'));
	}
}
function menu_item_list_select(id,section){
	var menu_item_select=false;
	var list_name="list_item_"+id+"_END";
	var add_name="add_item_"+id+"_END";
	var edit_name="edit_item_"+id+"_END";
	var delete_name="delete_item_"+id+"_END";
	if($('input[name*='+list_name+']').attr('checked')) menu_item_select=true;
	if($('input[name*='+add_name+']').attr('checked')) menu_item_select=true;
	if($('input[name*='+edit_name+']').attr('checked')) menu_item_select=true;
	if($('input[name*='+delete_name+']').attr('checked')) menu_item_select=true;
	$("input[name*='menu_item_"+id+"_END']").attr("checked",menu_item_select);
	var menu_selectAll_status=$("input[name='menu_selectAll']").attr("checked");
	if(menu_selectAll_status!=menu_item_select){
		var allCheck=true;
		$('input[name*=menu_item_]').each(function (){
			if(!$(this).attr('checked')){
				allCheck=false;
			}
		});
		$("input[name='menu_selectAll']").attr("checked",allCheck);
	}
	var itme_allCheck=true;
	$('input[name*='+section+'_item_]').each(function (){
		if(!$(this).attr('checked')){
			itme_allCheck=false;
		}
	});
	$("input[name='"+section+"_selectAll']").attr("checked",itme_allCheck);
}

function item_select(section,end_str,current_select_status){
	var itme_allCheck=true;
	$('input[name*='+section+'_item_]').each(function (){
		if(this.name==(section+end_str)){
			if(!current_select_status){
				itme_allCheck=false;
			}
		}else{
			if(!$(this).attr('checked')){
				itme_allCheck=false;
			}
		}
	});
	$("input[name='"+section+"_selectAll']").attr("checked",itme_allCheck);
}
/*
$(document).ready(function(){
	  $("input[name='selectAll']").click( function (){ 
		$("input[name*='permission_group']").attr("checked",this.checked); 
	  }); 
}); */
</script>