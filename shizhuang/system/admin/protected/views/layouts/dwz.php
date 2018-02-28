<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?=Yii::app()->name;?></title>
        <link href="<?=ASSETS_URL; ?>bootstrap/css/scojs.css" rel="stylesheet" type="text/css" /> 
        <link href="<?=ASSETS_URL; ?>css/dpl-min.css" rel="stylesheet" type="text/css" /> 
        <link href="<?=ASSETS_URL; ?>css/bui-min.css" rel="stylesheet" type="text/css" /> 
        <link href="<?=ASSETS_URL; ?>css/main-min.css" rel="stylesheet" type="text/css" /> 
        <link href="<?=ASSETS_URL; ?>css/toastr.min.css" rel="stylesheet" type="text/css" /> 
        <link href="<?=ASSETS_URL; ?>css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        
    </head>
    <body>

        <div class="header">

            <div class="dl-title">
                <span class="dl-title-text">海牛管理系统</span>
            </div>

            <div class="dl-log">欢迎您，<span class="dl-log-user"><?=Yii::app()->user->name; ?></span>
               
                <a href="<?=$this->createUrl('public/logout'); ?>" onclick="return confirm('你确定要离开系统吗？')" title="离开系统" class="dl-log-quit"><i class="icon-reply"></i></a>
                <a href="#" onclick="ferosClient.mini()" class="dl-log-quit"><i class="icon-minus"></i></a>
                <a href="<?=$this->createUrl('public/logout'); ?>" onclick="return confirm('你确定要退出系统吗？')" title="退出系统" class="dl-log-quit"><i class="icon-remove"></i></a>

            </div>
        </div>
        <div class="content">
            <div class="dl-main-nav">
                <div class="dl-inform"><div class="dl-inform-title">海牛管理系统<s class="dl-inform-icon dl-up"></s></div></div>
                <ul id="J_Nav"  class="nav-list ks-clear">
                 
                    <li class="nav-item dl-selected"><div class="nav-item-inner nav-home">系统管理</div></li>
                    <li class="nav-item"><div class="nav-item-inner nav-inventory">充值管理</div><div class="nav-item-mask"></div></li>
                    <li class="nav-item"><div class="nav-item-inner nav-marketing">运营管理</div><div class="nav-item-mask"></div></li>
                    <?php if($this->mangerInfo['game_id'] == 19 || $this->mangerInfo['game_id'] == 0){ ?>
                   	<!-- <li class="nav-item"><div class="nav-item-inner nav-supplier">时尚管理</div><div class="nav-item-mask"></div></li> -->
                    <?php } ?>
                    <!-- <li class="nav-item"><div class="nav-item-inner nav-order"></div><div class="nav-item-mask"></div></li>
                    <li class="nav-item"><div class="nav-item-inner nav-product-certified"></div><div class="nav-item-mask"></div></li>
                    <li class="nav-item"><div class="nav-item-inner nav-sample"></div><div class="nav-item-mask"></div></li>         -->           
                </ul>
            </div>
            <ul id="J_NavContent" class="dl-tab-conten">

            </ul>
        </div>
        <script type="text/javascript" src="<?=ASSETS_URL; ?>js/jquery-2.1.1.min.js"></script>
        <script type="text/javascript" src="<?=ASSETS_URL; ?>bootstrap/js/sco.js"></script>
        <script type="text/javascript" src="<?=ASSETS_URL; ?>js/bui.js"></script>
        <script type="text/javascript" src="<?=ASSETS_URL; ?>js/toastr.min.js"></script>
        <script type="text/javascript" src="<?=ASSETS_URL; ?>js/config.js"></script>
        
        <script type="text/javascript">
            BUI.use('common/main', function () {
                var config=<?=$this->bui; ?>;
                new PageUtil.MainPage({modulesConfig:config});
            });
          
            //window.setTimeout(showremind, 5000);
            function showremind() {
                $.post("{site 'index/remind'}", function (data) {
                    if (data.status === 1) {
                        window.parent.toastr.info(data.message);
                    }
                    window.setTimeout(showremind, 5000);
                }, "json");
            }
        </script>
    </body>
</html>
