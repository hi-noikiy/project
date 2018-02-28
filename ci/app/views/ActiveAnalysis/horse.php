<section id="content">
    <div class="container">
        <div class="block-header">
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
                        <thead id='exth'>
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
               var str = '<tr><th>vip等级</th>';
				for(var j in result.data.horsedata){
					if (!result['data']['horsedata'].hasOwnProperty(j)) continue;
					str += '<th>'+j+'</th>';
				}
				str += '</tr>';
				$('#exth').html(str);
                var table_html = '';
                for(var i in result.data.data){
                	if (!result['data']['data'].hasOwnProperty(i)) continue;
                	table_html += '<tr><td>'+i+'</td>';
                	for(var j in result.data.horsedata){
                		if (!result['data']['horsedata'].hasOwnProperty(j)) continue;
                		if (result.data.data[i].hasOwnProperty(j)){
                			table_html += '<td>'+result.data.data[i][j]+'</td>';
                    	}else{
                    		table_html += '<td>0</td>';
                        }
    				}
                	 '</tr>';
                 }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
