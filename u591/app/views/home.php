<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SeaCow Data</title>

    <!-- Vendor CSS -->
    <link href="<?=base_url()?>public/ma/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
    <link href="<?=base_url()?>public/ma/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">

    <!-- CSS -->
    <link href="<?=base_url()?>public/ma/css/app.min.1.css" rel="stylesheet">
    <link href="<?=base_url()?>public/ma/css/app.min.2.css" rel="stylesheet">
    <style>
        .card-body{
            font-size: 26px;
            font-weight: bold;
        }
        /*.card-body a{color:#fff;}*/
    </style>
</head>
<body>
<div class="container">
    <?php if($is_admin):?>
    <div class="row">
        <p>系统管理</p>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body card-padding bgm-amber c-white">
                    <?php echo anchor('auth/index','用户管理');?>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body card-padding bgm-amber c-white">
                    <?php echo anchor('system/get_menus','菜单管理');?>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body card-padding bgm-amber c-white">
                    <?php echo anchor('staff/index','客服管理');?>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body card-padding bgm-amber c-white">
                    <?php echo anchor('Tongyong/show','页面编辑');?>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body card-padding bgm-amber c-white">
                    <?php echo anchor('Tongyong/sql','页面sql');?>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body card-padding bgm-amber c-white">
                    <?php echo anchor('AutoRunBak/bakmenu','刷新菜单');?>
                </div>
            </div>
        </div>
    </div>
    <?php endif;?>
    <div class="row">
        <p>游戏列表</p>
    </div>
    <div class="row">
        <?php foreach($games as $game):?>
            <div class="col-sm-4">
                <div class="card">
<!--                    <div class="card-body card-padding bgm-teal c-white">-->
                    <div class="card-body card-padding bgm-amber c-white">
                    <?php echo anchor('Home/emptyhtml/?appid=' . $game['appid'],$game['name']);?>
                    </div>
                </div>
            </div>
        <?php endforeach;?>
    </div>
</div>
</body>
</html>