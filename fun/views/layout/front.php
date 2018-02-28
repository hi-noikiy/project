<!DOCTYPE html>
<html lang="zh_cn">
<!--[if IE 9 ]><html class="ie9"><![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SeaCaw Data</title>

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="<?=base_url()?>public/ma/vendors/bootstrap-multiselect/dist/css/bootstrap-multiselect.css"/>
    <link href="<?=base_url()?>public/ma/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
    <link href="<?=base_url()?>public/ma/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.css" rel="stylesheet">
    <link href="<?=base_url()?>public/ma/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
    <link href="<?=base_url()?>public/ma/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet">
    <link href="<?=base_url()?>public/ma/vendors/bootgrid/jquery.bootgrid.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?=base_url()?>public/ma/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css"/>
    <!-- CSS -->
    <link href="<?=base_url()?>public/ma/css/app.min.1.css" rel="stylesheet">
    <link href="<?=base_url()?>public/ma/css/app.min.2.css" rel="stylesheet">
    <!--<script src="--><?//=base_url()?><!--public/js/echarts.simple.min.js"></script>-->
    <script src="<?=base_url()?>public/ma/js/echarts-all-3.0.0.js"></script>
</head>
<body>
<header id="header" class="clearfix" data-current-skin="blue">
    <ul class="header-inner">
        <li id="menu-trigger" data-trigger="#sidebar">
            <div class="line-wrap">
                <div class="line top"></div>
                <div class="line center"></div>
                <div class="line bottom"></div>
            </div>
        </li>

        <li class="logo hidden-xs">
            <a href="/">数据统计</a>
        </li>

        <li class="pull-right">
            <ul class="top-menu">
                <li id="toggle-width">
                    <div class="toggle-switch">
                        <input id="tw-switch" type="checkbox" hidden="hidden">
                        <label for="tw-switch" class="ts-helper"></label>
                    </div>
                </li>
            </ul>
        </li>
    </ul>
    <!-- Top Search Content -->
    <div id="top-search-wrap">
        <div class="tsw-inner">
            <i id="top-search-close" class="zmdi zmdi-arrow-left"></i>
            <input type="text">
        </div>
    </div>
</header>

<section id="main">
<?php echo $left; ?>
<!-- Javascript Libraries -->
    <script src="<?=base_url()?>public/ma/vendors/bower_components/jquery/dist/jquery.min.js"></script>
    <!--<script src="http://apps.bdimg.com/libs/jquery/1.8.0/jquery.min.js"></script>-->
    <!--<script src="--><?//=base_url()?><!--public/ma/js/jquery-ui-1.10.4.min.js"></script>-->
    <script src="<?=base_url()?>public/ma/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?=base_url()?>public/ma/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="<?=base_url()?>public/ma/vendors/bower_components/Waves/dist/waves.min.js"></script>
    <script src="<?=base_url()?>public/ma/vendors/bootstrap-growl/bootstrap-growl.min.js" ></script>
    <script src="<?=base_url()?>public/ma/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.min.js" ></script>
    <script src="<?=base_url()?>public/ma/vendors/bootgrid/jquery.bootgrid.updated.min.js"></script>
    <script src="<?=base_url()?>public/ma/vendors/bootstrap-multiselect/dist/js/bootstrap-multiselect.js"></script>

    <script src="<?=base_url()?>public/ma/vendors/bower_components/moment/min/moment.min.js"></script>
    <script src="<?=base_url()?>public/ma/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
    <!-- Placeholder for IE9 -->
    <!--[if IE 9 ]>
    <script src="<?=base_url()?>public/ma/vendors/bower_components/jquery-placeholder/jquery.placeholder.min.js"></script>
    <![endif]-->
    <!--select 多选-->
    <script src="<?=base_url()?>public/ma/js/functions_new.js?v=071725" ></script>
    <script src="<?=base_url()?>public/ma/js/demo.js?v=1"></script>
<?php echo isset($body) ?  $body : '';?>
</section>
<footer id="footer">
    Copyright &copy; 2016 SeaCow
    <br/>
    Thanks <a
        href="http://byrushan.com/projects/ma/1-5-2/jquery/index.html"
              target="_blank" >Material Admin</a>

<!--    <ul class="f-menu">-->
<!--        <li><a href="">Home</a></li>-->
<!--        <li><a href="">Dashboard</a></li>-->
<!--        <li><a href="">Reports</a></li>-->
<!--        <li><a href="">Support</a></li>-->
<!--        <li><a href="">Contact</a></li>-->
<!--    </ul>-->
</footer>

<!-- Page Loader -->
<div class="page-loader">
    <div class="preloader pls-blue">
        <svg class="pl-circular" viewBox="25 25 50 50">
            <circle class="plc-path" cx="50" cy="50" r="20" />
        </svg>

        <p>Please wait...</p>
    </div>
</div>
</body>
</html>