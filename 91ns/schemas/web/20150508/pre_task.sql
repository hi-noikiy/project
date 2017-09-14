/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-05-08 18:04:21
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
  `taskReward` int(11) DEFAULT '0' COMMENT '任务报酬 ',
  `taskSort` smallint(4) DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) DEFAULT '1' COMMENT '任务状态',
  `type` tinyint(1) DEFAULT '1' COMMENT '子任务类型：普通任务、首充任务',
  `rewardType` tinyint(1) DEFAULT '1' COMMENT '奖励类型：聊币、聊豆',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_task
-- ----------------------------
INSERT INTO `pre_task` VALUES ('1', '1001', '修改昵称', '首次修改昵称', '1', '50', '1', '1', '1', '1');
INSERT INTO `pre_task` VALUES ('2', '1002', '送玫瑰', '送一朵玫瑰给主播吧', '1', '100', '2', '1', '1', '1');
INSERT INTO `pre_task` VALUES ('3', '1003', '绑定手机', '马上去“账户安全”里绑定手机', '1', '200', '3', '1', '1', '1');
INSERT INTO `pre_task` VALUES ('4', '1004', '修改头像', '成功修改一次头像', '1', '50', '4', '1', '1', '1');
INSERT INTO `pre_task` VALUES ('5', '1005', '安全问题', '去“账户安全”里设置安全问题', '1', '200', '5', '1', '1', '1');
INSERT INTO `pre_task` VALUES ('6', '1006', '连续观看', '在任意直播间连续观看40分钟', '1', '400', '6', '1', '1', '1');
INSERT INTO `pre_task` VALUES ('7', '1007', '第一次充值', '首次完成充值', '1', '10', '7', '1', '2', '1');
INSERT INTO `pre_task` VALUES ('8', '2001', '在线领奖', '每持续在线观看直播5分钟', '2', '25', '1', '1', '1', '2');
INSERT INTO `pre_task` VALUES ('9', '2002', '分享活动', '每天分享5次SNS（<font color=\"#00cc00\"><a href=\"/activities/share\" target=\"_blank\">规则详见分享活动</a></font>）', '2', '100', '2', '1', '1', '1');
INSERT INTO `pre_task` VALUES ('10', '2003', '送红玫瑰', '成功送出999朵红玫瑰', '2', '999', '3', '1', '1', '2');
INSERT INTO `pre_task` VALUES ('11', '2004', '魅力', '每天送出30个魅力', '2', '200', '4', '1', '1', '2');
INSERT INTO `pre_task` VALUES ('12', '2005', '抢座', '每天成功抢座10次', '2', '1000', '5', '1', '1', '2');
INSERT INTO `pre_task` VALUES ('13', '1008', '新手引导', '新手引导', '1', '50', '8', '0', '1', '1');
INSERT INTO `pre_task` VALUES ('14', '2006', '分享回链', '每天收到20个不同用户的分享回链（<font color=\"#00cc00\"><a href=\"/activities/share\" target=\"_blank\">规则详见分享活动</a></font>）', '2', '50', '6', '1', '1', '1');
