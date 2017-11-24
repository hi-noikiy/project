<?php if (!defined('THINK_PATH')) exit(); /*a:7:{s:64:"/data/home/hngame/public/../application/admin/view/job/edit.html";i:1509961195;s:67:"/data/home/hngame/public/../application/admin/view/public/base.html";i:1509598470;s:75:"/data/home/hngame/public/../application/admin/view/public/admin_load_t.html";i:1505569886;s:72:"/data/home/hngame/public/../application/admin/view/public/admin_top.html";i:1507990064;s:73:"/data/home/hngame/public/../application/admin/view/public/admin_left.html";i:1507990021;s:75:"/data/home/hngame/public/../application/admin/view/public/admin_bottom.html";i:1507990082;s:75:"/data/home/hngame/public/../application/admin/view/public/admin_load_b.html";i:1509956980;}*/ ?>
<?php if($box_is_pjax != 1): ?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<?php endif; ?>
<title><?php if($data): ?><?php echo \think\Lang::get('edit'); else: ?><?php echo \think\Lang::get('create'); endif; ?></title>

<?php if($box_is_pjax != 1): ?>
<link rel="stylesheet" type="text/css" href="__static__/global/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="__static__/global/bootstrap/css/font-awesome.min.css" />
<link rel="stylesheet" type="text/css" href="__static__/system/iCheck/minimal/blue.css" />
<link rel="stylesheet" type="text/css" href="__static__/system/select2/select2.min.css" />
<link rel="stylesheet" type="text/css" href="__static__/system/dist/css/AdminLTE.min.css" />
<link rel="stylesheet" type="text/css" href="__static__/system/dist/css/skins/skin-blue.min.css" />

<script type="text/javascript" src="__static__/global/jQuery/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="__static__/global/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="__static__/system/slimScroll/jquery.slimscroll.min.js"></script>
<script type="text/javascript" src="__static__/system/dist/js/app.min.js"></script>
<script type="text/javascript" src="__static__/global/jQuery/jquery.pjax.js"></script>

<link rel="stylesheet" type="text/css" href="__static__/system/kindeditor/themes/default/default.css" />
<script type="text/javascript" src="__static__/system/kindeditor/kindeditor-all.js"></script>
<script type="text/javascript" src="__static__/system/kindeditor/lang/zh-CN.js"></script>
<!--[if lt IE 9]>
<script type="text/javascript" src="__static__/system/dist/js/html5shiv.min.js"></script>
<script type="text/javascript" src="__static__/system/dist/js/respond.min.js"></script>
<![endif]-->
<?php endif; if($box_is_pjax != 1): ?>
</head>
<body class="hold-transition skin-blue fixed sidebar-mini">
<div class="wrapper">
<?php endif; if($box_is_pjax != 1): ?>
    <header class="main-header">
        <a href="#" class="logo"><span class="logo-mini">HN</span><span class="logo-lg">HN game</span></a>
        <nav class="navbar navbar-static-top">
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
<!--                     <li class="dropdown messages-menu">
    <a href="/" target="_blank" ><?php echo \think\Lang::get('web_home'); ?></a>
</li>
<li class="dropdown messages-menu">
    <a href="javascript:void(0);" class="delete-one" data-url="<?php echo url('Index/cleanCache'); ?>" data-id="-1" ><?php echo \think\Lang::get('clean_cache'); ?></a>
</li> -->
                    <li class="dropdown user user-menu">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="<?php echo cookie('avatar'); ?>" class="user-image">
                            <span class="hidden-xs"><?php echo cookie('name'); ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header">
                                <img src="<?php echo cookie('avatar'); ?>" class="img-circle">
                                <p><?php echo cookie('name'); ?><small>Member since admin</small></p>
                            </li>
                            <li class="user-footer">
                                <div class="pull-left"><a href="<?php echo url('User/edit', ['id' => cookie('uid')]); ?>" class="btn btn-default btn-flat">个人设置</a></div>
                                <div class="pull-right"><a href="<?php echo url('Login/loginOut'); ?>" class="btn btn-default btn-flat">退出</a></div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
