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
    },
});

// Export selectors engine
var $$ = Dom7;

// Add view
var mainView = myApp.addView('.view-main', {
    // Because we use fixed-through navbar we can enable dynamic navbar
    dynamicNavbar: true
});

// Callbacks to run specific code for specific pages, for example for About page:
var w=$(document).width();
myApp.onPageInit('indexIndex', function (page) {
    var w=$(document).width();
        $('.index-swiper-container').height(w*0.33);
         var indexSwiper = myApp.swiper('.index-swiper-container'/*, {
            pagination:'.pagination',
            loop:true,
            grabCursor: true,
            paginationClickable: true
          }*/);
});

myApp.onPageInit('getWeiXinCode', function (page) {
    window.location.href = $('#goWeiXinUrl').val();
})
myApp.onPageInit('userLogin', function (page) {
$(function(){
  $('.loginBtn a').click(function(){
    var cardId=$('input[name="cardId"]').val();
    var pwd=$('input[name="passWord"]').val();
    if(cardId.trim()==''){
      alert('请输入会员卡号');
      return false;
    }else if(pwd.trim()==''){
      alert('请输入会员卡密码');
      return false;
    }else{
      userLogin(cardId, pwd);
      // $('#myform').submit();
    }
  });
});

  
function userLogin (cardId, pwd) {
  myApp.showIndicator();

  $("#loginform").ajaxSubmit({  
        type:"post",  //提交方式  
        dataType:"json", //数据类型 
        url:$('#thisUrl').html(), //请求url 
        data:{'cardId':cardId, 'pwd':pwd}, 
        success:function(data){ //提交成功的回调函数 
          myApp.hideIndicator();
            if(data.status == 0){
                // alert(data.data.url);
                window.location.href=data.data.url;
                mainView.router.loadPage(data.data.url);
              }else{
                myApp.alert(data.content);
              }
        } 
    });
}

//登录方式切换
 var tabsSwiper = new Swiper('.swiper-container',{
    onlyExternal : true,
    speed:500
  })
  $(".tabs a").on('touchstart mousedown',function(e){
    e.preventDefault()
    $(".tabs .active").removeClass('active')
    $(this).addClass('active')
    tabsSwiper.swipeTo( $(this).index() )
  })
  $(".tabs a").click(function(e){
    e.preventDefault()
  })
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
});



myApp.onPageInit('plan', function (page) {
    // run createContentPage func after link was clicked
    // $$('.create-page').on('click', function () {
        // createContentPage();
var w = $("body").width();
var i = $("#dateBody > li").length;
var n = $("#dateBody > li").width();
var planDate = '';

var __UPLOAD__ = $('#uploadUrl').html();

var __IMG__ = $('#imgUrl').html();
var __JS__ = $('#__JS__').html();
$("#dateBody").width((parseInt(n*i)));
$("#dateBody > li").width(w*0.33);
$("#dateBody > li").click(function(){
$(this).addClass("cur").siblings().removeClass("cur")}
);
$("#dateBody").find("li").each(function(){
    $(this).click(function(){
        planDate = $(this).attr("planDate");
        getPlanlist(planDate);
    });
});

function getPlanlist(planDate){
var details = $('#details').html();
myApp.showIndicator();
    $.get($('#planajax').html(),{
        cinemaCode:$('#nowCinemaCode').html(),
        startTime:planDate,
        random:Math.random(),
        }, function(msg) {
             myApp.hideIndicator();
            var data=JSON.parse(msg);
            $('.planList').html('');
            if(data){
                var indexSeat = $('#indexSeat').html();
                $.each(data,function(k,value){
                    var str="";
                     str+='<dl><dt><a class="filmLink" href="' + details + '?filmNo=' + value['filmNo'] +'" ><div class="filmImg">'+
                        '<img src="';
                     if(value['film']['image']){
                         str+= __UPLOAD__ +value['film']['image'];
                     } else{
                         str+= __IMG__ + '/movie/default.jpg';
                     }
                     str+='" height="100%"  /></div><div class="filmInfo"><h2>'+
                        value['filmName']+'</h2><span>'+value['film']['score']/10+'</span><p>影片片长:'+value['totalTime']+'分钟</p>'+
                    '</div><div class="clear"></div> </a></dt>';
                     $.each(value.planInfo,function(key,v){
                         
                         str+='<a class="planInfo" href="'+indexSeat+'?featureAppNo='+v['featureAppNo']+'"><div class="planDate"><span>'
                                 +v['startTime']+'</span><em>￥'+v['memberPrice']+'</em></div><div class="planType"><span>'
                                 +v['copyLanguage']+v['copyType']+'</span><em>￥'+v['listingPrice']+'</em></div></a>';
                    });
                    str+='<div class="clear"></div></dl>';
                    $('.planList').append(str);
                });             
            }
    }) ;        
}

});


