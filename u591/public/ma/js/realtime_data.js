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
            boundaryGap: false
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
                    option.xAxis.data = result.category;
                    option.legend = result.legend;
                    option.series = result.series;
                    //console.log(result);
                    myChart.setOption(option);
                    if ($("#count_total").length) $("#count_total").text(result.count_total);

                    if(dataOption.callback) {
                        dataOption.callback(result.output, result);
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