<?php endif; if($box_is_pjax != 1): ?>
    <aside class="main-sidebar">
        <section class="sidebar">
            <div class="user-panel">
                <div class="pull-left image"><img src="<?php echo cookie('avatar'); ?>" class="img-circle" alt="<?php echo cookie('name'); ?>"></div>
                <div class="pull-left info">
                    <p><?php echo cookie('name'); ?></p>
                    <a href="#"><i class="fa fa-circle text-success"></i>在线</a>
                </div>
            </div>
            <ul class="sidebar-menu">
                <li class="header">菜单</li>
                <?php if(is_array($treeMenu) || $treeMenu instanceof \think\Collection || $treeMenu instanceof \think\Paginator): $i = 0; $__LIST__ = $treeMenu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$oo): $mod = ($i % 2 );++$i;if($oo['level'] == '1' && $oo['name'] == 'Index/index'): ?>
                    <li><a href="<?php echo url(MODULE_NAME.'/'.$oo['name']); ?>"><i class="<?php echo $oo['icon']; ?>"></i><span><?php echo $oo['title']; ?></span></a></li>
                    <?php elseif($oo['level'] == '1'): ?>
                    <li class="treeview">
                        <a href="javascript:void(0);">
                            <i class="<?php echo $oo['icon']; ?>"></i><span><?php echo $oo['title']; ?></span>
                            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                        </a>
                        <ul class="treeview-menu">
                            <?php if(is_array($treeMenu) || $treeMenu instanceof \think\Collection || $treeMenu instanceof \think\Paginator): $i = 0; $__LIST__ = $treeMenu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$to): $mod = ($i % 2 );++$i;if($to['pid'] == $oo['id']): ?>
                            <li><a href="<?php echo url(MODULE_NAME.'/'.$to['name']); ?>"><i class="<?php echo $to['icon']; ?>"></i><?php echo $to['title']; ?></a></li>
                            <?php endif; endforeach; endif; else: echo "" ;endif; ?>
                        </ul>
                    </li>
                    <?php endif; endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </section>
    </aside>
<?php endif; ?>
    
    
    <div class="content-wrapper" id="pjax-container">
        
