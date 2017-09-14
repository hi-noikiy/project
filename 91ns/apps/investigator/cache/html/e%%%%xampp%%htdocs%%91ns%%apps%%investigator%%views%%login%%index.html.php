
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <?php echo $this->tag->getTitle(); ?>
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="<?php echo $this->url->getStatic('web/css/bootstrap.min.css'); ?>" />
        <link rel="stylesheet" href="<?php echo $this->url->getStatic('web/css/bootstrap-responsive.min.css'); ?>" />
        <link rel="stylesheet" href="<?php echo $this->url->getStatic('web/css/matrix-login.css'); ?>" />
        <link rel="stylesheet" href="<?php echo $this->url->getStatic('web/font-awesome/css/font-awesome.css'); ?>"  />
    </head>
    <script type="text/javascript">
        $(function(){
            $('#user-nav').hide();
            $('#user-info').hide();
            $('#header').hide();
            $('#header').next().hide();
        })
    </script>
    <body>

        <?php echo $this->getContent(); ?>
        <div id="loginbox">
            <form id="loginform" style="background-color: #e6e8f4;" class="form-vertical" action="">
                 <div class="control-group normal_text" style="width: 416px;height: 74px; background:url(../web/img/u4.png) ">
                    <!--<h1>-->
                     <img src="<?php echo $this->url->getStatic('web/img/icon_u6.png'); ?>" alt="Logo" />
                    <!--后台管理系统-->
                    <!--</h1>-->
                 </div>
                <div class="control-group">
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on bg_lg"><i class="icon-user"></i></span><input type="text" id="username" placeholder="用户名" />
                            <!--<label class="text-left mssage"><span class="help-inline">用户名错误</span></label>-->
                            
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on bg_ly"><i class="icon-lock"></i></span><input type="password" id="password" placeholder="密码"/>
                            <label style="margin-top: 15px;" class="text-left mssage open"><span class="help-inline" style="color: red"></span></label>
                        </div>
                    </div>
                </div>
                <div class="form-actions" style="margin: -15px auto 0px;width: 100px;height: 30px;">
                    <!-- <span class="pull-left"><a href="#" class="flip-link btn btn-info" id="to-recover">忘记密码</a></span> -->
                    <span class="pull-right"><a id="btnlogin" style="width: 100px;height: 30px;line-height: 30px;font-size: 16px;background-color: #817cce" type="submit"  class="btn btn-success"  onclick="login()"/> 登&nbsp;录</a></span>
                </div>
            </form>
            <form id="recoverform" action="#" class="form-vertical">
                <p class="normal_text">输入您的电子邮件地址，我们会送你说明如何恢复密码.</p>
                
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on bg_lo"><i class="icon-envelope"></i></span><input type="text" placeholder="邮箱地址" />
                        </div>
                    </div>
               
                <div class="form-actions">
                    <span class="pull-left"><a href="#" class="flip-link btn btn-success" id="to-login">&laquo; 返回&nbsp;登录</a></span>
                    <span class="pull-right"><a class="btn btn-info"/>发&nbsp;送</a></span>
                </div>
            </form>
        </div>
        
        <script src="<?php echo $this->url->getStatic('web/js/jquery.min.js'); ?>"></script>  
        <script src="<?php echo $this->url->getStatic('web/js/matrix.js'); ?>"></script>
        <script src="<?php echo $this->url->getStatic('web/js/matrix.login.js'); ?>"></script> 

    </body>
</html>
<script type="text/javascript">
    $(function(){
    })

    $('#username').on('keypress',function(e){
        e = e || window.event;
        if(13 == e.keyCode){
            $('#btnlogin').click();
            e.returnValue = false
            return false
        }
    })
    $('#password').on('keypress',function(e){
        e = e || window.event;
        if(13 == e.keyCode){
            $('#btnlogin').click();
            e.returnValue = false
            return false
        }
    })

    function login(){
        var username=$('#username').val();
        var password=$('#password').val();
        data={};
        data.username=username;
        data.password=password;
        $.ajax({
            type:"post",
            data:data,
            url:'/ajax/login',
            dataType:'json',
            success:function(res){
                if(res.code==0){
                    $('.mssage .help-inline').html('');
                    location.href='/index';
                }
                else{
                    $('.mssage .help-inline').html('用户名或密码错误，请检查后重新输入！')
                }
            }
        })
    }
</script>