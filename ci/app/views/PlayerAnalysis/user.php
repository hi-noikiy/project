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
                            <th>区服</th>
                            <th>角色名称</th>
                            <th>角色ID</th>
                            <th>账号ID</th>
                            <th>角色等级</th>
                            <th>VIP等级</th>
                            <th>渠道</th>
                            <th>最后登录时间</th>
                            <th>最后登录IP</th>
                            <th>机型</th>
                            <th>MAC</th>
                            <th>注册时间</th>
                            <th>注册IP</th>
                            <th>剩余钻石</th>
                            <th>充值总金额</th>
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
    //var channel_list = <?=json_encode($channel_list, JSON_UNESCAPED_UNICODE);?>;
    var dataOption = {
        title:'',
        autoload: true,
        request_url:'<?php echo site_url('PlayerAnalysis/user');?>',
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
                        '<td>'+result['data'][i]['servername']+'</td>' +
                        '<td>'+result['data'][i]['username']+'</td>' +
                        '<td>'+result['data'][i]['userid']+'</td>' +
                        '<td>'+result['data'][i]['accountid']+'</td>' +
                        '<td>'+result['data'][i]['lev']+'</td>' +
                        '<td>'+result['data'][i]['viplev']+'</td>' +
                        '<td>'+result['data'][i]['channel']+'</td>' +
                        '<td>'+result['data'][i]['lasttime']+'</td>' +
                        '<td>'+result['data'][i]['lip']+'</td>' +
                        '<td>'+result['data'][i]['client_type']+'</td>' +
                        '<td>'+result['data'][i]['mac']+'</td>' +
                        '<td>'+result['data'][i]['registertime']+'</td>' +
                        '<td>'+result['data'][i]['rip']+'</td>' +
                        '<td>'+result['data'][i]['emoney']+'</td>' +
                        '<td>'+result['data'][i]['total_recharge_num']+'</td>' +
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