myApp.onPageInit('filmList', function (page) {

});


    function details(filmNo){
    $.get($('#indexHasfilm').html(),{filmNo:filmNo},function(json){
        if(json=='1'){

            mainView.router.loadPage($('#details').html() +"?filmNo="+filmNo);
        }else{
            myApp.alert('该影片信息未编辑');
        }
    });
}




myApp.onPageInit('seat', function (page) {
    $('#seatPage').css('display', 'none');
	var url=$('#seaturl').html();
    var featureAppNo=$('#featureAppNo').html();
	window.location.href = url;
   
});



myApp.onPageInit('userIndent', function (page) {


    $(function(){ 
 var userSeatLock = $('#userSeatLock').html();
    var orderstatus = $('#orderstatus').html();

        if(orderstatus != '0'){
        var gourl=$('#gourl').html();;
        var cancelurl=$('#cancelurl').html();


        myApp.confirm('您之前有一笔未支付订单，去支付？', 
        function () {
          window.location.href=gourl;
        },
        function () {
            $.get(cancelurl,{},function(msg){});
        }
    ); 
        }



        var hsc=$('#hsc').html();;
        $(".firmBtm").bind("click",function(){
            function isMobil(s) {   //验证手机号 
                var patrn=/^1\d{10}$/; 
                if (!patrn.exec(s)){
                    return false 
                }else{
                    return true
                }
            }
            if(hsc !='ok'){
                alert("您最多可再买"+hsc+"张本场次影票!");
                return false;
            }
            var mobile=$("input[name='mobile']").val();
            if(mobile==""){
                alert("手机号码不能为空！");
                return false;
            }
            if(!isMobil(mobile)){
                alert("请正确输入手机号码！");
                return false;
            }
            var featureAppNo=$('#featureAppNo').html();
            var datas=$('#mydata').html();
            myApp.showIndicator();
            $.get(userSeatLock,{datas:datas,featureAppNo:featureAppNo,mobile:mobile},function(msg){
                myApp.hideIndicator();
                var data=JSON.parse(msg);

                url = $('#payUrl').html();


                if(data['ResultCode']=='0'){
                    location.href=url + "?orderid="+data['OrderCode'];      
                }else{
                    myApp.alert(data['ResultCode']);   
                }
            });
        });
        
    });
        $("#firmOrder").slideDown(1000);

});

myApp.onPageInit('filmDetails', function (page) {

    /*=== 默认为 standalone ===*/

    var photos = [];

    $(".phpotoList").each(function(k,v){
        photos[k] = $(this).html();
    });

    var myPhotoBrowserStandalone = myApp.photoBrowser({
        'photos' : photos
    });
    //点击时打开图片浏览器
    $$('.filmDetails >ul>li').on('click', function () {
        myPhotoBrowserStandalone.open();
    });
});

