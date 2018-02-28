<section id="content">
    <div class="container">
        <div class="block-header">
            <h2><?php echo $page_title;?></h2>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card">
                    <div class="table-responsive">
                        <table id="data-table-basic" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>机型总数:</th>
                                    <th colspan="2" id="total_client"></th>
                                </tr>
                                <tr>
                                    <th>机型</th>
                                    <th>设备数量</th>
                                    <th>百分比</th>
                                </tr>
                            </thead>
                            <tbody id="dataTable"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    var dataOption = {
        title:'机型',
        request_url:'<?php echo site_url('PlayerAnalysis/getDeviceDetailData');?>'
    };
</script>
<script src="<?=base_url()?>public/ma/js/player_device_detail.js"></script>