<?php

require APP_PATH.'/apps/loader/default.php';

$loader->registerNamespaces(
    array(
       'Micro\Controllers'         => $config->directory->controllersDir,
       'Micro\Models'              => $config->directory->modelsDir,    
       'Micro\Views'               => $config->directory->viewsDir, 
    ),true
)->register();

use Phalcon\Mvc\Micro\Collection as MicroCollection;
use Phalcon\Events\Manager as EventsManager;

use Micro\Controllers\PostsController as Posts;
use Micro\Controllers\PushsController as Pushs;

use Micro\Controllers\SessionController as SessionAuth;
use Micro\Controllers\ActionsController as Actions;
use Micro\Controllers\SignController as Sign;
use Micro\Controllers\MessagesController as Message;
use Micro\Controllers\ChargingController as Charging;
use Micro\Controllers\RoomActionsController as RoomActions;
use Micro\Controllers\RankController as Rank;
use Micro\Controllers\DynamicsController as Dynamics;
use Micro\Controllers\RoomsController as Rooms;
use Micro\Controllers\UsersController as Users;
use Micro\Controllers\FriendsController as Friends;
use Micro\Controllers\FollowersController as Followers;
use Micro\Controllers\AlbumsController as Albums;
use Micro\Controllers\FamilyController as Family;
use Micro\Controllers\GiftsController as Gifts;
use Micro\Controllers\ConfigsController as Configs;
use Micro\Controllers\TreasureController as Treasure;
use Micro\Controllers\ShowController as Show;
use Micro\Controllers\HomeController as Home;
use Micro\Controllers\TestController as Test;
use Micro\Controllers\DiceGameController as DiceGame;
use Micro\Controllers\AppRoomController as AppRoom;

use Micro\Router\SecurityAuthPlugin as SecurityAuth;
use Micro\Router\JsonFormatPlugin as JsonFormat;
use Micro\Router\NotFoundPlugin as NotFound;

$family = new MicroCollection();
$family->setHandler(new Family());
$family->setPrefix('/family');
$family->get('/{familyId}', 'getFamilyInfo');
$application->mount($family);

$rank = new MicroCollection();
$rank->setHandler(new Rank());
$rank->setPrefix('/rank');
$rank->get('/', 'getRank');
$rank->get('/gift', 'getGiftRank');
$application->mount($rank);

//push
$pushs = new MicroCollection();
$pushs->setHandler(new Pushs());
$pushs->setPrefix('/pushs');
$pushs->post('/messagesingle', 'pushAPNMessageToSingle');
$pushs->post('/messagelist', 'pushAPNMessageToList');
$pushs->post('/transmissionsingle', 'pushMessageToSingle');
$pushs->post('/deviceinfo', 'setDeviceInfo');
$application->mount($pushs);

// Session
$auth = new MicroCollection();
$auth->setHandler(new SessionAuth());
$auth->setPrefix('/session');
$auth->post('/', 'auth');
$auth->get('/', 'getVersion');
$auth->get('/getversion', 'getNewVersion');
$auth->get('/getsession', 'getSession');
$auth->get('/loginstatus', 'getLoginStatus');
$application->mount($auth);


$dynamics = new MicroCollection();
$dynamics->setHandler(new Dynamics());
$dynamics->setPrefix('/dynamics');
$dynamics->post('/', 'addDynamics');
$dynamics->get('/', 'getDynamicsList');
$dynamics->post('/reply', 'replyDynamics');
$dynamics->get('/reply', 'getDynamicsReply');
$dynamics->get('/replylist', 'getReplyList');
$dynamics->post('/praise', 'praiseDynamics');
$dynamics->post('/forward', 'forwardDynamics');
$dynamics->get('/praise', 'getPraiseList');
$dynamics->get('/forward', 'getForwardList');
$dynamics->post('/hot', 'execDynamicsHotPoint');
$dynamics->post('/del', 'delDynamics');
$dynamics->get('/praiseslist', 'getDynamicsPraises');
$dynamics->get('/forwardlist', 'getDynamicsForwards');
$application->mount($dynamics);

