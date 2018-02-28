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
                            <th>项目</th>
                            <th>0-10</th>
                            <th>11-20</th>
                            <th>21-30</th>
                            <th>31-40</th>
                            <th>41-50</th>
                            <th>51-60</th>
                            <th>61-70</th>
                            <th>71-80</th>
                            <th>81-90</th>
                            <th>91-100</th>
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
function showdetail(act_id,param){
	layer.open({
		  type: 2,
		  title: '玩家详细',
		  maxmin: true,
		  shadeClose: true, //点击遮罩关闭层
		  area : ['800px' , '520px'],
		  content: '../frame/towerDetail?act_id='+act_id+'&param='+param
		  });
}



function ajaxRequest(btype){
	var param = $("#search_form").serialize();
	param  += "&btype="+btype;
	var index = layer.load();
    $.ajax({
        type : "get",
        async : true, //同步执行
        url : '<?php echo site_url('SystemFunction/fairyTower');?>',
        data : param,
        dataType : "json", //返回数据形式为json
        before: function(){
            //myChart.showLoading();
        },
        success : function(result) {
        	 layer.close(index);
        	if (result.status!='ok') {
                return false;
            }
        	var table_html = '';
        	if(result['data']!='')
                for(var i in result['data']){
                	   if(!isNaN(i))    {                	
                	       if(typeof(result['data'][i]['c1'])=='undefined'){result['data'][i]['c1']=0;}
                       	  if(typeof(result['data'][i]['c2'])=='undefined'){result['data'][i]['c2']=0;}
                     	  if(typeof(result['data'][i]['c3'])=='undefined'){result['data'][i]['c3']=0;}
                     	  if(typeof(result['data'][i]['c4'])=='undefined'){result['data'][i]['c4']=0;}
                     	  if(typeof(result['data'][i]['c5'])=='undefined'){result['data'][i]['c5']=0;}
                     	  if(typeof(result['data'][i]['c6'])=='undefined'){result['data'][i]['c6']=0;}
                     	  if(typeof(result['data'][i]['c7'])=='undefined'){result['data'][i]['c7']=0;}
                     	  if(typeof(result['data'][i]['c8'])=='undefined'){result['data'][i]['c8']=0;}
                     	  if(typeof(result['data'][i]['c9'])=='undefined'){result['data'][i]['c9']=0;}
                     	  if(typeof(result['data'][i]['c10'])=='undefined'){result['data'][i]['c10']=0;} 
                          	table_html += '<tr>' +
                             '<td>'+result['data'][i]['name']+'</td>' +
                             '<td>'+result['data'][i]['c1']+'</td>' +
                             '<td>'+result['data'][i]['c2']+'</td>' +
                             '<td>'+result['data'][i]['c3']+'</td>' +
                             '<td>'+result['data'][i]['c4']+'</td>' +
                             '<td>'+result['data'][i]['c5']+'</td>' +
                             '<td>'+result['data'][i]['c6']+'</td>' +
                             '<td>'+result['data'][i]['c7']+'</td>' +
                             '<td>'+result['data'][i]['c8']+'</td>' +
                             '<td>'+result['data'][i]['c9']+'</td>' +
                             '<td>'+result['data'][i]['c10']+'</td>' +
                             
                             '</tr>';
                	   }
                    }
                
                $("#dataTable").append(table_html);
             
        },
        error : function(errorMsg) {
            notify("客官,不好意思，请求数据失败啦!");
        }
    });
}
    var dataOption = {
        title:'',
        autoload: false,
        request_url:'<?php echo site_url('SystemFunction/fairyTower');?>',
        callback: function (result) {
            if (result) {
                if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify(result.info);
                    return false;
                }
                //console.log(result.data);

                var table_html = '';
                  //  len = result.data.length;
                if(result['data']!=''){
                for(var i in result['data']){
                    if(!isNaN(i)) {   

                 if(typeof(result['data'][i]['c1'])=='undefined'){result['data'][i]['c1']=0;}
              	  if(typeof(result['data'][i]['c2'])=='undefined'){result['data'][i]['c2']=0;}
            	  if(typeof(result['data'][i]['c3'])=='undefined'){result['data'][i]['c3']=0;}
            	  if(typeof(result['data'][i]['c4'])=='undefined'){result['data'][i]['c4']=0;}
            	  if(typeof(result['data'][i]['c5'])=='undefined'){result['data'][i]['c5']=0;}
            	  if(typeof(result['data'][i]['c6'])=='undefined'){result['data'][i]['c6']=0;}
            	  if(typeof(result['data'][i]['c7'])=='undefined'){result['data'][i]['c7']=0;}
            	  if(typeof(result['data'][i]['c8'])=='undefined'){result['data'][i]['c8']=0;}
            	  if(typeof(result['data'][i]['c9'])=='undefined'){result['data'][i]['c9']=0;}
            	  if(typeof(result['data'][i]['c10'])=='undefined'){result['data'][i]['c10']=0;} 
                	table_html += '<tr>' +
                    '<td>'+result['data'][i]['name']+'</td>' +
                    '<td>'+result['data'][i]['c1']+'</td>' +
                    '<td>'+result['data'][i]['c2']+'</td>' +
                    '<td>'+result['data'][i]['c3']+'</td>' +
                    '<td>'+result['data'][i]['c4']+'</td>' +
                    '<td>'+result['data'][i]['c5']+'</td>' +
                    '<td>'+result['data'][i]['c6']+'</td>' +
                    '<td>'+result['data'][i]['c7']+'</td>' +
                    '<td>'+result['data'][i]['c8']+'</td>' +
                    '<td>'+result['data'][i]['c9']+'</td>' +
                    '<td>'+result['data'][i]['c10']+'</td>' +
                    
                    '</tr>';
                    }}
                
                $("#dataTable").html(table_html);
                ajaxRequest(1);
           
                }
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
