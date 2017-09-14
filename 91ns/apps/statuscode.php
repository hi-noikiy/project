<?php

use Phalcon\DI\FactoryDefault;

class StatusCode{
    public $StatusEnum = array(
        'OK'                        => 200,
        'CREATED'                   => 201,
        'UNAUTHORIZED'              => 401,
        'NOTFOUND'                  => 404
    );

    public $Code = array(
        //'配置项'=>'配置值'
        'OK'                        => array(0, '操作成功'),

        // 系统错误
        'URI_ERROR'                 => array(1, '请求Url错误'),
        'PROXY_ERROR'               => array(2, '请求协议错误'),
        'AUTH_ERROR'                => array(3, '认证错误'),
        'PARAM_ERROR'               => array(4, '参数错误'),
        'DB_OPER_ERROR'             => array(5, '数据库操作错误'),
        'VALID_ERROR'               => array(6, '信息校验错误'),
        'FILESYS_OPER_ERROR'        => array(7, '文件系统操作错误'),
        'UPLOADFILE_ERROR'          => array(8, '上传文件错误'),
        'DATA_FROMATE_ERROR'        => array(9, '数据格式错误'),

        // 业务错误
        'USER_NOT_EXIST'                => array(101, '用户不存在'),
        'USER_CAN_NOT_OPER'             => array(102, '用户没有操作的权限'),
        'CHATSERVER_RETURN_ERROR'       => array(103, '聊天服务器返回错误信息'),
        'CANNOT_CONNECT_CHATSERVER'     => array(104, '无法连接聊天服务器'),
        'ROOM_NOT_EXIST'                => array(105, '房间不存在'),
        'SESSION_HASNOT_LOGIN'          => array(106, '用户未登录'),
        'CURRENT_ROOM_IS_PUBLISHED'     => array(107, '当前房间直播中'),
        'CURRENT_ROOM_IS_NOT_PUBLISHED' => array(108, '当前房间没有直播'),
        'DATA_IS_NOT_EXISTED'           => array(109, '数据不存在'),
        'DELETE_DATA_FAILED'            => array(110, '删除数据失败'),
        'USER_HAS_LOGIN'                => array(111, '用户已经登录'),
        'THIRD_LOGIN_RETURN_ERROR'      => array(112, '第三方登录返回错误'),
        'USER_NOT_ACTIVE'               => array(113, '用户未激活'),
        'USER_IS_KICKED_FROM_ROOM'      => array(114, '用户已经从房间踢出'),
        'SECURITY_CODE_ERROR'           => array(115, '验证码输入错误'),
        'USER_EXPIRE_VALIDITY'          => array(116, '验证串失效'),
        'USER_NAME_EXISTS'              => array(117, '用户名已经存在'),
        'IS_SIGN_USER'                  => array(118, '您已经是签约主播'),
        'IS_ANCHOR_USER'                => array(119, '是签约家族主播'),
        'IS_SIGNING_USER'               => array(120, '您申请的签约还在审核中'),
        'NOT_SIGN_USER'                 => array(121, '不是签约主播'),
        'IS_SIGNING_FAMILY_USER'        => array(122, '签约家族在审核中'),
        'NOT_FROZEN'                    => array(123, '账户未冻结'),
        'HAS_FROZEN_OR_NOT_SIGN'        => array(124, '已冻结或未签约'),
        'NICKNAME_HAS_EXISTS'           => array(125, '昵称已经存在'),
        'NICKNAME_ALL_NUMBER'           => array(126, '昵称全为数字'),
        'SECURITY_SESSION_FALSE'        => array(127, '请求失效'),
        'TELEPHONE_HAS_EXISTS'          => array(128, '该手机号已绑定账号'),
        'NOT_HAS_EMAIL'                 => array(129, '未绑定邮箱'),
        'GET_ROOM_LIST_PARAM_ERROR'     => array(130, '获取房间列表参数错误'),
        'FAMILY_NOT_EXISTS'             => array(131, '家族不存在'),
        'IS_FAMILY_ANCHOR'              => array(132, '您已经是家族主播</br>请退出家族后再来创建'),
        'FAMILY_BE_APPLY_STATUS'        => array(133, '创建的家族正在审核中'),
        'SIGN_ANCHOR_STATUS_NOT_FORZEN' => array(134, '未冻结状态'),
        'SIGN_ANCHOR_STATUS_FORZEN'     => array(135, '已处于冻结状态'),
        'HAS_FAMILY'                    => array(136, '已经有家族'),
        'NO_HAS_FAMILY'                 => array(137, '没有家族'),
        'SEATCOUNT_IS_LITTLE'           => array(138, '座位数值不够大'),
        'COIN_NOT_ENOUGH'               => array(139, '聊豆不足,您可以通过签到和日常任务获取'),
        'CASH_NOT_ENOUGH'               => array(140, '聊币不足'),
        'MONEY_NOT_ENOUGH'              => array(141, '收益不足'),
        'CREATOR_NO_FOUND'              => array(142, '家族长信息错误'),
        'USER_CAN_NOT_UPDATE_FAMILY'    => array(143, '用户权限不足'),
        'CREATOR_CAN_NOT_EXIT'          => array(144, '家族长无法退出家族'),
        'NO_ANCHOR_FAMILY_CREATOR'      => array(145, '不是该主播的族长'),
        'NO_ANCHOR_FAMILY'              => array(146, '家族与主播不对应'),
        'NO_SIGN_ANCHOR_CAN_NOT_JOIN'   => array(147, '不是签约主播，无法加入'),
        'NO_FAMILY_CREATOR'             => array(148, '不是家族长'),
        'CANNOT_OPER_OWNER'             => array(149, '不能对自己操作'),
        'IS_FOLLOWED'                   => array(150, '该用户已被关注'),
        'MULTIOPER_FAILED'              => array(151, '多个连续操作步骤失败'),
        'ALREADY_IN_FAMILY'             => array(152, '已经有家族'),
        'APPLY_FAMILY_STATUS'           => array(153, '您申请的家族还在审核中'),
        'EMPTY_APPLY'                   => array(154, '申请记录不存在'),
        'IS_SIGN_ANCHOR'                => array(155, '已经是签约主播'),
        'ACTION_NO_ALLOW'               => array(156, '权限不对'),
        'CAR_NOT_EXIST'                 => array(157, '该座驾不存在'),
        'CHARM_NOT_ENOUGH'              => array(158, '今天您的魅力已经送完,请明天继续赠送'),
        'NO_ANY_DATA_OF_RANK_LOG'       => array(159, '没有任何数据'),
        'UPDATE_ISSUES_NO_SUCCESS'      => array(160, '安全问题修改失败'),
        'SET_ISSUES_NO_SUCCESS'         => array(174, '设置问题设置失败'),
		'ALREADY_GOLDEN_GUARDIAN'       => array(161, '您已经购买了黄金守护'),
		'SHARE_HAVE_TO_FOCUS_ANCHOR'    => array(162, '分享时未关注该主播'),
		'NO_FAMILY_CAN_BE_APPLY'        => array(163, '当前没有已创建的家族，无法加入'),
        'THIRDPARTY_CANNOT_CHANGEPWD'   => array(164, '使用第三方登录，无法修改密码'),
        'NO_FIND_THIS_CAR'              => array(165, '未找到该座驾信息'),
        'CAR_STATUS_ALREADY_BEEN'       => array(166, '座驾状态已经是这个状态'),
        'CAR_EXPIRE_TIME_OUT'           => array(167, '座驾已经过期'),
        'NO_BIND_TELEPHONE'             => array(168, '未绑定手机'),
        'SMS_CODE_NO_SEND'              => array(169, '手机验证码发送失败'),
        'HAS_BIND_TELEPHONE'            => array(170, '已经有绑定手机'),
        'FAMILY_NAME_HAS'               => array(171, '您的家族名重名'),
        'FAMILY_SHORT_NAME_HAS'         => array(172, '您的家族徽章重名'),
        'APPLY_PIC_NO_ENOUGH'           => array(173, '缺少需要的照片'),
        'NOT_ENOUGH_VIPLEVEL'           => array(174, 'VIP等级不足'),
        'NOT_ENOUGH_RICHERLEVEL'        => array(175, '富豪等级不足'),
        'QUSETION_OR_ANSWER_ERR'        => array(176, '安全问题错误'),
        'CHARM_IS_INSCREASING'          => array(177, '您的魅力还在努力积攒中，请耐心等候'),
        'CHARM_NOT_ENOUGH_NEED_VIP'     => array(178, '您的魅力值不足,购买VIP并升级可以提高魅力获取速度并提高上限，是否现在购买？'),
        'FREE_GIFT_USE_OUT'             => array(179, '今日免费礼物已达到上限'),
        'FREE_COIN_NOT_AVAILABLE'       => array(180, '时间还没到，无法领取免费聊豆'),
        'USER_NICK_NAME_EXIST'          => array(181, '昵称已被注册'),
		'MGR_NUM_BEYOND_LIMIT'          => array(182, '管理员数量超过限制'),
        'DOUZI_KEY_ERROR'               => array(183, '豆子密钥错误'),
         'HAS_SIGN'                     => array(184, '已签到'),
         'HAS_GET_REWARD'               => array(185, '您没有可领取的奖品'),
        'REWARD_IS_NOT_EXISTED'         => array(186, '奖品不存在'),
        'NOT_GUARD'                     => array(187, '不是守护'),
        'NOT_ENOUGH_BAG_ITEM'           => array(188, '背包物品不足'),
        'IS_YOUR_ROOM'                  => array(189, '该用户已经是您的房管了！'),
        'ROOM_HAS_REACHED_ITS_LIMIT'    => array(190, '您拥有的房管已达上限，添加失败！'),
        'NO_PUBLISHED_ROOM'             => array(191, '没有开播房间'),
        'PAY_NOT_FINISH'                => array(192, '充值未完成'),
        'DOUZI_ERR'                     => array(193, '豆子秀吧接口返回错误'),
        'ANNOUNCEMENT_LENGTH_OVER'      => array(194, '公告长度超过100个字了'),
        'USER_BEOPER_IS_CURRENT_LEVEL'  => array(195, '被操作者已是当前等级'),
        'TIME_NOT_NULL'                 => array(196, '时间不能为空'),
        'THIS_TELEPHONE_HAS_BIND'       => array(197, '该号码已经绑定过了'),
        'IS_SUPER_ADMIN'                => array(198, '该用户是超级管理员，操作无效！'),
        'MOBILEPHONE_IS_ERROR'          => array(199, '手机号码不正确'),
        'SMSCODE_IS_TIME_OUT'           => array(200, '验证码已过期'),
        'APPLY_NO_COMPLETE'             => array(201, '申请信息不完整'),
        'APPLY_OVER_TIME'               => array(202, '该申请不可被同意加入家族了'),
        'VIP_LEVEL_HAS_TOP'             => array(203, '您已经购买了至尊VIP，无法再购买普通VIP'),
        'NOT_SET_SECURE'                => array(204, '没有设定密保，请使用其他方式找回'),
        'NOT_BIND_PHONE'                => array(205, '没有绑定手机，请使用其他方式找回'),
        'THIS_TELEPHONE_HAS_REG'        => array(206, '此号码已经注册过'),
        'LEFT_BONUS_NOT_ENOUGH'         => array(207, '该主播当日剩余可接受收益不足'),
        'TUO_CANNOT_SEND_TO_ANCHOR'     => array(208, '贵宾号不能给非保底底薪主播送礼物'),
        'MESSAGE_NOT_EXIST'             => array(209, '私信不存在'),
        'MESSAGE_NOT_OWNER_YOU'         => array(210, '不能编辑非自己的私信'),
        'NOT_SET_POSITIONS'             => array(211, '未设置坐标'),
        'FORBIDDEN_BY_SUPER_ADMIN'      => array(212, '您被超级管理员终止了直播'),
        'SUPER_ADMIN_CANNOT_OPER_LEVEL' => array(213, '超级管理员不能操作等级权限'),
        'CURRENT_ROOM_IS_PAUSE'         => array(214, '当前房间已暂停直播'),
        'OPER_USER_MONEY_ERROR'         => array(215, '操作失败，请重试！'),
        'USERNAME_NOT_ALNUM'            => array(216, '账号只有同字母和数字组成！'),
        'USERNAME_IS_NUMERIC'           => array(217, '账号不能纯数字！'),
        'USERNAME_LENGTH_ERROR'         => array(218, '账号长度为4-12位！'),
        'NICKNAME_LENGTH_ERROR'         => array(219, '昵称长度为2-10位！'),
        'NICKNAME_ALL_SPACE'            => array(220, '昵称不能全为空格！'),
        'LAST_TASK_NOT_FINISH'          => array(221, '上一个任务未完成'),
        'THIS_TASK_NOT_FINISH'          => array(222, '该任务未完成'),
        'HAS_NOT_BIND_TELEPHONE'        => array(223, '您还没有绑定手机'),
        'MONEY_NOT_LARGE_ENOUGH'        => array(224, '提现金额不能低于限额'),
        'MONEY_TOO_LARGE_ENOUGH'        => array(225, '提现金额不足'),
        'SETTLE_LOG_HAS_SETTLED'        => array(226, '结算日志已经被结算了，请重试'),
        'EMPTY_DATA'                    => array(227, '没有数据'),
        'UID_ERROR'                     => array(228, '请输入正确的账号ID'),
        'SONG_HAS_EXISTS'               => array(229, '歌曲已经存在'),
        'SONG_HAS_NOT_EXISTS'           => array(230, '歌曲不存在或者已经删除'),
        'IOS_PAY_FAIL'                  => array(231, '支付失败'),
        'CANNOT_USE_TELEPHONE'          => array(232, '不允许使用手机号'),
        'USERNAME_CANNOT_USE'           => array(233, '用户名不可用'),
        'NICKNAME_CANNOT_USE'           => array(234, '昵称不可用'),
        'USERNAME_CANNOT_EDIT'          => array(235, '用户名不可修改'),
        'PASSWORD_CANNOT_INIT'          => array(236, '密码不可初始化'),
        'USER_PASSWORD_ERROR'           => array(237, '账号不存在或密码错误'),
        'OLD_PASSWORD_ERROR'            => array(238, '旧密码错误'),
        'ACTIVITY_END'                  => array(239, '活动已结束'),
        'POS_HAS_RECOMMOND'             => array(240, '该位置已推荐其他主播'),
        'OTHER_POS_RECOMMOND'           => array(241, '已经在其他位置推荐'),
        'CANNOT_SEND_THIS_GIFT'         => array(242, '不能赠送此礼物'),
        'SEND_MESSAGE_LIMITED'          => array(243, '今天的免费短信已用完，请明天再来'),
        'SMS_NUM_LIMITED'               => array(244, '今天的绑定/解绑次数已用完，请明天再来'),
        'REG_TOO_OFTEN'                 => array(245, '请不要频繁的注册新账号'),
        'CONTENT_IS_TOOlONG'            => array(246, '公告条数不宜过多，请删除部分之后再添加'),
        'EXCHANGE_FAIL'                 => array(247, '兑换码无效或已过期'),
        'APPLY_HAS_SEND'                => array(248, '加入家族的申请已提交，请耐心等待审核'),
        'PASSWORDS_CANNOT_BE_SAME'      => array(249, '新密码不能与旧密码一致'),
        'NOT_SET_LOGIN_PWD'             => array(250 ,'您还未设置登录密码，请先设置登录密码'),
        'EXCHANGE_CODE_ERROR'           => array(251, '兑换码无效或已过期'),
 	'RECOMMENT_IS_ADDED'            => array(252 ,'推荐用户已经存在'),
        'GAME_CAN_NOT_START'            => array(253, '月光值不足,无法开启游戏'),
        'MOON_VALUE_NOT_ENOUGH'         => array(254, '月光值不足，无法博饼'),
        'ADD_SHORT_URL_FAIL'            => array(255, '短地址生成失败'),
        'HAS_BIND_RECOMMOND'            => array(256, '已与推荐用户绑定'),
        'RECOMMOND_UID_ERROR'           => array(257, '推荐人信息错误'),
        'THE_PHONE_HAS_GET_REWARD'      => array(258, '您不是新用户，不能参与该活动'),
        'LOGIN_OVERTIME'                => array(259, '登录超时'),
        'BIND_PHONE_LIMIT'              => array(260, '您的手机已绑定满5个账号，无法再绑定新账号'),
        'NOT_ENOUGH_NUM_LEFT'           => array(261, '开启宝箱次数不足'),
        'TIME_IS_NOT_COMING'            => array(262, '还没到开启宝箱时间'),
        'REWARD_IS_OPENED'              => array(263, '宝箱已经开启过了'),
        'CHANGE_ACCOUNT_ERROR'          => array(264, '切换账号失败'),
        'NONE_CHANGE_ACCOUNT'           => array(265, '您没有可切换的账号'),
        'PLEASE_ENTER_PWD'              => array(266, '请输入房间密码'),
        'PERMISSION_DENIED'             => array(267, '您没有进入房间的权限'),
        'ROOM_PWD_ERROR'                => array(268, '房间密码输入错误'),
        'RED_PACKET_LIMIT'              => array(269, '成为该房间的管理或者守护才能抢该红包，快去开通守护讨好主播吧~'),
        'RED_PACKET_HAS_GRAB'           => array(270, '稍微来迟了一步，下次下手快点吧'),
        'VIDEO_NUM_IS_LIMITED'          => array(271, '抱歉，你录保存的录像已达6部，无法继续录制！'),
        'VIDEO_IS_USING'                => array(272, '录像正在使用中，不允许删除'),
        'VIDEO_IS_IN_SAVE_PERIOD'       => array(273, '录像在保存期内，不允许删除'),
        'PLEASE_CHOOSE_PLAY_VIDEO'      => array(274, '请先选择播放录像'),
        'PLEASE_CLOSE_PLAY_VIDEO'       => array(275, '请先关闭播放录像功能'),
        'OPER_NOT_AFFACT'               => array(276, '操作无效'),
        'BET_POINT_HAS_OPENED'          => array(277, '该轮夺宝已结束，请投注下一期'),
        'NOT_ENOUGH_POINT'              => array(278, '您的积分不足'),
        'NOT_ENOUGH_NOT_ENOUGH'         => array(279, '本期投注数不足'),
        'NOT_IN_ACTIVITY_PERIOD'        => array(280, '该时间不在活动范围内'),
        'TUO_HAS_NOT_AUTH'              => array(281, '推广员不能参与该活动'),
        'DAY_LIMIT_IS_NOT_ENOUGH'       => array(282, '单日额度不足'),
        'CONVENE_LIMIT'                 => array(283, '每日只能使用三次此功能'),
        'CONVENE__TIME_LIMIT'           => array(284, '半小时内只能使用一次本功能'),
        'NOT_ENOUGH_CASH'               => array(285, '您的聊币不足'),
        'FORBID_TIMES_LIMIT'            => array(286, '本日禁言特权次数已用完'),
        'FORBID_LEVEL_LIMIT'            => array(287, '禁言失败！你可以禁言等级低三级以上的用户！'),
        'KICK_LEVEL_LIMIT'              => array(288, '踢人失败！你可以踢等级低三级以上的用户！'),
        'KICK_TIMES_LIMIT'              => array(289, '本日踢人特权次数已用完'),
        'NOT_ENOUGH_SHOW_CARD'          => array(290, '您的节目卡不足'),
        'EVERY_ANCHOR_HAS_ONE'          => array(291, '每个主播只能领取一种价格的酒水券'),
        'NOT_ALLOWED_TO_SEND'           => array(292, '贵宾号或者托账号没有该权限'),
        'ONE_WINE_NOT_ALLOWED_REPEAT'   => array(293, '每种商品不允许重复分配给同一个主播'),
        'THIS_WINE_HAS_ALLOCATED'       => array(294, '不允许重新分配已经有用户下注了的商品'),
        'GROUP_NAME_IS_EXIST'           => array(295, '军团名称已被占用'),
        'GROUP_SHORTNAME_IS_EXIST'      => array(296, '徽章名称已被占用'),
        'HAS_JOIN_GROUP'                => array(297, '该用户已加入军团'),
        'USER_NOT_IN_ROOM'              => array(298, '用户不在该直播间'),
        'GAME_IS_OPENING'               => array(299, '游戏正在进行中'),
        'GAME_IS_FINISH'                => array(300, '游戏已结束'),
        'INVALID_REC_CODE'              => array(301, '无效邀请码'),
        'HAS_BEEN_REC'                  => array(302, '已填写推广码'),
        'NOT_JOIN_ACTIVITY'             => array(303, '不属于活动参与者'),
        'NOT_SET_ROOM_PIC'              => array(304, '您还未设置直播封面'),
        'FIRST_APP_PUBLISH'             => array(305, '第一次手机直播'),
        'HAS_DECLARE_GAME'              => array(501, '已有人上庄'),
        'STATUS_HAS_CHANGED'            => array(502, '状态已改变，不能操作'),
        'DICE_GAME_HAS_END'             => array(503, '本次押注已经结束'),
        'DECLARER_CASH_NOT_ENOUGH'      => array(504, '庄家聊币不足，无法下注'),
        'ONE_GAME_STAKE_LIMIT'          => array(505, '单局下注不得超过10000聊币'),
        'GRAB_DECLARER_HAS_END'         => array(506, '抢庄已结束'),
        'GRAB_CASH_NOT_ENOUGH'          => array(507, '抢庄者携带聊币不足'),
         
        // 附加模块错误
        //'ROOM_MODEL_RETURN_ERROR'   => array(200, '房间模块返回错误信息'),
        //'FRIEND_MODEL_RETURN_ERROR' => array(300, '好友模块返回错误信息'),
        //'FOLLOW_MODEL_RETURN_ERROR' => array(400, '关注模块返回错误信息'),
    );