var cinemaajax = $('#cinemaajax').html();
myApp.onPageInit('planFilm', function (page) {
    var w = $("body").width();
     var i = $("#dateBody > li").length;
     var n = $("#dateBody > li").width();
        //$("#dateBody").width((parseInt(n*i)));
        //$("#dateBody > li").width(w*0.33);
        $("#dateBody > li").click(function(){
            $(this).addClass("cur").siblings().removeClass("cur")}
        )
        $("#dateBody").find("li").each(function(){
            $(this).click(function(){
                getPlanlist($(this).attr("planDate"));
            });
        });


        

        function getPlanlist(planDate){
            myApp.showIndicator();
            var filmNo=$('#filmCode').val();

            $.get($('#cinemaplanajax').html(),{
                filmNo:filmNo,
                startTime:planDate,
                random:Math.random(),
                }, function(msg) {
                    myApp.hideIndicator();
                    var data=JSON.parse(msg);
                    $('.showPlan').html('');
                    if(data){
                        var indexSeat = $('#indexSeat').html();
                        $.each(data,function(k,value){
                            var str="";
                             str+='<a href="'+ indexSeat + "?featureAppNo="+value['featureAppNo']+'"><ul>'+
                                '<li class="tal"><div class="ledTime">'+value['start']+'</div><p>'+value['end']+'结束</p></li>'+
                            '<li class="tac"><div  class="planStyle">'+value['copyLanguage']+'/'+value['copyType']+'</div><p>'+value['hallName']+'</p></li>'+
                            '<li class="tar"><div class="planPirce">￥'+value['memberPrice']+'</div><p >￥'+value['listingPrice']+'</p></li></ul></a>';
                            $('.showPlan').append(str);
                        });             
                    }
            }) ;        
        }
$(".tar").click(function(){
        var cardStyle = $(this).parent().next("div.cardPrice").css('display');
        $(this).parent().next("div.cardPrice").slideToggle("slow").siblings(".cardPrice:visible").slideUp("slow");
        
        
        $(".tar").find("em").removeClass("cur");
        
         if(cardStyle == 'none'){
             $(this).find("em").addClass("cur");
        }
        
        
        });
var swiper = new Swiper('.swiper-container', {
    pagination: '.plan-swiper-pagination',
    slidesPerView: 5,
    centeredSlides: true,
    paginationClickable: true,
    spaceBetween:0,
    onTransitionEnd: function(swiper){
        var tdata=$('#planFilm .swiper-slide-active').attr('id');
        var cdata=$('#filmCode').val();

        if(tdata!=cdata&&tdata!=undefined){
            $('#filmCode').val(tdata);
            $('.planFilmLink').html('<a href="'+$('#details').html()+'?filmNo='+tdata+'"><span>'+$('#'+tdata).attr('name')+'</span><b>'+$('#'+tdata).attr('score')+'</b></a>');
            myApp.showIndicator();
            $.get($('#cinemaajax').html(),{
                filmNo:tdata,
                random:Math.random(),
                }, function(json) {
                    myApp.hideIndicator();
                    var msg=JSON.parse(json);
                    var data=msg.plans;
                    var times=msg.planTime;
                    $('#dateBody').html('');
                    if(times){
                        var str="";
                        $.each(times,function(k,value){
                             str+='<li planDate="'+value['time']+'" ';
                             if(k==0){
                                 str+='class="cur"';
                             }
                             str+='>'+value['instr']+value['dtime']+'</li>';
                        });         
                        $('.dateBody').append(str);
                    }
                    $('.showPlan').html('');
                    if(data){
                        var indexSeat = $('#indexSeat').html();
                        $.each(data,function(k,value){
                            var str="";
                             str+='<a href="'+ indexSeat + "?featureAppNo="+value['featureAppNo']+'"><ul>'+
                                '<li class="tal"><div class="ledTime">'+value['start']+'</div><p>'+value['end']+'结束</p></li>'+
                            '<li class="tac"><div  class="planStyle">'+value['copyLanguage']+'/'+value['copyType']+'</div><p>'+value['hallName']+'</p></li>'+
                            '<li class="tar"><div class="planPirce">￥'+value['memberPrice']+'</div><p >￥'+value['listingPrice']+'</p></li></ul></a>';
                            $('.showPlan').append(str);
                        });             
                    }
                        $("#dateBody > li").click(function(){
                            $(this).addClass("cur").siblings().removeClass("cur")}
                        )
                        $("#dateBody").find("li").each(function(){
                            $(this).click(function(){
                                getPlanlist($(this).attr("planDate"));
                            });
                        });
                    $("#dateBody").find("li").each(function(){
                        $(this).click(function(){
                            getPlanlist($(this).attr("planDate"));
                        });
                    });
                    function getPlanlist(planDate){
                        myApp.showIndicator();

                        var filmNo=$('#filmCode').val();
                        $.get($('#cinemaplanajax').html(),{
                            filmNo:filmNo,
                            startTime:planDate,
                            random:Math.random(),
                            }, function(msg) {
                                myApp.hideIndicator();
                                var data=JSON.parse(msg);
                                $('.showPlan').html('');
                                var indexSeat = $('#indexSeat').html();
                                if(data){
                                    $.each(data,function(k,value){
                                        var str="";
                                         str+='<a href="'+ indexSeat + "?featureAppNo="+value['featureAppNo']+'"><ul>'+
                                            '<li class="tal"><div class="ledTime">'+value['start']+'</div><p>'+value['end']+'结束</p></li>'+
                                        '<li class="tac"><div  class="planStyle">'+value['copyLanguage']+'/'+value['copyType']+'</div><p>'+value['hallName']+'</p></li>'+
                                        '<li class="tar"><div class="planPirce">￥'+value['memberPrice']+'</div><p >￥'+value['listingPrice']+'</p></li></ul></a>';
                                        $('.showPlan').append(str);
                                    });             
                                }
                        }) ;        
                    }
            }) ;
        }
        
    },
});


});

