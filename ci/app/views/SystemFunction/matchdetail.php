<div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th><?php if($_GET['type']==1){ ?>段位<?php }else{ ?>区服<?php } ?></th>
                            <th>人次</th>
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
param  += "&time=<?php echo $_GET['time'];?>&type=<?php echo $_GET['type'];?>";
var index = layer.load();
$.get('matchDetail',param,function(json){
	 layer.close(index);
	var result = JSON.parse(json);
	 if (result) {
         if (result.status!='ok') {
             $("#dataTableframe").html('');
             notify(result.info);
             return false;
         }
         //console.log(result.data);

         var table_html = '';

         for(var i in result.data){
         	if (!result['data'].hasOwnProperty(i)) continue;
         	table_html += '<tr><td>'+result.data[i]['showa']+'</td><td>'+result.data[i]['c']+'</td></tr>';
          }
         $("#dataTableframe").html(table_html);
     }
});
</script>
