/*
 * 显示样式中定义id : [message,message_close,message_content]请勿重复定义以免出错
 *	使用必须引用messager.css注意修改CSS图片路径
 * 调用api
 *		
 * 		$.messager.show(title, text, time,callback)
 *				显示消息框
 *					title		:	标题,可增加任意标签<font color=red>自定义标题</font>, 如 title = 0 则为默认值 '信息提示';
 *					text		:	消息内容,同text
 *					time		:	自动关闭时间, 如 time = 0 则不自动关闭
 *					callback:	回调函数function
 *
 *		$.messager.lays(width, height)
 *				设置消息宽大小
 *					width		: 宽
 *					height	: 高				
 *		
 *		$.messager.anims(type, speed)
 *				设置动画效果
 *					type		: 动画类型,value:[slide,fade,show]	,默认slide
 *					speed		：显示速度,value:[slow,fast,normal]	,默认600
 *
 *		$.messager.close(callback);
 *				关闭消息框
 *					callback:	回调函数function
 *
 *		$.messager.showNext(title, text, time,callback)
 *				关闭现在显示消息框，显示一新消息框
 *					同show(...)
 *
 */
(function(){  
	var ua=navigator.userAgent.toLowerCase();  
	var is=(ua.match(/\b(chrome|opera|safari|msie|firefox)\b/) || ['','mozilla'])[1];  
	jQuery.browser.is=is;
	jQuery.browser[is]=true;  
})();
(function (jQuery){
	this.layer = {'width' : 200, 'height': 100};
	this.title = '信息提示';
	var messageHeight = this.layer.height;
	this.time = 4000;
	this.anims = {'type' : 'slide', 'speed' : 600};
	this.timer1 = null;
	this.inits = function(title, text){
		if($("#message").is("div")){ return;}

		$(document.body).prepend(
				'<div id="message" style="width:'+this.layer.width+'px;height:'+this.layer.height+'px;">'
					+ '<div class="title_board">'
						+ '<div id="message_close" class="close"></div><div id="message_minRes" class="min"></div>'
						+ '<div style="padding:5px 0 5px 5px;">'+this.title+'</div>'
						+ '<div style="clear:both;"></div>'
					+ '</div>'
					+ '<div class="content_board">'
						+ '<div id="message_content" style="width:'+(this.layer.width-17)+'px;height:'+(this.layer.height-50)+'px;">'+text+'</div>'
					+ '</div>'
				+ '</div>');
		
		$("#message_close").click(function(){		
			setTimeout('this.close()', 1);
		});
		
		//最小最大化
		this.minimize = function (){
			if($('#message').attr('clientHeight') >= $('div .title_board').height()){
				$('#message').css('height',$('#message').attr('clientHeight') -1);
				setTimeout('this.minimize()',5);
			}else{
				$('#message_minRes').attr('className','max');
			}
		};
		
		this.restore = function (){
			if($('#message').attr('clientHeight') <= this.layer.height){
				$('#message').css('height',$('#message').attr('clientHeight') +1);
				setTimeout('this.restore()',5);
			}else{
				$('#message_minRes').attr('className','min');
			}
		};
		
		$("#message_minRes").click(function(){				
			if(/^min\w*$/.test($('#message_minRes').attr('className'))){
					minimize();
				}else{
					restore();
				}
		});
		
		$("#message_minRes").mousemove(function(){
			if($('#message_minRes').attr('className') == 'min'){
				$('#message_minRes').attr('className','min_over');
			}else if($('#message_minRes').attr('className') == 'max'){
				$('#message_minRes').attr('className','max_over');
			}
		});
		
		$("#message_minRes").mouseout(function(){
			if($('#message_minRes').attr('className') == 'min_over'){
				$('#message_minRes').attr('className','min');
			}else if($('#message_minRes').attr('className') == 'max_over'){
				$('#message_minRes').attr('className','max');
			}
		});
		
	 	$("#message_close").mousemove(function(){
				$('#message_close').attr('className','close_over');
		});
		
		$("#message_close").mouseout(function(){
				$('#message_close').attr('className','close');
		});
		
	};
	
	//显示
	this.show = function(title, text, time,callback){
            
		if($("#message").is("div")){ return; }
		if(title==0 || !title)title = this.title;
		this.inits(title, text);
                
		if(time>=0)this.time = time;
		switch(this.anims.type){
			case 'slide':$("#message").slideDown(this.anims.speed);break;
			case 'fade':$("#message").fadeIn(this.anims.speed);break;
			case 'show':$("#message").show(this.anims.speed);break;
			default:$("#message").slideDown(this.anims.speed);break;
		}
		if($.browser.is=='chrome'){
			setTimeout(function(){
				$("#message").remove();
				this.inits(title, text);
				$("#message").css("display","block");
			},this.anims.speed-(this.anims.speed/5));
		}
		this.rmmessage(this.time);
		if(typeof callback == 'function'){
			callback();
		}
	};
	
	this.lays = function(width, height){
		if($("#message").is("div")){ return; }
		if(width!=0 && width)this.layer.width = width;
		if(height!=0 && height)this.layer.height = height;
	};
	this.anim = function(type,speed){
		if($("#message").is("div")){ return; }
		if(type!=0 && type)this.anims.type = type;
		if(speed!=0 && speed){
			switch(speed){
				case 'slow' : ;break;
				case 'fast' : this.anims.speed = 200; break;
				case 'normal' : this.anims.speed = 400; break;
				default:					
					this.anims.speed = speed;
			}			
		}
	};
	
	this.rmmessage = function(time){
		if(time>0){
			timer1 = setTimeout('this.close()', time);
		}
	};	
	
	this.close = function(callback){
		switch(this.anims.type){
			case 'slide':$("#message").slideUp(this.anims.speed);break;
			case 'fade':$("#message").fadeOut(this.anims.speed);break;
			case 'show':$("#message").hide(this.anims.speed);break;
			default:$("#message").slideUp(this.anims.speed);break;
		};
		setTimeout('$("#message").remove()', this.anims.speed);
		this.original();
		if(typeof callback == 'function'){
			callback();
		}
	};
	
	this.original = function(){
		this.layer = {'width' : 200, 'height': 100};
		this.title = '信息提示';
		this.time = 4000;
		this.anims = {'type' : 'slide', 'speed' : 600};
	};
	
	this.showNext = function(title, text, time,callback){
		clearTimeout(this.timer1);
		this.close();
		setTimeout('this.show("'+title +'","' + text + '","'+ time +'",' + callback +')',this.anims.speed);
		if(typeof callback == 'function'){
			callback();
		}
	};
	
	$(window).scroll(function (){
			$("#message").css('right','-' + $(window).scrollLeft() + 'px');
			$("#message").css('bottom','-' + $(window).scrollTop() + 'px');
	});
  jQuery.messager = this;
  return jQuery;
})(jQuery);