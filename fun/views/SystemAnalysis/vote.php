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
                            <th>开放时间</th>
                            <th>投票人数</th>
                            <th>总投票数</th>
                            <th>平均投票数</th>
                            <th>左边总次数</th>
                            <th>右边总次数</th>
                            <th>等于总次数</th>
                            <th>放弃总次数</th>
                            <th>单人最小投票数</th>
                            <th>单人最大投票数</th>
                            <th>单人投票中位数</th>
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
        request_url:'<?php echo site_url('SystemAnalysis/vote');?>',
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
                        '<td>'+result['data'][i]['logdate']+'</td>' +
                        '<td>'+result['data'][i]['vaccountid']+'</td>' +
                        '<td>'+result['data'][i]['vid']+'</td>' +
                        '<td>'+result['data'][i]['rate']+'</td>' +
                        '<td>'+result['data'][i]['s0']+'</td>' +
                        '<td>'+result['data'][i]['s1']+'</td>' +
                        '<td>'+result['data'][i]['s2']+'</td>' +
                        '<td>暂无数据</td>' +
                        '<td>'+result['data'][i]['mincid']+'</td>' +
                        '<td>'+result['data'][i]['maxcid']+'</td>' +
                        '<td>暂无数据</td>' +
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