$message = new MicroCollection();
$message->setHandler(new Message());
$message->setPrefix('/message');
$message->post('/', 'sendMessage');
$message->get('/', 'getMessage');
$message->get('/content', 'getMessageInfo');
$message->post('/setmessageread', 'setMessageRead');
$message->post('/setmessagetop', 'setMessageTop');
$message->post('/delmessage', 'delMessage');
$message->post('/upload', 'uploadMessageImg');
$application->mount($message);

$charging = new MicroCollection();
$charging->setHandler(new Charging());
$charging->setPrefix('/charging');
$charging->post('/alipayreturn', 'alipayReturn');
$charging->post('/alipayaddorder', 'alipayAddOrder');
$charging->post('/pingaddorder', 'pingAddOrder');
$charging->post('/pingreturn', 'pingReturn');
$charging->post('/iosreturn', 'iosReturn');
$charging->post('/iosaddorder', 'iosAddOrder');
$charging->post('/wxpayaddorder', 'wxPayAddOrder');
$charging->post('/wxpayreturn', 'wxPayReturn');
$charging->post('/wxpayappstoreaddorder', 'wxPayAppstoreAddOrder');
$charging->post('/wxpayappstorereturn', 'wxPayAppstoreReturn');
$charging->post('/unionaddorder', 'unionPayAddOrder');//APP银联支付方式：生成订单
$charging->post('/unionpayreturn', 'unionPayReturn');//APP银联支付方式：回调返回
/*
 * H5充值
 */
$charging->get('/pay', 'pay');
 

$application->mount($charging);

// Actions
$actions = new MicroCollection();
$actions->setHandler(new Actions());
$actions->setPrefix('/actions');
$actions->post('/login', 'login');
$actions->get('/loginofthird', 'loginOfThirdCallback');
$actions->get('/logout', 'logout');
$actions->post('/changePwd', 'changePwd');
$actions->get('/forgetPwd', 'forgetPwd');
$actions->post('/checkuserexists', 'checkUserExists');
$actions->get('/secure', 'getSecure');
$actions->post('/checkpwdbysecure', 'checkPwdBySecure');
$actions->post('/checkpwdbyphone', 'checkPwdByPhone');
$actions->post('/resetpwd', 'resetPwd');
$actions->post('/sendcodetophone', 'sendCodeToPhone');
$actions->post('/checknicknameexists', 'checkNicknameExists');
$actions->post('/updatenickname', 'updateNickName');
$actions->post('/updatesignature', 'updateSignature');
$actions->post('/setusercoordinate', 'setUserCoordinate');
$actions->get('/recommfocuslist', 'getRecommFocusList');
$actions->post('/giveitemsendsmscode', 'giveItemSendSmsCode');
$actions->post('/sendphoneloginsms', 'sendPhoneLoginSms');
$actions->post('/newsendphoneloginsms', 'newSendPhoneLoginSms');
$actions->post('/phonesmslogin', 'phoneSmsLogin');
$actions->post('/initpassword', 'setInitPassword');
$actions->post('/setusername', 'setUsername');
$actions->get('/geteventlist', 'getEventList');
$actions->get('/getbannerslist', 'getBannersList');
$actions->post('/phonesmsusers', 'phoneSmsUsers');
$actions->post('/phoneuserlogin', 'phoneUserLogin');
$actions->post('/phonesmsreg', 'phoneSmsReg');
$actions->post('/thirdloignbindphone', 'thirdLoignBindPhone');
$actions->post('/thirdloignbindphonesms', 'thirdLoignBindPhoneSms');
$actions->post('/loginbytoken', 'loginByToken');
$actions->get('/changeaccount', 'changeAccount');
$actions->post('/changeaccountlogin', 'changeAccountLogin');
$actions->get('/getusertask', 'getUserTask');//获取用户任务列表
$actions->get('/gettaskstatus', 'getTaskStatus');//获取某个任务状态
$actions->post('/settotalwatch', 'setTotalWatch');//累计观看
$actions->post('/settotaltalk', 'setTotalTalk');//累计发言
$actions->post('/share', 'share');//分享
$actions->get('/getsecurityscript', 'getSecurityScript');//图形验证码
$actions->get('/getsecurityimage', 'getSecurityImage');//图形验证码
$actions->get('/refuserec', 'refuseRec');//拒绝填写推荐码
$actions->post('/fillrec', 'fillRec');//填写推荐码
$application->mount($actions);

