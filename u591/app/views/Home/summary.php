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
                            <th>时间</th>
                            <th>新增设备</th>
                            <th>新增设备注册数</th>
                            <th>新增设备注册转化率</th>
                            <th>注册数</th>
                               <th>注册递增百分比</th>
                            <!--<th>月付费人数</th>-->
                            <!--<th>累计金额</th>-->
                            <!--<th>月新付费人数</th>-->
                            <!--<th>新付费累计金额</th>-->
                            <th>创建数</th>
                            <th>创建率</th>
                              <th> 新设备使用<br>旧账号登录数量</th>
                               <th> 安装新包使用<br>旧账号登录数量</th>
                           
                            <th>DAU</th>
                            <th>DAU递增百分比</th>
                              <th>净DAU</th>
                               <th>净DAU递增百分比</th>
                                 <th>付费DAU</th>
                            <th>WAU</th>
                            <th>MAU</th>
                            <th>最高在线<br/>(在线峰值)</th>
                            <th>平均在线人数<br/>(最高在线数/24)</th>
                            <th>平均在线时间<br/>(单位:分钟)</th>
                            <!--<th>充值人数</th>-->
                            <!--<th>充值金额</th>-->
                            <!--<th>首充人数</th>-->
                            <!--<th>首充金额</th>-->
                            <th>次日<br/>留存</th>
                            <th>3日<br/>留存</th>
                            <th>7日<br/>留存</th>
                            <th>15日<br/>留存</th>
                            <th>30日<br/>留存</th>
                             <th>vip角色</th>
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
<script src="<?=base_url()?>public/ma/js/layer.js"></script>
<script>

function showdetail(date,where){
	layer.open({
		  type: 2,
		  title: 'iframe父子操作',
		  maxmin: true,
		  shadeClose: true, //点击遮罩关闭层
		  area : ['800px' , '520px'],
		  content: '../frame/vipDistribution?date='+date+'&show='+where
		  });
}
    var channel_list = <?=json_encode($channel_list, JSON_UNESCAPED_UNICODE);?>;
    var dataOption = {
        title:'',
        autoload: false,
        request_url:'<?php echo site_url('Home/summary');?>',
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
                        '<td>'+result['data'][i]['date']+'</td>' +
                        '<td>'+result['data'][i]['device']+'</td>' +
                        '<td>'+result['data'][i]['macregister']+'</td>' +
                        '<td>'+result['data'][i]['rare']+'</td>' +
                        '<td>'+result['data'][i]['reg']+'</td>' +
                        '<td>'+result['data'][i]['reg_rate']+'%</td>' +                        
                        '<td>'+result['data'][i]['role']+'</td>' +
                        '<td>'+result['data'][i]['trans_rate']+'%</td>' +                        
                        '<td>'+result['data'][i]['device_old_account']+'</td>' +
                        '<td>'+result['data'][i]['install_old_account']+'</td>' +     
                        '<td>'+result['data'][i]['dau']+'</td>' +
                        '<td>'+result['data'][i]['dau_rate']+'%</td>' +
                        '<td>'+result['data'][i]['clean_dau']+'</td>' +
                        '<td>'+result['data'][i]['clean_dau_rate']+'</td>' +
                        '<td>'+result['data'][i]['pay_dau']+'</td>' +
                        '<td>'+result['data'][i]['wau']+'</td>' +
                        '<td>'+result['data'][i]['mau']+'</td>' +
                        '<td>'+result['data'][i]['max_online']+'</td>' +
                        '<td>'+result['data'][i]['avg_online_cnt']+'</td>' +
                        '<td>'+result['data'][i]['avg_online']+'</td>' +
                        '<td>'+result['data'][i]['remain_1']+'</td>' +
                        '<td>'+result['data'][i]['remain_3']+'</td>' +
                        '<td>'+result['data'][i]['remain_7']+'</td>' +
                        '<td>'+result['data'][i]['remain_15']+'</td>' +
                        '<td>'+result['data'][i]['remain_30']+'</td>' +
                        '<td>'+result['data'][i]['text']+'</td>' +
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
