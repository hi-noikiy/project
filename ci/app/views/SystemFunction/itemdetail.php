<div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th>行为类型</th>
                            <th>物品id</th>
                            <th>物品名称</th>
                            <th>获得数量</th>
                            <th>消耗数量</th>
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
param  += "&itemid=<?php echo $_GET['itemid'];?>&show=<?php echo $_GET['show'];?>";
var index = layer.load();
$.get('ItemDetail',param,function(json){
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
             	'<td>'+result['types'][result['data'][i]['showid']]+'</td>' +
                 '<td>'+result['data'][i]['item_id']+'</td>' +
                 '<td>'+result['name']+'</td>' +
                 '<td>'+result['data'][i]['getnum']+'</td>' +
                 '<td>'+result['data'][i]['consumenum']+'</td>' +
                 '</tr>';
         }
         $("#dataTableframe").html(table_html);
     }
});
</script>
