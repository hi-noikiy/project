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
                            <th>总登录数：</th>
                            <th colspan="12" id="total_count" style="font-size:14px;font-weight: bold;color:#ff0000;"></th>
                        </tr>
                        <tr>
                            <th>等级</th>
                            <th>当天登录人数</th>
                            <th>等级比例</th>
                            <th>1日流失数</th>
                            <th>1日流失率</th>
                            <th>3日流失数</th>
                            <th>3日流失率</th>
                            <th>7日流失数</th>
                            <th>7日流失率</th>
                            <th>14日流失数</th>
                            <th>14日流失率</th>
                            <th>30日流失数</th>
                            <th>30日流失率</th>
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
    function ForDight(Dight,How){
        Dight = Math.round(Dight*Math.pow(10,How))/Math.pow(10,How);
        return Dight;
    }
    function decimal(num,v)
    {
        var vv = Math.pow(10,v);
        return Math.round(num*vv)/vv;
    }
    //function f2(){
    //    alert(ForDight(12345.67890,3));//保留三位小数
    //    alert(ForDight(123.99999,4));//保留四位小数
    //}
    var dataOption = {
        autoload: true,
        title:'活跃玩家每日流失',
        request_url:'<?php echo site_url('LostAnalysis/active?data_type=0');?>',
        callback: function(result) {
            if (result.status!='ok') {
                $("#total_count").text(0);
                $("#dataTable").html('');
                notify("抱歉，没有查询到数据");
                return false;
            }
            var table_html = '', length = result['collection'].length, i=0;
            $("#total_count").text(result['total']);
            for (i;i<length;i++) {
                table_html += "<tr>";
                table_html += "<td data-type='numeric'>"+ result['collection'][i]['lev'] +"</td>";
                table_html += "<td data-type='numeric'>"+ result['collection'][i]['usercount'] +"</td>";
                table_html += "<td data-type='numeric'>"+ number_format(result['collection'][i]['usercount']/result['total'] * 100, 2) +" % </td>";
                table_html += "<td data-type='numeric'>"+result['collection'][i]['lost_1']+'</td>' +
                    '<td>'+(result['collection'][i]['lost_1']/ result['collection'][i]['usercount'] * 100).toFixed(2) +"%</td>";
                table_html += "<td data-type='numeric'>"+result['collection'][i]['lost_3']+'</td>' +
                    '<td>'+(result['collection'][i]['lost_3']/ result['collection'][i]['usercount'] * 100).toFixed(2) +"%</td>";
                table_html += "<td data-type='numeric'>"+result['collection'][i]['lost_7']+'</td>' +
                    '<td>'+(result['collection'][i]['lost_7']/ result['collection'][i]['usercount'] * 100).toFixed(2)  +"%</td>";
                table_html += "<td data-type='numeric'>"+result['collection'][i]['lost_14']+'</td>' +
                    '<td>'+(result['collection'][i]['lost_14']/ result['collection'][i]['usercount'] * 100).toFixed(2) +"%</td>";
                table_html += "<td data-type='numeric'>"+result['collection'][i]['lost_30']+'</td>' +
                    '<td>'+(result['collection'][i]['lost_30']/ result['collection'][i]['usercount'] * 100).toFixed(2) +"%</td>";
                table_html += "</tr>";
            }
            $("#dataTable").html(table_html);
        }
    };
    $(document).ready(function(){
        var load_data = function(t1, t2) {
            $.ajax({
                type : "get",
                async : false, //同步执行
                url : dataOption.request_url,
                data : $("#search_form").serialize(),
                dataType : "json", //返回数据形式为json
                before: function(){
                },
                success : function(result) {
                    if (result) {
                        if (result.status!='ok') {
                            notify("客官,不好意思，没有查到数据!", 'warn');
                            //alert("客官,不好意思，没有查到数据!");
                        }
                        dataOption.callback(result);
                    }
                },
                error : function(errorMsg) {
                    notify("客官,不好意思，图表请求数据失败啦!");
                }
            });
        };
        load_data();
        $("#submit").on('click', function(){
            load_data();
        });
        //myChart.setOption(option);
    });
</script>
