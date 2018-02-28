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
$.get('imageMultiple',param,function(json){
	 layer.close(index);

		var result = JSON.parse(json);
		 if (result) {
	         if (result.status!='ok') {
	             $("#dataTableframe").html('');
	             notify(result.info);
	             return false;
	         }

	  	   var table_html = '',
           len = result.data.length;
	         for (var i in result.data) {
                 if (isNaN(i)) continue;
		

	         table_html += '<tr>' +      
               '<td><img src="'+result['data'][i]['image']+'" ></td>' +	    
               
               '</tr>';
			
	         }
	      


	         $("#dataTableframe").html(table_html);
	     }
	});
</script>
