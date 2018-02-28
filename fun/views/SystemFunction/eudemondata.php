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
                            <th></th>
                            <th>出场次数</th>                       
                            <th>出场率</th>
                            <th>胜利次数</th>
                               <th>胜率</th>
                            <th>失败次数</th>
                           <th>使用玩家</th>
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
$('#btype').change(function(){
	if($(this).val()==4){
		$('#gametype').hide();
	}else{
		$('#gametype').show();
	}
});
    var dataOption = {
        title:'',
        autoload: false,
        request_url:'<?php echo site_url('SystemFunction/eudemonData');?>',
        callback: function (result) {
            if (result) {
                if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify(result.info);
                    return false;
                }
                //console.log(result.data);

                var table_html = '<tr>' +
                '<td>队伍数量</td>' +
                '<td>'+result['cgid']+'</td>' +
                '<td>100%</td>' +
                '<td>0</td>' +
                '<td>0</td>' +

                '</tr>';
                  //  len = result.data.length;
                if(result['data']!='')
                for(var i in result['data']){
                    if(!isNaN(i)){
                    	table_html += '<tr>' +
                        '<td>'+result['data'][i]['eudemon']+'</td>' +                       
                        '<td>'+result['data'][i]['cid']+'</td>' +
                        '<td>'+result['data'][i]['rare']+'</td>' +
                        '<td>'+result['data'][i]['sum0']+'</td>' +
                        '<td>'+result['data'][i]['win_rate']+'</td>' +
                        '<td>'+result['data'][i]['sum1']+'</td>' +
                        '<td>'+result['data'][i]['user_total']+'</td>' +
                        '</tr>';
                        }
                	
                    }
                
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
