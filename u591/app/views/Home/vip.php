<section id="content">
    <div class="container">
        <div class="block-header">
            <h2><?php echo $page_title; ?></h2>
        </div>
        <div class="col-md-12">
            <div class="card">
              <?php echo $search_form; ?>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th>VIP等级</th>
                            <th>总人数</th>
                            <th>活跃人数</th>
                            <th>新增</th>
                        </tr>
                        </thead>
                        <tbody id="dataTable">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<!--<script>-->
<!--    $("#submit").attr('type', 'submit');-->
<!--</script>-->
<script src="<?=base_url() ?>public/ma/js/layer.js"></script>
<script>function showdetail(date, where) {
	layer.open({
		type: 2,
		title: 'iframe父子操作',
		maxmin: true,
		shadeClose: true, //点击遮罩关闭层
		area: ['800px', '520px'],
		content: '../frame/vipDistribution?date=' + date + '&show=' + where
	});
}
var channel_list =<?=json_encode($channel_list, JSON_UNESCAPED_UNICODE); ?>;
var dataOption = {
		title: '',
		autoload: false,
		request_url: '<?php echo site_url('Home/vip'); ?>',
callback: function(result) {
	if(result) {
		if(result.status != 'ok') {
			$("#dataTable").html('');
			notify("客官,不好意思，没有查到数据!");
			return false;
		}
		var table_html = '',
			len = result.data[0].length;
		var dataXz = new Array();
		var dataHc = new Array();
//		console.log(result);
		for (var i = 0; i < len; i++) {
			dataXz[i] = {'vip_level':i,'xz':'0'};
			dataHc[i] = {'viplev':i,'hc':'0'};
		}
		for(var i = 0; i < len; i++) {
			for(var j = 0; j < result.data[2].length; j++){
				if (result.data[2][j].vip_level == i) {
					dataXz[i] = {'vip_level':i,'xz':result.data[2][j].xz};
				}
			}
			for(var k = 0; k < result.data[1].length; k++){
				if (result.data[1][k].viplev == i) {
					dataHc[i] = {'viplev':i,'hc':result.data[1][k].hc};
				}
			}
		}
		for(var i = 0; i < len; i++) {
			table_html += '<tr>' +
				'<td>' + i + '</td>' +
				'<td>' + result.data[0][i]['zc'] + '</td>' +
				'<td>' + dataHc[i]['hc'] + '</td>' +
				'<td>' + dataXz[i]['xz'] + '</td>' +
				'</tr>';
		}
		$("#dataTable").html(table_html);
	}
}
};</script>
<script src="<?=base_url() ?>public/ma/js/common_req.js"></script>