<section class="content-header">
    <h1>招聘信息</h1>
    <ol class="breadcrumb">
        <li class="active"><i class="fa fa-dashboard"></i> 招聘信息</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <form class="form-horizontal" method="POST" action="" onsubmit="return false" >
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab1" data-toggle="tab"><?php echo \think\Lang::get('base_param'); ?></a></li>
                        <li class="pull-right"><a href="javascript:history.back(-1)" class="btn btn-sm" style="padding:10px 2px;"><i class="fa fa-list"></i> <?php echo \think\Lang::get('back'); ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab1">
                            <input type="hidden" name="id" value="<?php echo $data['id']; ?>" />
                            <input type="hidden" name="subType" value="form" />
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo \think\Lang::get('name'); ?></label>
                                <div class="col-sm-7">
                                <div class="input-group"><input class="form-control" id="name" name="name" value="<?php echo $data['name']; ?>" placeholder="<?php echo \think\Lang::get('name'); ?>">
                                <span class="input-group-btn">
                                    <button id="translateNameBtn" class="btn" type="button">
                                        <i class="fa fa-level-down">翻译</i>
                                    </button>
                                </span>
                                </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo \think\Lang::get('name'); ?></label>
                                <div class="col-sm-7"><input class="form-control" id="name_en" name="name_en" value="<?php echo $data['name_en']; ?>" placeholder="<?php echo \think\Lang::get('name_en'); ?>"></div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo \think\Lang::get('description'); ?></label>
                                <div class="col-sm-7">
                                <div class="input-group"><textarea class="form-control" style="resize:none;height:80px;" id="description" name="description" placeholder="<?php echo \think\Lang::get('description'); ?>"><?php echo $data['description']; ?></textarea>
                                <span class="input-group-btn">
                                    <button id="translateDescBtn" class="btn" type="button">
                                        <i class="fa fa-level-down">翻译</i>
                                    </button>
                                </span>
                                </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo \think\Lang::get('description_en'); ?></label>
                                <div class="col-sm-7"><textarea class="form-control" style="resize:none;height:80px;" id="description_en" name="description_en" placeholder="<?php echo \think\Lang::get('description_en'); ?>"><?php echo $data['description_en']; ?></textarea></div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo \think\Lang::get('begin_time'); ?></label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input class="form-control timepicker" name="begin_time" value="<?php echo $data['begin_time']; ?>" placeholder="<?php echo \think\Lang::get('begin_time'); ?>" >
                                        <div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo \think\Lang::get('end_time'); ?></label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input class="form-control timepicker" name="end_time" value="<?php echo $data['end_time']; ?>" placeholder="<?php echo \think\Lang::get('end_time'); ?>" >
                                        <div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo \think\Lang::get('need'); ?></label>
                                <div class="col-sm-7"><input class="form-control" name="need" value="<?php echo $data['need']; ?>" placeholder="<?php echo \think\Lang::get('need'); ?>"></div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo \think\Lang::get('address'); ?></label>
                                <div class="col-sm-7">
                                <div class="input-group"><input class="form-control" id="address" name="address" value="<?php echo $data['address']; ?>" placeholder="<?php echo \think\Lang::get('address'); ?>">
                                <span class="input-group-btn">
                                    <button id="translateAddressBtn" class="btn" type="button">
                                        <i class="fa fa-level-down">翻译</i>
                                    </button>
                                </span>
                                </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo \think\Lang::get('address_en'); ?></label>
                                <div class="col-sm-7"><input class="form-control" id="address_en" name="address_en" value="<?php echo $data['address_en']; ?>" placeholder="<?php echo \think\Lang::get('address_en'); ?>"></div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo \think\Lang::get('contacts'); ?></label>
                                <div class="col-sm-7">
                                <div class="input-group"><input class="form-control" id="contacts" name="contacts" value="<?php echo $data['contacts']; ?>" placeholder="<?php echo \think\Lang::get('contacts'); ?>">
                                <span class="input-group-btn">
                                    <button id="translateContactsBtn" class="btn" type="button">
                                        <i class="fa fa-level-down">翻译</i>
                                    </button>
                                </span>
                                </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo \think\Lang::get('contacts_en'); ?></label>
                                <div class="col-sm-7"><input class="form-control" id="contacts_en" name="contacts_en" value="<?php echo $data['contacts_en']; ?>" placeholder="<?php echo \think\Lang::get('contacts_en'); ?>"></div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo \think\Lang::get('email'); ?></label>
                                <div class="col-sm-7"><input class="form-control" name="email" value="<?php echo $data['email']; ?>" placeholder="<?php echo \think\Lang::get('email'); ?>"></div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo \think\Lang::get('phone'); ?></label>
                                <div class="col-sm-7"><input class="form-control" name="phone" value="<?php echo $data['phone']; ?>" placeholder="<?php echo \think\Lang::get('phone'); ?>"></div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo \think\Lang::get('sorts'); ?></label>
                                <div class="col-sm-7"><input class="form-control" name="sorts" value="<?php echo (isset($data['sorts']) && ($data['sorts'] !== '')?$data['sorts']:'1'); ?>" placeholder="<?php echo \think\Lang::get('sorts'); ?>"></div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo \think\Lang::get('status'); ?></label>
                                <div class="col-sm-7">
                                    <select class="form-control select2" name="status" style="width:100%;">
                                        <option value="1" <?php if($data['status'] == '1'): ?>selected="selected"<?php endif; ?> ><?php echo \think\Lang::get('status1'); ?></option>
                                        <option value="0" <?php if($data['status'] == '0'): ?>selected="selected"<?php endif; ?> ><?php echo \think\Lang::get('status0'); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-7">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-info submits" data-loading-text="&lt;i class='fa fa-spinner fa-spin '&gt;&lt;/i&gt; <?php echo \think\Lang::get('submit'); ?>"><?php echo \think\Lang::get('submit'); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<script type="text/javascript">
