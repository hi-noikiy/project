// Initialize your app
var myApp = new Framework7({
    pushState: true,
    swipeBackPage: true,
    modalTitle: '',
    modalButtonOk: '确认',
    modalButtonCancel: '取消',
    cache: false,
    cacheDuration: 1000*60*5,
    cacheIgnore: [
        'index/index.html',
        'User/haspaying/status/3.html',
        'User/haspaying/status/0.html',
        'User/order.html',
        'User/orderajax1.html?status=3',
        'User/orderajax1.html?status=7',
        'User/record.html',
        'User/user/op/login.html',
        'User/user.html',
    ],
    preloadPreviousPage: false,
    cacheIgnoreGetParameters: false,
    swipeout:false,
    // Index/seat
    onAjaxStart: function (xhr) {
        myApp.showIndicator();
    },
    onAjaxComplete: function (xhr) {
        myApp.hideIndicator();
    }
});

// Export selectors engine
var $$ = Dom7;

// Add view
var mainView = myApp.addView('.view-main', {
    // Because we use fixed-through navbar we can enable dynamic navbar
    dynamicNavbar: true
});


//首页
myApp.onPageInit('bookwholeIndex', function (page) {
    var wrapWidth = $(window).width(),
        unitWidth = wrapWidth / 4,
        ulWidth = unitWidth * $('.date li').length;
    $('.date li').width(unitWidth);
    $('.date ul').width(ulWidth);
    $('.date li').on('click',function(){
        $(this).addClass('active');
        $(this).siblings().removeClass('active');
    });

//日期滚动
    var myScroll;
    myScroll = new IScroll('.date', { scrollX: true, scrollY: false, scrollbars: 'custom' });
    
    var index_flag=$('#index_flag').html();
    if( index_flag=='111') {
        var index_payoff_thisUrl=$('#index_payoff_thisUrl').html();
        var myApp = new Framework7({
            modalButtonOk: '前往支付',
            modalButtonCancel: '重新购买'
        });
        myApp.confirm('你有一笔订单未完成支付！','温馨提示',function(){
             location.href=index_payoff_thisUrl+'?id='+$('#index_flag_id').html();
           // _this.parents('label').remove();
       })
    }

    //下一步是否可点

    $('#bookwholeindex').on('click','label', function () {
        setTimeout(function () {        //加入延迟避免点击label时所探测的input:checked个数未及时改变

            if($('input:checked').length>=2){ //判断选择的项是否连续
                $('#step1').removeClass('disabled');
                for(i=0;i<=$('input:checked').length-2;i++){
                    if(!$('input:checked').eq(i).parents('label').next().find('input').is(':checked')){

                        $('#step1').addClass('disabled');
                        myApp.alert('必须选择连续的时间段！','温馨提醒');
                        return false;
                    }
                }
                var stime;
                var endtime;
                $("input[name='index_plan']:checked").each(function(i,a){ 
                        var $this = $(this);
                        if(i==0)    {
                            stime=$this.attr("time");
                        } 
                        if(i==($("input[name='index_plan']:checked").length-1) )   {
                            endtime=$this.attr("endTime");
                        }            
                });

                 var sedata='&time='+stime+'&endTime='+endtime;
                 
                 $.ajax({
                     // global:true,
                     type: "POST",
                     url:$('#search_plan_theme_thisUrl').html(),
                     data: sedata,
                     success: function(msg){
                    
                 	}
                 });
       
            }else if($('input:checked').length==0) {
                $('#step1').addClass('disabled');
                myApp.alert('请选择时间段','温馨提醒');
                return false;

            }else {
                $('#step1').removeClass('disabled');
            }

	    }, 100);
	});

	function search_plan(time) {
	    var search_plan_thisUrl = $('#search_plan_thisUrl').html();
	    var sedata='';
	    if(time) {
	        sedata+='&time='+time;
	         $.cookie('time', time, { path: '/' });  
	    }          
	    $.ajax({
	        // global:true,
	        type: "POST",
	        url:search_plan_thisUrl,
	        data: sedata,
	        success: function(msg){
	
	
	          	var data=msg.data;
	          //  var data= eval("(" + msg + ")");
	            var html='';
	             for(var o in data){ 
	                html+='<label class="item '+data[o].classesd+'">';
	                html+='    <input type="checkbox" name="index_plan" time="'+data[o].time+'" endTime="'+data[o].endTime+'" price="'+data[o].price+'">';
	                html+='    <div>';
	                html+='         <span>';
	                html+='             <b>'+data[o].timeflag+'<em>- '+data[o].endTimeflag+'</em></b>';
	                html+='             <i>预定时段</i>';
	                html+='         </span>';
	                html+='         <span>';
	                html+='             <b>'+data[o].duration+'min</b>';
	                html+='             <i>预定时长</i>';
	                html+='         </span>';
	                html+='         <span>';
	                html+='             <b>¥'+data[o].price+'</b>';
	                html+='             <i>'+data[o].oriPrice+'</i>';
	                html+='         </span>';
	                html+='         <a></a>';
	                html+='     </div>';
	                html+='</label>';
	              } 
	              $('#plannumList').empty().append(html);
	        }
	    });
	}


    //按钮点击操作

    $('#step1').on('click',function(){

            var index_all='';
            var stime;
            var endtime;
            var index_price=0;

            $("input[name='index_plan']:checked").each(function(i,a){

                var $this = $(this);
                if(i==0)    {
                    stime=$this.attr("time");
                }

                if(i==($("input[name='index_plan']:checked").length-1) )   {
                    endtime=$this.attr("endTime");
                }


                var time=$this.attr("time");
                var price=$this.attr("price");
                // time+= time+',';
                index_price+=price*1;
                index_all+='{"time":"'+time+'","price":"'+price+'"},'
                //如果元素已经存在，不再添加
            });
            index_all = "[" + index_all.substring(0, index_all.length - 1) + "]";
            $.cookie('index_all', index_all, { path: '/' });
            $.cookie('index_duration', (endtime-stime)/60, { path: '/' });
            $.cookie('viewingDate', stime, { path: '/' });
            $.cookie('endTime', endtime, { path: '/' });
            $.cookie('planPrice', index_price, { path: '/' });
            $.cookie('index_price', index_price, { path: '/' });

            mainView.router.loadPage($('#index_theme_thisUrl').html());
            //    location.href='{:U("Bookwhole/theme")}';
    });

});




