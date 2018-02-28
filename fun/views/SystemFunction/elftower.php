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
                            <th>0-9</th>
                            <th>10-19</th>
                            <th>20-29</th>
                            <th>30-39</th>
                            <th>40-49</th>
                            <th>50-59</th>
                            <th>60-69</th>
                            <th>70-79</th>
                            <th>80-89</th>
                            <th>90-100</th>
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
function gettower(btype){
	var param = $("#search_form").serialize();
	param  += "&btype="+btype;
	var index = layer.load();
    $.ajax({
        type : "get",
        async : true, //同步执行
        url : '<?php echo site_url('SystemFunction/Elftower');?>',
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
                	if(typeof(result['data'][i]['level_0'])=='undefined'){result['data'][i]['level_0']=0;}
                    if(typeof(result['data'][i]['level_1'])=='undefined'){result['data'][i]['level_1']=0;}
                    if(typeof(result['data'][i]['level_2'])=='undefined'){result['data'][i]['level_2']=0;}
                    if(typeof(result['data'][i]['level_3'])=='undefined'){result['data'][i]['level_3']=0;}
                    if(typeof(result['data'][i]['level_4'])=='undefined'){result['data'][i]['level_4']=0;}
                    if(typeof(result['data'][i]['level_5'])=='undefined'){result['data'][i]['level_5']=0;}
                    if(typeof(result['data'][i]['level_6'])=='undefined'){result['data'][i]['level_6']=0;}
                    if(typeof(result['data'][i]['level_7'])=='undefined'){result['data'][i]['level_7']=0;}
                    if(typeof(result['data'][i]['level_8'])=='undefined'){result['data'][i]['level_8']=0;}
                    if(typeof(result['data'][i]['level_9'])=='undefined'){result['data'][i]['level_9']=0;}
                    
                    if(!isNaN(i))
                	table_html += '<tr>' +
                    '<td>'+result['data'][i]['act_name']+'</td>' +
                    '<td>'+result['data'][i]['level_0']+'</td>' +
                    '<td>'+result['data'][i]['level_1']+'</td>' +
                    '<td>'+result['data'][i]['level_2']+'</td>' +
                    '<td>'+result['data'][i]['level_3']+'</td>' +
                    '<td>'+result['data'][i]['level_4']+'</td>' +
                    '<td>'+result['data'][i]['level_5']+'</td>' +
                    '<td>'+result['data'][i]['level_6']+'</td>' +
                    '<td>'+result['data'][i]['level_7']+'</td>' +
                    '<td>'+result['data'][i]['level_8']+'</td>' +
                    '<td>'+result['data'][i]['level_9']+'</td>' ;

                    if(i<6){
                        if(btype == 1){
                       	 table_html +="<td><a href='javascript:showdetail(67,"+i+")'>玩家详细</a></td>" ;
                           }
                        }
               
                    table_html += '</tr>';
                    }
                
                $("#dataTable").append(table_html);
                if(btype<3){
                	gettower(parseInt(btype)+1);
                    }
        },
        error : function(errorMsg) {
            notify("客官,不好意思，请求数据失败啦!");
        }
    });
}