$(function(){
    /*ajax页面加载icheck，有该控件的页面才需要*/
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
    });

     $('.timepicker').datetimepicker({
        format: 'YYYY-MM-DD',   //YYYY-MM-DD HH:mm:ss
        locale: moment.locale('zh-cn')
    });

    var translateNameBtn = document.getElementById( 'translateNameBtn' );
    translateNameBtn.onclick = function() {
        doTranslate( setNameEn,'name' );
    };

    var translateDescBtn = document.getElementById( 'translateDescBtn' );
    translateDescBtn.onclick = function() {
        doTranslate( setDescEn,'description' );
    };

    var translateAddressBtn = document.getElementById( 'translateAddressBtn' );
    translateAddressBtn.onclick = function() {
        doTranslate( setAddressEn,'address' );
    };

    var translateContactsBtn = document.getElementById( 'translateContactsBtn' );
    translateContactsBtn.onclick = function() {
        doTranslate( setContactsEn,'contacts' );
    };
    
    /*ajax页面加载icheck，有该控件的页面才需要*/
    $(".select2").select2({language:"zh-CN"});
    
    <?php if($rest_login == 1): ?>
    restlogin('<?php echo $rest_login_info; ?>');   //登录超时
    <?php endif; ?>
})

function doTranslate(callBack,elementId){
    var chStr = $("#"+elementId).val();
    var enStr = translate(callBack,chStr);
}

function setDescEn(enStr){
    $("#description_en").val(enStr);
}

function setNameEn(enStr){
    $("#name_en").val(enStr);
}

function setAddressEn(enStr){
    $("#address_en").val(enStr);
}

function setContactsEn(enStr){
    $("#contacts_en").val(enStr);
}

</script>

    </div>
    
        
<?php if($box_is_pjax != 1): ?>
    <footer class="main-footer">
        <div class="pull-right">Version 1.0</div>
        Copyright &copy; 2017-2018 海牛游戏
    </footer>
<?php endif; if($box_is_pjax != 1): ?>
</div>
<?php endif; if($box_is_pjax != 1): ?>
<script type="text/javascript" src="__static__/global/jQuery/jquery.form.js"></script>

<script type="text/javascript" src="__static__/system/editable/bootstrap-editable.js"></script>
<link rel="stylesheet" type="text/css" href="__static__/system/editable/bootstrap-editable.css" />

<script type="text/javascript" src="__static__/global/nprogress/nprogress.js"></script>
<link rel="stylesheet" type="text/css" href="__static__/global/nprogress/nprogress.css" />

<link rel="stylesheet" type="text/css" href="__static__/global/jQuery-gDialog/animate.min.css" />
<link rel="stylesheet" type="text/css" href="__static__/global/Amaranjs/amaran.min.css" />
<script type="text/javascript" src="__static__/global/Amaranjs/jquery.amaran.min.js"></script>
<link rel="stylesheet" type="text/css" href="__static__/global/bootstrap/js/bootstrap-dialog.min.css" />
<script type="text/javascript" src="__static__/global/bootstrap/js/bootstrap-dialog.min.js"></script>

<script type="text/javascript" src="__static__/system/datetimepicker/moment-with-locales.min.js"></script>
<link rel="stylesheet" type="text/css" href="__static__/system/datetimepicker/bootstrap-datetimepicker.min.css" />
<script type="text/javascript" src="__static__/system/datetimepicker/bootstrap-datetimepicker.min.js"></script>

<script type="text/javascript" src="__static__/system/multiselect/multiselect.min.js"></script>

<script type="text/javascript" src="__static__/system/iCheck/icheck.min.js"></script>

<script type="text/javascript" src="__static__/system/select2/select2.min.js"></script>
<script type="text/javascript" src="__static__/system/select2/i18n/zh-CN.js"></script>

<link rel="stylesheet" type="text/css" href="__static__/global/cropper/cropper.min.css" />
<script type="text/javascript" src="__static__/global/cropper/cropper.min.js"></script>
<link rel="stylesheet" type="text/css" href="__static__/global/cropper/cropper.main.css" />
<script type="text/javascript" src="__static__/global/cropper/cropper.main.js"></script>

<script type="text/javascript" src="__static__/system/chart/Chart.min.js"></script>
<script type="text/javascript" src="__static__/system/dist/js/md5.js"></script>
<script type="text/javascript" src="__static__/system/dist/js/common.js"></script>
<script type="text/javascript" src="__static__/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="__static__/ckfinder/ckfinder.js"></script>
<?php endif; if($box_is_pjax != 1): ?>
</body>
</html>
<?php endif; ?>