    public $ValidatorCode = array (
        'USERNAME_ERROR'              => array(1, '账号格式错误'),
        'PASSWORD_ERROR'              => array(2, '密码格式错误'),
        'NICKNAME_ERROR'              => array(3, '昵称格式错误'),
        'BIRTHDAY_ERROR'              => array(4, '生日格式错误'),
        'SEX_ERROR'                   => array(5, '性别格式错误'),
        'DEVICEID_ERROR'              => array(6, '设备ID格式错误'),
        'PLATFORM_ERROR'              => array(7, '平台信息格式错误'),
        'DEVICENAME_ERROR'            => array(8, '设备名称格式错误'),
        'VERSION_ERROR'               => array(9, '版本信息格式错误'),
        'OLDPWD_ERROR'                => array(10, '旧密码格式错误'),
        'NEWPWD_ERROR'                => array(11, '新密码格式错误'),
        'ROOMID_ERROR'                => array(12, '房间号格式错误'),
        'UID_ERROR'                   => array(13, '用户ID格式错误'),
        'CONTENT_ERROR'               => array(14, '内容格式错误'),
        'LEVEL_ERROR'                 => array(15, '等级格式错误'),
        'TIME_ERROR'                  => array(16, '时间格式错误'),
        'PRICE_ERROR'                 => array(17, '价格格式错误'),
        'ID_ERROR'                    => array(18, 'ID格式错误'),
        'ACCOUNTID_ERROR'             => array(19, '账号ID格式错误'),
        'ROOMTITLE_ERROR'             => array(20, '房间标题长度过长'),
        'SORTTYPE_ERROR'              => array(21, '排序类型格式错误'),
        'ISFORBID_ERROR'              => array(22, '禁言解禁格式错误'),
        'SEATPOS_ERROR'               => array(23, '抢座座位格式错误'),
        'SEATCOUNT_ERROR'             => array(24, '抢座座位数格式错误'),
		'BUYTYPE_ERROR'			      => array(25, '购买格式错误'),
        'TYPE_ERROR'                  => array(26, '类型格式错误'),
        'NUMBER_ERROR'                => array(27, '数据格式错误'),
        'INDEX_ERROR'                 => array(28, '索引类型错误'),
        'COMPANYNAME_ERROR'           => array(29, '公司名字错误'),
        'LONGITUDE_ERROR'             => array(30, '经度格式错误'),
        'LATITUDE_ERROR'              => array(30, '纬度格式错误'),
        'CLIENTID_ERROR'              => array(31, 'CLIENTID错误'),
        'ANSWER_ERROR'                => array(32, '安全问题答案错误'),
    );

