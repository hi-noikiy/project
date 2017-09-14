<div id="register1"style="margin-top: 30px;background-color:  #FFFFFF;">
 <ul class="lt" style="width: 720px;">
     <li class="first" style="text-align: center;">欢迎登录海牛游戏</li>
    <div class="login-box">
    <div class="box-con tran">
        <form method="post" action="#" id="form" name="form">
        <div class="login-con f-l">
            <div class="form-group">
                <input type="text" name="username" id="username" placeholder="邮箱/手机号码"/>
                <span class="error-notic"></span>
            </div>

            <div class="form-group">
                <input type="password"  name="password" id="password" placeholder="密码">
                <span class="error-notic"></span>
            </div>
            <div class="form-group">
                <button type="submit" class="tran pr" >
                    <a href="javascript:;" class="tran" onclick="$('#form').submit()">登录</a>
                    <img class="loading" src="" style="display:block">
                </button>
            </div>
            <div id="confirm"></div>
            <div class="from-line"></div>
            <div class="form-group">
                <a href="javascript:;" class="move-signup a-tag tran blue-border">还没有帐号？免费注册<i class="iconfont tran">&#xe606;</i></a>
            </div>
            <div class="form-group">
                <a href="javascript:;" class=""> <i class="iconfont tran"></i></a>
            </div>
            <div class="form-group">
                <a href="javascript:;" class=""><i class="iconfont tran"></i></a>
            </div>
        </div>
        </form>
        <!-- end login-->
        <!-- start register -->
        <form name="RegForm" method="post" action="#" id="regFrom">
        <div class="signup f-l">
            <div class="form-group">
                <div class="signup-form">
                    <input type="text" name="username" placeholder="邮箱" class="email-mobile" onblur="verify.verifyEmail(this)">
                    <a href="javascript:;" class="signup-select">手机注册</a>
                </div>
                <span class="error-notic"></span>
            </div>
            <div class="form-group">
                <input name="password" type="password" placeholder="密码（字母、数字，至少6位）" onblur="verify.PasswordLenght(this)">
                <span class="error-notic"></span>
            </div>
            <div class="signup-email">
                <div class="form-group">
                    <button type="submit" class="tran pr">
                        <a href="javascript:;" class="tran" onclick="$('#regFrom').submit()">注册</a>
                        <img class="loading" src="">
                    </button>
                </div>
                <p class="view-clause">点击注册，即同意我们的 <a href="#">用户隐私条款</a></p>
            </div>
            <!-- 邮箱注册 -->
            <div class="signup-tel" style="display:none">
                <div class="signup-form" id="message-inf" >
                    <input type="text" placeholder="发送验证码" name="code" style="width:180px;" onblur="verify.VerifyCount(this)">
                    <input name="btn" id="btn" class="get-code get-message"  style="margin-top: -3px;" value="发送验证码" />
                    <span class="error-notic"></span>
                </div>
                <div class="form-group">
                    <button type="submit"  name="submit" class="tran pr">
                        <a href="javascript:;" class="tran">注册</a>
                        <img class="loading" src="">
                    </button>
                </div>
            </div>
            <!-- 手机号码注册 -->
            <div class="from-line"></div>
            <div class="form-group">
                <a href="javascript:;" class=""><i class="iconfont tran"></i></a>
            </div>
            <div class="form-group">
                <a href="javascript:;" class=""><i class="iconfont tran"></i></a>
            </div>
        </div>
        </form>
        <!-- 注册 -->
    </div>
    </div>
