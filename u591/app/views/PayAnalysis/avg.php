<section id="content">
    <div class="container">
        <div class="block-header">
            <h2><?php echo $page_title;?></h2>
        </div>
        <div class="col-md-12">
            <div class="card">
                <?php echo $search_form;?>
                <div id="echart" style="width: 100%;height:400px;"></div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                        	<th>渠道</th>
                            <th>注册人数</th>
                            <th>充值总金额</th>
                            <th>人均充值</th>
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
$("input[name='reg1']").blur(function(){
	$("input[name='date1']").val($(this).val());
});
$("input[name='reg2']").blur(function(){
	var d = new Date($(this).val());
	var new_year = d.getFullYear();    //取当前的年份          
    var new_month = d.getMonth()+1;//取下一个月的第一天，方便计算（最后一天不固定）          
    if(new_month>12) {         
     new_month -=12;        //月份减          
     new_year++;            //年份增          
    }         
    var new_date = new Date(new_year,new_month,1);                //取当年当月中的第一天          
    new_date =  (new Date(new_date.getTime()-1000*60*60*24)).getDate();//获取当月最后一天日期          
	$("input[name='date2']").val(new_year+'-'+new_month+'-'+new_date);
});
    var dataOption = {
        title:'',
        autoload: false,
        request_url:'<?php echo site_url('PayAnalysis/Payavg');?>',
        callback: function (result) {
            if (result) {
				 var table_html = '';
                for(var i in result['accounts']){
                	table_html += '<tr>' +
                     '<td>' + i +'</td>' +
                     '<td>' +result['accounts'][i] + '</td>' +
                     '<td>' +result['pays'][i] + '</td>' +
                     '<td>'+result['avgs'][i]+'</td></tr>';
             }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_data.js"></script>
<!-- <script src="<?=base_url()?>public/ma/js/common_req.js"></script> -->
