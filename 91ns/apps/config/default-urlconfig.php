<?php

return array(
    'urlConfig' => array(
        'other_dir' => '' . 'web/cssimg/icon/', // 其他资源

        'shop' => 'shop',//商城页面
        'guard' => 'shop/guard',//开通守护页面
        'vip' => 'shop/vip',//购买VIP
        'car' => 'shop/car',//购买座驾
        'signing' => 'transition/applysign', // 签约主播页面
        'rank' => 'rank',//排行榜
        'index' => '',//首页
        'charging' => 'charging',//充值中心
        'download' => 'download',//下载中心
        'help' => 'help/helpanchor',//帮助中心
        'myProp' => 'personal/props',//我的道具
        'group' => 'personal/imanchor?secondpage=myfamil',//我的家族
        'myAttention' => 'personal/concern',//我的关注
        'myFans' => 'personal/myfans',//我的粉丝
        'myAppliy' => 'message',//我的消息
        'accountSecurity' => 'personal/savecenter',//账户安全
        'modifyPassword' => 'personal/savecenter',//修改密码
        'forgetPassword' => 'forgetpwd?ul=1',//找回密码
        'agreement' => 'agreement/reggreement',//用户许可协议
        'bindPhone' => 'personal/savecenter',//绑定手机
        'activityShare' => 'activities/share',//分享规则
        'defaultAvatar' => 'public/userupload/default/avatars/0.jpg',//默认头像地址
        'midAutumn' => 'activities/midautumn',//中秋活动
		'activityBox' => 'activities/box',//宝箱页面
		'setCover' => 'personal/imanchor?secondpage=myliveroom',//设置封面页面
		'space'	=> 'home',//主播个人空间页面
		'setRecordVedio'=> 'personal/imanchor?secondpage=myliveroom&type=video',//设置离线视频
		'betting'=> 'crowdfunded',//积分夺宝活动
		'bettingDetail'=> 'crowdfunded/details',//积分夺宝物品详细
		'roomBetStr'=> 'activities/onedollar',//一元嗨活动页
		'springFestivalStr'=> 'activities/monkey',//春节活动页

        //Flash主播页面的配置信息
        'videoplay' =>  'rtmp://fall.putianmm.com/xhblive',//Video Play Url
        'videopublish' =>  'rtmp://rise.putianmm.com/xhblive',//Video Publish Url
        'mobileplay' =>  'http://flv.putianmm.com/xhblive',//Video Play Url
        //'videoplay' =>  'rtmp://download.91ns.com/xhblive',//Video Play Url
        //'videopublish' =>  'rtmp://upload.91ns.com/xhblive',//Video Publish Url
        'flashFileName' => '201603291300',                  //201603021300
        'item_act_pre' => 'web/flash/item/201603021300/' , // 道具动画图标前缀(如座驾)
        'gift_dir' => 'web/flash/gift/201601272200/', // gift swf动画
        'icon_image_pre' => 'web/cssimg/icon/icon_20160405/', // 资源大图
        'icon_css_pre' => 'web/css/icon/icon_20160405/', // 资源css
        'appImgUrl' => 'web/appimg/default',
    ),
);
