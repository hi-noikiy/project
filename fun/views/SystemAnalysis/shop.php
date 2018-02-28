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
                            <th>时间</th>
                            <th>金币购买人数</th>
                            <th>金币购买次数</th>
                            <th>金币购买总额</th>
                            <th>钻石购买人数</th>
                            <th>钻石购买次数</th>
                            <th>钻石购买总额</th>
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
        request_url:'<?php echo site_url('SystemAnalysis/shop');?>',
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
                        '<td>'+result['data'][i]['money_buy_account']+'</td>' +
                        '<td>'+result['data'][i]['money_buy_count']+'</td>' +
                        '<td>'+result['data'][i]['money_buy_num']+'</td>' +
                        '<td>'+result['data'][i]['emoney_buy_account']+'</td>' +
                        '<td>'+result['data'][i]['emoney_buy_count']+'</td>' +
                        '<td>'+result['data'][i]['emoney_buy_num']+'</td>' +
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
