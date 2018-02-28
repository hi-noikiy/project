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

                            <th> VIP等级</th>
                            <th>活跃人数</th>
                            <th>单次狩猎参与人数</th>
                            <th>单次狩猎参与率</th>
                            <th>一键狩猎参与人数</th>
                            <th> 一键狩猎参与率</th>
                            <th>一键狩猎捕捉花费的钻石 </th>
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
        request_url:'<?php echo site_url('SystemFunction/hunting');?>',
        callback: function (result) {
            if (result) {
                if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify(result.info);
                    return false;
                }
                //console.log(result.data);

                var table_html='';
                if(result['data']!='')
                    for(var i in result['data']){
                        if(!isNaN(i)){
                            var h = ((result['data'][i]['v'])/(result['data'][i]['c'])*100).toFixed(2);
                            var j = ((result['data'][i]['p1'])/(result['data'][i]['c'])*100).toFixed(2);
                            table_html += '<tr>' +
                                '<td>'+result['data'][i]['viplev']+'</td>' +
                                '<td>'+result['data'][i]['c']+'</td>' +
                                '<td>'+result['data'][i]['v']+'</td>' +
                                '<td>'+h+'%</td>' +
                                '<td>'+result['data'][i]['p1']+'</td>' +
                                '<td>'+j+'%</td>' +
                                '<td>'+result['data'][i]['consume']+'</td>' +
                                '</tr>';
                        }

                    }

                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>