//主题
myApp.onPageInit('theme', function (page) {
    $(function() {

        //相册排列宽度初始
        var unitWidth = 80,
            totalWidth = $(window).width();
        $('.img-list').each(function () {
            var ulWidth = unitWidth * $(this).find('img').length;
            $(this).width(ulWidth);
        });
        $('.theme-block .right').width(totalWidth-113);

        //下一步是否可点
        $('#theme label').on('click', function () {
            $('#step2').removeClass('disabled');
        });
        //点击时打开图片浏览器
        $$('.img-list').on('click', function () {
            var photos = [];
            $(this).find('img').each(function (i) {
                photos[i] = $(this).attr('src');
            });
            var myPhotoBrowserStandalone = myApp.photoBrowser({
                'photos': photos,
                'backLinkText': '关闭',
                'ofText': '/'
            });
            myPhotoBrowserStandalone.open();
        })    
    });

    //按钮点击操作
    $('#step2').on('click',function(){
        var passnum = $('#theme input:checked').parents('label').attr('passnum');
        var unitprice = $('#theme input:checked').parents('label').attr('unitprice');
        myApp.confirm('您预订的时段定价为最低保底'+passnum+'人价格。不足'+passnum+'人按'+passnum+'人计算，人员超出部分，每人需额外补差'+'<i class="orange">'+unitprice+'元</i>，感谢您的支持！','温馨提示', function () {
            $.cookie('videoId', $("input[name='theme']:checked").attr("uuid"), { path: '/' });
            $.cookie('topicName', $("input[name='theme']:checked").attr("topicName"), { path: '/' });
            $.cookie('videoCode', $("input[name='theme']:checked").attr("videoCode"), { path: '/' });
            $.cookie('seating', $("input[name='theme']:checked").attr("seating"), { path: '/' });
            mainView.router.loadPage($('#theme_filmList_thisUrl').html());
            // location.href = '{:U("Bookwhole/filmList")}';
        });
    });

});


