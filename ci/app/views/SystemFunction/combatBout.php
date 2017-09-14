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
                            <th>战斗回合</th>
                            <th>场次</th>
                            <th></th>
                      
                         
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
<!--<script>-->
<!--    $("#submit").attr('type', 'submit');-->
<!--</script>-->
<script src="<?=base_url()?>public/ma/js/layer.js"></script>
<script>


function dan(continuous,where){
	layer.open({
		  type: 2,
		  title: 'iframe父子操作',
		  maxmin: true,
		  shadeClose: true, //点击遮罩关闭层
		  area : ['800px' , '520px'],
		  content: '../frame/danDistribution?continuous='+continuous+'&show='+where
		  });
}


$('#btype').change(function(){
	if($(this).val()==4){
		$('#gametype').hide();
	}else{
		$('#gametype').show();
	}
});
    var dataOption = {
        title:'',
        autoload: false,
        request_url:'<?php echo site_url('SystemFunction/combatBout');?>',
        callback: function (result) {
            if (result) {
                if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify(result.info);
                    return false;
                }
                //console.log(result.data);

/*             var table_html = '<tr>' +
                '<td>队伍数量</td>' +
                '<td>'+result['cgid']+'</td>' +
                '<td>100%</td>' +
                 '</tr>'; */
                  //  len = result.data.length;
                 var table_html='';
                if(result['data']!='')
                for(var i in result['data']){
                    if(!isNaN(i)){
                    	table_html += '<tr>' +
                        '<td>'+result['data'][i]['continuous']+'</td>' +
                        '<td>'+result['data'][i]['cid']+'</td>' +
                        '<td>'+result['data'][i]['text']+'</td>' +
       
           
                        '</tr>';
                        }
                	
                    }
                
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
