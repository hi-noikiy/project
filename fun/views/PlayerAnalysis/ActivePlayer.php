<section id="content">
    <div class="container">
        <div class="block-header">
            <h2><?php echo $page_title;?></h2>
        </div>
        <div class="col-md-12">
            <div class="card">
                <?php echo $search_form;?>
                <div id="echart" style="width: 100%;height:400px;"></div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                            <tr>
                                <th data-column-id="id" >日期</th>
                                <th data-column-id="sender" data-type="numeric">新增角色</th>
                                <th data-column-id="received" data-type="numeric">每天游戏时间大于5分钟</th>
                                <th data-column-id="received" data-type="numeric">每天游戏时间大于120分钟</th>
                                <th data-column-id="received" data-type="numeric">每天游戏时间大于500分钟</th>
                                <th data-column-id="received" data-type="numeric">付费玩家</th>
                                <th data-column-id="received" data-type="numeric">非付费玩家</th>
                                <th data-column-id="received" data-type="numeric"></th>
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
function showdetail(sday){
	layer.open({
		  type: 2,
		  title: 'iframe父子操作',
		  maxmin: true,
		  shadeClose: true, //点击遮罩关闭层
		  area : ['800px' , '520px'],
		  content: '../frame/ActiveDetail?sday='+sday
		  });
}
    var dataOption = {
        title:'活跃玩家',
        request_url:'<?php echo site_url('PlayerAnalysis/getActiveData');?>'
    };
</script>
<script src="<?=base_url()?>public/ma/js/player_analysis_data.js"></script>