<section id="content">
    <div class="container">
        <div class="block-header">
            <h2><?php echo $page_title;?></h2>
        </div>
        <div class="col-md-12">
            <div class="card">
                <?php echo $search_form;?>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th>日期</th>
                            <th>总剩余钻石</th>
                            <th>活跃玩家剩余钻石</th>
                            <th>服务器详细</th>
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
<script src="<?=base_url()?>public/ma/js/layer.js"></script>
<script>
function showdetail(logdate){
	layer.open({
		  type: 2,
		  title: logdate+'服务器详细',
		  maxmin: true,
		  shadeClose: true, //点击遮罩关闭层
		  area : ['800px' , '520px'],
		  content: '../frame/emoneyDetail?logdate='+logdate
		  });
}
function rankdetail(logdate){
	layer.open({
		  type: 2,
		  title: logdate+'前100名玩家',
		  maxmin: true,
		  shadeClose: true, //点击遮罩关闭层
		  area : ['800px' , '520px'],
		  content: '../frame/emoneyRank?logdate='+logdate
		  });
}
    var dataOption = {
        title:'',
        autoload: true,
        request_url:'<?php echo site_url('Home/lastEmoney');?>',
        callback: function (result) {
            if (result) {
                if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify(result.info);
                    return false;
                }
                //console.log(result.data);

                var table_html = '',
                    len = result.data.length;
                for (var i in result.data) {
                    if (isNaN(i)) continue;
                    //for (var i=0;i<len; i++) {
                    table_html += '<tr>' +
                        '<td>'+result['data'][i]['logdate']+'</td>' +
                        '<td>'+result['data'][i]['allemoney']+'</td>' +
                        '<td>'+result['newdata'][result['data'][i]['logdate']]+'</td>' +
                        '<td><a href="javascript:showdetail('+result['data'][i]['logdate']+')">详细</a> <a href="javascript:rankdetail('+result['data'][i]['logdate']+')">前100名玩家</a></td>' +
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
