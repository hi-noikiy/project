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
                magicType: {show: true, type: ['line', 'bar']},
                saveAsImage: {show: true},
                dataZoom: {  
                    show: true,  
                     title: {  
                        dataZoom: '区域缩放',  
                        dataZoomReset: '区域缩放后退'  
                    }  
                }
            }
        },
        xAxis: {
            type: 'category'
        },
        yAxis: {
        	splitArea: {show: true},
            type: 'value'
        }
    };
    var bigserver;
    $('#servertype').change(function(){
    	bigserver = $(this).val();
		var parn = new RegExp("^"+bigserver+"\\d+");
		var ids = [];
		$('input[name="server_id[]"]').each(function(i){
			if(parn.test($(this).attr('value'))){
				$(this).attr('checked',true);
				ids.push($(this).attr('value'));
			}else{
				$(this).attr('checked',false);
			}
		});
		$("select#server_id_mul").val(ids);
		$("select#server_id_mul").multiselect('refresh');
    });
    var load_data = function(t1, t2) {
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
                    myChart.clear();
                    if (result.status!='ok') {
                        notify("客官,不好意思，没有查到数据!");
                    }

                    //options.legend.data = result.legend;
                    option.xAxis.data = result.category;
                    option.legend = result.legend;
                    option.series = result.series;
                    //console.log(result);
                    myChart.setOption(option);

                    if(dataOption.callback) {
                        dataOption.callback(result.data);
                    }
                }
            },
            error : function(errorMsg) {
                notify("客官,不好意思，图表请求数据失败啦!");
                myChart.hideLoading();
            }
        });
    };
    if ( dataOption.autoload==true ) load_data();
    $("#submit").on('click', function(){
        load_data();
    });
});