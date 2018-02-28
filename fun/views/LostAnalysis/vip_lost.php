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
                            <th>1日流失数(率)</th>
                            <th>3日流失数(率)</th>
                            <th>7日流失数(率)</th>
                            <th>14日流失数(率)</th>
                            <th>30日流失数(率)</th>
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
        title:'会员等级流失',
        request_url:'<?php echo site_url('LostAnalysis/active?data_type=1');?>',
        callback: function(result) {
            var table_html = '';
            for (var i in result['rawLostData']) {
                //console.log(i);
                table_html += "<tr>";
                table_html += "<td data-type='numeric'>"+ i +"</td>";
                table_html += "<td data-type='numeric'>"+result['rawLostData'][i]['lost_1']+'('+(result['rawLostData'][i]['lost_1']/ result['rawLoginData'][i]).toFixed(3)+')'+"</td>";
                table_html += "<td data-type='numeric'>"+result['rawLostData'][i]['lost_3']+'('+(result['rawLostData'][i]['lost_3']/ result['rawLoginData'][i]).toFixed(3)+')'+"</td>";
                table_html += "<td data-type='numeric'>"+result['rawLostData'][i]['lost_7']+'('+(result['rawLostData'][i]['lost_7']/ result['rawLoginData'][i]).toFixed(3)+')'+"</td>";
                table_html += "<td data-type='numeric'>"+result['rawLostData'][i]['lost_14']+'('+(result['rawLostData'][i]['lost_14']/ result['rawLoginData'][i]).toFixed(3)+')'+"</td>";
                table_html += "<td data-type='numeric'>"+result['rawLostData'][i]['lost_30']+'('+(result['rawLostData'][i]['lost_30']/ result['rawLoginData'][i]).toFixed(3)+')'+"</td>";
                table_html += "</tr>";

            }
            $("#dataTable").html(table_html);
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/player_lost_detail.js"></script>