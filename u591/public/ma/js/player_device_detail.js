$(document).ready(function(){
    //var myChart = echarts.init(document.getElementById('echart'));
    //var option = {
    //    title: {
    //        text: dataOption.title,
    //        subtext: ''
    //    },
    //    tooltip : {
    //        trigger: 'axis',
    //        axisPointer : {            // 坐标轴指示器，坐标轴触发有效
    //            type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
    //        }
    //    },
    //    toolbox: {
    //        show: true,
    //        feature: {
    //            magicType: {show: true, type: ['stack', 'tiled']},
    //            saveAsImage: {show: true}
    //        }
    //    },
    //    xAxis:{type : 'category'},
    //    yAxis : [
    //        {
    //            type : 'value'
    //        }
    //    ],
    //};

    var load_data = function(t1, t2) {
        var t1 = t1 || $("input[name='t1']").val(),
            t2 = t2 || $("input[name='t2']").val();
        var server_id = $("select[name=server_id]").find('option:selected').val();
        $.ajax({
            type : "get",
            async : false, //同步执行
            url : dataOption.request_url,
            data : {date1: t1, date2: t2, "server_id":server_id},
            dataType : "json", //返回数据形式为json
            before: function(){
                //myChart.showLoading();
            },
            success : function(result) {
                if (result) {
                    //myChart.hideLoading();
                    if (result.status!='ok') {
                        notify("客官,不好意思，没有查到数据!", 'warn');
                        //alert("客官,不好意思，没有查到数据!");
                    }
                    //options.legend.data = result.legend;
                    //options.xAxis[0].data = result.category;
                    //option.xAxis['data'] = result.xAxis;
                    //option.legend = result.legend;
                    //option.series = result.series;
                    //myChart.setOption(option);
                    var table_html = '';
                    var cnt = result.xAxis.length, types = result.series.length;
                    var total = 0;
                    for (var i=0; i<cnt; i++) {
                        total += parseInt(result['series'][0]['data'][i]);
                        table_html += "<tr>";
                        table_html += "<td>"+result.xAxis[i]+"</td>";
                        table_html += "<td data-type='numeric'>"+result['series'][0]['data'][i]+"</td>";
                        table_html += "<td data-type='numeric'>"+number_format(result['series'][0]['data'][i] / result['total'] * 100, 2)+" %</td>";
                        table_html += "</tr>"
                    }
                    $("#dataTable").html(table_html);
                    $("#total_client").html(total);
                    //console.log(table_html);
                }
            },
            error : function(errorMsg) {
                notify("客官,不好意思，图表请求数据失败啦!");
                //myChart.hideLoading();
            }
        });
    };
    load_data();
    $("#submit").on('click', function(){
        //myChart.resetOption();
        //myChart.clear();
        var t1 = $("input[name='t1']").val(),
            t2 = $("input[name='t2']").val();
        load_data(t1, t2);
    });
    //myChart.setOption(option);
});