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
                                <th style="width: 120px;">时间</th>
                                <th>服务器ID</th>
                                <th>账号ID</th>
                                <th>玩家ID</th>
                                <th>角色名称</th>
                                   <th>qq</th>
                                      <th>电话</th>
                                         <th>类型</th>
                                <th>玩家等级</th>
                                <th>VIP等级</th>
                                <th>客户端类型</th>
                                <th>Bug内容</th>
                                 <th>回复内容</th>
                                   <th>附图</th>
                                <th>点击回复</th>
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
function showdetail(id,images){
	layer.open({
		  type: 2,
		  title: 'iframe父子操作',
		  maxmin: true,
		  shadeClose: true, //点击遮罩关闭层
		  area : ['800px' , '620px'],
		  content: '../frame/imageDetail?id='+id+'&images='+images
		  });
}



function showImages(id,images){
	layer.open({
		  type: 2,
		  title: 'iframe父子操作',
		  maxmin: true,
		  shadeClose: true, //点击遮罩关闭层
		  area : ['800px' , '620px'],
		  content: '../frame/imageMultiple?id='+id+'&images='+images
		  });
}

    var dataOption = {
        title:'',
        autoload: true,
        request_url:'<?php echo site_url('Home/getBugReport');?>',
        callback: function (result) {
            if (result) {
                if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify("客官,不好意思，没有查到数据!");
                    return false;
                }
                var table_html = '',
                    len = result.data.length;
                for (var i in result.data) {
                    if (isNaN(i)) continue;
                    //for (var i=0;i<len; i++) {
                    table_html += '<tr>' +
                        '<td>'+result['data'][i]['date']+'</td>' +
                        '<td>'+result['data'][i]['serverid']+'</td>' +
                        '<td>'+result['data'][i]['accountid']+'</td>' +
                        '<td>'+result['data'][i]['userid']+'</td>' +
                        '<td>'+result['data'][i]['username']+'</td>' +
                        '<td>'+result['data'][i]['qq']+'</td>' +
                        '<td>'+result['data'][i]['telno']+'</td>' +
                        '<td>'+result['data'][i]['bug_type']+'</td>' +                        
                        '<td>'+result['data'][i]['user_level']+'</td>' +
                        '<td>'+result['data'][i]['vip_level']+'</td>' +
                        '<td>'+result['data'][i]['client_type']+'</td>' +
                        '<td>'+result['data'][i]['content']+'</td>' +
                        '<td>'+result['data'][i]['status']+'</td>' +
                        '<td>'+result['data'][i]['flag']+'</td>' +
                  //     '<td><img src="'+result['data'][i]['imgfile']+'" width="100px"/></td>' +                     
                        '<td>'+result['data'][i]['text']+'</td>' +
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
