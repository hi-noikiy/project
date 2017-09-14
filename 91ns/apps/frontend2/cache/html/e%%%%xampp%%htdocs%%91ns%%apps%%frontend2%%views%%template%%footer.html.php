<div class="wrapBm">
    <div class="w_1200 clearfix">
        <p style="padding-top:20px;padding-bottom:5px;">
            <?php if ($webType['channelType'] != 2) { ?>
            <a href="/about/about" target="_blank">关于我们</a>
            |
            <a href="/about/partner" target="_blank">商务合作</a>
            <?php } ?>
            <!-- <i></i>
            <a href="#">意见反馈</a>
            <i></i>-->
            |
            <a href="/help/helpanchor" target="_blank">帮助中心</a>
        </p>
        <p>网络文化经营许可证：<a href="http://www.miitbeian.gov.cn/" target="_blank">沪网文[2015]0711-161号</a> &nbsp;&nbsp;|&nbsp;&nbsp; 组织机构代码证：NO.2014 5734555 &nbsp;&nbsp;|&nbsp;&nbsp; 营业执照：04000000201504070097<br>地址：徐汇区华泾路509号7幢243室 &nbsp;&nbsp;|&nbsp;&nbsp; 商务QQ：438559282 &nbsp;&nbsp; <span class="t_arial">©</span> 2015 - 2018 91ns All Rights Reserved</p>
    </div>
</div>

<div class="JS-ALERT-BACKGROUND"></div>
<div class="ns-alert js-id-alert">
    <div class="win">
        <div class="header">
            <span class="_title"><?php echo $webType['name']; ?></span><span class="_tip">提示您</span>
            <i class="ico-main-2 alert-exit js-exitAlert-control"></i>
        </div>
        <div class="body clearfix"></div>
        <div class="bottom"></div>
    </div>
</div>
<div class="reg-avatar-upload" id="reg-avatar-upload">
    <div class="reg-avatar-flash" id="reg-avatar-flash"></div>
</div>
<!--dialog-login-->
<div id="theDayFirstLogin" style="height:0px;width:0px;overflow: visible;"></div>
<div class="dialog-mask"></div>

