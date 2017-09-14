

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `inv_rule`
-- ----------------------------
DROP TABLE IF EXISTS `inv_rule`;
CREATE TABLE `inv_rule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rids` varchar(20) DEFAULT '' COMMENT '规则id',
  `conditions` tinyint(1) DEFAULT '0' COMMENT '条件：1或 2且',
  `type` tinyint(1) DEFAULT '1' COMMENT '类型 :主播分成比例、家族分成比例、直播间加机器人规则',
  `value` int(11) DEFAULT '0' COMMENT '值',
  `conType` tinyint(1) DEFAULT '1' COMMENT '直播间加机器人配置规则类型：１代N、直接增加',
  `conValue` int(11) DEFAULT '0' COMMENT '直播间加机器人配置规则值',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of inv_rule
-- ----------------------------
INSERT INTO `inv_rule` VALUES ('10', '12,13', '2', '1', '45', '1', '0', null);
INSERT INTO `inv_rule` VALUES ('9', '11', '2', '1', '30', '1', '0', null);
INSERT INTO `inv_rule` VALUES ('13', '16', '2', '2', '30', '1', '0', null);
INSERT INTO `inv_rule` VALUES ('11', '14', '2', '1', '40', '1', '0', null);
INSERT INTO `inv_rule` VALUES ('12', '15', '2', '1', '45', '1', '0', null);
INSERT INTO `inv_rule` VALUES ('14', '17,18', '2', '2', '40', '1', '0', null);
INSERT INTO `inv_rule` VALUES ('15', '19,20', '2', '2', '45', '1', '0', null);
INSERT INTO `inv_rule` VALUES ('16', '21', '2', '2', '50', '1', '0', null);

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
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 COMMENT='客服后台模块表';

-- ----------------------------
-- Table structure for inv_rule_message
-- ----------------------------
DROP TABLE IF EXISTS `inv_rule_message`;
CREATE TABLE `inv_rule_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(2000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

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


-- ----------------------------
-- Table structure for `inv_operation_log`
-- ----------------------------
DROP TABLE IF EXISTS `inv_operation_log`;
CREATE TABLE `inv_operation_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(32) DEFAULT NULL COMMENT '登录用户名',
  `operaObject` varchar(30) DEFAULT '0' COMMENT '操作对象',
  `operaType` varchar(10) DEFAULT '0' COMMENT '操作类型',
  `operaDesc` varchar(50) DEFAULT '' COMMENT '操作描述',
  `createTime` int(11) DEFAULT '0' COMMENT '操作时间',
  `log1` varchar(100) DEFAULT '' COMMENT '操作前日志',
  `log2` varchar(100) DEFAULT '' COMMENT '操作后日志',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='客服后台操作';

-- ----------------------------
-- Records of inv_operation_log


-- ----------------------------
-- Table structure for `inv_role`
-- ----------------------------
DROP TABLE IF EXISTS `inv_role`;
CREATE TABLE `inv_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roleName` varchar(20) DEFAULT '' COMMENT '权限名称',
  `roleType` smallint(4) DEFAULT '0' COMMENT '权限类型 1：超级管理员 ',
  `roleModule` varchar(255) DEFAULT '' COMMENT '可操作的模块',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态 1：正常 2禁用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='客服后台权限表';

-- ----------------------------
-- Records of inv_role
-- ----------------------------
INSERT INTO `inv_role` VALUES ('1', '超级管理员', '1', '', '1');
INSERT INTO `inv_role` VALUES ('2', '管理员', '2', '', '1');

