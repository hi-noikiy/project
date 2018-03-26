<section id="content">
    <div class="container">
        <div class="block-header">
            <h2><?php echo $page_title; ?></h2>
        </div>
        <div class="col-md-12">
            <div class="card">
              <?php echo $search_form; ?>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th>VIP等级</th>
                            <th>超过15天未登录数量</th>
                            <th>超过30天未登录数量</th>
                            <th>超过40天未登录数量</th>
                            <th>超过50天未登录数量</th>
                            <th>超过60天未登录数量</th>
                            <th>Vip总数</th>
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
<script src="<?=base_url() ?>public/ma/js/layer.js"></script>
<script>
function showdetail(date, where) {
	layer.open({
		type: 2,
		title: 'iframe父子操作',
		maxmin: true,
		shadeClose: true, //点击遮罩关闭层
		area: ['800px', '520px'],
		content: '../frame/vipDistribution?date=' + date + '&show=' + where
	});
}
function gettower(btype){
	var param = $("#search_form").serialize();
	param  += "&btype="+btype;
	var index = layer.load();
    $.ajax({
        type : "get",
        async : true, //同步执行
        url : '<?php echo site_url('Home/vip_loss');?>',
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
//          console.log(btype+':'+result.data);
            for(var i = 0; i < len; i++) {
            	for(var j = 0; j < result.data.length; j++){
						if (result.data[j].viplev == i) {
							if (btype == 1) {
			            		dataTimeout15[i] = {'viplev':i,'conts':result.data[j].conts};
			            	}else if(btype == 2){
			            		dataTimeout30[i] = {'viplev':i,'conts':result.data[j].conts};
			            	}else if(btype == 3){
			            		dataTimeout40[i] = {'viplev':i,'conts':result.data[j].conts};
			            	}else if(btype == 4){
			            		dataTimeout50[i] = {'viplev':i,'conts':result.data[j].conts};
			            	}else if(btype == 5){
			            		dataTimeout60[i] = {'viplev':i,'conts':result.data[j].conts};
			            	}
							
						}
					}
            	
				
			}
			if(btype<5){
                gettower(parseInt(btype)+1);
            }
            var table_html = '';
            if(btype==5){
            	for(var i = 0; i < len; i++) {
					table_html += '<tr>' +
						'<td>' + dataSy[i].viplev + '</td>' +
						'<td>' + dataTimeout15[i].conts + '</td>' +
						'<td>' + dataTimeout30[i].conts + '</td>' +
						'<td>' + dataTimeout40[i].conts + '</td>' +
						'<td>' + dataTimeout50[i].conts + '</td>' +
						'<td>' + dataTimeout60[i].conts + '</td>' +
						'<td>' + dataSy[i].conts + '</td>' +
						'</tr>';
				}
            	$("#dataTable").html(table_html);
            }
        	
        },
        error : function(errorMsg) {
            notify("客官,不好意思，请求数据失败啦!");
        }
    });
}
var dataSy = new Array();
var dataTimeout15 = new Array();
var dataTimeout30 = new Array();
var dataTimeout40 = new Array();
var dataTimeout50 = new Array();
var dataTimeout60 = new Array();
var len = 0;
		
var dataOption = {
        title:'',
        autoload: false,
        request_url:'<?php echo site_url('Home/vip_loss');?>',
        callback: function (result) {
            if (result) {
                if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify(result.info);
                    return false;
                }
//              console.log('所有：'+result.data);
                var table_html = '';
                    len = result.data.length;
				for (var i = 0; i < len; i++) {
					dataSy[i] = {'viplev':i,'conts':result.data[i].conts};
					dataTimeout15[i] = {'viplev':i,'conts':'0'};
					dataTimeout30[i] = {'viplev':i,'conts':'0'};
					dataTimeout40[i] = {'viplev':i,'conts':'0'};
					dataTimeout50[i] = {'viplev':i,'conts':'0'};
					dataTimeout60[i] = {'viplev':i,'conts':'0'};
				}
				gettower(1);
                }
            }
    };
</script>
<script src="<?=base_url() ?>public/ma/js/common_req.js"></script>
