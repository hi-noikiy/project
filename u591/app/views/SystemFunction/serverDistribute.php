<div class="table-responsive">
	<table id="data-table-basic" class="table table-striped">
		<thead>
			<tr>

				<th>区服</th>
				<th>充值金额</th>
				<th>帐号数</th>
				<th>充值条数</th>
				<th>arppu</th>
				<th>arpu</th>
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
param  += "&day=<?php echo $_GET['day'];?>&show=<?php echo $_GET['show'];?>";
var index = layer.load();
$.get('serverDistribute',param,function(json){
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
             '<td>'+result['data'][i]['serverid']+'</td>' +                
             '<td>'+result['data'][i]['allmoney']+'</td>' +
             '<td>'+result['data'][i]['countAccountid']+'</td>' +
             '<td>'+result['data'][i]['count']+'</td>' +
             '<td>'+result['data'][i]['arppu']+'</td>' +
             '<td>'+result['data'][i]['arpu']+'</td>' +
                 '</tr>';
         }
         $("#dataTableframe").html(table_html);
     }
});
</script>
