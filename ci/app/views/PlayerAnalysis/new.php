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
                            <th data-column-id="id" data-type="numeric">日期</th>
                            <th data-column-id="received" data-order="desc">账号注册</th>
                            <th data-column-id="sender">安装解压</th>
                        </tr>
                        </thead>
                        <tbody id="dataTable">
                        <!--<tr>-->
                        <!--    <td>10238</td>-->
                        <!--    <td>eduardo@pingpong.com</td>-->
                        <!--    <td>14.10.2013</td>-->
                        <!--</tr>-->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    var dataOption = {
        title:'新增玩家',
        request_url:'<?php echo site_url('PlayerAnalysis/getNewPlayerData');?>',
        callback : function(result){
            if (result['status']=='fail') {
                $("#dataTable").empty();
                return false;
            }
            var tr = '';
            for (var i in result['raw']) {
                if (isNaN(i)) continue;
                tr += '<tr><td>' + i + '</td>'
                    + '<td>' + result['raw'][i]['register']+ '</td>'
                    + '<td>' + result['raw'][i]['device']+ '</td>'
                    + '</tr>';
            }
            $("#dataTable").html(tr);
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/player_analysis_data.js"></script>