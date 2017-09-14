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
var height = window.innerHeight;
// var scrolltop = document.documentElement.scrollTop;
// Add view
var mainView = myApp.addView('.view-main', {
    // Because we use fixed-through navbar we can enable dynamic navbar
    dynamicNavbar: true
});
//是否数字
function isNum(s) { 
	var patrn=/^[1-9][0-9]*$/;
	if (!patrn.exec(s)){
		return false ;
	}else{
		return true;
	}
}
function isMobil(s) {   //验证手机号 
    var patrn=/^1\d{10}$/; 
    if (!patrn.exec(s)){
        return false 
    }else{
        return true
    }
}
function Isyx(yx){//验证邮箱
	var reyx= /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/;
	return(reyx.test(yx));
	}
// Callbacks to run specific code for specific pages, for example for About page:
var w=$(document).width();
myApp.onPageInit('indexIndex', function (page) {
    var w=$(document).width();
        $('#indexIndex .index-swiper-container').height(w*0.33);
         var indexSwiper = myApp.swiper('#indexIndex .index-swiper-container'/*, {
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
  $('#userLogin .loginBtn').click(function(){
	var cinemaCode=$(this).parent().prev().prev().prev().find('input[name="cinemaCode"]').val();
	var cardId=$(this).parent().prev().prev().find('input[name="userAccount"]').val();
    var pwd=$(this).parent().prev().find('input[name="userPasswd"]').val();
    if(cardId.trim()==''){
      myApp.alert('请输入登录帐号');
      return false;
    }else if(pwd.trim()==''){
    	myApp.alert('请输入登录密码');
      return false;
    }else{
    	myApp.showIndicator();
    	$(this).parent().parent('form').ajaxSubmit({  
    	        type:"post",  //提交方式  
    	        dataType:"json", //数据类型 
    	        url:$('#userLogin #thisUrl').html(), //请求url 
    	        data:{'cardId':cardId, 'pwd':pwd,'cinemaCode':cinemaCode}, 
    	        success:function(data){ //提交成功的回调函数 
    	          myApp.hideIndicator();
    	            if(data.status == 0){
    	                // alert(data.data.url);
    	                // window.location.href=data.data.url;
    	                mainView.router.loadPage(data.data.url);
    	              }else{
    	                myApp.alert(data.content);
    	              }
    	        } 
    	    });
    }
  });
});

  


  //门店选择
  $('#userLogin #select').click(function(e){
    $('#userLogin #select').find('ul').hide();
    $(this).find('ul').show();
    e.stopPropagation();
  });
  $('#userLogin #select li').hover(function(e){
    $(this).toggleClass('on');
    e.stopPropagation();
  });
  $('#userLogin #select li').click(function(e){
    var val = $(this).text();
    $(this).parents('#userLogin #select').find('b').text(val);
    $(this).parents('#userLogin #select').find('input').val($(this).attr('data-value'));
    $('#select ul').hide();
    e.stopPropagation();
  });
  $(document).click(function(){
    $('#userLogin #select ul').hide();
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

    $("#dateBody li").eq(0).addClass("cur").siblings().removeClass("cur");
    getPlanlist($("#dateBody li").eq(0).attr("planDate"));


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
                     str+='<dl><dt><a class="filmLink" href="javascript:details(\''+ value['filmNo'] +'\')" ><div class="filmImg">'+
                        '<img src="';
                     str+= value['film']['image'];
                     str+='" height="100%"  /></div><div class="filmInfo"><h2>'+
                        value['filmName']+'</h2><span>'+value['film']['score']/10+'</span><p>影片片长:'+value['totalTime']+'分钟</p>'+
                    '</div><div class="clear"></div> </a></dt>';
                     $.each(value.planInfo,function(key,v){
                         var bool = v['copyType'].indexOf("MAX");
                         var nowClass = '';
                         // alert(bool);
                         if (bool >= 0) {
                            v['copyLanguage'] = '';
                            nowClass = 'MAX';
                         };
                         str+='<a class="planInfo" href="'+indexSeat+'?featureAppNo='+v['featureAppNo']+'"><div class="planDate"><span>'
                                 +v['startTime']+'</span><em>￥'+v['memberPrice']+'</em></div><div class="planType"><span class="'+nowClass+'">'
                                 +v['copyLanguage']+v['copyType']+'</span><em>￥'+v['listingPrice']+'</em></div></a>';
                    });
                    str+='<div class="clear"></div></dl>';
                    $('.planList').append(str);
                });             
            }
    }) ;        
}
window.clearInterval(window.timerID);
$("#scrollDiv").Scroll({line:1,speed:500,timer:2000});


});

myApp.onPageInit('package', function (page) {
	$('.voucherBtn').click(function(){
		var voucherNum=$('#voucher').val();
		if(voucherNum==''){
			myApp.alert('请输入券码');
		}else{
			 var  url = $('#url').html();
			 myApp.showIndicator();
			$.post(url,{voucherNum:voucherNum},function(data){
                myApp.hideIndicator();
                if(data['status']=='0'){
                	var htmlstr='<li><img src="/Public/Home/template/images/user/voucher'+(parseInt(data['data']['voucherType'])+1)+'.png" width="100%" />'+
                        '<div class="voucherBody"><div class="voucherPrice"><b>￥</b>'+data['data']['voucherValue']+'</div>'+
                            '<div class="voucherCode">'+data['data']['voucherNum']+'</div><div class="voucherName">'+data['data']['voucherName']+'</div>'+
                            '<div class="voucherDate">有效期至'+data['data']['validData']+'</div></div></li>';
                	$('#voucherList').prepend(htmlstr);
                }else{
                    myApp.alert(data['content']);   
                }
            },'json');
		}
	});
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
    //$('#seatPage').css('display', 'none');
	var url=$('#seaturl').html();
    var featureAppNo=$('#featureAppNo').html();
	window.location.href = url;
   
});
//注册
myApp.onPageInit('register', function (page) {
	function isMobil(s) {   //验证手机号 
        var patrn=/^1\d{10}$/; 
        if (!patrn.exec(s)){
            return false 
        }else{
            return true
        }
    }

    var validCode=true;
	$('.getBtn').click(function(){

       

		var userMobile=$('#userMobile').val();
		var codeType='register';
		var code=$(this);
		if(!isMobil(userMobile)){
			myApp.alert('请输入正确手机号码');
		}else {
        
			 var  codeUrl = $('#codeUrl').html();
			 myApp.showIndicator();
			 $.getJSON(codeUrl,{userMobile:userMobile,codeType:codeType},function(data){
		            myApp.hideIndicator();
		            if(data['status']!='0'){
		            	 myApp.alert(data['content']);   
		            }else{
							  
							 var time=60;
							//获取短信验证码
							
							if (validCode) {
								validCode=false;
								code.addClass("msgs");
								code.attr('disabled',true);
								var t=setInterval(function  () {
									time--;
									code.html(time+"秒");
									
									if (time==0) {
										clearInterval(t);
										code.html("重新获取");
										validCode=true;
										code.removeAttr("disabled");
										code.removeClass("msgs");
									}
								},1000)
								myApp.alert('验证码已发送，请查收'); 
							}
							 
						}
		        });

		}
	});
	$('#registerBtn').click(function(){
		var userMobile=$('#userMobile').val();
		var validateCode=$('#validateCode').val();
		var userPasswd=$('#userPasswd').val();
		var tuserPasswd=$('#tuserPasswd').val();
		if(!isMobil(userMobile)){
			myApp.alert('请输入正确手机号码');
		}else if(validateCode==''){
			myApp.alert('请输入验证码');
		}else if(userPasswd==''){
			myApp.alert('请输入密码');
		}else if(tuserPasswd==''){
			myApp.alert('请输入确认密码');
		}else if(tuserPasswd!=userPasswd){
			myApp.alert('密码不一致');
		}else{
			 var  url = $('#registerUrl').html();
			 var  loginUrl = $('#loginUrl').html();
			 myApp.showIndicator();
			$.getJSON(url,{userMobile:userMobile,validateCode:validateCode,userPasswd:userPasswd},function(data){
	            myApp.hideIndicator();
	            myApp.alert(data['content']);   
	            if(data['status']=='0'){
	            	setTimeout(function(){location.href=loginUrl;},3000);
	            }
	        });
		}
	});

});

//密码找回
myApp.onPageInit('find', function (page) {
	function isMobil(s) {   //验证手机号 
        var patrn=/^1\d{10}$/; 
        if (!patrn.exec(s)){
            return false 
        }else{
            return true
        }
    }
	var validCode=true;
	$('.getBtn').click(function(){
		var userMobile=$('#userMobile').val();
		var codeType='find';
		var code=$(this);
		if(!isMobil(userMobile)){
			myApp.alert('请输入正确手机号码');
		}else {
			 var  codeUrl = $('#codeUrl').html();
			 myApp.showIndicator();
			 $.getJSON(codeUrl,{userMobile:userMobile,codeType:codeType},function(data){
		            myApp.hideIndicator();
		            if(data['status']!='0'){
		            	 myApp.alert(data['content']);   
					}else{
						   var time=60;
							//获取短信验证码
							
							if (validCode) {
								validCode=false;
								code.addClass("msgs");
								code.attr('disabled',true);
								var t=setInterval(function  () {
									time--;
									code.html(time+"秒");
									
									if (time==0) {
										clearInterval(t);
										code.html("重新获取");
										validCode=true;
										code.removeAttr("disabled");
										code.removeClass("msgs");
									}
								},1000)
								myApp.alert('验证码已发送，请查收'); 
							}
						}
		        });
		}
	});
	$('#findBtn').click(function(){
		var userMobile=$('#userMobile').val();
		var validateCode=$('#validateCode').val();
		var newUserPasswd=$('#newUserPasswd').val();
		var tnewUserPasswd=$('#tnewUserPasswd').val();
		if(!isMobil(userMobile)){
			myApp.alert('请输入正确手机号码');
		}else if(validateCode==''){
			myApp.alert('请输入验证码');
		}else if(newUserPasswd==''){
			myApp.alert('请输入密码');
		}else if(tnewUserPasswd==''){
			myApp.alert('请输入确认密码');
		}else if(tnewUserPasswd!=newUserPasswd){
			myApp.alert('密码不一致');
		}else{
			 var  url = $('#findUrl').html();
			 var  loginUrl = $('#loginUrl').html();
			 myApp.showIndicator();
			$.getJSON(url,{userMobile:userMobile,validateCode:validateCode,newUserPasswd:newUserPasswd},function(data){
	            myApp.hideIndicator();
	            
	            if(data['status']=='0'){
                    var newMyApp = new Framework7({
                    modalTitle:'',
                    modalButtonOk:'确认',
                    modalButtonCancel:'关闭'
                    });
                    newMyApp.confirm(data['content'], 
                        function () {
                          location.href=loginUrl;
                        },
                        function () {
                           location.href=loginUrl;
                        });

	            	
	            }else{
                    myApp.alert(data['content']);   
                }
	        });
		}
	});

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
                myApp.alert("手机号码不能为空！");
                return false;
            }
            if(!isMobil(mobile)){
            	myApp.alert("请正确输入手机号码！");
                return false;
            }
            var featureAppNo=$('#featureAppNo').html();
            var datas=$('#mydata').html();
            myApp.showIndicator();
            $.getJSON(userSeatLock,{datas:datas,featureAppNo:featureAppNo,mobile:mobile},function(data){
                myApp.hideIndicator();
                var  url = $('#payUrl').html();


                if(data['status']=='0'){
                    location.href=url;      
                }else{
                    myApp.alert(data['content']);   
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
        console.log(photos[k]);
    });

    var myPhotoBrowserStandalone = myApp.photoBrowser({
        'photos' : photos
    });
    //点击时打开图片浏览器
    $$('.filmDetails >ul>li').on('click', function () {
        myPhotoBrowserStandalone.open();
    });
    var str='';
    var filmNo = $('#filmDetailsFilmNo').html();
    pinglunurl=$('#pinglunurl').html();
    zanurl=$('#zanurl').html();
    delzanurl=$('#delzanurl').html();
    $('#reply-area a').click(function(){
    	var text=$(this).prev();
    	var content=text.val();
    	if(content!=''){
    		$.post($('#addview').html(),{'content':content,'thisUrl':'/index/details/filmNo/'+filmNo+'.html'},function(data){
    			if(data.status=='0'){
    				$('.comment-title').html('');
    				 $.each(data.data,function(k,value){
    					 str+='<div class="comment-main"><h4><img src="'+value['headImage']+'">'+value['otherName']+'</h4><article>'+value['content']+'</article>'+
    					 '<section><div class="item-block"><div class="datetime">'+value['time']+'</div><div class="comment-area"><span onclick="zan(this)" pid="'+value['id']+'">'+value['clickNum']+
    					 '</span><a onclick="pinglun(this)" pid="'+value['id']+'" hasclick="'+value['hasclick']+'">'+value['lookNum']+'</a></div></div></section></div>';
    				 });
    				 text.val('');
    				$('.comment-title').html(str);
    			}else{
    				myApp.alert(data.content);
                    if (data.status == '20001') {
                        mainView.router.loadPage(data.data);
                    };
                    
    			}
    		},'json');
    	}
    });
});
myApp.onPageInit('comment', function (page) {
	 zanurl=$('#zanurl').html();
	delzanurl=$('#delzanurl').html();
    var str='';
    $('#reply-area2 a').click(function(){
    	var text=$(this).prev();
    	var content=text.val();
    	if(content!=''){
    		$.getJSON($('#addview').html(),{'content':content,'backUrl':'index/details.html?filmNo=051200902015'},function(data){
    			if(data.status=='0'){
    				$('#text').html('');
    				 $.each(data.data,function(k,value){
    					 str+=' <div class="comment-follow"><h4><img src="'+value['headImage']+'">'+value['otherName']+' <div class="datetime">'+value['time']+'</div></h4>'+
    			                '<article>'+value['content']+'</article></div>';
    				 });
    				 text.val('');
    				$('#text').html(str);
    				$('.comment-area a').html(data.data.length);
    			}
    		});
    	}
    });
});
var pinglunurl='';
var zanurl='';
var delzanurl='';
function pinglun(obj){
	var pid=$(obj).attr('pid');
	location.href=pinglunurl+'?pid='+pid;
}
function zan(obj){
	var pid=$(obj).attr('pid');
	var hasclick=$(obj).attr('hasclick');
	var gourl='';
	var no='';
	if(hasclick=='1'){
		gourl=delzanurl;
		no=0;
        $(obj).removeClass('active');
	}else{
		gourl=zanurl;
		no=1;
        $(obj).addClass('active');
	}
	$.getJSON(gourl,{pid:pid},function(data){
		if(data.status=='0'){
			$(obj).html(data.data);
			$(obj).attr('hasclick',no);
		}
	});
}
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
                            '<li class="tar"><div class="planPirce">￥'+value['memberPrice']+'</div><s >￥'+value['listingPrice']+'</s></li></ul></a>';
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

var newSwiper = new Swiper('#planFilm .swiper-container', {
    // pagination: '.plan-swiper-pagination',
    slidesPerView: 5,
    centeredSlides: true,
    paginationClickable: true,
    spaceBetween:0,
    onTransitionEnd: function(swiper){
        var tdata=$('#planFilm .swiper-slide-active').attr('id');
        var cdata=$('#filmCode').val();
        if(tdata!=cdata&&tdata!=undefined){
            $('#filmCode').val(tdata);
            $('.planFilmLink').html('<a href="javascript:details(\''+tdata+'\')"><span>'+$('#'+tdata).attr('name')+'</span><b>'+$('#'+tdata).attr('score')+'</b></a>');
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
    window.location.href = '/user/recharge.html';
});


myApp.onPageInit('userIndex', function (page) {


});

myApp.onPageInit('password', function (page) {
	$$('#pwdid1').on('click', function () {
		var oldPasswd=$('#oldPasswd1').val();
		var newMobilePasswd=$('#newMobilePasswd').val();
		var tnewMobilePasswd=$('#tnewMobilePasswd').val();
		if(oldPasswd==''){
			myApp.alert('请输入原密码');
		}else if(newMobilePasswd==''){
			myApp.alert('请输入密码');
		}else if(tnewMobilePasswd==''){
			myApp.alert('请输入确认密码');
		}else if(newMobilePasswd!=tnewMobilePasswd){
			myApp.alert('密码不一致');
		}else{
			$.getJSON($('#userSexajax').html(),{oldPasswd:oldPasswd,newMobilePasswd:newMobilePasswd},function(data){
				myApp.hideIndicator();
				myApp.alert(data['content']);
				if(data['status']=='0'){
					setTimeout(function(){window.location.href = '/user/info.html';},3000);
				}
			});
		}
	});
	$$('#pwdid').on('click', function () {
		var oldPasswd=$('#oldPasswd').val();
		var newCardPasswd=$('#newCardPasswd').val();
		var tnewCardPasswd=$('#tnewCardPasswd').val();
		if(oldPasswd==''){
			myApp.alert('请输入原密码');
		}else if(newCardPasswd==''){
			myApp.alert('请输入密码');
		}else if(tnewCardPasswd==''){
			myApp.alert('请输入确认密码');
		}else if(newCardPasswd!=tnewCardPasswd){
			myApp.alert('密码不一致');
		}else{
			$.getJSON($('#userSexajax').html(),{oldPasswd:oldPasswd,newCardPasswd:newCardPasswd},function(data){
				myApp.hideIndicator();
				myApp.alert(data['content']);
				if(data['status']=='0'){
					setTimeout(function(){window.location.href = '/user/info.html';},3000);
				}
			});
		}
	});

});

myApp.onPageInit('addcard', function (page) {
	var myApp = new Framework7({
		modalTitle:'温馨提示',
		modalButtonOk: '确认',
    	modalButtonCancel: '取消'
		});
	$$('.loginBtn').on('click', function () {
		var cinemaCode=$('#lcinemaCode').val();
		var userAccount=$('#cardId').val();
		var userPasswd=$('#passWord').val();
		if(userAccount==''){
			myApp.alert('卡号不能为空');
			return false;
		}
		if(userPasswd==''){
			myApp.alert('密码不能为空');
			return false;
		}
		myApp.confirm('绑定后手机余额和积分会被覆盖，是否继续绑定？', function () {
			myApp.showIndicator();
			$.getJSON($('#bindurl').html(),{cinemaCode:cinemaCode,userAccount:userAccount,userPasswd:userPasswd},function(data){
				myApp.hideIndicator();
				myApp.alert(data['content']);
				if(data['status']=='0'){
					setTimeout(function(){window.location.href = '/user/cardbind.html';},3000);
				}
			});
		});
	});
	
	//门店选择
  $('#selectcinema').click(function(e){
    $('#selectcinema').find('ul').hide();
    $(this).find('ul').show();
    e.stopPropagation();
  });
  $('#selectcinema li').hover(function(e){
    $(this).toggleClass('on');
    e.stopPropagation();
  });
  $('#selectcinema li').click(function(e){
    var val = $(this).text();
    $(this).parents('#selectcinema').find('b').text(val);
    $(this).parents('#selectcinema').find('input').val($(this).attr('data-value'));
    $('#selectcinema ul').hide();
    e.stopPropagation();
  });
  $(document).click(function(){
    $('#selectcinema ul').hide();
  });

});

myApp.onPageInit('addphone', function (page) {
	var validCode=true;
	$('.getBtn').click(function(){
		var userMobile=$('#userMobile').val();
		var codeType='bind';
		var code=$(this);
		if(!isMobil(userMobile)){
			myApp.alert('请输入正确手机号码');
		}else {
			 var  codeUrl = $('#codeUrl').html();
			 myApp.showIndicator();
			 $.getJSON(codeUrl,{userMobile:userMobile,codeType:codeType},function(data){
		            myApp.hideIndicator();
		            if(data['status']!='0'){
		            	 myApp.alert(data['content']);   
		            }else{
							  
							 var time=60;
							//获取短信验证码
							
							if (validCode) {
								validCode=false;
								code.addClass("msgs");
								code.attr('disabled',true);
								var t=setInterval(function  () {
									time--;
									code.html(time+"秒");
									
									if (time==0) {
										clearInterval(t);
										code.html("重新获取");
										validCode=true;
										code.removeAttr("disabled");
										code.removeClass("msgs");
									}
								},1000)
								myApp.alert('验证码已发送，请查收'); 
							}
						}
		        });
	 	}
	});
	$('.loginBtn').click(function(){
		var userMobile=$('#userMobile').val();
		var validateCode=$('#validateCode').val();
		if(!isMobil(userMobile)){
			myApp.alert('请输入正确手机号码');
		}else if(validateCode==''){
			myApp.alert('请输入验证码');
		}else{
			 var  url = $('#bindurl').html();
			 myApp.showIndicator();
			$.getJSON(url,{userMobile:userMobile,validateCode:validateCode},function(data){
	            myApp.hideIndicator();
	            myApp.alert(data['content']);
				if(data['status']=='0'){
					setTimeout(function(){window.location.href = '/user/phonebind.html';},3000);
				}
	        });
		}
	});;

});

myApp.onPageInit('unbindcard', function (page) {
	$$('.bindBtn').on('click', function () {
		var passWord=$('#passWord').val();
		if(passWord==''){
			myApp.alert('请输入密码');
			return false;
		}
		$.getJSON($('#unbindurl').html(),{passWord:passWord,type:0},function(data){
			myApp.hideIndicator();
			myApp.alert(data['content']);
			if(data['status']=='0'){
				setTimeout(function(){window.location.href = '/user/user.html';},3000);
			}
		});
	});

});
myApp.onPageInit('unbind', function (page) {
	var validCode=true;
	$('.getBtn').click(function(){
		var userMobile=$('#userMobile').val();
		var codeType='unbind';
		var code=$(this);
		if(!isMobil(userMobile)){
			myApp.alert('请输入正确手机号码');
		}else {
			 var  codeUrl = $('#codeUrl').html();
			 myApp.showIndicator();
			 $.getJSON(codeUrl,{userMobile:userMobile,codeType:codeType},function(data){
		            myApp.hideIndicator();
		            if(data['status']!='0'){
		            	 myApp.alert(data['content']);   
		            }else{
							  
							 var time=60;
							//获取短信验证码
							
							if (validCode) {
								validCode=false;
								code.addClass("msgs");
								code.attr('disabled',true);
								var t=setInterval(function  () {
									time--;
									code.html(time+"秒");
									
									if (time==0) {
										clearInterval(t);
										code.html("重新获取");
										validCode=true;
										code.removeAttr("disabled");
										code.removeClass("msgs");
									}
								},1000)
								myApp.alert('验证码已发送，请查收'); 
							}
						}
		        });
	 	}
	});
	
	$$('.bindBtn').on('click', function () {
		var type=1;
		var validateCode=$('#validateCode').val();
		if(validateCode==''){
			myApp.alert('请输入验证码');
			return false;
		}
		myApp.showIndicator();
		$.getJSON($('#unbindurl').html(),{validateCode:validateCode,type:type},function(data){
			myApp.hideIndicator();
			myApp.alert(data['content']);
			if(data['status']=='0'){
				setTimeout(function(){window.location.href = '/user/user.html';},3000);
			}
		});
	});

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
var cancelurl='';
var delurl='';
var backurl='';
var supplyurl='';
function delOrder(obj){
	var orderid=$(obj).prev().val();
	var codec=$(obj).parent().parent().parent();
	 myApp.confirm('请确认是否删除订单', function () {
        	$.getJSON(delurl,{orderid:orderid},function(data){
        		if(data['status']=='0'){
        			codec.remove();
        		}else{
        			 myApp.alert(data['content']);
        		}
        	});
        });
}
function cancelOrder(obj){
	var orderid=$(obj).prev().val();
	 myApp.confirm('请确认是否取消订单', function () {
		 	$.get(cancelurl,{orderid:orderid},function(msg){
                location.href=backurl;
            });
        });
}
function supplay(orderid,mobile){
	$.getJSON(supplyurl,{orderid:orderid,mobile:mobile},function(data){
        myApp.alert(data.content);
    });
}

function delordergoods(obj,orderid){
	var codec=$(obj).parent().parent();
	$.getJSON(delordergoodsurl,{orderid:orderid},function(data){
		if(data['status']=='0'){
			codec.remove();
		}else{
			 myApp.alert(data.content);
		}
    });
}
function delorderround(obj,orderid){
	var codec=$(obj).parent().parent();
	$.getJSON(delorderroundurl,{orderid:orderid},function(data){
		if(data['status']=='0'){
			codec.remove();
		}else{
			 myApp.alert(data.content);
		}
    });
}
myApp.onPageInit('goodsOrder', function (page) {
	delordergoodsurl=$('#delordergoodsurl').html();
	delorderroundurl=$('#delorderroundurl').html();
	var goodsurl=$('#goodsurl').html();
	var roundurl=$('#roundurl').html();
	 $('.buttons-row > a').click(function(){
	        $('.buttons-row > a').removeClass('active');
	        $(this).addClass('active');
	        var type=$(this).attr('type');
	        if(type=='1'){
	        	$.getJSON(goodsurl,function(data){
		            $('.record .tab ul').html('');
		            var str="";
		            if(data.data){
		                $.each(data.data,function(k,value){
		                     str+='<li><div class="recordTop">'+
		                     '<span>订单号:'+value['id']+'</span>'+
		                     '<span style="float:right;">订单时间：'+value['ctime']+'</span><a href="#" class="confirm-ok"></a></div><div class="orderMid">';
		                     $.each(value.details,function(j,val){
		                    	 str+='<div class="orderMidItem"><div class="orderMidLeft"><img src="'+val['goodsImg']+'" width="120" height="80"></div>'+
		                    	 '<div class="orderMidRight"><h2>'+val['goodsName']+'</h2><b>×'+val['number']+'</b>'+
                                 '<p><span>单价：￥'+val['price']+'</span><span>总额：￥'+(val['price']*val['number'])+'</span></p></div><div class="clear"></div></div>';
		                     });
		                    str+= '</div><div class="orderNum"><span>共'+value['number']+'件商品，共付：<b>￥'+value['price']+'</b></span></div>'+
		                    '<div class="orderBtm"><span>兑换码：'+value['convcode']+'<b>（'+value['cinemaName']+'）</b></span>'+
		                    '<a href="javascript:;" onclick="delordergoods(this,'+value['id']+')"  class="delOrderBtn">删除订单</a></div></li>';
		                });         
		                $('.record #tab'+type+' ul').append(str);
		            }else{
		                str+='<div class="orderBg">没有发现任何信息</div>';
		                $('.record #tab'+type).html(str);
		            }
		        });
	        }else{
	        	$.getJSON(roundurl,function(data){
	        		$('.record .tab ul').html('');
		            var str="";
		            if(data.data){
		                $.each(data.data,function(k,value){
		                     str+='<li><div class="recordTop">'+
		                     '<span>订单号:'+value['id']+'</span>'+
		                     '<span style="float:right;">订单时间：'+value['ctime']+'</span><a href="#" class="confirm-ok"></a></div><div class="orderMid">'+
		                     '<div class="orderMidItem"><div class="orderMidLeft"><img src="'+value['goodsImg']+'" width="120" height="80"></div>'+
                             '<div class="orderMidRight"><h2>'+value['goodsName']+'</h2><b>×'+value['number']+'</b><p><span>单价：￥'+value['price']+'</span>'+
                             '<span>总额：￥'+(value['price']*value['number'])+'</span></p></div><div class="clear"></div></div> </div><div class="orderCash">';
		                     $.each(value.details,function(j,val){
		                    	 str+='<p>兑换码：<b>'+val['code']+'</b></p>';
		                     });
		                    str+= '<a href="javascript:;" onclick="delorderround(this,'+value['id']+')"  class="delOrderBtn">删除订单</a></div></li>';
		                });         
		                $('.record #tab'+type+' ul').append(str);
		            }else{
		                str+='<div class="orderBg">没有发现任何信息</div>';
		                $('.record #tab'+type).html(str);
		            }
		        });
	        }
	    })
});
myApp.onPageInit('userOrder', function (page) {
	cancelurl=$('#cancelurl').html();
	delurl=$('#delurl').html();
	backurl=$('#backurl').html();
	supplyurl=$('#supplyurl').html();
    $('.orderTab > a').click(function(){
        $('.orderTab > a').removeClass('cur');
        $(this).addClass('cur');
        var status=$(this).attr('status');
        var str="";
        myApp.showIndicator();
        $.getJSON($('#getOrderList').html(),{status:status},function(json){
            myApp.hideIndicator();
            $('.order ul').html('');
            if(json['status']=='0'){
            	var data=json.data;
            	if(data){
                    $.each(data,function(k,value){
                         str+='<li><div class="orderTop">'+
                         '<span>订单号：'+value['orderCode']+'</span>'+
                         '<span style="float:right;">'+value['downTime']+'</span>'+
                         '<a href="#" class="confirm-ok"></a></div><div class="orderMid"><div class="orderMidLeft">'+
                         '<img src="'+value['filmImg']+'" width="67.5" height="90"></div><div class="orderMidRight">'+
                         '<h2>'+value['filmName']+'</h2>'+
                         '<b>￥'+value['allprice']+'</b>'+
                         '<p>'+value['startTime']+'</p>'+
                         '<p><span>'+value['cinemaName']+'</span><span>'+value['hallName']+'</span></p>'+
                         '<p>'+value['seatIntroduce']+'</p></div><div class="clear"></div></div>';
                         str+='<div class="orderBtm"><div class="orderLink"><input type="hidden" value="'+value['orderCode']+'" />';
                         if(value['status']=='0'){
                             str+=' <a href="javascript:;" onclick="cancelOrder(this)" class="out" >取消订单</a><a href="'+$('#userPay').html()+'" class="go external">立即支付</a>';
                         }
                         if(value['isdel']!='1'){
                             str+='<a href="javascript:;" onclick="delOrder(this)" class="out" >删除订单</a>';
                         }else if(value['status']=='3'){
                        	 // str+='<a href="" class="go send" onclick="send(this)" orderid="'+value['orderCode']+'">补发短信</a>';
                         }
                        str+='</div>';
                        if(value['status']=='3'){
                            str+='<div class="orderBtmInfo"><b>手机号：'+value['mobile']+'</b><b>取票码：'+value['printNo']+'</b></div>';
                        }else{
                        	 str+='<p>'+value['str']+'</p>';
                        }
                        str+='</div>';
                        // if(value['status']=='3'){
                        //     str+='<div class="orderCode"><span class="on" onclick="openCode(this);">查看二维码</span>'+
                        //         '<div class="orderCodeImg" style="display:none;"><img src="'+value['qrcode']+'" width="200" height="200" /></div></div>';
                        // }
                        str+='</li>';
                    });             
                    $('.order ul').append(str);
                }else{
                    str+='<div class="orderBg">没有发现任何购票信息</div>';
                    $('.order ul').html(str);
                }
            }else{
            	myApp.alert(json['content']);
            }
        });
    });
            
    $('#edit').click(function(){
        $('.confirm-ok').css('display','block');
        $(this).replaceWith("<a href='javascript:' id='finish'>完成</a>");
        })
    $('.orderTab > a').eq(0).click();
});
function send(obj){
	var myApp = new Framework7({
        modalTitle:'取票码补发',
        modalButtonOk:'确认',
        modalButtonCancel:'取消'
        });
		var orderid=$(obj).attr('orderid');
		myApp.prompt('接收短信的手机号', function (value) {
			$.getJSON(supplyurl,{orderid:orderid,mobile:value},function(data){
	            myApp.alert(data.content);
	        });
		});
}
function openCode(obj){ 
	if($(obj).hasClass("on"))	{	
		$('.orderCodeImg').css("display","block");
		$(obj).removeClass('on');
		$(obj).addClass('off');
		
		}else{
		$('.orderCodeImg').css("display","none");
		$(obj).removeClass('off');
		$(obj).addClass('on');
		
		}
}

myApp.onPageInit('userRecord', function (page) {

    $('.buttons-row > a').click(function(){
        $('.buttons-row > a').removeClass('active');
        $(this).addClass('active');
        var type=$(this).attr('type');
        $.getJSON($('#getRecordList').html(),{type:type},function(data){
            $('.record .tab ul').html('');
            var str="";
            if(data){
                $.each(data,function(k,value){
                     str+='<li><div class="recordTop">'+
                     '<span class="fl">订单号：'+value['id']+'</span>'+
                     '<span class="fr">订单时间：'+value['createTime']+'</span><div class="clear"></div></div><div class="recordMid">'+
                     '<div class="recordMidLeft"><span>'+value['filmName']+'</span><span>'+value['typestr']+'</span></div><div class="recordMidRight">'+
                     '<span>'+value['payMoney']+'</span><span>'+value['payIntegral']+'</span></div><div class="clear"></div></div></li>';
                });         
                $('.record #tab'+(parseInt(type)+1)+' ul').append(str);
            }else{
                str+='<div class="orderBg">没有发现任何信息</div>';
                $('.record #tab'+(parseInt(type)+1)).html(str);
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
	h=$("#messages").scrollHeight();
	//alert($("#messages").scrollTop());
	$("#messages").scrollTop(h);
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
	$('.btn').click(function(){
		var name=$('#name').val().trim();
	    if(name==''){
	        myApp.alert('昵称不能为空');
	    }else{
	        $.getJSON($("#userSexajax").html(),{userNickname:name},function(data){
	        	if(data['status']=='0'){
	        		mainView.router.loadPage($("#userInfo").html());
	        	}
	        });
	    }
	});
	
});
myApp.onPageInit('userEmail', function (page) {
	$('.btn').click(function(){
		var email=$('#email').val().trim();
	    if(!Isyx(email)){
	        myApp.alert('邮箱格式不正确');
	    }else{
	        $.getJSON($("#userSexajax").html(),{email:email},function(data){
	        	if(data['status']=='0'){
	        		mainView.router.loadPage($("#userInfo").html());
	        	}
	        });
	    }
	});
	
});

function setftime(userBirthday){
	$.getJSON($('#userSexajax').html(),{userBirthday:userBirthday},function(data){
    });
}
myApp.onPageInit('userInfo', function (page) {

    //时间控件
    var currYear = (new Date()).getFullYear();

    // myDate.getMonth();

    var currMonth = (new Date()).getMonth();
    var currDate = (new Date()).getDate();
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
    $("#appDate").scroller('destroy').scroller($.extend(opt['date'], opt['default']));
    var optDateTime = $.extend(opt['datetime'], opt['default']);
    var optTime = $.extend(opt['time'], opt['default']);
    $("#appDateTime").mobiscroll(optDateTime).datetime(optDateTime);
    $("#appTime").mobiscroll(optTime).time(optTime);
    // 直接写参数方法
    //$("#scroller").mobiscroll(opt).date();
    // Shorthand for: $("#scroller").mobiscroll({ preset: 'date' });
    //具体参数定义如下
    //{
    //preset: 'date', //日期类型--datatime --time,
    //theme: 'ios', //皮肤其他参数【android-ics light】【android-ics】【ios】【jqm】【sense-ui】【sense-ui】【sense-ui】
    //【wp light】【wp】
    //mode: "scroller",//操作方式【scroller】【clickpick】【mixed】
    //display: 'bubble', //显示方【modal】【inline】【bubble】【top】【bottom】
    //dateFormat: 'yyyy-mm-dd', // 日期格式
    //setText: '确定', //确认按钮名称
    //cancelText: '清空',//取消按钮名籍我
    //dateOrder: 'yymmdd', //面板中日期排列格
    //dayText: '日',
    //monthText: '月',
    //yearText: '年', //面板中年月日文字
    //startYear: (new Date()).getFullYear(), //开始年份
    //endYear: (new Date()).getFullYear() + 9, //结束年份
    //showNow: true,
    //nowText: "明天",  //
    //showOnFocus: false,
    //height: 45,
    //width: 90,
    //rows: 3}

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
                    $.getJSON($('#userSexajax').html(),{userSex:'男'},function(data){
                    	if(data['status']=='0'){
                             $$('.sex .item-after').text('男');
                         }
                     });
                }
            },
            {
                text: '女',
                onClick: function() {
                    $.getJSON($('#userSexajax').html(),{userSex:'女'},function(data){
                        if(data['status']=='0'){
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
var today = new Date();
 
var pickerInline = myApp.picker({
    input: '#picker-date',
    toolbar: true,
    rotateEffect: true,
 
    value: [today.getMonth(), today.getDate(), today.getFullYear(), today.getHours(), (today.getMinutes() < 10 ? '0' + today.getMinutes() : today.getMinutes())],
 
    onChange: function (picker, values, displayValues) {
        var daysInMonth = new Date(picker.value[2], picker.value[0]*1 + 1, 0).getDate();
		//alert(daysInMonth);
        if (values[1] > daysInMonth) {
            picker.cols[1].setValue(daysInMonth);
        }
    },
 
    formatValue: function (p, values, displayValues) {
        return values[2] +'-' + displayValues[0] + '-' + values[1];
    },
    cols: [
        // Months
        {
            values: ('0 1 2 3 4 5 6 7 8 9 10 11').split(' '),
            displayValues: ('01 02 03 04 05 06 07 08 09 10 11 12').split(' '),
            textAlign: 'left'
        },
        // Days
        {
            values: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31],
        },
        // Years
        {
            values: (function () {
                var arr = [];
                for (var i = 1950; i <= 2030; i++) { arr.push(i); }
                return arr;
            })(),
        },
        // Space divider
        {
            divider: true,
            content: '  '
        },
    ]
	
	  
}); 
$$('#picker-date').on('click', function () {
	
	$$('.modal-overlay').addClass('modal-overlay-visible');
		  
    });
 
/*$('.close-picker').click(function(){

	alert(111);
	});*/


});




myApp.onPageInit('confirmgoods', function (page) {
	$('#confirm').click(function(){
		var mobile=$('#mobile').val();
		if(!isMobil(mobile)){
			myApp.alert('请输入正确手机号码');
		}else{
			var payurl=$('#payurl').html();
			var gourl=$('#gourl').html();;
			$.getJSON(payurl,{mobile:mobile},function(data){
	            if(data['status']=='0'){
	                location.href=gourl;
	             }
	        });
		}
	});
});

myApp.onPageInit('goods', function (page) {
    $(".Spinner").Spinner({value:0, min:0, len:2, max:99});

    $('.Spinner a').click(function(){
        jsuan();
    });

    $('.Spinner input').blur(function(){
        jsuan();
    });

    function jsuan (){

        var	allPrice = 0;

        for(i=0;i<$('.Spinner input').length;i++){

            var num = $('.Spinner input').eq(i).val(),
                univalence = $('.Spinner input').eq(i).parents('.goods-list').find('span.current').text(),
                univalence = univalence.substr(1,univalence.length-1),
                itemPrice  = num*univalence;

            allPrice += itemPrice;

        }

        $('.price-info a.total').text('￥'+allPrice);

        if(allPrice==0){
            $('.price-info').hide();
        }else{
            $('.price-info').show();
        }

    }
    $('.confirm').click(function(){
    	var data='';
    	var num='';
    	$('.Amount').each(function(){
    		num=parseInt($(this).val());
    		if(num>0){
    			data+=','+$(this).parent().prev().val()+':'+num;
    		}
    	});
    	if(data==''){
    		myApp.alert('请选择商品');
    	}else{
    		var url=$('#confirmurl').html()+'?data='+data.substr(1);
    		mainView.router.loadPage(url);
    	}
    });
});

myApp.onPageInit('payGoods', function (page) {

    $$('.open-info-sale').on('click', function () {
	  myApp.pickerModal('.salePicker');
	  $$('.modal-overlay').addClass('modal-overlay-visible');
    });
    $$('.saleClosePicker').on('click', function () {
      myApp.closeModal('.salePicker')
    });  

});


function getScrollTop(){ 
var scrollTop=0; 
if(document.documentElement&&document.documentElement.scrollTop){ 
scrollTop=document.documentElement.scrollTop; 
}else if(document.body){ 
scrollTop=document.body.scrollTop; 
} 
return scrollTop; 
} 

myApp.onPageInit('buying', function (page) {

        //弹出支付小窗口
        $$('.item-right p a').on('click', function () {
            
            var couponId = $(this).attr('data');
            var url = $('#page-buying #checkOrderNum').val();
            var thisUrl = $('#page-buying #thisUrl').val();
            $.getJSON(url,{'couponId':couponId, 'thisUrl':thisUrl},function(json){
                if (json.status == 0) {
                   var nowSum = json.data;
                    if (nowSum == 0) {
                        myApp.alert('不好意思，已经售完，下次请早！');
                        isCheck = false;
                        return false;
                    }else{
						$('.modal-overlay').addClass('modal-overlay-visible');
                        showPay(json.data.newPrice,json.data.couponId);
                    } 
                }else if (json.status == '20001') {
                    var loginUrl = $("#page-buying #loginUrl").val();
                    mainView.router.loadPage(loginUrl);
                }else if (json.status == '20002') {
                    //var scrolltop = getScrollTop();
                    //alert(scrolltop);
                    //window.scroll(0,100);
                   // $('.main').css('overflow', 'hidden');
                   // $('.views').css('overflow', 'hidden');
                    // $('.main').css('height', height+'px');
                    // $('.views').css('height', height+'px');

                    var timestamp = Date.parse(new Date()) / 1000;
                    $("#page-buying .error-section #cancelBtn").attr('couponOrderId',json.data.couponOrderId);
                    $("#page-buying .error-section #cancelBtn").attr('couponId',json.data.couponId);
                    $("#page-buying .error-section #orderTimers").attr('data', (json.data.orderTime - timestamp + 600));
                    $("#page-buying .error-section").css('display', 'block');
                    orderTimer('orderTimers', $('#page-buying #orderTimers').attr('data'));
                    return;
                };
                
            });

            
        });
		
        //加减按钮功能
        $("#page-buying .Spinner").Spinner({value:1, min:1, len:2, max:2});
        //关掉支付的弹窗提示的遮罩层
        $('#page-buying .close-picker').on('click',function(){
            //清空之前的val
            $('#page-buying .Amount').val('1');
        });

        //点击按钮价格变化
        $('#page-buying .Spinner>a').on('click',function(){
            var a = $('#page-buying .cost').attr('unitPrice');
            var b = $('#page-buying .Amount').val();

            allCost = parseFloat(a*b).toFixed(2)
            $('#page-buying .cost').text(allCost);
        })
        //关掉超时提示
        $('#page-buying #cancelBtn').on('click',function(){
            var url = $('#page-buying #cancelOrder').val();
            // alert(url);
            var couponOrderId = $(this).attr('couponOrderId');
            $.getJSON(url,{'couponOrderId':couponOrderId},function(json){
                if (json.status==0) {
                    $('#page-buying .error-section').hide();
                }
            })
            
        })

        // alert($('.times').html());
        var nS=0;
        jQuery(function($){
        $.each($('#page-buying .timer'),function(){
            // $(this).attr('data')
            timer($(this).attr('id'), $(this).attr('data'));
        });

        $.each($('#page-buying .couponSum'),function(){
            // $(this).attr('data')
            checkSum($(this).attr('id'));
        });

        
    });
        // return new Date(parseInt(nS) * 1000).toLocaleString().replace(/:\d{1,2}$/,' ');     


});

function showPay (price,couponId) {

    var a = parseFloat(price).toFixed(2);//获取价格;
    $('#page-buying .cost').text(a).attr('unitPrice',a);
    $('#page-buying #couponId').val(couponId);

    myApp.pickerModal('.picker-info');
}


function createOrder (url, couponId, couponOrderId) {
    var sum = $('#couponBuySum').val();
     $.getJSON(url,{'couponId':couponId, 'sum':sum, 'couponOrderId':couponOrderId},function(data){
        if(data['status']=='0'){
            window.location.href = data['data'];
        }else{
            myApp.alert(data['content']);
        }
    });
}

function continuePayOrder (url) {
    var couponId = $('#page-buying .error-section #cancelBtn').attr('couponId');
    var couponOrderId = $('#page-buying .error-section #cancelBtn').attr('couponOrderId');
    $.getJSON(url,{'couponId':couponId, 'couponOrderId':couponOrderId},function(data){
        if(data['status']=='0'){
            window.location.href = data['data'];
        }else{
            myApp.alert(data['content']);
        }
    });
}

function checkSum (objId) {
    var url = $('#page-buying #checkSum').val();
    if($('#page-buying #' + objId).css('display') != 'none'){
        var couponId = $('#page-buying #' + objId + ' a').attr('data');
        // alert($('#' + objId + ' a').attr('data'));
        $.getJSON(url,{couponId:couponId},function(json){
            // $('#' + objId + ' i').html('');
            var nowSum = json.data.couponSum;
            if (nowSum<100 && nowSum>=10) {
                nowSum = '0' + nowSum;
            }else if(nowSum < 10){
                nowSum = '00' + nowSum;
            }
            $('#page-buying #' + objId + ' i').flipcountdown({tick:nowSum, size:"xs"});
        });
    }
    setTimeout("checkSum('"+objId+"')", 1000);
}

function orderTimer (objId, nS) {
    nS--;
    var date = parseInt(nS/3600/24);

    var h = parseInt((nS - date * 3600 * 24)/3600);
    if (h<10) {
        h = '0'+h;
    }

    var m = parseInt((nS - date*3600 * 24 - h*3600)/60);
    if (m<10) {
        m = '0'+m;
    }

    var s = nS - date*3600 * 24 - h*3600 - m * 60;
    if (s<10) {
        s = '0'+s;
    }

    $('#page-buying #'+objId).flipcountdown({tick:m+'.'+s+'', size:"xs"});
   
    if (nS == 0) {
        $('#page-buying .error-section').hide();
        return true;
    }
    setTimeout("orderTimer('"+objId+"',"+nS+")", 1000);
}

function timer (objId, nS) {
    nS--;
    var date = parseInt(nS/3600/24);
    if (date<10) {
        date = '0'+date;
    }

    var h = parseInt((nS - date * 3600 * 24)/3600);
    if (h<10) {
        h = '0'+h;
    }

    var m = parseInt((nS - date*3600 * 24 - h*3600)/60);
    if (m<10) {
        m = '0'+m;
    }

    var s = nS - date*3600 * 24 - h*3600 - m * 60;
    if (s<10) {
        s = '0'+s;
    }

    $('#page-buying #'+objId+' .timerDate').flipcountdown({tick:date, size:"xs"});
    $('#page-buying #'+objId+' .timers').flipcountdown({tick:h+'.'+m+'.'+s+'', size:"xs"});
   
    if (nS == 0) {
        $('#page-buying #'+objId).css('display','none');
         $('#page-buying #showSell'+objId).css('display','');
         return true;
    }
    setTimeout("timer('"+objId+"',"+nS+")", 1000);
}
