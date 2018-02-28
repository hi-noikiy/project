<div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th>VIP等级</th>
                            <th>行为1次数</th>
                            <th>行为2次数</th>
                            <th>行为3次数</th>
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
param  += "&act_id=<?php echo $_GET['actid'];?>";
var index = layer.load();
$.get('niuDetail',param,function(json){
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
             	'<td>'+result['data'][i]['vip_level']+'</td>' +
                 '<td>'+result['data'][i]['p1']+'</td>' +
                 '<td>'+result['data'][i]['p2']+'</td>' +
                 '<td>'+result['data'][i]['p3']+'</td>' +
                 '</tr>';
         }
         $("#dataTableframe").html(table_html);
     }
});
</script>