    public $FriendCode = array(
        'OK' => 0,                          // 操作成功
        'DB_OPER_ERROR' => 1,               // 数据库操作失败
        'FRIEND_REQ_IS_NOT_EXIST' =>2,      // 该好友请求不存在
        'FRIEND_IS_EXIST' => 3,             // 该用户已是好友
        'FRIEND_IS_NOT_EXIST' => 4,         // 该好友不存在
        'CANNOT_OPER_OWNER' => 5,           // 不能对自己操作
        'MULTIOPER_FAILED' => 6,            // 多个连续操作步骤失败
        'CONNECT_CHARSERVER_FAILED' => 7,   // 连接ChatServer失败
        'OPER_CHATSERVER_FAILED' => 8,      // 操作ChatServer失败
        'USER_IS_IN_BLACKLIST' => 9,        // 该用户已在黑名单中
        'USER_IS_NOT_IN_BLACKLIST' => 10,   // 该用户未在黑名单中
        'FRIEND_REQ_IS_EXIST' => 11,        // 该好友请求已存在
    );

    public $FollowCode = array(
        'OK' => 0,                          // 操作成功
        'DB_OPER_ERROR' => 1,               // 数据库操作失败
        'IS_FOLLOWED' => 3,                 // 该用户已被关注
        'IS_NOT_FOLLOWED' => 4,             // 该用户未被关注
        'CANNOT_OPER_OWNER' => 5,           // 不能对自己操作
        'MULTIOPER_FAILED' => 6,            // 多个连续操作步骤失败
    );