myApp.onPageInit('filmlist', function (page) {

	 $(function() {
         $('.modal-overlay').last().remove();
         //时段
         var wrapWidth = $(window).width(),
         unitWidth = wrapWidth / 4,
         ulWidth = unitWidth * $('.date li').length;
         $('.date li').width(unitWidth);
         $('.date ul').width(ulWidth);
         $('.date li').on('click',function(){
             $(this).addClass('active');
             $(this).siblings().removeClass('active');
         });

         // 操作提示
         var msg = function(text){
             $('#filmList').after('<div class="msg"><div class="shad"></div></div></div>');
             $('.shad').before('<p>'+text+'</p>');
             setTimeout(function () {
                 $('.msg').remove();
             }, 2000);
         };
         // msg('您选择的时段没有影厅可接受预定！');

         //下一步是否可点
         $('#filmList label').on('click', function () {
             if(($.cookie('index_duration')-0)<($(this).find('p span').text()-0)) {
            	 
            	 
            	 $.cookie('filmlist_duration', ($(this).find('p span').text()-0), { path: '/' });
                 // alert($.cookie('index_duration'));
                 // alert($(this).find('p span').text());
                  $('#step3').addClass('disabled');
                  var myApp = new Framework7({
                     modalButtonOk: '增加时段',
                     modalButtonCancel: '取消'
                 });
                 myApp.confirm('您预定的电影片长超过预定总时长，请延长预定时段！ ','温馨提示', function () {
                    // alert( $.cookie('index_all'));
                     myApp.pickerModal('.picker-info');
                     $$('.modal-overlay').addClass('modal-overlay-visible');
						var json_index_all= eval('(' + $.cookie('index_all')+ ')');
						$.each(json_index_all,function(i,o){
							$("#filmlistplannumList input[name='filmlist_plan']").each(function(b,a){    
								var $this = $(this);
								if(o.time==$this.attr("time")){
									$this.prop('checked', true); 
								};														   
							}); 						
						})

                     
                 });
             }else{
                 $('#step3').removeClass('disabled');
             }              
             // $(this).find('p span').text();
             // console.log($(this).find('p span').text())
         });
			//点击两次后有问题
         
//         $(".modal-button:eq(0)").unbind( "click" );
//         $('body').on('click','.modal-button:eq(0)',function(){
//               $('#step3').removeClass('disabled');
//         })
         $$('.confirm').on('click', function () {

         	 	var index_all='';
         	   var stime;
                var endtime;
                var index_price=0;

                 $("input[name='filmlist_plan']:checked").each(function(i,a){ 

                         var $this = $(this);
                        if(i==0)    {
                            stime=$this.attr("time");
                        } 

                        if(i==($("input[name='filmlist_plan']:checked").length-1) )   {
                            endtime=$this.attr("endTime");
                        } 
                 
                       
                        var time=$this.attr("time");
                        var price=$this.attr("price");
                        index_all+='{"time":"'+time+'","price":"'+price+'"},'
                        // time+= time+',';
    					index_price+=price*1;
                         //如果元素已经存在，不再添加                                  
                 });                 
                 if($.cookie('filmlist_duration')>((endtime-stime)/60)){                	 
                	 myApp.alert('预定时段小于电影播放时间！','');              	 
                	 return;                 	 
                 }
                 index_all = "[" + index_all.substring(0, index_all.length - 1) + "]";
                 $.cookie('index_all', index_all, { path: '/' });  
                 $.cookie('index_duration', (endtime-stime)/60, { path: '/' });
                 
                
                 
                 $.cookie('viewingDate', stime, { path: '/' });
                 $.cookie('endTime', endtime, { path: '/' });
                 $.cookie('planPrice', index_price, { path: '/' });
                 $.cookie('index_price', index_price, { path: '/' });
                 $('#step3').removeClass('disabled');
             myApp.closeModal('.picker-info')
         });


         $('body').on('click','#filmlistplannumList label', function () {
             setTimeout(function () {        //加入延迟避免点击label时所探测的input:checked个数未及时改变
                 if($('.planCon input:checked').length>=2){ //判断选择的项是否连续
                     $('#step1').removeClass('disabled');
                     for(i=0;i<=$('.planCon input:checked').length-2;i++){
                         if(!$('.planCon input:checked').eq(i).parents('label').next().find('input').is(':checked')){

                             $('#step1').addClass('disabled');
                             myApp.alert('必须选择连续的时间段！','温馨提醒');
                             return false;
                         }
                     }
                 }else if($('.planCon input:checked').length==0) {
                     $('#step1').addClass('disabled');
                     myApp.alert('请选择时间段','温馨提醒');
                     return false;

                 }else {
                     $('#step1').removeClass('disabled');
                 }

             }, 100);
         });



          //按钮点击操作
     $('#step3').on('click',function(){
         $.cookie('filmNo', $("input[name='filmlist']:checked").attr("filmNo"), { path: '/' });
         $.cookie('filmName', $("input[name='filmlist']:checked").attr("filmName"), { path: '/' });
         mainView.router.loadPage($('#filmList_service_thisUrl').html());


       //  alert($.cookie('endTime'));
//			 location.href='{:U("Bookwhole/service")}';
     });

     


     });


   
});
function search_filmlist_plan(time) {
    var search_filmlist_plan_thisUrl = $('#search_filmlist_plan_thisUrl').html();
    var sedata='';
    if(time) {
        sedata+='&time='+time;
       //  $.cookie('time', time, { path: '/' });  
    }          
    $.ajax({
        // global:true,
        type: "POST",
        url:search_filmlist_plan_thisUrl,
        data: sedata,
        success: function(msg){
            var data=msg.data;
          //  var data= eval("(" + msg + ")");
            var html='';
             for(var o in data){ 
                html+='<label class="item '+data[o].classesd+'">';
                html+='    <input type="checkbox" name="filmlist_plan" time="'+data[o].time+'" endTime="'+data[o].endTime+'" price="'+data[o].price+'">';
                html+='    <div>';
                html+='         <span>';
                html+='             <b>'+data[o].timeflag+'<em>- '+data[o].endTimeflag+'</em></b>';
                html+='             <i>预定时段</i>';
                html+='         </span>';
                html+='         <span>';
                html+='             <b>'+data[o].duration+'min</b>';
                html+='             <i>预定时长</i>';
                html+='         </span>';
                html+='         <span>';
                html+='             <b>¥'+data[o].price+'</b>';
                html+='             <i>'+data[o].oriPrice+'</i>';
                html+='         </span>';
                html+='         <a></a>';
                html+='     </div>';
                html+='</label>';
              } 
              $('#filmlistplannumList').empty().append(html);
        }
    });
}
myApp.onPageInit('service', function (page) {

    //数量加减
    $(".Spinner").Spinner({ min:0, len:2, max:99});
    $('#step4').on('click',function(){
        //取值
        var costA = new Array(), //附加服务项
            costB = new Array(),   //零食项
            cost1 = 0, //附加服务总价
            cost2 = 0,//零食总价/
            costAll = 0; //总价
        var package_price='';
        var service_price='';
        $('.service-item').each(function(i){
            var a = $(this).find('p span').eq(0).text();
            var b = $(this).find('p span:eq(1) i').text();
            var c = $(this).find('.Amount').val();
            var tt=$(this).find('.Amount');
            console.log(a+'--'+b+'--'+c);
            costA[i] = b*c;
            if(costA[i]!=0) {
            	service_price+='{"id":"'+tt.attr('sid')+'","name":"'+a+'","num":"'+c+'","price":"'+b+'","tolprice":"'+costA[i]+'"},';
             }
        });
        service_price = "[" + service_price.substring(0, service_price.length - 1) + "]";
        $('.goods-list').each(function(i){
            var a = $(this).find('h4').text();
            var b = $(this).find('.leftblcok i:eq(0) em').text();
            var c = $(this).find('.Amount').val();
            var d = $(this).find('.combination').text();
            var tt=$(this).find('.Amount');
            console.log(a+'--'+b+'--'+c);
            costB[i] = b*c;
            if(costB[i]!=0) {
				if(tt.attr('state')==1){
					console.log('进行优惠价格判断');
					console.log(c);
					console.log(tt.attr('disNum'));						
					if(Number(c)>=Number(tt.attr('disNum'))) {
						b=b*tt.attr('discount')/10
						costB[i]=costB[i]*tt.attr('discount')/10;
						console.log('优惠价格'+costB[i]);
					}
				}
				package_price+='{"id":"'+tt.attr('sid')+'","name":"'+a+'","num":"'+c+'","detail":"'+d+'","price":"'+b+'" ,"tolprice":"'+costB[i]+'"},';
             }
        });
        package_price = "[" + package_price.substring(0, package_price.length - 1) + "]";
        $.cookie('service_price', service_price, { path: '/' });
        $.cookie('package_price', package_price, { path: '/' });
        for(var x in costA){
            cost1 += costA[x];
        }
        for(var x in costB){
            cost2 += costB[x];
        }
        costAll = cost1 + cost2;
        console.log('总价'+ costAll);
        $.cookie('detail', $('#service_detail').val(), { path: '/' });


       var index_price= $.cookie('index_price');

       var all_price=Number(index_price)+Number(costAll);
       $.cookie('all_price', all_price, { path: '/' });
       mainView.router.loadPage($('#service_confirmorder_thisUrl').html());
//       location.href='{:U("Bookwhole/confirmorder")}';
    })

});


