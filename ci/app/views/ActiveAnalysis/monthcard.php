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
                            <th>普通月卡人数</th>
                            <th>vip占比</th>        
                            <th>狩猎人数</th>
                            <th>vip占比</th>        
                            <th>终身月卡人数</th>
                            <th>vip占比</th>     
                            <th>vip人数</th>          
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
        request_url:'<?php echo site_url($_request_method);?>',
        callback: function (result) {
            if (result) {
                if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify(result.info);
                    return false;
                }
                //console.log(result.data);

                var table_html = '';
                for(var j in result.data){
                	if (!result['data'].hasOwnProperty(j)) continue;
                	table_html += '<tr><td>'+result.data[j]['vip_level']+'</td>'+
                	 '<td>'+result.data[j]['susual']+'</td>'+
                	 '<td>'+result.data[j]['susualrate']+'%</td>'+
                	 '<td>'+result.data[j]['shunting']+'</td>'+
                	 '<td>'+result.data[j]['shuntingrate']+'%</td>'+
                	 '<td>'+result.data[j]['slifetime']+'</td>'+
                	 '<td>'+result.data[j]['slifetimerate']+'%</td>'+
                	 '<td>'+result.data[j]['caccount']+'</td>'+
                	 '</tr>';
                 }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
