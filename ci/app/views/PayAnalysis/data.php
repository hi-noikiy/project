<section id="content">
    <div class="container">
        <div class="block-header">
        	<h2><?=$page_title?></h2>
        </div>
        <div class="col-md-12">
            <div class="card">
            	<?=$search_form_web?>
            	<div id="echart" style="width: 100%;height:400px; display: none"></div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th>时间</th>
                             <th>总活跃账号</th>
                            <th>充值总金额</th>
                            <th>账号数</th>                           
                            <th>充值次数</th>
                                <th>付费率</th>
                            <th>ARPPU</th>
                            <th>ARPU</th>
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
function serverDistribute(day,where){
	layer.open({
		  type: 2,
		  title: 'iframe父子操作',
		  maxmin: true,
		  shadeClose: true, //点击遮罩关闭层
		  area : ['800px' , '520px'],
		  content: '../frame/serverDistribute?day='+day+'&show='+where
		  });
}
    var dataOption = {
        title:'付费数据',
        request_url:'<?=site_url('PayAnalysis/getActivePaylogData');?>'
    };
</script>
<script src="<?=base_url()?>public/ma/js/pay_analysis_data.js"></script>