</ul>
</div>
<link href="<?=ASSETS_URL; ?>css/login.css" rel="stylesheet" type="text/css">
<script>
var _handle='';//储存电话是否填写正确
$(function(){
    $(".signup-form input").on("focus",function(){
        $(this).parent().addClass("border");
    });
    $(".signup-form input").on("blur",function(){
        $(this).parent().removeClass("border");
    })
    $(document).ready(function(){
        $("#form").submit(function(){
            var user = $("#username").val();
            var pass = $("#password").val();
            if (user == ""){
                $("#confirm").text("请输入登录用户名");
                $("#username").focus();
                return false;
            }
            if(pass == ""){
                $("#confirm").text("请输入登录密码");
                $("#password").focus();
                return false;
            }
            $.ajax({
                type: "POST",
                url: "<?=$this->createUrl('user/UserLogin');?>",
                data: "username=" + user + "&password=" + pass,
                beforeSend: function(){
                    $("confirm").text("登录中，请稍候");
                },
                success: function(data){
                    var obj = JSON.parse(data);
                    if( obj.status == 0){
                        $("#confirm").text("登录成功，欢迎" + user + "回来！正在进入你的空间");
                        window.location.href="/";
                    }else {
                        $("#confirm").text(obj.msg);
                    }
                }
            });
            return false;
        });
        $("#regFrom").submit(function () {
            var $user = $(this).find("input[name='username']");
            var $pass = $(this).find("input[name='password']");
            var $code = $(this).find("input[name='code']");

            if ($user.val() == ""){
                return false;
            }
            if($pass.val() == ""){
                return false;
            }
            var data = "username=" + $user.val() + "&password=" + $pass.val();
            if(_handle)
                data = "username=" + $user.val() + "&password=" + $pass.val()+"&code="+$code.val();
            $.ajax({
                type: "POST",
                url: "<?=$this->createUrl('user/UserRegister');?>",
                data: data,
                success: function(data){
                    var obj = JSON.parse(data);
                    if( obj.status == 0 && obj.msg =='success'){
                        window.location.href="/";
                    }else {
                        alert(obj.msg);
                    }
                }
            });
            return false;
        });
    });
    //注册方式切换
    $(".signup-select").on("click",function(){
        var _text=$(this).text();
        var $_input=$(this).prev();
        $_input.val('');
        if(_text=="手机注册"){
            $(".signup-tel").fadeIn(200);
            $(".signup-email").fadeOut(180);
            $(this).text("邮箱注册");
            $_input.attr("placeholder","手机号码");
            $_input.attr("onblur","verify.verifyMobile(this)");
            $(this).parents(".form-group").find(".error-notic").text("手机号码格式不正确")
        }
        if(_text=="邮箱注册"){
            $(".signup-tel").fadeOut(180);
            $(".signup-email").fadeIn(200);
            $(this).text("手机注册");
            $_input.attr("placeholder","邮箱");
            $_input.attr("onblur","verify.verifyEmail(this)");
            $(this).parents(".form-group").find(".error-notic").text("邮箱格式不正确")
        }

    });
    //步骤切换
    var _boxCon=$(".box-con");
    $(".move-signup").on("click",function(){
        $(_boxCon).css({
            'marginLeft':-320
        })
    });
    //获取短信验证码
    $(".get-message").on("click",function(){
        if(_handle){
            settime(this);
        } else {
           alert('手机不能为空.');
            return false;
        }
        var phone = $(".email-mobile").val();
        $.ajax({
            type: "POST",
            url: "<?=$this->createUrl('user/smsSent');?>",
            data: "phone=" + phone,
            success: function(data){
                //var obj = JSON.parse(data);
            }
        });

    });
});
//错误提示显示
function showNotic(_this){
    $(_this).parents(".form-group").find(".error-notic").fadeIn(100);
    $(_this).focus();

}
//错误提示隐藏
function hideNotic(_this){
    $(_this).parents(".form-group").find(".error-notic").fadeOut(100);
}
var countdown = 60;
function settime(obj) {
    if (countdown == 0) {
        obj.removeAttribute("disabled");
        obj.value="重获验证码";
        countdown = 60;
        return;
    } else {
        obj.setAttribute("disabled", true);
        obj.value="重新发送(" + countdown + ")";
        countdown--;
    }
    setTimeout(function() {settime(obj) },1000);
}

var verify={
    verifyEmail:function(_this){
        var validateReg = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        var _value=$(_this).val();
        if(!validateReg.test(_value)){
            showNotic(_this)
        }else{
            hideNotic(_this)
        }

    },//验证邮箱

    verifyMobile:function(_this){
        var validateReg = /^((\+?86)|(\(\+86\)))?1\d{10}$/;
        var _value=$(_this).val();
        if(!validateReg.test(_value)){
            showNotic(_this);
            _handle=false;
        }else{
            hideNotic(_this);
            _handle=true;
        }
        return _handle
    },//验证手机号码
    PasswordLenght:function(_this){
        var _length=$(_this).val().length;
        if(_length<6){
            showNotic(_this)
        }else{
            hideNotic(_this)
        }

    },//验证设置密码长度

    VerifyCount:function(_this){
        var _count="1234";
        var _value=$(_this).val();
        console.log(_value)
        if(_value!=_count){
            showNotic(_this)
        }else{
            hideNotic(_this)
        }
    }//验证验证码
}
</script>
</body>
</html>