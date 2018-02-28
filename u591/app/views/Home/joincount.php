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
                            <th>行为类型</th>
                            <th>行为参数</th>
                            <th><span class='id0'></span>行为次数</th>
                            <th><span class='id0'></span>参与人数</th>
                            <th><span class='id1'></span>行为次数</th>
                            <th><span class='id1'></span>参与人数</th>
                            <th><span class='id2'></span>行为次数</th>
                            <th><span class='id2'></span>参与人数</th>
                            <th><span class='id3'></span>行为次数</th>
                            <th><span class='id3'></span>参与人数</th>
                            <th><span class='id4'></span>行为次数</th>
                            <th><span class='id4'></span>参与人数</th>
                            <th><span class='id5'></span>行为次数</th>
                            <th><span class='id5'></span>参与人数</th>
                            <th><span class='id6'></span>行为次数</th>
                            <th><span class='id6'></span>参与人数</th>
                            <th></th>
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
function showdetail(actid,param,type){
	layer.open({
		  type: 2,
		  title: type+'详细',
		  maxmin: true,
		  shadeClose: true, //点击遮罩关闭层
		  area : ['800px' , '520px'],
		  content: '../frame/joinDetail?act_id='+actid+'&param='+param+'&type='+type
		  });
}
    var dataOption = {
        title:'',
        autoload: false,
        request_url:'<?php echo site_url('Home/joincount');?>',
        callback: function (result) {
            if (result) {
                if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify(result.info);
                    return false;
                }
                for(var j=0;j<7;j++){
                	$('.id'+j).html(result['dates'][j]);
                }
                var table_html = '',
                    len = result.data.length;
                for (var i in result.data) {
                	if(result['data'].hasOwnProperty(i)){
                		for(var x in result.data[i]){
                			if(result['data'][i].hasOwnProperty(x)){
                    			table_html += '<tr>' +
                                '<td>'+result['data'][i][x]['actname']+'</td>' +
                                '<td>'+result['data'][i][x]['paramname']+'</td>';
                                for(var j=0;j<7;j++){
                                	if(!isNaN(result['data'][i][x]['act_count_'+result['dates'][j]])){
                                		table_html +='<td>'+result['data'][i][x]['act_count_'+result['dates'][j]]+'</td>';
                                    	}else{
                                    		table_html +='<td>0</td>';
                                     }
                                	if(!isNaN(result['data'][i][x]['act_account_'+result['dates'][j]])){
                                		table_html +='<td>'+result['data'][i][x]['act_account_'+result['dates'][j]]+'</td>';
                                    	}else{
                                    		table_html +='<td>0</td>';
                                     }
                                 }
                                table_html +='<td><a href="javascript:showdetail('+result['data'][i][x]['act_id']+','+result['data'][i][x]['param']+',\'vip\')">vip</a>'+
                                ' <a href="javascript:showdetail('+result['data'][i][x]['act_id']+','+result['data'][i][x]['param']+',\'server\')">区服</a></td></tr>';
                    			}
                        }
                	}
                    
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
