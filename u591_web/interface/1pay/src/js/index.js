/**
 * Created by karl.zheng on 2017/1/13.
 */
var PAGE = {
    BANKAUTO: 0,
    BANKHUMAN: 1,
    CARD: 2,
    BANKONLINE: 3,
    SMS: 4
};

$(window).on("hashchange", function(){
    onRouter();
});

function thisPage(){
    var link = location.href;
    if(link.indexOf("#/bankauto")>=0){
        return PAGE.BANKAUTO;
    }else if(link.indexOf("#/bankhuman")>=0){
        return PAGE.BANKHUMAN;
    }else if(link.indexOf("#/card")>=0){
        return PAGE.CARD;
    }else if(link.indexOf("#/bankonline")>=0){
        return PAGE.BANKONLINE;
    }else if(link.indexOf("#/sms")>=0){
        return PAGE.SMS;
    }else{
        return PAGE.CARD;
    }
}

function onRouter(){
    $(".recharge-type li.active").removeClass("active");
    var index = thisPage();
    $(".recharge-type li").eq(index).addClass("active");

    switch(index){
        case PAGE.BANKAUTO:
            $(".page.active").removeClass("active");
            $(".bank-auto").addClass("active");
            break;
        case PAGE.BANKHUMAN:
            $(".page.active").removeClass("active");
            $(".bank-human").addClass("active");
            break;
        case PAGE.CARD:
            $(".page.active").removeClass("active");
            $(".box-card").addClass("active");
            break;
        case PAGE.BANKONLINE:
            $(".page.active").removeClass("active");
            $(".bank-online").addClass("active");
            break;
        case PAGE.SMS:
            $(".page.active").removeClass("active");
            $(".box-sms").addClass("active");
            break;
        default: break;
    }
}

onRouter();

// 选择区服和游戏id
$(".sel-zoneId").on("click",function () {
    $('.box-zoneId').show();
});
$(".sel-playId").on("click",function () {
    $('.box-playId').show();
});


$("body").click(function (e) {
    if ((!$(e.target).closest(".sel-zoneId").length && !$(e.target).closest(".box-zoneId").length )|| $(e.target).closest(".list-zones").length) {
        $(".box-zoneId").hide();
    }
    if (!$(e.target).closest(".sel-playId").length) {
        $(".box-playId").hide();
    }
});

$(".list-zones").on("click",'li', function(){
    $(".box-zoneId li.active").removeClass("active");
    $(this).addClass("active");
    $(".sel-zoneId span.target").text($(".box-zoneId li.active").html()).attr("data-id", $(".box-zoneId li.active").attr("data-id"));
    getRoleInfo();
});

$(".box-playId").on("click",'li', function(){
    $(".box-playId li.active").removeClass("active");
    $(this).addClass("active");

    $(".sel-playId span.target").text($(".box-playId li.active").html()).attr("data-id", $(".box-playId li.active").attr("data-id"))
        .attr("data-level",$(".box-playId li.active").attr("data-level")).attr("data-name", $(".box-playId li.active").attr("data-name"));
});

//选择服务商
$(".list-provider li").on("click", function(){
    $(".list-provider li.active").removeClass("active");
    $(this).addClass("active");
});

//短信充值数量
$(".box-sms .list-amount-tab li div").on("click", function(){
    $(".box-sms .list-amount-tab li.active div.active").removeClass("active");
    $(this).addClass("active");

    var amount = $(".box-sms .list-amount-tab li.active div.active label").text();
    $(".box-sms .charge-result span").eq(1).text(amount);
    $(".box-sms .charge-result span").eq(3).text(smsScale[amount]);
});

//网络银行充值数量
$(".bank-online .list-amount-tab li div").on("click", function(){
    $(".bank-online .list-amount-tab li.active div.active").removeClass("active");
    $(this).addClass("active");

    var amount = $(".bank-online .list-amount-tab li.active div.active label").text();
    $(".bank-online .charge-result span").eq(1).text(amount);
    $(".bank-online .charge-result span").eq(3).text(config.bankScale*amount);
    $(".bank-online .charge-result span").eq(5).text((config.bankScale*amount)/10);
});


