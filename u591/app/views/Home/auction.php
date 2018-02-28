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
                            <th>顺序</th>
                            <th>商品类型</th>
                               <th>server</th>                            
                            <th>商品id</th>
                            <th>商品数量</th>
                             <th>价格</th>
                         
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
        request_url:'<?php echo site_url('HomeNew/auction');?>',
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
                    '<td>'+result['data'][i]['group']+'</td>' +
                        '<td>'+result['data'][i]['award_type']+'</td>' +
                        '<td>'+result['data'][i]['server']+'</td>' +
                        '<td>'+result['data'][i]['award_itemtype']+'</td>' +
                        '<td>'+result['data'][i]['award_amount']+'</td>' +
                        '<td>'+result['data'][i]['max_offermoney']+'</td>' +
           
     
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
