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
function gettower(btype){
	var param = $("#search_form").serialize();
	param  += "&btype="+btype;
	var index = layer.load();
    $.ajax({
        type : "get",
        async : true, //同步执行
        url : '<?php echo site_url('SystemFunction/Egg');?>',
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
        	for(var i in result['data']){
        		if(result['data'].hasOwnProperty(i)){
                	table_html += '<tr><td>'+result['data'][i]['name']+
                	'</td><td>'+result['data'][i]['count']+'</td></tr>' ;
                	}
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
        request_url:'<?php echo site_url('SystemFunction/Egg');?>',
        callback: function (result) {
            if (result) {
                if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify(result.info);
                    return false;
                }
                //console.log(result.data);

                var table_html = '';

                for(var i in result['data']){
                	if(result['data'].hasOwnProperty(i)){
                	table_html += '<tr><td>'+result['data'][i]['name']+
                	'</td><td>'+result['data'][i]['count']+'</td></tr>' ;
                	}
                 }
                
                $("#dataTable").html(table_html);
                gettower(1);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
