// Copyright 2010 htmldrive.net Inc.
/**
* @author htmldrive.net
* More script and css style : htmldrive.net
* @version 1.0
* @license http://www.apache.org/licenses/LICENSE-2.0
*/
var nowid,tal;

$(function(){
	if(window.screen.availWidth == 360){
		$('.container').css('min-width','360px');
	}
	var video = document.getElementById('video');
	$('.startpng').click(function(){
		$('#close').show();
		$('#video').show();
		video.play();
	});
	$('#close').click(function(){
		$('#video').hide();
		$('#close').hide();
	});
	$('.lrslidesTag li').click(function(){
		$(this).addClass('selected');
		$(this).siblings().removeClass('selected');
		tal = $(this).attr('tal');
		$('.lrslidesImg img').attr('src','./assets/images/b'+tal+'.png');
	});
	
	$('.lrdiv').click(function(){
		nowid=parseInt($('.lrslidesTag').find('.selected').attr('tal'));
		if($(this).attr('id')=='ldiv'){
			if(nowid == 1){
				tal = 5;
			}else{
				tal = nowid-1;
			}
		}else{
			if(nowid == 5){
				tal = 1;
			}else{
				tal = nowid+1;
			}
		}
		$('.lrslidesTag').find('li[tal='+tal+']').addClass('selected');
		$('.lrslidesTag').find('li[tal='+tal+']').siblings().removeClass('selected');
		$('.lrslidesImg img').attr('src','./assets/images/b'+tal+'.png');
	});

});