function gettowermore(btype){
	var param = $("#search_form").serialize();
	param  += "&btype="+btype;
	var index = layer.load();
    $.ajax({
        type : "get",
        async : true, //同步执行
        url : '<?php echo site_url('SystemFunction/Elftower');?>',
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
                	if(typeof(result['data'][i]['level_0'])=='undefined'){result['data'][i]['level_0']=0;}
                    if(typeof(result['data'][i]['level_1'])=='undefined'){result['data'][i]['level_1']=0;}
                    if(typeof(result['data'][i]['level_2'])=='undefined'){result['data'][i]['level_2']=0;}
                    if(typeof(result['data'][i]['level_3'])=='undefined'){result['data'][i]['level_3']=0;}
                    if(typeof(result['data'][i]['level_4'])=='undefined'){result['data'][i]['level_4']=0;}
                    if(typeof(result['data'][i]['level_5'])=='undefined'){result['data'][i]['level_5']=0;}
                    if(typeof(result['data'][i]['level_6'])=='undefined'){result['data'][i]['level_6']=0;}
                    if(typeof(result['data'][i]['level_7'])=='undefined'){result['data'][i]['level_7']=0;}
                    if(typeof(result['data'][i]['level_8'])=='undefined'){result['data'][i]['level_8']=0;}
                    if(typeof(result['data'][i]['level_9'])=='undefined'){result['data'][i]['level_9']=0;}
                    if(!isNaN(i))
                	table_html += '<tr>' +
                    '<td>'+result['data'][i]['act_name']+'</td>' +
                    '<td>'+result['data'][i]['level_0']+'</td>' +
                    '<td>'+result['data'][i]['level_1']+'</td>' +
                    '<td>'+result['data'][i]['level_2']+'</td>' +
                    '<td>'+result['data'][i]['level_3']+'</td>' +
                    '<td>'+result['data'][i]['level_4']+'</td>' +
                    '<td>'+result['data'][i]['level_5']+'</td>' +
                    '<td>'+result['data'][i]['level_6']+'</td>' +
                    '<td>'+result['data'][i]['level_7']+'</td>' +
                    '<td>'+result['data'][i]['level_8']+'</td>' +
                    '<td>'+result['data'][i]['level_9']+'</td>' ;
                
                    table_html += '</tr>';
                    }
                
                $("#dataTable").append(table_html);
                if(btype<3){
                	gettower(parseInt(btype)+1);
                    }
        },
        error : function(errorMsg) {
            notify("客官,不好意思，请求数据失败啦!");
        }
    });
}

function gettowermorec(btype){
	var param = $("#search_form").serialize();
	param  += "&btype="+btype;
	var index = layer.load();
    $.ajax({
        type : "get",
        async : true, //同步执行
        url : '<?php echo site_url('SystemFunction/Elftower');?>',
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
                    if(typeof(result['data'][i]['level_0'])=='undefined'){result['data'][i]['level_0']=0;}
                    if(typeof(result['data'][i]['level_1'])=='undefined'){result['data'][i]['level_1']=0;}
                    if(typeof(result['data'][i]['level_2'])=='undefined'){result['data'][i]['level_2']=0;}
                    if(typeof(result['data'][i]['level_3'])=='undefined'){result['data'][i]['level_3']=0;}
                    if(typeof(result['data'][i]['level_4'])=='undefined'){result['data'][i]['level_4']=0;}
                    if(typeof(result['data'][i]['level_5'])=='undefined'){result['data'][i]['level_5']=0;}
                    if(typeof(result['data'][i]['level_6'])=='undefined'){result['data'][i]['level_6']=0;}
                    if(typeof(result['data'][i]['level_7'])=='undefined'){result['data'][i]['level_7']=0;}
                    if(typeof(result['data'][i]['level_8'])=='undefined'){result['data'][i]['level_8']=0;}
                    if(typeof(result['data'][i]['level_9'])=='undefined'){result['data'][i]['level_9']=0;}
                    if(!isNaN(i))
                	table_html += '<tr>' +
                    '<td>'+result['data'][i]['act_name']+'</td>' +
                    '<td>'+result['data'][i]['level_0']+'</td>' +
                    '<td>'+result['data'][i]['level_1']+'</td>' +
                    '<td>'+result['data'][i]['level_2']+'</td>' +
                    '<td>'+result['data'][i]['level_3']+'</td>' +
                    '<td>'+result['data'][i]['level_4']+'</td>' +
                    '<td>'+result['data'][i]['level_5']+'</td>' +
                    '<td>'+result['data'][i]['level_6']+'</td>' +
                    '<td>'+result['data'][i]['level_7']+'</td>' +
                    '<td>'+result['data'][i]['level_8']+'</td>' +
                    '<td>'+result['data'][i]['level_9']+'</td>' ;
                
                    table_html += '</tr>';
                    }
                
                $("#dataTable").append(table_html);
                if(btype<3){
                	gettower(parseInt(btype)+1);
                    }
        },
        error : function(errorMsg) {
            notify("客官,不好意思，请求数据失败啦!");
        }
    });
}