    public $CharServerCode = array(
        -1 => '聊天服务器已经断开',
        0 => '成功',
        1001 => '房间Id错误或者房间不存在',
        2007 => '用户不在线或不存在',
        2009 => '权限不足',
        2010 => '无效操作',
        3001 => '账号已注册',
        3002 => '用户不存在',
        3003 => '账号不存在或密码错误',   //账号不存在
        3004 => '账号不存在或密码错误',   //密码错误
        3005 => ' ',//CHG_PSW_ERR
        3006 => '账号已注册',//DEL_ACCOUNT_ERR
    );

    protected $logger;

    public function __construct(){
        $directory = FactoryDefault::getDefault()->get('config')->directory->logsDir;
        $this->logger = new \Phalcon\Logger\Adapter\File($directory.'/interact.log');
    }

    public function errLog($errInfo) {
        $this->logger->error('【StatusCode】 error : '.$errInfo);
    }

    public function getCode($index) {
        return $index;
        //return $this->Code[$index][0];
    }

    public function getCodeInfo($index) {
        return $this->Code[$index][1];
    }

    public function getStatus($index) {
        return $this->StatusEnum[$index];
    }

    public function getValidatorCode($index) {
        return $this->ValidatorCode[$index][0];
    }

    public function getValidatorInfo($index) {
        return $this->ValidatorCode[$index][1];
    }

