$(document).ready(function(){
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
    	var param = $("#search_form").serialize();
        $.ajax({
            type : "get",
            async : false, //同步执行
            url : dataOption.request_url,
            data : param,
            dataType : "json", //返回数据形式为json
            before: function(){
                myChart.showLoading();
            },
            success : function(result) {
                if (result) {
                    myChart.hideLoading();
                    if (result.status!='ok') {
                        //notify("客官,不好意思，没有查到数据!", 'warn');
                        alert("客官,不好意思，没有查到数据!");
                    }
                    //options.legend.data = result.legend;
                    //options.xAxis[0].data = result.category;
                    option.xAxis['data'] = result.xAxis;
                    option.legend = result.legend;
                    option.series = result.series;
                    //console.log(result);
                    myChart.setOption(option);
                    var table_html = '';
                    var tables = {};
                    var cnt = result.xAxis.length, types = result.series.length;
                    for (var i=0; i<cnt; i++) {
                        table_html += "<tr>";
                        table_html += "<td>"+result.xAxis[i]+"</td>";
                        for (var j=0; j<types;j++) {
                            table_html += "<td data-type='numeric'>"+result['series'][j]['data'][i]+"</td>";
                            //table_html += "<td></td>";
                        }
                        table_html += "</tr>"
                    }
                    $("#dataTable").html(table_html);
                    //talble
                    var table_html = '';
                    for (var i in result.data) { 
                        //if (isNaN(i)) continue;
                        table_html += '<tr>' +
                            '<td>'+i+'</td>' +
                            '<td>'+result['data'][i]['daycount']+'</td>' +
                            '<td>'+result['data'][i]['weekcount']+'</td>' +
                            '<td>'+result['data'][i]['monthcount']+'</td>' +
                            '</tr>';
                    }
                    $("#dataTable").html(table_html);
                    //console.log(table_html);
                }
            },
            error : function(errorMsg) {
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