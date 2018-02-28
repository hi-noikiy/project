<div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th>玩家名</th>
                            <th>区服</th>
                            <th>精灵名</th>
                            <th>亲密等级</th>
                            <th>图鉴等级</th>
                            <th>努力总值</th>
                            <th>个体总值</th>
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
var param = $("#search_form", window.parent.document).serialize();
param  += "&serverid=<?php echo $_GET['serverid'];?>&accountid=<?php echo $_GET['accountid'];?>";
var index = layer.load();
$.get('eudemonDetail',param,function(json){
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
         	table_html += '<tr><td>'+result.data[i]['name']+
         	'</td><td>'+result.data[i]['serverid']+
         	'</td><td>'+result.data[i]['eud']+
         	'</td><td>'+result.data[i]['intilv']+
         	'</td><td>'+result.data[i]['booklv']+
         	'</td><td>'+result.data[i]['ex2']+
         	'</td><td>'+result.data[i]['ex1']+
         	'</td></tr>';
          }
         $("#dataTableframe").html(table_html);
     }
});
</script>