    public function getFriendCode($index) {
        return $this->FriendCode[$index];
    }

    public function getFollowCode($index) {
        return $this->FollowCode[$index];
    }

    public function genCharServerError($errInfo) {
        $errMsg['code'] = $errInfo['code'];
        $errMsg['info'] = $this->CharServerCode[$errInfo['code']];

        return $errMsg;
    }

    //统一定义：返回给PC客户端ajax请求的数据格式
    public function ajaxReturn($code, $data="") {
        exit($this->generate(0, $code, $data, true));
    }

    //统一定义：获取给PC客户端ajax请求的数据格式
    public function ajaxGetReturnData($code, $data="") {
        return $this->generate(0, $code, $data, true);
    }

    //统一定义：返回给手机端请求的数据格式
    public function mobileReturn($code, $data="") {
        return $this->generate(0, $code, $data);
    }

    //内部接口：返回json格式的数据
    private function generate($status, $code, $data="", $encode=false) {
        $result['code'] = $status*1000+$this->Code[$code][0];
        $result['data'] = $data;
        $result['info'] = $this->getCodeInfo($code);

        //if($code != $this->Code['OK']){
        if($code != 'OK'){
            if (is_array($data)) {
                $data = json_encode($data, JSON_UNESCAPED_UNICODE);
            }
            $this->errLog('generate => code: '.$this->Code[$code][0].' data: '.$data);
        }

        if ($encode) {
            $callback = isset($_GET['callback'])?$_GET['callback']:'';
            if ($callback) {
                return $callback . "(" . json_encode($result, JSON_UNESCAPED_UNICODE) . ")";
            }
            return json_encode($result, JSON_UNESCAPED_UNICODE);
        }
        return $result;
    }
    
    //返回json格式的数据
    public function newAjaxReturn( $result=array(), $encode=true) {
    	$code = $result['code'];
    	$data = $result['info'];
    
    	//if($code != $this->Code['OK']){
    	if($code != '0'){
    		if (is_array($data)) {
    			$data = json_encode($data, JSON_UNESCAPED_UNICODE);
    		}
    		$this->errLog('generate => code: '.$code.' info: '.$data);
    	}
    	if ($encode) {
    		$callback = isset($_GET['callback'])?$_GET['callback']:'';
    		if ($callback) {
    			exit($callback . "(" . json_encode($result, JSON_UNESCAPED_UNICODE) . ")");
    		}
    		exit(json_encode($result, JSON_UNESCAPED_UNICODE));
    	}
    	return $result;
    }

    //统一定义：从framework返回出去的数据格式
    public function retFromFramework($code, $data="") {
        $result['code'] = $code;
        $result['data'] = $data;
        //if($code != $this->Code['OK']){
        /*if($code != 'OK'){
            if (is_array($data)) {
                $data = json_encode($data);
            }
            $this->errLog('retFromFramework => code: '.$this->Code[$code][0].' data: '.$data);
        }*/

        return $result;
    }

}

return new StatusCode();
