<div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th>玩家名</th>
                            <th>区服</th>
                            <th>账号id</th>
                            <th>玩家id</th>
                            <th>vip等级</th>
                            <th></th>
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
function eudemondetail(accountid,serverid){
	parent.layer.open({
		  type: 2,
		  title: '精灵详细',
		  maxmin: true,
		  shadeClose: true, //点击遮罩关闭层
		  area : ['800px' , '520px'],
		  content: '../frame/eudemonDetail?accountid='+accountid+'&serverid='+serverid
		  });
}
function gamedetail(accountid,serverid){
	parent.layer.open({
		  type: 2,
		  title: '对战数据',
		  maxmin: true,
		  shadeClose: true, //点击遮罩关闭层
		  area : ['800px' , '520px'],
		  content: '../frame/gameDetail?accountid='+accountid+'&serverid='+serverid
		  });
}
var param = $("#search_form", window.parent.document).serialize();
param  += "&ranklev=<?php echo $_GET['ranklev'];?>";
var index = layer.load();
$.get('danuserDetail',param,function(json){
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
         	'</td><td>'+result.data[i]['account_id']+
         	'</td><td>'+result.data[i]['playerid']+
         	'</td><td>'+result.data[i]['vip_level']+
         	"</td><td><a href='javascript:eudemondetail("+result.data[i]['account_id']+','+result.data[i]['serverid']+")'>精灵养成</a> <a href='javascript:gamedetail("+result.data[i]['account_id']+','+result.data[i]['serverid']+")'>对战数据</a>"+
         	'</td></tr>';
          }
         $("#dataTableframe").html(table_html);
     }
});
</script>
