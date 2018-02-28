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
                        <thead id="headerTitle"></thead>
                        <tbody id="dataTable"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    var dataOption = {
        title:'',
        autoload: true,
        request_url:'<?php echo site_url('GameAnalysis/RiskLost');?>',
        callback: function (result) {
            if (result) {
                if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify("客官,不好意思，没有查到数据!");
                    return false;
                }
                //console.log(result.data);

                var table_html = table_header = '',
                    len = result.data.length;
                table_header += '<tr><th>冒险关卡ID</th>';
                for (var d in result.datelist) {
                    if (isNaN(d)) continue;
                    //console.log(result.datelist[d])
                    table_header += '<th>'+result.datelist[d]+'</th>';
                }
                for (var wn in result.wanted_normal) {
                    if (isNaN(wn)) continue;
                    var idx = result.wanted_normal[wn];
                    table_html += '<tr><td>'+result.wanted_normal[wn]+'</td>';
                    for (var date in result.data) {
                        //console.log(idx);
                        //console.log(result.data[date]);
                        table_html +='<td>'+(result.data[date][idx] ? result.data[date][idx] : '0' )+'</td>';
                    }
                    table_html += '</tr>';
                }
                table_header  += '</tr>';
                $("#headerTitle").html(table_header);
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