-- ----------------------------
-- Table structure for `inv_rule_log`
-- ----------------------------
DROP TABLE IF EXISTS `inv_rule_log`;
CREATE TABLE `inv_rule_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `symbol` smallint(4) DEFAULT '1' COMMENT '符号 =、>、>=、<、<=',
  `value` int(11) DEFAULT '0' COMMENT '值',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of inv_rule_log
-- ----------------------------
INSERT INTO `inv_rule_log` VALUES ('13', '4', '15');
INSERT INTO `inv_rule_log` VALUES ('12', '3', '13');
INSERT INTO `inv_rule_log` VALUES ('11', '4', '13');
INSERT INTO `inv_rule_log` VALUES ('16', '4', '200000');
INSERT INTO `inv_rule_log` VALUES ('15', '3', '16');
INSERT INTO `inv_rule_log` VALUES ('14', '1', '15');
INSERT INTO `inv_rule_log` VALUES ('17', '3', '200000');
INSERT INTO `inv_rule_log` VALUES ('18', '4', '350000');
INSERT INTO `inv_rule_log` VALUES ('19', '3', '350000');
INSERT INTO `inv_rule_log` VALUES ('20', '4', '500000');
INSERT INTO `inv_rule_log` VALUES ('21', '2', '500000');


-- ----------------------------
-- Table structure for `inv_user`
-- ----------------------------
DROP TABLE IF EXISTS `inv_user`;
CREATE TABLE `inv_user` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `userName` varchar(20) DEFAULT '' COMMENT '用户名',
  `password` varchar(50) DEFAULT '' COMMENT '密码',
  `picture` varchar(100) DEFAULT '' COMMENT '照片',
  `roleId` smallint(4) DEFAULT '0' COMMENT '角色权限',
  `createTime` int(11) DEFAULT '0' COMMENT '创建时间',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态 1：正常 2禁用',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='客服后台用户表';

-- ----------------------------
-- Records of inv_user
-- ----------------------------
INSERT INTO `inv_user` VALUES ('1', 'admin', '0192023a7bbd73250516f069df18b500', '', '1', '1427169574', '1');
 

 
-- ----------------------------
-- Table structure for `inv_user_exception`
-- ----------------------------
DROP TABLE IF EXISTS `inv_user_exception`;
CREATE TABLE `inv_user_exception` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT '用户id',
  `value` int(11) DEFAULT '1' COMMENT '例外的值',
  `type` smallint(4) DEFAULT '0' COMMENT '例外类型 1：分成例外 2：兑换限制例外',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='用户例外表';

 
-- ----------------------------
-- Table structure for inv_basic_salary
-- ----------------------------
DROP TABLE IF EXISTS `inv_basic_salary`;
CREATE TABLE `inv_basic_salary` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '1',
  `type` bigint(20) DEFAULT NULL COMMENT '底薪类型',
  `money` float(32,2) DEFAULT NULL COMMENT '金额',
  `uid` int(11) DEFAULT NULL COMMENT '主播ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for inv_rule_message
-- ----------------------------
DROP TABLE IF EXISTS `inv_rule_message`;
CREATE TABLE `inv_rule_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(2000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


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
  `fid` int(11) DEFAULT '0' COMMENT '家族id',
  `uid` int(11) DEFAULT '0' COMMENT '主播id',
  `cash` float(32,3) DEFAULT '0.000' COMMENT '结算聊币',
  `rmb` float(32,3) DEFAULT '0.000' COMMENT '结算人民币',
  `basicSalary` float(32,3) DEFAULT '0.000' COMMENT '底薪',
  `auditUser` varchar(32) DEFAULT '' COMMENT '结算操作者',
  `auditTime` int(11) DEFAULT '0' COMMENT '结算时间',
  `settleType` tinyint(2) DEFAULT '0' COMMENT '结算方式1收益，2时长，3收益+时长',
  `remark` varchar(2000) DEFAULT '' COMMENT '备注',
  `auditImg` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '结算上传图片',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

 
-- ----------------------------
-- Table structure for `inv_accounts_notice`
-- ----------------------------
DROP TABLE IF EXISTS `inv_accounts_notice`;
CREATE TABLE `inv_accounts_notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mobile` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for inv_login_log
-- ----------------------------
DROP TABLE IF EXISTS `inv_login_log`;
CREATE TABLE `inv_login_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(32) DEFAULT NULL COMMENT '登录用户名',
  `ip` varchar(32) DEFAULT NULL,
  `loginType` tinyint(1) DEFAULT '0' COMMENT '登录类型：1登录2退出',
  `createTime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
  `basicSalary` float(32,3) DEFAULT '0.000' COMMENT '底薪',
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
  `stype` tinyint(2) DEFAULT '0' COMMENT '结算方式1收益，2时长，3收益+时长',
  `basicSalary` float(32,3) DEFAULT NULL COMMENT '某主播底薪',
  `rmb` float(32,3) DEFAULT NULL COMMENT '某主播分成',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
