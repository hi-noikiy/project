/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.70
Source Server Version : 50539
Source Host           : 192.168.1.70:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50539
File Encoding         : 65001

Date: 2015-12-02 23:09:30
*/

SET FOREIGN_KEY_CHECKS=0;

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
  `taskReward` varchar(100) DEFAULT '' COMMENT '任务奖励',
  `taskSort` smallint(4) DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) DEFAULT '1' COMMENT '任务状态',
  `type` tinyint(1) DEFAULT '1' COMMENT '子任务类型：普通任务、首充任务',
  `rewardType` tinyint(1) DEFAULT '1' COMMENT '奖励类型：1聊币、2聊豆、3礼包、  0.其他 ',
  `sourceReward` varchar(500) DEFAULT '' COMMENT '渠道奖励',
  `showStatus` tinyint(1) DEFAULT '1' COMMENT '是否显示',
  PRIMARY KEY (`id`),
  KEY `taskId` (`taskId`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_task
-- ----------------------------
INSERT INTO `pre_task` VALUES ('1', '1001', '修改昵称', '首次修改昵称', '1', '50', '1', '0', '1', '2', '', '0');
INSERT INTO `pre_task` VALUES ('2', '1002', '送玫瑰', '送一朵玫瑰给主播吧', '1', '100', '2', '0', '1', '2', '', '0');
INSERT INTO `pre_task` VALUES ('3', '1003', '绑定手机', '马上去“账户安全”里绑定手机', '1', '50', '3', '1', '1', '2', '', '0');
INSERT INTO `pre_task` VALUES ('4', '1004', '修改头像', '成功修改一次头像', '1', '50', '4', '0', '1', '2', '', '0');
INSERT INTO `pre_task` VALUES ('5', '1005', '安全问题', '去“账户安全”里设置安全问题', '1', '200', '5', '0', '1', '2', '', '0');
INSERT INTO `pre_task` VALUES ('6', '1006', '连续观看', '在任意直播间连续观看40分钟', '1', '400', '6', '0', '1', '2', '', '0');
INSERT INTO `pre_task` VALUES ('7', '1007', '第一次充值', '首次完成充值', '1', '10', '7', '0', '2', '1', '', '0');
INSERT INTO `pre_task` VALUES ('8', '2001', '在线领奖', '每持续在线观看直播5分钟', '2', '25', '1', '0', '1', '2', '', '0');
INSERT INTO `pre_task` VALUES ('9', '2002', '分享活动', '累计分享5次SNS', '2', '100', '6', '1', '1', '2', '', '1');
INSERT INTO `pre_task` VALUES ('10', '2003', '送红玫瑰', '每天送出999朵红玫瑰', '2', '999', '3', '0', '1', '2', '', '0');
INSERT INTO `pre_task` VALUES ('11', '2004', '累计赠送魅力', '累计赠送10颗魅力星', '2', '0', '5', '1', '1', '0', '', '1');
INSERT INTO `pre_task` VALUES ('12', '2005', '抢沙发', '抢一次沙发', '2', '0', '4', '1', '1', '0', '', '1');
INSERT INTO `pre_task` VALUES ('13', '1008', '新手引导', '新手引导', '1', '24', '8', '1', '1', '3', '{\"qipaimi\":23}', '1');
INSERT INTO `pre_task` VALUES ('14', '2006', '分享SNS', '累计分享5次SNS', '2', '50', '6', '0', '1', '2', '', '0');
INSERT INTO `pre_task` VALUES ('15', '999', '普通vip礼物', '普通vip礼物', '2', '0', '0', '1', '1', '1', '', '0');
INSERT INTO `pre_task` VALUES ('16', '998', '至尊vip礼物', '至尊vip礼物', '2', '0', '0', '1', '1', '1', '', '0');
INSERT INTO `pre_task` VALUES ('17', '997', '渠道登录领送礼包', '渠道登录领送礼包', '1', '0', '0', '1', '1', '3', '', '0');
INSERT INTO `pre_task` VALUES ('18', '3001', '登录', '客官您好，\r\n很高兴为您服务！作为新手的灵魂导师，我会陪在您身边随时指导，并送上限量超值大礼作为奖励哦！\r\n50聊豆是见面礼，注册登录后即可获得！', '3', '25', '1', '1', '1', '3', '', '1');
INSERT INTO `pre_task` VALUES ('19', '3002', '修改昵称', '客官您好，\r\n您需要个酷炫的昵称来让主播记住现在我来教您如何修改它。\r\n修改昵称后我会继续为您服务~', '3', '26', '2', '1', '1', '3', '', '1');
INSERT INTO `pre_task` VALUES ('20', '3003', '与主播聊天', '下面我要教您如何与主播聊天，聊天完成后我会继续为您服务~', '3', '27', '3', '1', '1', '3', '', '1');
INSERT INTO `pre_task` VALUES ('21', '3004', '关注主播', '您还可以关注主播便于收到开播提醒哦！关注Ta后我会继续为您服务~', '3', '28', '4', '1', '1', '3', '', '1');
INSERT INTO `pre_task` VALUES ('22', '3005', '送礼', '下面我会教您如何送主播礼物，送礼完成后我会继续为您服务~', '3', '29', '5', '1', '1', '3', '', '1');
INSERT INTO `pre_task` VALUES ('23', '3006', '充值', '聊币不够怎么办？本次充值会有10%的额外奖励哦！（最多100000聊币）', '3', '10', '6', '0', '2', '1', '', '1');
INSERT INTO `pre_task` VALUES ('24', '2007', '累计看直播', '累计观看直播', '2', '0', '1', '1', '1', '0', '', '1');
INSERT INTO `pre_task` VALUES ('25', '2008', '累计发言', '直播间累计发言', '2', '0', '2', '1', '1', '0', '', '1');
INSERT INTO `pre_task` VALUES ('26', '2009', '累计送聊币礼物', '累计送出聊币礼物，包裹礼物不算', '2', '0', '3', '1', '1', '0', '', '1');
