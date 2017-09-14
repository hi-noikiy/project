<?php
 
return array(
    'active_mailer' => array(
        'host' => 'smtp.exmail.qq.com',
        'port' => 465,
        'secure' => true,
        'username' => 'no-reply@91ns.com',
        'password' => 'csq1655001515',
        'fromname' => '系统管理员',
        'fromaddr' => 'no-reply@91ns.com',
        'subject' => '91NS虚拟社区账号激活',
        'active_key' => 'join91ns',
    ),
    'active_sms' => array(
        'accountId' => 'f730ab72b31e5a8e40307b0d3e9d77ed',
        'token' => '32f3cf8019ad9cf5be6a9bcc40092dd0',
        'appId' => '55deacc28a9d4913bf137adc226f75cd',
    ),
    'skeleton' => array(
        'frameworksDir' => APP_PATH . '/skeleton/frameworks',
        'libraryDir' => APP_PATH . '/skeleton/library'
    ),
    'url' => array(
        'development' => array(
            'staticBaseUri' => '/',
            'baseUri' => '/'
        ),
        'production' => array(
            'staticBaseUri' => 'http://cdn.91ns.com/',
            'baseUri' => '/'
        ),
        'forbiddenwordtxt'=>'web/res/forbiddenword/20160127.txt',//敏感字文件路径
        'tips' => 'web/res/tips/20160106.txt',//tips文件路径
        'mobiletips' => 'web/res/tips/mobile20160106.txt',//tips文件路径
        'robotname' => 'web/res/robot/robotname/20151208.txt',//机器人名字库
		'robotchat' => 'web/res/robot/robotchat/20150806.txt',//机器人聊天库
        'swfUrl' => 'web/swf/simplevideo/201511241400',//下发发给前端swf目录地址
        'jsURL' => 'web/js/20160328/',//下发发给前端js目录地址注意。屁股后面有/
        'cssURL' => 'web/css20160405/',//下发发给前端css目录地址注意。屁股后面有/
        'posterPre' => 'http://image.91ns.com/',//下发发给前端swf目录地址
    ),
    'directory' => array(
        'controllersDir' => APP_PATH . '/apps/controllers',
        'modelsDir' => APP_PATH . '/apps/models',
        'viewsDir' => APP_PATH . '/apps/views',
        'cacheDir' => APP_PATH . '/apps/cache',
        'logsDir' => APP_PATH . '/apps/logs'
    ),
    'storage' => array(
        'localDir' => APP_PATH . '/public',
        //'remoteDir' => 'putianmm-oss'
        'remoteDir' => '91ns-oss'
    ),
    'miscellaneous' => array(
        'qrcode' => APP_PATH . '/miscellaneous/qrcode/phpqrcode.php',
        'oss' => APP_PATH . '/miscellaneous/oss/sdk.class.php',
        'pushservice' => APP_PATH . '/miscellaneous/gtpush/IGt.Push.php',
        'ucpaas' => APP_PATH . '/miscellaneous/ucpaas/lib/Ucpaas.class.php',
        'pingpp' => APP_PATH . '/miscellaneous/pingpp-php/init.php',
        'wxpay' => APP_PATH . '/miscellaneous/WxPayPubHelper/WxPayPubHelper.php',
        'wxpayappstore' => APP_PATH . '/miscellaneous/WxPayPubHelper/WxPayAppstorePubHelper.php',
    ),
    'websiteinfo' => array(
        'authkey' => 'auth',
        'mobileauthkey' => 'mobileauth',
        'smscodekey' => 'smscode',
        'emailcodekey' => 'mailcode',
        'securityuser' => 'securityuser',
        'securityuserverified' => 'securityuserverified',
        's_user_be_active' => 'userhasbeactive',
        'user_unbind_phone_sms' => 'user_unbind_phone_sms_key', //解绑手机，验证码
        'user_unbind_phone_time' => 'user_unbind_phone_sms_time', //解绑手机，时间
        'user_bind_phone_sms' => 'user_bind_phone_sms_key', //绑定手机，验证码
        'user_bind_phone' => 'user_bind_phone_key', //绑定手机，手机号
        'user_bind_phone_time' => 'user_bind_phone_time', //绑定手机，时间
        'user_get_password_key' => 'user_get_password_key', //找回密码，验证码
        'user_get_password_reset' => 'user_get_password_reset', //找回密码，最后一步
        'user_update_answer' => 'user_update_answer', //安全问题，修改
        'user_get_password_time' => 'user_get_password_time', // 找回密码， 验证码时间
        'user_auto_login_username' => 'user_auto_login_username', //自动登录账号。
        'user_auto_login_password' => 'user_auto_login_password', //自动登录密码。
        'user_reg_phone_sms' => 'user_reg_phone_sms_key', //手机注册，验证码
        'user_reg_phone' => 'user_reg_phone_key', //手机注册，手机号
        'user_reg_phone_time' => 'user_reg_phone_time', //手机注册,时间
        'user_give_vip_phone_sms' => 'user_give_vip_phone_sms', //赠送vip，短信发送验证码
        'user_give_vip_phone' => 'user_give_vip_phone', //赠送vip，手机号
        'user_give_vip_phone_time' => 'user_give_vip_phone_time', //赠送vip，时间
        'user_give_car_phone_sms' => 'user_give_car_phone_sms', //赠送座驾，短信发送验证码
        'user_give_car_phone' => 'user_give_vip_phone', //赠送座驾，手机号
        'user_give_car_phone_time' => 'user_give_vip_phone_time', //赠送座驾，时间
        'user_settle_phone_sms' => 'user_settle_phone_sms_key', //提现，验证码
        'user_settle_phone' => 'user_settle_phone_key', //提现，手机号
        'user_settle_phone_time' => 'user_settle_phone_time', //提现,时间
        'user_phone_login_sms' => 'user_phone_login_sms', //手机验证码登录，验证码
        'user_phone_login_phone' => 'user_phone_login_phone', //手机验证码登录，手机号
        'user_phone_login_time' => 'user_phone_login_time', //手机验证码登录,时间
        'user_question_phone_sms' => 'user_question_phone_sms', //手机验证码找回密保问题，验证码
        'user_question_phone' => 'user_question_phone', //手机验证码找回密保问题，手机号
        'user_question_phone_time' => 'user_question_phone_time', //手机验证码找回密保问题,时间
        'unset_question'           => 'unset_question', //重置安全问题
        'user_bank_account' => 'user_bank_account',
        'user_bank_account_time' => 'user_bank_account_time',
        'user_phone_sms_users_phone' => 'user_phone_sms_users_phone', //手机验证码登录多账号手机号
        'user_phone_sms_users_time' => 'user_phone_sms_users_time', //手机验证码登录多账号时间
        'user_third_login_uid' => 'user_third_login_uid', //第三方登录的uid
        'third_login_bind_phone' => 'third_login_bind_phone', //第三方登录绑定手机
        'third_login_bind_phone_sms' => 'third_login_bind_phone_sms', //第三方登录绑定手机验证码
        'third_login_bind_phone_time' => 'third_login_bind_phone_time', //第三方登录绑定手机时间
        'apply_sign_pic' => array(
            'live' => array(
                '1' => 'apply_sign_pic_live_1',
                '2' => 'apply_sign_pic_live_2',
                '3' => 'apply_sign_pic_live_3',
            ),
            'id' => array(
                '1' => 'apply_sign_pic_id_1',
                '2' => 'apply_sign_pic_id_2',
            )
        ), //申请签约
        'familyapplyurlkey' => 'familyapplyurl',
        'useruploadpath' => 'userupload/',
        'gapath' => 'analytics/google/',
        'movementpath' => '/movements/',
        'avatarpath' => '/avatars/',
        'posterpath' => '/posters/',
        'messagepath' => '/messages/',
        'livepicpath' => '/livepic/',
        'familyposterpath' => '/familyposter/',
        'albumpath' => '/albums/',
        'customatatarnum' => 48,
        'invuploadpath' => 'invupload/',//客服后台上传路径
        'accountpath' => '/accounts/',//账单路径
        'ratioconfig' => array(//主播收到推广员收益占比配置键名
            'key' => 'ratioNum',
            'default' => 80
        ),
        'suggestionpath' => 'suggestionpath/',
        'informpath' => 'informpath/',
        'suggestionimgpath' => 'suggestionpath/img/',
        'suggestionlogpath' => 'suggestionpath/log/',
        'dynamicspath' => 'dynamicspath/',
        'familyskinpath' => '/familyskin/',
        'familylogopath' => '/familylogo/',
        'recommendqrcodepath'=>'recommendqrcodepath/',//推广活动二维码 路径
        'anchorposterpath'=>'/anchorposterpath/',//主播海报 路径
        'anchoralbumpath'=>'/anchoralbumpath/',//主播相册 路径
        'chatdatapath'=>'chatdatapath/',//聊天日志
        'goodspath'=>'web/goods/',//一元夺宝商品图片
    ),
    'websitecookies' => array(
        'userName' => 'NSUN',
        'userPassword' => 'NSPW',
        'guestinfo' => 'guestinfo',
        'guestinfotime' => 'guestinfotime',
        'guestchannel' => 'guestchannel',//渠道cookie，记录留存率用的
        'guestchanneltype' => 'guestchanneltype',//渠道子分类cookie，记录留存率用的
        'utm_source' => 'utm_source',//渠道cookie，注册弹窗用的
        'utm_medium' => 'utm_medium',//渠道子分类cookie，注册弹窗用的
        'source_gift' => 'source_gift',//是否领取渠道礼包
        'recommendStr' => 'recommendStr',//推荐人
    ),
    'pushservice' => array(
        'host' => 'http://sdk.open.api.igexin.com/apiex.htm',
        'appId' => 'gr7Jk2GZ9T7M4fSI9RyYm3',
        'appKey' => 'EXWHSzYliu6IKni97tcBH9',
        'masterSecret' => 'DEpLTPLdcD7YbGWZqAxI87',
        'type' => array(
            'pc' => 1,
            'ios' => 2,
            'android' => 3
        ),
    ),
    'pushserviceappstore' => array(
        'host' => 'http://sdk.open.api.igexin.com/apiex.htm',
        'appId' => '6L1WdpmInl6T4nXu51gM67',
        'appKey' => '4PuKfyKM0i7v0cCMJQtsQ1',
        'masterSecret' => 'kp6dNK5ee1ARPvyTJwYGj9',
//        'type' => array(
//            'pc' => 1,
//            'ios' => 2,
//            'android' => 3
//        ),
    ),
    'anchorPwd' => '816888',
    'allocateCash' => '821117',
    'manageCfg' => array(
        'normalUp' => 200,
        'imperialUp' => 1000
    ),
    'pingpp' => array(
//        'key' => 'sk_test_yfrn50eXz9SGTu1aHCXPG4aT',
        'key' => 'sk_live_P1yr1734xhxb3wbdQvlEeDtD',
        'appId' => 'app_X1Ouj58qffr1GePy',
        'appName' => '91女神',
        'public_key_path' => APP_PATH . '/apps/key/rsa_public_key.pem',
    ),
    'lbs' => array(
        'collection' => 'user_coordinates'
    ),
    'geetest' => array(
        'appId' => '729cb8b594322cf0a94591a612eb2ecd',
        'appKey' => 'f0c7a8c0c9c0a9c347a6a78ba5109aa7'
    ),
    'pay' => array(
        'alipay' => array(
            'partner' => '2088811392194721',
            'key' => 'o183ft7pv9i6hr2gw0ie1yl0mp7irbkw',
            'seller' => 'xhb91ns@126.com',
            'seller_id' => '2088811392194721',
            'notify' => '/charging/alipaynotice', //异步通知
            'return' => '/charging/callbackpage/alipay', //同步通知
            'app_return' => '/app/paycallback/alipay', //app内嵌H5支付宝 同步通知
            'cacert' => APP_PATH . '/apps/key/cacert.pem', // ca证书路径地址，用于curl中ssl校验
            // 以下是手机配置
            'input_charset' => strtolower('utf-8'), // 字符编码格式 目前支持 gbk 或 utf-8
            'sign_type' => strtoupper('RSA'), // 签名方式 不需修改
            'transport' => 'http', // 访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
            'private_key_path' => APP_PATH . '/apps/key/rsa_private_key.pem', // 商户的私钥（后缀是.pen）文件相对路径
            'ali_public_key_path' =>  APP_PATH . '/apps/key/alipay_public_key.pem',// 支付宝公钥（后缀是.pen）文件相对路径
            'https_verify_url'  => 'https://mapi.alipay.com/gateway.do?service=notify_verify&',
            'http_verify_url'  => 'http://notify.alipay.com/trade/notify_query.do?',
            'privateKey' => 'MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAL8C2sq0Y/kAz1HQx8P9WKu0gXTFAoyWOskJGSUQQaQuY1TJlWhOVCnuQ8DC8b3oUoxi8FsusmWqgkyR9qiI6JFTmhlW0AXXpCF8wdxQLevvnWqVLYA0d4VWTboUiGkjRncJ95S8pTswbmzm+nQ9gzpb3GEXwiZWbbU2fTMaSpWBAgMBAAECgYAhNtnczalkryNd0rQp/G/iy6yHJYrf72+hbZeVvlHrvrU/FR6J2LUB5ZCagEuQ/kj8aGfrMx+KVi/6KJd/ju8BqOQORdsK5BgIKOJVTz/u87GreQLSSsv7IFshVyvlvkXui7e6Hr9z51Mmx8HWM/va6H6BmX6xHw7Ly+o+jEdhUQJBAPfcWCd6PdOZYYYTyJVi9FmanyW+2nfqwhfL97z62mtISFqN/FKwzX98aOehb5aaV9jzTJS/wuMyTe2+zfwaUrMCQQDFSJnf9m1e0iKD4LTlW1EAS/TMeLoY9fDiLImXUq5joBWrDF+RuchiKa3ZmCSZkJTHK1jNZ1P0oG1sXumZVoD7AkEA0gl26n08R0OC0QuSvoTMa+ILWwEQQO4+68t8dDhfPupR5erMF4abnZzfiOnUldrU9pO2IZcen0jRoNY/15K24QJATspmGhpTJ/GIs17FIGzN9u5fAGKfAGUJJWtgMD1nRIak4MF6Ubr/GqVGt4aZ53Lk2H6pYq+HykVgLk3hwcnYjQJABV85IWGkjY4CLMELRMbngNyj5RmRQ3r8rEGwmhmeNEbJhLZWNnrrDcYuCDqa+D+NARcbBZXpZsK0y4fc4GK8dg==',
        ),
        'tenpay' => array(),
        'netpay' => array(),
        'shenzhoufu' => array(//神州付充值卡支付平台
            'partner' => '142191',//商户id
            'key' => 'csq1655001515',
            'notify' => '/charging/shenzhoufunotice', //异步通知
            'return' => '/charging/callbackpage/shenzhoufu', //同步通知
        ),
        'wxpay' => array(//微信支付平台
            'partner' => '153860',//商户id
            'key' => 'cbafa73ed895272feaf8670705ba013c',
            'notify' => '/charging/wxpaynotice', //异步通知
            'return' => '/charging/callbackpage/wxpay', //同步通知
            'APPID' => 'wx7e0b919ca0e68429',
            'MCHID' => '1251666701',
            'APIKEY' => 'xiaohuoban1111111111111111111111',
            'APPSECRET' => '35910f71ec10928ed2eae5f569a599a3',
            'SSLCERT_PATH' => APP_PATH . '/apps/key/apiclient_cert.pem',
            'SSLKEY_PATH' => APP_PATH . '/apps/key/apiclient_key.pem',
            'CURL_PROXY_HOST' => '0.0.0.0',
            'CURL_PROXY_PORT' => '0',
            'REPORT_LEVENL' => '1',
        ),
    ),
    'banckcode' => array(
        '中国银行' => 'BOCB2C',
        '中国工商银行' => 'ICBCB2C',
        '招商银行' => 'CMB',
        '中国建设银行' => 'CCB',
        '中国农业银行' => 'ABC',
        '中国邮政储蓄银行' => 'POSTGC',
        '上海浦东发展银行' => 'SPDB',
        '兴业银行' => 'CIB',
        '广发银行' => 'GDB',
        '富滇银行' => 'FDB',
        '杭州银行' => 'HZCBB2C',
        '上海银行' => 'SHBANK',
        '宁波银行' => 'NBBANK',
        '平安银行' => 'SPABANK',
        //------------------------
        '交通银行' => 'COMM-DEBIT',
        '中国光大银行' => 'CEB-DEBIT',
        '北京银行' => 'BJBANK',
        '上海农商银行' => 'SHRCB',
        '温州银行' => 'WZCBB2C-DEBIT',
        '交通银行' => 'COMM',
        '中国民生银行' => 'CMBC',
        '北京农村商业银行' => 'BJRCB'
    ),
    'oauth' => array(
        'qq' => array(
            'appId' => '101188323',
            'appKey' => 'cad1d04fc2bbc860ee4ed019575c0bad'
        ),
        'sina' => array(
            'appId' => '3223364004',
            'appKey' => 'a97e88d940ea9bb4f3085de7505e88c8'
        ),
        'douzi' => array(
            'appKey' => 'xiuba@odao^&*',
            'callback' => '/user/loginOfThirdCallbackDouzi'
        ),
    	'weixin' => array(
    		/*'appId' => 'wxb6cf016b84b6e058', //公众平台
    		'appKey' => 'e5290d376c874bafb2353dc5aebcea7f',*/
    		'appId' => 'wx60e587bf9d21d1b9',  //开放平台
    		'appKey' => '3eeb5df89068ecbddf440e5b69a00fb6',
    	),
    	'mweixin' => array(  //h5
    		'appId' => 'wxb6cf016b84b6e058', //公众平台
    		'appKey' => 'e5290d376c874bafb2353dc5aebcea7f',
//      		'appId' => 'wx708064fceb58d8eb',  //开放平台
//      		'appKey' => '8873796405eaaa8478ce42f9578f3435',
    	),
        'callback' => '/user/loginofthirdcallback'
    ),
    'dbTypeConfigName' => array(
        'gift' => array('giftType', 1),
        'car' => array('carType', 2),
        'food' => array('foodType', 3)
    ),
    'dbBaseConfigName' => array(
        'richerLimitLevel' => '1', //富豪等级可以在房间广播的下限
        'chatTime' => '5', //聊天间隔时间
        'exchangeLimit' => '10000.000',
    ),
    'roomBroadcastMask' => array(
        'enterRoom' => 1,
        'leaveRoom' => 2,
        'levelUp' => 4,
        'kickUser' => 8,
        'forbidTalk' => 16,
    ),
    'roomBroadcastThrowMask' => array(
        'enterRoom' => 1,
        'leaveRoom' => 2,
        'levelUp' => 4,
        'kickUser' => 8,
        'forbidTalk' => 16,
        'roomChat' => 32,
    ),
    'callbackMask' => array(
        'enterRoom' => 1,
        'leaveRoom' => 2,
        'levelUp' => 4,
        'kickUser' => 8,
        'forbidTalk' => 16,
        'roomChat' => 32,
    ),
    'consumeType' => array(
        'buyVip' => 1,
        'buyCar' => 2,
        'buyGuard' => 3,
        'grabSeat' => 4,
        'sendGift' => 5,
        'sendRoomBroadcast' => 6,
        'sendAllRoomBroadcast' => 7,
        'sendStar' => 8,//送魅力星
        'giveVip'=>9,//赠送vip
        'giveCar'=>10,//赠送座驾
        'buyShow'=>11,//点播节目
        'coinType' => 1000,
        'sendGiftByCoin' => 1001
    ),
    //flash图片url
    'flashPicUrl' => array(
        'web/flashpics/chong.jpg',
        'web/flashpics/shua.jpg',
        'web/flashpics/zhi.jpg',
        'web/flashpics/zhou.jpg'
    ),
    //周星礼物类型配置
    'weekStarType' => 9,
    //排行榜缓存类型
    'rankLogType' => array(
        'star_day' => 1,
        'star_week' => 2,
        'star_month' => 3,
        'star_total' => 4,
        'rich_day' => 5,
        'rich_week' => 6,
        'rich_month' => 7,
        'rich_total' => 8,
        'fans_day' => 9,
        'fans_week' => 10,
        'fans_month' => 11,
        'fans_total' => 12,
        'gift_star' => 13,
        'visitor_anchor' => 14,
        'consume_family' => 15,
        'fans_anchor' => 16,
        'consume_family_day' => 17, //家族加入列表
        'hot_gift'=>18,//热门礼物
        'family_day' => 19,
        'family_week' => 20,
        'family_month' => 21,
        'family_total' => 22,
        'gift_star_thisWeek' => 23,
        'gift_star_lastWeek' => 24,
        'charm_day' => 25,
        'charm_week' => 26,
        'charm_month' => 27,
        'charm_total' => 28,
        'family_anchor_gift_week' => 29,
        'family_anchor_gift_month' => 30,
        'family_anchor_charm_week' => 31,
        'family_anchor_charm_month' => 32,
    ),
    'buyVipConfig' => array(
        '1' => array(//普通vip
            // 索引 => (天数，聊币)
            1 => array(30, 30000, 1),
            3 => array(90, 90000, 3),
            6 => array(180, 180000, 6),
            12 => array(360, 360000, 12),
        ),
        '2' => array(//至尊vip
            // 索引 => (天数，聊币)
            1 => array(30, 100000, 1),
            3 => array(90, 300000, 3),
            6 => array(180, 600000, 6),
            12 => array(360, 1200000, 12),
        ),
    ),
    'buyGuardConfig' => array(
        '1' => array(//黄金守护配置信息
            // 索引 => (天数，聊币)
            1 => array(30, 66666, 0, 1),
            2 => array(90, 199900, 0, 2),
            3 => array(180, 399900, 0, 3),
            4 => array(360, 799800, 0, 4),
        ),
        '2' => array(
            // 索引 => (天数，聊币)
            1 => array(30, 22222, 0, 1),
            2 => array(90, 66600, 0, 2),
            3 => array(180, 133300, 0, 3),
            4 => array(360, 266500, 0, 4),
        ),
        '3' => array(//铂金守护配置信息
            // 索引 => (天数，聊币,赠送时间)
            1 => array(30, 99999, 0, 1),
            2 => array(90, 299900, 0, 2),
            3 => array(180, 599900, 0, 3),
            4 => array(360, 1199900, 0, 4),
        ),
    ),
    //铂金守护配置信息
    'boGuard' => array(
        // 索引 => (天数，聊币,赠送时间)
        1 => array(30, 99999, 0, 1),
        2 => array(90, 299900, 0, 2),
        3 => array(180, 599900, 0, 3),
        4 => array(360, 1199900, 0, 4),
    ),
    //白银守护配置信息
    'silverGuard' => array(
        // 索引 => (天数，聊币)
        1 => array(30, 22222, 0, 1),
        2 => array(90, 66600, 0, 2),
        3 => array(180, 133300, 0, 3),
        4 => array(360, 266500, 0, 4),
    ),
    //黄金守护配置信息
    'goldGuard' => array(
        // 索引 => (天数，聊币)
        1 => array(30, 66666, 0, 1),
        2 => array(90, 199900, 0, 2),
        3 => array(180, 399900, 0, 3),
        4 => array(360, 799800, 0, 4),
    ),
    'signAnchorStatus' => array(
        'refuse' => 3,
        'forzen' => 2,
        'normal' => 1,
        'apply' => 0,
        'unbind' => 4,//解约
    ),
    'familyStatus' => array(
        'refuse' => 3,
        'normal' => 1,
        'apply' => 0,
    ),
    'familyLogStatus' => array(
        'refuse' => 3,
        'normal' => 1,
        'apply' => 0,
    ),
    //申请类型
    'applyType' => array(
        'family' => 1, //申请家族
        'sign' => 2, //申请签约
        'createFamily' => 3, //申请创建家族
    ),
    //申请状态
    'applyStatus' => array(
        'ing' => 0, //申请中
        'pass' => 1, //申请通过
        'cancel' => 2, //申请取消
        'fail' => 3, //申请失败
        'unbind' => 4, //解约
    ),
    //视频加速
    'radioType' => 1,
    //支付类型
    'payType' => array(
        'alipay' => array(
            'id' => 1,
            'name' => '支付宝'
        ),
        'alipay2' => array(
            'id' => 2,
            'name' => '支付宝网银'
        ),
        'alipay3' => array(
            'id' => 3,
            'name' => '支付宝扫码'
        ),
        'alipay4' => array(
            'id' => 4,
            'name' => '支付宝手机'
        ),
        'shenzhoufu' => array(
            'id' => 5,
            'name' => '手机充值卡'
        ),
        'wxpay' => array(
            'id' => 6,
            'name' => '微信支付'
        ),
         'alipay5' => array(
            'id' => 7,
            'name' => '支付宝手机wap'
        ),
        'mobilewxpay' => array(
            'id' => 8,
            'name' => '微信手机支付'
        ),
        'iosPayInner' => array(
            'id' => 9,
            'name' => 'ios内购'
        ),
        'alipay6' => array(
            'id' => 10,
            'name' => '支付宝手机app'//app内嵌h5支付宝
        ),
        'wxpayappstore' => array(
            'id' => 11,
            'name' => 'appstore微信支付'//appstore微信支付
        ),
        'baofoo' => array(
            'id' => 12,
            'name' => 'app银联支付'//宝付银联支付
        ),
        'innerpay' => array(
            'id' => 1000,
            'name' => '推广支付'
        ),
        'ingot' => array(
            'id' => 1001,
            'name' => '元宝兑换'
        ),
    ),
    //支付状态
    'payStatus' => array(
        'ing' => 0, //未支付
        'success' => 1, //支付成功
    ),
    //聊币来源
    'cashSource' => array(
        'pay' => 1, //充值获得
        'task' => 2, //任务获得
        'activity' => 3, //活动获得
        'invSend'=>4,//客服后台发放
        'ingotExchange'=>5,//元宝兑换
        'lucky'=>6,//幸运礼物获得
        'givePay'=>7,//赠送充值

        'weekStar'=>8,//周星第一主播
        'gitfPackage'=>9,//礼包获得
        'innerpay' => 1000,//推广充值
        'changeCash' => 100,//兑换聊币
        'depositCash' => 101,//提现
        'rewardBox' => 102,//宝箱赠送
        'redPacket' => 10,//红包
    ),
    'weekAward' => array(
        'anchorAward'=>array('minNum' => 20000, 'awardCash' => 99900, 'type'=> 1, 'desc' => '周星活动奖励'),
        'richerAward'=>array('giftPackageId' => 37),
    ),
    //人民币和聊币兑换比例
    'cashScale' => 100,
    //在线礼物
    'onlineGift' => array(
        'interval' => 1, //礼物计算间隔（每这个时间(分钟) 增长一个礼物）
        'limit' => 10, //礼物上限
    ),
    //活动  activityId：活动id，须确保唯一性。其它参数可根据需要添加
    'activities' => array(
        //首充活动1  money:累计充值需要达到的金额 giftTime:礼包领取有效期  activityGift：礼包配置  cash:聊币数量  vipTime:vip有效期 carId:坐骑id carTime:坐骑有效期
       // 'firstPay1' => array('activityId' => 1, 'money' => 10, 'giftTime' => 604800,'giftPackageId'=>12, 'activityGift' => array('cash' => 1000, 'vipTime' => 604800, 'carId' => 21, 'carTime' => 864000)),
        //首充活动2
       // 'firstPay2' => array('activityId' => 2, 'money' => 100, 'giftTime' => 604800,'giftPackageId'=>13, 'activityGift' => array('cash' => 10000, 'vipTime' => 2592000, 'carId' => 19, 'carTime' => 2592000)),
        //首充活动3 
       // 'firstPay3' => array('activityId' => 3,'min'=>10,'max'=>100, 'giftTime' => 604800,'giftPackageId'=>10),
        //首充活动4
        //'firstPay4' => array('activityId' => 4,'min'=>100, 'giftTime' => 604800,'giftPackageId'=>11),
        //累计充值活动1
        'totalPay1' => array('activityId' => 5, 'min' => 10, 'max' => 100, 'giftTime' => 24192000, 'giftPackageId' => 30,'message'=>'累积充值达到10元，获赠迷你青铜礼包：聊豆*50、棒棒糖*10、座驾-拖拉机（7天）。'),
        //累计充值活动2
        'totalPay2' => array('activityId' => 6, 'min' => 100, 'max' => 500, 'giftTime' => 24192000, 'giftPackageId' => 31,'message'=>'累积充值达到100元，获赠超值青铜礼包：聊豆*1000、普通VIP(7天)、心心相印*10、座驾-魔法扫帚（15天）。'),
        //累计充值活动3
        'totalPay3' => array('activityId' => 7, 'min' => 500, 'max' => 1000, 'giftTime' => 24192000, 'giftPackageId' => 32,'message'=>'累积充值达到500元，获赠土豪白银礼包：聊豆*2000、普通VIP（15天）、蛋糕*200、音喇叭卡*1、座驾-呆萌木马（30天）。'),
        //累计充值活动4
        'totalPay4' => array('activityId' => 8, 'min' => 1000, 'max' => 5000, 'giftTime' => 24192000, 'giftPackageId' => 33,'message'=>'累积充值达到1000元，获赠至尊白银礼包：聊豆*3000、至尊VIP（7天）、爱的火山*3、金喇叭卡*1、座驾-保时捷（15天）。'),
        //累计充值活动5
        'totalPay5' => array('activityId' => 9, 'min' => 5000, 'max' => 10000, 'giftTime' => 24192000, 'giftPackageId' => 34,'message'=>'累积充值达到5000元，获赠史诗黄金礼包：聊豆*5000、至尊VIP（15天）、幸福摩天轮*3、银喇叭卡*2、金喇叭卡*1、座驾-劳斯莱斯银魅（30天）、徽章-万贯（永久）。'),
        //累计充值活动6
        'totalPay6' => array('activityId' => 10, 'min' => 10000, 'max' => 100000000, 'giftTime' => 24192000, 'giftPackageId' => 35,'message'=>'累积充值达到10000元，获赠传说黄金礼包：聊豆*8000、至尊VIP（30天）、私人游艇*3、银喇叭卡*2、金喇叭卡*2、座驾-黄金战车（30天）、徽章-富甲（永久）。'),
    ),
    //用户活动状态
    'activityStatus' => array(
        'undone' => 0, //未完成
        'done' => 1, //已完成，未领取
        'received' => 2, //已领取
        'expired' => 3, //已过期
    ),
    //任务类型
    'taskType' => array(
        //新手任务
        'newUser' => 1,
        //日常任务
        'daily' => 2,
        //新手引导
        'guide' => 3,
    ),
    //用户任务状态
    'taskStatus' => array(
        'undone' => 0, //未完成
        'done' => 1, //已完成
        'received' => 2, //已领取
    ),
    //任务配置
    'taskConfig' => array(
        'cashMax' => 100000, //聊币上限
        'roseId' => 4, //玫瑰id
        'sendCash' => 1, //送聊币
        'sendCoin' => 2, //送聊豆
        'sendGift' => 3, //送礼包
        'shareTimes' => 5, //分享次数
        'roseNum' => 999, //送玫瑰数
        'starNum' => 10, //送魅力星数
        'seatNum' => 1, //抢座数
        'seatReward' => array('exp' => 10,'points'=>1),//抢沙发奖励
        'starReward' => array('exp' => 20,'points'=>1),//送魅力星奖励
        'shareNum' => 5, //分享次数
        'shareReward' => array('coin' => 100), //分享奖励
        'shareBackNum' => 20, //分享回访次数
        'watchsTimes' => 60, //累计观看直播请求时间间隔，单位秒
        'watchs' => array(0 => array('times' => 10, 'reward' => array('exp' => 10)),//累计观看直播
            1 => array('times' => 30, 'reward' => array('exp' => 10)),
            2 => array('times' => 90, 'reward' => array('exp' => 20,'points'=>1))),
        'talks' => array(0 => array('times' => 10, 'reward' => array('giftId' => 61,'giftNum' => 8,'giftName' => '幸运桃花','configName'=>'th')),//累计发言
            1 => array('times' => 20,'reward' => array('giftId' => 61,'giftNum' => 12,'giftName' => '幸运桃花','configName'=>'th'))),
        'gifts' => array(0 => array('times' => 10, 'reward' => array('exp' => 10)),//累计送聊币礼物
            1 => array('times' => 50, 'reward' => array('exp' => 50)),
            2 => array('times' => 100, 'reward' => array('exp' => 100))),
        'taskTime'=>1454255999,//截止时间
        'taskUnits'=>array(2007=>'分钟',2008=>'次',2009=>'个',2005=>'次',2004=>'颗',2002=>'次'),//单位配置
       
    ),
    //任务id配置
    'taskIds' => array(
        'editNickName' => 1001, //新手任务--修改昵称
        'sendRose' => 1002, //新手任务--送玫瑰
        'bindPhone' => 1003, //新手任务--绑定手机
        'editAvtar' => 1004, //新手任务--设置头像
        'setQuestion' => 1005, //新手任务--设置安全问题
        'watch' => 1006, //新手任务--连续观看
        'firstPay' => 1007, //新手任务--首充任务
        'userGuide' => 1008, //新手任务--新手引导
        'online' => 2001, //日常任务--在线领奖
        'share' => 2002, //日常任务--分享有礼
        'rose' => 2003, //日常任务--送红玫瑰
        'charm' => 2004, //日常任务--魅力
        'seat' => 2005, //日常任务--抢座
        'shareBack' => 2006, //日常任务--分享回访
        'vipReward1' => 999, //日常任务--普通vip礼物
        'vipReward2' => 998, //日常任务--至尊vip礼物
        'sourceLoginReward' => 997, //新手任务--渠道登录领取礼包
        'login' => 3001, //新手引导--登录
        'nickName' => 3002, //新手引导--修改昵称
        'talk' => 3003, //新手引导--与主播聊天
        'follow' => 3004, //新手引导--关注主播
        'gift' => 3005, //新手引导--送礼
        'pay' => 3006, //新手引导--充值
        'totalWatch' => 2007, //日常任务--累计观看
        'totalTalk' => 2008, //日常任务--累计发言
        'totalGift' => 2009, //日常任务--累计送聊币礼物
    ),
   
    //第三方分享类型
    'shareType' => array(
        'tsina' => 1,
        'qzone' => 2,
        'weixin' => 3,
        'tqq' => 4,
        'moments' => 5,//微信朋友圈
    ),
    //用户照片类型
    'photoType' => array(
        'lifePhoto' => 1, //生活照
        'idPhoto' => 2, //证件照
    ),
    //当前网站所在的渠道版本配置
    'webType' => array(
        '1' => array(
            'channelType' => 1,
            'domain' => 'http://www.91ns.com',
            'mDomain' => 'http://m.91ns.com',
            'name' => '91NS',
            'cnzzID' => '1256651695',
            'logoURL' => 'web/cssimg/91ns/logo.png',
            'roomLogoURL' => 'web/cssimg/91ns/logo.png',
            'roomLoadingURL' => 'web/cssimg/91ns/loadingLogo.png',
            'paySubject' => '91ns充值',
            'smsType' => array(
                'bindPhone' => 4719,
                'unbindPhone' => 4720,
                'register' => 3039,
                'getPassword' => 3103,
                'accountNotice' => 5578,//客服后台结算申请短信通知
                'settleCode' => 9478,//提现和兑换聊币短信验证
             ),
        ),
        '2' => array(
            'channelType' => 2,
            'domain' => 'http://v.douzi.com',
            'mDomain' => 'http://m.91ns.com',
            'name' => '秀吧',
            'cnzzID' => '1256850361',
            'logoURL' => 'web/cssimg/91ns/douzilogo.png',
            'roomLogoURL' => 'web/cssimg/91ns/douzilogo.png',
            'roomLoadingURL' => 'web/cssimg/91ns/douzilogo_2.png',
            'paySubject' => '秀吧充值',
            'smsType' => array(
                'bindPhone' => 6966,
                'unbindPhone' => 6965,
                'register' => 6967,
                'getPassword' => 6968,
                'accountNotice' => 6964,//客服后台结算申请短信通知
             ),
        ),
    ),
    //验证码类型 geeTest/securimage
    'captchaType' => 'securimage',
    //客服配置
    'GMConfig' => array(
        'QQNumber' => array('1929586997', '3252147620','3202470752'),
    ),
    //客服后台--用户状态
    'userStatus' => array(
        'normal' => '1', //正常用户
        'black' => '2', //禁用用户
    ),
    //客服后台--规则配置类型
    'ruleType' => array(
        'anchorBonus' => '1', //主播分成规则
        'familyBonus' => '2', //家族分成规则
        'liveRoomRobotIn' => '3', //直播间加机器人规则
        'liveRoomRobotOut' => '4', //直播间机器人退出规则
    ),
    //客服后台--例外主播类型
    'exceptionType' => array(
        'bonus' => '1', //分成规则例外
        'exchange' => '2', //兑换限制例外
        'robotSkip' => '3', // 直播间自动跳转
    ),
    //客服后台--规则符号
    'ruleSymbol' => array(
        '1' => '=',
        '2' => '>',
        '3' => '>=',
        '4' => '<',
        '5' => '<=',
    ),
	
	//底薪方式
	'salaryType' => array(
		'keepLow' => '1' ,  //保低薪资
		'fixation' => '2',   //固定薪资
	),
    //直播间页面的log等级
    'loggerLevel' => 3,

    //用户内部类型
    'userInternalType' => array(
        'normal' => 0,  //普通用户
        'tuo' => 1,     //推广员用户
        'realTuo' => 2, //托
    ),

    //自动跳转优先级
    'jumpRatio' => array(
        '0' => array('高',10), // 高
        '1' => array('较高',6), // 较高
        '2' => array('中',3), // 中
        '3' => array('低',1), // 低
    ),

    //收益类型
    'moneyType' => array(
        '1' => array('type'=>'1','desc'=>'主播礼物分成'),
        '2' => array('type'=>'2','desc'=>'家族主播分成'), 
        '3' => array('type'=>'3','desc'=>'主播礼物分成结算'), 
        '4' => array('type'=>'4','desc'=>'家族主播分成结算'),
        '5' => array('type'=>'5','desc'=>'活动收入'), 
        '6' => array('type'=>'6','desc'=>'活动收入结算'),
        '7' => array('type'=>'7','desc'=>'游戏提成收入'),
        '8' => array('type'=>'8','desc'=>'游戏提成收入结算'),
    ),

    //收益分成配置信息
    'incomeRatios' => array(
        'platRatio' => 100,
        'divideRatio' => 20
    ),

    //交易记录类型
    'changeType' => array(
        '1' => array('type'=>'1','desc'=>'发放','status'=>array('0'=>'无')),
        '2' => array('type'=>'2','desc'=>'提现','status'=>array('0'=>'预扣费','1'=>'打款成功')),
        '3' => array('type'=>'3','desc'=>'兑换聊币','status'=>array('0'=>'预扣费','1'=>'打款成功'))
    ),
    
    //通知类型
    'informationType' => array(
        'system' => 1,//系统消息
        'official'=>2,//官方通知，由后台发送的消息通知
     ),
    //客服后台--session名称
    'userSession' => array(
        'invUid' => 'uid', //用户id
        'invUsername' => 'username', //用户名
        'invRoleModule' => 'roleModuleIds', //用户有权限的动作id
        'invShowModuleList' => 'showModuleList', //用户模块列表
    ),
    
    //所在地
    /*'location' => array(
        '059' => array(
            'name' => '福建省',
            'city'=> array('0594','0592','0593','0591','0595','0596','0597','0598','0599'),
        ),
    ),*/
    'location' => array(
        '059' => array('name' => '福建', 'id' => '059'),
    ),
    //签约默认省
    'provinceDefault'=> '059',
    //签约默认市
    'cityDefault'=> '0591',
    //市
    'signAnchorCityDefault'=> '059',
    'city' => array(
        '0000' => array('name' => '未知', 'id' => '0000'),
        '0591' => array('name' => '福州', 'id' => '0591'),
        '0592' => array('name' => '厦门', 'id' => '0592'),
        '0593' => array('name' => '宁德', 'id' => '0593'),
        '0594' => array('name' => '莆田', 'id' => '0594'),
        '0595' => array('name' => '泉州', 'id' => '0595'),
        '0596' => array('name' => '漳州', 'id' => '0596'),
        '0597' => array('name' => '龙岩', 'id' => '0597'),
        '0598' => array('name' => '三明', 'id' => '0598'),
        '0599' => array('name' => '南平', 'id' => '0599'),
    ),
    
    //星座
    'constellation'=>array(
        '1'=>'白羊座',
        '2'=>'金牛座',
        '3'=>'双子座',
        '4'=>'巨蟹座',
        '5'=>'狮子座',
        '6'=>'处女座',
        '7'=>'天秤座',
        '8'=>'天蝎座',
        '9'=>'射手座',
        '10'=>'摩羯座',
        '11'=>'水瓶座',
        '12'=>'双鱼座',
     ),
    
    //签到配置
    'signConfig'=>array(
        'dayCoin'=>10,//每日签到可获得聊豆数
        'giftNum'=>3,//桃花数量
        'giftId'=>61,//桃花id
        'configName'=>'th',//桃花
    ),
    
 
 
    //签到类型
    'signType' => array(
        'accumulate1' => 1, //累计签到7天
        'accumulate2' => 2, //累计签到17天
        'accumulate3' => 3, //累计签到27天
        'continue1' => 4, //连续签到2天
        'continue2' => 5, //连续签到3天
        'continue3' => 6, //连续签到4天
        'continue4' => 7, //连续签到5天
        'continue5' => 8, //连续签到6天
        'continue6' => 9, //连续签到28天
    ),
    
    //物品表分类
    'itemConfigType' => array(
        'horn' => 1, //喇叭
        'badge' => 2, //徽章
        'show' => 3, //节目卡
    ),
    
    //消息内容
    'informationCode' => array(
        'guard' => 1, //守护
        'passJoinFamily' => 2, //申请加入家族通过
        'passCreateFamily' => 3, //申请创建家族通过
        'rich' => 4, //成为富豪
        'outFamily' => 5, //退出家族
        'failJoinFamily' => 6, //申请加入家族失败
        'passAnchorSign' => 7, //申请主播签约成功
        'failAnchorSign' => 8, //申请主播签约失败
        'failCreateFamily' => 9, //申请创建家族失败
        'carHasExpired' => 10, //座驾过期
        'carAboutToExpire' => 11, //座驾即将过期
        'firstCharge' => 12, //首充活动
        'histManagement' => 13, //我自己的房管 删除
        'management' => 14, //我担任的房管 删除
        'unbindAnchorSign' => 15, //解约主播
        'vipHasExpired' => 16, //vip过期
        'vipAboutToExpire' => 17, //vip即将过期
        'guardHasExpired' => 18, //守护过期
        'guardAboutToExpire' => 19, //守护即将过期
        'totalPay' => 20, //累计充值活动
        'giveVip' => 21, //赠送vip
        'giveCar' => 22, //赠送座驾
        'givePay' => 23, //赠送充值
        'zhinv' => 24, //送织女徽章
        'niulang' => 25, //送牛郎徽章
        'applyFamily' => 26, //申请家族反馈
        'applySignAnchor' => 27, //申请签约主播
        'applyCreateFamily' => 28, //申请创建家族
        'applyJoinFamily' => 29, //申请加入家族
        'addManagement' => 30, //主播添加管理
        'badgeHasExpired' => 31, //徽章过期
        'familyHeaderDelAnchor' => 32, //删除家族下主播
        'anchorPosterSuccess' => 33, //主播封面审核通过
        'anchorPosterFail' => 34, //主播封面审核不通过
        'redPacketReturn' => 35, //红包退还
        'monthRank'=>36,//月榜大作战
        'richerHorn'=>37,//富豪等级升级送金喇叭
    ),
    //消息操作类型
    'informationOperType' => array(
        'check' => array('id' => 1, 'name' => "查看"),
        'audit' => array('id' => 2, 'name' => "去审核"),
        'charge' => array('id' => 3, 'name' => "去续费"),
     ),
    'itemType' => array(
        'vip' => 1,
        'car' => 2,
        'gift' => 3,
        'item' => 4,
    ),
    //直播间超级管理员操作类型
    'roomAdminOperType' => array(
        'forbitTalk' => 1, //禁言
        'cacanlForbitTalk' => 2, //取消禁言
        'kickUser' => 3, //踢人
        'roomLevelUp' => 4, //设置房管
        'roomLevelDown' => 5, //卸房管
    ),
   
    //渠道礼包对应的动作
    'giftPackageAction'=>array(
        'login'=>1,//登录
    ),
    
    'roomLiveStatus'=>array(
        'notexist'=>-1,
        'stop'=>0,
        'start'=>1,
        'forbid'=>2,
        'pause'=>3,
    ),
    'roomUserType'=>array(
        'guest'=>0,
        'user'=>1,
        'hoster'=>2,
    ),
    
    //房间类型
    'roomType'=>array(
        'family' => 1,
        'puxianxi'=>1000,//莆仙戏
    ),
    
    //订单类型
    'orderType' => array(
        'common' => 1, //普通订单
        'guide' => 2, //新手引导
    ),
    
    //渠道类型
    'mt_source' => array(
        'qipaimi' => 'qipaimi', //棋牌迷
    ),
    //赠送类型
    'giveType'=>array(
        'vip'=>1,//vip
        'car'=>2,//座驾
     ),
     //用户类型
    'userType' => array(
        'default' => 0, //web普通注册用户
        'qqdenglu' => 1, //qq登录
        'sinaweibo' => 2, //新浪微博登录
        'douzi' => 3, //豆子登录
        'telephone' => 4, //手机验证码登录
    	'weixin' => 5, //微信登录
    ),
    
    //bmob短信通道配置
    'bmob_sms' => array(
        'id' => 'dbbe69b492b1d71e500aa421d4050cb9',
        'key' => 'dc9ca841d0a218953d84b9b4f90765e0'
    ),
    //短信发送类型id
    'sms_template' => array(
        'bindPhone' => 1,
        'unbindPhone' => 2,
        'register' => 3,
        'getPassword' => 4,
        'accountNotice' => 5, //客服后台结算申请短信通知
        'settleCode' => 6, //提现和兑换聊币短信验证
        'giveCode' => 7, //赠送系统
        'smsLogin'=>8,//手机验证码登录短信验证
        'setQuestion'=>9,//手机找回密保问题短信验证
        'updateAccount' => 10,
     ),
    
//    //软件下载地址
//    'downloadUrl' => array(
//        'android' => 'http://cdn.91ns.com/download/android/NSLive_V1.0.8.apk',
//        'ios' => 'itms-services://?action=download-manifest&url=https://cdnhttps-91ns-com.alikunlun.com/91ns_ios_v1.1.7.plist',
//        'pcios' => 'http://cdn.91ns.com/download/ios/91NSv1.1.7.ipa'
//     ),
    //活动推荐配置
    'recommendConfig'=>array(
        'endTime'=>'2016-01-31 23:59:59',//活动时间
        'validity'=>2592000,//充值返还有效期
        'proportion'=>0.05,//返现比例：5%
    ),
//    'appUpdateContent' => array(
//        'android' => 'web/res/appUpdate/android/20150810.txt',
//        'ios' => 'web/res/appUpdate/ios/20150810.txt',
//    ),
    'robotConfig' => array(
        'robotChatDelay' => '10000',//机器人聊天间隔
        'robotIncrementDelay' => '15000'//机器人增长间隔
    ),
    // 推荐主播个数
    'isRecommend' => 9,
    // 家族房间主播位置
    'familyPos' => array(
        0,1,2,3
    ),
    'appConfig' => array(
        'FetchIPTimeout' => 10,
        'PlayMediaTimeout' => 10,
        'goldHorn' => 500,
        'silverHorn' => 200,
        'freeBean' => 25,
    ),
    //领取聊豆的时间
    'getCoinTime'=>300,//5分钟
    
    //绑定\解绑手机发送短信 每日上限
    'bindPhoneSmsLimit'=>6,//6条

    // 兑换聊币短信验证
    'exchangeCashSmsLimit' => 10,
    // 赠送购买限制
    'purchasingForOtherLimit' => 10,
    // 更新银行账号手机验证
    'updateAccountSmsLimit' => 10,
    //每日注册上限
    'regDayLimit'=>500,
    
    //推荐礼包id
    'recommendGiftId'=>38,
    //手机号注册礼包id
    'phoneRegGiftId'=>52,
    
    
    //活动收入类型
    'activityIncomeType' => array(
        1 => '周星活动奖励',
        2 => '推荐有奖活动奖励',
        3 => '邀请用户活动奖励',
        4 => '中秋活动奖励',
        5 => '月榜大作战',
    ),

    //活动收入类型
    'gameIncomeType' => array(
        1 => '骰宝',
        2 => '牛牛',
    ),

    /**
     * 礼物配置
     */
    'giftConfig' => array(
        array('count'=>9999,'image'=>'liwu_tianchangdijiu','image_p'=>'liwu_tianchangdijiu_p','configName'=>'皇冠'),
        array('count'=>3344,'image'=>'liwu_shengshengshishi','image_p'=>'liwu_shengshengshishi_p','configName'=>'生生世世'),
        array('count'=>1314,'image'=>'liwu_yishengyishi','image_p'=>'liwu_yishengyishi_p','configName'=>'一生一世'),
        array('count'=>520,'image'=>'liwu_woaini','image_p'=>'liwu_woaini_p','configName'=>'我爱你'),
        array('count'=>188,'image'=>'luwu_yaobaobao','image_p'=>'luwu_yaobaobao_p','configName'=>'要抱抱'),
        array('count'=>99,'image'=>'liwu_yongjiu','image_p'=>'liwu_yongjiu_p','configName'=>'永久'),
        array('count'=>50,'image'=>'luwu_jiayou','image_p'=>'luwu_jiayou_p','configName'=>'加油'),
    ),
    
    //手机绑定账号数量限制
    'bindPhoneLimit'=>5,

    //app自动登录token有效期
    'mobileTokenTime'=>604800,//7天
    
    //app是否需要强制登录
    "isNeedLogin" => 0,
    //appstore版本是否需要内购
    'insidePurchases' => 1,
    //app session唤起时间间隔
    'wakeupTokenTime' => 3600,
    //短信验证码有效期
    'smsExpireTime'=>180,//3分钟
    
    //短信发送平台
    'sendSmsPlatform'=>2,//1:bomb平台，2：云通讯平台

    //app动态更新下字段
    'dyUpdateFileUrl' => 'http://cdn.91ns.com/appdata/update/ios/20/',
    //'dyUpdateFileUrl' => '',
    'dyUpdateVersion' => 20,

    // 爵位最低等级
    'minNobility' => 10,
    //app商城banner
    'appShopBanner' => array(
        'vipBanner' => 'banner/shangcheng_hengtiao1.png',
        'carBanner' => 'banner/shangcheng_hengtiao2.png'
    ),
    //文字颜色配置
    'wordAndColor' => array(
        'vip' => array(
            'buy' => array(
                '2' => array('word'=>'购买','normal'=>'#F67D1E','highlighted'=>'#f37401'),
                '1' => array('word'=>'购买','normal'=>'#6960BF','highlighted'=>'#524b9b'),
            ),
            'renew' => array(
                '2' => array('word'=>'续费','normal'=>'#F67D1E','highlighted'=>'#f37401'),
                '1' => array('word'=>'续费','normal'=>'#6960BF','highlighted'=>'#524b9b'),
            ),
        ),
        'guard' => array(
            'buy' => array(
                '3' => array('word'=>'购买','normal'=>'#68C4E6','highlighted'=>'#4CA5C6'),
                '1' => array('word'=>'购买','normal'=>'#F67D1E','highlighted'=>'#f37401'),
                '2' => array('word'=>'购买','normal'=>'#6960BF','highlighted'=>'#524b9b'),
            ),
            'renew' => array(
                '3' => array('word'=>'续费','normal'=>'#68C4E6','highlighted'=>'#4CA5C6'),
                '1' => array('word'=>'续费','normal'=>'#F67D1E','highlighted'=>'#f37401'),
                '2' => array('word'=>'续费','normal'=>'#6960BF','highlighted'=>'#524b9b'),
            ),
        ),
    ),
    // 说话间隔和字数
    'sayInterval' => array(
        'richer' => array(
            '0' => array('seconds'=>'3','words'=>'10','richerLevel'=>'0'),
            '1' => array('seconds'=>'3','words'=>'10','richerLevel'=>'1'),
            '2' => array('seconds'=>'3','words'=>'10','richerLevel'=>'2'),
            '3' => array('seconds'=>'3','words'=>'20','richerLevel'=>'3'),
            '4' => array('seconds'=>'3','words'=>'20','richerLevel'=>'4'),
            '5' => array('seconds'=>'3','words'=>'20','richerLevel'=>'5'),
            '6' => array('seconds'=>'3','words'=>'40','richerLevel'=>'6'),
            '7' => array('seconds'=>'3','words'=>'40','richerLevel'=>'7'),
            '8' => array('seconds'=>'2','words'=>'60','richerLevel'=>'8'),
            '9' => array('seconds'=>'2','words'=>'60','richerLevel'=>'9'),
            '10' => array('seconds'=>'2','words'=>'60','richerLevel'=>'10'),
            '11' => array('seconds'=>'1','words'=>'80','richerLevel'=>'11'),
            '12' => array('seconds'=>'1','words'=>'80','richerLevel'=>'12'),
            '13' => array('seconds'=>'1','words'=>'80','richerLevel'=>'13'),
            '14' => array('seconds'=>'0.5','words'=>'100','richerLevel'=>'14'),
            '15' => array('seconds'=>'0.5','words'=>'100','richerLevel'=>'15'),
            '16' => array('seconds'=>'0.5','words'=>'100','richerLevel'=>'16'),
            '17' => array('seconds'=>'0.5','words'=>'100','richerLevel'=>'17'),
            '18' => array('seconds'=>'0.5','words'=>'100','richerLevel'=>'18'),
            '19' => array('seconds'=>'0.5','words'=>'100','richerLevel'=>'19'),
            '20' => array('seconds'=>'0.5','words'=>'100','richerLevel'=>'20'),
            '21' => array('seconds'=>'0.5','words'=>'100','richerLevel'=>'21'),
            '22' => array('seconds'=>'0.5','words'=>'100','richerLevel'=>'22'),
            '23' => array('seconds'=>'0.5','words'=>'100','richerLevel'=>'23'),
            '24' => array('seconds'=>'0.5','words'=>'100','richerLevel'=>'24'),
            '25' => array('seconds'=>'0.5','words'=>'100','richerLevel'=>'25'),
            '26' => array('seconds'=>'0.5','words'=>'100','richerLevel'=>'26'),
            '27' => array('seconds'=>'0.5','words'=>'100','richerLevel'=>'27'),
            '28' => array('seconds'=>'0.5','words'=>'100','richerLevel'=>'28'),
            '29' => array('seconds'=>'0.5','words'=>'100','richerLevel'=>'29'),
            '30' => array('seconds'=>'0.5','words'=>'100','richerLevel'=>'30'),
        ),
        'vip' => array(
            '0' => array('seconds'=>'2','words'=>'60','vipLevel'=>'1'),
            '1' => array('seconds'=>'1','words'=>'80','vipLevel'=>'2'),
        ),
        'guard' => array(
            '0' => array('seconds'=>'2','words'=>'60','guardLevel'=>'1'),
            '1' => array('seconds'=>'1','words'=>'80','guardLevel'=>'2'),
            '2' => array('seconds'=>'0.5','words'=>'100','guardLevel'=>'3'),
        ),
    ),
    //是否开启其他支付
    'isOpenOther' => 1,
    // 录像最大录制数
    'maxVideoNum' => 6,
    //购买天数
    'buyConfigs' => array(
        1 => array('num'=>1,'days'=>30),
        3 => array('num'=>3,'days'=>90),
        6 => array('num'=>6,'days'=>180),
        12 => array('num'=>12,'days'=>360),
    ),
    //录像保存期内不可删除
    'RECSavePeriod' => 7 * 86400,
    //录像路径和格式
    'RECInfo' => array(
        'url' => 'http://st.91ns.com/record/',
        'format' => '.flv',
        'chatUrl' => 'http://cdn.91ns.com/chatdatapath/',
    ),
    //服务器默认地址
    'hostIp' => 'http://app.91ns.com/',
    //ftp配置
    'ftpConfigs' => array('host'=>'newupload.dnion.com','username'=>'st91ns','password'=>'st91ns@31xQ6cc3'),
    //点赞大于个数分页
    'thumbMinNum' => 28,
    //appstore内购产品标识
    'appstoreProSign' => array(
        '91ns.iap.840Coins',
        '91ns.iap.2100Coins',
        '91ns.iap.3500Coins',
        '91ns.iap.7560Coins',
        '91ns.iap.27160Coins',
        '91ns.iap.36260Coins',
    ),
    
    //积分来源
    'pointsType' => array(
        'sendGift' => 1, //送桃花
        'task' => 2, //任务
        'reg' => 3, //注册赠送
    ),
    
    //召集次数限制
    'conveneLimit'=>3,
    'conveneTimeLimt'=>1800,//间隔时间 半小时
    
    //app注册赠送积分
    'regSendPoints'=>1000,//赠送1000积分
    
    //富豪等级相关配置
    'richerConfigs' => array(
        'carExpireTime' => 1464710399, //富豪座驾过期时间:2016/05/31 23:59:59
        'privateChatLevelLimit'=>10,//私聊 等级下限
        'privateChatLevelName'=>'男爵',//私聊 等级下限名称
        'expressionLevelLimit'=>10,//富豪专属表情 等级下限
        'expressionLevelName'=>'男爵',//富豪专属表情 等级下限名称
        //禁言每日次数限制
        'forbidLimit' => array(12 => 3, //大于等于伯爵 每日可禁言3次
            21 => 5, //大于等于太皇 每日可禁言5次
            27 => 10, //大于等于帝皇 每日可禁言10次
        ),
        'forbidLevelLimit'=>12, //禁言 等级下限
        'forbidLevelInterval'=>3, //禁言 等级间隔
        //踢人每日次数限制
        'kickLimit' => array(14 => 3, //大于等于公爵 每日可踢人3次
            21 => 5, //大于等于太皇 每日可踢人5次
            30 => 10, //大于等于教皇 每日踢人10次
        ),
        'kickLevelLimit'=>14, //踢人 等级下限
        'kickLevelInterval'=>3, //踢人 等级间隔
    ),
    
    

    //节目价格
    'showConfigs' => array(
        'showPrice' => array(
            '500','1000','2000','3000','5000',
        ),
        'optionShow' => '5000',
        'showCardId' => array(
            '500' => 17,
            '1000' => 18,
            '2000' => 19,
            '3000' => 20,
            '5000' => 21,
        ),
    ),

    //计入收益的消费类型
    'consumeTypeAnchor' => '3,4,5,11',

    //主播或者用户升级系统通知
    'minLevelUpBroad' => array(
        'richerMinLevel' => 8,
        'anchorMinLevel' => 9,
    ),
    'robotVersion' => '0.0.2',
    'robotMinUid' => 51000,
    'robotMaxUid' => 54999,
    
    //世界广播类型
    'worldBroadcastType' => array(
        'springFestival' => 6, //春节活动
        'diceGameGetCash' => 7, //骰宝游戏获得大奖励
    ),
    
    //是否开启appsstore内购
    'isOpenInsidePurchases'=>0,
    //游戏抽成比例
    'gameDeductConfig' => array(
        'dice' => array(
            'platform' => 0.5,
            'anchor' => 0.45,
            'family' => 0.05,
        ),
        'niuniu' => array(
            'platform' => 0.5,
            'anchor' => 0.5
        ),
    ),
);
