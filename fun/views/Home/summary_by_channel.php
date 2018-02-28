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
                            <th>渠道</th>
                            <th>新增设备</th>
                            <th>新增设备注册数</th>
                            <th>新增设备注册转化率</th>
                            <th>注册数</th>
                            <th>创建数</th>
                            <th>创建率</th>
                            <th>DAU</th>
                            <th>WAU</th>
                            <th>MAU</th>
                            <th>次日<br/>留存</th>
                            <th>3日<br/>留存</th>
                            <th>7日<br/>留存</th>
                            <th>15日<br/>留存</th>
                            <th>30日<br/>留存</th>
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
<script>
    var channel_list = <?=json_encode($channel_list, JSON_UNESCAPED_UNICODE);?>;
    var dataOption = {
        title:'',
        autoload: true,
        request_url:'<?php echo site_url($_request_method);?>',
        callback: function (result) {
            if (result) {
                if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify("客官,不好意思，没有查到数据!");
                    return false;
                }
                //console.log(result.data);

                var table_html = '',
                    len = result.data.length;
                for (var i in result.data) {
                    if (isNaN(i)) continue;
                    //for (var i=0;i<len; i++) {
                    table_html += '<tr>' +
                        '<td>'+result['data'][i]['channel']+'</td>' +
                        '<td>'+result['data'][i]['device']+'</td>' +
                        '<td>'+result['data'][i]['macregister']+'</td>' +
                        '<td>'+result['data'][i]['rare']+'</td>' +
                        '<td>'+result['data'][i]['reg']+'</td>' +
                        '<td>'+result['data'][i]['role']+'</td>' +
                        '<td>'+result['data'][i]['trans_rate']+'%</td>' +
                        '<td>'+result['data'][i]['dau']+'</td>' +
                        '<td>'+result['data'][i]['wau']+'</td>' +
                        '<td>'+result['data'][i]['mau']+'</td>' +
                        '<td>'+result['data'][i]['remain_1']+'</td>' +
                        '<td>'+result['data'][i]['remain_3']+'</td>' +
                        '<td>'+result['data'][i]['remain_7']+'</td>' +
                        '<td>'+result['data'][i]['remain_15']+'</td>' +
                        '<td>'+result['data'][i]['remain_30']+'</td>' +
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