myApp.onPageInit('cinemaIndex', function (page) {

    var w=$(document).width();
    $('.swiper-container').height(w*0.33);
    var mySwiper = new Swiper('.swiper-container',{
        pagination: '.pagination',
        loop:true,
        grabCursor: true,
        paginationClickable: true
    })
    
});
myApp.onPageInit('recharge', function (page) {
    window.location.href = '/Otherpay/index.html';
});


myApp.onPageInit('userIndex', function (page) {


});

function getcode(){
    $.get($('#codeUrlUrl').html(),function(json){
        if(json=='1'){
            mainView.router.loadPage($("#userCode").html());
        }else{
            myApp.alert('没有完成的订单');
        }
    });
}


function getpaying(){
    $.get($('#payingUrl').html(),function(json){
        if(json=='1'){
            mainView.router.loadPage($("#userPaying").html());
        }else{
            myApp.alert('没有等待支付订单');
        }
    });
}


myApp.onPageInit('paying', function (page) {
$(function(){
    var i,s,time= $('#lockTime').html();
    var id=setInterval(function(){
        time-=1;
        if(time>0){
            i=Math.floor(time/60);
            s=time%60;
            $('#time1').text(i+':'+s);
        }else{
            // location.href='{:U("user")}';
            $.get($('#cancelOrder').html() + "?orderid=" + $("#orderCode").html(),function(msg){
                mainView.router.loadPage($("#userIndex").html());
            });
            clearInterval(id);
        }
    },1000);



    var myApp = new Framework7({
        modalTitle:'',
        modalButtonOk:'确认',
        modalButtonCancel:'取消'
        });
    var $$ = Dom7;
    $('.out').on('click', function () {
        myApp.confirm('请确认是否取消该订单', function () {
            $.get($('#cancelOrder').html() + "?orderid=" + $("#orderCode").html(),function(msg){
                mainView.router.loadPage($("#userIndex").html());
            });
        });
    });
    $('.go').click(function(){
        mainView.router.loadPage($("#userPay").html() + "?orderid=" + $("#orderCode").html());
    });
});

});



