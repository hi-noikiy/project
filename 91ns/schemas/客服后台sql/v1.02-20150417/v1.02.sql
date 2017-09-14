/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.23
Source Server Version : 50611
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50611
File Encoding         : 65001

Date: 2015-04-17 14:08:26
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for inv_login_log
-- ----------------------------
DROP TABLE IF EXISTS `inv_login_log`;
CREATE TABLE `inv_login_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '登录用户ID',
  `ip` varchar(32) DEFAULT NULL,
  `loginType` tinyint(1) DEFAULT '0' COMMENT '登录类型：1登录2退出',
  `createTime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- ----------------------------
-- Records of inv_login_log
-- ----------------------------
-- ----------------------------
-- Table structure for `inv_accounts`
-- ----------------------------
DROP TABLE IF EXISTS `inv_accounts`;
CREATE TABLE `inv_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `createTime` int(11) DEFAULT '0' COMMENT '申请结算时间',
  `applyUser` varchar(32) DEFAULT '' COMMENT '申请操作者',
  `peopleNum` int(11) DEFAULT '0' COMMENT '结算人数',
  `auditTime` int(11) DEFAULT '0' COMMENT '结算时间',
  `status` tinyint(1) DEFAULT '0' COMMENT '结算状态：0申请中、1已结算、2锁定',
  `lockUser` varchar(32) DEFAULT '' COMMENT '锁定结算的操作者',
  `auditUser` varchar(32) DEFAULT '' COMMENT '结算操作者',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='主播结算表';


-- ----------------------------
-- Table structure for `inv_accounts_log`
-- ----------------------------
DROP TABLE IF EXISTS `inv_accounts_log`;
CREATE TABLE `inv_accounts_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `accountId` int(11) DEFAULT '0' COMMENT '对应inv_accounts表的id',
  `uid` int(11) DEFAULT '0' COMMENT '主播id或家族id',
  `type` tinyint(1) DEFAULT '1' COMMENT '类型 1主播2家族',
  `cash` float(32,3) DEFAULT '0.000' COMMENT '结算聊币',
  `rmb` float(32,3) DEFAULT '0.000' COMMENT '结算人民币',
  `auditUser` varchar(32) CHARACTER SET latin1 DEFAULT '' COMMENT '结算操作者',
  `auditTime` int(11) DEFAULT '0' COMMENT '结算时间',
  `auditImg` varchar(255) DEFAULT '' COMMENT '结算上传图片',
  `status` tinyint(1) DEFAULT '0' COMMENT '结算状态 0申请中 1已结算',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for `inv_accounts_family_log`
