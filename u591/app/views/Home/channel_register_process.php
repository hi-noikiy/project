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
                                <th>渠道ID</th>
                                <th>安装解压数量</th>
                                <th>注册数量</th>
                                <th title="注册数量/设备激活数量">注册数量占比</th>
                                <th>创建角色数量</th>
                                <th title="创建角色数量/设备激活数量">创建角色数量占比</th>
                                <th title="创建角色人数/注册人数">注册转化率</th>
                                <th>创建角色设备数</th>
                                <th>注册账号设备数</th>
                                <th title="总注册数">总注册数</th>
                                <th title="活跃玩家数">活跃玩家数</th>
                                <th>详细</th>
                            </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>渠道ID</th>
                            <th>安装解压数量</th>
                            <th>注册数量</th>
                            <th title="注册数量/设备激活数量">注册数量占比</th>
                            <th>创建角色数量</th>
                            <th title="创建角色数量/设备激活数量">创建角色数量占比</th>
                            <th title="创建角色人数/注册人数">注册转化率</th>
                            <th>创建角色设备数</th>
                            <th>注册账号设备数</th>
                            <th title="总注册数">总注册数</th>
                            <th title="活跃玩家数">活跃玩家数</th>
                            <th>详细</th>
                        </tr>
                        </tfoot>
                        <tbody id="dataTable"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    var channel_list = <?=json_encode($channel_list, JSON_UNESCAPED_UNICODE);?>;
    var dataOption = {
        title:'',
        autoload: true,
        request_url:'<?php echo site_url('Home/ChannelRegisterProcess');?>',
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
                    //for (var i=0;i<len; i++) {
                    table_html += '<tr>' +
                        '<td>['+i+']'+(channel_list[i] ? channel_list[i] : "未知渠道")+'</td>' +
                        '<td>'+(result['data'][i]['device']?result['data'][i]['device']:0)+'</td>' +
                        '<td>'+(result['data'][i]['reg']?result['data'][i]['reg'] : 0)+'</td>' +
                        '<td>'+(result['data'][i]['reg_rate'] ? number_format(result['data'][i]['reg_rate'], 2) : 0)+'%</td>' +
                        '<td>'+(result['data'][i]['role']? result['data'][i]['role'] : 0)+'</td>' +
                        '<td>'+(result['data'][i]['role_rate']?number_format(result['data'][i]['role_rate'],2):0)+'%</td>' +
                        '<td>'+(result['data'][i]['trans_rate']?number_format(result['data'][i]['trans_rate'], 2):0)+'%</td>' +
                        '<td>'+(result['data'][i]['device_role']?result['data'][i]['device_role']:0)+'</td>' +
                        '<td>'+(result['data'][i]['device_reg']?result['data'][i]['device_reg']:0)+'</td>' +
                        '<td>'+(result['data'][i]['register_his']?result['data'][i]['register_his']:0)+'</td>' +
                        '<td>'+(result['data'][i]['active']?result['data'][i]['active']:0)+'</td>' +
                        '<td><a href="<?php echo site_url('Home/getRegFlowDataDetail?channel_id=');?>'+i+'&date1='+result['t']+'&t='+channel_list[i]+'" target="_blank">详细</a></td>' +
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
