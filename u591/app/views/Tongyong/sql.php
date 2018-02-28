<section id="content">
    <div class="container">
        <div class="block-header">
            <h2><?php echo $page_title;?></h2>
        </div>
        <a href="javascript:;" onclick="showdetail()">新增</a>
        <div class="col-md-12">
            <div class="card">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                            <tr>
                                <th>显示名称</th>
                                <th>sql</th>
                                <th>可选条件</th>
                                <th>执行顺序</th>
                                <th>所属页面</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody id="dataTable">
                        </tbody>
                    </table>
            </div>
        </div>
    </div>
</section>
<script src="<?=base_url()?>public/ma/js/layer.js"></script>
<script>
function showdetail(sqlid){
	layer.open({
		  type: 2,
		  title: '新增页面',
		  maxmin: true,
		  shadeClose: true, //点击遮罩关闭层
		  area : ['800px' , '520px'],
		  content: '../TongyongFrame/sql_edit?sqlid='+sqlid
		  });
}
    var dataOption = {
        title:'',
        autoload: true,
        request_url:'<?php echo site_url($_request_method);?>',
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
                        '<td>'+result['data'][i]['fields_name']+'</td>' +
                        '<td>'+result['data'][i]['use_sql']+'</td>' +
                        '<td>'+result['data'][i]['where_info']+'</td>' +
                        '<td>'+result['data'][i]['exec_sort']+'</td>' +
                        '<td>'+result['data'][i]['name']+'</td>' +
                        '<td><a href="javascript:;" onclick="showdetail('+result['data'][i]['id']+')">编辑</a></td>' +
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
