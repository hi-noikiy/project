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
                            <th>创建角色总数</th>
                            <th>流失人数</th>
                            <th>留失率</th>
                            <th>流失玩家总付费</th>
                            <th>流失玩家平均付费</th>
                             <th>流失玩家平均生命周期</th>
 
                             
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
        autoload: false,
        request_url:'<?php echo site_url('DataAnalysis/lifePeriod');?>',
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
                        '<td>'+result['data'][i]['viplev']+'</td>' +
                        '<td>'+result['data'][i]['total_role']+'</td>' +
                        '<td>'+result['data'][i]['leave_num']+'</td>' +
                        '<td>'+result['data'][i]['leave_percent']+'</td>' +
                        '<td>'+result['data'][i]['total_pay']+'</td>' +
                        '<td>'+result['data'][i]['avg_pay']+'</td>' +
                        '<td>'+result['data'][i]['avg_period']+'</td>' +
             
  
                  
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };




    
</script>


<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
