<div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                        	<th>类型</th>
                            <th>掉线时间</th>
                            <th>区服</th>
                            <th>玩家id</th>
                            <th>账号id</th>
                            <th>玩家名</th>
                            <th>社团id</th>
                            <th>社团职位</th>
                            <th>渠道</th>
                            <th>客户端版本</th>
                            <th>手机类型</th>
                            <th>系统</th>
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
param  += "&btype=<?php echo $_GET['btype'];?>";
var index = layer.load();
$.get('DropsDetail',param,function(json){
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

         for(var i in result['data']){
             if(!isNaN(i))
         	table_html += '<tr>' +
         	'<td>'+result['data'][i]['btypename']+'</td>' +
             '<td>'+result['data'][i]['create_time']+'</td>' +
             '<td>'+result['data'][i]['serverid']+'</td>' +
             '<td>'+result['data'][i]['userid']+'</td>' +
             '<td>'+result['data'][i]['accountid']+'</td>' +
             '<td>'+result['data'][i]['name']+'</td>' +
             '<td>'+result['data'][i]['communityid']+'</td>' +
             '<td>'+result['data'][i]['composition']+'</td>' +
             '<td>'+result['data'][i]['channel']+'</td>' +
             '<td>'+result['data'][i]['client_version']+'</td>' +
             '<td>'+result['data'][i]['client_type']+'</td>' +
             '<td>'+result['data'][i]['sys']+'</td>' +
             '</tr>';
             }
         $("#dataTableframe").html(table_html);
     }
});
</script>
