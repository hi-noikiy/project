<div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th>vip</th>
                            <th>分布人数</th>
                            <th>占比</th>
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
param  += "&ranklev=<?php echo $_GET['ranklev'];?>";
var index = layer.load();
$.get('danDetail',param,function(json){
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

         for(var i in result.data){
         	if (!result['data'].hasOwnProperty(i)) continue;
         	table_html += '<tr><td>'+result.data[i]['vip_level']+'</td><td>'+result.data[i]['caccount']+'</td><td>'+result.data[i]['rare']+'%</td></tr>';
          }
         table_html += '<tr><td>总人数</td><td>'+result['allaccount']+'</td><td>100%</td>';
         $("#dataTableframe").html(table_html);
     }
});
</script>
