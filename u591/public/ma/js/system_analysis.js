$(document).ready(function(){
    var load_data = function(t1, t2) {
        var param = {};
        param['date1']  = t1 || $("input[name='t1']").val();
        param['date2']  = t2 || $("input[name='t2']").val();
        param['server_id']  = $("select[name=server_id]").find('option:selected').val();
        param['channel_id']  = $("select[name=channel_id]").find('option:selected').val();
        param['item_type']  = $("input[name='item_type']").val();
        param['item_type2'] = $("input[name='item_type2']") ? $("input[name='item_type2']").val() : 0;
        param['accountid']  = $("input[name='accountid']") ? $("input[name='accountid']").val() : 0;
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
                dataOption.callback(result);
            },
            error : function(errorMsg) {
                notify("客官,不好意思，请求数据失败啦!");
            }
        });
    };
    if ( dataOption.autoload==true ) load_data();
    $("#submit").on('click', function(){
        load_data();
    });
});