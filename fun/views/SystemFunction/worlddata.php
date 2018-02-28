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
                            <th>比赛时间</th>
                            <th>模式</th>
                            <th>玩家A</th>
                            <th>所在区服</th>
                            <th>胜负</th>
                            <th>vip</th>
                            <th>段位星级</th>
                            <th>A精灵1</th>
                            <th>情况</th>
                            <th>A精灵2</th>
                            <th>情况</th>
                            <th>A精灵3</th>
                            <th>情况</th>
                            <th>A精灵4</th>
                            <th>情况</th>
                            <th>A精灵5</th>
                            <th>情况</th>
                            <th>A精灵6</th>
                            <th>情况</th>
                            <th>玩家B</th>
                            <th>所在区服</th>
                            <th>胜负</th>
                            <th>vip</th>
                            <th>段位星级</th>
                            <th>B精灵1</th>
                            <th>情况</th>
                            <th>B精灵2</th>
                            <th>情况</th>
                            <th>B精灵3</th>
                            <th>情况</th>
                            <th>B精灵4</th>
                            <th>情况</th>
                            <th>B精灵5</th>
                            <th>情况</th>
                            <th>B精灵6</th>
                            <th>情况</th>
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
        autoload: true,
        request_url:'<?php echo site_url('SystemFunction/worldData');?>',
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
                if(result['data']!='')
                for(var i in result['data']){
                    if(isNaN(i)){
                        continue;
                        }
                	table_html += '<tr>' +
                    '<td>'+result['data'][i]['endTime']+'</td>' +
                    '<td>'+result['types'][result['data'][i]['type']]+'</td>' +
                    '<td>'+result['data'][i]['accountid1']+'</td>' +
                    '<td>'+result['data'][i]['serverid1']+'</td>' +
                    '<td>'+result['utypes'][result['data'][i]['status1']]+'</td>' +
                    '<td>'+result['data'][i]['viplevel1']+'</td>' +
                    '<td>'+result['data'][i]['dan1']+'</td>' +
                    '<td>'+result['itemtypes'][result['data'][i]['eudemon11']]+'</td>' +
                    '<td>'+result['etypes'][result['data'][i]['estatus11']]+'</td>' +
                    '<td>'+result['itemtypes'][result['data'][i]['eudemon12']]+'</td>' +
                    '<td>'+result['etypes'][result['data'][i]['estatus12']]+'</td>' +
                    '<td>'+result['itemtypes'][result['data'][i]['eudemon13']]+'</td>' +
                    '<td>'+result['etypes'][result['data'][i]['estatus13']]+'</td>' +
                    '<td>'+result['itemtypes'][result['data'][i]['eudemon14']]+'</td>' +
                    '<td>'+result['etypes'][result['data'][i]['estatus14']]+'</td>' +
                    '<td>'+result['itemtypes'][result['data'][i]['eudemon15']]+'</td>' +
                    '<td>'+result['etypes'][result['data'][i]['estatus15']]+'</td>' +
                    '<td>'+result['itemtypes'][result['data'][i]['eudemon16']]+'</td>' +
                    '<td>'+result['etypes'][result['data'][i]['estatus16']]+'</td>' +
                    '<td>'+result['data'][i]['accountid2']+'</td>' +
                    '<td>'+result['data'][i]['serverid2']+'</td>' +
                    '<td>'+result['utypes'][result['data'][i]['status2']]+'</td>' +
                    '<td>'+result['data'][i]['viplevel2']+'</td>' +
                    '<td>'+result['data'][i]['dan2']+'</td>' +
                    '<td>'+result['itemtypes'][result['data'][i]['eudemon21']]+'</td>' +
                    '<td>'+result['etypes'][result['data'][i]['estatus21']]+'</td>' +
                    '<td>'+result['itemtypes'][result['data'][i]['eudemon22']]+'</td>' +
                    '<td>'+result['etypes'][result['data'][i]['estatus22']]+'</td>' +
                    '<td>'+result['itemtypes'][result['data'][i]['eudemon23']]+'</td>' +
                    '<td>'+result['etypes'][result['data'][i]['estatus23']]+'</td>' +
                    '<td>'+result['itemtypes'][result['data'][i]['eudemon24']]+'</td>' +
                    '<td>'+result['etypes'][result['data'][i]['estatus24']]+'</td>' +
                    '<td>'+result['itemtypes'][result['data'][i]['eudemon25']]+'</td>' +
                    '<td>'+result['etypes'][result['data'][i]['estatus25']]+'</td>' +
                    '<td>'+result['itemtypes'][result['data'][i]['eudemon26']]+'</td>' +
                    '<td>'+result['etypes'][result['data'][i]['estatus26']]+'</td>' +
                    '</tr>';
                    }
                
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
