<div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th>副本</th>
                            <th>玩家名</th>
                            <th>区服</th>
                            <th>战力</th>
                            <th>精灵1</th>
                            <th>精灵2</th>
                            <th>精灵3</th>
                            <th>精灵4</th>
                            <th>精灵5</th>
                            <th>精灵6</th>
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
param  += "&template_id=<?php echo $_GET['template_id'];?>";
var index = layer.load();
$.get('squadDetail',param,function(json){
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
        	// if(!isNaN(i))
             	table_html += '<tr>' +
                 '<td>'+result['data'][i]['template_id']+'</td>' +
                 '<td>'+result['data'][i]['username']+'</td>' +
                 '<td>'+result['data'][i]['server_id']+'</td>' +
                 '<td>'+result['data'][i]['totalpower']+'</td>' +
                 '<td>'+result['data'][i]['eud1']+'</td>' +
                 '<td>'+result['data'][i]['eud2']+'</td>' +
                 '<td>'+result['data'][i]['eud3']+'</td>' +
                 '<td>'+result['data'][i]['eud4']+'</td>' +
                 '<td>'+result['data'][i]['eud5']+'</td>' +
                 '<td>'+result['data'][i]['eud6']+'</td>' +
                 '</tr>';
         }
         $("#dataTableframe").html(table_html);
     }
});
</script>
