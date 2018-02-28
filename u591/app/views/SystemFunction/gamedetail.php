<div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th>对战结束时间</th>
                            <th>类型</th>
                            <th>区服</th>
                            <th>玩家名</th>
                            <th>胜负</th>
                            <th>段位</th>
                            <th>vip等级</th>
                            <th>用户等级</th>
                            <th>持续回合</th>
                            <th>精灵名字</th>
                            <th>存活情况</th>
                            <th>剩余血量</th>
                            <th>出招次数</th>
                            <th>技能1</th>
                            <th>技能2</th>
                            <th>技能3</th>
                            <th>技能4</th>
                            <th>特性</th>
                            <th>树果</th>
                            <th>装备</th>
                            <th>性格</th>
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
$.get('gameDetail',param,function(json){
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
         	table_html += '<tr><td>'+result.data[i]['endTime']+
         	'</td><td>'+result.data[i]['type']+
         	'</td><td>'+result.data[i]['serverid']+
         	'</td><td>'+result.data[i]['name']+
         	'</td><td>'+result.data[i]['ustatus']+
         	'</td><td>'+result.data[i]['dan']+
         	'</td><td>'+result.data[i]['viplevel']+
         	'</td><td>'+result.data[i]['level']+
         	'</td><td>'+result.data[i]['continuous']+
         	'</td><td>'+result.data[i]['eudemon']+
         	'</td><td>'+result.data[i]['estatus']+
         	'</td><td>'+result.data[i]['hp']+
         	'</td><td>'+result.data[i]['allpp']+
         	'</td><td>'+result.data[i]['skills1']+
         	'</td><td>'+result.data[i]['skills2']+
         	'</td><td>'+result.data[i]['skills3']+
         	'</td><td>'+result.data[i]['skills4']+
         	'</td><td>'+result.data[i]['abilities']+
         	'</td><td>'+result.data[i]['fruit']+
         	'</td><td>'+result.data[i]['equip']+
         	'</td><td>'+result.data[i]['kidney']+
         	'</td></tr>';
          }
         $("#dataTableframe").html(table_html);
     }
});
</script>
