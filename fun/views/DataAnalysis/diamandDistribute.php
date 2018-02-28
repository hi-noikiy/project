<section id="content">
    <div class="container">
        <div class="block-header">
            <h2><?php echo $page_title;?></h2>
        </div>
        <div class="col-md-12">
            <div class="card">
                <?php echo $search_form_server;?>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th>vip等级</th>
                            <th>活跃玩家</th>
                            <th>获得钻石数</th>
                            <th>消耗钻石数</th>
                            <th>剩余钻石数</th>
                            <th>获得分布 与消耗分布</th>                        
                            
                      
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






function actdistribute(vip_level,type){
	 layer.open({			
		  title: '分布详情',
		  type: 2,
		  maxmin: true,
		  shadeClose: true, //点击遮罩关闭层
		  area : ['800px' , '520px'],
		  content: '../frame/actDistribute?vip_level='+vip_level+'&type='+type
		  });
	}

    var dataOption = {
        title:'',
        autoload: false,
        request_url:'<?php echo site_url('DataAnalysis/diamandDistribute');?>',
        callback: function (result) {
            if (result) {
                if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify(result.info);
                    return false;
                }
                //console.log(result.data);

                var table_html = '',
                    len = result.data.length;
                for (var i in result.data) {
                    if (isNaN(i)) continue;
                    //for (var i=0;i<len; i++) {
                    table_html += '<tr>' +
                        '<td>'+result['data'][i]['viplev']+'</td>' +
                     //   '<td>'+i+'</td>' +
                        '<td>'+result['data'][i]['active']+'</td>' +
                        '<td>'+result['data'][i]['type0']+'</td>' +
                        '<td>'+result['data'][i]['type1']+'</td>' +
                        '<td>'+result['data'][i]['surplus_money']+'</td>' +
                        '<td>'+result['data'][i]['text']+'</td>' +                     
                      
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