$(".box-sms .amount-tab li").on("click", function(){
    $(".box-sms .amount-tab li.active").removeClass("active");
    $(this).addClass("active");
    var index = $(this).index();
    var dis = [100, 271, 443];
    $(".box-sms .triangle").css("left", dis[index]);
    $(".box-sms .list-amount-tab li.active").removeClass("active");
    $(".box-sms .list-amount-tab li").eq(index).addClass("active");

    var amount = $(".bank-sms .list-amount-tab li.active div.active label").text();
    $(".charge-result span").eq(1).text(amount);
    $(".charge-result span").eq(3).text(smsScale[amount]);
});

$(".bank-online .amount-tab li").on("click", function(){
    $(".bank-online .amount-tab li.active").removeClass("active");
    $(this).addClass("active");
    var index = $(this).index();
    var dis = [100, 271, 443];
    $(".bank-online .triangle").css("left", dis[index]);
    $(".bank-online .list-amount-tab li.active").removeClass("active");
    $(".bank-online .list-amount-tab li").eq(index).addClass("active");

    var amount = $(".bank-online .list-amount-tab li.active div.active label").text();
    $(".charge-result span").eq(1).text(amount);
    $(".charge-result span").eq(3).text(5*amount);
});

$(".btn-close").on("click", function(){
    $(".win-wrap").hide();
    $(".win-alert").hide();
});

//登出
$(".btn-logout").on("click", function(){
    if(confirm(i18N.get("public.sureLogout"))){
        logout();
        window.location.href = "login.html";
    }
});



//刮刮卡充值
$(".btn-card-charge").on("click", function(){
    chargeForwardCard();
});

$(".scale").on("click", function(){
    $(".win-wrap").show();
    $(".win-alert").show();
    $(".win.active").removeClass("active");
    $(".alert-exchange").addClass("active");
});

//返回按钮
$(".btn-return").on("click", function(){
    $(".win-wrap").hide();
    $(".win-alert").hide();
});

$(".btn-success").on("click", function(){
    $(".win-wrap").hide();
    $(".win-alert").hide();
});

$(".btn-bank-charge").on("click", function(){
    // console.log($(".charge-amount .list-amount-tab li.active div.active").index());
    chargeForwardWeb();
});

$(".list-bank li").on("click", function(){
    $(".charge-list").hide();
    $(".charge-amount").show();
    $(".list-bank li.active").removeClass("active");
    $(this).addClass("active");
});

$(".btn-sms-charge").on("click", function(){
    chargeForwardSms();
});

/********************************************************************************************************/

//兑换比例窗口
var str = "<p>";
var count = 0;
for(var i in scale){
    str += i+" = "+scale[i]+"<br/>";
    count++;
    if(count == 6){
        str+="</p><div class='line'></div>"
        $(".exchange").append(str);
        str = "<p>"
    }
}
str += "</p>";
$(".exchange").append(str);

$(document).ready(function(){
    if(isMobile()){
        checkOrient();
        window.onorientationchange = checkOrient;
    }
    //获取code
    // var CODE = $.trim(getParameterByName("code"));
    // if(CODE && !isLogin()){
    //     //facebook 登录
    //     var data = {
    //         appId: config.appId,
    //         accountType: 2,
    //         userName: "",
    //         password: "",
    //         code: CODE,
    //         redirectUrl: config.redirect_uri,
    //         exInfo: ""
    //     };
    //     data.sign = createSigntrue(data);
    //     login(data, function(result){
    //         console.log(JSON.stringify(result));
    //         if(result.code==200){
    //             sessionStorage.setItem("userId", result.data.userId);
    //             sessionStorage.setItem("username", result.data.userName);
    //             config.userId = sessionStorage.getItem("userId");
    //             config.userName = sessionStorage.getItem("username");
    //             var myTimer = new Date().getTime();
    //             sessionStorage.setItem("activeTime", myTimer);
    //             $(".box-head p").text(config.userName);
    //         }else{
    //             sessionStorage.setItem("userId", "");
    //             sessionStorage.setItem("username", "");
    //             sessionStorage.setItem("activeTime", "");
    //             alert(i18N.get("status."+result.code));
    //             window.location.href = "login.html";
    //         }
    //
    //     })
    // }else{
    //     if(isLogin()){
    //         //获取登录后的uerId
    //         config.userId = sessionStorage.getItem("userId");
    //         config.userName = sessionStorage.getItem("username");
    //         $(".box-head p").text(config.userName);
    //     }else{
    //         window.location.href = "login.html";
    //     }
    // }
    // getZoneList();
    sessionStorage.setItem("userId", '11');
    sessionStorage.setItem("username", "test");
    config.userId = sessionStorage.getItem("userId");
    config.userName = sessionStorage.getItem("username");
});


