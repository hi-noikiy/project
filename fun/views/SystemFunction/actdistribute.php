<div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th style="width: 200px;">行为类型 </th>
                            <th>消耗/获得钻石</th>
                       
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
param  += "&vip_level=<?php echo $_GET['vip_level'];?>&type=<?php echo $_GET['type'];?>";
var index = layer.load();
$.get('actDistribute',param,function(json){
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
             '<td>'+result['data'][i]['act_id']+'</td>' +
             '<td>'+result['data'][i]['total_item']+'</td>' +
   
                 '</tr>';
         }
         $("#dataTableframe").html(table_html);
     }
});
</script>
