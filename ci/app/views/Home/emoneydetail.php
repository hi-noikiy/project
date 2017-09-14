<div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th>服务器编号</th>
                            <th>日期</th>
                            <th>剩余钻石</th>
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
//$('#id', window.parent.document)
	var logdate = "";
function rankdetail(serverid){
	parent.layer.open({
		  type: 2,
		  title: serverid+'前50名玩家',
		  maxmin: true,
		  shadeClose: false, //点击遮罩关闭层
		  area : ['600px' , '420px'],
		  content: '../frame/emoneyRank?logdate='+<?php echo $_GET['logdate'];?>+"&serverid="+serverid
		  });
}
var param = $("#search_form", window.parent.document).serialize();
param  += "&logdate=<?php echo $_GET['logdate'];?>";
var index = layer.load();
$.get('emoneyDetail',param,function(json){
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
             	'<td>'+result['data'][i]['serverid']+'</td>' +
                 '<td>'+result['data'][i]['logdate']+'</td>' +
                 '<td>'+result['data'][i]['allemoney']+'</td>' +
                 '<td><a href="javascript:rankdetail('+result['data'][i]['serverid']+')">前50名玩家</a></td>' +
                 '</tr>';
         }
         $("#dataTableframe").html(table_html);
     }
});
</script>
