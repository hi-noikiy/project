<div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th>玩家id</th>
                            <th>区服</th>
                             <th>玩家名</th>
                                <th>vip等级</th>
                               <th>等级</th>
                               
                                   <th>排名</th>
                            
                           
                        </tr>
                        </thead>
                        <tbody id="dataTableframe">
                        </tbody>
                    </table>
                      </div>
<script src="/public/ma/js/jquery.min.js"></script>
<script src="/public/ma/js/layer.js"></script>

<script>
//$('#id', window.parent.document)

var param = $("#search_form", window.parent.document).serialize();
param  += "&serverid=<?php echo $_GET['serverid'];?>";

var index = layer.load();
$.get('sixteen',param,function(json){
	 layer.close(index);

		var result = JSON.parse(json);
		 if (result) {
	         if (result.status!='ok') {
	             $("#dataTableframe").html('');
	             notify(result.info);
	             return false;
	         }
	         
		 //    console.log(result.data);

	         var table_html = '',
	             len = result.data.length;

	         for (var i in result.data) {
	             table_html += '<tr>' +
	             '<td>'+result['data'][i]['accountid']+'</td>' +	 
	             '<td>'+result['data'][i]['serverid']+'</td>' +	     
	             '<td>'+result['data'][i]['username']+'</td>' +	        
	             '<td>'+result['data'][i]['vip_level']+'</td>' +	   
	             '<td>'+result['data'][i]['user_level']+'</td>' +	  
	         
	             '<td>'+result['data'][i]['param1']+'</td>' +	   
	             
	                 '</tr>';
	         }
	         $("#dataTableframe").html(table_html);
	     }
	});
</script>
