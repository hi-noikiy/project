<div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr id='mytr'>
                            <th><?php echo $_GET['type'];?></th>
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
                        </tr>
                        </thead>
                        <tbody id="dataTableframe">
                        </tbody>
                    </table>
                                        </div>
<script src="/public/ma/js/jquery.min.js"></script>
<script src="/public/ma/js/layer.js"></script>
<script src="/public/ma/js/functions.js"></script>
<script>
//$('#id', window.parent.document)
var param = $("#search_form", window.parent.document).serialize();
param  += "&act_id=<?php echo $_GET['act_id'];?>&param=<?php echo $_GET['param'];?>&type=<?php echo $_GET['type'];?>";
var type = "<?php echo $_GET['type'];?>",firstshow;
var index = layer.load();
$.get('joinDetail',param,function(json){
	 layer.close(index);
	var result = JSON.parse(json);
	 if (result) {
         if (result.status!='ok') {
             $("#dataTableframe").html('');
             notify(result.info);
             return false;
         }
         
         if(type == 'server'){
        	 firstshow = 'serverid';
             }else{
            	 firstshow = 'vip_level';
                 }
         var mytr = "<th>"+type+"</th>";
         for(var i in result.dates){
        	 if (!result['dates'].hasOwnProperty(i)) continue;
        	 mytr +='<th>'+result.dates[i]+'行为次数</th> <th>'+result.dates[i]+'参与人数</th>';
         }
         $('#mytr').html(mytr);
         var table_html = '',
             len = result.data.length;
         for (var i in result.data) {
             table_html += '<tr>' +
                 '<td>'+result['data'][i][firstshow]+'</td>';
                 for(var j in result.dates){
                	 if(!isNaN(result['data'][i]['act_count_'+result['dates'][j]])){
                 		table_html +='<td>'+result['data'][i]['act_count_'+result['dates'][j]]+'</td>';
                     	}else{
                     		table_html +='<td>0</td>';
                      }
                 	if(!isNaN(result['data'][i]['act_account_'+result['dates'][j]])){
                 		table_html +='<td>'+result['data'][i]['act_account_'+result['dates'][j]]+'</td>';
                     	}else{
                     		table_html +='<td>0</td>';
                      }
                  }
                 table_html +='</tr>';
                 //alert('act_account_'+result['dates'][0]);return false;
         }
         $("#dataTableframe").html(table_html);
     }
});
</script>
