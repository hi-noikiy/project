<section id="content">
    <div class="container">
        <div class="block-header">
            <h2><?php echo $page_title;?></h2>
        </div>
        <div class="col-md-12">
            <div class="card">
                <?php echo $search_form;?>
            </div>
            <div class="card">
                <div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th data-type="numeric">服务器id</th>
                            <th data-type="numeric">当前在线</th>
                            <th data-type="numeric">最高在线</th>
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
        autoload: false,
        title:'',
        request_url:'<?php echo site_url($_request_method);?>',
        callback: function (result) {
            if (result) {
                if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify("客官,不好意思，没有查到数据!");
                    return false;
                }
                var table_html = '';
                for (var idx in result['data']) {
                    if (!result['data'].hasOwnProperty(idx)) continue;
                    table_html += '<tr>' +
                        '<td>'+result['data'][idx]['serverid']+'</td>'+
                        '<td>'+result['data'][idx]['online']+'</td>'+
                        '<td>'+result['data'][idx]['MaxOnline']+'</td>'+
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
