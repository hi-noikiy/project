/**
 * Created by karl.zheng on 2017/1/16.
 */
//登录接口
function login(data, callback){
    $.ajax({
        type: "POST",
        url: config.url + "/web/login",
        dataType: 'json',
        // jsonp: 'jsonCallback',
        data: data,
        success: function(result){
            console.log("登录 \r\n"+result);
            callback(result);
        },
        error: function(err){
            console.log("登录失败 \r\n"+err);
        }
    });
}

//退出登录接口
function logout(){
    sessionStorage.setItem("userId", "");
    $.ajax({
        type: "GET",
        url: config.url + "/web/logout",
        success: function(result){
            console.log("退出登录 \r\n"+result);
        },
        error: function(err){
            console.log("退出登录 \r\n"+err);
        }
    });
}

//获取区服接口
function getZoneList(){
    $.ajax({
        type: "GET",
        url: config.url + "/web/getZoneList",
        dataType: 'json',
        data: {
            appId: config.appId
        },
        success: function(result){
            console.log("获取区服 \r\n"+JSON.stringify(result));
            var data = result.data;
            if(result.code == 200){
                $(".list-zones").html("");
                for(var i = 0; i < 10&&i<data.length; i++){
                    var str = "<li data-id="+data[i].thirdGameZoneId +">"+data[i].localName+"</li>"
                    $(".list-zones").append(str);
                }
                var maxPage = Math.ceil(data.length/10);
                $(".pagination input").attr("data-max-page", maxPage);
                //分页
                $('.pagination').jqPagination({
                    paged: function(page) {
                        // do something with the page variable
                        $(".list-zones").html("");
                        var data = JSON.parse(sessionStorage.getItem("zones"));
                        var index = (page-1)*10;
                        for(var i = 0; i < 10&&index+i<data.length; i++){
                            var str = "<li data-id="+data[index+i].thirdGameZoneId +">"+data[index+i].localName+"</li>"
                            $(".list-zones").append(str);
                        }
                    }
                });

                // if(data.length == 1){
                //     $(".sel-zoneId span.target").text(data[0].zoneName).attr("data-id", data[0].zoneId);
                // }

                sessionStorage.setItem("zones", JSON.stringify(data));
            }else{
                console.log("获取区服失败");
            }
        },
        error: function(err){
            console.log("获取区服 \r\n"+err);
        }
    });
}

//获取角色信息
function getRoleInfo(){
    config.zoneId = $(".box-zoneId li.active").attr("data-id");
    var data = {
        appId: config.appId,
        userId: sessionStorage.getItem("userId"),
        gameZoneId: config.zoneId,
        timestamp: getCurrentTime()
    };
    data.sign = createRoleSignture(data);

    $.ajax({
        type: "GET",
        url: config.url + "/client/user/role",
        dataType: 'json',
        data: data,
        success: function(result){
            console.log("获取角色 \r\n"+JSON.stringify(result));
            var data = result.data;
            $(".box-playId").html("");
            if(result.code == 200){
                for(var i = 0; i < data.length; i++){
                    var str = "<li data-id="+data[i].roleId+" data-level="+data[i].roleLevel+">"+data[i].roleName+"</li>"
                    $(".box-playId").append(str);
                }
                if(data.length == 1){
                    $(".sel-playId span.target").text(data[0].roleName).attr("data-id", data[0].roleId);
                    $(".sel-playId span.target").attr("data-name", data[0].roleName);
                    $(".sel-playId span.target").attr("data-level", data[0].roleLevel);
                }
                // $(".sel-playId span.target").text(data.roleName).attr("data-id", data.roleId);
                // $(".sel-playId span.target").attr("data-name", data.roleName);
                // $(".sel-playId span.target").attr("data-level", data.roleLevel);

            }else{
                $(".sel-playId span.target").text(i18N.get("public.choosePlayId")).attr("data-id", "");
                $(".sel-playId span.target").attr("data-name", "");
                $(".sel-playId span.target").attr("data-level", "");
                console.log("获取角色列表失败");
            }
        },
        error: function(err){
            console.log(err);
        }
    });
}

