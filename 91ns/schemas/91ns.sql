/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-02-16 01:46:59
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for pre_sign_anchor
-- ----------------------------
DROP TABLE IF EXISTS `pre_sign_anchor`;
CREATE TABLE `pre_sign_anchor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `familyId` int(11) DEFAULT '0',
  `realName` varchar(100) DEFAULT '',
  `gender` tinyint(1) DEFAULT '0',
  `photo` varchar(255) DEFAULT '',
  `bank` varchar(100) DEFAULT '',
  `birth` varchar(100) DEFAULT '',
  `cardNumber` varchar(30) DEFAULT '',
  `accountName` varchar(100) DEFAULT '',
  `idCard` varchar(30) DEFAULT '',
  `telephone` varchar(30) DEFAULT '',
  `qq` varchar(20) DEFAULT '',
  `birthday` int(11) DEFAULT '0',
  `location` varchar(30) DEFAULT '0594' COMMENT '所在地',
  `constellation` smallint(4) DEFAULT '0' COMMENT '星座',
  `address` text,
  `status` tinyint(1) DEFAULT '0',
  `createTime` int(11) DEFAULT '0',
  `money` float(32,3) DEFAULT '0.000' COMMENT '个人对家族的收益',
  PRIMARY KEY (`id`),
  KEY `Index_uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='签约主播表';

-- ----------------------------
-- Table structure for pre_anchor_configs
-- ----------------------------
DROP TABLE IF EXISTS `pre_anchor_configs`;
CREATE TABLE `pre_anchor_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  `higher` bigint(32) DEFAULT NULL,
  `lower` bigint(32) DEFAULT NULL,
  `level` tinyint(3) DEFAULT NULL,
  `roomLimitNum` int(11) DEFAULT '0' COMMENT '房间上限人数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='主播配置表';

-- ----------------------------
-- Table structure for pre_base_configs
-- ----------------------------
DROP TABLE IF EXISTS `pre_base_configs`;
CREATE TABLE `pre_base_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='pre_base_configs';

