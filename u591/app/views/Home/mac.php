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
                            <th>mac地址</th>
                            <th>角色名字</th>
                               <th>区服</th>                            
                            <th>角色ID</th>
                            <th> 账号ID</th>
                          

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

function showdetail(date,where){
	layer.open({
		  type: 2,
		  title: 'iframe父子操作',
		  maxmin: true,
		  shadeClose: true, //点击遮罩关闭层
		  area : ['800px' , '520px'],
		  content: '../frame/vipDistribution?date='+date+'&show='+where
		  });
}

    var dataOption = {
        title:'',
        autoload: false,
        request_url:'<?php echo site_url('HomeNew/mac');?>',
        callback: function (result) {
            if (result) {
                if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify("客官,不好意思，没有查到数据!");
                    return false;
                }
                //console.log(result.data);

               var table_html = '',
                    len = result.data.length;
                 
                for (var i in result.data) {
                    if (isNaN(i)) continue;
                    //for (var i=0;i<len; i++) {
                    table_html += '<tr>' +
                    '<td>'+result['data'][i]['mac']+'</td>' +
                        '<td>'+result['data'][i]['username']+'</td>' +
                        '<td>'+result['data'][i]['serverid']+'</td>' +
                        '<td>'+result['data'][i]['userid']+'</td>' +
                        '<td>'+result['data'][i]['accountid']+'</td>' +
                        
                       
     
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
