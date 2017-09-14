/**
 * Created by karl.zheng on 2017/2/10.
 */
var config = {
    // url : "http://10.10.15.44:8080/pocketgames",
    url: static_uri+"/interface/1pay/",
    appId: 10062,
    appKey: "f43968a73df747aab4b9b1e506265050",
    appSecret: "fdce048a87e94f48a0f67096b17cb221",
    userId: "",
    userName: "",
    roleId: "",
    level: "",
    gameZoneId: "",
    cardChannel: 13,
    onlineChannel: 21,
    smsChannel: 21,
    onlineExinfo: "10023",
    smsExinfo: "10013",
    fbId: "1887911061495843",
    redirect_uri: static_uri+"/interface/1pay/",
    // 默认语言
    defaultLang:'vn',
    bankScale: 4
};
var scale = {
    "20k"  : "80 Ruby + 8 điểm nạp",
    "30k"  : "120 Ruby + 12 điểm nạp",
    "50k"  : "200 Ruby + 20 điểm nạp",
    "100k" : "400 Ruby + 40 điểm nạp",
    "200k" : "800 Ruby + 80 điểm nạp",
    "300k" : "1200 Ruby + 120 điểm nạp",
    "500k" : "2000 Ruby + 200 điểm nạp",
    "1000k": "4000 Ruby + 400 điểm nạp",
    "2000k": "8000 Ruby + 800 điểm nạp",
    "5000k": "20000 Ruby + 2000 điểm nạp"
};

var smsScale = {
    5 : 15,
    10: 35,
    15: 50,
    20: 70,
    30: 105,
    50: 175
};

//获取刮刮卡参数
function getDataCard(){
    var serialNo = $(".card-number").val();
    var pin = $(".card-password").val();
    var cardExinfo = $(".list-provider li.active img").attr("data-exinfo");
    var extern = $("input[name='extern']").val();

    var data = {
        lstTelco:cardExinfo,
        txtSeri:serialNo,
        pin:pin,
        extern:extern,
    };
    return data;
}

//获取网银参数
function getDataOnline(){
    var extern = $("input[name='extern']").val();
    var productId = $(".bank-online .list-amount-tab li.active .amount.active").attr("data-id");

    var data = {
        order_info: extern,
        amount: productId,
    };
    return data;
}

//获取短代参数
function getDataSms(){
    // var cardType = $(".list-bank li.active img").attr("data-id");
    var cardType = 26;
    var gameZoneId = $(".sel-zoneId span.target").attr("data-id");
    var roleId = $(".sel-playId span.target").attr("data-id");
    var roleLevel = $(".sel-playId span.target").attr("data-level");
    var productId = $(".box-sms .list-amount-tab li.active .amount.active").attr("data-id");

    var data = {
        appId: config.appId,
        userId: sessionStorage.getItem("userId"),
        roleId: roleId,
        level: roleLevel,
        gameZoneId: gameZoneId,
        productId: productId,
        model: isMobile()? "1":"0",
        channel: config.smsChannel,
        cardType: cardType,
        clientDate: getCurrentTime(),
        exinfo: config.smsExinfo
    };

    return data;
}
