<div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th></th>
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
param  += "&id=<?php echo $_GET['id'];?>+&images=<?php echo $_GET['images'];?>";

var index = layer.load();
$.get('imageDetail',param,function(json){
	 layer.close(index);

		var result = JSON.parse(json);
		 if (result) {
	         if (result.status!='ok') {
	             $("#dataTableframe").html('');
	             notify(result.info);
	             return false;
	         }
	         
	/* 	  if(result['data']['images']){
			   var table_html = '',
	             len = result.data.length;

	         table_html += '<tr>' +      
               '<td><img src="'+result['data']['images']+'" height="450px"></td>' +	    
               '<td><textarea rows="10" cols="50">'+result['data']['status']+'</textarea><button>提交</button></td>' +	
               '</tr>';
			  } else { */
				   var table_html = '',
		             len = result.data.length;

		         table_html += '<tr>' +      
	                 '<td>'+result['data']['content']+'</td>' +	    
	                 '<td><textarea rows="10" cols="50">'+result['data']['status']+'</textarea><button>提交</button></td>' +	
	                 '</tr>';

				//  }

	      


	         $("#dataTableframe").html(table_html);
	     }
	});
</script>