//刮刮卡
function chargeForwardCard(){
    var data = getDataCard();
    if(!data.txtSeri || !data.pin){
        // alert("序列號或密碼不能為空~");
        alert(i18N.get("public.lackParam"));
        return ;
    }

    $.ajax({
        type: "POST",
        url: config.url + "card_v5.php",
        dataType: 'json',
        data: data,
        beforeSend: function(){
            $(".btn-card-charge").css("background-color","#959595").text(i18N.get("public.charging")).attr("disabled", "true");
            $(".win-wrap").show();
            $(".win-alert").show();
            $(".win.active").removeClass("active");
            $(".box-loading").addClass("active");
        },

        success: function(result){
            console.log(JSON.stringify(result));
            if(result.code == 200){
                $(".win-wrap").show();
                $(".win-alert").show();
                $(".win.active").removeClass("active");
                $(".alert-result .success p").text(i18N.get("public.chargeSuccess"))
                $(".alert-result").addClass("active");
                $(".card-number").val("");
                $(".card-password").val("");

            }else{
                $(".win-wrap").show();
                $(".win-alert").show();
                $(".win.active").removeClass("active");
                $(".alert-result .success p").text(i18N.get("status."+result.code));
                $(".alert-result").addClass("active");
            }
            $(".btn-card-charge").css("background-color","#00a7de").text(i18N.get("public.charge")).removeAttr("disabled");
        },

        error: function(err){
            console.log(JSON.stringify(err));
        }
    })
}

//网络银行
function chargeForwardWeb(){
    var data = getDataOnline();
    var tempwindow=window.open();
    $.ajax({
        type: "POST",
        url: config.url + "/bank.php",
        dataType: 'json',
        async:false,
        data: data,
        beforeSend: function(){
            $(".win-wrap").show();
            $(".win-alert").show();
            $(".win.active").removeClass("active");
            $(".box-loading").addClass("active");
        },
        success: function(result){
            console.log(JSON.stringify(result));
            if(result.code == 200){
                $(".win-wrap").show();
                $(".win-alert").show();
                $(".win.active").removeClass("active");
                $(".alert-rechanging").addClass("active");
                tempwindow.location=result.data.paymentUrl;
            }else{
                $(".win-wrap").show();
                $(".win-alert").show();
                $(".win.active").removeClass("active");
                $(".alert-result .success p").text(i18N.get("status."+result.code));
                $(".alert-result").addClass("active");
                tempwindow.close();
            }
        },
        error: function(err){
            console.log(JSON.stringify(err));
        }
    })
}

function chargeForwardSms(){
    var data = getDataSms();

    if(!data.gameZoneId){
        alert(i18N.get("public.chooseZone"));
        return ;
    }else if(!data.roleId){
        alert(i18N.get("public.choosePlayId"));
        return ;
    }

    data.sign = createSigntrue(data);

    $.ajax({
        type: "POST",
        url: config.url + "/web/chargeForwardWeb",
        dataType: 'json',
        async:false,
        data: data,
        beforeSend: function(){
            $(".win-wrap").show();
            $(".win-alert").show();
            $(".win.active").removeClass("active");
            $(".box-loading").addClass("active");
        },
        success: function(result){
            console.log(JSON.stringify(result));
            if(result.code == 200){
                if(isMobile()){
                    window.location.href = result.data.paymentUrl;
                }else{
                    $(".win-wrap").show();
                    $(".win-alert").show();
                    $(".win.active").removeClass("active");
                    $(".box-qrcode").addClass("active");
                    $(".qrcode").attr("src",  result.data.paymentUrl);
                }
            }else{
                $(".win-wrap").show();
                $(".win-alert").show();
                $(".win.active").removeClass("active");
                $(".alert-result .success p").text(i18N.get("status."+result.code));
                $(".alert-result").addClass("active");
            }
        },
        error: function(err){
            console.log(JSON.stringify(err));
        }
    })
}