myApp.onPageInit('userOrder', function (page) {
    $$('.confirm-ok').on('click', function () {
        myApp.confirm('请确认是否删除订单', function () {
            myApp.alert('You clicked Ok button');
        });
    });


    $('.orderTab > a').click(function(){
        $('.orderTab > a').removeClass('cur');
        $(this).addClass('cur');
        var status=$(this).attr('status');
        var str="";
        myApp.showIndicator();

        $.get($('#getOrderList').html(),{status:status},function(json){
            myApp.hideIndicator();
            var data=JSON.parse(json);
            $('.order ul').html('');
            if(data){
                $.each(data,function(k,value){
                     str+='<li><div class="orderTop">'+
                     '<span>订单号：'+value['orderCode']+'</span>'+
                     '<span>订单时间：'+value['downTime']+'</span>'+
                     '<a href="#" class="confirm-ok"></a></div><div class="orderMid"><div class="orderMidLeft">'+
                     '<img src="__UPLOAD__/'+value['image']+'" width="67.5" height="90"></div><div class="orderMidRight">'+
                     '<h2>'+value['filmName']+'</h2>'+
                     '<b>'+value['allprice']+'</b>'+
                     '<p>'+value['startTime']+'</p>'+
                     '<p><span>'+value['cinemaName']+'</span><span>'+value['hallName']+'</span></p>'+
                     '<p>'+value['seatIntroduce']+'</p></div><div class="clear"></div></div>';
                     if(value['status']=='3'){
                         str+='<div class="orderBtm"><p>取票码：'+value['printNo']+'</p></div>';
                     }
                    str+='</li>';
                });             
                $('.order ul').append(str);
            }else{
                str+='<div class="orderBg">没有发现任何购票信息</div>';
                $('.order ul').html(str);
            }
        });
    });
            
    $('#edit').click(function(){
        $('.confirm-ok').css('display','block');
        $(this).replaceWith("<a href='javascript:' id='finish'>完成</a>");
        })

});


myApp.onPageInit('userRecord', function (page) {

    $('.orderTab > a').click(function(){
        $('.orderTab > a').removeClass('cur');
        $(this).addClass('cur');
        var type=$(this).attr('type');
        $.get($('#getRecordList').html(),{type:type},function(json){
            var data=JSON.parse(json);
            $('.record ul').html('');
            var str="";
            if(data){
                $.each(data,function(k,value){
                     str+='<li><div class="recordTop">'+
                     '<span class="fl">订单号：'+value['id']+'</span>'+
                     '<span class="fr">订单时间：'+value['createTime']+'</span><div class="clear"></div></div><div class="recordMid">'+
                     '<div class="recordMidLeft"><img src="' + $('#__IMG__').html() + '/user/'+value['icon']+'.png" width="50" height="50"></div><div class="recordMidRight">'+
                     '<h2>'+value['filmName']+'</h2>'+
                     '<span>'+value['typestr']+'</span></div><div class="clear"></div></div></li>';
                });         
                 $('.record ul').append(str);
            }else{
                str+='<div class="orderBg">没有发现任何购票信息</div>';
                $('.record ul').html(str);
            }
        });
    })

});


myApp.onPageInit('userFeedback', function (page) {
        $('#content').val('');
    /*var id=setInterval(function(){
        $.get("{:U()}",function(json){
            
        }
    },3000);*/
    $('.sendBtn').click(function(){
        var content=$('#content').val();
        var myDate = new Date();
        if(content){
            if(content.length<10){
                myApp.alert('反馈内容长度不小于10字');
            }else{
                $.get($('#userFeedback').html(),{'content':content,'action':'setFeedback'},function(json){
                    if(json!='1'){
                        myApp.alert('提交失败');
                    }else{
                        myApp.alert('提交成功');
                        $('#content').val('');
                        var myDate = new Date();

                        $('.messages').prepend('<div class="messages-date">' + myDate.Format('yyyy-MM-dd HH:mm:ss') + '</div><div class="message message-sent"><div class="message-text">'+ content +'</div></div>');
                        //var str1=$('.messages').html();
                    }
                });
            }
        }else{
            myApp.alert('请输入要反馈的内容');
        }
    });
})

