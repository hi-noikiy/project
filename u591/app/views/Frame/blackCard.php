<div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th>mac</th>
                            <th>帐号</th>
                               <th>区服</th>
                                <th>角色id</th>
                                 <th></th>
                            
                           
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
param  += "&para=<?php echo $_GET['para'];?>";

var index = layer.load();
$.get('blackCard',param,function(json){
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
	             '<td>'+result['data'][i]['mac']+'</td>' +	 
	             '<td>'+result['data'][i]['accountid']+'</td>' +	
	             '<td>'+result['data'][i]['serverid']+'</td>' +	          
	             '<td>'+result['data'][i]['userid']+'</td>' +	        
	                 '</tr>';
	         }
	         $("#dataTableframe").html(table_html);
	     }
	});
</script>
