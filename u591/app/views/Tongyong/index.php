<section id="content">
	<div class="container">
		<div class="block-header">
			<h2><?php echo $title; ?></h2>
		</div>
		<div class="col-md-12">
			<div class="card">
                <?php echo $tongyong_search_form; ?>
            </div>
		</div>
		<style type="text/css">
			.table_box {
			position: relative;
			width: 100%;
			padding: 0px 15px;
			float: left;
		}
		
		.table_box .table_box_content {
			float: left;
		}
		
		.table_box .table_box_content ul {
			width: 100%;
			padding: 0px;
			list-style-type: none;
		}
		
		.table_box .table_box_content .table_box_title li {
			float: left;
			text-align: center;
			background-color: white;
		}
		
		.table_box .table_box_content .table_box_text {
			width: 100%;
		}
		
		.table_box .table_box_content .table_box_text li {
			width: 100%;
		}
		
		.table_box .table_box_content .table_box_text li p{
			text-align: center;
			margin: 0;
			float: left;
		}
		.table_box .table_box_content .table_box_text li:nth-of-type(2n){
			background-color: white;
		}
		
		.clearBoth {
			clear: both;
			width: 0px;
			height: 0px;
		}
		</style>
		<div class="table_box">
			<!-- <div class="table_box_content">
				<ul class="table_box_title">
					<li>用户</li>
					<li>用户</li>
					<div class="clearBoth"></div>
				</ul>
				<ul class="table_box_text">
					<li><p>41654646</p><p>41654646</p><div class="clearBoth"></div></li>
					<li><p>41654646</p><p>41654646</p><div class="clearBoth"></div></li>
					<li><p>41654646</p><p>41654646</p><div class="clearBoth"></div></li>
					<li><p>41654646</p><p>41654646</p><div class="clearBoth"></div></li>
					<li><p>41654646</p><p>41654646</p><div class="clearBoth"></div></li>
				</ul>
			</div> -->
		</div>
		<script type="text/javascript">
			function tableBox() {
				var conLens = new Array();
				var conCont = 0;
				for(var i = 0; i < $('.table_box_content').length; i++) {
					conLens[i] = $('.table_box_content').eq(i).find('.table_box_title li').length;
					conCont += $('.table_box_content').eq(i).find('.table_box_title li').length;
				}
				for(var i = 0; i < $('.table_box_content').length; i++) {
					var conLen = conLens[i] / conCont * 100;
					var liLen = 100 / conLens[i];
					$('.table_box_content').eq(i).css('width', conLen + '%');
					$('.table_box_content').eq(i).find('.table_box_title li').css('width', liLen + '%');
					$('.table_box_content').eq(i).find('.table_box_text li p').css('width', liLen + '%');
				}
			}
		</script>
	</div>
</section>
<script src="<?=base_url()?>public/ma/js/layer.js"></script>
<script>function getdata(beginid) {
	var param = $("#search_form").serialize();
	param += "&beginid=" + beginid;
	var index = layer.load();
	$.ajax({
				type: "get",
				async: true, //同步执行
				url: '<?php echo site_url($_request_method); ?>',
				data: param,
				dataType: "json", //返回数据形式为json
				before: function() {

				},
		success: function(result) {
		layer.close(index);
		if(result.status != 'ok') {
			return false;
		}
		var table_html = '';
		table_html += '<div class="table_box_content"><ul class="table_box_title">';
		for(var i in result.data.fields_name) {
			if(isNaN(i)) continue;
			table_html += '<li>'+result.data.fields_name[i]+'</li>';
		}
		table_html += '<div class="clearBoth"></div></ul><ul class="table_box_text">';
		for(var i in result.data.data) {
			if(isNaN(i)) continue;
			table_html += '<li>';
			for(var j in result.data.field) {
				if(isNaN(j)) continue;
				table_html += '<p>' + result.data.data[i][result.data.field[j]] + '</p>';
			}
			table_html += '<div class="clearBoth"></div></li>';
		}
		table_html += '</ul></div>';
		$('.table_box').append(table_html);
		tableBox();
		if(result.data.beginid > 1) {
			getdata(beginid);
		}
	},
	error: function(errorMsg) {
		layer.close(index);
		alert("客官,不好意思，请求数据失败啦!");
	}
});
}
var dataOption = {
		title: '',
		autoload: false,
		request_url: '<?php echo site_url($_request_method) . '?beginid=' . $beginid; ?>',
callback: function(result) {
	if(result) {
		if(result.status != 'ok') {
			$("#dataTable").html('');
			notify("客官,不好意思，没有查到数据!");
			return false;
		}
		var table_html = '';
		table_html += '<div class="table_box_content"><ul class="table_box_title">';
		for(var i in result.data.fields_name) {
			if(isNaN(i)) continue;
			table_html += '<li>'+result.data.fields_name[i]+'</li>';
		}
		table_html += '<div class="clearBoth"></div></ul><ul class="table_box_text">';
		for(var i in result.data.data) {
			if(isNaN(i)) continue;
			table_html += '<li>';
			for(var j in result.data.field) {
				if(isNaN(j)) continue;
				table_html += '<p>' + result.data.data[i][result.data.field[j]] + '</p>';
			}
			table_html += '<div class="clearBoth"></div></li>';
		}
		table_html += '</ul></div>';
		$('.table_box').html(table_html);
		tableBox();
		if(result.data.beginid > 0) {
			getdata(result.data.beginid);
		}
	}
}
};</script>
<script src="<?=base_url() ?>public/ma/js/common_req.js"></script>
