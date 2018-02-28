<div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr id='mytr'>
                            
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
param  += "&type=<?php echo $_GET['type'];?>";
var index = layer.load();
$.get('vipDetail',param,function(json){
	 layer.close(index);
	var result = JSON.parse(json);
	 if (result) {
         if (result.status!='ok') {
             $("#dataTableframe").html('');
             notify(result.info);
             return false;
         }
         
         var mytr = "<th>行为类型</th>";
         for(var i in result.vips){
        	 if (!result['vips'].hasOwnProperty(i)) continue;
        	 mytr +='<th>vip'+i+'</th>';
         }
         $('#mytr').html(mytr);
         var table_html = '';
         for (var i in result.acts) {
        	 if (!result['acts'].hasOwnProperty(i)) continue;
             table_html += '<tr>' +
                 '<td>'+result['acts'][i]+'</td>';
                 for(var j in result.vips){
                	 if (!result['vips'].hasOwnProperty(j)) continue;
                	 if (result['data'].hasOwnProperty(i+'_'+j)){
                 		table_html +='<td>'+result['data'][i+'_'+j]+'</td>';
                     	}else{
                     		table_html +='<td>0</td>';
                      }
                  }
                 table_html +='</tr>';
         }
         $("#dataTableframe").html(table_html);
     }
});
</script>