// roomactions
$roomactions = new MicroCollection();
$roomactions->setHandler(new RoomActions());
$roomactions->setPrefix('/roomactions');
$roomactions->get('/{roomid}/gifts', 'sendGifts');
$roomactions->get('/{roomid}/charm', 'getCharm');
$roomactions->post('/{roomid}/charm', 'sendCharm');
$roomactions->post('/{roomid}/broadcast', 'sendRoomBroadcast');
$roomactions->post('/{roomid}/bags', 'sendBagGift');
$roomactions->get('/getguessroom', 'getMobileJumpAnchorId');
// $roomactions->post('/getguessroom', 'getGuessRoom');
//$roomactions->get ('/mobilejumpanchor', 'getMobileJumpAnchorId');
$roomactions->post('/uploadsuggestionspic', 'uploadSuggestionsPic');
$roomactions->post('/addinform', 'addInform');//举报
$roomactions->post('/savesuggestion', 'saveSuggestion');//反馈
$roomactions->get('/test', 'pushPublish');
$roomactions->post('/onlinecoin', 'getOnlineCoin');
$roomactions->post('/setonlinecoin', 'setOnlineCoin');
$roomactions->post('/{roomid}/gameface', 'sendGameFace');
$roomactions->post('/{roomid}/forbidtalk', 'forbidTalk');
$roomactions->post('/{roomid}/kickuser', 'kickUser');
$roomactions->post('/userhorn', 'getUserHorn');
$roomactions->get('/{roomid}/getuserdata', 'getUserData');
$roomactions->post('/usertask', 'getUserTask');
$roomactions->post('/taskreward', 'getTaskReward');
$roomactions->post('/watchtask', 'setWatchTask');
$roomactions->post('/taskstatus', 'getTaskStatus');
$roomactions->get('/getbobing', 'getBobing');
$roomactions->get('/bobinginfo', 'getBobingInfo');
$roomactions->post('/shakedice', 'shakeDice');
$roomactions->get('/zhuangyuanrank', 'getZhuangyuanRank');
$roomactions->get('/energyrank', 'getEnergyRank');
// $roomactions->post('/addrewardlog', 'addRewardLog');
// $roomactions->post('/openreward', 'openReward');
$roomactions->post('/openrewardbox', 'openRewardBox');
$roomactions->post('/checkreward', 'checkReward');
$roomactions->post('/getroomusers', 'getRoomUsers');
$roomactions->post('/getroommanagers', 'getRoomManagers');
$roomactions->get('/redpacketconfig', 'redPacketConfig');
$roomactions->get('/getredpacketlist', 'getRedPacketList');
$roomactions->get('/startredpacket', 'startRedPacket');
$roomactions->get('/getredpacket', 'getRedPacket');
$roomactions->get('/getredpacketinfo', 'getRedPacketInfo');//查询红包详情
$roomactions->post('/searchdetail', 'searchDetail');
$roomactions->get('/getuserredpacket', 'getUserRedPacket');//查询红包最近记录
$roomactions->post('/betpoints', 'betPoints');//积分投注
$roomactions->post('/getbetlog', 'getBetLog');//获取用户投注记录
$roomactions->post('/getbettinglist', 'getBettingList');//打开当前进行中夺宝记录
$roomactions->post('/checkhaswarningbet', 'checkHasWarningBet');//是否
$roomactions->get('/getspringfestivalinfo', 'getSpringFestivalInfo');//春节年味活动
$roomactions->post('/getanchormovieinfo', 'getAnchorMovieInfo');//获取电影众筹信息
$application->mount($roomactions);

