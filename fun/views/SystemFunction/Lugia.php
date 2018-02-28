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
                              <th>总</th>
                               <th>vip0</th>
                            <th>vip1</th>
                            <th>vip2</th>
                            <th>vip3</th>
                            <th>vip4</th>
                            <th>vip5</th>
                            <th>vip6</th>
                            <th>vip7</th>
                            <th>vip8</th>
                            <th>vip9</th>
                            <th>vip10</th>
                         <th>vip11</th>
                          <th>vip12</th>
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
		  content: '../frame/lugiaDetail?act_id='+act_id+'&param='+param
		  });
}

function customsPass(btype){
	var param = $("#search_form").serialize();
	param  += "&btype="+btype;
	var index = layer.load();
    $.ajax({
        type : "get",
        async : true, //同步执行
        url : '<?php echo site_url('SystemFunction/Lugia');?>',
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
                    if(typeof(result['data'][i]['v0'])=='undefined'){result['data'][i]['v0']=0;}
                  	 if(typeof(result['data'][i]['v1'])=='undefined'){result['data'][i]['v1']=0;}
                  	 if(typeof(result['data'][i]['v2'])=='undefined'){result['data'][i]['v2']=0;}
                  	 if(typeof(result['data'][i]['v3'])=='undefined'){result['data'][i]['v3']=0;}
                  	 if(typeof(result['data'][i]['v4'])=='undefined'){result['data'][i]['v4']=0;}
                  	 if(typeof(result['data'][i]['v5'])=='undefined'){result['data'][i]['v5']=0;}
                  	 if(typeof(result['data'][i]['v6'])=='undefined'){result['data'][i]['v6']=0;}
                  	 if(typeof(result['data'][i]['v7'])=='undefined'){result['data'][i]['v7']=0;}
                  	 if(typeof(result['data'][i]['v8'])=='undefined'){result['data'][i]['v8']=0;}
                  	 if(typeof(result['data'][i]['v9'])=='undefined'){result['data'][i]['v9']=0;}
                  	 if(typeof(result['data'][i]['v10'])=='undefined'){result['data'][i]['v10']=0;}
                  	 if(typeof(result['data'][i]['v11'])=='undefined'){result['data'][i]['v11']=0;}
                  	 if(typeof(result['data'][i]['v12'])=='undefined'){result['data'][i]['v12']=0;}
                	table_html += '<tr>' +
                    '<td>'+result['data'][i]['name']+'</td>' +
                    '<td>'+result['data'][i]['total']+'</td>' +
                    '<td>'+result['data'][i]['v0']+'</td>' +
                    '<td>'+result['data'][i]['v1']+'</td>' +
                    '<td>'+result['data'][i]['v2']+'</td>' +
                    '<td>'+result['data'][i]['v3']+'</td>' +
                    '<td>'+result['data'][i]['v4']+'</td>' +
                    '<td>'+result['data'][i]['v5']+'</td>' +
                    '<td>'+result['data'][i]['v6']+'</td>' +
                    '<td>'+result['data'][i]['v7']+'</td>' +
                    '<td>'+result['data'][i]['v8']+'</td>' +
                    '<td>'+result['data'][i]['v9']+'</td>' +
                    '<td>'+result['data'][i]['v10']+'</td>' +
                    '<td>'+result['data'][i]['v11']+'</td>' +
                    '<td>'+result['data'][i]['v12']+'</td>' +
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




function customsPass2(btype){
	var param = $("#search_form").serialize();
	param  += "&btype="+btype;
	var index = layer.load();
    $.ajax({
        type : "get",
        async : true, //同步执行
        url : '<?php echo site_url('SystemFunction/Lugia');?>',
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
                     if(typeof(result['data'][i]['v0'])=='undefined'){result['data'][i]['v0']=0;}
                 	 if(typeof(result['data'][i]['v1'])=='undefined'){result['data'][i]['v1']=0;}
                 	 if(typeof(result['data'][i]['v2'])=='undefined'){result['data'][i]['v2']=0;}
                 	 if(typeof(result['data'][i]['v3'])=='undefined'){result['data'][i]['v3']=0;}
                 	 if(typeof(result['data'][i]['v4'])=='undefined'){result['data'][i]['v4']=0;}
                 	 if(typeof(result['data'][i]['v5'])=='undefined'){result['data'][i]['v5']=0;}
                 	 if(typeof(result['data'][i]['v6'])=='undefined'){result['data'][i]['v6']=0;}
                 	 if(typeof(result['data'][i]['v7'])=='undefined'){result['data'][i]['v7']=0;}
                 	 if(typeof(result['data'][i]['v8'])=='undefined'){result['data'][i]['v8']=0;}
                 	 if(typeof(result['data'][i]['v9'])=='undefined'){result['data'][i]['v9']=0;}
                 	 if(typeof(result['data'][i]['v10'])=='undefined'){result['data'][i]['v10']=0;}
                 	 if(typeof(result['data'][i]['v11'])=='undefined'){result['data'][i]['v11']=0;}
                 	 if(typeof(result['data'][i]['v12'])=='undefined'){result['data'][i]['v12']=0;}
                 	table_html += '<tr>' +
                    '<td>'+result['data'][i]['name']+'</td>' +
                    '<td>'+result['data'][i]['total']+'</td>' +
                    '<td>'+result['data'][i]['v0']+'</td>' +
                    '<td>'+result['data'][i]['v1']+'</td>' +
                    '<td>'+result['data'][i]['v2']+'</td>' +
                    '<td>'+result['data'][i]['v3']+'</td>' +
                    '<td>'+result['data'][i]['v4']+'</td>' +
                    '<td>'+result['data'][i]['v5']+'</td>' +
                    '<td>'+result['data'][i]['v6']+'</td>' +
                    '<td>'+result['data'][i]['v7']+'</td>' +
                    '<td>'+result['data'][i]['v8']+'</td>' +
                    '<td>'+result['data'][i]['v9']+'</td>' +
                    '<td>'+result['data'][i]['v10']+'</td>' +
                    '<td>'+result['data'][i]['v11']+'</td>' +
                    '<td>'+result['data'][i]['v12']+'</td>' +
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
        request_url:'<?php echo site_url('SystemFunction/Lugia');?>',
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
                    if(!isNaN(i))    {
                 if(typeof(result['data'][i]['v0'])=='undefined'){result['data'][i]['v0']=0;}
               	 if(typeof(result['data'][i]['v1'])=='undefined'){result['data'][i]['v1']=0;}
               	 if(typeof(result['data'][i]['v2'])=='undefined'){result['data'][i]['v2']=0;}
               	 if(typeof(result['data'][i]['v3'])=='undefined'){result['data'][i]['v3']=0;}
               	 if(typeof(result['data'][i]['v4'])=='undefined'){result['data'][i]['v4']=0;}
               	 if(typeof(result['data'][i]['v5'])=='undefined'){result['data'][i]['v5']=0;}
               	 if(typeof(result['data'][i]['v6'])=='undefined'){result['data'][i]['v6']=0;}
               	 if(typeof(result['data'][i]['v7'])=='undefined'){result['data'][i]['v7']=0;}
               	 if(typeof(result['data'][i]['v8'])=='undefined'){result['data'][i]['v8']=0;}
               	 if(typeof(result['data'][i]['v9'])=='undefined'){result['data'][i]['v9']=0;}
               	 if(typeof(result['data'][i]['v10'])=='undefined'){result['data'][i]['v10']=0;}
               	 if(typeof(result['data'][i]['v11'])=='undefined'){result['data'][i]['v11']=0;}
               	 if(typeof(result['data'][i]['v12'])=='undefined'){result['data'][i]['v12']=0;}
              	 if(typeof(result['data'][i]['text'])=='undefined'){result['data'][i]['text']='';}
                	table_html += '<tr>' +
                    '<td>'+result['data'][i]['name']+'</td>' +
                    '<td>'+result['data'][i]['total']+'</td>' +
                    '<td>'+result['data'][i]['v0']+'</td>' +
                    '<td>'+result['data'][i]['v1']+'</td>' +
                    '<td>'+result['data'][i]['v2']+'</td>' +
                    '<td>'+result['data'][i]['v3']+'</td>' +
                    '<td>'+result['data'][i]['v4']+'</td>' +
                    '<td>'+result['data'][i]['v5']+'</td>' +
                    '<td>'+result['data'][i]['v6']+'</td>' +
                    '<td>'+result['data'][i]['v7']+'</td>' +
                    '<td>'+result['data'][i]['v8']+'</td>' +
                    '<td>'+result['data'][i]['v9']+'</td>' +
                    '<td>'+result['data'][i]['v10']+'</td>' +
                    '<td>'+result['data'][i]['v11']+'</td>' +
                    '<td>'+result['data'][i]['v12']+'</td>' +
                    '<td>'+result['data'][i]['text']+'</td>' +
                    '</tr>';
                    }
                    }                
                $("#dataTable").html(table_html);
                customsPass(1);
                customsPass2(2);
               }
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
