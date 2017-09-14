<section id="content">
    <div class="container">
        <div class="block-header">
            <h2><?php echo $page_title;?></h2>
        </div>
        <div class="col-md-12">
            <div class="card">
                <?php echo $search_form_server;?>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th>vip等级</th>
                            <th>活跃人数</th>
                            <th>参与购买人数</th>
                            <th>平均购买次数</th>
                                <th>总购买次数</th>
                            <th>平均购买花费</th>
                            <th>总花费</th>
                          
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
        autoload: false,
        request_url:'<?php echo site_url('DataAnalysis/wavePurchase');?>',
        callback: function (result) {
            if (result) {
                if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify(result.info);
                    return false;
                }
               console.log(result.data);
           

                var table_html = '',
                    len = result.data.length;
                for (var i in result.data) {
                    if (isNaN(i)) continue;
                    //for (var i=0;i<len; i++) {
                    table_html += '<tr>' +
                        '<td>'+result['data'][i]['vip_level']+'</td>' +
                        '<td>'+result['data'][i]['active']+'</td>' +
                        '<td>'+result['data'][i]['buy_num']+'</td>' +
                        '<td>'+result['data'][i]['avg_buy']+'</td>' +
                        '<td>'+result['data'][i]['total_buy_num']+'</td>' +
                        '<td>'+result['data'][i]['avg_money']+'</td>' +
                        '<td>'+result['data'][i]['total']+'</td>' +
                      
                  
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
