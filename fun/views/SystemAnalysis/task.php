<style>
.cur{
cursor: pointer;
}
.curs{
color: red
}
</style>
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
                            <th>任务ID</th>
                            <th>开放时间</th>
                            <th>参与人数</th>
                            <th>完成人数</th>
                            <th>完成率</th>
                            <th>最短耗时</th>
                            <th>最长耗时</th>
                            <th>中位数耗时</th>
                            <th>平均耗时</th>
                            <th>投票人数</th>
                            <th>投票次数</th>
                            <th>3星人数</th>
                            <th>4星人数</th>
                            <th>4.5星人数</th>
                            <th>5星人数</th>
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
    var dataOption = {
        title:'',
        autoload: true,
        request_url:'<?php echo site_url('SystemAnalysis/Task');?>',
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
                        '<td>'+result['data'][i]['taskid']+'</td>' +
                        '<td>'+result['data'][i]['logdate']+'</td>' +
                        '<td>'+result['data'][i]['caccountid']+'</td>' +
                        '<td>'+result['data'][i]['sok']+'</td>' +
                        '<td>'+result['data'][i]['rate']+'</td>' +
                        '<td>'+result['data'][i]['mintime']+'</td>' +
                        '<td>'+result['data'][i]['maxtime']+'</td>' +
                        '<td>暂无数据</td>' +
                        '<td>'+result['data'][i]['avgtime']+'</td>' +
                        '<td>'+result['data'][i]['vaccountid']+'</td>' +
                        '<td>'+result['data'][i]['vid']+'</td>' +
                        '<td>'+result['data'][i]['star3']+'</td>' +
                        '<td>'+result['data'][i]['star4']+'</td>' +
                        '<td>'+result['data'][i]['star4_5']+'</td>' +
                        '<td>'+result['data'][i]['star5']+'</td>' +
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
