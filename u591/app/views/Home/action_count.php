<style>
.cur{
cursor: pointer;
}
.curs{
color: red
}
</style>
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
            前面3个字段头可以点击
                <div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th class='cur'><span class='curs' id='1'>统计类型</span></th>
                            <th class='cur'><span id='2'>区服</span></th>
                            <th class='cur'><span id='3'>渠道</span></th>
                            <th>参与玩家数量</th>
                            <th>消耗金钱总数</th>
                            <th>消耗钻石总数</th>
                            <th>消耗体力总数</th>
                            <th>获得金钱总数</th>
                            <th>获得钻石总数</th>
                            <th>获得体力总数</th>
                            <th> <a href="javascript:vipdetail(1)">钻石消耗详细</a> <a href="javascript:vipdetail(0)">钻石获取详细</a> </th>
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
var name;
function vipdetail(type){
	if(type==1){
		name = '消耗';
		}else{
			name = '获取';
			}
	layer.open({
		  type: 2,
		  title: '钻石'+name+'详细',
		  maxmin: true,
		  shadeClose: true, //点击遮罩关闭层
		  area : ['800px' , '520px'],
		  content: '../frame/vipDetail?type='+type
		  });
}
function showdetail(actid,where){
	layer.open({
		  type: 2,
		  title: 'iframe父子操作',
		  maxmin: true,
		  shadeClose: true, //点击遮罩关闭层
		  area : ['800px' , '520px'],
		  content: '../frame/ActionDetail?actid='+actid+'&show='+where
		  });
}
function actiondetail(actid){
	layer.open({
		  type: 2,
		  title: 'iframe父子操作',
		  maxmin: true,
		  shadeClose: true, //点击遮罩关闭层
		  area : ['800px' , '520px'],
		  content: '../frame/niuDetail?actid='+actid
		  });
}
	$('.cur').click(function(){
		$(this).children().addClass('curs');
		$(this).siblings().children().removeClass('curs');
		$('#searchtype').val($(this).children().attr('id'));
	});
    var dataOption = {
        title:'',
        autoload: true,
        request_url:'<?php echo site_url('Home/actioncount');?>',
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
                        '<td>'+result['data'][i]['typename']+'</td>' +
                        '<td>'+result['data'][i]['servername']+'</td>' +
                        '<td>'+result['data'][i]['channelname']+'</td>' +
                        '<td>'+result['data'][i]['caccountid']+'</td>' +
                        '<td>'+result['data'][i]['scmoney']+'</td>' +
                        '<td>'+result['data'][i]['scemoney']+'</td>' +
                        '<td>'+result['data'][i]['sctired']+'</td>' +
                        '<td>'+result['data'][i]['sgmoney']+'</td>' +
                        '<td>'+result['data'][i]['sgemoney']+'</td>' +
                        '<td>'+result['data'][i]['sgtired']+'</td>' +
                        '<td>'+result['data'][i]['text']+'</td>' +
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
