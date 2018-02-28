<div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                        	<th>排名</th>
                            <th>服务器编号</th>
                            <th>账号编号</th>
                            <th>剩余金币</th>
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
param  += "&logdate=<?php echo $_GET['logdate'];?>&serverid=<?php echo $_GET['serverid'];?>";
var index = layer.load();
$.get('moneyRank',param,function(json){
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
                '<td>'+(parseInt(i)+1)+'</td>' +
             	'<td>'+result['data'][i]['serverid']+'</td>' +
                 '<td>'+result['data'][i]['accountid']+'</td>' +
                 '<td>'+result['data'][i]['money']+'</td>' +
                 '</tr>';
         }
         $("#dataTableframe").html(table_html);
     }
});
</script>
