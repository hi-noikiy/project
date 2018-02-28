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
                            <th>物品ID</th>
                            <th>物品名称</th>
                            <th>区服</th>
                            <th>渠道</th>
                            <th>获得数量</th>
                            <th>消耗数量</th>
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
        request_url:'<?php echo site_url('Home/shop_count');?>',
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
                        '<td>'+result['data'][i]['item_id']+'</td>' +
                        '<td>'+result['data'][i]['item_name']+'</td>' +
                        '<td>'+result['data'][i]['servername']+'</td>' +
                        '<td>'+result['data'][i]['channelname']+'</td>' +
                        '<td>'+result['data'][i]['get_num']+'</td>' +
                        '<td>'+result['data'][i]['consume_num']+'</td>' +
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
