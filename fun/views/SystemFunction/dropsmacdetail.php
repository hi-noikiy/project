<div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th>机型</th>
                            <th>人数</th>
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
$.get('DropsmacDetail',param,function(json){
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
             '<td>'+result['data'][i]['client_type']+'</td>' +
             '<td>'+result['data'][i]['caccount']+'</td>' +
             '</tr>';
             }
         $("#dataTableframe").html(table_html);
     }
});
</script>
