/**
 * Created by cgp on 16/7/23.
 */
$(document).ready(function(){
	
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
		//alert($("select#server_id_mul").val());
	});
	
	
	$('#play_pay').change(function(){
		playpay = $(this).val();		
		var ids = [];	 
         $.ajax({
             type: "GET",
             url: "getPlayPay",			
             data: {id:$("#play_pay").val()},
             dataType: "json",
             success: function(data){
            
                 //     console.log(data.data);
                     ids=data.data;
				
		$("select#type_id_mul").val(ids);
		$("select#type_id_mul").multiselect('refresh');	
                     
                      }
         });

	
	});
	
	
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
    //$("#selectAll").on('click', function(){
    //    $("input.events").attr("checked", "checked");
    //});
    $("#unSelect").on('click', function(){
        $("input.events").removeAttr("checked");
        $("#chk-all").removeAttr("checked");
    });
    $("#chk-all").on('click', function(){
        $("#unSelect").removeAttr("checked");
        var chkd = $(this).prop("checked");
        $(".events").trigger('click');
    });
});