myApp.onPageInit('schedule', function (page) {

    $(function(){
        var wrapWidth = $(window).width();
        $('.date li').width(wrapWidth/3);

        $('.date li').eq(0).addClass('active');
        $('#page-schedule .content').eq(0).show();

        $('.date li').each(function (i) {
            $(this).click(function () {
                $('.date li').removeClass('active');
                $(this).addClass('active');
                $('.content').hide();
                $('.content').eq(i).show();
            })
        })
    })

})

myApp.onPageInit('invoice', function (page) {

    $('.invoice-type li').each(function (i) {

        $(this).click(function () {
            $('.invoice-type li').removeClass('active');
            $(this).addClass('active');
            $('.section').hide();
            $('.section').eq(i).show();
        })

    })

})


myApp.onPageInit('search', function (page) {


    var currYear = (new Date()).getFullYear();

    // myDate.getMonth();

    var currMonth = (new Date()).getMonth();
    var currDate = (new Date()).getDate()+3;
    // var currYear = (new Date()).getFullYear();
    var opt={};
    opt.date = {preset : 'date'};
    // opt.datetime = { preset : 'date', minDate: new Date(2015,11,13,9,22), maxDate: new Date(2015,12,30,11,44), stepMinute: 5  };
    opt.datetime = {preset : 'datetime'};
    opt.time = {preset : 'time'};
    opt.default = {
        theme: 'android-ics light', //皮肤样式
        display: 'modal', //显示方式
        mode: 'scroller', //日期选择模式
        lang:'zh',
        minDate:new Date(currYear,currMonth,currDate,0,0),
        maxDate:new Date(currYear,currMonth,currDate+10,0,0),
        startYear:currYear - 0, //开始年份
        endYear:currYear + 10 //结束年份
    };

    $("#date").scroller('destroy').scroller($.extend(opt['date'], opt['default']));
    var optDateTime = $.extend(opt['datetime'], opt['default']);
    var optTime = $.extend(opt['time'], opt['default']);
    $("#appDateTime").mobiscroll(optDateTime).datetime(optDateTime);
    $("#appTime").mobiscroll(optTime).time(optTime);

        $('#page-search a').click(function(){

            if($('#filmNo_search').val()=='') {
                myApp.alert("请选择观看影片",'');
                // form.userId.focus();
                return false;
             }
            var filmNo=$('#filmNo_search').val();


            var url=$('#thisUrl').html()+"?filmNo="+filmNo;
             if($('#date').val()) {
               url+='&data='+$('#date').val();


                var currYear = (new Date()).getFullYear();

                // myDate.getMonth(); 

                var currMonth = (new Date()).getMonth()+1;
                var currDate = (new Date()).getDate()+2;



               date1=new Date(currYear+'/'+currMonth+'/'+currDate);
                date2=new Date($('#date').val());

               //  alert(date1);
               //  alert(date2);

              
                 if(Date.parse(date1)>Date.parse(date2)){
                     myApp.alert("请选择两天后的时间",'');
                      return false;



                 }

             }


           mainView.router.loadPage(url);

            
        })
         
})