-- ----------------------------
-- Table structure for pre_type_config
-- ----------------------------
DROP TABLE IF EXISTS `pre_type_config`;
CREATE TABLE `pre_type_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL,
  `typeId` int(11) DEFAULT NULL,
  `parentTypeId` int(11) DEFAULT NULL,
  `createTime` int(11) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `roomAnimate` tinyint(3) DEFAULT '0' COMMENT '主要用在座驾上，拥有座驾进房间之后，是否有大的座驾广播',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='pre_type_config';

-- ----------------------------
-- Table structure for pre_car_configs
-- ----------------------------
DROP TABLE IF EXISTS `pre_car_configs`;
CREATE TABLE `pre_car_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typeId` tinyint(3) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `price` int(11) DEFAULT NULL,
  `orderType` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `configName` varchar(20) NOT NULL COMMENT '配置名称，索引图片别名用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='座驾配置表';

-- ----------------------------
-- Table structure for pre_consume_log
-- ----------------------------
DROP TABLE IF EXISTS `pre_consume_log`;
CREATE TABLE `pre_consume_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `anchorId` int(11) DEFAULT NULL,
  `familyId` int(11) DEFAULT NULL,
  `amount` float(32,3) DEFAULT NULL,
  `createTime` int(11) DEFAULT NULL,
  `income` float(32,3) DEFAULT 0 COMMENT '收益记录',
  PRIMARY KEY (`id`),
  KEY `Index_uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `pre_family`
-- ----------------------------
DROP TABLE IF EXISTS `pre_family`;
CREATE TABLE `pre_family` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `shortName` varchar(100) DEFAULT NULL,
  `announcement` varchar(255) DEFAULT NULL,
  `description` text,
  `logo` varchar(100) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `createTime` int(11) DEFAULT NULL,
  `creatorUid` int(11) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `companyName` varchar(100) DEFAULT NULL COMMENT '公司地址',
  `settlementDate` tinyint(2) DEFAULT 10 COMMENT '结算日',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='pre_family';

-- ----------------------------
-- Table structure for pre_fans_configs
-- ----------------------------
DROP TABLE IF EXISTS `pre_fans_configs`;
CREATE TABLE `pre_fans_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  `higher` bigint(32) DEFAULT NULL,
  `lower` bigint(32) DEFAULT NULL,
  `level` tinyint(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='粉丝配置表';

-- ----------------------------
-- Table structure for pre_food_configs
-- ----------------------------
DROP TABLE IF EXISTS `pre_food_configs`;
CREATE TABLE `pre_food_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typeId` tinyint(3) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `price` int(11) DEFAULT NULL,
  `orderType` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='酒水配置表';

-- ----------------------------
-- Table structure for pre_gift_configs
-- ----------------------------
DROP TABLE IF EXISTS `pre_gift_configs`;
CREATE TABLE `pre_gift_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vipLevel` tinyint(3) DEFAULT '0',
  `richerLevel` tinyint(3) DEFAULT '0',
  `typeId` tinyint(3) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `coin` int(11) DEFAULT NULL,
  `cash` int(11) DEFAULT NULL,
  `recvCoin` int(8) DEFAULT NULL,
  `discount` tinyint(1) DEFAULT NULL,
  `freeCount` tinyint(1) DEFAULT NULL,
  `littleFlag` tinyint(1) DEFAULT NULL,
  `orderType` int(11) DEFAULT NULL,
  `createTime` int(11) DEFAULT NULL,
  `configName` varchar(20) NOT NULL COMMENT '配置名称，索引图片别名用',
  `guardFlag` tinyint(1) DEFAULT '0' COMMENT '是否需要是守护',
  `description` varchar(50) DEFAULT NULL COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='礼物配置表';

-- ----------------------------
-- Table structure for `pre_gift_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_gift_log`;
CREATE TABLE `pre_gift_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `giftId` int(11) DEFAULT NULL,
  `count` int(11) DEFAULT NULL,
  `consumeLogId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Index_giftId` (`giftId`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='礼物日志表';

-- ----------------------------
-- Table structure for pre_grabseat_log
-- ----------------------------
DROP TABLE IF EXISTS `pre_grabseat_log`;
CREATE TABLE `pre_grabseat_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `anchorUid` int(11) DEFAULT NULL,
  `seatUid` int(11) DEFAULT NULL,
  `seatPos` int(11) DEFAULT NULL,
  `seatCount` int(11) DEFAULT NULL,
  `updateTime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Index_anchorUid` (`anchorUid`),
  KEY `Index_seatUid` (`seatUid`),
  KEY `Index_seatPos` (`seatPos`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='pre_grabseat_log';

-- ----------------------------
-- Table structure for `pre_guard_configs`
-- ----------------------------
DROP TABLE IF EXISTS `pre_guard_configs`;
CREATE TABLE `pre_guard_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `level` tinyint(3) DEFAULT NULL,
  `carId` int(11) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='守护配置表';

-- ----------------------------
-- Table structure for pre_notice_configs
-- ----------------------------
DROP TABLE IF EXISTS `pre_notice_configs`;
CREATE TABLE `pre_notice_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `contents` text,
  `image` varchar(255) DEFAULT NULL,
  `createTime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for pre_online_gift
-- ----------------------------
DROP TABLE IF EXISTS `pre_online_gift`;
CREATE TABLE `pre_online_gift` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `type` tinyint(3) DEFAULT NULL,
  `leftCount` int(11) DEFAULT NULL,
  `count` int(11) DEFAULT NULL,
  `updateTime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Index_uid` (`uid`),
  KEY `Index_type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='pre_online_gift';

-- ----------------------------
-- Table structure for pre_richer_configs
-- ----------------------------
DROP TABLE IF EXISTS `pre_richer_configs`;
CREATE TABLE `pre_richer_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  `higher` bigint(32) DEFAULT NULL,
  `lower` bigint(32) DEFAULT NULL,
  `level` tinyint(3) DEFAULT NULL,
  `carId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='富豪配置表';

-- ----------------------------
-- Table structure for pre_rooms
-- ----------------------------
DROP TABLE IF EXISTS `pre_rooms`;
CREATE TABLE `pre_rooms` (
  `roomId` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `isRecommend` tinyint(1) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `announcement` varchar(100) DEFAULT NULL,
  `publicTime` int(11) DEFAULT NULL,
  `syncTime` int(11) DEFAULT NULL,
  `liveStatus` tinyint(1) DEFAULT NULL,
  `poster` varchar(255) DEFAULT NULL COMMENT '海报路径',
  `onlineNum` int(11) DEFAULT '0' COMMENT '房间在线人数',
  `showStatus` tinyint(1) DEFAULT '1' COMMENT '是否显示 1显示 0不显示',
  `robotNum` int(11) DEFAULT '0' COMMENT '机器人人数',
  PRIMARY KEY (`roomId`),
  UNIQUE KEY `Index_uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='pre_rooms';

-- ----------------------------
-- Table structure for pre_room_user_status
-- ----------------------------
DROP TABLE IF EXISTS `pre_room_user_status`;
CREATE TABLE `pre_room_user_status` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `roomId` int(8) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `level` tinyint(3) DEFAULT NULL,
  `forbid` tinyint(1) DEFAULT NULL,
  `kick` tinyint(1) DEFAULT NULL,
  `createTime` int(11) DEFAULT NULL,
  `kickTimeLine` int(11) DEFAULT NULL,
  `levelTimeLine` int(11) DEFAULT NULL,
  `hisRemarks` varchar(7) DEFAULT NULL COMMENT '自己的备注',
  `remarks` varchar(7) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`),
  KEY `Index_roomId` (`roomId`),
  KEY `Index_uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='pre_room_user_status';

-- ----------------------------
-- Table structure for pre_users
-- ----------------------------
DROP TABLE IF EXISTS `pre_users`;
CREATE TABLE `pre_users` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `accountId` varchar(100) NOT NULL,
  `userName` varchar(100) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `createTime` int(11) DEFAULT NULL,
  `updateTime` int(11) DEFAULT NULL,
  `userType` tinyint(1) NOT NULL,
  `internalType` tinyint(1) DEFAULT '0' COMMENT '0-普通用户,1-托用户',
  `isChatRecord` tinyint(1) DEFAULT '0' COMMENT '是否记录聊天信息',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `Index_acountId` (`uid`),
  KEY `Index_useType` (`userType`)
) ENGINE=MyISAM  AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8 COMMENT='账号表，用户的基本账号信息，基本不改变';

-- ----------------------------
-- Table structure for pre_user_info
-- ----------------------------
DROP TABLE IF EXISTS `pre_user_info`;
CREATE TABLE `pre_user_info` (
  `uid` int(11) NOT NULL,
  `nickName` varchar(50) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `gender` tinyint(1) DEFAULT NULL,
  `birthday` int(11) DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='pre_user_info';

-- ----------------------------
-- Table structure for pre_user_item
-- ----------------------------
DROP TABLE IF EXISTS `pre_user_item`;
CREATE TABLE `pre_user_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `itemType` varchar(10) NOT NULL,
  `itemId` int(11) NOT NULL,
  `itemCount` int(11) DEFAULT NULL,
  `itemExpireTime` int(11) DEFAULT NULL,
  `createTime` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Index_item` (`uid`,`itemId`,`itemType`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='pre_user_item';

-- ----------------------------
-- Table structure for pre_user_profiles
-- ----------------------------
DROP TABLE IF EXISTS `pre_user_profiles`;
CREATE TABLE `pre_user_profiles` (
  `uid` int(11) NOT NULL,
  `coin` bigint(32) DEFAULT NULL COMMENT '聊豆',
  `cash` float(32,3) DEFAULT NULL COMMENT '聊币',
  `money` float(32,3) DEFAULT NULL COMMENT '收益',
  `exp1` bigint(32) DEFAULT NULL COMMENT 'VIP经验值',
  `exp2` bigint(32) DEFAULT NULL COMMENT '主播经验值',
  `exp3` bigint(32) DEFAULT NULL COMMENT '富豪经验值',
  `exp4` bigint(32) DEFAULT NULL COMMENT '粉丝经验值',
  `exp5` bigint(32) DEFAULT NULL COMMENT '魅力值',
  `level1` tinyint(3) DEFAULT NULL COMMENT 'VIP等级',
  `level2` tinyint(3) DEFAULT NULL COMMENT '主播等级',
  `level3` tinyint(3) DEFAULT NULL COMMENT '富豪等级',
  `level4` tinyint(3) DEFAULT NULL COMMENT '粉丝等级',
  `level5` tinyint(3) DEFAULT NULL COMMENT '魅力等级',
  `level6` tinyint(3) DEFAULT NULL COMMENT '至尊vip',
  `vipExpireTime` int(11) DEFAULT NULL,
  `vipExpireTime2` int(11) DEFAULT NULL,
  `questionId` smallint(4) DEFAULT '0' COMMENT '安全问题',
  `answer` varchar(30) DEFAULT '' COMMENT '问题答案',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='pre_user_profiles';

-- ----------------------------
-- Table structure for pre_vip_configs
-- ----------------------------
DROP TABLE IF EXISTS `pre_vip_configs`;
CREATE TABLE `pre_vip_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level` tinyint(3) DEFAULT NULL,
  `lower` bigint(32) DEFAULT NULL COMMENT '当前vip等级最低经验值',
  `higher` bigint(32) DEFAULT NULL COMMENT '当前vip等级最高经验值',
  `description` text,
  `carId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='pre_vip_configs';

-- ----------------------------
-- Table structure for pre_guard_list
-- ----------------------------
DROP TABLE IF EXISTS `pre_guard_list`;
CREATE TABLE `pre_guard_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guardUid` int(11) DEFAULT NULL,
  `beGuardedUid` int(11) DEFAULT NULL,
  `guardLevel` tinyint(3) DEFAULT NULL,
  `addTime` int(11) DEFAULT NULL,
  `expireTime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Index_guardUid` (`guardUid`),
  KEY `Index_beGuardedUid` (`beGuardedUid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `pre_rank_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_rank_log`;
CREATE TABLE `pre_rank_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text,
  `index` tinyint(1) DEFAULT NULL,
  `lastTime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `pre_family_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_family_log`;
CREATE TABLE `pre_family_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `joinTime` int(11) DEFAULT NULL,
  `outOfTime` int(11) DEFAULT NULL,
  `familyId` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL COMMENT '0:家族进出记录，1:当前所处家族。',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for `pre_apply_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_apply_log`;
CREATE TABLE `pre_apply_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '申请者ID',
  `targetId` int(11) DEFAULT NULL COMMENT '目标id',
  `description` varchar(255) DEFAULT NULL COMMENT '描述',
  `type` tinyint(1) DEFAULT NULL COMMENT '类型：加入家族申请，签约申请，家族申请',
  `createTime` int(11) DEFAULT NULL COMMENT '申请时间',
  `status` tinyint(1) DEFAULT NULL COMMENT '申请状态：申请中、同意、拒绝',
  `auditUser` varchar(20) DEFAULT '' COMMENT '审核人',
  `auditTime` int(11) DEFAULT '0' COMMENT '审核时间',
  `reason` varchar(100) DEFAULT '' COMMENT '拒绝理由',
  `isRead` tinyint(1) DEFAULT '1' COMMENT '用户是否已读 0未读 1已读',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户申请表';

-- ----------------------------
-- Table structure for `pre_order`
-- ----------------------------
DROP TABLE IF EXISTS `pre_order`;
CREATE TABLE `pre_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户id',
  `orderId` varchar(30) NOT NULL DEFAULT '' COMMENT '订单号',
  `createTime` int(11) NOT NULL DEFAULT '0' COMMENT '下单时间',
  `cashNum`  int(11) DEFAULT '0' COMMENT '聊币数量',
  `totalFee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '金额（单位元）',
  `status` tinyint(1) DEFAULT '0' COMMENT '订单状态',
  `payType` smallint(4) NOT NULL DEFAULT '0' COMMENT '支付类型',
  `payTime` int(11) DEFAULT '0' COMMENT '支付成功时间',
  `tradeNo` varchar(50) DEFAULT '' COMMENT '交易流水号',
  PRIMARY KEY (`id`),
  KEY `orderId` (`orderId`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `pre_cash_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_cash_log`;
CREATE TABLE `pre_cash_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `num` int(11) NOT NULL DEFAULT '0' COMMENT '聊币数量',
  `source` smallint(4) NOT NULL DEFAULT '0' COMMENT '钱币来源',
  `createTime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `orderId` varchar(30) CHARACTER SET latin1 NOT NULL DEFAULT '' COMMENT '订单号',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `pre_user_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_user_log`;
CREATE TABLE `pre_user_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `roomId` int(11) DEFAULT NULL,
  `updateTime` int(11) DEFAULT NULL COMMENT '用户访问该房间的最后时间',
  `count` int(11) DEFAULT NULL COMMENT '用户访问该房间次数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `pre_room_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_room_log`;
CREATE TABLE `pre_room_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roomId` int(11) DEFAULT NULL,
  `publicTime` int(11) DEFAULT NULL COMMENT '该场次、开播时间',
  `count` int(11) DEFAULT NULL COMMENT '当前开播场次、被有效访问次数',
  `endTime` int(11) DEFAULT NULL COMMENT '该场次、关播时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_activities_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_activities_log`;
CREATE TABLE `pre_activities_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '用户id',
  `activityId` smallint(4) DEFAULT '0' COMMENT '活动id 写在配置文件中',
  `expireTime` int(11) DEFAULT '0' COMMENT '领取 过期时间',
  `status` tinyint(1) DEFAULT '0' COMMENT '领取状态 1:可领取 2:已领取',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `pre_task`
-- ----------------------------
DROP TABLE IF EXISTS `pre_task`;
CREATE TABLE `pre_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `taskId` int(11) DEFAULT NULL COMMENT '任务id',
  `taskName` varchar(30) DEFAULT '' COMMENT '任务名称',
  `taskDes` varchar(255) DEFAULT '' COMMENT '任务描述',
  `taskType` smallint(4) DEFAULT '1' COMMENT '任务类型：新手任务、日常任务',
  `taskReward` int(11) DEFAULT '0' COMMENT '任务报酬 ',
  `taskSort` smallint(4) DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) DEFAULT '1' COMMENT '任务状态',
  `type` tinyint(1) DEFAULT '1' COMMENT '子任务类型：普通任务、首充任务',
  `rewardType` tinyint(1) DEFAULT '1' COMMENT '奖励类型：聊币、聊豆',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `pre_task_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_task_log`;
CREATE TABLE `pre_task_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT '用户id',
  `taskId` int(11) DEFAULT NULL COMMENT '任务id',
  `status` tinyint(1) DEFAULT '0' COMMENT '任务完成状态',
  `finishRate` int(11) DEFAULT '0' COMMENT '完成进度',
  `finishTime` int(11) DEFAULT '0' COMMENT '完成时间',
  `receiveTime` int(11) DEFAULT '0' COMMENT '领取时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `pre_activities_share`
-- ----------------------------
DROP TABLE IF EXISTS `pre_activities_share`;
CREATE TABLE `pre_activities_share` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT '用户id',
  `anchorId` int(11) DEFAULT NULL COMMENT '主播id',
  `type` tinyint(1) DEFAULT NULL COMMENT '分享类型',
  `createTime` int(11) DEFAULT NULL COMMENT '分享时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `pre_question_configs`
-- ----------------------------
DROP TABLE IF EXISTS `pre_question_configs`;
CREATE TABLE `pre_question_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '安全问题内容',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET FOREIGN_KEY_CHECKS=0;

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_user_photo`
-- ----------------------------
DROP TABLE IF EXISTS `pre_user_photo`;
CREATE TABLE `pre_user_photo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT '用户id',
  `photoUrl` varchar(100) CHARACTER SET latin1 DEFAULT '' COMMENT '照片地址',
  `type` smallint(4) DEFAULT '0' COMMENT '类型：生活照、证件照',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户上传照片表';

-- ----------------------------
-- Table structure for pre_car_log
-- ----------------------------
DROP TABLE IF EXISTS `pre_car_log`;
CREATE TABLE `pre_car_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `carId` int(11) DEFAULT NULL,
  `consumeLogId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for pre_grab_log
-- ----------------------------
DROP TABLE IF EXISTS `pre_grab_log`;
CREATE TABLE `pre_grab_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `seatPos` int(11) DEFAULT NULL,
  `count` int(11) DEFAULT NULL,
  `consumeLogId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for pre_guard_log
-- ----------------------------
DROP TABLE IF EXISTS `pre_guard_log`;
CREATE TABLE `pre_guard_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guardType` int(11) DEFAULT NULL,
  `consumeLogId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `pre_user_information`
-- ----------------------------
DROP TABLE IF EXISTS `pre_user_information`;
CREATE TABLE `pre_user_information` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT '用户id',
  `content` varchar(500) DEFAULT '' COMMENT '通知内容',
  `type` smallint(4) DEFAULT '0' COMMENT '通知类型：消息、申请、审批',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态：0未读 1已读',
  `createTime` int(11) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户通知表';

-- ----------------------------
-- Table structure for pre_record_chat
-- ----------------------------
DROP TABLE IF EXISTS `pre_record_chat`;
CREATE TABLE `pre_record_chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `roomId` int(11) DEFAULT NULL,
  `chatData` varchar(512) DEFAULT NULL COMMENT '聊天信息',
  `createTime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='聊天记录表';


-- ----------------------------
-- Table structure for `pre_sign`
-- ----------------------------
DROP TABLE IF EXISTS `pre_sign`;
CREATE TABLE `pre_sign` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT '用户id',
  `month` int(11) DEFAULT '0' COMMENT '月份',
  `type` smallint(4) DEFAULT '0' COMMENT '类型',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态 1可领取 2已领取',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for `pre_sign_configs`
-- ----------------------------
DROP TABLE IF EXISTS `pre_sign_configs`;
CREATE TABLE `pre_sign_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) DEFAULT '1' COMMENT '签到类型：1：累计签到 2：连续签到',
  `desc` varchar(100) DEFAULT '' COMMENT '描述',
  `daysNum` int(11) DEFAULT '0' COMMENT '签到天数',
  `package` varchar(255) DEFAULT '' COMMENT '礼包配置：【用户类型，礼包id】',
  `validity` int(11) DEFAULT '0' COMMENT '有效期：单位秒。0为永久',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='签到配置表 ';


-- ----------------------------
-- Table structure for `pre_sign_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_sign_log`;
CREATE TABLE `pre_sign_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `createTime` int(11) NOT NULL DEFAULT '0' COMMENT '签到时间',
  `conTimes` int(11) NOT NULL DEFAULT '1' COMMENT '本月持续签到次数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



-- ----------------------------
-- Table structure for `pre_item_configs`
-- ----------------------------
DROP TABLE IF EXISTS `pre_item_configs`;
CREATE TABLE `pre_item_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` smallint(4) DEFAULT '1' COMMENT '类型：喇叭、徽章',
  `name` varchar(30) DEFAULT '' COMMENT '物品名称',
  `description` varchar(100) DEFAULT '' COMMENT '描述',
  `cash` float(32,3) DEFAULT '0.000' COMMENT '聊币',
  `configName` varchar(20) DEFAULT '' COMMENT '配置名称，索引图片别名用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='物品配置表';

-- ----------------------------
-- Table structure for `pre_room_gift_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_room_gift_log`;
CREATE TABLE `pre_room_gift_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roomId` int(11) DEFAULT '0' COMMENT '房间id',
  `type` smallint(4) DEFAULT '0' COMMENT '类型：1礼物、2抢座、3魅力星',
  `uid` int(11) DEFAULT '0' COMMENT '用户id',
  `giftId` int(11) DEFAULT '0' COMMENT '礼物id',
  `configName` varchar(30) DEFAULT '',
  `giftNum` int(11) DEFAULT '0' COMMENT '礼物数量',
  `giftName` varchar(30) DEFAULT NULL COMMENT '礼物名称',
  `price` int(11) DEFAULT '0' COMMENT '价钱',
  `priceType` tinyint(1) DEFAULT '1' COMMENT '价钱类型 1:聊币 2聊豆',
  `createTime` int(11) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='直播间送礼日志-用于进入直播间时显示送礼记录';


-- ----------------------------
-- Table structure for `pre_gift_package_configs`
-- ----------------------------
DROP TABLE IF EXISTS `pre_gift_package_configs`;
CREATE TABLE `pre_gift_package_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT '' COMMENT '礼包名称',
  `desc` varchar(255) DEFAULT '' COMMENT '礼包描述',
  `items` varchar(255) DEFAULT '' COMMENT '【物品类型，物品id，物品数量,有效期】',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='礼包配置表';

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_room_admin_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_room_admin_log`;
CREATE TABLE `pre_room_admin_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `operateUid` int(11) NOT NULL DEFAULT '0' COMMENT '操作者的id',
  `roomId` int(11) NOT NULL DEFAULT '0' COMMENT '房间id',
  `type` smallint(4) NOT NULL DEFAULT '0' COMMENT '操作类型：1禁言、2踢人、3设管理、',
  `beOperateUid` int(11) NOT NULL DEFAULT '0' COMMENT '被操作的用户id',
  `createTime` int(11) NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='直播间超级管理员操作日志';

-- ----------------------------
-- Table structure for `pre_visit_count_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_visit_count_log`;
CREATE TABLE `pre_visit_count_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) DEFAULT NULL COMMENT '日期',
  `parentType` smallint(4) DEFAULT '0',
  `subType` smallint(4) DEFAULT '0',
  `visit` int(11) DEFAULT '0' COMMENT '访问数',
  PRIMARY KEY (`id`),
  KEY `date` (`date`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='访问统计表';


-- ----------------------------
-- Table structure for `pre_register_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_register_log`;
CREATE TABLE `pre_register_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parentType` smallint(4) DEFAULT '0',
  `subType` smallint(4) DEFAULT NULL,
  `uuid` varchar(50) DEFAULT '0',
  `uid` int(11) DEFAULT '0',
  `createTime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='注册日志表';

 
-- ----------------------------
-- Table structure for `pre_guest_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_guest_log`;
CREATE TABLE `pre_guest_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) DEFAULT '',
  `parentType` smallint(4) DEFAULT '0',
  `subType` smallint(4) DEFAULT NULL,
  `createTime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='游客记录';



-- ----------------------------
-- Table structure for `pre_login_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_login_log`;
CREATE TABLE `pre_login_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `createTime` int(11) DEFAULT '0',
  `ip` varchar(50) CHARACTER SET utf8 DEFAULT '' COMMENT 'ip地址',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `pre_user_info` CHANGE `signature` `signature` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '个人签名';

-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2015-06-03 13:59:58
-- 服务器版本： 5.6.21
-- PHP Version: 5.5.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `91ns`
--

-- --------------------------------------------------------

--
-- 表的结构 `pre_private_message`
--

CREATE TABLE IF NOT EXISTS `pre_private_message` (
`id` int(11) NOT NULL,
  `pcId` int(11) NOT NULL,
  `sendUid` int(11) NOT NULL,
  `toUid` int(11) NOT NULL,
  `content` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `isdel` int(11) NOT NULL,
  `addtime` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='私信表';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pre_private_message`
--
ALTER TABLE `pre_private_message`
 ADD PRIMARY KEY (`id`), ADD KEY `sendUid` (`sendUid`,`toUid`,`addtime`), ADD KEY `pcId` (`pcId`), ADD KEY `delete` (`isdel`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pre_private_message`
--
ALTER TABLE `pre_private_message`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2015-06-03 13:59:46
-- 服务器版本： 5.6.21
-- PHP Version: 5.5.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `91ns`
--

-- --------------------------------------------------------

--
-- 表的结构 `pre_privatemessage_config`
--

CREATE TABLE IF NOT EXISTS `pre_privatemessage_config` (
`id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `toUid` int(11) NOT NULL,
  `top` tinyint(1) NOT NULL DEFAULT '0',
  `shield` tinyint(1) NOT NULL DEFAULT '0',
  `lastTime` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='私信配置表';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pre_privatemessage_config`
--
ALTER TABLE `pre_privatemessage_config`
 ADD PRIMARY KEY (`id`), ADD KEY `uid` (`uid`,`lastTime`), ADD KEY `top` (`top`), ADD KEY `toUid` (`toUid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pre_privatemessage_config`
--
ALTER TABLE `pre_privatemessage_config`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
--
-- 表的结构 `pre_device_info`
--

CREATE TABLE IF NOT EXISTS `pre_device_info` (
  `uid` int(11) NOT NULL,
  `deviceid` varchar(255) NOT NULL,
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='设备信息表';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pre_device_info`
--
ALTER TABLE `pre_device_info`
 ADD PRIMARY KEY (`uid`);
 
 ALTER TABLE `pre_device_info` ADD `devicetoken` VARCHAR(255) NULL AFTER `deviceid`;
