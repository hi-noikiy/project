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
                                <?php if ($by_channel):?>
                                <th>渠道</th>
                                <?php else :?>
                                <th data-column-id="id" >日期</th>
                                <?php endif;?>
                                <th data-column-id="received" data-type="numeric">创建角色数</th>
                                <th data-column-id="received" data-type="numeric">DAU</th>
                                <th data-column-id="received" data-type="numeric" title="不是今天注册，但在今天有登录的账号数量">净DAU(非当日注册)</th>
                                <th data-column-id="received" data-type="numeric">WAU</th>
                                <th data-column-id="received" data-type="numeric">MAU</th>
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
    var channel_list = <?=json_encode($channel_list, JSON_UNESCAPED_UNICODE);?>;
    var dataOption = {
        autoload: true,
        request_url:'<?php echo site_url($_request_method);?>',
        callback: function (result) {
            if (result) {
                if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify("客官,不好意思，没有查到数据!");
                    return false;
                }
                var table_html = '';
                var len = result['data'].length;
                for (var i =0 ;i<len;i++) {
                    if (!result['data'].hasOwnProperty(i)) continue;
                    table_html += '<tr>' +
                            <?php if($by_channel===true):?>
                        '<td>['+result['data'][i]['channel']+']'+channel_list[result['data'][i]['channel']]+'</td>'+
                        <?php else:?>
                        '<td>'+result['data'][i]['sday']+'</td>'+
                        <?php endif;?>
                        '<td>'+result['data'][i]['new_role']+'</td>'+
                        '<td>'+result['data'][i]['dau']+'</td>'+
                        '<td>'+result['data'][i]['clean_dau']+'</td>'+
                        '<td>'+result['data'][i]['wau']+'</td>'+
                        '<td>'+result['data'][i]['mau']+'</td>'+
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>