/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : redpeck

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-01-22 10:12:09
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for department
-- ----------------------------
DROP TABLE IF EXISTS `department`;
CREATE TABLE `department` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of department
-- ----------------------------
INSERT INTO `department` VALUES ('2', '总办');
INSERT INTO `department` VALUES ('6', '服务端');
INSERT INTO `department` VALUES ('7', '策划部');
INSERT INTO `department` VALUES ('8', '美术部');
INSERT INTO `department` VALUES ('9', 'QA部');
INSERT INTO `department` VALUES ('10', '运营部');
INSERT INTO `department` VALUES ('11', '客服部');
INSERT INTO `department` VALUES ('12', 'web开发');
INSERT INTO `department` VALUES ('13', '客户端');
INSERT INTO `department` VALUES ('16', '市场部');

-- ----------------------------
-- Table structure for employ
-- ----------------------------
DROP TABLE IF EXISTS `employ`;
CREATE TABLE `employ` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `depId` int(11) NOT NULL,
  `isSpecial` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=165 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of employ
-- ----------------------------
INSERT INTO `employ` VALUES ('3', '黄灿鑫', '13', '1');
INSERT INTO `employ` VALUES ('4', '张祚柳', '2', '0');
INSERT INTO `employ` VALUES ('5', '郑宏楗', '11', '0');
INSERT INTO `employ` VALUES ('6', '赵文雍', '7', '0');
INSERT INTO `employ` VALUES ('7', '财务员', '2', '0');
INSERT INTO `employ` VALUES ('8', '容珊', '8', '0');
INSERT INTO `employ` VALUES ('9', '周晓林', '6', '0');
INSERT INTO `employ` VALUES ('10', '田青', '2', '0');
INSERT INTO `employ` VALUES ('11', '王宁', '7', '0');
INSERT INTO `employ` VALUES ('12', '郑晟', '8', '0');
INSERT INTO `employ` VALUES ('13', '林永尧', '6', '0');
INSERT INTO `employ` VALUES ('14', '肖珊珊', '9', '0');
INSERT INTO `employ` VALUES ('15', '田烨', '7', '0');
INSERT INTO `employ` VALUES ('16', '陈秀清', '2', '0');
INSERT INTO `employ` VALUES ('17', '王思维', '2', '0');
INSERT INTO `employ` VALUES ('18', '方建锋', '7', '0');
INSERT INTO `employ` VALUES ('19', '詹承宇', '7', '0');
INSERT INTO `employ` VALUES ('20', '陈曦', '11', '0');
INSERT INTO `employ` VALUES ('21', '林光海', '6', '0');
INSERT INTO `employ` VALUES ('22', '吴李伟', '7', '0');
INSERT INTO `employ` VALUES ('23', '管智渊', '9', '0');
INSERT INTO `employ` VALUES ('24', '翁礼强', '13', '0');
INSERT INTO `employ` VALUES ('25', '张鹏', '7', '0');
INSERT INTO `employ` VALUES ('26', '赵丹丹', '11', '0');
INSERT INTO `employ` VALUES ('27', '卢海燕', '8', '0');
INSERT INTO `employ` VALUES ('28', '陈凡生', '2', '0');
INSERT INTO `employ` VALUES ('29', '陈媛', '7', '0');
INSERT INTO `employ` VALUES ('30', '林斯超', '2', '0');
INSERT INTO `employ` VALUES ('31', '吴步广', '7', '0');
INSERT INTO `employ` VALUES ('32', '陈志忠', '8', '0');
INSERT INTO `employ` VALUES ('33', '林连帆', '2', '0');
INSERT INTO `employ` VALUES ('34', '张孝榕', '6', '0');
INSERT INTO `employ` VALUES ('35', '张伟', '9', '0');
INSERT INTO `employ` VALUES ('36', '骆斌杰', '13', '0');
INSERT INTO `employ` VALUES ('37', '吴婷', '2', '0');
INSERT INTO `employ` VALUES ('38', '李冰', '7', '0');
INSERT INTO `employ` VALUES ('39', '游翊晨', '11', '0');
INSERT INTO `employ` VALUES ('40', '王琳', '11', '0');
INSERT INTO `employ` VALUES ('41', '辛晓泉', '9', '0');
INSERT INTO `employ` VALUES ('42', '江辉', '9', '0');
INSERT INTO `employ` VALUES ('43', '方誉', '2', '0');
INSERT INTO `employ` VALUES ('44', '钟吓丽', '8', '0');
INSERT INTO `employ` VALUES ('45', '蔡纯', '7', '0');
INSERT INTO `employ` VALUES ('46', '陈琦', '6', '0');
INSERT INTO `employ` VALUES ('47', '运营主管', '10', '0');
INSERT INTO `employ` VALUES ('48', '赖娜', '7', '0');
INSERT INTO `employ` VALUES ('49', '黄淋', '13', '0');
INSERT INTO `employ` VALUES ('50', '白猛', '6', '0');
INSERT INTO `employ` VALUES ('51', '康晓烽', '9', '0');
INSERT INTO `employ` VALUES ('52', '陈晓忠', '9', '0');
INSERT INTO `employ` VALUES ('53', '王嘉勇', '11', '0');
INSERT INTO `employ` VALUES ('54', '刘欢', '2', '0');
INSERT INTO `employ` VALUES ('55', '薛小燕', '11', '0');
INSERT INTO `employ` VALUES ('56', '客服主管', '11', '0');
INSERT INTO `employ` VALUES ('57', '林文川', '9', '0');
INSERT INTO `employ` VALUES ('58', '王剑洪', '9', '0');
INSERT INTO `employ` VALUES ('59', '张梦婷', '11', '0');
INSERT INTO `employ` VALUES ('60', '程灵艳', '7', '0');
INSERT INTO `employ` VALUES ('61', '李强', '8', '0');
INSERT INTO `employ` VALUES ('62', '谢兴朋', '8', '0');
INSERT INTO `employ` VALUES ('63', '孙多文', '2', '0');
INSERT INTO `employ` VALUES ('64', '李雅茜', '7', '0');
INSERT INTO `employ` VALUES ('65', '郑宇超', '7', '0');
INSERT INTO `employ` VALUES ('66', '许国荣', '13', '0');
INSERT INTO `employ` VALUES ('67', '叶文乐', '13', '0');
INSERT INTO `employ` VALUES ('68', '吴友柱', '7', '0');
INSERT INTO `employ` VALUES ('69', '保洁员', '2', '0');
INSERT INTO `employ` VALUES ('70', '游楠舟', '7', '0');
INSERT INTO `employ` VALUES ('71', '陈堃', '8', '0');
INSERT INTO `employ` VALUES ('72', '张凯成', '8', '0');
INSERT INTO `employ` VALUES ('73', '林永蘸', '2', '0');
INSERT INTO `employ` VALUES ('74', '吴美珍', '2', '0');
INSERT INTO `employ` VALUES ('75', '郑振清', '6', '0');
INSERT INTO `employ` VALUES ('76', '陈梓睿', '7', '0');
INSERT INTO `employ` VALUES ('77', '陈浩洋', '7', '0');
INSERT INTO `employ` VALUES ('78', '秦羽茜', '7', '0');
INSERT INTO `employ` VALUES ('79', '许建斌', '12', '0');
INSERT INTO `employ` VALUES ('80', '陈恺', '7', '0');
INSERT INTO `employ` VALUES ('81', '汤晓森', '13', '0');
INSERT INTO `employ` VALUES ('82', '叶峰', '13', '0');
INSERT INTO `employ` VALUES ('83', '王曌影', '8', '0');
INSERT INTO `employ` VALUES ('84', '林毅', '6', '0');
INSERT INTO `employ` VALUES ('85', '王娟娟(旧）', '9', '0');
INSERT INTO `employ` VALUES ('86', '林翔宇', '8', '0');
INSERT INTO `employ` VALUES ('87', '陈恩敏', '6', '0');
INSERT INTO `employ` VALUES ('88', '叶文浩', '6', '0');
INSERT INTO `employ` VALUES ('89', '王丽萍', '8', '0');
INSERT INTO `employ` VALUES ('90', '邵长民', '9', '0');
INSERT INTO `employ` VALUES ('91', '许楠', '11', '0');
INSERT INTO `employ` VALUES ('92', '陈颖', '13', '0');
INSERT INTO `employ` VALUES ('93', '郑丹', '8', '0');
INSERT INTO `employ` VALUES ('94', '冯超群', '11', '0');
INSERT INTO `employ` VALUES ('95', '叶孝廷', '7', '0');
INSERT INTO `employ` VALUES ('96', '陈君', '13', '0');
INSERT INTO `employ` VALUES ('97', '汪月华', '2', '0');
INSERT INTO `employ` VALUES ('98', '陈勇', '11', '0');
INSERT INTO `employ` VALUES ('99', '张兆臻', '8', '0');
INSERT INTO `employ` VALUES ('100', '李书群', '7', '0');
INSERT INTO `employ` VALUES ('101', '闵晓轩', '13', '0');
INSERT INTO `employ` VALUES ('102', '加武鹏', '7', '0');
INSERT INTO `employ` VALUES ('103', '吴真', '8', '0');
INSERT INTO `employ` VALUES ('104', '黄长海', '11', '0');
INSERT INTO `employ` VALUES ('105', '夏宇航', '7', '0');
INSERT INTO `employ` VALUES ('106', '朱剑辉', '6', '0');
INSERT INTO `employ` VALUES ('107', '陈祥坤', '9', '0');
INSERT INTO `employ` VALUES ('108', '陈文旺', '2', '0');
INSERT INTO `employ` VALUES ('109', '黄建', '7', '0');
INSERT INTO `employ` VALUES ('110', '欧乐辉', '9', '0');
INSERT INTO `employ` VALUES ('111', '陈超', '13', '0');
INSERT INTO `employ` VALUES ('112', '陈诗兰', '2', '0');
INSERT INTO `employ` VALUES ('113', '江晖', '7', '0');
INSERT INTO `employ` VALUES ('114', '林晨', '8', '0');
INSERT INTO `employ` VALUES ('115', '邱庆元', '8', '0');
INSERT INTO `employ` VALUES ('116', '张健', '7', '0');
INSERT INTO `employ` VALUES ('117', '彭茂荣', '13', '0');
INSERT INTO `employ` VALUES ('118', '何君杰', '11', '0');
INSERT INTO `employ` VALUES ('119', '王涛', '2', '0');
INSERT INTO `employ` VALUES ('120', '王娟娟', '7', '0');
INSERT INTO `employ` VALUES ('121', '杨小花', '8', '0');
INSERT INTO `employ` VALUES ('122', '朱雄峰', '13', '0');
INSERT INTO `employ` VALUES ('123', '9楼保洁员', '2', '0');
INSERT INTO `employ` VALUES ('124', '林良奎', '8', '0');
INSERT INTO `employ` VALUES ('125', '张晰洁', '2', '0');
INSERT INTO `employ` VALUES ('126', '李淑青', '2', '0');
INSERT INTO `employ` VALUES ('127', '崔本凯', '13', '0');
INSERT INTO `employ` VALUES ('128', '赖苹苹', '2', '0');
INSERT INTO `employ` VALUES ('129', '戴郎达', '13', '0');
INSERT INTO `employ` VALUES ('130', '王星星', '8', '0');
INSERT INTO `employ` VALUES ('131', '陈悦', '2', '0');
INSERT INTO `employ` VALUES ('132', '薛尤升', '7', '0');
INSERT INTO `employ` VALUES ('133', '杨欢', '2', '0');
INSERT INTO `employ` VALUES ('134', '张玉玲', '2', '0');
INSERT INTO `employ` VALUES ('135', '刘金秀', '2', '0');
INSERT INTO `employ` VALUES ('136', '吴财贵', '13', '0');
INSERT INTO `employ` VALUES ('137', '林豪', '13', '0');
INSERT INTO `employ` VALUES ('138', '顾梦妮', '11', '0');
INSERT INTO `employ` VALUES ('139', '李斌', '2', '0');
INSERT INTO `employ` VALUES ('141', '陈樑森', '7', '0');
INSERT INTO `employ` VALUES ('142', '徐惠源', '9', '0');
INSERT INTO `employ` VALUES ('143', '杜唯毅', '7', '0');
INSERT INTO `employ` VALUES ('144', '陈凯', '7', '0');
INSERT INTO `employ` VALUES ('145', '游朝山', '8', '0');
INSERT INTO `employ` VALUES ('146', '陈俊阳', '9', '0');
INSERT INTO `employ` VALUES ('147', '石敏敏', '2', '0');
INSERT INTO `employ` VALUES ('148', '张婷婷', '9', '0');
INSERT INTO `employ` VALUES ('149', '陈燕斌', '2', '0');
INSERT INTO `employ` VALUES ('150', '林绍', '6', '0');
INSERT INTO `employ` VALUES ('151', '胡晓珊', '2', '0');
INSERT INTO `employ` VALUES ('152', '邱晓卿', '8', '0');
INSERT INTO `employ` VALUES ('153', '付健美', '2', '0');
INSERT INTO `employ` VALUES ('154', '肖雪平', '6', '0');
INSERT INTO `employ` VALUES ('155', '谢灵燕', '9', '0');
INSERT INTO `employ` VALUES ('156', '黄辉', '2', '0');
INSERT INTO `employ` VALUES ('157', '卢丽芳', '8', '0');
INSERT INTO `employ` VALUES ('158', '詹富伟', '8', '0');
INSERT INTO `employ` VALUES ('159', '饶智滨', '9', '0');
INSERT INTO `employ` VALUES ('160', '王家裕', '8', '0');
INSERT INTO `employ` VALUES ('161', '吴家炳', '13', '0');
INSERT INTO `employ` VALUES ('163', '陈彬', '7', '0');

-- ----------------------------
-- Table structure for redpack
-- ----------------------------
DROP TABLE IF EXISTS `redpack`;
CREATE TABLE `redpack` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `employid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `redlevel` int(255) NOT NULL,
  `time` int(11) NOT NULL,
  `prize` varchar(255) NOT NULL,
  `isGetStatus` int(255) NOT NULL,
  `nper` int(255) NOT NULL DEFAULT '0' COMMENT '期数',
  PRIMARY KEY (`id`),
  KEY `uk` (`employid`,`isGetStatus`,`nper`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1229 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of redpack
-- ----------------------------