myApp.onPageInit('orderBc', function (page) {

   $$('.root').on('click', function () {
        myApp.pickerModal('.picker-info');
    });

    //订单删除
    $('#editBc,#delectBc').remove();
    $('#page-orderBc').parent().before('<div id="editBc">编辑</div>');
    $('#page-orderBc').parent().before('<div id="delectBc">删除</div>');

    $('#editBc').on('click',function(){
        $(this).hide();
        $('#delectBc').show();
        $('.check-order').removeAttr('disabled');
        $('.check-order').parents('.order-section').find('h4 em').show();
    });

    $('#delectBc').on('click',function(){
          var ids='';
             $("input[name='orderbc_del']:checked").each(function(i,a){    
                    var $this = $(this);
                    var uuid=$this.attr("uuid");
                    ids+= uuid+',';
                     //如果元素已经存在，不再添加                                  
             }); 
           if(!ids) {
            myApp.alert('请选择要删除的订单！','');
            return;
           }  
         myApp.confirm('是否删除选中订单？','提醒', function () {
        var orderbc_del_order_thisUrl = $('#orderbc_del_order_thisUrl').html();
             $("input[name='orderbc_del']:checked").parents('.order-section').remove()      
            $.ajax({
                global:true,
                type: "post",
                url:orderbc_del_order_thisUrl,
                data: 'ids='+ids,
                success: function(msg){


                }
            })
        })

    });
    //是否删除选中订单(2.0改版)
    $('.codePw b').on('click',function(){
         var  _this = $(this);
        myApp.confirm('是否删除选中订单？','温馨提示',function(){
     	   var uid=_this.parents('label').find("input[name='orderbc_del']").attr('uuid');
     	   var orderbc_del_order_thisUrl = $('#orderbc_del_order_thisUrl').html();
     	   $.ajax({
                global:true,
                type: "post",
                url:orderbc_del_order_thisUrl,
                data: 'ids='+uid,
                success: function(msg){
                }
            })
            _this.parents('label').remove();
        })
    })

 

});


myApp.onPageInit('userLogin', function (page) {
 $(function(){
   $('.loginBtn1').click(function(){
    var mobile=$('input[name="mobile"]').val();
    var telPassWord=$('input[name="telPassWord"]').val();
    if(mobile.trim()==''){
        myApp.alert('请输入电话号码！','');
      return false;
    }else if(telPassWord.trim()==''){
        myApp.alert('请输入会密码','');
      return false;
    }else{
    // myApp.showIndicator();
       userLogin1(mobile, telPassWord);
    //   // $('#myform').submit();
    }
  });
  $('.loginBtn2').click(function(){



    var placeNo=$('input[name="cinemaCode"]').val();

    // alert(placeNo);
    var cardId=$('input[name="cardId"]').val();
    var pwd=$('input[name="passWord"]').val();
    if(cardId.trim()==''){
        myApp.alert('请输入会员卡号','');
      return false;
    }else if(pwd.trim()==''){
        myApp.alert('请输入会员卡密码','');
      return false;
    }else{
      myApp.showIndicator();
      userLogin(cardId, pwd,placeNo);
      // $('#myform').submit();
    }
  });
});

 function userLogin1 (mobile, telPassWord) {
  $("#loginform1").ajaxSubmit({  
        type:"post",  //提交方式  
        dataType:"json", //数据类型 
         url:$('#login_thisUrl').html(), //请求url 
        data:{'mobile':mobile, 'pwd':telPassWord}, 
        success:function(data){ //提交成功的回调函数 
          // myApp.hideIndicator();
            if(data.status == 0){



                 location.href= data.data.url;
              }else{
                myApp.alert(data.content,'');
              }
        } 
    });
} 
function userLogin (cardId, pwd,placeNo) {

  $("#loginform").ajaxSubmit({  
        type:"post",  //提交方式  
        dataType:"json", //数据类型 
         url:$('#login_thisUrl').html(), //请求url 
        data:{'cardId':cardId, 'pwd':pwd, 'placeNo':placeNo}, 
        success:function(data){ //提交成功的回调函数 
          myApp.hideIndicator();
            if(data.status == 0){
                location.href= data.data.url;
              }else{
                myApp.alert(data.content,'');
              }
        } 
    });
}

  //门店选择
  $('#select').click(function(e){
    $('#select').find('ul').hide();
    $(this).find('ul').show();
    e.stopPropagation();
  });
  $('#select li').hover(function(e){
    $(this).toggleClass('on');
    e.stopPropagation();
  });
  $('#select li').click(function(e){
    var val = $(this).text();
    $(this).parents('#select').find('b').text(val);
    $(this).parents('#select').find('input').val($(this).attr('data-value'));
    $('#select ul').hide();
    e.stopPropagation();
  });
  $(document).click(function(){
    $('#select ul').hide();
  });
})


