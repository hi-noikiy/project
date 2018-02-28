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
function gettower(btype){
	var param = $("#search_form").serialize();
	param  += "&btype="+btype;
	var index = layer.load();
    $.ajax({
        type : "get",
        async : true, //同步执行
        url : '<?php echo site_url('SystemFunction/Tree');?>',
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
                
                $("#dataTable").append(table_html);
                if(btype<4){
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
        request_url:'<?php echo site_url('SystemFunction/Tree');?>',
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
                }
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
