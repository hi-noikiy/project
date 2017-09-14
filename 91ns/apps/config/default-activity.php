<?php

return array(
    //七夕活动的配置
    'qixi' => array(
        'giftId' => 47, //莲花灯
        'carId' => 42, //比翼鸟
        'uids' => array(), //参与七夕活动的主播id
        'endTime' => 1440000000, //结束时间：2015-08-20 00:00:00
        'zhinv' => 10, //七夕织女徽章
        'niulang' => 11, //七夕牛郎徽章
        'limit' => 2600, //送莲花灯的下限
    ),
    //中秋活动的配置
    'midAutumn' => array(
        'moonValue' => array(48 => 1, 49 => 1000), //月光值
        'reward' => array(//每轮博饼会的奖励
            //状元
            'chajinhua' => array('resultCode' => 100, 'showCode' => 100, 'name' => '状元插金花', 'giftId' => 50, 'giftType' => 3),
            'liubeihong' => array('resultCode' => 90, 'showCode' => 90, 'name' => '六杯红', 'giftId' => 50, 'giftType' => 3),
            'biandijin' => array('resultCode' => 80, 'showCode' => 80, 'name' => '遍地锦', 'giftId' => 50, 'giftType' => 3),
            'wuhong' => array('resultCode' => 70, 'showCode' => 70, 'name' => '五红', 'giftId' => 50, 'giftType' => 3),
            'wuzidengkexiu' => array('resultCode' => 60, 'showCode' => 61, 'name' => '五子登科带一秀', 'giftId' => 50, 'giftType' => 3),
            'wuzidengke' => array('resultCode' => 60, 'showCode' => 60, 'name' => '五子登科', 'giftId' => 50, 'giftType' => 3),
            'sidianhong' => array('resultCode' => 50, 'showCode' => 50, 'name' => '四点红', 'giftId' => 50, 'giftType' => 3),
            //普通中奖
            'duitang' => array('resultCode' => 5, 'showCode' => 5, 'name' => '对堂', 'giftId' => 51, 'giftType' => 3), //对堂      对堂饼+2天普通VIP体验
            'tanhua' => array('resultCode' => 4, 'showCode' => 4, 'name' => '探花', 'giftId' => 52, 'giftType' => 3), //三红       探花饼+1天普通VIP体验
            'jinshi' => array('resultCode' => 3, 'showCode' => 3, 'name' => '进士', 'giftId' => 53, 'giftType' => 3), //四进       进士饼
            'jinshi_juren' => array('resultCode' => 32, 'showCode' => 32, 'name' => '四进带二举', 'giftType' => 3), //四进+举人       进士饼+举人饼
            'jinshi_xiucai' => array('resultCode' => 31, 'showCode' => 31, 'name' => '四进带一秀', 'giftType' => 3), //四进+秀才       进士饼+秀才饼
            'juren' => array('resultCode' => 2, 'showCode' => 2, 'name' => '举人', 'giftId' => 54, 'giftType' => 3), //二举        举人饼
            'xiucai' => array('resultCode' => 1, 'showCode' => 1, 'name' => '秀才', 'giftId' => 55, 'giftType' => 3), //一秀       秀才饼
        ),
        //'startTime' => 1442559600, //活动开始时间 2015-09-18 15:00:00
        'startTime' => 1442764800, //活动开始时间 2015-09-21 00:00:00
        'endTime' => 1443628799, //活动结束时间：2015-09-30 23:59:59 
        'energyLimit' => 5000, //能量值下限
        'consumeMoonValue' => 10, //博饼需消耗的月光值
        'bobingTimesLimit' => 10, //批量博饼次数限制
        'badge' => array('zhuangyuan' => 12, 'zhuangyuanwang' => 13, 'zhongqiu' => 14), //徽章
        'car' => array('dujiaoshou' => 41, 'yueliangche' => 43), //座驾
        'odds' => array('zhuangyuan' => array(1, 12), 'duitang' => array(2, 15), 'tanhua' => array(3, 54), 'jinshi' => array(4, 40), 'juren' => array(5, 199), 'xiucai' => array(6, 373), 'other' => array(7, 307)), //概率
    ),
    // 中秋排行榜奖励配置
    'midAutumnRankConfig' => array(
        'anchorConfig' => array(
            0 => array(
                'itemId' => 14, // 徽章
                'cash' => 66600, // 奖励聊豆
                'moonLimit' => 90000, // 月光限制
                'carId' => 0, // 座驾id
                'expireDay' => 0, // 座驾过期时间
            ),
            1 => array(
                'itemId' => 14,
                'cash' => 33300,
                'moonLimit' => 60000,
                'carId' => 0,
                'expireDay' => 0,
            ),
            2 => array(
                'itemId' => 14,
                'cash' => 11100,
                'moonLimit' => 30000,
                'carId' => 0,
                'expireDay' => 0,
            ),
        ),
        'userConfig' => array(
            0 => array(
                'itemId' => 14,
                'cash' => 0,
                'moonLimit' => 0,
                'carId' => 41,
                'expireDay' => 30,
            ),
            1 => array(
                'itemId' => 14,
                'cash' => 0,
                'moonLimit' => 0,
                'carId' => 41,
                'expireDay' => 15,
            ),
            2 => array(
                'itemId' => 14,
                'cash' => 0,
                'moonLimit' => 0,
                'carId' => 41,
                'expireDay' => 10,
            ),
        ),
        'zhuangyuanConfig' => array(
            0 => array(
                'itemId' => 13,
                'cash' => 0,
                'moonLimit' => 0,
                'carId' => 43,
                'expireDay' => 30,
            ),
            1 => array(
                'itemId' => 0,
                'cash' => 0,
                'moonLimit' => 0,
                'carId' => 43,
                'expireDay' => 15,
            ),
            2 => array(
                'itemId' => 0,
                'cash' => 0,
                'moonLimit' => 0,
                'carId' => 43,
                'expireDay' => 10,
            ),
        ),
        'anchorMessageConfig' => array(
            0 => array(
                'first' => '恭喜您在嫦娥奔月活动中，荣获“月光仙子”榜第一名。获得奖励：中秋徽章（7天）+66600聊币。',
                'second' => '恭喜您在嫦娥奔月活动中，荣获“月光仙子”榜第一名。获得奖励：中秋徽章（7天）。',
            ),
            1 => array(
                'first' => '恭喜您在嫦娥奔月活动中，荣获“月光仙子”榜第二名。获得奖励：中秋徽章（7天）+33300聊币。',
                'second' => '恭喜您在嫦娥奔月活动中，荣获“月光仙子”榜第二名。获得奖励：中秋徽章（7天）。',
            ),
            2 => array(
                'first' => '恭喜您在嫦娥奔月活动中，荣获“月光仙子”榜第三名。获得奖励：中秋徽章（7天）+11100聊币。',
                'second' => '恭喜您在嫦娥奔月活动中，荣获“月光仙子”榜第三名。获得奖励：中秋徽章（7天）。',
            ),
        ),
        'userMessageConfig' => array(
            0 => array(
                'first' => '恭喜您在嫦娥奔月活动中，荣获“桂月护法”榜第一名。获得奖励：中秋徽章（7天）+独角兽（30天）。',
            ),
            1 => array(
                'first' => '恭喜您在嫦娥奔月活动中，荣获“桂月护法”榜第二名。获得奖励：中秋徽章（7天）+独角兽（15天）。',
            ),
            2 => array(
                'first' => '恭喜您在嫦娥奔月活动中，荣获“桂月护法”榜第三名。获得奖励：中秋徽章（7天）+独角兽（10天）。',
            ),
        ),
        'zhuangyuanMessageConfig' => array(
            0 => array(
                'first' => '恭喜您在中秋“状元王中王”活动中成为第一名。您的“状元”徽章升级为“状元王中王”徽章,并获得中秋限定座驾—月亮座驾（30天）。',
            ),
            1 => array(
                'first' => '恭喜您在中秋“状元王中王”活动中成为第二名。获得中秋限定座驾—月亮座驾（15天）。',
            ),
            2 => array(
                'first' => '恭喜您在中秋“状元王中王”活动中成为第三名。获得中秋限定座驾—月亮座驾（10天）。',
            ),
        ),
    ),

    // 宝箱中奖配置信息
    'liveBagConfig' => array(
        'ld' => array('id'=>1,'ratioNum'=>300,'data'=>array('num'=>20,'typeId'=>0,'itemId'=>1,'configName'=>'ld','desc'=>'聊豆','tip'=>'获得20聊豆')),
        'cm' => array('id'=>2,'ratioNum'=>200,'data'=>array('num'=>3,'typeId'=>3,'itemId'=>56,'configName'=>'cm','desc'=>'鲜美多汁的草莓，不来一个么？','tip'=>'获得草莓(3个)')),
        'ttq' => array('id'=>3,'ratioNum'=>300,'data'=>array('num'=>1,'typeId'=>3,'itemId'=>57,'configName'=>'ttq','desc'=>'甜美松软，口感极佳！','tip'=>'获得甜甜圈(1个)')),
        'bsyjx' => array('id'=>4,'ratioNum'=>120,'data'=>array('num'=>1,'typeId'=>3,'itemId'=>58,'configName'=>'bsyjx','desc'=>'她的心灵纯洁得就像这白色郁金香！','tip'=>'获得白色郁金香(1个)')),
        'ylbk' => array('id'=>5,'ratioNum'=>50,'data'=>array('num'=>1,'typeId'=>4,'itemId'=>1,'configName'=>'ylbk','desc'=>'可以向正在观看的直播房间发送一条飞屏信息','tip'=>'获得银喇叭卡(1张)')),
        'jlbk' => array('id'=>6,'ratioNum'=>20,'data'=>array('num'=>1,'typeId'=>4,'itemId'=>2,'configName'=>'jlbk','desc'=>'可以向其他直播房间发送一条飞屏信息','tip'=>'获得金喇叭卡(1张)')),
        'black_bear' => array('id'=>7,'ratioNum'=>8,'data'=>array('num'=>5,'typeId'=>2,'itemId'=>44,'configName'=>'black_bear','desc'=>'会骑单车的黑熊，还真是少见呢','tip'=>'获得黑熊座驾(5天)')),
        'lb' => array('id'=>8,'ratioNum'=>1,'data'=>array('num'=>1000,'typeId'=>0,'itemId'=>1,'configName'=>'lb','desc'=>'聊币','tip'=>'获得1000聊币')),
        'xcqs' => array('id'=>9,'ratioNum'=>1,'data'=>array('num'=>2,'typeId'=>2,'itemId'=>45,'configName'=>'xcqs','desc'=>'诞生于星辰深处的座驾，神秘、高贵~','tip'=>'获得星辰骑士座驾(2天)')),
    ),
    'rewardArr' => array(
        1 => 'ld',
        2 => 'cm',
        3 => 'ttq',
        4 => 'bsyjx',
        5 => 'ylbk',
        6 => 'jlbk',
        7 => 'black_bear',
        8 => 'lb',
        9 => 'xcqs',
    ),

    'rewardBox' => array(
        'startTime' => 1444752000, //活动开始时间 2015-10-15 00:00:00
        'endTime' => 1447603200, //活动结束时间：2015-11-15 23:59:59 
    ),

    // 宝箱次数配置
    'rewardConfig' => array(
        'getCoinTime' => 60,
        'activityUrl' => 'http://m.91ns.com',
    ),
    // 充值金额宝箱配置
    'rewardCashConfig' => array(
        'maxNum' => 10,
        'rate' => 100,
    ),
    
    //红包配置
    'redPacketConfigs' => array(
        "enable" => true, //是否开启红包功能
        "redGiftId" => 59, //红包礼物id
        "moneyList" => array(1 => 1000, 2 => 2000, 3 => 3000, 4 => 4000, 5 => 5000, 6 => 10000, 7 => 50000, 8 => 100000), //金额选择列表
        "limitList" => array('1' => '主播、守护、管理'), //限制选择列表
        'numMax' => 99, //红包个数限制
        'moneyMin' => 11, //平均红包金额最小限制
        'moneyMax' => 9999, //平均红包金额最大限制
        'randMin' => 10, //手气红包每个红包最小值
        'richerLimit' => 1, //富豪等级最小限制
        'vieUrl' => '/app/vie?', //app红包地址
        'redUrl' => '/app/red?', //app红包地址
        'reddetailUrl' => '/app/reddetail?', //app红包记录地址
        'vip' => 0, //:1：普通vip 2：至尊vip
        'guard' => 2, //1：黄金守护，2：白银守护 3：铂金守护
        'admin' => 1, //管理员
        'sumMoneyMin' => 1000, //手气红包总金额下限
        'checkIsOnlyAnchor' => false, //是否只有主播能查看红包近期记录
        'checkRedPacket' => 0, //是否可查看红包记录
        //猴年春节红包
        'monkeyRedPacket' => array(
            //'startTime' => 1054342400, //活动开始时间：TEST
            'startTime' => 1454342400, //活动开始时间： 2016.2.2  00:00:00
            'endTime' => 1455674400, // 活动结束时间：   2016.2.22   23:59:59,临时修改成2016.2.17 10:00:00
            'giftId' => 84, //礼物id
            "limitList" => array('1' => '四富及以上'), //限制选择列表
            "richerLimit" => 4, //富豪等级限制：四富及以上
            //奖励
            'rewards' => array('send' => array('itemId' => 22, 'expireDay' => 20, 'message' => '红包活动中，您的慷慨获得大奖的认可。获得“一掷千金”徽章（20天）',), //赠送徽章
                'get' => array('itemId' => 23, 'expireDay' => 20, 'message' => '红包活动中，你的财运让大家羡慕。获得“财源滚滚”徽章（20天）',),), //赠送徽章
        ),
    ),
    //幸运礼物配置
    'luckyGiftConfigs' => array(
        'odds' => array(0 => array(10, 15), 1 => array(50, 3), 2 => array(100, 1), 3 => array(0, 4981)), //基本概率  格式： array(key=>array(中奖倍数，中奖个数))
        'oddsEx' => array(0 => array(500, 1)), //特殊概率  格式： array(key=>array(中奖倍数，中奖个数))
        'oddsNum' => 25000, //概率总条数
        'oddsRound' => 5, //基本概率循环次数
        'accrue' => true, //是否累加中奖倍数
        'cycled' => true, //中奖倍数累加时，是否多次广播中奖结果
        'flowerConfigs' => array(//幸运桃花配置
            'giftId' => 61, //幸运桃花礼物id
            // 'odds' => array(0 => array(10, 50), 1 => array(50, 30), 2 => array(100, 10), 4 => array(1000, 1), 5 => array(2000, 1), 6 => array(0, 9908)), //概率
            'odds' => array(0 => array(10, 2), 1 => array(50, 1), 2 => array(0, 496)), //基本概率
            'oddsRe' =>array(0 => array(10, 10),1 => array(50, 10)), //基本概率 除不尽 剩余个数
           // 'oddsEx' => array(1 => array(100, 10),2 => array(1000, 1),3 => array(2000, 1)), //特殊概率
            'oddsEx' => array(1 => array(1000, 1),2 => array(2000, 1)), //特殊概率
            'oddsRound' => 20, //基本概率循环次数
            'oddsNum' => 10000, //概率总条数
            'oddsOt'=>array(100, 10),//特殊概率
            'exceptOdds' => array(9 => 10, 49 => 50), //特殊概率：用户赠送1~9，则只能中10倍奖励，用户赠送10~49，则只能中50倍奖励。
        ),
        'jhConfigs' => array(//幸运菊花配置
            'giftId' => 79, //幸运菊花礼物id
            'odds' => array(0 => array(10, 5), 1 => array(50, 5), 2 => array(0, 990)), //基本概率
            'oddsOt'=>array(500, 2),//特殊概率
            'oddsEx' => array(0 => array(2000, 1)), //特殊概率
            'oddsRound' => 10, //基本概率循环次数
            'oddsNum' => 10000, //概率总条数
            'exceptOdds' => array(9 => 10, 49 => 50), //特殊概率：用户赠送1~9，则只能中10倍奖励，用户赠送10~49，则只能中50倍奖励。
        ),
        'qgConfigs' => array(//幸运青瓜配置
            'giftId' => 89,//礼物id
        ),
        'showTime' => array(2000 => 5 ,1000 => 3, 500 => 2,100 => 1), //不同中奖倍数显示不同时间，单位：秒
        'isBroadArr'=>true,//是否需要以数组形式广播
    ),
    
    //月榜大作战奖励配置
    'monthRankConfigs' => array(
        'endTime'=>1452441600,//结束时间，2016-1-11 00:00:00
        'incomeRank' => array(//收益月榜奖励
            1 => array(//第1名
                'cash' => 500000, // 奖励聊币
                'message'=>'恭喜你在本轮“月榜大作战”活动中，获得人气榜第一名，奖励500000聊币。',//通知
            ),
            2 => array(//第2名
                'cash' => 400000, // 奖励聊币
                 'message'=>'恭喜你在本轮“月榜大作战”活动中，获得人气榜第二名，奖励400000聊币。',//通知
            ),
            3 => array(//第3名
                'cash' => 300000, // 奖励聊币
                 'message'=>'恭喜你在本轮“月榜大作战”活动中，获得人气榜第三名，奖励300000聊币。',//通知
            ),
            4 => array(//第4名
                'cash' => 200000, // 奖励聊币
                'message'=>'恭喜你在本轮“月榜大作战”活动中，获得人气榜第四名，奖励200000聊币。',//通知
            ),
            5 => array(//第5名
                'cash' => 100000, // 奖励聊币
                'message'=>'恭喜你在本轮“月榜大作战”活动中，获得人气榜第五名，奖励100000聊币。',//通知
            ),
        ),
        'richRank' => array(//消费月榜奖励
            1 => array(//第1名
                'carId' => 47, // 奖励座驾
                'expireDay' => 30, // 座驾过期时间
                'message' => '恭喜你在本轮“月榜大作战”活动中，获得富豪榜第一名，奖励座驾—穿云豹（30天）。', //通知
            ),
            2 => array(//第2名
                'carId' => 47, // 奖励座驾
                'expireDay' => 20, // 座驾过期时间
                'message' => '恭喜你在本轮“月榜大作战”活动中，获得富豪榜第二名，奖励座驾—穿云豹（20天）。', //通知
            ),
            3 => array(//第3名
                'carId' => 47, // 奖励座驾
                'expireDay' => 15, // 座驾过期时间
                'message' => '恭喜你在本轮“月榜大作战”活动中，获得富豪榜第三名，奖励座驾—穿云豹（15天）。', //通知
            ),
        ),
    ),

    //日榜奖励配置
    'dayRankConfigs' => array(
        'richRank' => array(//消费日榜奖励
            1 => array(//第1名
                'carId' => 62, // 奖励座驾
                'expireDay' => 1, // 座驾过期时间
                'message' => '恭喜你荣获 富豪榜-日榜 第1名，获赠座驾—齐天大圣（1天）。', //通知
            ),
            2 => array(//第2名
                'carId' => 63, // 奖励座驾
                'expireDay' => 1, // 座驾过期时间
                'message' => '恭喜你荣获 富豪榜-日榜 第2名，获赠座驾—天蓬元帅（1天）。', //通知
            ),
            3 => array(//第3名
                'carId' => 64, // 奖励座驾
                'expireDay' => 1, // 座驾过期时间
                'message' => '恭喜你荣获 富豪榜-日榜 第3名，获赠座驾—卷帘大将（1天）。', //通知
            ),
        ),
    ),

    //积分礼物
    'pointsGiftConfigs' => array(
        'activityTime'=>array('start'=>1448467200,'end'=>1451059200),//2015.11.26-2015.12.26
        'pointsGiftIds' => array(61,79,80),
        'sendPointsConfigs' => array(61=>1,79=>1,80=>1),
        'typeConfigs' => array(
            1=>array('rewardMoney'=>100,'rewardName'=>'杜蕾斯','perPoints'=>50,'totalNum'=>1000,'message1'=>'恭喜你获得 第','message2'=>'期100元的“积分夺宝”奖品：杜蕾斯。未绑定手机用户请绑定手机，客服人员会在未来7个工作日内联系你。','rewardId'=>1,'configName'=>'durex','rewardDesc'=>'随性而动，大展雄风'),
            2=>array('rewardMoney'=>500,'rewardName'=>'纯金车票','perPoints'=>250,'totalNum'=>1000,'message1'=>'恭喜你获得 第','message2'=>'期500元的“积分夺宝”奖品：纯金车票。未绑定手机用户请绑定手机，客服人员会在未来7个工作日内联系你。','rewardId'=>2,'configName'=>'cjcp','rewardDesc'=>'纯金打造，永久典藏'),
            3=>array('rewardMoney'=>1000,'rewardName'=>'八骏金条','perPoints'=>500,'totalNum'=>1000,'message1'=>'恭喜你获得 第','message2'=>'期1000元的“积分夺宝”奖品：八骏金条。未绑定手机用户请绑定手机，客服人员会在未来7个工作日内联系你。','rewardId'=>3,'configName'=>'bjjt','rewardDesc'=>'八骏金典，尽显奢华'),
        ),
    ),
    
    
    //圣诞活动
    'christmas' => array(
        'startTime'=>1450368000,//活动开始时间： 12/18 00:00:00
        'endTime' => 1451318399, // 活动结束时间： 12/28 23:59:59
        'giftId' => 78, //礼物id
        'odds' => array(0 => array(1, 1), 1 => array(0, 4999)),//概率表
        'oddsNum' => 5000, //概率总条数
        'reward' => array(//中奖奖励
            'carId' => 48, // 奖励座驾
            'expireTime' => 864000, // 有效时间10天
            'message' => '恭喜你获得圣诞节座驾—圣诞雪橇（10天）', //通知
        ),
        //圣诞树等级配置
        'levelConfig' => array(0 => array('level' => 0, 'min' => 0, 'max' => 10000), 
            1 => array('level' => 1, 'min' => 10000, 'max' => 30000),
            2 => array('level' => 2, 'min' => 30000, 'max' => 50000),
            3 => array('level' => 3, 'min' => 50000, 'max' => 100000000)),
    ),
    
    //元旦活动
    'newyear' => array(
        'startTime' => 1451577600, //活动开始时间： 2016.1.1 00:00:00
        'endTime' => 1452441599, // 活动结束时间： 2016.1.10 23:59:59
        'limit' => 2016, //开门红特效触发条件
        'giftId' => 80, //礼物id
        'configName' => 'kmh', //礼物特效
        'timeArr' => array(1 => array('1:20', '1:50'),
            2 => array('13:20', '13:50')),
        'oddsNum' => 10000, //概率总条数
        'cashNum' => 50000, //中奖聊币数
    ),
    
    //春节活动
    'springFestival' => array(
        //'startTime' => 1054342400, //活动开始时间： 2016.2.2  00:00:00
        'startTime' => 1454342400, //活动开始时间： 2016.2.2  00:00:00
        'endTime' => 1455674400, // 活动结束时间：   2016.2.22   23:59:59,临时修改成2016.2.17 10:00:00
        'giftIds' => array(81,82), //礼物id
        'flavors' => array(81 => 1, 82 => 200), //礼物对应的年味值
        'carProbability' => array(81 => array('0' => 99999, '1' => 1), //获得座驾的概率 ,10万之1
                                  82 => array('0' => 998, '1' => 2), //千分之2
        ), 
        'carId' => 61, // 年兽座驾
        'expireTime' => 1296000, // 有效时间15天
        'carLimitNum' => 8, //平台总共只产生8个座驾，且，同一用户不可重复获得座驾。
        'oddsNum' => 10000, //概率总条数
        'rewards' => array(0 => array('cash' => 20000, 'message' => '恭喜你获得春节年味奖励：20000聊币。'),//中奖聊币配置
            1 => array('cash' => 10000, 'message' => '恭喜你获得春节年味奖励：10000聊币。'),
            2 => array('cash' => 10000, 'message' => '恭喜你获得春节年味奖励：10000聊币。'),),
    ),
    
    //情人节活动
    'valentineDay' => array(
        //'startTime' => 1054342400, //test
        'startTime' => 1455379200, //活动开始时间： 2016.2.14   00:00:00
        'endTime' => 1455465599, // 活动结束时间：  2016.2.14    23:59:59
    ),

    //电影众筹活动
    'anchorMovie' =>array(
        'startTime' => 1458835200,//20160326
        'beginTime' => 1458835200,//20160325
        'endTime' => 1999999999,//
        'periodTime' => 604800,//
        'giftId' => 88,//
        'finishNum' => 10000,
        'anchorMessage' => array('期的电影票任务已完成，你将和','免费观看一场电影。'),
        'userMessage' => array('期的电影票任务已结束，恭喜你获得和','共赏电影的机会。请尽快绑定手机号码，方便我们联系你'),
    ),
    
);