// rooms
$rooms = new MicroCollection();
$rooms->setHandler(new Rooms());
$rooms->setPrefix('/rooms');
$rooms->get('/{roomid}/rank', 'getRoomRank');
$rooms->post('/{roomid}/logintonodejs', 'loginToNodeJS');
$rooms->post('/{roomid}/enternodejsroom', 'enterNodeJSRoom');
$rooms->get('/{roomid}/getguarddatalist', 'getGuardDataList');
$rooms->get('/{uid}/enterroom', 'enterRoom');
$rooms->get('/bags', 'getRoomBag');
$rooms->get('/forbiddenwordtxt', 'forbiddenwordtxt');
$rooms->get('/sysconfig', 'sysConfig');
$rooms->get('/{roomid}/totalcount', 'getTotalCount');
$rooms->post('/checkroomlimit', 'checkRoomLimit');
$rooms->post('/checkroompwd', 'checkRoomPwd');
$rooms->post('/getstreamname', 'getStreamName');
$rooms->get('/morefunc', 'getMoreFunction');//获取更多模块
$application->mount($rooms);


// Users
$users = new MicroCollection();
$users->setHandler(new Users());
$users->setPrefix('/users');
$users->get('/', 'getList');
$users->post('/', 'reg');
$users->post('/newlist', 'getNewList');
$users->post('/phone', 'phoneRegister');
$users->post('/smscode', 'regPhoneSendCode');
$users->post('/newsmscode', 'newRegPhoneSendCode');
$users->get('/{uid}', 'getInfo');
$users->get('/{uid}/getuserlevelinfo', 'getUserLevelInfo');
$users->put('/', 'updateInfo');
$users->post('/avatar', 'uploadAvatar');
$users->get('/bindphonesendcode', 'bindPhoneSendCode');
$users->post('/bindphone', 'bindPhone');
$users->get('/unbindphonesendcode', 'unbindPhoneSendCode');
$users->post('/unbindphone', 'unbindPhone');
$users->post('/mofpwd', 'mofpwd');
$users->get('/items/car', 'getUserCars');
$users->post('/items/car', 'buyCar');
$users->get('/items/guard', 'getUserGuard');
$users->post('/items/guard', 'buyGuard');
$users->get('/items/beGuard', 'getUserBeGuard');
$users->get('/items/vip', 'getUserVip');
$users->get('/items/vipnew', 'getUserVipNew');
$users->post('/items/vip', 'buyVip');
$users->post('/items/carstatus', 'updateCarStatus');
$users->get('/items/noraml', 'getUserNormal');
$application->mount($users);

// sign
$sign = new MicroCollection();
$sign->setHandler(new Sign());
$sign->setPrefix('/sign');
$sign->get('/', 'getUserSign');
$sign->post('/', 'setUserSign');
$sign->post('/reward', 'getUserSignReward');
$sign->delete('/', 'del');
$sign->post('/signstatus', 'setSignStatus');
$application->mount($sign);

// Friends
$friends = new MicroCollection();
$friends->setHandler(new Friends());
$friends->setPrefix('/friends');
$friends->get('/', 'getList');
$friends->post('/', 'add');
$friends->delete('/', 'del');
$application->mount($friends);

// Followers
$followers = new MicroCollection();
$followers->setHandler(new Followers());
$followers->setPrefix('/followers');
$followers->get('/focus', 'getFocusList');
$followers->get('/fans', 'getFansList');
$followers->post('/add', 'add');
$followers->post('/del', 'del');
$followers->get('/isfans', 'isFans');
$followers->get('/nickname', 'getNickNameList');
$application->mount($followers);

// Albums
$albums = new MicroCollection();
$albums->setHandler(new Albums());
$albums->setPrefix('/albums');
$albums->get('/', 'getList');
$albums->post('/', 'add');
$albums->delete('/', 'del');
$application->mount($albums);

// Gifts
$gifts = new MicroCollection();
$gifts->setHandler(new Gifts());
$gifts->setPrefix('/gifts');
$gifts->get('/', 'getList');
$gifts->post('/', 'sendGift');
$application->mount($gifts);

// Configs
$configs = new MicroCollection();
$configs->setHandler(new Configs());
$configs->setPrefix('/configs');
$configs->get('/gift', 'getGift');
$configs->get('/vip', 'getVip');
$configs->get('/vipright', 'getVipRight');
$configs->post('/viprights', 'getVipRights');
$configs->get('/guardright', 'getGuardRight');
$configs->post('/guardrights', 'getGuardRights');
$configs->get('/car', 'getCar');
$configs->get('/guard', 'getGuard');
$configs->get('/recharge', 'getRecharge');
$configs->get('/bags', 'getItemConfigList');
$configs->get('/getguardconfigs', 'getGuardConfigs');
$configs->get('/getvipconfigs', 'getVipConfigs');
$configs->get('/isopenother', 'isOpenOther');
$configs->get('/isshowqrcode', 'isShowQrCode');
$configs->post('/gethostip', 'getHostIp');
$configs->get('/appstoreprosign', 'appstoreProSign');//appstore内购产品标识
$configs->get('/getricherconfig', 'getRicherConfig');//富豪等级权限配置
$application->mount($configs);

