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
                                <th>在线时长（分钟）</th>
                                <th>付费玩家</th>
                                <th>付费玩家占比</th>
                                <th>非付费玩家</th>
                                <th>非付费玩家占比</th>
                                <th>总玩家</th>
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
    var lvl_list = <?=json_encode($lvl_list, JSON_UNESCAPED_UNICODE);?>;
    var dataOption = {
        title:'',
        autoload: true,
        request_url:'<?php echo site_url('OnlineAnalysis/online_time_lvl');?>',
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
                var data_list = result['data']['list'];
                var total_players = result['data']['total']['players'];
                for (var i in lvl_list) {
                    if (isNaN(i)) continue;
                    table_html += '<tr><td>'+lvl_list[i]+'</td>';
                    if (!data_list.hasOwnProperty(i)) {
                        table_html += '<td>0</td><td>0%</td><td>0</td><td>0%</td><td>0</td>';
                    } else {
                        table_html += '<td>'+(data_list[i].hasOwnProperty('rmb')  ? data_list[i]['rmb']:0)+'</td>' +
                            '<td>'+(data_list[i].hasOwnProperty('rmb') ? number_format(data_list[i]['rmb'] / total_players * 100, 2) : 0)+'%</td>' +
                            '<td>'+(data_list[i].hasOwnProperty('not_rmb') ? data_list[i]['not_rmb']:0)+'</td>' +
                            '<td>'+(data_list[i].hasOwnProperty('not_rmb') ? number_format(data_list[i]['not_rmb'] / total_players * 100, 2) : 0)+'%</td>' +
                            '<td>'+(data_list[i].hasOwnProperty('player')  ? data_list[i]['player']:0)+'</td>';

                    }
                    table_html += '</tr>';
                }

                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
