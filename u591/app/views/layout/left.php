<aside id="sidebar" class="sidebar c-overflow">
    <div class="profile-menu">
        <a href="">
            <div class="profile-pic">
                <img src="<?=base_url()?>public/ma/img/profile-pics/1.jpg">
            </div>
            <div class="profile-info">
                您好，<?php echo $this->userData->first_name .  $this->userData->last_name;?>
                <i class="zmdi zmdi-caret-down"></i>
            </div>
        </a>
        <ul class="main-menu">
            <li>
                <?php echo anchor('Auth/edit_user/'.$this->session->userdata('user_id'), '<i class="zmdi zmdi-account"></i> 个人信息')?>
            </li>
            <li>
                <?php echo anchor("Auth/logout",'<i class="zmdi zmdi-time-restore"></i> 退出')?>
            </li>
        </ul>
    </div>

    <ul class="main-menu">
       <li>
           <?php echo anchor('Home/index', '返回首页'); ?>
       </li>
        <?php foreach ($menus as $sub_menus):?>
        <li class="sub-menu <?php echo ($sub_menus['controller']==$_controller ? 'active toggled':'')?>">
            <a href="javascript:;">
                <!--<i class="zmdi zmdi-view-compact"></i>-->
                <i class="zmdi zmdi-caret-right zmdi-hc-fw"></i>
                <?php echo $sub_menus['title']?>
            </a>
            <ul>
            <?php foreach ($sub_menus['menus'] as $sub_menu):?>
                <?php if (isset($sub_menu['display']) && $sub_menu['display']==false) continue;?>
                <li> <?php
                        echo anchor(
                        $sub_menu['controller'].'?appid=' . $this->appid,
                        $sub_menu['title'],
                        ['class'=>$sub_menu['controller']==$_request_method ? 'active':'']
                        );
                    ?>
                </li>
            <?php endforeach;?>
            </ul>
        </li>
        <?php endforeach;?>
    </ul>
</aside>