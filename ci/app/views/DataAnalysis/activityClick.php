<section id="content">
    <div class="container">
        <div class="block-header">
            <h2><?php echo $page_title;?></h2>
        </div>
        <div class="col-md-12">
            <div class="card">
                <?php echo $search_form;?>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>  
                            <th>vip</th>
                            <th> 活跃人数</th>                        
                            <th>点击人数</th>
                            <th>点击次数</th>
                           
                          </tr>
                        </thead>
                        <tbody id="dataTable">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="<?=base_url()?>public/ma/js/layer.js"></script>


<script>
var name;



function areaClickDistribution(itemid,where){
	layer.open({
		  type: 2,
		  title: 'iframe父子操作',
		  maxmin: true,
		  shadeClose: true, //点击遮罩关闭层
		  area : ['800px' , '520px'],
		  content: '../frame/areaClickDistribution?itemid='+itemid+'&show='+where
		  });
}

function vipClickDistribution(itemid,where){
	layer.open({
		  type: 2,
		  title: 'iframe父子操作',
		  maxmin: true,
		  shadeClose: true, //点击遮罩关闭层
		  area : ['800px' , '520px'],
		  content: '../frame/vipClickDistribution?itemid='+itemid+'&show='+where
		  });
}


$("#click_type").change(function()  {    
    var id=$(this).val();    
    var dataString = 'flid=2&id='+ id ; 
        $.ajax ({    
            type: "POST",    
            url: "<?php echo site_url('DataAnalysis/getTypeTwo');?>",    
            data: dataString,    
            cache: false,    
            success: function(html)  {    

                console.log(html);
               $("#click_type_two").html(html);

               }     
        });      
});   
  

	$('.cur').click(function(){
		$(this).children().addClass('curs');
		$(this).siblings().children().removeClass('curs');
		$('#searchtype').val($(this).children().attr('id'));
	});
    var dataOption = {
        title:'',
        autoload: false,
        request_url:'<?php echo site_url('DataAnalysis/activityClick');?>',
        callback: function (result) {
            if (result) {
               /*  if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify(result.info);
                    return false;
                } */
               console.log(result.data);

                var table_html = '',
                    len = result.data.length;
                for (var i in result.data) {
                    if (isNaN(i)) continue;
                    //for (var i=0;i<len; i++) {
                    table_html += '<tr>' +
                    '<td>'+result['data'][i]['viplev']+'</td>' +                          
                       '<td>'+result['data'][i]['c']+'</td>' +
                       '<td>'+result['data'][i]['total_user']+'</td>' +
                       '<td>'+result['data'][i]['total_time']+'</td>' +
                     //  '<td>'+result['data'][i]['text']+'</td>' +
                      
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>




<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
