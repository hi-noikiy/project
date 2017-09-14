<div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th>玩家名</th>
                            <th>区服</th>
                            <th>账号id</th>
                            <th>玩家id</th>
                            <th>vip等级</th>
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
param  += "&vip_status=<?php echo $_GET['vip_status'];?>&title=<?php echo $_GET['title'];?>";
var index = layer.load();
$.get('processDetail',param,function(json){
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
         	table_html += '<tr><td>'+result.data[i]['name']+'</td><td>'+result.data[i]['serverid']+'</td><td>'+result.data[i]['account_id']+
			'</td><td>'+result.data[i]['player_id']+
			'</td><td>'+result.data[i]['vip_level']+
         	'</td></tr>';
          }
         $("#dataTableframe").html(table_html);
     }
});
</script>
