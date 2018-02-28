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
                            <th>活跃玩家</th>
                            <th>制作人数</th>
                            <th>精炼人数</th>
                            <th>平均制作次数</th>
                            <th>平均精炼次数</th>   
                            <th>平均付费次数</th>  
                            <th>平均付费钻石</th>                   
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
                	if(isNaN(result.data[j]['caccount'])){
                		result.data[j]['caccount'] = 0;
                    }
                	if(isNaN(result.data[j]['126'])){
                		result.data[j]['126'] = 0;
                    }
                	if(isNaN(result.data[j]['127'])){
                		result.data[j]['127'] = 0;
                    }
                	if(isNaN(result.data[j]['126a'])){
                		result.data[j]['126a'] = 0;
                    }
                	if(isNaN(result.data[j]['127a'])){
                		result.data[j]['127a'] = 0;
                    }
                	if(isNaN(result.data[j]['avgcount'])){
                		result.data[j]['avgcount'] = 0;
                    }
                	if(isNaN(result.data[j]['avgnum'])){
                		result.data[j]['avgnum'] = 0;
                    }
                	table_html += '<tr><td>'+j+'</td>'+
                	 '<td>'+result.data[j]['caccount']+'</td>'+
                	 '<td>'+result.data[j]['126']+'</td>'+
                	 '<td>'+result.data[j]['127']+'</td>'+
                	 '<td>'+result.data[j]['126a']+'</td>'+
                	 '<td>'+result.data[j]['127a']+'</td>'+
                	 '<td>'+result.data[j]['avgcount']+'</td>'+
                	 '<td>'+result.data[j]['avgnum']+'</td>'+
                	 '</tr>';
                 }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
