function initTop(){
    //登录下拉栏目
    $('#login').bind('mouseover', function () {
        if ($('#login_con').is(':hidden')) {
            $(this).addClass('visited');
            $('#login_con').prev().show(100);
            $('#login_con').slideDown(100);
        }
    });
    //游戏推荐
    $('#game_tj').bind('mouseover', function() {
        if($('#js_tj').is(':hidden')) {
            if(!$('#js_tj').html()){
                $("#games a").each(function(){
                    href = $(this).attr("href");
                    game_name = $(this).text();
                    rel = $(this).attr("rel");
                    $('#js_tj').append('<li class="left"><a href="'+href+'"><img src="http://hejin.u591.com/assets/images/'+rel+'.png" class="left"><span class="left">'+game_name+'</span></a></li>');
                });
            }
            $(this).addClass('visited');
            $('#js_tj').slideDown(100);
        }

    });

    $(document).bind('mouseover', function (e) {
        var e = e || window.event;
        var target = $(e.target);
        if (target.closest('.option').length == 0) {
            $('#login').removeClass('visited');
            $('#login_con').prev().hide(100);
            $('#login_con').slideUp(100);
        }

        if (target.closest('h1').length == 0) {
            $('#game_tj').removeClass('visited');
            $('#js_tj').slideUp(100);
        }
    });
};
initTop();

$("#game_rank ul li").each(function(){
    var that = $(this);

    that.hover(function() {
        that.siblings('li').find('p').hide();
        that.find('p').show();
    });

    that.click(function(){
        that.addClass("on").siblings("li").removeClass("on");
        $("#" + that.attr("data-name")).show().siblings("div").hide();
    });
});

function media(){
    $("#media ul").animate({}, 2000 , function(){
        $("#media ul li:last").after($("#media ul li:first"));
        $("#media ul li:first").fadeIn(500);
    });
}
setInterval("media()", 2000);

function links(){
    $("#links ul").animate({}, 3000 , function(){
        $("#links ul li:last").after($("#links ul li:first"));
        $("#links ul li:first").fadeIn(500);
    });
}
setInterval("links()", 3000);

//悬浮
var leftpx = parseInt($('#main').outerWidth()) + parseInt($('#main').offset().left) + 25;
$('#right_option').css({left:leftpx});
$(window).bind('scroll', function() {
    if($(this).scrollTop() > 200) {
        $('#right_option').show();
    } else {
        $('#right_option').hide();
    }
});