-- ----------------------------
DROP TABLE IF EXISTS `inv_accounts_family_log`;
CREATE TABLE `inv_accounts_family_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `logId` int(11) DEFAULT '0' COMMENT '对应accounts_log表的id',
  `uid` int(11) DEFAULT '0',
  `cash` float(32,3) DEFAULT '0.000',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `inv_module`
-- ----------------------------
DROP TABLE IF EXISTS `inv_module`;
CREATE TABLE `inv_module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parentId` int(11) DEFAULT '0' COMMENT '父id',
  `moduleName` varchar(20) DEFAULT '' COMMENT '模块名称',
  `moduleAction` varchar(20) DEFAULT '' COMMENT '模块对应的程序动作',
  `moduleCss` varchar(20) DEFAULT '' COMMENT '前端样式',
  `moduleSort` int(11) DEFAULT '0' COMMENT '模块排序',
  `moduleDesc` varchar(30) DEFAULT '' COMMENT '模块描述',
  `moduleType` smallint(4) DEFAULT '0' COMMENT '模块类型 ',
  `createTime` int(11) DEFAULT '0' COMMENT '创建时间',
  `status` tinyint(1) DEFAULT '1' COMMENT '是否显示 1:显示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=utf8 COMMENT='客服后台模块表';

-- ----------------------------
-- Records of inv_module
-- ----------------------------
INSERT INTO `inv_module` VALUES ('1', '0', '主页', 'index', 'icon-home', '1', '', '0', '1427169574', '1');
INSERT INTO `inv_module` VALUES ('4', '0', '\"托\" 账号', 'pull', 'icon-th', '4', '', '0', '1427169574', '1');
INSERT INTO `inv_module` VALUES ('2', '0', '主播', 'anchor', 'icon-signal', '2', '', '0', '1427169574', '1');
INSERT INTO `inv_module` VALUES ('3', '0', '代理', 'agent', 'icon-fullscreen', '3', '', '0', '1427169574', '1');
INSERT INTO `inv_module` VALUES ('5', '0', '直播间', 'room', 'icon-fullscreen', '5', '', '0', '1427169574', '1');
INSERT INTO `inv_module` VALUES ('6', '0', '数据统计', 'statistics', 'icon-fullscreen', '6', '', '0', '1427169574', '1');
INSERT INTO `inv_module` VALUES ('7', '0', '系统设置', 'manage', 'icon-fullscreen', '7', '', '0', '1427169574', '0');
INSERT INTO `inv_module` VALUES ('8', '2', '已签约', 'sign', '', '1', '', '1', '1427169574', '1');
INSERT INTO `inv_module` VALUES ('9', '2', '未签约', 'noSign', '', '2', '', '1', '1427169574', '1');
INSERT INTO `inv_module` VALUES ('10', '2', '分成比例', 'bonus', '', '3', '', '1', '1427169574', '1');
INSERT INTO `inv_module` VALUES ('11', '2', '兑换限制', 'exchange', '', '4', '', '1', '1427169574', '1');
INSERT INTO `inv_module` VALUES ('12', '2', '签约申请', 'signapply', '', '5', '', '1', '1427169574', '1');
INSERT INTO `inv_module` VALUES ('14', '8', '修改', 'editSign', '', '1', '冻结、解冻主播，主播底薪', '2', '1427169574', '1');
INSERT INTO `inv_module` VALUES ('15', '8', '查看', 'checkSign', '', '2', '', '2', '1427169574', '1');
INSERT INTO `inv_module` VALUES ('16', '10', '新增', 'addBonus', '', '1', '添加规则、添加例外主播', '2', '1427169574', '1');
INSERT INTO `inv_module` VALUES ('17', '10', '修改', 'ditBonus', '', '2', '修改规则、修改例外主播', '2', '1427169574', '1');
INSERT INTO `inv_module` VALUES ('18', '10', '删除', 'delBonus', '', '3', '删除规则、删除例外主播', '2', '1427169574', '1');
INSERT INTO `inv_module` VALUES ('19', '10', '查看', 'checkBonus', '', '4', '', '2', '1427169574', '1');
INSERT INTO `inv_module` VALUES ('20', '11', '新增', 'addExchange', '', '1', '添加例外主播', '2', '1427169574', '1');
INSERT INTO `inv_module` VALUES ('21', '11', '修改', 'editExchange', '', '2', '修改兑换下限、修改例外主播', '2', '1427169574', '1');
INSERT INTO `inv_module` VALUES ('22', '11', '删除', 'delExchange', '', '3', '删除例外主播', '2', '1427169574', '1');
INSERT INTO `inv_module` VALUES ('23', '11', '查看', 'checkExchange', '', '4', '', '2', '1427169574', '1');
INSERT INTO `inv_module` VALUES ('24', '9', '查看', 'checkSign', '', '2', '', '2', '1427169574', '1');
INSERT INTO `inv_module` VALUES ('25', '9', '修改', 'editNoSign', '', '1', '冻结、解冻主播，主播底薪', '2', '1427169574', '1');
INSERT INTO `inv_module` VALUES ('26', '12', '修改', 'editSignApply', '', '1', '审批申请', '2', '1427169574', '1');
INSERT INTO `inv_module` VALUES ('27', '12', '查看', 'checkSignApply', '', '2', '', '2', '1427169574', '1');
INSERT INTO `inv_module` VALUES ('28', '0', '收益结算', 'settle', 'icon-fullscreen', '6', '', '1', '1427169574', '1');
INSERT INTO `inv_module` VALUES ('29', '28', '修改', 'editAccounts', '', '1', '收益的结算，结算表导出', '2', '1427169574', '1');
INSERT INTO `inv_module` VALUES ('30', '28', '查看', 'checkAccouts', '', '2', '', '2', '1427169574', '1');
INSERT INTO `inv_module` VALUES ('31', '3', '家族', 'family', '', '1', '', '1', '1427169574', '1');
INSERT INTO `inv_module` VALUES ('32', '3', '分成比例', 'familybonus', '', '2', '', '1', '1427169574', '1');
INSERT INTO `inv_module` VALUES ('33', '3', '创建申请', 'familyapply', '', '3', '', '1', '1427169574', '1');
INSERT INTO `inv_module` VALUES ('34', '5', '机器人', 'robot', '', '1', '', '1', '1427169574', '1');
INSERT INTO `inv_module` VALUES ('35', '5', '自动跳转', 'autoskip', '', '3', '', '1', '1427169574', '1');
INSERT INTO `inv_module` VALUES ('36', '5', '信息内容', 'content', '', '2', '', '1', '0', '1');
INSERT INTO `inv_module` VALUES ('37', '0', '日志管理', 'logsmag', 'icon-fullscreen', '8', '', '0', '0', '1');
INSERT INTO `inv_module` VALUES ('40', '37', '登录日志', 'loginlogs', '', '2', '', '1', '0', '1');
INSERT INTO `inv_module` VALUES ('39', '37', '操作日志', 'operlogs', '', '1', '', '1', '0', '1');
INSERT INTO `inv_module` VALUES ('41', '6', '消费趋势', 'consumer', '', '1', '', '1', '0', '1');
INSERT INTO `inv_module` VALUES ('42', '6', '畅销礼物', 'selling', '', '2', '', '1', '0', '1');
INSERT INTO `inv_module` VALUES ('47', '28', '结算提醒', 'settleremind', '', '4', '', '1', '0', '1');
INSERT INTO `inv_module` VALUES ('44', '28', '申请结算', 'index', '', '1', '', '1', '0', '1');
INSERT INTO `inv_module` VALUES ('45', '28', '已结算', 'settled', '', '3', '', '1', '0', '1');
INSERT INTO `inv_module` VALUES ('46', '28', '待结算', 'waitsettle', '', '2', '', '1', '0', '1');
INSERT INTO pre_base_configs (`key`,`value`) values('exchangeLimit','10000.000'); 
