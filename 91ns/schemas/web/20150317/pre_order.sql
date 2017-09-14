/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-03-17 18:45:53
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_order`
-- ----------------------------
DROP TABLE IF EXISTS `pre_order`;
CREATE TABLE `pre_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户id',
  `orderId` varchar(30) NOT NULL DEFAULT '' COMMENT '订单号',
  `createTime` int(11) NOT NULL DEFAULT '0' COMMENT '下单时间',
  `totalFee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '金额（单位元）',
  `status` tinyint(1) DEFAULT '0' COMMENT '订单状态',
  `payType` smallint(4) NOT NULL DEFAULT '0' COMMENT '支付类型',
  `payTime` int(11) DEFAULT '0' COMMENT '支付成功时间',
  `tradeNo` varchar(50) DEFAULT '' COMMENT '交易流水号',
  PRIMARY KEY (`id`),
  KEY `orderId` (`orderId`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_order
-- ----------------------------
INSERT INTO `pre_order` VALUES ('1', '1', '20150317182952133610', '1426588192', '0.01', '0', '1', '0', '');
INSERT INTO `pre_order` VALUES ('2', '1', '20150317183110698053', '1426588270', '0.01', null, '1', null, null);