<?php if ($webType['channelType'] != 2) { ?>
<div class="dialog-login _register" id="loginbox">
    <div class="dialog-content clearfix">
        <div class="d-l-left">
            <!--登录-->
            <div id="login_user_box" class="login_user_box">
                <div class="row _texts d_focus clearfix">
                    <span class="_login_title">现在登录,</span>
                    <span class="_login_title">您就可以与主播聊天互动了！</span>
                </div>
                <div id="pcLogin" class="_LoginBy _loginShowDiv">
                    <div class="row d_focus clearfix d_point">
                        <span>用户名/手机号</span>
                    </div>
                    <div class="row d_focus clearfix">
                        <input class="login_username_input _regLogin _loginInput" type="text" name="lf_username" id="lf_username" autocomplete="off" size="12" fwin="login">
                    </div>
                    <div class="row d_focus clearfix d_point">
                        <span>密码</span>
                        <span class="_loginmsg status-tips lb-tips" style="float:right;"></span>
                    </div>
                    <div class="row d_focus clearfix">
                        <input class="login_pwd_input _regLogin _loginInput" type="password" id="lf_password" name="lf_password" size="30" class="px p_fre" fwin="login">
                    </div>
                    <div class="row checkbox clearfix" style="margin-top: 16px;">
                        <!--<i id="loginAutoSelect" class="auto-login-div autoLogin"></i>-->
                        <!--<a class="auto-login-lable">自动登录</a>-->
                        <a class="forgetpwd pull-right" href="/forgetpwd?ul=1">忘记密码>></a>
                    </div>
                </div>
                <div id="phoneLogin" class="_LoginBy _loginHideDiv">
                    <div class="_LoginByTelphone">
                        <div class="row d_focus clearfix d_point" style="margin-left: 15px;">
                            <span>手机号码</span>
                            <span id="rTelephone1" class="status-tips lb-tips" style="float:right;"></span>
                        </div>
                        <div class="row d_focus clearfix" style="margin-left: 15px;">
                            <label defaultdisplay="default" class="_tel_number" defaultvalue="请输入手机号码">请输入手机号码</label>
                            <input id="telPhone_Input" class="_regLogin _loginInput" type="text" name="lf_tel" autocomplete="off" size="12" fwin="login">
                            <!--<span id="rTelephone1" class="status-tips lb-tips" style="float:right;margin-top: -62px;"></span>-->

                        </div>
                        <div class="row d_focus clearfix d_point" style="margin-left: 15px;">
                            <!--<span>验证码</span>-->
                            <span id="regSecurityCodeTip1" class="ns_tip_color" style="float: right;margin-right: 80px;"></span>
                        </div>
                        <div class="row d_focus clearfix" id="regSecurityCodeL" style="margin-left: 15px;"></div>
                        <div class="_changeCode" style="margin-left: 205px;">
                            看不到<a onclick="changeSecurityCodeL();">换一换</a>
                        </div>
                    </div>
                    <div class="row d_focus clearfix d_point">
                        <span>验证码</span>
                        <span id="regTelephoneTip1" class="ns_tip_color" style="float: right;margin-right: 128px;"></span>
                    </div>
                    <div class="row d_focus clearfix">
                        <label defaultdisplay="default" class="_tel_number" defaultvalue="请输入验证码">请输入验证码</label>
                        <input id="regTelephone1" class="_regLogin _tel_number" type="text" autocomplete="off" size="12">
                        <span class="telGetCode" onclick="telGetCodeLogin(this);">获取验证码<i class="_all_code" style="display:none;">(<i>60</i>)</i></span>
                    </div>
                </div>

                <div id="divShowLogin" class="row btn" style="display: block;">
                    <div class="login-btn">
                        <div id="loginSubmit" class="_btn _loginSub" onclick="loginSubmit();" onselectstart="return false">登 录</div>
                    </div>
                </div>
                <div id="divHideLogin" class="row btn" style="display: none;">
                    <div class="login-btn">
                        <div id="loginTelSubmit" class="_btn _loginSub" onclick="loginTelSubmit();" onselectstart="return false">登 录</div>
                    </div>
                </div>
                <div class="row btn btn2">
                    <div class="login-btn">
                        <div id="loginType" class="_btn _loginSub" onclick="phoneLogin()" onselectstart="return false">手 机 登 录</div>
                    </div>
                </div>
            </div>
            <!--注册-->
            <div id="reg_user_box" class="reg_user_box">
                <div class="row d_focus clearfix _texts regType">
                    <span id="registerByNol" class="active">用户名注册</span>
                    <span id="registerByTel">手机号注册</span>
                    <span class="noContent"></span>
                </div>
                <div class="row d_focus clearfix d_point _nol_reg">
                    <span>用户名</span>
                    <span id="regUserNameTip" class="ns_tip_color" style="float: right;"></span>
                </div>
                <div class="row d_focus clearfix _nol_reg">
                    <label defaultdisplay="default" defaultvalue="4-12 字母，数字；区分大小写">4-12 字母，数字；区分大小写</label>
                    <input id="regUserName" class="_regLogin" type="text" autocomplete="off" size="12">
                </div>

                <div class="_LoginByTelphone _tel_reg" style="display:none;">
                    <div class="row d_focus clearfix d_point" style="margin-left:15px;*margin-left:26px;">
                        <span>手机号</span>
                        <span id="regTelephoneTip" class="ns_tip_color" style="float: right"></span>
                    </div>
                    <div class="row d_focus clearfix" style="margin-left: 15px;*margin-left:26px;">
                        <label defaultdisplay="default" class="_tel_number" defaultvalue="请输入手机号码">请输入手机号码</label>
                        <input id="regTelephone" class="_regLogin _loginInput" type="text" name="lf_tel" autocomplete="off" size="12" fwin="login">
                    </div>
                    <div class="row d_focus clearfix d_point" style="margin-left: 15px;*margin-left:26px;">
                        <!--<span>验证码</span>-->
                        <span id="regSecurityCodeTipR" class="ns_tip_color" style="float: right;margin-right: 80px;"></span>
                    </div>
                    <div class="row d_focus clearfix" id="regSecurityCodeR" style="margin-left: 15px;*margin-left:26px;"></div>
                    <div class="_changeCode" style="margin-left: 205px;">
                        看不到<a onclick="changeSecurityCodeR();">换一换</a>
                    </div>
                </div>
                <!-- <div class="row d_focus clearfix d_point _tel_reg">
                    <span>手机号</span>
                    <span id="regTelephoneTip" class="ns_tip_color" style="float: right"></span>
                </div> -->
                <!-- <div class="row d_focus clearfix _tel_reg">
                    <label defaultdisplay="default" class="_tel_number" defaultvalue="请输入11位数字">请输入11位数字</label>
                    <input id="regTelephone" class="_regLogin _tel_number" type="text" autocomplete="off" size="12">
                    <span class="telGetCode" onclick="telGetCode(this);">获取验证码<i class="_all_code" style="display:none;">(<i>60</i>)</i></span>
                </div> -->
                <div class="row d_focus clearfix d_point _tel_reg">
                    <span>手机验证码</span>
                    <span id="regSmsCodeTip" class="ns_tip_color" style="float: right"></span>
                </div>
                <div class="row d_focus clearfix _tel_reg">
                    <label defaultdisplay="default" defaultvalue="" class="_tel_number"></label>
                    <input id="regSmsCode" class="_regLogin _tel_number" type="text" autocomplete="off" size="12">
                    <span class="telGetCode" onclick="telGetCode(this);">获取验证码<i class="_all_code" style="display:none;">(<i>60</i>)</i></span>
                </div>

                <div class="row d_focus clearfix d_point _nol_reg">
                    <span>密码</span>
                    <span id="regUserPasswordTip" class="ns_tip_color" style="float: right"></span>
                </div>
                <div class="row d_focus clearfix _nol_reg">
                    <label defaultdisplay="default" defaultvalue="6-18 字母，数字；区分大小写">6-18 字母，数字；区分大小写</label>
                    <input id="regUserPassword" class="_regLogin" type="password" autocomplete="off" size="12" >
                    <!--<span id="regUserPasswordTip" class="lb-tips _right"></span>oninput="regCheckPWD1()" onpropertychange="regCheckPWD1()"-->
                </div>
                <div class="row d_focus clearfix d_point _nol_reg">
                    <span>确认密码</span>
                    <span id="regUserCheckPasswordTip" class="ns_tip_color" style="float: right"></span>
                </div>
                <div class="row d_focus clearfix _nol_reg">
                    <label defaultdisplay="default" defaultvalue="6-18 字母，数字；区分大小写">6-18 字母，数字；区分大小写</label>
                    <input id="regUserCheckPassword" class="_regLogin" type="password" autocomplete="off" size="12" >
                    <!--<span id="regUserCheckPasswordTip" class="lb-tips _right"></span>oninput="regCheckPWD2()" onpropertychange="regCheckPWD2()"-->
                </div>
                <div class="row d_focus clearfix d_point">
                    <span>昵称</span>
                    <span id="regNickNameTip" class="ns_tip_color" style="float: right"></span>
                </div>
                <div class="row d_focus clearfix">
                    <label defaultdisplay="default" defaultvalue="2-10 个字、不可纯数字">2-10 个字、不可纯数字</label>
                    <input id="regNickName" class="_regLogin" type="text" autocomplete="off" size="12">
                    <!--<span id="regNickNameTip" class="lb-tips _right"></span>-->
                </div>
                <div class="row d_focus clearfix d_point _nol_reg">
                    <span>验证码</span>
                    <span id="regSecurityCodeTip" class="ns_tip_color" style="float: right"></span>
                </div>
                <div class="row d_focus clearfix _nol_reg" id="regSecurityCode"></div>
                <div class="_changeCode _nol_reg">
                    看不到<a onclick="changeSecurityCode();">换一换</a>
                </div>
                <div class="row _agree d_focus clearfix" style="margin-top: 22px;">
                    <i id="agreeAutoSelect" class="auto-login-div autoLogin"></i>
                    <b>我已阅读并同意<a href="/agreement/reggreement" target="_blank">《91NS使用协议》</a></b>
                </div>
                <div class="row btn">
                    <div class="login-btn">
                        <div class="_btn" id="registerSubmit">同意并注册</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="height-line"></div>
        <div class="d-l-right">
            <div class="row row-close clearfix" style="margin-top:5px;">
                <a href="javascript:;" title="关闭" class="close">
                    <i class="ns_icon_close"></i>
                </a>
            </div>
            <div class="right-row" id="loginOrRegisterTip">
                <span class="_tip _login">没有账号？</span>
                <span class="_btn _login" onclick="userRegister()">马上注册</span>
                <span class="_tip _reg">已有账号？</span>
                <span class="_btn _reg" onclick="userLogin()">马上登录</span>
            </div>
            <?php if ((empty($ns_iscn) ? ('0') : ($ns_iscn)) != '1') { ?>
            <div class="right-row d-directly">
                <span class="_tip">快捷登录方式：</span>
            </div>
            <div class="thirdPLogin">
                <i class="sprite-thirdPLogin PLoginweixin" onclick="thirdLogin('weixin');return false;"></i>
                <i class="sprite-thirdPLogin PLoginqq" onclick="thirdLogin('qqdenglu');return false;"></i>
                <i class="sprite-thirdPLogin PLoginweibo" onclick="thirdLogin('sinaweibo');return false;"></i>
            </div>
            <?php } ?>
        </div>
    </div>
    <!--<div class="footer"></div>-->
</div>
<?php } else { ?>
<div class="dialog-login _register" id="douzilogin">
    <div class="dialog-content clearfix">
        <div class="d-l-left">
            <!--登录-->
            <div id="login_user_box" class="login_user_box">
                <div class="row _texts d_focus clearfix">
                    <span class="_login_title">现在登录,</span>
                    <span class="_login_title" style="margin-top: 6px;">您就可以与主播聊天互动了！</span>
                </div>
                <div class="row d_focus clearfix d_point" style="margin-top: 14px;">
                    <span>用户名</span>
                </div>
                <div class="row d_focus clearfix">
                    <input class="login_username_input _regLogin _loginInput" type="text" name="lf_username" id="lf_username" autocomplete="off" size="12" fwin="login">
                </div>
                <div class="row d_focus clearfix d_point">
                    <span>密码</span>
                    <span class="_loginmsg status-tips lb-tips" style="float:right;"></span>
                </div>
                <div class="row d_focus clearfix">
                    <input class="login_pwd_input _regLogin _loginInput" type="password" id="lf_password" name="lf_password" size="30" class="px p_fre" fwin="login">
                </div>
                <div class="row checkbox clearfix" style="margin-top: 16px;">
                    <!--<i id="loginAutoSelect" class="auto-login-div autoLogin"></i>-->
                    <!--<a class="auto-login-lable">自动登录</a>-->
                    <a class="forgetpwd pull-right" href="/forgetpwd">忘记密码>></a>
                </div>
                <div class="row btn">
                    <div class="login-btn">
                        <div id="loginSubmit" class="_btn _loginSub" onclick="loginSubmit();" onselectstart="return false">登 录</div>
                    </div>
                </div>
            </div>
            <!--注册-->
            <div id="reg_user_box" class="reg_user_box">
                <div class="row d_focus clearfix _texts">
                    注&nbsp;册
                </div>
                <div class="row d_focus clearfix d_point">
                    <span>用户名</span>
                    <span id="regUserNameTip" class="ns_tip_color" style="float: right"></span>
                </div>
                <div class="row d_focus clearfix">
                    <label defaultdisplay="default" defaultvalue="4-12 字母，数字；区分大小写">4-12 字母，数字；区分大小写</label>
                    <input id="regUserName" class="_regLogin" type="text" autocomplete="off" size="12">
                </div>
                <div class="row d_focus clearfix d_point">
                    <span>密码</span>
                    <span id="regUserPasswordTip" class="ns_tip_color" style="float: right"></span>
                </div>
                <div class="row d_focus clearfix">
                    <label defaultdisplay="default" defaultvalue="6-18 字母，数字；区分大小写">6-18 字母，数字；区分大小写</label>
                    <input id="regUserPassword" class="_regLogin" type="password" autocomplete="off" size="12" >
                    <!--<span id="regUserPasswordTip" class="lb-tips _right"></span>oninput="regCheckPWD1()" onpropertychange="regCheckPWD1()"-->
                </div>
                <div class="row d_focus clearfix d_point">
                    <span>确认密码</span>
                    <span id="regUserCheckPasswordTip" class="ns_tip_color" style="float: right"></span>
                </div>
                <div class="row d_focus clearfix">
                    <label defaultdisplay="default" defaultvalue="6-18 字母，数字；区分大小写">6-18 字母，数字；区分大小写</label>
                    <input id="regUserCheckPassword" class="_regLogin" type="password" autocomplete="off" size="12" >
                    <!--<span id="regUserCheckPasswordTip" class="lb-tips _right"></span>oninput="regCheckPWD2()" onpropertychange="regCheckPWD2()"-->
                </div>
                <div class="row d_focus clearfix d_point">
                    <span>昵称</span>
                    <span id="regNickNameTip" class="ns_tip_color" style="float: right"></span>
                </div>
                <div class="row d_focus clearfix">
                    <label defaultdisplay="default" defaultvalue="2-10 个字、不可纯数字">2-10 个字、不可纯数字</label>
                    <input id="regNickName" class="_regLogin" type="text" autocomplete="off" size="12">
                    <!--<span id="regNickNameTip" class="lb-tips _right"></span>-->
                </div>
                <div class="row d_focus clearfix d_point">
                    <span>验证码</span>
                    <span id="regSecurityCodeTip" class="ns_tip_color" style="float: right"></span>
                </div>
                <div class="row d_focus clearfix" id="regSecurityCode" style="height: 50px;"></div>
                <div class="_changeCode">
                    看不到<a onclick="changeSecurityCode();">换一换</a>
                </div>
                <div class="row _agree d_focus clearfix" style="margin-top: 10px;">
                    <i id="agreeAutoSelect" class="auto-login-div autoLogin"></i>
                    <b>我已阅读并同意<a href="http://www.7pmi.com/regAgree.html" target="_blank">《棋牌迷用户服务协议》</a></b>
                </div>
                <div class="row btn">
                    <div class="login-btn">
                        <div class="_btn" id="registerSubmit">同意并注册</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="height-line"></div>
        <div class="d-l-right">
            <div class="row row-close clearfix" style="margin-top:5px;">
                <a href="javascript:;" title="关闭" class="close">
                    <i class="ns_icon_close"></i>
                </a>
            </div>
            <div class="right-row" id="loginOrRegisterTip">
                <span class="_tip _login">没有账号？</span>
                <span class="_btn _login" onclick="userRegister()">马上注册</span>
                <span class="_tip _reg">已有账号？</span>
                <span class="_btn _reg" onclick="userLogin()">马上登录</span>
                <span style="padding-top: 10px;color:#6a6a6a;font-size: 12px;display:inline-block; ">支持棋牌迷游戏账号直接登录</span>
            </div>
            <div class="right-row d-directly">
                <span class="_tip">快捷登录方式：</span>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<!-- 多账号登录 -->
<div id="selUserAct" class="selUserAct">
    <div class="header">请选择要登录的账户</div>
    <div class="timeOut">
        <i class="lines"></i>
        <div class="timeCount">
            <span>30</span>秒
        </div>
    </div>
    <div class="ns_icon_close"></div>
    <div class="actBody hasScroll" onSelectStart="return false;">
        <div class="no-wh g-scroll">
            <div class="scroll-bg"></div>
            <div class="scroll-ban"></div>
        </div>
        <ul class="scroll-body">
        </ul>
    </div>
</div>
<div id="userCardInfo" class="userCard clearfix" jsaction="showCard" cardInfo="true">
    <i class="bgr"><i></i></i>
    <div class="cts clearfix">
        <img class="avatar" src="">
        <div class="baseInfo">
            <div class="base1 clearfix">
                <span></span>
            </div>
            <div class="_userInfoUid"><span></span></div>
            <div class="levelInfo">
            </div>
        </div>
        <span class="signature"></span>
    </div>
    <div class="cts anchorInfo clearfix">
        <div class="baseInfo clearfix">
            <span bindData="gender"></span>
            <span>|</span>
            <span bindData="birthday"></span>
            <span>|</span>
            <span bindData="location"></span>
            <span>|</span>
            <a>TA的空间</a>
        </div>
        <div class="btn focus">关注</div>
        <a class="btn goRoom">进入房间</a>
    </div>
</div>
<script type="text/javascript" src="<?php echo $this->url->getStatic("web/js/20160317/ns.js"); ?>"></script>
<script type="text/javascript">
    var isAddGoogleCode = '<?php echo (empty($isAddGoogleCode) ? (false) : ($isAddGoogleCode)); ?>';
    if(isAddGoogleCode){
        //声明_czc对象:
        var _czc = _czc || [];
        var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_<?php echo $webType['cnzzID']; ?>'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "w.cnzz.com/q_stat.php%3Fid%3D<?php echo $webType['cnzzID']; ?>' type='text/javascript'%3E%3C/script%3E"));
    }
</script>
<script type="text/javascript" src="<?php echo $this->url->getStatic("web/js/20160317/ga.cnzz.js"); ?>"></script>
