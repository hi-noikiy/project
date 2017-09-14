/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-05-25 20:33:41
*/

SET FOREIGN_KEY_CHECKS=0;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='访问统计表';

 