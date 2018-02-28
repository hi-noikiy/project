$(document).ready(function(){
    var myChart = echarts.init(document.getElementById('echart'));
    var option = {
        title: {
            text: dataOption.title,
            subtext: ''
        },
        tooltip : {
            trigger: 'axis',
            axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
            }
        },
        toolbox: {
            show: true,
            feature: {
                magicType: {show: true, type: ['stack', 'tiled']},
                saveAsImage: {show: true}
            }
        },
        xAxis:{type : 'category'},
        yAxis : [
            {
                type : 'value'
            }
        ],
    };

    var load_data = function(t1, t2) {
        //var t1 = t1 || $("input[name='t1']").val(),
        //    t2 = t2 || $("input[name='t2']").val();
        //var server_id = $("select[name=server_id]").find('option:selected').val();
        var data = $("form#search_form").serialize();
        $.ajax({
            type : "get",
            async : false, //同步执行
            url : dataOption.request_url,
            data : data,//{"date1": t1, "date2": t2, "server_id":server_id},
            dataType : "json", //返回数据形式为json
            before: function(){
                myChart.showLoading();
            },
            success : function(result) {
                if (result) {
                    myChart.hideLoading();
                    if (result.status!='ok') {
                        $("#dataTable").empty();
                        notify("客官,不好意思，没有查到数据!", 'warn');
                        return false;
                    }

                    option.xAxis['data'] = result.xAxis;
                    option.legend = result.legend;
                    option.series = result.series;
                    //console.log(result);
                    myChart.setOption(option);
                    if (dataOption.callback!=undefined) {
                        dataOption.callback(result);
                        return true;
                    }
                    //console.log(option);
                    var table_html = '';
                    /*var cnt = result.xAxis.length, types = result.series.length;
                    for (var i=0; i<cnt; i++) {
                        table_html += "<tr>";
                        table_html += "<td>"+result.xAxis[i]+"</td>";
                        for (var j=0; j<types;j++) {
                            table_html += "<td data-type='numeric'>"+(result['series'][j]['data'][i]? result['series'][j]['data'][i] : 0)+"</td>";
                            //table_html += "<td></td>";
                        }

                        table_html += "</tr>"
                    }*/
                    for(var i in result.odata){
                    	table_html += '<tr>' +
                        '<td>'+i+'</td>' +
                        '<td>'+result['odata'][i]['new_role']+'</td>' +
                        '<td>'+result['odata'][i]['m1']+'</td>' +
                        '<td>'+result['odata'][i]['m2']+'</td>' +
                        '<td>'+result['odata'][i]['m3']+'</td>' +
                        '<td>'+result['odata'][i]['vip_role']+'</td>' +
                        '<td>'+result['odata'][i]['novip']+'</td>' +
                        '<td>'+result['odata'][i]['text']+'</td>' +
                        '</tr>';
                    }
                    $("#dataTable").html(table_html);
                    //console.log(table_html);
                }
            },
            error : function(errorMsg) {
                notify("客官,不好意思，图表请求数据失败啦!");
                myChart.hideLoading();
                $("#dataTable").empty();
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