// Treasure
$treasure = new MicroCollection();
$treasure->setHandler(new Treasure());
$treasure->setPrefix('/treasure');
$treasure->post('/getbanners', 'getBanners');
$treasure->post('/getrecent', 'getRecent');
$treasure->post('/getopening', 'getOpening');
$treasure->post('/gethottest', 'getHottest');
$treasure->post('/getallgoodslist', 'getAllGoodsList');
$treasure->post('/getgoodsinfo', 'getGoodsInfo');
$treasure->post('/dobetting', 'doBetting');
$treasure->post('/getbetresults', 'getBetResults');
$treasure->post('/getroomsbetlist', 'getRoomsBetList');
$treasure->post('/checkroombet', 'checkRoomBet');
$application->mount($treasure);

// Show
$show = new MicroCollection();
$show->setHandler(new Show());
$show->setPrefix('/show');
$show->post('/getshowlist', 'getShowList');
$show->post('/getbuyshowlist', 'getBuyShowList');
$show->post('/buyshow', 'buyShow');
$show->post('/getoptionshow', 'getOptionShow');
$application->mount($show);

// Home
$home = new MicroCollection();
$home->setHandler(new Home());
$home->setPrefix('/home');
$home->post('/getanchorinfo', 'getAnchorInfo');
$home->post('/getfanscontribute', 'getFansContribute');
$application->mount($home);

// TEST
$test = new MicroCollection();
$test->setHandler(new Test());
$test->setPrefix('/test');
$test->get('/redislog', 'redisLog');
$test->get('/', 'index');
$test->get('/show/{slug}', 'show');
$test->post('/edit', 'edit');
$test->get('/test', 'test');
$test->get('/pay', 'pay');
$test->get('/pay2', 'pay2');
$test->get('/pay3', 'pay3');
$test->get('/verifyReturn', 'verifyReturn');
$test->post('/verifyNotify', 'verifyNotify');
$test->get('/auth1', 'auth1');
$test->get('/auth2', 'auth2');
$application->mount($test);


// DiceGame 骰宝游戏
$dicegame = new MicroCollection();
$dicegame->setHandler(new DiceGame());
$dicegame->setPrefix('/dicegame');
/*$dicegame->get('/getinfo', 'getInfo');//获取房间游戏信息
$dicegame->get('/getlastinfo', 'getLastInfo');//获取上一轮游戏信息
$dicegame->post('/bedeclarer', 'beDeclarer');//上庄
$dicegame->post('/canceldeclare', 'cancelDeclare');//下庄
$dicegame->post('/startgame', 'startGame');//庄家开庄,开启押注
$dicegame->post('/stake', 'stake');//用户押注
$dicegame->post('/opendice', 'openDice');//庄家开盅*/
$application->mount($dicegame);


// AppRoom 手机直播
$approom = new MicroCollection();
$approom->setHandler(new AppRoom());
$approom->setPrefix('/approom');
$approom->post('/checkuserpublishinfo', 'checkUserPublishInfo');//检查用户直播条件
$approom->post('/setroomtitle', 'setRoomTitle');//设置直播间title
$approom->post('/startpublish', 'startPublish');//开播通知
$approom->post('/addliveaudiencelog', 'addLiveAudienceLog');//直播观众统计
$approom->post('/getroominfo', 'getRoomInfo');//获取房间信息
$approom->post('/stoppublish', 'stopPublish');//关播通知
$application->mount($approom);

//注册插件
$eventManager = new EventsManager();
$eventManager->attach('micro:beforeExecuteRoute', new SecurityAuth());
$eventManager->attach('micro:afterExecuteRoute', new JsonFormat());
$eventManager->attach('micro:beforeNotFound', new NotFound());
$application->setEventsManager($eventManager);