myApp.onPageInit('confirmOrder', function (page) {

    var fixedPrice = parseFloat($('#allprice').val()),// 初始总价价
        initialPrice =parseFloat($('#iprice').val()),//初始小计价
        inputV = parseInt($('#unitsc').val()),//座位最小值
        unitPrice = parseFloat($('#wp').val()); //座位单价
    $('.price').text(fixedPrice);

    $('#confirmOrder_num').keyup(function(){

        var inputObj = $(this),
            maxVal = parseInt($('#maxsc').val()),
            minVal = 1,
            inputVal = parseInt(inputObj.val()),//输入的座位数
            xiaoji = $('.fixedPrice'),
            costObj = $('.price');

        inputObj.val(inputObj.val().replace(/[^\d]/g,''));

        if(inputVal > maxVal){
            inputObj.val(maxVal);
            inputVal = maxVal;
        }
        if(inputVal < minVal){
            inputObj.val(minVal)
        }

        if(inputVal <= inputV ){ //小于基础座位数
            costObj.text(fixedPrice); //总价
            xiaoji.text(initialPrice); //小计价
        }else{
            if(!isNaN(inputVal)){
                costObj.text(unitPrice*(inputVal-inputV) + fixedPrice);
                xiaoji.text(unitPrice*(inputVal-inputV) + initialPrice);
            }else{
                costObj.text('');
            }
        }

    });

});
//提交订单
function confirmOrder_payoff() {
    var confirmOrder_thisUrl = $('#confirmOrder_thisUrl').html();
    var confirmOrder_tel = $('#confirmOrder_tel').val();
    var confirmOrder_num = $('#confirmOrder_num').val();
    var  sedata='';
    if(confirmOrder_tel) {
        if(!$("#confirmOrder_tel").val().match(/^([\+][0-9]{1,3}[ \.\-])?([\(]{1}[0-9]{2,6}[\)])?([0-9 \.\-\/]{3,20})((x|ext|extension)[ ]?[0-9]{1,4})?$/)){
          myApp.alert("手机号码格式不正确！请重新输入！",'');
          return;
        } 
        if(confirmOrder_tel.length<11){
       	 
       	 myApp.alert("手机号码少于11位！当前长度"+confirmOrder_tel.length+'位','');
            return;
       	 
        }
        
        sedata+='&confirmOrder_tel='+confirmOrder_tel;
    }else{
         myApp.alert('请输入手机号','');
         return;
    
    }
    sedata+='&num='+confirmOrder_num;
//    if(!confirmOrder_num) {    	
//    	 myApp.alert('请输入观影人数','');
//         return;
//    	
//    }
    
//    alert($);
//    return;
myApp.showIndicator();

    $.ajax({
            global:true,
            type: "post",
            url:confirmOrder_thisUrl,
            data: sedata,
            success: function(data){
                myApp.hideIndicator();
               if(data.status == 0){
            	   location.href=confirmOrder_thisUrl;
                     //mainView.router.loadPage(confirmOrder_thisUrl);
                }else{
                      myApp.alert(data.content);
                }       
            }
         
      }) 


       
}


myApp.onPageInit('payoff', function (page) {


    function cost(obj){
        var obj = obj.find('input').attr('ratio');
        var payoff_all_price=$('#payoff_all_price').val();
        payoff_all_price=parseFloat(payoff_all_price*obj/100).toFixed(1);
        $('#payoff_all_price_html').text(payoff_all_price);
    }

    //页面初始应付金额显示(默认全额支付)
    cost($('.payp .item').eq(1));

    //点击单选框价格变化
     $('#page-payoff label.payoff_type').click(function(){
         cost($(this));
     });

    //弹窗提示
    $$('.payp .item').on('click', function () {
    	$this=$(this);
//    	alert($this.find('input').attr('ratio'));
        if($this.find('input').attr('ratio')!=100){
            myApp.alert('预定金为订单总额的'+$this.find('input').attr('ratio')+'%','提醒');
        }else if($this.find('input').attr('ratio')!=100){
            myApp.confirm('预定金为订单总额的'+$this.find('input').attr('ratio')+'%','提醒');
        }


//         //点击确定选中变化、价格变化(排序上默认第一个是支付金)
//        $('body').on('click','.modal-button-bold',function(){
//            $('.payp .item').each(function(){
//                $(this).find('input')[0].checked = false;
//            })
//            $('.payp .item').eq(0).find('input')[0].checked = true;
//            cost($('.payp .item').eq(0));
//        });
//
//        //点击取消
//         $('body').on('click','.modal-button:eq(0)',function(){
//             var a = $('.payp .item input').index($('input:checked'));
//             $('.payp .item').each(function(){
//                 $(this).find('input')[0].checked = false;
//             })
//             $('.payp .item').eq(a).find('input')[0].checked = true;
//             cost($('.payp .item').eq(a));
//         });


     });


   var time = 15*60;

    var payoff_index_thisUrl=$('#payoff_index_thisUrl').html();
    var t=setInterval(function  () {
        time--;
        // console.log(time);
        if (time==0) {
            clearInterval(t);
            myApp.alert('支付超时，请返回重新抢票！','温馨提醒');

            window.location.href=payoff_index_thisUrl;

                // mainView.router.loadPage(payoff_index_thisUrl);

        }
    },1000)


})


