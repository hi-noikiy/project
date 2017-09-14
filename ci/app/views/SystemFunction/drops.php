<section id="content">
    <div class="container">
        <div class="block-header">
            <h2><?php echo $page_title;?></h2>
        </div>
        <div class="col-md-12">
            <div class="card">
                <?php echo $search_form;?>
                <div id="echart" style="width: 100%;height:400px;"></div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th>类型</th>
                            <th>掉线人数</th>
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
function showdetail(btype){
	layer.open({
		  type: 2,
		  title: '玩家详细',
		  maxmin: true,
		  shadeClose: true, //点击遮罩关闭层
		  area : ['800px' , '520px'],
		  content: '../frame/DropsDetail?btype='+btype
		  });
}
function macdetail(btype){
	layer.open({
		  type: 2,
		  title: '机型详细',
		  maxmin: true,
		  shadeClose: true, //点击遮罩关闭层
		  area : ['800px' , '520px'],
		  content: '../frame/DropsmacDetail?btype='+btype
		  });
}
    var dataOption = {
        title:'',
        autoload: false,
        request_url:'<?php echo site_url('Home/Drops');?>',
        callback: function (result) {
            if (result) {
                if (result.allaccount==0) {
                    $("#dataTable").html('');
                    return false;
                }
                //console.log(result.data);

                var table_html = '';
                table_html += '<tr>' +
                '<td>总</td>' +
                '<td>'+result['allaccount']+'</td>' +
                "<td><a href='javascript:showdetail(0)'>玩家详细</a> <a href='javascript:macdetail(0)'>机型统计</a></td>"+
                '</tr>';
                  //  len = result.data.length;
                if(result['data']!=''){
                for(var i in result['data']){
                    if(!isNaN(i))
                	table_html += '<tr>' +
                    '<td>'+result['data'][i]['btypename']+'</td>' +
                    '<td>'+result['data'][i]['caccount']+'</td>' +
                    "<td><a href='javascript:showdetail("+result['data'][i]['btype']+")'>玩家详细</a> <a href='javascript:macdetail("+result['data'][i]['btype']+")'>机型统计</a></td>"+
                    '</tr>';
                    }
               
                $("#dataTable").html(table_html);
                }
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_data.js"></script>
