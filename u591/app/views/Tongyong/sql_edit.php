<section id="content">
    <div class="container">
		显示名称: <input id='fields_name' value="<?php echo $this->data['fields_name'];?>" ><br/>
		select字段: <input id='fields' value="<?php echo $this->data['fields'];?>" size="100"><br/>
		from表及条件: <input id='use_sql' value="<?php echo $this->data['use_sql'];?>" size="100"><br/>
		可选条件: <input id='where_info' value="<?php echo $this->data['where_info'];?>" size="100"><br/>
		执行顺序: <input id='exec_sort' type="number" value="<?php echo $this->data['exec_sort'];?>" ><br/>
		所属页面: <select id='tongyong_id'>
		<?php foreach($this->show as $v){ 
		echo "<option value='{$v['id']}' ";
		if($v['id']==$this->data['tongyong_id']) echo 'selected';
		echo ">{$v['name']}</option>";
		}
		?>
		</select>
		<br/>
		<button id='subm'>提交</button>
    </div>
    
</section>
<script src="/public/ma/js/jquery.min.js"></script>
<script src="/public/ma/js/layer.js"></script>
<script>
var sqlid = "<?php echo $_GET['sqlid'];?>";
if(sqlid == 'undefined'){
	sqlid = 0;
}
$('#subm').click(function(){
	var fields_name=$('#fields_name').val();
	var fields=$('#fields').val();
	var use_sql=$('#use_sql').val();
	var where_info=$('#where_info').val();
	var exec_sort=$('#exec_sort').val();
	var tongyong_id=$('#tongyong_id').val();
	if(fields_name.trim() == ''){
		alert('显示名称不能为空');
		return false;
	}
	if(fields.trim() == ''){
		alert('字段不能为空');
		return false;
	}
	if(use_sql.trim() == ''){
		alert('表及条件不能为空');
		return false;
	}
	if(exec_sort.trim() == ''){
		alert('执行顺序不能为空');
		return false;
	}
	var index = layer.load();
	$.get('',{sqlid:sqlid,fields_name:fields_name,fields:fields,use_sql:use_sql,where_info:where_info,exec_sort:exec_sort,tongyong_id:tongyong_id},function(json){
		 layer.closeAll();
		if(json == '0'){
			 window.parent.location.reload();
		}
		
	});
});

</script>
               