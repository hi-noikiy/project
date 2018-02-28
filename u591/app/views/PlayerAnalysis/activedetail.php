<div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th>服务器</th>
                            <th>大于5分钟</th>
                            <th>大于120分钟</th>
                            <th>大于500分钟</th>
                            <th>VIP玩家</th>
                            <th>非VIP玩家</th>
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
param  += "&sday=<?php echo $_GET['sday'];?>";
var index = layer.load();
$.get('ActiveDetail',param,function(json){
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
             	'<td>'+result['data'][i]['servername']+'</td>' +
                 '<td>'+result['data'][i]['m1']+'</td>' +
                 '<td>'+result['data'][i]['m2']+'</td>' +
                 '<td>'+result['data'][i]['m3']+'</td>' +
                 '<td>'+result['data'][i]['vip_role']+'</td>' +
                 '<td>'+result['data'][i]['novip']+'</td>' +
                 '</tr>';
         }
         $("#dataTableframe").html(table_html);
     }
});
</script>
