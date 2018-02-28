<div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th><?php if($_GET['show'] == 2){ ?>VIP<?php }?>等级</th>
                            <th>行为名称</th>
                            <th>玩家数量</th>
                            <th>行为次数</th>
                            <th>无行为玩家数量</th>
                            <th>活跃人数</th>
                            <th>参与人数占比</th>
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
param  += "&act_id=<?php echo $_GET['actid'];?>&show=<?php echo $_GET['show'];?>";
var index = layer.load();
$.get('ActionDetail',param,function(json){
	 layer.close(index);
	var result = JSON.parse(json);
	 if (result) {
         if (result.status!='ok') {
             $("#dataTableframe").html('');
             notify(result.info);
             return false;
         }
         //console.log(result.data);

         var table_html = '',
             len = result.data.length;

         for (var i in result.data) {
             table_html += '<tr>' +
             	'<td>'+result['data'][i]['level']+'</td>' +
                 '<td>'+result['name']+'</td>' +
                 '<td>'+result['data'][i]['cuid']+'</td>' +
                 '<td>'+result['data'][i]['cid']+'</td><td>' ;
                 if((parseInt(result['logindata'][result['data'][i]['level']])-parseInt(result['data'][i]['cuid']))>0){
                	 table_html+=(parseInt(result['logindata'][result['data'][i]['level']])-parseInt(result['data'][i]['cuid']));
                     }else{
                    	 table_html+=0;
                         }

                 
                 table_html+='</td>'+'<td>'+result['data'][i]['active']+'</td>'+'<td>'+result['data'][i]['rate']+'</td>'+' </tr>';
         }
         $("#dataTableframe").html(table_html);
     }
});
</script>