Date.prototype.Format = function(formatStr)   
{   
    var str = formatStr;   
    var Week = ['日','一','二','三','四','五','六'];  
  
    str=str.replace(/yyyy|YYYY/,this.getFullYear());   
    str=str.replace(/yy|YY/,(this.getYear() % 100)>9?(this.getYear() % 100).toString():'0' + (this.getYear() % 100));   
  
    str=str.replace(/MM/,this.getMonth()>9?(this.getMonth()+1):'0' + (this.getMonth() + 1));   
    str=str.replace(/M/g,this.getMonth() + 1);   
  
    str=str.replace(/w|W/g,Week[this.getDay()]);   
  
    str=str.replace(/dd|DD/,this.getDate()>9?this.getDate().toString():'0' + this.getDate());   
    str=str.replace(/d|D/g,this.getDate());   
  
    str=str.replace(/hh|HH/,this.getHours()>9?this.getHours().toString():'0' + this.getHours());   
    str=str.replace(/h|H/g,this.getHours());   
    str=str.replace(/mm/,this.getMinutes()>9?this.getMinutes().toString():'0' + this.getMinutes());   
    str=str.replace(/m/g,this.getMinutes());   
  
    str=str.replace(/ss|SS/,this.getSeconds()>9?this.getSeconds().toString():'0' + this.getSeconds());   
    str=str.replace(/s|S/g,this.getSeconds());   
  
    return str;   
}  


myApp.onPageInit('userName', function (page) {

});


function nameajax(){
    var name=$('#name').val().trim();
    if(name==''){
        myApp.alert('昵称不能为空');
    }else{
        $.get($("#userNameajax").html(),{'name':name},function(msg){
             mainView.router.loadPage($("#userInfo").html());
        });
    }
}


    function pwdajax(){
        var oldp=$('#oldp').val().trim();
        var curp=$('#curp').val().trim();
        var dp=$('#dp').val().trim();
        if(oldp==''){
            myApp.alert('原始密码不能为空');
        }else if(curp==''){
            myApp.alert('请输入新密码');
        }else if(dp!=curp){
            myApp.alert('确认密码不一致');
        }else{
            $.get($("#userPwdajax").html(),{oldp:oldp,curp:curp},function(json){
                var msg=JSON.parse(json);
                if(msg['ResultCode']=='0'){
                    mainView.router.loadPage($("#userInfo").html());
                 }else{
                     myApp.alert(''+msg['Message']);
                 }
            });
        }
    }

myApp.onPageInit('userInfo', function (page) {

// 编辑性别
    $$('.sex').on('click', function () {
        var buttons1 = [
            {
                text: '性别',
                label: true
            },
            {
                text: '男',
                bold: true,
                onClick: function() {
                    $.get($('#userSexajax').html(),{'sex':0},function(msg){
                         if(msg=='1'){
                             $$('.sex .item-after').text('男');
                         }
                     });
                }
            },
            {
                text: '女',
                onClick: function() {
                    $.get($('#userSexajax').html(),{'sex':1},function(msg){
                        if(msg=='1'){
                            $$('.sex .item-after').text('女');
                         }
                    });
                }
            }
        ];
        var buttons2 = [
            {
                text: '取消',
                color: 'red'
            }
        ];
        var groups = [buttons1, buttons2];
        myApp.actions(groups);
    });
    
//生日
    var calendarDefault = myApp.calendar({
        input: '#birthday',
        dateFormat:'2000-10-10'
    }); 



});