//支付订单
function payoff_paymentSuccess() {
     var  paymentTypeId=$('input[name=payoff_type]:checked').val();


     var  paymentMethod=$('input[name=paymentMethod_payoff_my-radio2]:checked').val();


     var  ratio=$('input[name=payoff_type]:checked').attr('ratio');
     var payoff_all_price=$('#payoff_all_price').val();
     var payoff_now_price=payoff_all_price*ratio/100;




     // alert(paymentMethod);


     // alert('需要支付金额'+payoff_now_price);
    // return;
    var confirmOrder_thisUrl = $('#payoff_thisUrl').html();
    // var confirmOrder_tel = $('#confirmOrder_tel').val();

    
    var  sedata='';
     if(paymentTypeId) {
         sedata+='&paymentTypeId='+paymentTypeId;
     }
     if(paymentMethod) {
         sedata+='&paymentMethod='+paymentMethod;
     }
     if(payoff_all_price) {
         sedata+='&payoff_all_price='+payoff_all_price;
     }
     if(payoff_now_price) {
         sedata+='&payoff_now_price='+payoff_now_price;
     }
     // if(paymentTypeId) {
     //     sedata+='&paymentTypeId='+paymentTypeId;
     // }

$('#payoff_paymentSuccess_input').attr('href',"javascript:myApp.alert('请不要重新提交！','')");
// alert('还是可以按');

// return;

// alert(confirmOrder_tel);

myApp.showIndicator();

  
    $.ajax({
            global:true,
            type: "post",
            url:confirmOrder_thisUrl,
            data: sedata,
            success: function(data){
                myApp.hideIndicator();
                        if(data.status == 0){
                            _AP.pay(data.data);
                        }else{
                            myApp.alert(data.content,'');
                        }       
            }
     }) 


       
}
//添加收货人信息
function addressReceiving_consigneeInfo() {
	
	
	
	
    var addressReceiving_thisUrl = $('#addressReceiving_thisUrl').html();
    var addressReceiving_name = $('#addressReceiving_name').val();
    var addressReceiving_phone = $('#addressReceiving_phone').val();
    var addressReceiving_address = $('#addressReceiving_address').val();

    var  sedata='';
    if(addressReceiving_name) {
        sedata+='&addressReceiving_name='+addressReceiving_name;
    }else{   	
    	 myApp.alert('请输入收货人！','');
    	 return;
    }
    if(addressReceiving_phone) {
        if(!$("#addressReceiving_phone").val().match(/^([\+][0-9]{1,3}[ \.\-])?([\(]{1}[0-9]{2,6}[\)])?([0-9 \.\-\/]{3,20})((x|ext|extension)[ ]?[0-9]{1,4})?$/)){
          myApp.alert("手机号码格式不正确！请重新输入！",'');
          return;
        } 
        sedata+='&addressReceiving_phone='+addressReceiving_phone;
    }else{   	
	   	 myApp.alert('请输入电话号码！','');
	   	 return;
    }
     if(addressReceiving_address) {
        sedata+='&addressReceiving_address='+addressReceiving_address;
    }else{   	
	   	 myApp.alert('请输入详细地址！','');
	   	 return;
    }

     $.ajax({
            global:true,
            type: "post",
            url:addressReceiving_thisUrl,
            data: sedata,
            success: function(msg){
                   mainView.router.loadPage(addressReceiving_thisUrl);
            }
      })
}

//修改收货人信息
function editaddressReceiving_consigneeInfo() {
    var editaddressReceiving_thisUrl = $('#editaddressReceiving_thisUrl').html();
    var editaddressReceiving_id = $('#editaddressReceiving_id').val();
    var editaddressReceiving_name = $('#editaddressReceiving_name').val();
    var editaddressReceiving_phone = $('#editaddressReceiving_phone').val();
    var editaddressReceiving_address = $('#editaddressReceiving_address').val();

    var  sedata='';
    if(editaddressReceiving_id) {
        sedata+='&id='+editaddressReceiving_id;
    }
    if(editaddressReceiving_name) {
        sedata+='&editaddressReceiving_name='+editaddressReceiving_name;
    }else {
         myApp.alert('请输入收货人！','');
         return;        
    }
    if(editaddressReceiving_phone) {
         if(!$("#editaddressReceiving_phone").val().match(/^([\+][0-9]{1,3}[ \.\-])?([\(]{1}[0-9]{2,6}[\)])?([0-9 \.\-\/]{3,20})((x|ext|extension)[ ]?[0-9]{1,4})?$/)){
          myApp.alert("手机号码格式不正确！请重新输入！",'');
          return;
        } 
         
       
         
        sedata+='&editaddressReceiving_phone='+editaddressReceiving_phone;
    }else {
         myApp.alert('请输入电话号码！','');
         return;
    }
     if(editaddressReceiving_address) {
        sedata+='&editaddressReceiving_address='+editaddressReceiving_address;
    }else{
         myApp.alert('请输入详细地址！','');
         return;
    }
     // alert(sedata);
     // return;

     $.ajax({
            global:true,
            type: "post",
            url:editaddressReceiving_thisUrl,
            data: sedata,
            success: function(msg){
                   mainView.router.loadPage(editaddressReceiving_thisUrl);
            }
      })
}
//删除收货人信息
function addressReceiving_del(id) {
    var addressReceiving_del_thisUrl = $('#addressReceiving_del_thisUrl').html();
    var consigneeInfo_thisUrl = $('#consigneeInfo_thisUrl').html();

    // alert(consigneeInfo_thisUrl);

    myApp.confirm('删除是不可恢复的，你确认要删除吗？','', function () {
        $('#consigneeInfo_div_'+id).remove();
       
        $.ajax({
                global:true,
                type: "post",
                url:addressReceiving_del_thisUrl,
                data: 'id='+id,
                success: function(msg){

                    $('#consigneeInfo_div_'+id).remove();

                }
          })
           
    });






}

