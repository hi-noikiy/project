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
                            <th>副本</th>
                            <th>玩家名</th>
                            <th>区服</th>
                            <th>战力</th>
                            <th>精灵1</th>
                            <th>精灵2</th>
                            <th>精灵3</th>
                            <th>精灵4</th>
                            <th>精灵5</th>
                            <th>精灵6</th>
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
function showdetail(actid){
	layer.open({
		  type: 2,
		  title: '每层阵容详细',
		  maxmin: true,
		  shadeClose: true, //点击遮罩关闭层
		  area : ['1000px' , '520px'],
		  content: '../frame/squadDetail?template_id='+actid
		  });
}
    var dataOption = {
        title:'',
        autoload: true,
        request_url:'<?php echo site_url('SystemFunction/Squad');?>',
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
                    '<td>'+result['data'][i]['template_id']+'</td>' +
                    '<td>'+result['data'][i]['username']+'</td>' +
                    '<td>'+result['data'][i]['server_id']+'</td>' +
                    '<td>'+result['data'][i]['totalpower']+'</td>' +
                    '<td>'+result['data'][i]['eud1']+'</td>' +
                    '<td>'+result['data'][i]['eud2']+'</td>' +
                    '<td>'+result['data'][i]['eud3']+'</td>' +
                    '<td>'+result['data'][i]['eud4']+'</td>' +
                    '<td>'+result['data'][i]['eud5']+'</td>' +
                    '<td>'+result['data'][i]['eud6']+'</td>' +
                    "<td><a href='javascript:showdetail("+result['data'][i]['template_id']+")'>详细</a></td>"+
                    '</tr>';
                    }
                
                $("#dataTable").html(table_html);
                }
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
