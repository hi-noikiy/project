<section id="content">
    <div class="container">
        <div class="block-header">
            <h2><?php echo $page_title;?></h2>
        </div>
        <div class="col-md-12">
            <div class="card">
                <?php echo $search_form;?>
                <div class="card-body card-padding" style="margin-top: -40px;">
                    <div class="alert alert-info" role="alert">总数:<strong id="count_total"></strong></div>
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
                            <th data-column-id="id" data-type="numeric">数量</th>
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
        title:'安装解压',
        request_url:'<?php echo site_url('RealTime/getDeviceData');?>',
        callback: function(result) {
            if (!result) {
                $("#dataTable").empty();
                return false;
            }
            var length = result.length;
            var tr = '';
            for (var i=0; i< length; i++) {
                //result[i]['data'].sort()
                var some = result[i]['data'].reduce(function(pv, cv) { return pv + cv; }, 0);
                tr += '<tr><td>' + result[i]['name'] +'</td><td>' +some + '</td>';
            }
            $("#dataTable").html(tr);
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/realtime_data_1.js"></script>