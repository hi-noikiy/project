<section id="content">
    <div class="container">
        <div class="block-header">
            <h2><?php echo $page_title;?></h2>
        </div>
        <div class="col-md-12">
            <div class="card">
                <?php echo $search_form;?>
                <div class="card-body card-padding" style="margin-top: -40px;">
                    <div class="alert alert-info" role="alert">
                        总数:<strong id="count_total"></strong> | 注册转化率 = 新增账号（MAC去重） / 设备激活
                    </div>
                </div>
                <div id="echart" style="width: 100%;height:400px;"></div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th data-column-id="id" data-type="numeric">日期</th>
                            <th data-column-id="id" data-type="numeric">激活数量</th>
                            <th data-column-id="id" data-type="numeric">设备注册数量</th>
                            <th data-column-id="id" data-type="numeric" title="">注册转化率</th>
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
<script>
    var dataOption = {
        title:'设备激活',
        request_url:'<?php echo site_url('RealTime/getDeviceActiveData');?>',
        callback: function(result, raw_data) {
            if (!result) {
                $("#dataTable").empty();
                return false;
            }
            var length = result.length;
            var tr = '';
            for(var i in result){
            	 var some = result[i];
                 //var reg = ((raw_data['reg_data'][i] / some) * 100).toFixed(2);
                 var reg = raw_data['rares'][i];
                 tr += '<tr>' +
                     '<td>' + i +'</td>' +
                     '<td>' +some + '</td>' +
                     '<td>' +raw_data['reg_data'][i] + '</td>' +
                     '<td>'+reg+'%</td>';
             }
            $("#dataTable").html(tr);
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/realtime_data.js"></script>