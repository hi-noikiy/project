<section id="content">
    <div class="container">
		页面名称: <input id='name' value="" >
		<br/>
		<button id='subm'>提交</button>
    </div>
</section>
<script src="/public/ma/js/jquery.min.js"></script>
<script src="/public/ma/js/layer.js"></script>
<script>
$('#subm').click(function(){
	var name=$('#name').val();
	if(name.trim() == ''){
		alert('名称不能为空');
		return false;
	}
	var index = layer.load();
	$.get('',{name:name},function(json){
		 layer.closeAll();
		if(json == '0'){
			 window.parent.location.reload();
		}
		
	});
});

</script>
               