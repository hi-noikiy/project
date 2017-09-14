$(document).ready(function(){
    //$("#data-table-basic").bootgrid({
    //    css: {
    //        icon: 'zmdi icon',
    //        iconColumns: 'zmdi-view-module',
    //        iconDown: 'zmdi-expand-more',
    //        iconRefresh: 'zmdi-refresh',
    //        iconUp: 'zmdi-expand-less'
    //    },
    //});
    var myChart = echarts.init(document.getElementById('echart'));
    var option = {
        title: {
            text: dataOption.title,
            subtext: ''
        },
        tooltip: {
            trigger: 'axis'
        },
        //legend: {
        //    data:[]
        //},
        toolbox: {
            show: true,
            feature: {
                magicType: {show: true, type: ['stack', 'tiled']},
                saveAsImage: {show: true}
            }
        },
        xAxis: {
            type: 'category',
            boundaryGap: false,
            data: [
                '00:00',
                '01:00',
                '02:00',
                '03:00',
                '04:00',
                '05:00',
                '06:00',
                '07:00',
                '08:00',
                '09:00',
                '10:00',
                '11:00',
                '12:00',
                '13:00',
                '14:00',
                '15:00',
                '16:00',
                '17:00',
                '18:00',
                '19:00',
                '20:00',
                '21:00',
                '22:00',
                '23:00'
            ]
        },
        yAxis: {
            type: 'value'
        }
    };
    var load_data = function(t1, t2) {
        //var t1 = t1 || $("input[name='t1']").val(),
        //    t2 = t2 || $("input[name='t2']").val();
        //var server_id = $("select[name=server_id]").find('option:selected').val();
        var data = $("#search_form").serialize();
        $.ajax({
            type : "get",
            async : false, //同步执行
            url : dataOption.request_url,
            data : data,
            dataType : "json", //返回数据形式为json
            before: function(){
                myChart.showLoading();
            },
            success : function(result) {
                if (result) {
                    myChart.hideLoading();
                    if (result.status!='ok') {
                        //notify("客官,不好意思，没有查到数据!", 'warn');
                        if ($("#count_total").length) $("#count_total").text(0)
                        notify("客官,不好意思，没有查到数据!");
                    }

                    //options.legend.data = result.legend;
                    //options.xAxis[0].data = result.category;
                    option.legend = result.legend;
                    option.series = result.series;
                    //console.log(result);
                    myChart.setOption(option);
                    if ($("#count_total").length) $("#count_total").text(result.count_total);

                    if(dataOption.callback) {
                        dataOption.callback(result.series, result);
                    }
                }
            },
            error : function(errorMsg) {
                if ($("#count_total").length) $("#count_total").text(0)
                notify("客官,不好意思，图表请求数据失败啦!");
                myChart.hideLoading();
            }
        });
    };
    load_data();
    $("#submit").on('click', function(){
        //myChart.resetOption();
        myChart.clear();
        var t1 = $("input[name='t1']").val(),
            t2 = $("input[name='t2']").val();
        load_data(t1, t2);
    });
    //myChart.setOption(option);
});