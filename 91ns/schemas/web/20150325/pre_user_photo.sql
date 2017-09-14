/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-03-25 14:34:56
*/

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
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf32 COMMENT='用户上传照片表';

-- ----------------------------
-- Records of pre_user_photo
-- ----------------------------