//提交发票内容
function invoice_payoff(type) {

    var invoice_thisUrl = $('#invoice_thisUrl').html();
    var invoice_name1 = $('#invoice_name1').val();
    var invoice_content = $('input[name=invoice_content]:checked').val();

    var invoice_name2 = $('#invoice_name2').val();
    var invoice_identificationNum = $('#invoice_identificationNum').val();
    var invoice_address = $('#invoice_address').val();
    var invoice_phone = $('#invoice_phone').val();
    var invoice_bank = $('#invoice_bank').val();
    var invoice_bankAccount = $('#invoice_bankAccount').val();




    var  sedata='invoice_type='+type;

    if(type==1){
        if(invoice_name1) {
            sedata+='&invoice_name1='+invoice_name1;
        }else{
             myApp.alert('请输入发票抬头！','');
             return;
        }
        if(invoice_content) {
            sedata+='&invoice_content='+invoice_content;
        }else{
             myApp.alert('请选择发票内容！','');
             return;
        }
    }else {
        if(invoice_name2) {
            sedata+='&invoice_name2='+invoice_name2;
        }else{
             myApp.alert('请输入单位名称！','');
             return;
        }

        if(invoice_identificationNum) {
            sedata+='&invoice_identificationNum='+invoice_identificationNum;
        }else{
             myApp.alert('请输入纳税人识别号！','');
             return;
        }
        if(invoice_address) {
            sedata+='&invoice_address='+invoice_address;
        }else{
             myApp.alert('请输入注册地址！','');
             return;
        }
        if(invoice_phone) {
            sedata+='&invoice_phone='+invoice_phone;
        }else{
             myApp.alert('请输入注册电话！','');
             return;
        }
        if(invoice_bank) {
            sedata+='&invoice_bank='+invoice_bank;
        }else{
             myApp.alert('请输入开户银行！','');
             return;
        }
        if(invoice_bankAccount) {
            sedata+='&invoice_bankAccount='+invoice_bankAccount;
        }else{
             myApp.alert('请输入银行帐户！','');
             return;
        }
    }       
    $.ajax({
            global:true,
            type: "post",
            url:invoice_thisUrl,
            data: sedata,
            success: function(msg){
                  mainView.router.loadPage(invoice_thisUrl);
         }
     })
} 




//存储缓存
function invoice_for_session() {




    var invoice_for_session_thisUrl = $('#invoice_for_session_thisUrl').html();
    var invoice_name1 = $('#invoice_name1').val();
    var invoice_content = $('input[name=invoice_content]:checked').val();

    var invoice_name2 = $('#invoice_name2').val();
    var invoice_identificationNum = $('#invoice_identificationNum').val();
    var invoice_address = $('#invoice_address').val();
    var invoice_phone = $('#invoice_phone').val();
    var invoice_bank = $('#invoice_bank').val();
    var invoice_bankAccount = $('#invoice_bankAccount').val();


    var sedata='';
    if(invoice_name1) {
            sedata+='&invoice_name1='+invoice_name1;
    }
    if(invoice_content) {
            sedata+='&invoice_content='+invoice_content;
    }
   
    if(invoice_name2) {
            sedata+='&invoice_name2='+invoice_name2;
     }

    if(invoice_identificationNum) {
            sedata+='&invoice_identificationNum='+invoice_identificationNum;
     }
    if(invoice_address) {
            sedata+='&invoice_address='+invoice_address;
    }
    if(invoice_phone) {
            sedata+='&invoice_phone='+invoice_phone;
    }
    if(invoice_bank) {
            sedata+='&invoice_bank='+invoice_bank;
    }
    if(invoice_bankAccount) {
            sedata+='&invoice_bankAccount='+invoice_bankAccount;
    }


// alert(invoice_for_session_thisUrl);
    
    
    $.ajax({
        global:true,
        type: "post",
        url:invoice_for_session_thisUrl,
        data: sedata,
        success: function(msg){
         }
     })


    // alert(sedata);
}  


//修改默认收票人       
function changmark(id) {
    // alert(id);
    // return;
    var consigneeInfo_changmark_thisUrl = $('#consigneeInfo_changmark_thisUrl').html();

    $.ajax({
            global:true,
            type: "post",
            url:consigneeInfo_changmark_thisUrl,
            data: 'id='+id,
            success: function(msg){


                  // mainView.router.loadPage(invoice_thisUrl);

            // $('#consigneeInfo_div_'+id).remove();

         }
     })
}
//取消发票 

function invoice_cancel() {

     var invoice_cancel_thisUrl = $('#invoice_cancel_thisUrl').html();
      var invoice_thisUrl = $('#invoice_thisUrl').html();


     $.ajax({
            global:true,
            type: "post",
            url:invoice_cancel_thisUrl,
            data: '',
            success: function(msg){

                $('#payoff_invoice_flag').html('否');

                


                   mainView.router.loadPage(invoice_thisUrl);

            // $('#consigneeInfo_div_'+id).remove();

         }
     })

    


}
function invoice_ok() {
    // alert(111);

     $('.invoice-type').show();
     $('.s1').show();


     

    


}





