<div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th>精灵ID</th>
                            <th>精灵名字</th>
                            <th>0-10级</th>
                            <th>11-20级</th>
                            <th>21-30级</th>
                            <th>31-40级</th>
                            <th>41-50级</th>
                            <th>51-60级</th>
                            <th>61-70级</th>
                            <th>71-80级</th>
                            <th>81-90级</th>
                            <th>91级以上</th>
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
$.get('FixchangeDetail',param,function(json){
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
             	'<td>'+result['data'][i]['param']+'</td>' +
                 '<td>'+result['data'][i]['paramName']+'</td>' +
                 '<td>'+result['data'][i]['level_1']+'</td>' +
                 '<td>'+result['data'][i]['level_2']+'</td>' +
                 '<td>'+result['data'][i]['level_3']+'</td>' +
                 '<td>'+result['data'][i]['level_4']+'</td>' +
                 '<td>'+result['data'][i]['level_5']+'</td>' +
                 '<td>'+result['data'][i]['level_6']+'</td>' +
                 '<td>'+result['data'][i]['level_7']+'</td>' +
                 '<td>'+result['data'][i]['level_8']+'</td>' +
                 '<td>'+result['data'][i]['level_9']+'</td>' +
                 '<td>'+result['data'][i]['level_10']+'</td>' +
                 '</tr>';
         }
         $("#dataTableframe").html(table_html);
     }
});
</script>
