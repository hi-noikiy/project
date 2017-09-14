<section id="content">
    <div class="container">
        <div class="block-header">
            <h2><?php echo $page_title;?></h2>
        </div>
        <div class="col-md-12">
            <div class="card">
                <?php echo $search_form;?>
                <div id="echart" style="width: 100%;height:400px;"></div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th>日期</th>
                            <th>7日回流人数</th>
                            <th>14日回流人数</th>
                            <th>30日回流人数</th>
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
        title:'活跃玩家每日回流',
        request_url:'<?php echo site_url('LostAnalysis/activeBack');?>',
        callback: function(result) {
            var table_html = '';
            var cnt = result.xAxis.length, types = result.series.length;
            for (var i=0; i<cnt; i++) {
                table_html += "<tr>";
                table_html += "<td>"+result.xAxis[i]+"</td>";
                for (var j=0; j<types;j++) {
                    table_html += "<td data-type='numeric'>"+result['series'][j]['data'][i]+"</td>";
                    //table_html += "<td></td>";
                }
                table_html += "</tr>"
            }
            $("#dataTable").html(table_html);
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/player_lost_detail.js"></script>