function gettowermored(btype){
	var param = $("#search_form").serialize();
	param  += "&btype="+btype;
	var index = layer.load();
    $.ajax({
        type : "get",
        async : true, //同步执行
        url : '<?php echo site_url('SystemFunction/Elftower');?>',
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
                    if(typeof(result['data'][i]['level_0'])=='undefined'){result['data'][i]['level_0']=0;}
                    if(typeof(result['data'][i]['level_1'])=='undefined'){result['data'][i]['level_1']=0;}
                    if(typeof(result['data'][i]['level_2'])=='undefined'){result['data'][i]['level_2']=0;}
                    if(typeof(result['data'][i]['level_3'])=='undefined'){result['data'][i]['level_3']=0;}
                    if(typeof(result['data'][i]['level_4'])=='undefined'){result['data'][i]['level_4']=0;}
                    if(typeof(result['data'][i]['level_5'])=='undefined'){result['data'][i]['level_5']=0;}
                    if(typeof(result['data'][i]['level_6'])=='undefined'){result['data'][i]['level_6']=0;}
                    if(typeof(result['data'][i]['level_7'])=='undefined'){result['data'][i]['level_7']=0;}
                    if(typeof(result['data'][i]['level_8'])=='undefined'){result['data'][i]['level_8']=0;}
                    if(typeof(result['data'][i]['level_9'])=='undefined'){result['data'][i]['level_9']=0;}
                    
                    if(!isNaN(i))
                	table_html += '<tr>' +
                    '<td>'+result['data'][i]['act_name']+'</td>' +
                    '<td>'+result['data'][i]['level_0']+'</td>' +
                    '<td>'+result['data'][i]['level_1']+'</td>' +
                    '<td>'+result['data'][i]['level_2']+'</td>' +
                    '<td>'+result['data'][i]['level_3']+'</td>' +
                    '<td>'+result['data'][i]['level_4']+'</td>' +
                    '<td>'+result['data'][i]['level_5']+'</td>' +
                    '<td>'+result['data'][i]['level_6']+'</td>' +
                    '<td>'+result['data'][i]['level_7']+'</td>' +
                    '<td>'+result['data'][i]['level_8']+'</td>' +
                    '<td>'+result['data'][i]['level_9']+'</td>' ;
                
                    table_html += '</tr>';
                    }
                
                $("#dataTable").append(table_html);
                if(btype<3){
                	gettower(parseInt(btype)+1);
                    }
        },
        error : function(errorMsg) {
            notify("客官,不好意思，请求数据失败啦!");
        }
    });
}


    var dataOption = {
        title:'',
        autoload: false,
        request_url:'<?php echo site_url('SystemFunction/Elftower');?>',
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
                    if(!isNaN(i))             
                        
                	table_html += '<tr>' +
                    '<td>'+result['data'][i]['act_name']+'</td>' +
                    '<td>'+result['data'][i]['level_0']+'</td>' +
                    '<td>'+result['data'][i]['level_1']+'</td>' +
                    '<td>'+result['data'][i]['level_2']+'</td>' +
                    '<td>'+result['data'][i]['level_3']+'</td>' +
                    '<td>'+result['data'][i]['level_4']+'</td>' +
                    '<td>'+result['data'][i]['level_5']+'</td>' +
                    '<td>'+result['data'][i]['level_6']+'</td>' +
                    '<td>'+result['data'][i]['level_7']+'</td>' +
                    '<td>'+result['data'][i]['level_8']+'</td>' +
                    '<td>'+result['data'][i]['level_9']+'</td>' +
                    
                    '</tr>';
                    }
                
                $("#dataTable").html(table_html);
                gettower(1);
                gettowermore(6);
                gettowermorec(7);
                gettowermored(8);
                }
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
