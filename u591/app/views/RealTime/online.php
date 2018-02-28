<section id="content">
    <div class="container">
        <div class="block-header">
            <h2><?php echo $page_title;?></h2>
        </div>
        <div class="col-md-12">
            <div class="card">
                <?php echo $search_form;?>
                <!--<div class="card-body card-padding" style="margin-top: -40px;">-->
                <!--    <div class="alert alert-info" role="alert">最大在线数:<strong id="count_total"></strong></div>-->
                <!--</div>-->
                <div id="echart" style="width: 100%;height:400px;"></div>
            </div>
            <div class="card">
                <div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th data-column-id="id" data-type="numeric">日期</th>
                            <th data-type="numeric">最大在线</th>
                            <th data-type="numeric">登录玩家总数</th>
                            <th data-type="numeric">平均在线(单位:分钟)</th>
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
        title:'',
        request_url:'<?php echo site_url('RealTime/getOnlineData');?>',
        callback: function(result, rawData) {
            if (rawData['status']=='fail') {
                $("#dataTable").empty();
                return false;
            }
            var length = result.length;
            var tr = '';
            for (var i=0; i< length; i++) {
                result[i]['data'].sort(function sortNumber(a,b) {
                    return a - b;
                });
                console.log(result[i]['data']);
                tr += '<tr><td>' + result[i]['name'] + '</td><td>' + result[i]['data'].pop() + '</td>'
                    + '<td>'
                    + (rawData['online_avg'].hasOwnProperty(result[i]['name']) ? rawData['online_avg'][result[i]['name']]['online_num']  : 0)
                    + '</td><td>'+(rawData['online_avg'].hasOwnProperty(result[i]['name']) ? rawData['online_avg'][result[i]['name']]['avg']  : 0)
                    + '</td></tr>';
            }
            $("#dataTable").html(tr);
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/realtime_data_1.js?v=12222"></script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
