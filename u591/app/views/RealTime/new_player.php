<section id="content">
    <div class="container">
        <div class="block-header">
            <h2><?php echo $page_title;?></h2>
        </div>
        <div class="col-md-12">
            <div class="card">
                <?php echo $search_form;?>
                <!--<div class="card-body card-padding" style="margin-top: -40px;">-->
                <!--    <div class="alert alert-info" role="alert">总激活数:<strong id="count_total"></strong></div>-->
                <!--</div>-->
                <!--<div id="echart" style="width: 100%;height:400px;"></div>-->
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr id="tb_head">
                            <th data-column-id="id" data-type="numeric">日期</th>
                            <th>注册数</th>
                            <th>创建角色数</th>
                            <th>转化率</th>
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
<script>
    function sum( obj ) {
        var sum = 0;
        for( var el in obj ) {
            if( obj.hasOwnProperty( el ) ) {
                sum += parseFloat( obj[el] );
            }
        }
        return sum;
    }
    var dataOption = {
        title:'新增玩家',
        request_url:'<?php echo site_url('RealTime/getNewRoleData');?>',
        callback: function(result) {
            //var hours = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23];
            //var hour_tr = '<th>日期</th>';
            //for (var i = 0; i<hours.length; i++) {
            //    hour_tr += '<th>'+hours[i]+'</th>';
            //}
            //var length = result.length;
            var tr_list = '';
            for (var idx in result) {
                var reg  = sum(result[idx]['reg']);//.reduce(function(pv, cv) { return pv + cv; }, 0);
                var role = sum(result[idx]['role']);//.reduce(function(pv, cv) { return pv + cv; }, 0);
                tr_list += '<tr><td>'+idx+'</td>' +
                    '<td>'+reg+'</td><td>'+role+'</td>' +
                    '<td>'+ number_format( ( role / reg ) * 100)+' % </td></tr>';
                //console.log(sum(result[idx]['reg']));
            }
            $("#dataTable").html(tr_list);

                //$("#")

        }
    };
    $(document).ready(function(){
        var load_data = function(t1, t2) {
            var data = $("#search_form").serialize();
            $.ajax({
                type : "get",
                async : false, //同步执行
                url : dataOption.request_url,
                data : data,
                dataType : "json", //返回数据形式为json
                before: function(){
                },
                success : function(result) {
                    if (result) {
                        if (result.status!='ok') {
                            //notify("客官,不好意思，没有查到数据!", 'warn');
                            notify("客官,不好意思，没有查到数据!");
                        }
                        if(dataOption.callback) {
                            dataOption.callback(result.series);
                        }
                    }
                },
                error : function(errorMsg) {
                    if ($("#count_total").length) $("#count_total").text(0)
                    notify("客官,不好意思，图表请求数据失败啦!");
                }
            });
        };
        load_data();
        $("#submit").on('click', function(){
            var t1 = $("input[name='t1']").val(),
                t2 = $("input[name='t2']").val();
            load_data(t1, t2);
        });
        //myChart.setOption(option);
    });
</script>