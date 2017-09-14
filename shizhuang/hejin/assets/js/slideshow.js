// Copyright 2010 htmldrive.net Inc.
/**
* @author htmldrive.net
* More script and css style : htmldrive.net
* @version 1.0
* @license http://www.apache.org/licenses/LICENSE-2.0
*/

$(function(){
	var c_f = 0;
	var c_l = $(".slidesul").children("li").length;
	var s_t_i = 3000;
	var timeout;
	var t_c_f =0;
	
	$("#slides").find(".slidesTag").children("ul").children("li").children("a").click(function(){
		t_c_f = $("#slides").find(".slidesTag").children("ul").children("li").index($(this).parent());
		stop();
		change(t_c_f);
		if(t_c_f == c_l-1){
			t_c_f = 0 ;
		}else{
			t_c_f ++;
		}
	});
	
	/*$(".slideshow-controls").children("a").click(function(){
		if($(this).attr("rel") == "prev"){
			if(c_f == 0){
				t_c_f = c_l-1 ;
			}else{
				t_c_f =  c_f - 1 ;
			}
		}else{
			if(c_f == c_l-1){
				t_c_f = 0 ;
			}else{
				t_c_f =  c_f + 1;
			}
		}
		play();
	});*/
	
	play();
	
	$("#slides").hover(function(){
		stop();
	},function(){
		timeout = setTimeout(play,s_t_i);
	});
	
	function stop(){
		clearTimeout(timeout);
	}
	
	function play(){
		clearTimeout(timeout);
		change(t_c_f);
		t_c_f = c_f + 1;
		if(t_c_f >= c_l){
			t_c_f = 0;
		}
		timeout = setTimeout(play,s_t_i);
	}

	function change(t_c_f){
		
		if(t_c_f == 0 && c_f == 0){
		
		}else{
			$("#slides").find(".slidesul").children("li").eq(c_f).fadeOut("slow");
			$("#slides").find(".slidesul").children("li").eq(t_c_f).fadeIn("slow");
		}
	
		$("#slides").find(".slidesTag").children("ul").children("li").removeClass("selected");
		$("#slides").find(".slidesTag").children("ul").children("li").eq(t_c_f).addClass("selected");
		c_f = t_c_f;
	}

});