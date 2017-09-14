/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-03-25 14:34:47
*/

SET FOREIGN_KEY_CHECKS=0;

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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='用户申请表';

-- ----------------------------
-- Records of pre_apply_log
-- ----------------------------
 