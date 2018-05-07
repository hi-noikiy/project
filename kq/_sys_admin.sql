/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : kq

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-05-07 10:16:39
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for _sys_admin
-- ----------------------------
DROP TABLE IF EXISTS `_sys_admin`;
CREATE TABLE `_sys_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login_name` varchar(255) NOT NULL,
  `login_pass` varchar(255) NOT NULL,
  `gpid` int(11) NOT NULL,
  `depId` int(10) unsigned NOT NULL,
  `jobId` int(10) unsigned NOT NULL,
  `depMax` enum('0','1') NOT NULL DEFAULT '0',
  `real_name` varchar(255) NOT NULL,
  `unread` varchar(1000) NOT NULL,
  `seartag` enum('0','1') NOT NULL DEFAULT '0',
  `totalOverTime` int(11) NOT NULL,
  `reserve` int(11) NOT NULL,
  `card_id` int(10) unsigned NOT NULL,
  `inTime` int(11) DEFAULT '0' COMMENT '入职时间',
  PRIMARY KEY (`id`),
  KEY `gpid` (`gpid`)
) ENGINE=MyISAM AUTO_INCREMENT=552 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of _sys_admin
-- ----------------------------
INSERT INTO `_sys_admin` VALUES ('99', 'admin', '7a0260097c41fcc0f116e761f1026332', '1', '2', '8', '1', '管理员', '', '1', '0', '0', '0', null);
INSERT INTO `_sys_admin` VALUES ('112', 'huangcanxin', 'c4ca4238a0b923820dcc509a6f75849b', '100', '13', '24', '0', '黄灿鑫', '', '0', '1926', '0', '4561228', '20090301');
INSERT INTO `_sys_admin` VALUES ('483', 'zhangzuoliu', '900150983cd24fb0d6963f7d28e17f72', '100', '17', '22', '0', '张祚柳', '', '0', '2700', '0', '8511169', '20170626');
INSERT INTO `_sys_admin` VALUES ('474', 'zhenghongjian', 'e10adc3949ba59abbe56e057f20f883e', '100', '11', '20', '0', '郑宏楗', '', '0', '0', '0', '8463137', '20170503');
INSERT INTO `_sys_admin` VALUES ('106', 'caiwu', '43ef53b1891727ac1be58907b2740bc7', '1', '2', '7', '0', '财务员', '', '1', '0', '0', '0', '19951215');
INSERT INTO `_sys_admin` VALUES ('364', 'ronshan', 'e10adc3949ba59abbe56e057f20f883e', '100', '8', '16', '0', '容珊', '', '0', '5052', '90', '4561179', '20101018');
INSERT INTO `_sys_admin` VALUES ('116', 'zhouxiaolin', 'f3ef54b307289a64ef3a435135ede4cd', '100', '6', '11', '1', '周晓林', '', '0', '55350', '270', '11835713', '20081116');
INSERT INTO `_sys_admin` VALUES ('484', 'tianqing', 'c4ca4238a0b923820dcc509a6f75849b', '100', '17', '3', '0', '田青', '', '0', '180', '0', '1643319', '20170629');
INSERT INTO `_sys_admin` VALUES ('121', 'wangning', 'c4ca4238a0b923820dcc509a6f75849b', '100', '7', '13', '1', '王宁', '', '0', '0', '0', '4632923', '20090415');
INSERT INTO `_sys_admin` VALUES ('122', 'zhengsheng', 'c4ca4238a0b923820dcc509a6f75849b', '100', '8', '15', '1', '郑晟', '', '0', '216', '150', '4628818', '20090518');
INSERT INTO `_sys_admin` VALUES ('486', 'linyongyao', 'c4ca4238a0b923820dcc509a6f75849b', '100', '6', '12', '0', '林永尧', '', '0', '240', '0', '6504903', '20170710');
INSERT INTO `_sys_admin` VALUES ('437', 'xiaoshanshan', 'e10adc3949ba59abbe56e057f20f883e', '100', '9', '10', '0', '肖珊珊', '', '0', '30', '0', '4632934', '20160627');
INSERT INTO `_sys_admin` VALUES ('501', 'tianye', 'c4ca4238a0b923820dcc509a6f75849b', '100', '7', '14', '0', '田烨', '', '0', '1740', '570', '6533961', '20170908');
INSERT INTO `_sys_admin` VALUES ('328', 'chenxiuqing', 'e10adc3949ba59abbe56e057f20f883e', '100', '17', '3', '0', '陈秀清', '', '0', '0', '0', '4411533', '20140527');
INSERT INTO `_sys_admin` VALUES ('415', 'wangsiwei', 'e10adc3949ba59abbe56e057f20f883e', '100', '17', '3', '0', '王思维', '', '0', '750', '180', '9911174', '20160214');
INSERT INTO `_sys_admin` VALUES ('130', 'fangjianfeng', '43002eec333e6557ebbbf95d2e1a0adc', '100', '7', '14', '0', '方建锋', '', '0', '4800', '630', '5424088', '20100407');
INSERT INTO `_sys_admin` VALUES ('133', 'zhanchengyu', 'c4ca4238a0b923820dcc509a6f75849b', '100', '7', '14', '0', '詹承宇', '', '0', '11580', '660', '5320477', '20091014');
INSERT INTO `_sys_admin` VALUES ('468', 'chenxi', 'e10adc3949ba59abbe56e057f20f883e', '100', '11', '20', '0', '陈曦', '', '0', '0', '0', '8504127', '20170405');
INSERT INTO `_sys_admin` VALUES ('481', 'linguanghai', 'e10adc3949ba59abbe56e057f20f883e', '100', '6', '12', '0', '林光海', '', '0', '3930', '300', '6649359', '20170612');
INSERT INTO `_sys_admin` VALUES ('478', 'wuliwei', 'e10adc3949ba59abbe56e057f20f883e', '100', '7', '14', '0', '吴李伟', '', '0', '3330', '330', '1657781', '20170601');
INSERT INTO `_sys_admin` VALUES ('460', 'guanzhiyuan', 'e10adc3949ba59abbe56e057f20f883e', '100', '9', '10', '0', '管智渊', '', '0', '330', '30', '8503330', '20170102');
INSERT INTO `_sys_admin` VALUES ('384', 'wengliqiang', 'e10adc3949ba59abbe56e057f20f883e', '100', '13', '24', '1', '翁礼强', '', '0', '49080', '0', '11716298', '20150416');
INSERT INTO `_sys_admin` VALUES ('432', 'luhaiyan', 'e10adc3949ba59abbe56e057f20f883e', '100', '8', '16', '0', '卢海燕', '', '0', '210', '0', '5663841', '20160606');
INSERT INTO `_sys_admin` VALUES ('480', 'chenyuan', 'e10adc3949ba59abbe56e057f20f883e', '100', '7', '14', '0', '陈媛', '', '0', '1560', '630', '6514665', '20170605');
INSERT INTO `_sys_admin` VALUES ('454', 'linsichao', 'e10adc3949ba59abbe56e057f20f883e', '100', '17', '19', '0', '林斯超', '', '0', '3360', '480', '4931898', '2016126');
INSERT INTO `_sys_admin` VALUES ('357', 'wubuguang', 'e10adc3949ba59abbe56e057f20f883e', '100', '7', '14', '0', '吴步广', '', '0', '35010', '0', '11801514', '20140918');
INSERT INTO `_sys_admin` VALUES ('390', 'chenzhizhong', 'e10adc3949ba59abbe56e057f20f883e', '100', '8', '16', '0', '陈志忠', '', '0', '4560', '90', '4407496', '20150504');
INSERT INTO `_sys_admin` VALUES ('421', 'linlianfan', 'e10adc3949ba59abbe56e057f20f883e', '100', '10', '18', '0', '林连帆', '', '0', '1050', '180', '11949842', '20160407');
INSERT INTO `_sys_admin` VALUES ('396', 'zhangwei', 'e10adc3949ba59abbe56e057f20f883e', '100', '9', '10', '0', '张伟', '', '0', '0', '0', '4632920', null);
INSERT INTO `_sys_admin` VALUES ('246', 'luobinjie', 'e10adc3949ba59abbe56e057f20f883e', '100', '13', '24', '0', '骆斌杰', '', '0', '20880', '0', '11842522', '20120625');
INSERT INTO `_sys_admin` VALUES ('446', 'libing', 'e10adc3949ba59abbe56e057f20f883e', '100', '7', '14', '0', '李冰', '', '0', '0', '0', '4409700', null);
INSERT INTO `_sys_admin` VALUES ('456', 'youyichen', 'e10adc3949ba59abbe56e057f20f883e', '100', '11', '20', '0', '游翊晨', '', '0', '0', '0', '8502651', '20161219');
INSERT INTO `_sys_admin` VALUES ('465', 'xinxiaoquan', 'e10adc3949ba59abbe56e057f20f883e', '100', '9', '10', '0', '辛晓泉', '', '0', '360', '360', '2416542', '20170306');
INSERT INTO `_sys_admin` VALUES ('375', 'jianghui1', 'e10adc3949ba59abbe56e057f20f883e', '100', '9', '10', '0', '江辉', '', '0', '0', '0', '11810599', '20141222');
INSERT INTO `_sys_admin` VALUES ('466', 'fangyu', 'e10adc3949ba59abbe56e057f20f883e', '100', '17', '29', '0', '方誉', '', '0', '150', '150', '1708554', '20170314');
INSERT INTO `_sys_admin` VALUES ('419', 'zhongxiali', 'e10adc3949ba59abbe56e057f20f883e', '100', '8', '16', '0', '钟吓丽', '', '0', '690', '90', '11944106', '20160405');
INSERT INTO `_sys_admin` VALUES ('158', 'caichun', 'c4ca4238a0b923820dcc509a6f75849b', '100', '7', '14', '0', '蔡纯', '', '0', '3030', '270', '4556823', '20101207');
INSERT INTO `_sys_admin` VALUES ('473', 'chenqi', 'e10adc3949ba59abbe56e057f20f883e', '100', '6', '12', '0', '陈琦', '', '0', '6150', '30', '8506415', '20170225');
INSERT INTO `_sys_admin` VALUES ('351', 'yunyingzhuguan', 'e10adc3949ba59abbe56e057f20f883e', '100', '10', '17', '1', '运营主管', '', '1', '0', '0', '6536368', '0');
INSERT INTO `_sys_admin` VALUES ('476', 'laina', 'e10adc3949ba59abbe56e057f20f883e', '100', '7', '14', '0', '赖娜', '', '0', '270', '270', '8502779', '20170522');
INSERT INTO `_sys_admin` VALUES ('225', 'huanglin', 'e10adc3949ba59abbe56e057f20f883e', '100', '13', '24', '0', '黄淋', '', '0', '40260', '210', '4632941', '20120405');
INSERT INTO `_sys_admin` VALUES ('410', 'kangxiaofeng', 'e10adc3949ba59abbe56e057f20f883e', '100', '9', '9', '1', '康晓烽', '', '0', '3090', '0', '12318519', '20151221');
INSERT INTO `_sys_admin` VALUES ('428', 'chenxiaozhong', 'e10adc3949ba59abbe56e057f20f883e', '100', '9', '10', '0', '陈晓忠', '', '0', '3420', '0', '4294967295', null);
INSERT INTO `_sys_admin` VALUES ('222', 'xuexiaoyan', 'e10adc3949ba59abbe56e057f20f883e', '100', '11', '20', '0', '薛小燕', '', '0', '0', '0', '4628819', '20120227');
INSERT INTO `_sys_admin` VALUES ('199', 'kefuzhuguan', 'e10adc3949ba59abbe56e057f20f883e', '100', '11', '19', '1', '客服主管', '', '1', '0', '0', '0', null);
INSERT INTO `_sys_admin` VALUES ('389', 'linwenchuan', 'e10adc3949ba59abbe56e057f20f883e', '100', '9', '10', '0', '林文川', '', '0', '8280', '810', '11821640', '20150427');
INSERT INTO `_sys_admin` VALUES ('363', 'wangjianhong', 'e10adc3949ba59abbe56e057f20f883e', '100', '9', '10', '0', '王剑洪', '', '0', '0', '0', '4632933', '20141013');
INSERT INTO `_sys_admin` VALUES ('452', 'zhangmengting', 'e10adc3949ba59abbe56e057f20f883e', '100', '11', '20', '0', '张梦婷', '', '0', '0', '0', '8502460', '20161205');
INSERT INTO `_sys_admin` VALUES ('463', 'chenglingyan', 'e10adc3949ba59abbe56e057f20f883e', '100', '7', '14', '0', '程灵艳', '', '0', '330', '300', '2416540', '20170306');
INSERT INTO `_sys_admin` VALUES ('426', 'liqiang', 'e10adc3949ba59abbe56e057f20f883e', '100', '8', '16', '0', '李强', '', '0', '330', '0', '11943862', '20160511');
INSERT INTO `_sys_admin` VALUES ('409', 'xiexingpeng', 'e10adc3949ba59abbe56e057f20f883e', '100', '8', '16', '0', '谢兴朋', '', '0', '7380', '0', '12319280', '20151102');
INSERT INTO `_sys_admin` VALUES ('359', 'sunduowen', 'e10adc3949ba59abbe56e057f20f883e', '100', '17', '3', '0', '孙多文', '', '0', '250', '0', '4632914', '20140918');
INSERT INTO `_sys_admin` VALUES ('423', 'liyaqian', 'e10adc3949ba59abbe56e057f20f883e', '100', '7', '14', '0', '李雅茜', '', '0', '390', '0', '11944905', null);
INSERT INTO `_sys_admin` VALUES ('393', 'xuguorong', 'e10adc3949ba59abbe56e057f20f883e', '100', '13', '24', '0', '许国荣', '', '0', '19650', '0', '4932134', '20150601');
INSERT INTO `_sys_admin` VALUES ('427', 'yewenle', 'e10adc3949ba59abbe56e057f20f883e', '100', '13', '24', '0', '叶文乐', '', '0', '14790', '180', '12320372', '20160512');
INSERT INTO `_sys_admin` VALUES ('218', 'wuyouzhu', 'e10adc3949ba59abbe56e057f20f883e', '100', '7', '14', '0', '吴友柱', '', '0', '12240', '570', '5320481', '20120305');
INSERT INTO `_sys_admin` VALUES ('386', 'baojieyuan', 'e10adc3949ba59abbe56e057f20f883e', '100', '2', '3', '0', '保洁员', '', '0', '0', '0', '11813799', '0');
INSERT INTO `_sys_admin` VALUES ('414', 'younanzhou', 'e10adc3949ba59abbe56e057f20f883e', '100', '7', '14', '0', '游楠舟', '', '0', '2220', '390', '11946218', '20160118');
INSERT INTO `_sys_admin` VALUES ('413', 'chenkun', 'e10adc3949ba59abbe56e057f20f883e', '100', '8', '16', '0', '陈堃', '', '0', '5490', '810', '12316754', '20160111');
INSERT INTO `_sys_admin` VALUES ('416', 'zhangkaichen', 'e10adc3949ba59abbe56e057f20f883e', '100', '8', '16', '0', '张凯成', '', '0', '90', '0', '11958000', '20160321');
INSERT INTO `_sys_admin` VALUES ('250', 'linyongzhan', 'e10adc3949ba59abbe56e057f20f883e', '100', '17', '7', '0', '林永蘸', '', '0', '1350', '30', '4409666', '20120718');
INSERT INTO `_sys_admin` VALUES ('538', 'wumeizhen', 'c4ca4238a0b923820dcc509a6f75849b', '100', '10', '18', '0', '吴美珍', '', '0', '300', '180', '7057060', '20180102');
INSERT INTO `_sys_admin` VALUES ('255', 'zhengzhenqing', 'e10adc3949ba59abbe56e057f20f883e', '100', '6', '12', '0', '郑振清', '', '0', '23220', '120', '4410947', '20130217');
INSERT INTO `_sys_admin` VALUES ('424', 'chenhaoyang', 'e10adc3949ba59abbe56e057f20f883e', '100', '7', '14', '0', '陈浩洋', '', '0', '18600', '0', '11943794', null);
INSERT INTO `_sys_admin` VALUES ('434', 'qinyuxi', 'e10adc3949ba59abbe56e057f20f883e', '100', '7', '14', '0', '秦羽茜', '', '0', '120', '0', '4931871', null);
INSERT INTO `_sys_admin` VALUES ('442', 'xujianbin', 'e10adc3949ba59abbe56e057f20f883e', '100', '12', '22', '0', '许建斌', '', '0', '0', '0', '4932132', null);
INSERT INTO `_sys_admin` VALUES ('362', 'tangxiaosen', 'e10adc3949ba59abbe56e057f20f883e', '100', '13', '24', '0', '汤晓森', '', '0', '3180', '0', '5425117', '20141008');
INSERT INTO `_sys_admin` VALUES ('332', 'yefeng', 'c4ca4238a0b923820dcc509a6f75849b', '100', '13', '24', '0', '叶峰', '', '0', '57900', '0', '11811979', '20140610');
INSERT INTO `_sys_admin` VALUES ('451', 'wangzhaoying', 'e10adc3949ba59abbe56e057f20f883e', '100', '8', '16', '0', '王曌影', '', '0', '90', '90', '11812303', '20161201');
INSERT INTO `_sys_admin` VALUES ('276', 'linyi', 'e10adc3949ba59abbe56e057f20f883e', '100', '6', '12', '0', '林毅', '', '0', '3240', '0', '11845014', null);
INSERT INTO `_sys_admin` VALUES ('272', 'wangjuanjuan1', 'c4ca4238a0b923820dcc509a6f75849b', '100', '9', '9', '0', '王娟娟(旧）', '', '0', '22020', '0', '4932091', '20130311');
INSERT INTO `_sys_admin` VALUES ('321', 'linxiangyu', 'e10adc3949ba59abbe56e057f20f883e', '100', '8', '16', '0', '林翔宇', '', '0', '3000', '150', '4409642', '20140408');
INSERT INTO `_sys_admin` VALUES ('469', 'yewenhao', 'e10adc3949ba59abbe56e057f20f883e', '100', '6', '12', '0', '叶文浩', '', '0', '0', '0', '8464996', '20170417');
INSERT INTO `_sys_admin` VALUES ('319', 'wangliping', 'e10adc3949ba59abbe56e057f20f883e', '100', '8', '16', '0', '王丽萍', '', '0', '2040', '0', '1055679', '20140331');
INSERT INTO `_sys_admin` VALUES ('320', 'shaochangmin', 'e10adc3949ba59abbe56e057f20f883e', '100', '9', '10', '0', '邵长民', '', '0', '7740', '810', '4931869', '20140401');
INSERT INTO `_sys_admin` VALUES ('470', 'xunan', 'e10adc3949ba59abbe56e057f20f883e', '100', '11', '20', '0', '许楠', '', '0', '0', '0', '8464808', '20170417');
INSERT INTO `_sys_admin` VALUES ('316', 'chenying', 'e10adc3949ba59abbe56e057f20f883e', '100', '13', '24', '0', '陈颖', '', '0', '390', '0', '11946219', '20140501');
INSERT INTO `_sys_admin` VALUES ('441', 'zhengdan', 'e10adc3949ba59abbe56e057f20f883e', '100', '8', '16', '0', '郑丹', '', '0', '270', '90', '4431006', '20160823');
INSERT INTO `_sys_admin` VALUES ('453', 'fengchaoqun', 'e10adc3949ba59abbe56e057f20f883e', '100', '11', '20', '0', '冯超群', '', '0', '0', '0', '11764832', '20161205');
INSERT INTO `_sys_admin` VALUES ('398', 'yexiaoting', 'e10adc3949ba59abbe56e057f20f883e', '100', '7', '14', '0', '叶孝廷', '', '0', '4680', '480', '4932133', '20150727');
INSERT INTO `_sys_admin` VALUES ('285', 'chenjun', 'e10adc3949ba59abbe56e057f20f883e', '100', '13', '24', '0', '陈君', '', '0', '23550', '0', '11832865', '20130527');
INSERT INTO `_sys_admin` VALUES ('344', 'wangyuehua', 'e10adc3949ba59abbe56e057f20f883e', '100', '10', '18', '0', '汪月华', '', '0', '690', '30', '11802927', '20140729');
INSERT INTO `_sys_admin` VALUES ('289', 'chenyong', 'e10adc3949ba59abbe56e057f20f883e', '100', '11', '20', '0', '陈勇', '', '0', '0', '0', '11731911', '20130704');
INSERT INTO `_sys_admin` VALUES ('418', 'zhangzhaozhen', 'e10adc3949ba59abbe56e057f20f883e', '100', '8', '16', '0', '张兆臻', '', '0', '510', '150', '11841943', '20160405');
INSERT INTO `_sys_admin` VALUES ('417', 'lishuqun', 'e10adc3949ba59abbe56e057f20f883e', '100', '7', '14', '0', '李书群', '', '0', '2430', '960', '11958919', '20160329');
INSERT INTO `_sys_admin` VALUES ('298', 'minxiaoxuan', 'e10adc3949ba59abbe56e057f20f883e', '1', '13', '23', '0', '闵晓轩', '', '0', '-90', '0', '11829369', null);
INSERT INTO `_sys_admin` VALUES ('291', 'jiawupeng', 'e10adc3949ba59abbe56e057f20f883e', '100', '7', '14', '0', '加武鹏', '', '0', '4860', '420', '11823470', '20131201');
INSERT INTO `_sys_admin` VALUES ('349', 'wuzhen', 'e10adc3949ba59abbe56e057f20f883e', '100', '8', '16', '0', '吴真', '', '0', '1260', '150', '4415699', '20140826');
INSERT INTO `_sys_admin` VALUES ('485', 'xiayuhang', 'c4ca4238a0b923820dcc509a6f75849b', '100', '7', '14', '0', '夏宇航', '', '0', '2550', '1140', '1641920', '20170710');
INSERT INTO `_sys_admin` VALUES ('382', 'zhujianhui', 'e10adc3949ba59abbe56e057f20f883e', '100', '6', '12', '0', '朱剑辉', '', '0', '4710', '0', '4423644', null);
INSERT INTO `_sys_admin` VALUES ('433', 'chenxiangkun', 'e10adc3949ba59abbe56e057f20f883e', '100', '9', '10', '0', '陈祥坤', '', '0', '6390', '390', '4412227', '20160606');
INSERT INTO `_sys_admin` VALUES ('387', 'chenzon', 'e10adc3949ba59abbe56e057f20f883e', '1', '2', '8', '0', '陈文旺', '', '0', '0', '0', '4419297', null);
INSERT INTO `_sys_admin` VALUES ('395', 'huangjian', 'e10adc3949ba59abbe56e057f20f883e', '100', '7', '14', '0', '黄建', '', '0', '8790', '0', '11715962', null);
INSERT INTO `_sys_admin` VALUES ('307', 'oulehui', 'e10adc3949ba59abbe56e057f20f883e', '100', '9', '10', '0', '欧乐辉', '', '0', '24300', '270', '11831141', '20140217');
INSERT INTO `_sys_admin` VALUES ('450', 'chenchao', 'e10adc3949ba59abbe56e057f20f883e', '100', '13', '24', '0', '陈超', '', '0', '570', '0', '4421827', '20161128');
INSERT INTO `_sys_admin` VALUES ('440', 'chenshilan', 'e10adc3949ba59abbe56e057f20f883e', '100', '10', '18', '0', '陈诗兰', '', '0', '2040', '150', '5425147', '20160711');
INSERT INTO `_sys_admin` VALUES ('306', 'jianghui', 'e10adc3949ba59abbe56e057f20f883e', '100', '7', '14', '0', '江晖', '', '0', '12030', '0', '11830839', null);
INSERT INTO `_sys_admin` VALUES ('482', 'linchen', 'e10adc3949ba59abbe56e057f20f883e', '100', '8', '16', '0', '林晨', '', '0', '300', '0', '8511339', '20170619');
INSERT INTO `_sys_admin` VALUES ('318', 'qiuqingyuan', 'e10adc3949ba59abbe56e057f20f883e', '100', '8', '16', '0', '邱庆元', '', '0', '23610', '30', '4429697', '20140331');
INSERT INTO `_sys_admin` VALUES ('305', 'zhangjian', 'e10adc3949ba59abbe56e057f20f883e', '100', '7', '14', '0', '张健', '', '0', '2160', '180', '11829873', '20140113');
INSERT INTO `_sys_admin` VALUES ('308', 'pengmaorong', 'e10adc3949ba59abbe56e057f20f883e', '100', '13', '24', '0', '彭茂荣', '', '0', '11490', '210', '11809946', '20140219');
INSERT INTO `_sys_admin` VALUES ('458', 'hejunjie', 'e10adc3949ba59abbe56e057f20f883e', '100', '11', '20', '0', '何君杰', '', '0', '0', '0', '8454422', '20161227');
INSERT INTO `_sys_admin` VALUES ('459', 'wangtao', 'e10adc3949ba59abbe56e057f20f883e', '100', '17', '22', '0', '王涛', '', '0', '990', '60', '8453613', '20161229');
INSERT INTO `_sys_admin` VALUES ('356', 'wangjuanjuan', 'e10adc3949ba59abbe56e057f20f883e', '100', '7', '14', '0', '王娟娟', '', '0', '30', '0', '1055559', '20130311');
INSERT INTO `_sys_admin` VALUES ('502', 'yangxiaohua', 'c4ca4238a0b923820dcc509a6f75849b', '100', '8', '16', '0', '杨小花', '', '0', '90', '60', '8512509', '20170911');
INSERT INTO `_sys_admin` VALUES ('492', 'zhuxiongfeng', 'c4ca4238a0b923820dcc509a6f75849b', '100', '13', '24', '0', '朱雄峰', '', '0', '240', '210', '1647405', '20170807');
INSERT INTO `_sys_admin` VALUES ('545', 'liwanyang', 'e10adc3949ba59abbe56e057f20f883e', '100', '11', '20', '0', '李婉阳', '', '0', '0', '0', '7100717', '20180208');
INSERT INTO `_sys_admin` VALUES ('493', 'baojieyuan', 'c4ca4238a0b923820dcc509a6f75849b', '100', '2', '3', '0', '9楼保洁员', '', '0', '0', '0', '1647892', '20170803');
INSERT INTO `_sys_admin` VALUES ('500', 'linliangkui', 'c4ca4238a0b923820dcc509a6f75849b', '100', '8', '16', '0', '林良奎', '', '0', '1770', '0', '6501768', '20170901');
INSERT INTO `_sys_admin` VALUES ('539', 'zhangxuefeng', 'c4ca4238a0b923820dcc509a6f75849b', '100', '10', '18', '0', '张雪枫', '', '0', '270', '120', '6531902', '20180103');
INSERT INTO `_sys_admin` VALUES ('522', 'zhangxijie', 'c4ca4238a0b923820dcc509a6f75849b', '100', '10', '18', '0', '张晰洁', '', '0', '4110', '180', '11943794', '20171101');
INSERT INTO `_sys_admin` VALUES ('489', 'lishuqing', 'c4ca4238a0b923820dcc509a6f75849b', '100', '10', '18', '0', '李淑青', '', '0', '720', '150', '1648617', '20170801');
INSERT INTO `_sys_admin` VALUES ('490', 'cuibenkai', 'd9c0fe51b56550fec2b653cdbb1d0d68', '100', '13', '24', '0', '崔本凯', '', '0', '5640', '0', '1647895', '20170801');
INSERT INTO `_sys_admin` VALUES ('491', 'laipingping', 'c4ca4238a0b923820dcc509a6f75849b', '100', '17', '7', '0', '赖苹苹', '', '0', '90', '0', '1703821', '20170801');
INSERT INTO `_sys_admin` VALUES ('535', 'dailangda', 'c4ca4238a0b923820dcc509a6f75849b', '100', '13', '24', '0', '戴郎达', '', '0', '150', '120', '6528546', '20171212');
INSERT INTO `_sys_admin` VALUES ('494', 'wangxingxing', 'c4ca4238a0b923820dcc509a6f75849b', '100', '8', '16', '0', '王星星', '', '0', '690', '90', '1649105', '20170810');
INSERT INTO `_sys_admin` VALUES ('495', 'chenyue', 'c4ca4238a0b923820dcc509a6f75849b', '100', '10', '18', '0', '陈悦', '', '0', '60', '30', '1648378', '20170810');
INSERT INTO `_sys_admin` VALUES ('523', 'xueyousheng', '4072b23a1d687adf1b581b9c359a25ec', '100', '7', '14', '0', '薛尤升', '', '0', '3360', '660', '12318067', '20171101');
INSERT INTO `_sys_admin` VALUES ('497', 'yanghuan', 'c4ca4238a0b923820dcc509a6f75849b', '100', '10', '18', '0', '杨欢', '', '0', '0', '0', '6525644', '20170816');
INSERT INTO `_sys_admin` VALUES ('498', 'zhangyuling', 'c4ca4238a0b923820dcc509a6f75849b', '100', '10', '18', '0', '张玉玲', '', '0', '60', '60', '6519055', '20170822');
INSERT INTO `_sys_admin` VALUES ('527', 'liujinxiu', 'c4ca4238a0b923820dcc509a6f75849b', '100', '17', '3', '0', '刘金秀', '', '0', '0', '0', '11944905', '20171115');
INSERT INTO `_sys_admin` VALUES ('503', 'wucaigui', 'c4ca4238a0b923820dcc509a6f75849b', '100', '13', '24', '0', '吴财贵', '', '0', '150', '150', '8511012', '20170911');
INSERT INTO `_sys_admin` VALUES ('533', 'linhao', 'c4ca4238a0b923820dcc509a6f75849b', '100', '13', '24', '0', '林豪', '', '0', '0', '0', '6538791', '20171204');
INSERT INTO `_sys_admin` VALUES ('532', 'gumengni', 'c4ca4238a0b923820dcc509a6f75849b', '100', '11', '20', '0', '顾梦妮', '', '0', '0', '0', '6735837', '20171202');
INSERT INTO `_sys_admin` VALUES ('504', 'libin', 'c4ca4238a0b923820dcc509a6f75849b', '100', '17', '29', '0', '李斌', '', '0', '0', '0', '8504719', '20170914');
INSERT INTO `_sys_admin` VALUES ('550', 'zbzg', 'e10adc3949ba59abbe56e057f20f883e', '100', '17', '3', '1', '总办', '', '0', '0', '0', '0', '0');
INSERT INTO `_sys_admin` VALUES ('506', 'xuhuiyuan', 'c4ca4238a0b923820dcc509a6f75849b', '100', '9', '10', '0', '徐惠源', '', '0', '3540', '0', '8464996', '20170920');
INSERT INTO `_sys_admin` VALUES ('507', 'duweiyi', 'c4ca4238a0b923820dcc509a6f75849b', '100', '7', '14', '0', '杜唯毅', '', '0', '3660', '570', '1708304', '20170921');
INSERT INTO `_sys_admin` VALUES ('508', 'chenkai', 'c4ca4238a0b923820dcc509a6f75849b', '100', '7', '14', '0', '陈凯', '', '0', '330', '0', '4931891', '20170925');
INSERT INTO `_sys_admin` VALUES ('510', 'chenjunyang', 'c4ca4238a0b923820dcc509a6f75849b', '100', '9', '10', '0', '陈俊阳', '', '0', '0', '0', '8510840', '20170925');
INSERT INTO `_sys_admin` VALUES ('511', 'shiminmin', 'c4ca4238a0b923820dcc509a6f75849b', '100', '10', '18', '0', '石敏敏', '', '0', '1200', '150', '11838440', '20170925');
INSERT INTO `_sys_admin` VALUES ('528', 'zhangtingting', 'c4ca4238a0b923820dcc509a6f75849b', '100', '9', '10', '0', '张婷婷', '', '0', '180', '0', '6508082', '20171120');
INSERT INTO `_sys_admin` VALUES ('514', 'qiuxiaoqing', 'c4ca4238a0b923820dcc509a6f75849b', '100', '8', '16', '0', '邱晓卿', '', '0', '420', '60', '8503521', '20171009');
INSERT INTO `_sys_admin` VALUES ('515', 'fujianmei', 'c4ca4238a0b923820dcc509a6f75849b', '100', '10', '18', '0', '付健美', '', '0', '1470', '180', '1055593', '20171009');
INSERT INTO `_sys_admin` VALUES ('516', 'xiaoxueping', 'c4ca4238a0b923820dcc509a6f75849b', '100', '6', '12', '0', '肖雪平', '', '0', '0', '0', '11806369', '20171009');
INSERT INTO `_sys_admin` VALUES ('517', 'xielingyan', 'c4ca4238a0b923820dcc509a6f75849b', '100', '9', '10', '0', '谢灵燕', '', '0', '0', '0', '11834395', '20171009');
INSERT INTO `_sys_admin` VALUES ('520', 'huanghui', 'c4ca4238a0b923820dcc509a6f75849b', '100', '10', '18', '0', '黄辉', '', '0', '450', '210', '4932132', '20171019');
INSERT INTO `_sys_admin` VALUES ('521', 'lulifang', 'c4ca4238a0b923820dcc509a6f75849b', '100', '8', '16', '0', '卢丽芳', '', '0', '330', '60', '8510840', '20171019');
INSERT INTO `_sys_admin` VALUES ('525', 'zhanfuwei', 'c4ca4238a0b923820dcc509a6f75849b', '100', '8', '16', '0', '詹富伟', '', '0', '390', '0', '6532629', '20171113');
INSERT INTO `_sys_admin` VALUES ('526', 'raozhibin', 'c4ca4238a0b923820dcc509a6f75849b', '100', '9', '10', '0', '饶智滨', '', '0', '30', '30', '6518027', '20171113');
INSERT INTO `_sys_admin` VALUES ('531', 'wangjiayu', 'c4ca4238a0b923820dcc509a6f75849b', '100', '8', '16', '0', '王家裕', '', '0', '0', '0', '6520365', '20171202');
INSERT INTO `_sys_admin` VALUES ('549', 'wangyongxiang', 'e10adc3949ba59abbe56e057f20f883e', '100', '7', '14', '0', '王雍翔', '', '0', '0', '0', '6734706', '20180306');
INSERT INTO `_sys_admin` VALUES ('537', 'wujiabing', 'c4ca4238a0b923820dcc509a6f75849b', '100', '13', '24', '0', '吴家炳', '', '0', '0', '0', '7007207', '20171218');
INSERT INTO `_sys_admin` VALUES ('530', 'baojieyuan2', 'c4ca4238a0b923820dcc509a6f75849b', '100', '2', '3', '0', '9楼保洁员2', '', '0', '0', '0', '6736106', '0');
INSERT INTO `_sys_admin` VALUES ('540', 'chenwei', 'c4ca4238a0b923820dcc509a6f75849b', '100', '17', '22', '0', '陈威', '', '0', '0', '0', '6534077', '20180109');
INSERT INTO `_sys_admin` VALUES ('546', 'lintaizhong', 'c4ca4238a0b923820dcc509a6f75849b', '100', '9', '10', '0', '林泰忠', '', '0', '0', '0', '7103264', '20180226');
INSERT INTO `_sys_admin` VALUES ('548', 'lintaojun', 'e10adc3949ba59abbe56e057f20f883e', '100', '9', '10', '0', '林陶钧', '', '0', '0', '0', '6736114', '20180306');
INSERT INTO `_sys_admin` VALUES ('536', 'chenbin', 'c4ca4238a0b923820dcc509a6f75849b', '100', '7', '14', '0', '陈彬', '', '0', '1380', '0', '7115098', '20171213');
INSERT INTO `_sys_admin` VALUES ('541', 'wanglongqin', 'c4ca4238a0b923820dcc509a6f75849b', '100', '13', '24', '0', '王龙钦', '', '0', '240', '30', '7107532', '20180112');
INSERT INTO `_sys_admin` VALUES ('542', 'zhangyichi', 'c4ca4238a0b923820dcc509a6f75849b', '100', '7', '14', '0', '张亦弛', '', '0', '930', '0', '6509720', '20180115');
INSERT INTO `_sys_admin` VALUES ('543', 'huangxiaojian', 'c4ca4238a0b923820dcc509a6f75849b', '100', '6', '12', '0', '黄晓健', '', '0', '180', '30', '6506691', '20180115');
INSERT INTO `_sys_admin` VALUES ('544', 'aiweijin', 'e40a9d50ba417ecc6cf7bb6998eb7685', '100', '13', '24', '0', '艾伟金', '', '0', '2160', '0', '7054132', '20180116');
INSERT INTO `_sys_admin` VALUES ('551', 'zhoudaifeng', '202cb962ac59075b964b07152d234b70', '100', '6', '12', '0', '周岱峰', '', '0', '0', '0', '6525107', '20180312');
INSERT INTO `_sys_admin` VALUES ('547', 'fengqiwei', 'e10adc3949ba59abbe56e057f20f883e', '100', '7', '14', '0', '丰奇炜', '', '0', '0', '0', '6515475', '20180302');

-- ----------------------------
-- Table structure for _sys_dic
-- ----------------------------
DROP TABLE IF EXISTS `_sys_dic`;
CREATE TABLE `_sys_dic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dickey` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sys_tag` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `dickey` (`dickey`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of _sys_dic
-- ----------------------------
INSERT INTO `_sys_dic` VALUES ('1', 'tag', '有效性', '1');

-- ----------------------------
-- Table structure for _sys_dic_item
-- ----------------------------
DROP TABLE IF EXISTS `_sys_dic_item`;
CREATE TABLE `_sys_dic_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dicid` int(11) NOT NULL,
  `dicval` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sys_tag` tinyint(2) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `dicid` (`dicid`),
  KEY `dicval` (`dicval`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of _sys_dic_item
-- ----------------------------
INSERT INTO `_sys_dic_item` VALUES ('1', '1', '1', '有效', '1');
INSERT INTO `_sys_dic_item` VALUES ('2', '1', '2', '无效', '1');

-- ----------------------------
-- Table structure for _sys_group
-- ----------------------------
DROP TABLE IF EXISTS `_sys_group`;
CREATE TABLE `_sys_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=102 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of _sys_group
-- ----------------------------
INSERT INTO `_sys_group` VALUES ('1', '超级管理员');
INSERT INTO `_sys_group` VALUES ('100', '普通会员');

-- ----------------------------
-- Table structure for _sys_group_perm
-- ----------------------------
DROP TABLE IF EXISTS `_sys_group_perm`;
CREATE TABLE `_sys_group_perm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `perm_id` int(11) NOT NULL,
  `s_tag` tinyint(4) DEFAULT '0',
  `a_tag` tinyint(4) DEFAULT '0',
  `e_tag` tinyint(4) DEFAULT '0',
  `d_tag` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`),
  KEY `group_id` (`group_id`),
  KEY `perm_id` (`perm_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5964 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of _sys_group_perm
-- ----------------------------
INSERT INTO `_sys_group_perm` VALUES ('4699', null, '101', '54', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('5946', null, '1', '82', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5845', null, '100', '39', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5844', null, '100', '71', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('5843', null, '100', '62', '1', '1', '1', '0');
INSERT INTO `_sys_group_perm` VALUES ('5842', null, '100', '60', '1', '1', '1', '0');
INSERT INTO `_sys_group_perm` VALUES ('4698', null, '101', '53', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('5841', null, '100', '67', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('5399', '121', null, '74', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5897', '106', null, '84', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5398', '121', null, '73', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5405', '99', null, '76', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5404', '99', null, '75', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5403', '99', null, '74', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5945', null, '1', '81', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5944', null, '1', '80', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5943', null, '1', '79', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5942', null, '1', '78', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5941', null, '1', '77', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5940', null, '1', '76', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5939', null, '1', '75', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5938', null, '1', '74', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5937', null, '1', '73', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5936', null, '1', '72', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5935', null, '1', '39', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5934', null, '1', '47', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('4697', null, '101', '52', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('4696', null, '101', '51', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('4695', null, '101', '50', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('4700', null, '101', '55', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('4701', null, '101', '56', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('4702', null, '101', '57', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('4703', null, '101', '58', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('4704', null, '101', '59', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('4705', null, '101', '1', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('4706', null, '101', '2', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('4707', null, '101', '49', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('4708', null, '101', '32', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('4709', null, '101', '37', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('4710', null, '101', '44', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('4711', null, '101', '45', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('4712', null, '101', '46', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('4713', null, '101', '47', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('4714', null, '101', '39', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5840', null, '100', '66', '1', '1', '1', '0');
INSERT INTO `_sys_group_perm` VALUES ('5933', null, '1', '46', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('5839', null, '100', '69', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('5932', null, '1', '45', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('5838', null, '100', '68', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('5931', null, '1', '44', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5930', null, '1', '37', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('5929', null, '1', '32', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5837', null, '100', '53', '1', '1', '1', '0');
INSERT INTO `_sys_group_perm` VALUES ('5928', null, '1', '87', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5836', null, '100', '52', '1', '1', '1', '0');
INSERT INTO `_sys_group_perm` VALUES ('5927', null, '1', '86', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5835', null, '100', '51', '1', '1', '1', '0');
INSERT INTO `_sys_group_perm` VALUES ('5926', null, '1', '85', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5834', null, '100', '61', '1', '1', '1', '0');
INSERT INTO `_sys_group_perm` VALUES ('5925', null, '1', '84', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5924', null, '1', '58', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('5833', null, '100', '50', '1', '1', '1', '0');
INSERT INTO `_sys_group_perm` VALUES ('5896', '106', null, '70', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('5397', '121', null, '72', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5396', '121', null, '71', '1', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5895', '106', null, '63', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('5923', null, '1', '57', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('5922', null, '1', '56', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('5921', null, '1', '55', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('5920', null, '1', '54', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('5919', null, '1', '94', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5402', '99', null, '73', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5401', '99', null, '72', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5395', '121', null, '66', '1', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5400', '121', null, '77', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5406', '99', null, '78', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5407', '99', null, '79', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5408', '99', null, '80', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5409', '99', null, '81', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5410', '99', null, '82', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5411', '99', null, '83', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5894', '106', null, '93', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('5918', null, '1', '70', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('5917', null, '1', '92', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5916', null, '1', '71', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('5915', null, '1', '63', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('5914', null, '1', '62', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('5913', null, '1', '60', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('5912', null, '1', '67', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('5911', null, '1', '66', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('5910', null, '1', '69', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('5909', null, '1', '68', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('5908', null, '1', '53', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('5907', null, '1', '93', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('5906', null, '1', '52', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('5905', null, '1', '51', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('5904', null, '1', '61', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('5903', null, '1', '50', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('5902', null, '1', '59', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('5676', '199', null, '77', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5901', null, '1', '91', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('5900', null, '1', '90', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('5899', null, '1', '89', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('5832', null, '100', '59', '1', '1', '1', '0');
INSERT INTO `_sys_group_perm` VALUES ('5898', '106', null, '46', '1', '1', '1', '1');
INSERT INTO `_sys_group_perm` VALUES ('5947', null, '1', '83', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5948', '380', null, '94', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5949', '225', null, '94', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5950', '364', null, '94', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5951', null, '1', '95', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5952', '351', null, '78', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5953', '351', null, '79', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5954', '351', null, '80', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5955', '351', null, '81', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5956', '351', null, '82', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5957', '351', null, '83', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5958', '550', null, '78', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5959', '550', null, '79', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5960', '550', null, '80', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5961', '550', null, '81', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5962', '550', null, '82', '0', '0', '0', '0');
INSERT INTO `_sys_group_perm` VALUES ('5963', '550', null, '83', '0', '0', '0', '0');

-- ----------------------------
-- Table structure for _sys_section
-- ----------------------------
DROP TABLE IF EXISTS `_sys_section`;
CREATE TABLE `_sys_section` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `table_name` varchar(100) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `field_value` varchar(100) DEFAULT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `link` varchar(255) NOT NULL,
  `sort` int(11) NOT NULL,
  `hide_sub` tinyint(4) NOT NULL DEFAULT '0',
  `Slist` int(11) NOT NULL DEFAULT '1',
  `Sadd` int(11) NOT NULL DEFAULT '1',
  `Sedit` int(11) NOT NULL DEFAULT '1',
  `Sdelete` int(11) NOT NULL DEFAULT '1',
  `control` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=96 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of _sys_section
-- ----------------------------
INSERT INTO `_sys_section` VALUES ('60', '个人资料', '_sys_admin', '', '', '50', 'index.php?type=system&do=userinfoedit', '20', '0', '1', '1', '1', '1', '1');
INSERT INTO `_sys_section` VALUES ('61', '加班管理', '_web_overtime', '', '', '50', 'index.php?type=web&do=list&cn=overtime', '1', '0', '1', '1', '1', '1', '1');
INSERT INTO `_sys_section` VALUES ('51', '调休管理', '_web_hugh', '', '', '50', 'index.php?type=web&do=list&cn=hugh', '5', '0', '1', '1', '1', '1', '1');
INSERT INTO `_sys_section` VALUES ('52', '请假管理', '_web_leave', '', '', '50', 'index.php?type=web&do=list&cn=leave', '10', '0', '1', '1', '1', '1', '1');
INSERT INTO `_sys_section` VALUES ('32', '系统管理', '', '', '', '0', '', '50', '1', '0', '0', '0', '0', '1');
INSERT INTO `_sys_section` VALUES ('37', '字典管理', '_sys_dic_item', '', '', '32', 'index.php?type=system&do=dic', '0', '0', '1', '1', '1', '1', '1');
INSERT INTO `_sys_section` VALUES ('39', '安全退出', '', '', '', '0', 'login.php?out=yes', '50', '0', '0', '0', '0', '0', '1');
INSERT INTO `_sys_section` VALUES ('50', '个人管理', '', '', '', '0', '', '10', '0', '1', '1', '1', '1', '1');
INSERT INTO `_sys_section` VALUES ('44', '管理权限', '', '', '', '32', '', '0', '0', '0', '0', '0', '0', '1');
INSERT INTO `_sys_section` VALUES ('45', '群组管理', '_sys_group', '', '', '44', 'index.php?type=system&do=group', '0', '0', '1', '1', '1', '1', '1');
INSERT INTO `_sys_section` VALUES ('46', '帐号管理', '_sys_admin', '', '', '44', 'index.php?type=system&do=user', '0', '0', '1', '1', '1', '1', '1');
INSERT INTO `_sys_section` VALUES ('47', '权限管理', '_sys_group_perm', '', '', '44', 'index.php?type=system&do=group_permission', '0', '0', '1', '1', '1', '1', '1');
INSERT INTO `_sys_section` VALUES ('53', '签呈管理', '_web_sign', '', '', '50', 'index.php?type=web&do=list&cn=sign', '15', '0', '1', '1', '1', '1', '1');
INSERT INTO `_sys_section` VALUES ('54', '部门管理', '', '', '', '0', '', '20', '0', '1', '1', '1', '1', '1');
INSERT INTO `_sys_section` VALUES ('55', '部门列表', '_web_department', '', '', '54', 'index.php?type=web&do=list&cn=department', '0', '0', '1', '1', '1', '1', '1');
INSERT INTO `_sys_section` VALUES ('56', '添加部门', '_web_department', '', '', '54', 'index.php?type=web&do=info&cn=department', '0', '0', '1', '1', '1', '1', '1');
INSERT INTO `_sys_section` VALUES ('57', '岗位列表', '_web_job', '', '', '54', 'index.php?type=web&do=list&cn=job', '0', '0', '1', '1', '1', '1', '1');
INSERT INTO `_sys_section` VALUES ('58', '添加岗位', '_web_job', '', '', '54', 'index.php?type=web&do=info&cn=job', '0', '0', '1', '1', '1', '1', '1');
INSERT INTO `_sys_section` VALUES ('59', '待处理', '', '', '', '0', 'unread.php', '1', '0', '1', '1', '1', '1', '1');
INSERT INTO `_sys_section` VALUES ('62', '审核结果查询', '', '', '', '50', 'search.php?type=web', '50', '0', '1', '1', '1', '1', '1');
INSERT INTO `_sys_section` VALUES ('63', '加班调休统计', 'overtime', '', '', '0', 'overtime.php', '15', '0', '1', '1', '1', '1', '1');
INSERT INTO `_sys_section` VALUES ('67', '我的打卡异常', '', '', '', '50', 'odd.php', '19', '0', '1', '1', '1', '1', '1');
INSERT INTO `_sys_section` VALUES ('66', '打卡查询', '_web_record', '', '', '50', 'index.php?type=web&do=list&cn=record', '18', '0', '1', '1', '1', '1', '1');
INSERT INTO `_sys_section` VALUES ('68', '公出单', '_web_outrecord', '', '', '50', 'index.php?type=web&do=list&cn=outrecord', '16', '0', '1', '1', '1', '1', '1');
INSERT INTO `_sys_section` VALUES ('69', '指纹异常单', '_web_oddtime', '', '', '50', 'index.php?type=web&do=list&cn=oddtime', '17', '0', '1', '1', '1', '1', '1');
INSERT INTO `_sys_section` VALUES ('70', '工作日管理', '_web_workday', '', '', '0', 'workday.php', '17', '0', '1', '1', '1', '1', '1');
INSERT INTO `_sys_section` VALUES ('71', '考勤统计', '', '', '', '0', 'stat.php', '16', '0', '1', '1', '1', '1', '1');
INSERT INTO `_sys_section` VALUES ('72', '加班管理查询', '', null, null, '0', '', '50', '1', '0', '0', '0', '0', '1');
INSERT INTO `_sys_section` VALUES ('73', '调休管理查询', '', null, null, '0', '', '50', '1', '0', '0', '0', '0', '1');
INSERT INTO `_sys_section` VALUES ('74', '请假管理查询', '', null, null, '0', '', '50', '1', '0', '0', '0', '0', '1');
INSERT INTO `_sys_section` VALUES ('75', '签呈管理查询', '', null, null, '0', '', '50', '1', '0', '0', '0', '0', '1');
INSERT INTO `_sys_section` VALUES ('76', '公出单查询', '', null, null, '0', '', '50', '1', '0', '0', '0', '0', '1');
INSERT INTO `_sys_section` VALUES ('77', '打卡查询(私人权限)', null, null, null, '0', '', '50', '1', '0', '0', '0', '0', '1');
INSERT INTO `_sys_section` VALUES ('78', '签呈批量审批', null, null, null, '0', '', '50', '1', '0', '0', '0', '0', '1');
INSERT INTO `_sys_section` VALUES ('79', '公出批量审批', null, null, null, '0', '', '50', '1', '0', '0', '0', '0', '1');
INSERT INTO `_sys_section` VALUES ('80', '异常批量审批', null, null, null, '0', '', '50', '1', '0', '0', '0', '0', '1');
INSERT INTO `_sys_section` VALUES ('81', '加班批量审批', null, null, null, '0', '', '50', '1', '0', '0', '0', '0', '1');
INSERT INTO `_sys_section` VALUES ('82', '请假批量审批', null, null, null, '0', '', '50', '1', '0', '0', '0', '0', '1');
INSERT INTO `_sys_section` VALUES ('83', '调休批量审批', null, null, null, '0', '', '50', '1', '0', '0', '0', '0', '1');
INSERT INTO `_sys_section` VALUES ('84', '调休时间更改', null, null, null, '0', 'upt_hugh.php', '49', '1', '0', '0', '0', '0', '1');
INSERT INTO `_sys_section` VALUES ('85', '任务绩效查看', null, null, null, '0', 'task.php', '49', '0', '0', '0', '0', '0', '1');
INSERT INTO `_sys_section` VALUES ('86', '任务绩效打分权限', null, null, null, '0', 'task_per.php', '49', '0', '0', '0', '0', '0', '1');
INSERT INTO `_sys_section` VALUES ('87', '调休时间查询', null, null, null, '0', 'upt_sel.php', '49', '1', '0', '0', '0', '0', '1');
INSERT INTO `_sys_section` VALUES ('89', '评价权限', null, null, null, '0', 'kaohe_my.php', '0', '0', '1', '1', '1', '1', '1');
INSERT INTO `_sys_section` VALUES ('90', '评价管理', null, null, null, '0', 'kaohe_you.php', '0', '0', '1', '1', '1', '1', '1');
INSERT INTO `_sys_section` VALUES ('91', '评价权限', null, null, null, '0', 'kaohe_qx.php', '0', '0', '1', '1', '1', '1', '1');
INSERT INTO `_sys_section` VALUES ('92', '计算考勤时间', null, null, null, '0', 'calculate_time.php', '16', '0', '1', '1', '1', '1', '1');
INSERT INTO `_sys_section` VALUES ('93', '请假报备~~', '_web_leave_filing', null, null, '50', 'index.php?type=web&do=list&cn=leave_filing', '10', '0', '1', '1', '1', '1', '1');
INSERT INTO `_sys_section` VALUES ('94', '调休特殊管理', null, null, null, '0', 'hugh_pass.php', '17', '0', '1', '1', '1', '1', '1');
INSERT INTO `_sys_section` VALUES ('95', '计算年假时间', '_web_annual_leave', null, null, '0', 'annual_leave_time.php', '17', '0', '1', '1', '1', '1', '1');

-- ----------------------------
-- Table structure for _web_annual_leave
-- ----------------------------
DROP TABLE IF EXISTS `_web_annual_leave`;
CREATE TABLE `_web_annual_leave` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `useYear` int(11) NOT NULL COMMENT '可使用年限',
  `allTime` decimal(11,1) NOT NULL DEFAULT '0.0' COMMENT '总时长',
  `useTime` decimal(11,1) unsigned NOT NULL DEFAULT '0.0' COMMENT '使用时长',
  `uid` int(11) NOT NULL COMMENT '员工编号',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk` (`useYear`,`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=314 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of _web_annual_leave
-- ----------------------------
INSERT INTO `_web_annual_leave` VALUES ('1', '2017', '0.0', '0.0', '99');
INSERT INTO `_web_annual_leave` VALUES ('2', '2017', '40.0', '0.0', '112');
INSERT INTO `_web_annual_leave` VALUES ('3', '2017', '0.0', '0.0', '483');
INSERT INTO `_web_annual_leave` VALUES ('4', '2017', '0.0', '0.0', '474');
INSERT INTO `_web_annual_leave` VALUES ('5', '2017', '0.0', '0.0', '462');
INSERT INTO `_web_annual_leave` VALUES ('6', '2017', '40.0', '21.5', '106');
INSERT INTO `_web_annual_leave` VALUES ('7', '2017', '40.0', '40.0', '364');
INSERT INTO `_web_annual_leave` VALUES ('8', '2017', '40.0', '0.0', '116');
INSERT INTO `_web_annual_leave` VALUES ('9', '2017', '0.0', '0.0', '484');
INSERT INTO `_web_annual_leave` VALUES ('10', '2017', '40.0', '0.0', '121');
INSERT INTO `_web_annual_leave` VALUES ('11', '2017', '40.0', '16.0', '122');
INSERT INTO `_web_annual_leave` VALUES ('12', '2017', '0.0', '0.0', '486');
INSERT INTO `_web_annual_leave` VALUES ('13', '2017', '20.0', '0.0', '437');
INSERT INTO `_web_annual_leave` VALUES ('14', '2017', '40.0', '40.0', '328');
INSERT INTO `_web_annual_leave` VALUES ('15', '2017', '35.0', '35.0', '415');
INSERT INTO `_web_annual_leave` VALUES ('16', '2017', '40.0', '16.0', '130');
INSERT INTO `_web_annual_leave` VALUES ('17', '2017', '40.0', '24.0', '133');
INSERT INTO `_web_annual_leave` VALUES ('18', '2017', '0.0', '0.0', '468');
INSERT INTO `_web_annual_leave` VALUES ('19', '2017', '0.0', '0.0', '481');
INSERT INTO `_web_annual_leave` VALUES ('20', '2017', '0.0', '0.0', '478');
INSERT INTO `_web_annual_leave` VALUES ('21', '2017', '0.0', '0.0', '460');
INSERT INTO `_web_annual_leave` VALUES ('22', '2017', '40.0', '16.0', '384');
INSERT INTO `_web_annual_leave` VALUES ('23', '2017', '39.0', '0.0', '412');
INSERT INTO `_web_annual_leave` VALUES ('24', '2017', '4.0', '0.0', '449');
INSERT INTO `_web_annual_leave` VALUES ('25', '2017', '22.0', '22.0', '432');
INSERT INTO `_web_annual_leave` VALUES ('26', '2017', '0.0', '0.0', '145');
INSERT INTO `_web_annual_leave` VALUES ('27', '2017', '0.0', '0.0', '480');
INSERT INTO `_web_annual_leave` VALUES ('28', '2017', '0.0', '0.0', '475');
INSERT INTO `_web_annual_leave` VALUES ('29', '2017', '26.0', '16.0', '454');
INSERT INTO `_web_annual_leave` VALUES ('30', '2017', '40.0', '40.0', '357');
INSERT INTO `_web_annual_leave` VALUES ('31', '2017', '40.0', '32.0', '390');
INSERT INTO `_web_annual_leave` VALUES ('32', '2017', '2.0', '0.0', '455');
INSERT INTO `_web_annual_leave` VALUES ('33', '2017', '29.0', '29.0', '421');
INSERT INTO `_web_annual_leave` VALUES ('34', '2017', '0.0', '0.0', '420');
INSERT INTO `_web_annual_leave` VALUES ('35', '2017', '0.0', '0.0', '396');
INSERT INTO `_web_annual_leave` VALUES ('36', '2017', '40.0', '24.0', '246');
INSERT INTO `_web_annual_leave` VALUES ('37', '2017', '0.0', '0.0', '479');
INSERT INTO `_web_annual_leave` VALUES ('38', '2017', '0.0', '0.0', '446');
INSERT INTO `_web_annual_leave` VALUES ('39', '2017', '1.0', '0.0', '456');
INSERT INTO `_web_annual_leave` VALUES ('40', '2017', '1.0', '0.0', '457');
INSERT INTO `_web_annual_leave` VALUES ('41', '2017', '0.0', '0.0', '465');
INSERT INTO `_web_annual_leave` VALUES ('42', '2017', '40.0', '0.0', '375');
INSERT INTO `_web_annual_leave` VALUES ('43', '2017', '0.0', '0.0', '466');
INSERT INTO `_web_annual_leave` VALUES ('44', '2017', '29.0', '29.0', '419');
INSERT INTO `_web_annual_leave` VALUES ('45', '2017', '40.0', '16.0', '158');
INSERT INTO `_web_annual_leave` VALUES ('46', '2017', '0.0', '0.0', '473');
INSERT INTO `_web_annual_leave` VALUES ('47', '2017', '0.0', '0.0', '351');
INSERT INTO `_web_annual_leave` VALUES ('48', '2017', '0.0', '0.0', '476');
INSERT INTO `_web_annual_leave` VALUES ('49', '2017', '40.0', '32.0', '225');
INSERT INTO `_web_annual_leave` VALUES ('50', '2017', '40.0', '40.0', '410');
INSERT INTO `_web_annual_leave` VALUES ('51', '2017', '0.0', '0.0', '428');
INSERT INTO `_web_annual_leave` VALUES ('52', '2017', '0.0', '0.0', '461');
INSERT INTO `_web_annual_leave` VALUES ('53', '2017', '0.0', '0.0', '477');
INSERT INTO `_web_annual_leave` VALUES ('54', '2017', '40.0', '0.0', '222');
INSERT INTO `_web_annual_leave` VALUES ('55', '2017', '0.0', '0.0', '199');
INSERT INTO `_web_annual_leave` VALUES ('56', '2017', '40.0', '34.5', '389');
INSERT INTO `_web_annual_leave` VALUES ('57', '2017', '40.0', '0.0', '363');
INSERT INTO `_web_annual_leave` VALUES ('58', '2017', '2.0', '0.0', '452');
INSERT INTO `_web_annual_leave` VALUES ('59', '2017', '0.0', '0.0', '463');
INSERT INTO `_web_annual_leave` VALUES ('60', '2017', '25.0', '25.0', '426');
INSERT INTO `_web_annual_leave` VALUES ('61', '2017', '40.0', '24.0', '409');
INSERT INTO `_web_annual_leave` VALUES ('62', '2017', '40.0', '0.0', '359');
INSERT INTO `_web_annual_leave` VALUES ('63', '2017', '0.0', '0.0', '423');
INSERT INTO `_web_annual_leave` VALUES ('64', '2017', '40.0', '0.0', '405');
INSERT INTO `_web_annual_leave` VALUES ('65', '2017', '40.0', '32.0', '393');
INSERT INTO `_web_annual_leave` VALUES ('66', '2017', '25.0', '0.0', '427');
INSERT INTO `_web_annual_leave` VALUES ('67', '2017', '40.0', '40.0', '218');
INSERT INTO `_web_annual_leave` VALUES ('68', '2017', '0.0', '0.0', '386');
INSERT INTO `_web_annual_leave` VALUES ('69', '2017', '38.0', '37.0', '414');
INSERT INTO `_web_annual_leave` VALUES ('70', '2017', '39.0', '11.0', '413');
INSERT INTO `_web_annual_leave` VALUES ('71', '2017', '31.0', '30.0', '416');
INSERT INTO `_web_annual_leave` VALUES ('72', '2017', '40.0', '40.0', '250');
INSERT INTO `_web_annual_leave` VALUES ('73', '2017', '10.0', '0.0', '445');
INSERT INTO `_web_annual_leave` VALUES ('74', '2017', '40.0', '32.0', '255');
INSERT INTO `_web_annual_leave` VALUES ('75', '2017', '0.0', '0.0', '467');
INSERT INTO `_web_annual_leave` VALUES ('76', '2017', '0.0', '0.0', '424');
INSERT INTO `_web_annual_leave` VALUES ('77', '2017', '0.0', '0.0', '434');
INSERT INTO `_web_annual_leave` VALUES ('78', '2017', '0.0', '0.0', '442');
INSERT INTO `_web_annual_leave` VALUES ('79', '2017', '0.0', '0.0', '370');
INSERT INTO `_web_annual_leave` VALUES ('80', '2017', '40.0', '0.0', '362');
INSERT INTO `_web_annual_leave` VALUES ('81', '2017', '40.0', '0.0', '332');
INSERT INTO `_web_annual_leave` VALUES ('82', '2017', '3.0', '3.0', '451');
INSERT INTO `_web_annual_leave` VALUES ('83', '2017', '0.0', '0.0', '276');
INSERT INTO `_web_annual_leave` VALUES ('84', '2017', '40.0', '0.0', '272');
INSERT INTO `_web_annual_leave` VALUES ('85', '2017', '40.0', '40.0', '321');
INSERT INTO `_web_annual_leave` VALUES ('86', '2017', '0.0', '0.0', '472');
INSERT INTO `_web_annual_leave` VALUES ('87', '2017', '0.0', '0.0', '469');
INSERT INTO `_web_annual_leave` VALUES ('88', '2017', '40.0', '40.0', '319');
INSERT INTO `_web_annual_leave` VALUES ('89', '2017', '40.0', '40.0', '320');
INSERT INTO `_web_annual_leave` VALUES ('90', '2017', '0.0', '0.0', '470');
INSERT INTO `_web_annual_leave` VALUES ('91', '2017', '40.0', '5.0', '316');
INSERT INTO `_web_annual_leave` VALUES ('92', '2017', '14.0', '14.0', '441');
INSERT INTO `_web_annual_leave` VALUES ('93', '2017', '2.0', '0.0', '453');
INSERT INTO `_web_annual_leave` VALUES ('94', '2017', '40.0', '40.0', '398');
INSERT INTO `_web_annual_leave` VALUES ('95', '2017', '40.0', '24.0', '285');
INSERT INTO `_web_annual_leave` VALUES ('96', '2017', '40.0', '40.0', '344');
INSERT INTO `_web_annual_leave` VALUES ('97', '2017', '40.0', '0.0', '289');
INSERT INTO `_web_annual_leave` VALUES ('98', '2017', '29.0', '29.0', '418');
INSERT INTO `_web_annual_leave` VALUES ('99', '2017', '30.0', '16.0', '417');
INSERT INTO `_web_annual_leave` VALUES ('100', '2017', '0.0', '0.0', '298');
INSERT INTO `_web_annual_leave` VALUES ('101', '2017', '40.0', '40.0', '291');
INSERT INTO `_web_annual_leave` VALUES ('102', '2017', '40.0', '40.0', '349');
INSERT INTO `_web_annual_leave` VALUES ('103', '2017', '0.0', '0.0', '294');
INSERT INTO `_web_annual_leave` VALUES ('104', '2017', '0.0', '0.0', '485');
INSERT INTO `_web_annual_leave` VALUES ('105', '2017', '0.0', '0.0', '382');
INSERT INTO `_web_annual_leave` VALUES ('106', '2017', '22.0', '22.0', '433');
INSERT INTO `_web_annual_leave` VALUES ('107', '2017', '0.0', '0.0', '387');
INSERT INTO `_web_annual_leave` VALUES ('108', '2017', '0.0', '0.0', '395');
INSERT INTO `_web_annual_leave` VALUES ('109', '2017', '40.0', '40.0', '307');
INSERT INTO `_web_annual_leave` VALUES ('110', '2017', '3.0', '0.0', '450');
INSERT INTO `_web_annual_leave` VALUES ('111', '2017', '19.0', '19.0', '440');
INSERT INTO `_web_annual_leave` VALUES ('112', '2017', '0.0', '0.0', '306');
INSERT INTO `_web_annual_leave` VALUES ('113', '2017', '0.0', '0.0', '482');
INSERT INTO `_web_annual_leave` VALUES ('114', '2017', '40.0', '0.0', '318');
INSERT INTO `_web_annual_leave` VALUES ('115', '2017', '40.0', '40.0', '305');
INSERT INTO `_web_annual_leave` VALUES ('116', '2017', '40.0', '32.0', '308');
INSERT INTO `_web_annual_leave` VALUES ('117', '2017', '0.0', '0.0', '458');
INSERT INTO `_web_annual_leave` VALUES ('118', '2017', '0.0', '0.0', '459');
INSERT INTO `_web_annual_leave` VALUES ('119', '2017', '40.0', '40.0', '356');
INSERT INTO `_web_annual_leave` VALUES ('120', '2017', '0.0', '0.0', '487');
INSERT INTO `_web_annual_leave` VALUES ('121', '2017', '0.0', '0.0', '492');
INSERT INTO `_web_annual_leave` VALUES ('122', '2017', '0.0', '0.0', '493');
INSERT INTO `_web_annual_leave` VALUES ('123', '2017', '0.0', '0.0', '500');
INSERT INTO `_web_annual_leave` VALUES ('124', '2017', '0.0', '0.0', '488');
INSERT INTO `_web_annual_leave` VALUES ('125', '2017', '0.0', '0.0', '489');
INSERT INTO `_web_annual_leave` VALUES ('126', '2017', '0.0', '0.0', '490');
INSERT INTO `_web_annual_leave` VALUES ('127', '2017', '0.0', '0.0', '491');
INSERT INTO `_web_annual_leave` VALUES ('128', '2017', '0.0', '0.0', '494');
INSERT INTO `_web_annual_leave` VALUES ('129', '2017', '0.0', '0.0', '495');
INSERT INTO `_web_annual_leave` VALUES ('130', '2017', '0.0', '0.0', '497');
INSERT INTO `_web_annual_leave` VALUES ('131', '2017', '0.0', '0.0', '498');
INSERT INTO `_web_annual_leave` VALUES ('132', '2017', '0.0', '0.0', '499');
INSERT INTO `_web_annual_leave` VALUES ('133', '2018', '0.0', '0.0', '99');
INSERT INTO `_web_annual_leave` VALUES ('134', '2018', '40.0', '0.0', '112');
INSERT INTO `_web_annual_leave` VALUES ('135', '2018', '0.0', '0.0', '483');
INSERT INTO `_web_annual_leave` VALUES ('136', '2018', '0.0', '0.0', '474');
INSERT INTO `_web_annual_leave` VALUES ('137', '2018', '0.0', '0.0', '462');
INSERT INTO `_web_annual_leave` VALUES ('138', '2018', '0.0', '0.0', '106');
INSERT INTO `_web_annual_leave` VALUES ('139', '2018', '40.0', '25.0', '364');
INSERT INTO `_web_annual_leave` VALUES ('140', '2018', '40.0', '0.0', '116');
INSERT INTO `_web_annual_leave` VALUES ('141', '2018', '0.0', '0.0', '484');
INSERT INTO `_web_annual_leave` VALUES ('142', '2018', '40.0', '0.0', '121');
INSERT INTO `_web_annual_leave` VALUES ('143', '2018', '40.0', '8.0', '122');
INSERT INTO `_web_annual_leave` VALUES ('144', '2018', '0.0', '0.0', '486');
INSERT INTO `_web_annual_leave` VALUES ('145', '2018', '40.0', '0.0', '437');
INSERT INTO `_web_annual_leave` VALUES ('146', '2018', '0.0', '0.0', '501');
INSERT INTO `_web_annual_leave` VALUES ('147', '2018', '40.0', '35.5', '328');
INSERT INTO `_web_annual_leave` VALUES ('148', '2018', '40.0', '0.0', '415');
INSERT INTO `_web_annual_leave` VALUES ('149', '2018', '40.0', '0.0', '130');
INSERT INTO `_web_annual_leave` VALUES ('150', '2018', '40.0', '0.0', '133');
INSERT INTO `_web_annual_leave` VALUES ('151', '2018', '0.0', '0.0', '468');
INSERT INTO `_web_annual_leave` VALUES ('152', '2018', '0.0', '0.0', '481');
INSERT INTO `_web_annual_leave` VALUES ('153', '2018', '0.0', '0.0', '478');
INSERT INTO `_web_annual_leave` VALUES ('154', '2018', '39.0', '39.0', '460');
INSERT INTO `_web_annual_leave` VALUES ('155', '2018', '40.0', '8.0', '384');
INSERT INTO `_web_annual_leave` VALUES ('156', '2018', '40.0', '0.0', '412');
INSERT INTO `_web_annual_leave` VALUES ('157', '2018', '40.0', '0.0', '449');
INSERT INTO `_web_annual_leave` VALUES ('158', '2018', '40.0', '40.0', '432');
INSERT INTO `_web_annual_leave` VALUES ('159', '2018', '0.0', '0.0', '145');
INSERT INTO `_web_annual_leave` VALUES ('160', '2018', '0.0', '0.0', '480');
INSERT INTO `_web_annual_leave` VALUES ('161', '2018', '0.0', '0.0', '475');
INSERT INTO `_web_annual_leave` VALUES ('162', '2018', '40.0', '0.0', '454');
INSERT INTO `_web_annual_leave` VALUES ('163', '2018', '40.0', '0.0', '357');
INSERT INTO `_web_annual_leave` VALUES ('164', '2018', '40.0', '0.0', '390');
INSERT INTO `_web_annual_leave` VALUES ('165', '2018', '40.0', '0.0', '455');
INSERT INTO `_web_annual_leave` VALUES ('166', '2018', '40.0', '0.0', '421');
INSERT INTO `_web_annual_leave` VALUES ('167', '2018', '0.0', '0.0', '420');
INSERT INTO `_web_annual_leave` VALUES ('168', '2018', '0.0', '0.0', '396');
INSERT INTO `_web_annual_leave` VALUES ('169', '2018', '40.0', '5.5', '246');
INSERT INTO `_web_annual_leave` VALUES ('170', '2018', '0.0', '0.0', '479');
INSERT INTO `_web_annual_leave` VALUES ('171', '2018', '0.0', '0.0', '446');
INSERT INTO `_web_annual_leave` VALUES ('172', '2018', '40.0', '0.0', '456');
INSERT INTO `_web_annual_leave` VALUES ('173', '2018', '40.0', '0.0', '457');
INSERT INTO `_web_annual_leave` VALUES ('174', '2018', '32.0', '0.0', '465');
INSERT INTO `_web_annual_leave` VALUES ('175', '2018', '40.0', '0.0', '375');
INSERT INTO `_web_annual_leave` VALUES ('176', '2018', '32.0', '0.0', '466');
INSERT INTO `_web_annual_leave` VALUES ('177', '2018', '40.0', '3.0', '419');
INSERT INTO `_web_annual_leave` VALUES ('178', '2018', '40.0', '0.0', '158');
INSERT INTO `_web_annual_leave` VALUES ('179', '2018', '33.0', '0.0', '473');
INSERT INTO `_web_annual_leave` VALUES ('180', '2018', '0.0', '0.0', '351');
INSERT INTO `_web_annual_leave` VALUES ('181', '2018', '0.0', '0.0', '476');
INSERT INTO `_web_annual_leave` VALUES ('182', '2018', '40.0', '0.0', '225');
INSERT INTO `_web_annual_leave` VALUES ('183', '2018', '40.0', '0.0', '410');
INSERT INTO `_web_annual_leave` VALUES ('184', '2018', '0.0', '0.0', '428');
INSERT INTO `_web_annual_leave` VALUES ('185', '2018', '39.0', '0.0', '461');
INSERT INTO `_web_annual_leave` VALUES ('186', '2018', '0.0', '1.0', '477');
INSERT INTO `_web_annual_leave` VALUES ('187', '2018', '40.0', '0.0', '222');
INSERT INTO `_web_annual_leave` VALUES ('188', '2018', '0.0', '0.0', '199');
INSERT INTO `_web_annual_leave` VALUES ('189', '2018', '40.0', '0.0', '389');
INSERT INTO `_web_annual_leave` VALUES ('190', '2018', '40.0', '0.0', '363');
INSERT INTO `_web_annual_leave` VALUES ('191', '2018', '40.0', '0.0', '452');
INSERT INTO `_web_annual_leave` VALUES ('192', '2018', '32.0', '0.0', '463');
INSERT INTO `_web_annual_leave` VALUES ('193', '2018', '40.0', '23.0', '426');
INSERT INTO `_web_annual_leave` VALUES ('194', '2018', '40.0', '0.0', '409');
INSERT INTO `_web_annual_leave` VALUES ('195', '2018', '40.0', '0.0', '359');
INSERT INTO `_web_annual_leave` VALUES ('196', '2018', '0.0', '0.0', '423');
INSERT INTO `_web_annual_leave` VALUES ('197', '2018', '40.0', '0.0', '405');
INSERT INTO `_web_annual_leave` VALUES ('198', '2018', '40.0', '0.0', '393');
INSERT INTO `_web_annual_leave` VALUES ('199', '2018', '40.0', '0.0', '427');
INSERT INTO `_web_annual_leave` VALUES ('200', '2018', '40.0', '0.0', '218');
INSERT INTO `_web_annual_leave` VALUES ('201', '2018', '0.0', '0.0', '386');
INSERT INTO `_web_annual_leave` VALUES ('202', '2018', '40.0', '0.0', '414');
INSERT INTO `_web_annual_leave` VALUES ('203', '2018', '40.0', '0.0', '413');
INSERT INTO `_web_annual_leave` VALUES ('204', '2018', '40.0', '0.0', '416');
INSERT INTO `_web_annual_leave` VALUES ('205', '2018', '40.0', '4.5', '250');
INSERT INTO `_web_annual_leave` VALUES ('206', '2018', '40.0', '0.0', '445');
INSERT INTO `_web_annual_leave` VALUES ('207', '2018', '40.0', '0.0', '255');
INSERT INTO `_web_annual_leave` VALUES ('208', '2018', '0.0', '0.0', '467');
INSERT INTO `_web_annual_leave` VALUES ('209', '2018', '0.0', '0.0', '424');
INSERT INTO `_web_annual_leave` VALUES ('210', '2018', '0.0', '0.0', '434');
INSERT INTO `_web_annual_leave` VALUES ('211', '2018', '0.0', '0.0', '442');
INSERT INTO `_web_annual_leave` VALUES ('212', '2018', '0.0', '0.0', '370');
INSERT INTO `_web_annual_leave` VALUES ('213', '2018', '40.0', '0.0', '362');
INSERT INTO `_web_annual_leave` VALUES ('214', '2018', '40.0', '0.0', '332');
INSERT INTO `_web_annual_leave` VALUES ('215', '2018', '40.0', '19.0', '451');
INSERT INTO `_web_annual_leave` VALUES ('216', '2018', '0.0', '0.0', '276');
INSERT INTO `_web_annual_leave` VALUES ('217', '2018', '40.0', '0.0', '272');
INSERT INTO `_web_annual_leave` VALUES ('218', '2018', '40.0', '0.0', '321');
INSERT INTO `_web_annual_leave` VALUES ('219', '2018', '0.0', '0.0', '472');
INSERT INTO `_web_annual_leave` VALUES ('220', '2018', '0.0', '0.0', '469');
INSERT INTO `_web_annual_leave` VALUES ('221', '2018', '40.0', '0.0', '319');
INSERT INTO `_web_annual_leave` VALUES ('222', '2018', '40.0', '40.0', '320');
INSERT INTO `_web_annual_leave` VALUES ('223', '2018', '0.0', '0.0', '470');
INSERT INTO `_web_annual_leave` VALUES ('224', '2018', '40.0', '0.0', '316');
INSERT INTO `_web_annual_leave` VALUES ('225', '2018', '40.0', '40.0', '441');
INSERT INTO `_web_annual_leave` VALUES ('226', '2018', '40.0', '0.0', '453');
INSERT INTO `_web_annual_leave` VALUES ('227', '2018', '40.0', '0.0', '398');
INSERT INTO `_web_annual_leave` VALUES ('228', '2018', '40.0', '0.0', '285');
INSERT INTO `_web_annual_leave` VALUES ('229', '2018', '40.0', '9.0', '344');
INSERT INTO `_web_annual_leave` VALUES ('230', '2018', '40.0', '0.0', '289');
INSERT INTO `_web_annual_leave` VALUES ('231', '2018', '40.0', '0.0', '418');
INSERT INTO `_web_annual_leave` VALUES ('232', '2018', '40.0', '0.0', '417');
INSERT INTO `_web_annual_leave` VALUES ('233', '2018', '0.0', '0.0', '298');
INSERT INTO `_web_annual_leave` VALUES ('234', '2018', '40.0', '0.0', '291');
INSERT INTO `_web_annual_leave` VALUES ('235', '2018', '40.0', '6.0', '349');
INSERT INTO `_web_annual_leave` VALUES ('236', '2018', '0.0', '0.0', '294');
INSERT INTO `_web_annual_leave` VALUES ('237', '2018', '0.0', '0.0', '485');
INSERT INTO `_web_annual_leave` VALUES ('238', '2018', '0.0', '0.0', '382');
INSERT INTO `_web_annual_leave` VALUES ('239', '2018', '40.0', '34.0', '433');
INSERT INTO `_web_annual_leave` VALUES ('240', '2018', '0.0', '0.0', '387');
INSERT INTO `_web_annual_leave` VALUES ('241', '2018', '0.0', '0.0', '395');
INSERT INTO `_web_annual_leave` VALUES ('242', '2018', '40.0', '21.0', '307');
INSERT INTO `_web_annual_leave` VALUES ('243', '2018', '40.0', '0.0', '450');
INSERT INTO `_web_annual_leave` VALUES ('244', '2018', '40.0', '13.0', '440');
INSERT INTO `_web_annual_leave` VALUES ('245', '2018', '0.0', '0.0', '306');
INSERT INTO `_web_annual_leave` VALUES ('246', '2018', '0.0', '0.0', '482');
INSERT INTO `_web_annual_leave` VALUES ('247', '2018', '40.0', '0.0', '318');
INSERT INTO `_web_annual_leave` VALUES ('248', '2018', '40.0', '8.0', '305');
INSERT INTO `_web_annual_leave` VALUES ('249', '2018', '40.0', '0.0', '308');
INSERT INTO `_web_annual_leave` VALUES ('250', '2018', '40.0', '0.0', '458');
INSERT INTO `_web_annual_leave` VALUES ('251', '2018', '40.0', '0.0', '459');
INSERT INTO `_web_annual_leave` VALUES ('252', '2018', '40.0', '0.0', '356');
INSERT INTO `_web_annual_leave` VALUES ('253', '2018', '0.0', '0.0', '502');
INSERT INTO `_web_annual_leave` VALUES ('254', '2018', '0.0', '0.0', '492');
INSERT INTO `_web_annual_leave` VALUES ('255', '2018', '0.0', '0.0', '493');
INSERT INTO `_web_annual_leave` VALUES ('256', '2018', '0.0', '0.0', '500');
INSERT INTO `_web_annual_leave` VALUES ('257', '2018', '17.0', '0.0', '488');
INSERT INTO `_web_annual_leave` VALUES ('258', '2018', '17.0', '0.0', '487');
INSERT INTO `_web_annual_leave` VALUES ('259', '2018', '0.0', '0.0', '489');
INSERT INTO `_web_annual_leave` VALUES ('260', '2018', '0.0', '0.0', '490');
INSERT INTO `_web_annual_leave` VALUES ('261', '2018', '0.0', '0.0', '491');
INSERT INTO `_web_annual_leave` VALUES ('262', '2018', '0.0', '0.0', '494');
INSERT INTO `_web_annual_leave` VALUES ('263', '2018', '0.0', '0.0', '495');
INSERT INTO `_web_annual_leave` VALUES ('264', '2018', '0.0', '0.0', '497');
INSERT INTO `_web_annual_leave` VALUES ('265', '2018', '0.0', '0.0', '498');
INSERT INTO `_web_annual_leave` VALUES ('266', '2018', '13.0', '0.0', '499');
INSERT INTO `_web_annual_leave` VALUES ('267', '2018', '0.0', '0.0', '503');
INSERT INTO `_web_annual_leave` VALUES ('268', '2018', '0.0', '0.0', '504');
INSERT INTO `_web_annual_leave` VALUES ('269', '2018', '0.0', '0.0', '518');
INSERT INTO `_web_annual_leave` VALUES ('270', '2018', '0.0', '0.0', '522');
INSERT INTO `_web_annual_leave` VALUES ('271', '2018', '0.0', '0.0', '535');
INSERT INTO `_web_annual_leave` VALUES ('272', '2018', '0.0', '0.0', '523');
INSERT INTO `_web_annual_leave` VALUES ('273', '2018', '0.0', '0.0', '527');
INSERT INTO `_web_annual_leave` VALUES ('274', '2018', '0.0', '0.0', '533');
INSERT INTO `_web_annual_leave` VALUES ('275', '2018', '0.0', '0.0', '532');
INSERT INTO `_web_annual_leave` VALUES ('276', '2018', '0.0', '0.0', '519');
INSERT INTO `_web_annual_leave` VALUES ('277', '2018', '0.0', '0.0', '505');
INSERT INTO `_web_annual_leave` VALUES ('278', '2018', '0.0', '0.0', '506');
INSERT INTO `_web_annual_leave` VALUES ('279', '2018', '0.0', '0.0', '507');
INSERT INTO `_web_annual_leave` VALUES ('280', '2018', '0.0', '0.0', '508');
INSERT INTO `_web_annual_leave` VALUES ('281', '2018', '0.0', '0.0', '509');
INSERT INTO `_web_annual_leave` VALUES ('282', '2018', '0.0', '0.0', '510');
INSERT INTO `_web_annual_leave` VALUES ('283', '2018', '0.0', '0.0', '511');
INSERT INTO `_web_annual_leave` VALUES ('284', '2018', '0.0', '0.0', '528');
INSERT INTO `_web_annual_leave` VALUES ('285', '2018', '0.0', '0.0', '524');
INSERT INTO `_web_annual_leave` VALUES ('286', '2018', '0.0', '0.0', '512');
INSERT INTO `_web_annual_leave` VALUES ('287', '2018', '0.0', '0.0', '513');
INSERT INTO `_web_annual_leave` VALUES ('288', '2018', '0.0', '0.0', '514');
INSERT INTO `_web_annual_leave` VALUES ('289', '2018', '0.0', '0.0', '515');
INSERT INTO `_web_annual_leave` VALUES ('290', '2018', '0.0', '0.0', '516');
INSERT INTO `_web_annual_leave` VALUES ('291', '2018', '0.0', '0.0', '517');
INSERT INTO `_web_annual_leave` VALUES ('292', '2018', '0.0', '0.0', '520');
INSERT INTO `_web_annual_leave` VALUES ('293', '2018', '0.0', '0.0', '521');
INSERT INTO `_web_annual_leave` VALUES ('294', '2018', '0.0', '0.0', '525');
INSERT INTO `_web_annual_leave` VALUES ('295', '2018', '0.0', '0.0', '526');
INSERT INTO `_web_annual_leave` VALUES ('296', '2018', '0.0', '0.0', '531');
INSERT INTO `_web_annual_leave` VALUES ('297', '2018', '0.0', '0.0', '537');
INSERT INTO `_web_annual_leave` VALUES ('298', '2018', '0.0', '0.0', '530');
INSERT INTO `_web_annual_leave` VALUES ('299', '2018', '0.0', '0.0', '536');
INSERT INTO `_web_annual_leave` VALUES ('300', '2018', '0.0', '0.0', '538');
INSERT INTO `_web_annual_leave` VALUES ('301', '2018', '0.0', '0.0', '539');
INSERT INTO `_web_annual_leave` VALUES ('302', '2018', '0.0', '0.0', '540');
INSERT INTO `_web_annual_leave` VALUES ('303', '2018', '0.0', '0.0', '541');
INSERT INTO `_web_annual_leave` VALUES ('304', '2018', '0.0', '0.0', '542');
INSERT INTO `_web_annual_leave` VALUES ('305', '2018', '0.0', '0.0', '543');
INSERT INTO `_web_annual_leave` VALUES ('306', '2018', '0.0', '0.0', '544');
INSERT INTO `_web_annual_leave` VALUES ('307', '2018', '0.0', '0.0', '545');
INSERT INTO `_web_annual_leave` VALUES ('308', '2018', '0.0', '0.0', '546');
INSERT INTO `_web_annual_leave` VALUES ('309', '2018', '0.0', '0.0', '547');
INSERT INTO `_web_annual_leave` VALUES ('310', '2018', '0.0', '0.0', '550');
INSERT INTO `_web_annual_leave` VALUES ('311', '2018', '0.0', '0.0', '549');
INSERT INTO `_web_annual_leave` VALUES ('312', '2018', '0.0', '0.0', '548');
INSERT INTO `_web_annual_leave` VALUES ('313', '2018', '0.0', '0.0', '551');

-- ----------------------------
-- Table structure for _web_department
-- ----------------------------
DROP TABLE IF EXISTS `_web_department`;
CREATE TABLE `_web_department` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL,
  `name` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of _web_department
-- ----------------------------
INSERT INTO `_web_department` VALUES ('2', '0', '总办');
INSERT INTO `_web_department` VALUES ('6', '2', '服务端程序部');
INSERT INTO `_web_department` VALUES ('7', '2', '策划部');
INSERT INTO `_web_department` VALUES ('8', '2', '美术部');
INSERT INTO `_web_department` VALUES ('9', '2', 'QA部');
INSERT INTO `_web_department` VALUES ('10', '2', '运营部');
INSERT INTO `_web_department` VALUES ('11', '2', '客服部');
INSERT INTO `_web_department` VALUES ('12', '2', 'web开发');
INSERT INTO `_web_department` VALUES ('13', '2', '客户端程序部');
INSERT INTO `_web_department` VALUES ('14', '2', 'iphone');
INSERT INTO `_web_department` VALUES ('15', '2', 'AS3');
INSERT INTO `_web_department` VALUES ('16', '2', '市场部');
INSERT INTO `_web_department` VALUES ('17', '2', '总办审核');

-- ----------------------------
-- Table structure for _web_hugh
-- ----------------------------
DROP TABLE IF EXISTS `_web_hugh`;
CREATE TABLE `_web_hugh` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `depId` int(10) unsigned NOT NULL,
  `fromTime` varchar(20) NOT NULL,
  `toTime` varchar(20) NOT NULL,
  `totalTime` varchar(40) DEFAULT '0',
  `hour_s` char(2) NOT NULL,
  `minute_s` enum('00','30') NOT NULL DEFAULT '00',
  `hour_e` char(2) NOT NULL,
  `minute_e` enum('00','30') NOT NULL DEFAULT '00',
  `addDate` date NOT NULL,
  `latetime` int(10) unsigned NOT NULL DEFAULT '0',
  `reason` varchar(400) NOT NULL,
  `noPassDep` varchar(200) DEFAULT NULL,
  `noPassPer` varchar(200) DEFAULT NULL,
  `noPassMan` varchar(200) DEFAULT NULL,
  `depTag` enum('0','1','2') NOT NULL DEFAULT '0',
  `perTag` enum('0','1','2') NOT NULL DEFAULT '0',
  `manTag` enum('0','1','2') NOT NULL DEFAULT '0',
  `depTime` varchar(20) DEFAULT NULL,
  `perTime` varchar(20) DEFAULT NULL,
  `manTime` varchar(20) DEFAULT NULL,
  `available` char(1) NOT NULL DEFAULT '1',
  `addtag` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`,`fromTime`)
) ENGINE=MyISAM AUTO_INCREMENT=63019 DEFAULT CHARSET=utf8 COMMENT='调休表';

-- ----------------------------
-- Records of _web_hugh
-- ----------------------------
INSERT INTO `_web_hugh` VALUES ('63009', '459', '17', '2018-03-22', '2018-03-22', '1.0', '09', '00', '10', '00', '2018-03-22', '0', '', null, null, null, '0', '0', '0', null, null, null, '0', '0');
INSERT INTO `_web_hugh` VALUES ('63010', '459', '17', '2018-03-22', '2018-03-22', '1.0', '09', '00', '10', '00', '2018-03-22', '0', '\r\n			\r\n<img src=\"http://127.0.0.1:8080/admin/image/6b975291534711a13024f8eef44e6138.png\">`1111<img src=\"http://127.0.0.1:8080/admin/image/287c0e60316e6e59c972902727a44e16.png\">', null, null, null, '0', '0', '0', null, null, null, '0', '0');
INSERT INTO `_web_hugh` VALUES ('63011', '459', '17', '2018-03-22', '2018-03-22', '1.0', '09', '00', '10', '00', '2018-03-22', '0', '\r\n			\r\n<img src=\"http://127.0.0.1:8080/admin/image/0482f4e49a4f790a6a5c18e94885c43d.png\">11111', null, null, null, '0', '0', '0', null, null, null, '0', '0');
INSERT INTO `_web_hugh` VALUES ('63012', '459', '17', '2018-03-22', '2018-03-22', '1.0', '09', '00', '10', '00', '2018-03-23', '0', '\r\n			\r\n<img src=\"http://127.0.0.1:8080/admin/image//dd9f8983b60c252c0ba51f5ed015da24.png\">', null, null, null, '0', '0', '0', null, null, null, '0', '0');
INSERT INTO `_web_hugh` VALUES ('63013', '459', '17', '2018-03-22', '2018-03-22', '1.0', '09', '00', '10', '00', '2018-03-26', '0', '', null, null, null, '0', '0', '0', null, null, null, '0', '0');
INSERT INTO `_web_hugh` VALUES ('63014', '459', '17', '2018-03-22', '2018-03-22', '1.0', '09', '00', '10', '00', '2018-03-26', '0', '', null, null, null, '0', '0', '0', null, null, null, '0', '0');
INSERT INTO `_web_hugh` VALUES ('63015', '459', '17', '2018-03-22', '2018-03-22', '1.0', '09', '00', '10', '00', '2018-03-26', '0', '', null, null, null, '0', '0', '0', null, null, null, '0', '0');
INSERT INTO `_web_hugh` VALUES ('63016', '459', '17', '2018-03-22', '2018-03-22', '1.0', '09', '00', '10', '00', '2018-03-26', '0', '', null, null, null, '0', '0', '0', null, null, null, '0', '0');
INSERT INTO `_web_hugh` VALUES ('63017', '459', '17', '2018-03-22', '2018-03-22', '1.0', '09', '00', '10', '00', '2018-03-26', '0', '', null, null, null, '0', '0', '0', null, null, null, '0', '0');
INSERT INTO `_web_hugh` VALUES ('63018', '459', '17', '2018-03-22', '2018-03-22', '1.0', '09', '00', '10', '00', '2018-03-26', '0', '\r\n			\r\n111', null, null, null, '0', '0', '0', null, null, null, '1', '0');

-- ----------------------------
-- Table structure for _web_hugh_pass
-- ----------------------------
DROP TABLE IF EXISTS `_web_hugh_pass`;
CREATE TABLE `_web_hugh_pass` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `hughdate` date DEFAULT NULL,
  `adddate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2235 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of _web_hugh_pass
-- ----------------------------
INSERT INTO `_web_hugh_pass` VALUES ('2226', '459', '2018-03-22', '2018-03-22 17:36:22');
INSERT INTO `_web_hugh_pass` VALUES ('2227', '459', '2018-03-23', '2018-03-22 17:36:23');
INSERT INTO `_web_hugh_pass` VALUES ('2228', '459', '2018-03-28', '2018-03-22 17:36:25');
INSERT INTO `_web_hugh_pass` VALUES ('2229', '459', '2018-03-21', '2018-03-22 17:36:26');
INSERT INTO `_web_hugh_pass` VALUES ('2230', '459', '2018-03-27', '2018-03-22 17:36:28');
INSERT INTO `_web_hugh_pass` VALUES ('2231', '459', '2018-03-26', '2018-03-22 17:36:29');
INSERT INTO `_web_hugh_pass` VALUES ('2232', '459', '2018-03-29', '2018-03-22 17:36:31');
INSERT INTO `_web_hugh_pass` VALUES ('2233', '459', '2018-03-20', '2018-03-22 17:36:32');
INSERT INTO `_web_hugh_pass` VALUES ('2234', '459', '2018-03-19', '2018-03-22 17:36:34');

-- ----------------------------
-- Table structure for _web_job
-- ----------------------------
DROP TABLE IF EXISTS `_web_job`;
CREATE TABLE `_web_job` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL,
  `name` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of _web_job
-- ----------------------------
INSERT INTO `_web_job` VALUES ('9', '2', 'QA主管');
INSERT INTO `_web_job` VALUES ('8', '2', '总经理');
INSERT INTO `_web_job` VALUES ('3', '2', '行政员');
INSERT INTO `_web_job` VALUES ('10', '9', 'QA');
INSERT INTO `_web_job` VALUES ('11', '6', '服务端程序主管');
INSERT INTO `_web_job` VALUES ('7', '2', '财务');
INSERT INTO `_web_job` VALUES ('12', '6', '服务端程序员');
INSERT INTO `_web_job` VALUES ('13', '7', '策划主管');
INSERT INTO `_web_job` VALUES ('14', '7', '策划员');
INSERT INTO `_web_job` VALUES ('15', '8', '美术主管');
INSERT INTO `_web_job` VALUES ('16', '8', '美术');
INSERT INTO `_web_job` VALUES ('17', '10', '运营主管');
INSERT INTO `_web_job` VALUES ('18', '10', '运营');
INSERT INTO `_web_job` VALUES ('19', '11', '客服主管');
INSERT INTO `_web_job` VALUES ('20', '11', '客服');
INSERT INTO `_web_job` VALUES ('21', '12', 'web主管');
INSERT INTO `_web_job` VALUES ('22', '12', 'web开发员');
INSERT INTO `_web_job` VALUES ('23', '13', '客户端程序主管');
INSERT INTO `_web_job` VALUES ('24', '13', '客户端程序员');
INSERT INTO `_web_job` VALUES ('25', '14', 'iphone程序员');
INSERT INTO `_web_job` VALUES ('26', '14', 'iphone主管');
INSERT INTO `_web_job` VALUES ('27', '15', 'AS3主管');
INSERT INTO `_web_job` VALUES ('28', '15', 'AS3程序员');
INSERT INTO `_web_job` VALUES ('29', '16', '广告投放专员');
INSERT INTO `_web_job` VALUES ('30', '2', '英语翻译');

-- ----------------------------
-- Table structure for _web_leave
-- ----------------------------
DROP TABLE IF EXISTS `_web_leave`;
CREATE TABLE `_web_leave` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `leaveType` varchar(40) NOT NULL,
  `depId` int(10) unsigned NOT NULL,
  `fromTime` varchar(20) NOT NULL,
  `toTime` varchar(20) NOT NULL,
  `timeDetail` varchar(255) DEFAULT NULL COMMENT '年假_区分去年跟今年',
  `totalTime` varchar(40) DEFAULT '0',
  `hour_s` char(2) NOT NULL,
  `minute_s` enum('00','30') NOT NULL DEFAULT '00',
  `hour_e` char(2) NOT NULL,
  `minute_e` enum('00','30') NOT NULL DEFAULT '00',
  `addDate` date NOT NULL,
  `reason` varchar(400) NOT NULL,
  `noPassDep` varchar(200) DEFAULT NULL,
  `noPassPer` varchar(200) DEFAULT NULL,
  `noPassMan` varchar(200) DEFAULT NULL,
  `depTag` enum('0','1','2') NOT NULL DEFAULT '0',
  `perTag` enum('0','1','2') NOT NULL DEFAULT '0',
  `manTag` enum('0','1','2') NOT NULL DEFAULT '0',
  `depTime` varchar(20) DEFAULT NULL,
  `perTime` varchar(20) DEFAULT NULL,
  `manTime` varchar(20) DEFAULT NULL,
  `available` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3911 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of _web_leave
-- ----------------------------
INSERT INTO `_web_leave` VALUES ('3910', '459', '哺乳假', '17', '2018-04-06', '2018-04-06', null, '4.0', '08', '00', '12', '00', '2018-05-03', '<p>&nbsp;1</p>', null, null, null, '0', '0', '0', null, null, null, '1');

-- ----------------------------
-- Table structure for _web_leave_filing
-- ----------------------------
DROP TABLE IF EXISTS `_web_leave_filing`;
CREATE TABLE `_web_leave_filing` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `depId` int(11) NOT NULL,
  `fromTime` date NOT NULL,
  `totalTime` varchar(20) NOT NULL,
  `hour_s` char(2) NOT NULL,
  `minute_s` enum('30','00') NOT NULL,
  `hour_e` char(2) NOT NULL,
  `minute_e` enum('30','00') NOT NULL,
  `toTime` date NOT NULL,
  `addDate` date NOT NULL,
  `reason` varchar(255) NOT NULL,
  `available` char(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8916 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of _web_leave_filing
-- ----------------------------

-- ----------------------------
-- Table structure for _web_news
-- ----------------------------
DROP TABLE IF EXISTS `_web_news`;
CREATE TABLE `_web_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hit` int(11) DEFAULT '0',
  `tab` varchar(255) DEFAULT NULL,
  `tabid` int(11) DEFAULT NULL,
  `ntype` int(11) DEFAULT '0',
  `imgurl` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `titlepy` varchar(255) DEFAULT NULL,
  `titlesub` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `formsite` varchar(255) DEFAULT NULL,
  `newsdt` datetime NOT NULL,
  `showtag` tinyint(4) NOT NULL,
  `systag` tinyint(4) DEFAULT '0',
  `descno` int(11) DEFAULT '100',
  PRIMARY KEY (`id`),
  KEY `tab` (`tab`,`tabid`),
  KEY `systag` (`systag`),
  KEY `titlepy` (`titlepy`),
  KEY `hit` (`hit`),
  KEY `ntype` (`ntype`),
  KEY `showtag` (`showtag`),
  KEY `descno` (`descno`)
) ENGINE=MyISAM AUTO_INCREMENT=105 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of _web_news
-- ----------------------------

-- ----------------------------
-- Table structure for _web_oddtime
-- ----------------------------
DROP TABLE IF EXISTS `_web_oddtime`;
CREATE TABLE `_web_oddtime` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `depId` int(10) unsigned NOT NULL,
  `supdate` varchar(20) NOT NULL,
  `amstart` varchar(10) NOT NULL,
  `amend` varchar(10) NOT NULL,
  `pmstart` varchar(10) NOT NULL,
  `pmend` varchar(10) NOT NULL,
  `addtime` varchar(60) DEFAULT NULL,
  `addDate` date NOT NULL,
  `reason` varchar(400) NOT NULL,
  `noPassDep` varchar(200) DEFAULT NULL,
  `noPassPer` varchar(200) DEFAULT NULL,
  `noPassMan` varchar(200) DEFAULT NULL,
  `depTag` enum('0','1','2') NOT NULL DEFAULT '0',
  `perTag` enum('0','1','2') NOT NULL DEFAULT '0',
  `manTag` enum('0','1','2') NOT NULL DEFAULT '0',
  `depTime` varchar(20) DEFAULT NULL,
  `perTime` varchar(20) DEFAULT NULL,
  `manTime` varchar(20) DEFAULT NULL,
  `available` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6015 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of _web_oddtime
-- ----------------------------
INSERT INTO `_web_oddtime` VALUES ('6014', '459', '17', '2018-04-02', '', '12:00', '', '', '12:00', '2018-04-03', 'dfsdfsfds&lt;&gt;', null, null, null, '0', '0', '0', null, null, null, '1');

-- ----------------------------
-- Table structure for _web_other_leave
-- ----------------------------
DROP TABLE IF EXISTS `_web_other_leave`;
CREATE TABLE `_web_other_leave` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `allTime` decimal(11,1) NOT NULL DEFAULT '0.0' COMMENT '总时长',
  `useTime` decimal(11,1) unsigned NOT NULL DEFAULT '0.0' COMMENT '使用时长',
  `uid` int(11) NOT NULL COMMENT '员工编号',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk` (`uid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of _web_other_leave
-- ----------------------------
INSERT INTO `_web_other_leave` VALUES ('1', '125.0', '4.0', '459');

-- ----------------------------
-- Table structure for _web_outrecord
-- ----------------------------
DROP TABLE IF EXISTS `_web_outrecord`;
CREATE TABLE `_web_outrecord` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `depId` int(10) unsigned NOT NULL,
  `fromTime` varchar(20) NOT NULL,
  `toTime` varchar(20) NOT NULL,
  `totalTime` varchar(40) DEFAULT '0',
  `totalM` int(10) unsigned NOT NULL DEFAULT '0',
  `hour_s` char(2) NOT NULL,
  `minute_s` char(2) NOT NULL DEFAULT '00',
  `hour_e` char(2) NOT NULL,
  `minute_e` char(2) NOT NULL DEFAULT '00',
  `addDate` date NOT NULL,
  `reason` varchar(400) NOT NULL,
  `noPassDep` varchar(200) DEFAULT NULL,
  `noPassPer` varchar(200) DEFAULT NULL,
  `noPassMan` varchar(200) DEFAULT NULL,
  `depTag` enum('0','1','2') NOT NULL DEFAULT '0',
  `perTag` enum('0','1','2') NOT NULL DEFAULT '0',
  `manTag` enum('0','1','2') NOT NULL DEFAULT '0',
  `depTime` varchar(20) DEFAULT NULL,
  `perTime` varchar(20) DEFAULT NULL,
  `manTime` varchar(20) DEFAULT NULL,
  `available` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1601 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of _web_outrecord
-- ----------------------------

-- ----------------------------
-- Table structure for _web_overtime
-- ----------------------------
DROP TABLE IF EXISTS `_web_overtime`;
CREATE TABLE `_web_overtime` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `depId` int(10) unsigned NOT NULL,
  `fromTime` varchar(20) NOT NULL,
  `toTime` varchar(20) NOT NULL,
  `totalTime` varchar(40) DEFAULT '0',
  `hour_s` char(2) NOT NULL,
  `minute_s` enum('00','30') NOT NULL DEFAULT '00',
  `hour_e` char(2) NOT NULL,
  `minute_e` enum('00','30') NOT NULL DEFAULT '00',
  `addDate` date NOT NULL,
  `reason` varchar(400) NOT NULL,
  `noPassDep` varchar(200) DEFAULT NULL,
  `noPassPer` varchar(200) DEFAULT NULL,
  `noPassMan` varchar(200) DEFAULT NULL,
  `depTag` enum('0','1','2') NOT NULL DEFAULT '0',
  `perTag` enum('0','1','2') NOT NULL DEFAULT '0',
  `manTag` enum('0','1','2') NOT NULL DEFAULT '0',
  `depTime` varchar(20) DEFAULT NULL,
  `perTime` varchar(20) DEFAULT NULL,
  `manTime` varchar(20) DEFAULT NULL,
  `available` char(1) NOT NULL DEFAULT '1',
  `addtag` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=40361 DEFAULT CHARSET=utf8 COMMENT='加班表';

-- ----------------------------
-- Records of _web_overtime
-- ----------------------------
INSERT INTO `_web_overtime` VALUES ('40358', '459', '17', '2018-02-04', '2018-02-04', '4.5', '', '00', '', '00', '0000-00-00', '', null, null, null, '2', '2', '2', null, null, null, '1', '1');
INSERT INTO `_web_overtime` VALUES ('40359', '459', '17', '2018-03-21', '2018-03-21', '2.0', '09', '00', '11', '00', '2018-03-22', '\r\n			\r\n<img src=\"http://127.0.0.1:8080/admin/image/cf2d1820183b7f94f78336146f901094.png\">111', null, null, null, '0', '0', '0', null, null, null, '1', '0');
INSERT INTO `_web_overtime` VALUES ('40360', '459', '17', '2018-04-06', '2018-04-06', '2.0', '08', '00', '10', '00', '2018-04-08', '1232', null, null, null, '0', '0', '0', null, null, null, '1', '0');

-- ----------------------------
-- Table structure for _web_product
-- ----------------------------
DROP TABLE IF EXISTS `_web_product`;
CREATE TABLE `_web_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `time` varchar(20) DEFAULT NULL,
  `size` varchar(100) DEFAULT NULL,
  `case` varchar(100) DEFAULT NULL,
  `number` int(11) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `youtobe` varchar(255) DEFAULT NULL,
  `showtag` int(11) NOT NULL DEFAULT '1',
  `small_img` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of _web_product
-- ----------------------------

-- ----------------------------
-- Table structure for _web_record
-- ----------------------------
DROP TABLE IF EXISTS `_web_record`;
CREATE TABLE `_web_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `card_id` int(10) unsigned NOT NULL,
  `gong_id` int(10) unsigned NOT NULL,
  `name` varchar(200) NOT NULL,
  `depname` varchar(200) CHARACTER SET gb2312 COLLATE gb2312_bin NOT NULL,
  `addtime` varchar(1000) CHARACTER SET gb2312 COLLATE gb2312_bin NOT NULL,
  `descri` varchar(50) CHARACTER SET gb2312 COLLATE gb2312_bin NOT NULL,
  `recorddate` date NOT NULL,
  `addtime_ex` varchar(1000) CHARACTER SET gb2312 COLLATE gb2312_bin DEFAULT NULL,
  `latetime` int(11) unsigned DEFAULT '0',
  `totaltime` int(11) unsigned DEFAULT '0',
  `totalall` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`card_id`,`recorddate`),
  UNIQUE KEY `id` (`id`),
  KEY `recorddate` (`recorddate`),
  KEY `card_date` (`card_id`,`recorddate`)
) ENGINE=MyISAM AUTO_INCREMENT=306271 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of _web_record
-- ----------------------------

-- ----------------------------
-- Table structure for _web_recorddata
-- ----------------------------
DROP TABLE IF EXISTS `_web_recorddata`;
CREATE TABLE `_web_recorddata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(4) unsigned NOT NULL DEFAULT '0',
  `status` int(4) unsigned NOT NULL DEFAULT '0',
  `card_id` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=36845555 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of _web_recorddata
-- ----------------------------

-- ----------------------------
-- Table structure for _web_sign
-- ----------------------------
DROP TABLE IF EXISTS `_web_sign`;
CREATE TABLE `_web_sign` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `depId` int(10) unsigned NOT NULL,
  `addDate` varchar(20) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `noPassDep` varchar(200) DEFAULT NULL,
  `noPassPer` varchar(200) DEFAULT NULL,
  `noPassMan` varchar(200) DEFAULT NULL,
  `depTag` enum('0','1','2') NOT NULL DEFAULT '0',
  `perTag` enum('0','1','2') NOT NULL DEFAULT '0',
  `manTag` enum('0','1','2') NOT NULL DEFAULT '0',
  `depTime` varchar(20) DEFAULT NULL,
  `perTime` varchar(20) DEFAULT NULL,
  `manTime` varchar(20) DEFAULT NULL,
  `available` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=330 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of _web_sign
-- ----------------------------

-- ----------------------------
-- Table structure for _web_workday
-- ----------------------------
DROP TABLE IF EXISTS `_web_workday`;
CREATE TABLE `_web_workday` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `workday` date NOT NULL,
  `tag` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `workday` (`workday`)
) ENGINE=MyISAM AUTO_INCREMENT=2739 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of _web_workday
-- ----------------------------
INSERT INTO `_web_workday` VALUES ('1', '2010-12-01', '1');
INSERT INTO `_web_workday` VALUES ('2', '2010-12-02', '1');
INSERT INTO `_web_workday` VALUES ('3', '2010-12-03', '1');
INSERT INTO `_web_workday` VALUES ('4', '2010-12-04', '0');
INSERT INTO `_web_workday` VALUES ('5', '2010-12-05', '0');
INSERT INTO `_web_workday` VALUES ('6', '2010-12-06', '1');
INSERT INTO `_web_workday` VALUES ('7', '2010-12-07', '1');
INSERT INTO `_web_workday` VALUES ('8', '2010-12-08', '1');
INSERT INTO `_web_workday` VALUES ('9', '2010-12-09', '1');
INSERT INTO `_web_workday` VALUES ('10', '2010-12-10', '1');
INSERT INTO `_web_workday` VALUES ('11', '2010-12-11', '0');
INSERT INTO `_web_workday` VALUES ('12', '2010-12-12', '0');
INSERT INTO `_web_workday` VALUES ('13', '2010-12-13', '1');
INSERT INTO `_web_workday` VALUES ('14', '2010-12-14', '1');
INSERT INTO `_web_workday` VALUES ('15', '2010-12-15', '1');
INSERT INTO `_web_workday` VALUES ('16', '2010-12-16', '1');
INSERT INTO `_web_workday` VALUES ('17', '2010-12-17', '1');
INSERT INTO `_web_workday` VALUES ('18', '2010-12-18', '0');
INSERT INTO `_web_workday` VALUES ('19', '2010-12-19', '0');
INSERT INTO `_web_workday` VALUES ('20', '2010-12-20', '1');
INSERT INTO `_web_workday` VALUES ('21', '2010-12-21', '1');
INSERT INTO `_web_workday` VALUES ('22', '2010-12-22', '1');
INSERT INTO `_web_workday` VALUES ('23', '2010-12-23', '1');
INSERT INTO `_web_workday` VALUES ('24', '2010-12-24', '1');
INSERT INTO `_web_workday` VALUES ('25', '2010-12-25', '0');
INSERT INTO `_web_workday` VALUES ('26', '2010-12-26', '0');
INSERT INTO `_web_workday` VALUES ('27', '2010-12-27', '1');
INSERT INTO `_web_workday` VALUES ('28', '2010-12-28', '1');
INSERT INTO `_web_workday` VALUES ('29', '2010-12-29', '1');
INSERT INTO `_web_workday` VALUES ('30', '2010-12-30', '1');
INSERT INTO `_web_workday` VALUES ('31', '2010-12-31', '1');
INSERT INTO `_web_workday` VALUES ('32', '2011-01-01', '0');
INSERT INTO `_web_workday` VALUES ('33', '2011-01-02', '0');
INSERT INTO `_web_workday` VALUES ('34', '2011-01-03', '0');
INSERT INTO `_web_workday` VALUES ('35', '2011-01-04', '1');
INSERT INTO `_web_workday` VALUES ('36', '2011-01-05', '1');
INSERT INTO `_web_workday` VALUES ('37', '2011-01-06', '1');
INSERT INTO `_web_workday` VALUES ('38', '2011-01-07', '1');
INSERT INTO `_web_workday` VALUES ('39', '2011-01-08', '0');
INSERT INTO `_web_workday` VALUES ('40', '2011-01-09', '0');
INSERT INTO `_web_workday` VALUES ('41', '2011-01-10', '1');
INSERT INTO `_web_workday` VALUES ('42', '2011-01-11', '1');
INSERT INTO `_web_workday` VALUES ('43', '2011-01-12', '1');
INSERT INTO `_web_workday` VALUES ('44', '2011-01-13', '1');
INSERT INTO `_web_workday` VALUES ('45', '2011-01-14', '1');
INSERT INTO `_web_workday` VALUES ('46', '2011-01-15', '0');
INSERT INTO `_web_workday` VALUES ('47', '2011-01-16', '0');
INSERT INTO `_web_workday` VALUES ('48', '2011-01-17', '1');
INSERT INTO `_web_workday` VALUES ('49', '2011-01-18', '1');
INSERT INTO `_web_workday` VALUES ('50', '2011-01-19', '1');
INSERT INTO `_web_workday` VALUES ('51', '2011-01-20', '1');
INSERT INTO `_web_workday` VALUES ('52', '2011-01-21', '1');
INSERT INTO `_web_workday` VALUES ('53', '2011-01-22', '0');
INSERT INTO `_web_workday` VALUES ('54', '2011-01-23', '0');
INSERT INTO `_web_workday` VALUES ('55', '2011-01-24', '1');
INSERT INTO `_web_workday` VALUES ('56', '2011-01-25', '1');
INSERT INTO `_web_workday` VALUES ('57', '2011-01-26', '1');
INSERT INTO `_web_workday` VALUES ('58', '2011-01-27', '1');
INSERT INTO `_web_workday` VALUES ('59', '2011-01-28', '1');
INSERT INTO `_web_workday` VALUES ('60', '2011-01-29', '1');
INSERT INTO `_web_workday` VALUES ('61', '2011-01-30', '1');
INSERT INTO `_web_workday` VALUES ('62', '2011-01-31', '1');
INSERT INTO `_web_workday` VALUES ('63', '2011-02-01', '0');
INSERT INTO `_web_workday` VALUES ('64', '2011-02-02', '0');
INSERT INTO `_web_workday` VALUES ('65', '2011-02-03', '0');
INSERT INTO `_web_workday` VALUES ('66', '2011-02-04', '0');
INSERT INTO `_web_workday` VALUES ('67', '2011-02-05', '0');
INSERT INTO `_web_workday` VALUES ('68', '2011-02-06', '0');
INSERT INTO `_web_workday` VALUES ('69', '2011-02-07', '0');
INSERT INTO `_web_workday` VALUES ('70', '2011-02-08', '0');
INSERT INTO `_web_workday` VALUES ('71', '2011-02-09', '1');
INSERT INTO `_web_workday` VALUES ('72', '2011-02-10', '1');
INSERT INTO `_web_workday` VALUES ('73', '2011-02-11', '1');
INSERT INTO `_web_workday` VALUES ('74', '2011-02-12', '1');
INSERT INTO `_web_workday` VALUES ('75', '2011-02-13', '0');
INSERT INTO `_web_workday` VALUES ('76', '2011-02-14', '1');
INSERT INTO `_web_workday` VALUES ('77', '2011-02-15', '1');
INSERT INTO `_web_workday` VALUES ('78', '2011-02-16', '1');
INSERT INTO `_web_workday` VALUES ('79', '2011-02-17', '1');
INSERT INTO `_web_workday` VALUES ('80', '2011-02-18', '1');
INSERT INTO `_web_workday` VALUES ('81', '2011-02-19', '0');
INSERT INTO `_web_workday` VALUES ('82', '2011-02-20', '0');
INSERT INTO `_web_workday` VALUES ('83', '2011-02-21', '1');
INSERT INTO `_web_workday` VALUES ('84', '2011-02-22', '1');
INSERT INTO `_web_workday` VALUES ('85', '2011-02-23', '1');
INSERT INTO `_web_workday` VALUES ('86', '2011-02-24', '1');
INSERT INTO `_web_workday` VALUES ('87', '2011-02-25', '1');
INSERT INTO `_web_workday` VALUES ('88', '2011-02-26', '0');
INSERT INTO `_web_workday` VALUES ('89', '2011-02-27', '0');
INSERT INTO `_web_workday` VALUES ('90', '2011-02-28', '1');
INSERT INTO `_web_workday` VALUES ('91', '2011-03-01', '1');
INSERT INTO `_web_workday` VALUES ('92', '2011-03-02', '1');
INSERT INTO `_web_workday` VALUES ('93', '2011-03-03', '1');
INSERT INTO `_web_workday` VALUES ('94', '2011-03-04', '1');
INSERT INTO `_web_workday` VALUES ('95', '2011-03-05', '0');
INSERT INTO `_web_workday` VALUES ('96', '2011-03-06', '0');
INSERT INTO `_web_workday` VALUES ('97', '2011-03-07', '1');
INSERT INTO `_web_workday` VALUES ('98', '2011-03-08', '1');
INSERT INTO `_web_workday` VALUES ('99', '2011-03-09', '1');
INSERT INTO `_web_workday` VALUES ('100', '2011-03-10', '1');
INSERT INTO `_web_workday` VALUES ('101', '2011-03-11', '1');
INSERT INTO `_web_workday` VALUES ('102', '2011-03-12', '0');
INSERT INTO `_web_workday` VALUES ('103', '2011-03-13', '0');
INSERT INTO `_web_workday` VALUES ('104', '2011-03-14', '1');
INSERT INTO `_web_workday` VALUES ('105', '2011-03-15', '1');
INSERT INTO `_web_workday` VALUES ('106', '2011-03-16', '1');
INSERT INTO `_web_workday` VALUES ('107', '2011-03-17', '1');
INSERT INTO `_web_workday` VALUES ('108', '2011-03-18', '1');
INSERT INTO `_web_workday` VALUES ('109', '2011-03-19', '0');
INSERT INTO `_web_workday` VALUES ('110', '2011-03-20', '0');
INSERT INTO `_web_workday` VALUES ('111', '2011-03-21', '1');
INSERT INTO `_web_workday` VALUES ('112', '2011-03-22', '1');
INSERT INTO `_web_workday` VALUES ('113', '2011-03-23', '1');
INSERT INTO `_web_workday` VALUES ('114', '2011-03-24', '1');
INSERT INTO `_web_workday` VALUES ('115', '2011-03-25', '1');
INSERT INTO `_web_workday` VALUES ('116', '2011-03-26', '0');
INSERT INTO `_web_workday` VALUES ('117', '2011-03-27', '0');
INSERT INTO `_web_workday` VALUES ('118', '2011-03-28', '1');
INSERT INTO `_web_workday` VALUES ('119', '2011-03-29', '1');
INSERT INTO `_web_workday` VALUES ('120', '2011-03-30', '1');
INSERT INTO `_web_workday` VALUES ('121', '2011-03-31', '1');
INSERT INTO `_web_workday` VALUES ('122', '2011-04-01', '1');
INSERT INTO `_web_workday` VALUES ('123', '2011-04-02', '1');
INSERT INTO `_web_workday` VALUES ('124', '2011-04-03', '0');
INSERT INTO `_web_workday` VALUES ('125', '2011-04-04', '0');
INSERT INTO `_web_workday` VALUES ('126', '2011-04-05', '0');
INSERT INTO `_web_workday` VALUES ('127', '2011-04-06', '1');
INSERT INTO `_web_workday` VALUES ('128', '2011-04-07', '1');
INSERT INTO `_web_workday` VALUES ('129', '2011-04-08', '1');
INSERT INTO `_web_workday` VALUES ('130', '2011-04-09', '0');
INSERT INTO `_web_workday` VALUES ('131', '2011-04-10', '0');
INSERT INTO `_web_workday` VALUES ('132', '2011-04-11', '1');
INSERT INTO `_web_workday` VALUES ('133', '2011-04-12', '1');
INSERT INTO `_web_workday` VALUES ('134', '2011-04-13', '1');
INSERT INTO `_web_workday` VALUES ('135', '2011-04-14', '1');
INSERT INTO `_web_workday` VALUES ('136', '2011-04-15', '1');
INSERT INTO `_web_workday` VALUES ('137', '2011-04-16', '0');
INSERT INTO `_web_workday` VALUES ('138', '2011-04-17', '0');
INSERT INTO `_web_workday` VALUES ('139', '2011-04-18', '1');
INSERT INTO `_web_workday` VALUES ('140', '2011-04-19', '1');
INSERT INTO `_web_workday` VALUES ('141', '2011-04-20', '1');
INSERT INTO `_web_workday` VALUES ('142', '2011-04-21', '1');
INSERT INTO `_web_workday` VALUES ('143', '2011-04-22', '0');
INSERT INTO `_web_workday` VALUES ('144', '2011-04-23', '1');
INSERT INTO `_web_workday` VALUES ('145', '2011-04-24', '0');
INSERT INTO `_web_workday` VALUES ('146', '2011-04-25', '1');
INSERT INTO `_web_workday` VALUES ('147', '2011-04-26', '1');
INSERT INTO `_web_workday` VALUES ('148', '2011-04-27', '1');
INSERT INTO `_web_workday` VALUES ('149', '2011-04-28', '1');
INSERT INTO `_web_workday` VALUES ('150', '2011-04-29', '1');
INSERT INTO `_web_workday` VALUES ('151', '2011-04-30', '0');
INSERT INTO `_web_workday` VALUES ('152', '2011-05-01', '0');
INSERT INTO `_web_workday` VALUES ('153', '2011-05-02', '0');
INSERT INTO `_web_workday` VALUES ('154', '2011-05-03', '1');
INSERT INTO `_web_workday` VALUES ('155', '2011-05-04', '1');
INSERT INTO `_web_workday` VALUES ('156', '2011-05-05', '1');
INSERT INTO `_web_workday` VALUES ('157', '2011-05-06', '1');
INSERT INTO `_web_workday` VALUES ('158', '2011-05-07', '0');
INSERT INTO `_web_workday` VALUES ('159', '2011-05-08', '0');
INSERT INTO `_web_workday` VALUES ('160', '2011-05-09', '1');
INSERT INTO `_web_workday` VALUES ('161', '2011-05-10', '1');
INSERT INTO `_web_workday` VALUES ('162', '2011-05-11', '1');
INSERT INTO `_web_workday` VALUES ('163', '2011-05-12', '1');
INSERT INTO `_web_workday` VALUES ('164', '2011-05-13', '1');
INSERT INTO `_web_workday` VALUES ('165', '2011-05-14', '0');
INSERT INTO `_web_workday` VALUES ('166', '2011-05-15', '0');
INSERT INTO `_web_workday` VALUES ('167', '2011-05-16', '1');
INSERT INTO `_web_workday` VALUES ('168', '2011-05-17', '1');
INSERT INTO `_web_workday` VALUES ('169', '2011-05-18', '1');
INSERT INTO `_web_workday` VALUES ('170', '2011-05-19', '1');
INSERT INTO `_web_workday` VALUES ('171', '2011-05-20', '1');
INSERT INTO `_web_workday` VALUES ('172', '2011-05-21', '0');
INSERT INTO `_web_workday` VALUES ('173', '2011-05-22', '0');
INSERT INTO `_web_workday` VALUES ('174', '2011-05-23', '1');
INSERT INTO `_web_workday` VALUES ('175', '2011-05-24', '1');
INSERT INTO `_web_workday` VALUES ('176', '2011-05-25', '1');
INSERT INTO `_web_workday` VALUES ('177', '2011-05-26', '1');
INSERT INTO `_web_workday` VALUES ('178', '2011-05-27', '1');
INSERT INTO `_web_workday` VALUES ('179', '2011-05-28', '0');
INSERT INTO `_web_workday` VALUES ('180', '2011-05-29', '0');
INSERT INTO `_web_workday` VALUES ('181', '2011-05-30', '1');
INSERT INTO `_web_workday` VALUES ('182', '2011-05-31', '1');
INSERT INTO `_web_workday` VALUES ('183', '2011-06-01', '1');
INSERT INTO `_web_workday` VALUES ('184', '2011-06-02', '1');
INSERT INTO `_web_workday` VALUES ('185', '2011-06-03', '1');
INSERT INTO `_web_workday` VALUES ('186', '2011-06-04', '0');
INSERT INTO `_web_workday` VALUES ('187', '2011-06-05', '0');
INSERT INTO `_web_workday` VALUES ('188', '2011-06-06', '0');
INSERT INTO `_web_workday` VALUES ('189', '2011-06-07', '1');
INSERT INTO `_web_workday` VALUES ('190', '2011-06-08', '1');
INSERT INTO `_web_workday` VALUES ('191', '2011-06-09', '1');
INSERT INTO `_web_workday` VALUES ('192', '2011-06-10', '1');
INSERT INTO `_web_workday` VALUES ('193', '2011-06-11', '0');
INSERT INTO `_web_workday` VALUES ('194', '2011-06-12', '0');
INSERT INTO `_web_workday` VALUES ('195', '2011-06-13', '1');
INSERT INTO `_web_workday` VALUES ('196', '2011-06-14', '1');
INSERT INTO `_web_workday` VALUES ('197', '2011-06-15', '1');
INSERT INTO `_web_workday` VALUES ('198', '2011-06-16', '1');
INSERT INTO `_web_workday` VALUES ('199', '2011-06-17', '1');
INSERT INTO `_web_workday` VALUES ('200', '2011-06-18', '0');
INSERT INTO `_web_workday` VALUES ('201', '2011-06-19', '0');
INSERT INTO `_web_workday` VALUES ('202', '2011-06-20', '1');
INSERT INTO `_web_workday` VALUES ('203', '2011-06-21', '1');
INSERT INTO `_web_workday` VALUES ('204', '2011-06-22', '1');
INSERT INTO `_web_workday` VALUES ('205', '2011-06-23', '1');
INSERT INTO `_web_workday` VALUES ('206', '2011-06-24', '1');
INSERT INTO `_web_workday` VALUES ('207', '2011-06-25', '0');
INSERT INTO `_web_workday` VALUES ('208', '2011-06-26', '0');
INSERT INTO `_web_workday` VALUES ('209', '2011-06-27', '1');
INSERT INTO `_web_workday` VALUES ('210', '2011-06-28', '1');
INSERT INTO `_web_workday` VALUES ('211', '2011-06-29', '1');
INSERT INTO `_web_workday` VALUES ('212', '2011-06-30', '1');
INSERT INTO `_web_workday` VALUES ('213', '2011-07-01', '1');
INSERT INTO `_web_workday` VALUES ('214', '2011-07-02', '0');
INSERT INTO `_web_workday` VALUES ('215', '2011-07-03', '0');
INSERT INTO `_web_workday` VALUES ('216', '2011-07-04', '1');
INSERT INTO `_web_workday` VALUES ('217', '2011-07-05', '1');
INSERT INTO `_web_workday` VALUES ('218', '2011-07-06', '1');
INSERT INTO `_web_workday` VALUES ('219', '2011-07-07', '1');
INSERT INTO `_web_workday` VALUES ('220', '2011-07-08', '1');
INSERT INTO `_web_workday` VALUES ('221', '2011-07-09', '0');
INSERT INTO `_web_workday` VALUES ('222', '2011-07-10', '0');
INSERT INTO `_web_workday` VALUES ('223', '2011-07-11', '1');
INSERT INTO `_web_workday` VALUES ('224', '2011-07-12', '1');
INSERT INTO `_web_workday` VALUES ('225', '2011-07-13', '1');
INSERT INTO `_web_workday` VALUES ('226', '2011-07-14', '1');
INSERT INTO `_web_workday` VALUES ('227', '2011-07-15', '1');
INSERT INTO `_web_workday` VALUES ('228', '2011-07-16', '0');
INSERT INTO `_web_workday` VALUES ('229', '2011-07-17', '0');
INSERT INTO `_web_workday` VALUES ('230', '2011-07-18', '1');
INSERT INTO `_web_workday` VALUES ('231', '2011-07-19', '1');
INSERT INTO `_web_workday` VALUES ('232', '2011-07-20', '1');
INSERT INTO `_web_workday` VALUES ('233', '2011-07-21', '1');
INSERT INTO `_web_workday` VALUES ('234', '2011-07-22', '1');
INSERT INTO `_web_workday` VALUES ('235', '2011-07-23', '0');
INSERT INTO `_web_workday` VALUES ('236', '2011-07-24', '0');
INSERT INTO `_web_workday` VALUES ('237', '2011-07-25', '1');
INSERT INTO `_web_workday` VALUES ('238', '2011-07-26', '1');
INSERT INTO `_web_workday` VALUES ('239', '2011-07-27', '1');
INSERT INTO `_web_workday` VALUES ('240', '2011-07-28', '1');
INSERT INTO `_web_workday` VALUES ('241', '2011-07-29', '1');
INSERT INTO `_web_workday` VALUES ('242', '2011-07-30', '0');
INSERT INTO `_web_workday` VALUES ('243', '2011-07-31', '0');
INSERT INTO `_web_workday` VALUES ('244', '2011-08-01', '1');
INSERT INTO `_web_workday` VALUES ('245', '2011-08-02', '1');
INSERT INTO `_web_workday` VALUES ('246', '2011-08-03', '1');
INSERT INTO `_web_workday` VALUES ('247', '2011-08-04', '1');
INSERT INTO `_web_workday` VALUES ('248', '2011-08-05', '1');
INSERT INTO `_web_workday` VALUES ('249', '2011-08-06', '0');
INSERT INTO `_web_workday` VALUES ('250', '2011-08-07', '0');
INSERT INTO `_web_workday` VALUES ('251', '2011-08-08', '1');
INSERT INTO `_web_workday` VALUES ('252', '2011-08-09', '1');
INSERT INTO `_web_workday` VALUES ('253', '2011-08-10', '1');
INSERT INTO `_web_workday` VALUES ('254', '2011-08-11', '1');
INSERT INTO `_web_workday` VALUES ('255', '2011-08-12', '1');
INSERT INTO `_web_workday` VALUES ('256', '2011-08-13', '0');
INSERT INTO `_web_workday` VALUES ('257', '2011-08-14', '0');
INSERT INTO `_web_workday` VALUES ('258', '2011-08-15', '1');
INSERT INTO `_web_workday` VALUES ('259', '2011-08-16', '1');
INSERT INTO `_web_workday` VALUES ('260', '2011-08-17', '1');
INSERT INTO `_web_workday` VALUES ('261', '2011-08-18', '1');
INSERT INTO `_web_workday` VALUES ('262', '2011-08-19', '1');
INSERT INTO `_web_workday` VALUES ('263', '2011-08-20', '0');
INSERT INTO `_web_workday` VALUES ('264', '2011-08-21', '0');
INSERT INTO `_web_workday` VALUES ('265', '2011-08-22', '1');
INSERT INTO `_web_workday` VALUES ('266', '2011-08-23', '1');
INSERT INTO `_web_workday` VALUES ('267', '2011-08-24', '1');
INSERT INTO `_web_workday` VALUES ('268', '2011-08-25', '1');
INSERT INTO `_web_workday` VALUES ('269', '2011-08-26', '1');
INSERT INTO `_web_workday` VALUES ('270', '2011-08-27', '0');
INSERT INTO `_web_workday` VALUES ('271', '2011-08-28', '0');
INSERT INTO `_web_workday` VALUES ('272', '2011-08-29', '1');
INSERT INTO `_web_workday` VALUES ('273', '2011-08-30', '1');
INSERT INTO `_web_workday` VALUES ('274', '2011-08-31', '1');
INSERT INTO `_web_workday` VALUES ('275', '2011-09-01', '1');
INSERT INTO `_web_workday` VALUES ('276', '2011-09-02', '1');
INSERT INTO `_web_workday` VALUES ('277', '2011-09-03', '0');
INSERT INTO `_web_workday` VALUES ('278', '2011-09-04', '0');
INSERT INTO `_web_workday` VALUES ('279', '2011-09-05', '1');
INSERT INTO `_web_workday` VALUES ('280', '2011-09-06', '1');
INSERT INTO `_web_workday` VALUES ('281', '2011-09-07', '1');
INSERT INTO `_web_workday` VALUES ('282', '2011-09-08', '1');
INSERT INTO `_web_workday` VALUES ('283', '2011-09-09', '1');
INSERT INTO `_web_workday` VALUES ('284', '2011-09-10', '0');
INSERT INTO `_web_workday` VALUES ('285', '2011-09-11', '0');
INSERT INTO `_web_workday` VALUES ('286', '2011-09-12', '0');
INSERT INTO `_web_workday` VALUES ('287', '2011-09-13', '1');
INSERT INTO `_web_workday` VALUES ('288', '2011-09-14', '1');
INSERT INTO `_web_workday` VALUES ('289', '2011-09-15', '1');
INSERT INTO `_web_workday` VALUES ('290', '2011-09-16', '0');
INSERT INTO `_web_workday` VALUES ('291', '2011-09-17', '1');
INSERT INTO `_web_workday` VALUES ('292', '2011-09-18', '0');
INSERT INTO `_web_workday` VALUES ('293', '2011-09-19', '1');
INSERT INTO `_web_workday` VALUES ('294', '2011-09-20', '1');
INSERT INTO `_web_workday` VALUES ('295', '2011-09-21', '1');
INSERT INTO `_web_workday` VALUES ('296', '2011-09-22', '1');
INSERT INTO `_web_workday` VALUES ('297', '2011-09-23', '1');
INSERT INTO `_web_workday` VALUES ('298', '2011-09-24', '0');
INSERT INTO `_web_workday` VALUES ('299', '2011-09-25', '0');
INSERT INTO `_web_workday` VALUES ('300', '2011-09-26', '1');
INSERT INTO `_web_workday` VALUES ('301', '2011-09-27', '1');
INSERT INTO `_web_workday` VALUES ('302', '2011-09-28', '1');
INSERT INTO `_web_workday` VALUES ('303', '2011-09-29', '1');
INSERT INTO `_web_workday` VALUES ('304', '2011-09-30', '1');
INSERT INTO `_web_workday` VALUES ('305', '2011-10-01', '0');
INSERT INTO `_web_workday` VALUES ('306', '2011-10-02', '0');
INSERT INTO `_web_workday` VALUES ('307', '2011-10-03', '0');
INSERT INTO `_web_workday` VALUES ('308', '2011-10-04', '0');
INSERT INTO `_web_workday` VALUES ('309', '2011-10-05', '0');
INSERT INTO `_web_workday` VALUES ('310', '2011-10-06', '0');
INSERT INTO `_web_workday` VALUES ('311', '2011-10-07', '0');
INSERT INTO `_web_workday` VALUES ('312', '2011-10-08', '1');
INSERT INTO `_web_workday` VALUES ('313', '2011-10-09', '1');
INSERT INTO `_web_workday` VALUES ('314', '2011-10-10', '1');
INSERT INTO `_web_workday` VALUES ('315', '2011-10-11', '1');
INSERT INTO `_web_workday` VALUES ('316', '2011-10-12', '1');
INSERT INTO `_web_workday` VALUES ('317', '2011-10-13', '1');
INSERT INTO `_web_workday` VALUES ('318', '2011-10-14', '1');
INSERT INTO `_web_workday` VALUES ('319', '2011-10-15', '0');
INSERT INTO `_web_workday` VALUES ('320', '2011-10-16', '0');
INSERT INTO `_web_workday` VALUES ('321', '2011-10-17', '1');
INSERT INTO `_web_workday` VALUES ('322', '2011-10-18', '1');
INSERT INTO `_web_workday` VALUES ('323', '2011-10-19', '1');
INSERT INTO `_web_workday` VALUES ('324', '2011-10-20', '1');
INSERT INTO `_web_workday` VALUES ('325', '2011-10-21', '1');
INSERT INTO `_web_workday` VALUES ('326', '2011-10-22', '0');
INSERT INTO `_web_workday` VALUES ('327', '2011-10-23', '0');
INSERT INTO `_web_workday` VALUES ('328', '2011-10-24', '1');
INSERT INTO `_web_workday` VALUES ('329', '2011-10-25', '1');
INSERT INTO `_web_workday` VALUES ('330', '2011-10-26', '1');
INSERT INTO `_web_workday` VALUES ('331', '2011-10-27', '1');
INSERT INTO `_web_workday` VALUES ('332', '2011-10-28', '1');
INSERT INTO `_web_workday` VALUES ('333', '2011-10-29', '0');
INSERT INTO `_web_workday` VALUES ('334', '2011-10-30', '0');
INSERT INTO `_web_workday` VALUES ('335', '2011-10-31', '1');
INSERT INTO `_web_workday` VALUES ('336', '2011-11-01', '1');
INSERT INTO `_web_workday` VALUES ('337', '2011-11-02', '1');
INSERT INTO `_web_workday` VALUES ('338', '2011-11-03', '1');
INSERT INTO `_web_workday` VALUES ('339', '2011-11-04', '1');
INSERT INTO `_web_workday` VALUES ('340', '2011-11-05', '0');
INSERT INTO `_web_workday` VALUES ('341', '2011-11-06', '0');
INSERT INTO `_web_workday` VALUES ('342', '2011-11-07', '1');
INSERT INTO `_web_workday` VALUES ('343', '2011-11-08', '1');
INSERT INTO `_web_workday` VALUES ('344', '2011-11-09', '1');
INSERT INTO `_web_workday` VALUES ('345', '2011-11-10', '1');
INSERT INTO `_web_workday` VALUES ('346', '2011-11-11', '1');
INSERT INTO `_web_workday` VALUES ('347', '2011-11-12', '0');
INSERT INTO `_web_workday` VALUES ('348', '2011-11-13', '0');
INSERT INTO `_web_workday` VALUES ('349', '2011-11-14', '1');
INSERT INTO `_web_workday` VALUES ('350', '2011-11-15', '1');
INSERT INTO `_web_workday` VALUES ('351', '2011-11-16', '1');
INSERT INTO `_web_workday` VALUES ('352', '2011-11-17', '1');
INSERT INTO `_web_workday` VALUES ('353', '2011-11-18', '1');
INSERT INTO `_web_workday` VALUES ('354', '2011-11-19', '0');
INSERT INTO `_web_workday` VALUES ('355', '2011-11-20', '0');
INSERT INTO `_web_workday` VALUES ('356', '2011-11-21', '1');
INSERT INTO `_web_workday` VALUES ('357', '2011-11-22', '1');
INSERT INTO `_web_workday` VALUES ('358', '2011-11-23', '1');
INSERT INTO `_web_workday` VALUES ('359', '2011-11-24', '1');
INSERT INTO `_web_workday` VALUES ('360', '2011-11-25', '1');
INSERT INTO `_web_workday` VALUES ('361', '2011-11-26', '0');
INSERT INTO `_web_workday` VALUES ('362', '2011-11-27', '0');
INSERT INTO `_web_workday` VALUES ('363', '2011-11-28', '1');
INSERT INTO `_web_workday` VALUES ('364', '2011-11-29', '1');
INSERT INTO `_web_workday` VALUES ('365', '2011-11-30', '1');
INSERT INTO `_web_workday` VALUES ('366', '2011-12-01', '1');
INSERT INTO `_web_workday` VALUES ('367', '2011-12-02', '1');
INSERT INTO `_web_workday` VALUES ('368', '2011-12-03', '0');
INSERT INTO `_web_workday` VALUES ('369', '2011-12-04', '0');
INSERT INTO `_web_workday` VALUES ('370', '2011-12-05', '1');
INSERT INTO `_web_workday` VALUES ('371', '2011-12-06', '1');
INSERT INTO `_web_workday` VALUES ('372', '2011-12-07', '1');
INSERT INTO `_web_workday` VALUES ('373', '2011-12-08', '1');
INSERT INTO `_web_workday` VALUES ('374', '2011-12-09', '1');
INSERT INTO `_web_workday` VALUES ('375', '2011-12-10', '0');
INSERT INTO `_web_workday` VALUES ('376', '2011-12-11', '0');
INSERT INTO `_web_workday` VALUES ('377', '2011-12-12', '1');
INSERT INTO `_web_workday` VALUES ('378', '2011-12-13', '1');
INSERT INTO `_web_workday` VALUES ('379', '2011-12-14', '1');
INSERT INTO `_web_workday` VALUES ('380', '2011-12-15', '1');
INSERT INTO `_web_workday` VALUES ('381', '2011-12-16', '1');
INSERT INTO `_web_workday` VALUES ('382', '2011-12-17', '0');
INSERT INTO `_web_workday` VALUES ('383', '2011-12-18', '0');
INSERT INTO `_web_workday` VALUES ('384', '2011-12-19', '1');
INSERT INTO `_web_workday` VALUES ('385', '2011-12-20', '1');
INSERT INTO `_web_workday` VALUES ('386', '2011-12-21', '1');
INSERT INTO `_web_workday` VALUES ('387', '2011-12-22', '1');
INSERT INTO `_web_workday` VALUES ('388', '2011-12-23', '1');
INSERT INTO `_web_workday` VALUES ('389', '2011-12-24', '0');
INSERT INTO `_web_workday` VALUES ('390', '2011-12-25', '0');
INSERT INTO `_web_workday` VALUES ('391', '2011-12-26', '1');
INSERT INTO `_web_workday` VALUES ('392', '2011-12-27', '1');
INSERT INTO `_web_workday` VALUES ('393', '2011-12-28', '1');
INSERT INTO `_web_workday` VALUES ('394', '2011-12-29', '1');
INSERT INTO `_web_workday` VALUES ('395', '2011-12-30', '1');
INSERT INTO `_web_workday` VALUES ('396', '2011-12-31', '1');
INSERT INTO `_web_workday` VALUES ('397', '2012-01-01', '0');
INSERT INTO `_web_workday` VALUES ('398', '2012-01-02', '0');
INSERT INTO `_web_workday` VALUES ('399', '2012-01-03', '0');
INSERT INTO `_web_workday` VALUES ('400', '2012-01-04', '1');
INSERT INTO `_web_workday` VALUES ('401', '2012-01-05', '1');
INSERT INTO `_web_workday` VALUES ('402', '2012-01-06', '1');
INSERT INTO `_web_workday` VALUES ('403', '2012-01-07', '0');
INSERT INTO `_web_workday` VALUES ('404', '2012-01-08', '0');
INSERT INTO `_web_workday` VALUES ('405', '2012-01-09', '1');
INSERT INTO `_web_workday` VALUES ('406', '2012-01-10', '1');
INSERT INTO `_web_workday` VALUES ('407', '2012-01-11', '1');
INSERT INTO `_web_workday` VALUES ('408', '2012-01-12', '1');
INSERT INTO `_web_workday` VALUES ('409', '2012-01-13', '1');
INSERT INTO `_web_workday` VALUES ('410', '2012-01-14', '1');
INSERT INTO `_web_workday` VALUES ('411', '2012-01-15', '0');
INSERT INTO `_web_workday` VALUES ('412', '2012-01-16', '1');
INSERT INTO `_web_workday` VALUES ('413', '2012-01-17', '1');
INSERT INTO `_web_workday` VALUES ('414', '2012-01-18', '1');
INSERT INTO `_web_workday` VALUES ('415', '2012-01-19', '1');
INSERT INTO `_web_workday` VALUES ('416', '2012-01-20', '0');
INSERT INTO `_web_workday` VALUES ('417', '2012-01-21', '0');
INSERT INTO `_web_workday` VALUES ('418', '2012-01-22', '0');
INSERT INTO `_web_workday` VALUES ('419', '2012-01-23', '0');
INSERT INTO `_web_workday` VALUES ('420', '2012-01-24', '0');
INSERT INTO `_web_workday` VALUES ('421', '2012-01-25', '0');
INSERT INTO `_web_workday` VALUES ('422', '2012-01-26', '1');
INSERT INTO `_web_workday` VALUES ('423', '2012-01-27', '1');
INSERT INTO `_web_workday` VALUES ('424', '2012-01-28', '0');
INSERT INTO `_web_workday` VALUES ('425', '2012-01-29', '0');
INSERT INTO `_web_workday` VALUES ('426', '2012-01-30', '1');
INSERT INTO `_web_workday` VALUES ('427', '2012-01-31', '1');
INSERT INTO `_web_workday` VALUES ('428', '2012-02-01', '1');
INSERT INTO `_web_workday` VALUES ('429', '2012-02-02', '1');
INSERT INTO `_web_workday` VALUES ('430', '2012-02-03', '1');
INSERT INTO `_web_workday` VALUES ('431', '2012-02-04', '0');
INSERT INTO `_web_workday` VALUES ('432', '2012-02-05', '0');
INSERT INTO `_web_workday` VALUES ('433', '2012-02-06', '1');
INSERT INTO `_web_workday` VALUES ('434', '2012-02-07', '1');
INSERT INTO `_web_workday` VALUES ('435', '2012-02-08', '1');
INSERT INTO `_web_workday` VALUES ('436', '2012-02-09', '1');
INSERT INTO `_web_workday` VALUES ('437', '2012-02-10', '1');
INSERT INTO `_web_workday` VALUES ('438', '2012-02-11', '0');
INSERT INTO `_web_workday` VALUES ('439', '2012-02-12', '0');
INSERT INTO `_web_workday` VALUES ('440', '2012-02-13', '1');
INSERT INTO `_web_workday` VALUES ('441', '2012-02-14', '1');
INSERT INTO `_web_workday` VALUES ('442', '2012-02-15', '1');
INSERT INTO `_web_workday` VALUES ('443', '2012-02-16', '1');
INSERT INTO `_web_workday` VALUES ('444', '2012-02-17', '1');
INSERT INTO `_web_workday` VALUES ('445', '2012-02-18', '0');
INSERT INTO `_web_workday` VALUES ('446', '2012-02-19', '0');
INSERT INTO `_web_workday` VALUES ('447', '2012-02-20', '1');
INSERT INTO `_web_workday` VALUES ('448', '2012-02-21', '1');
INSERT INTO `_web_workday` VALUES ('449', '2012-02-22', '1');
INSERT INTO `_web_workday` VALUES ('450', '2012-02-23', '1');
INSERT INTO `_web_workday` VALUES ('451', '2012-02-24', '1');
INSERT INTO `_web_workday` VALUES ('452', '2012-02-25', '0');
INSERT INTO `_web_workday` VALUES ('453', '2012-02-26', '0');
INSERT INTO `_web_workday` VALUES ('454', '2012-02-27', '1');
INSERT INTO `_web_workday` VALUES ('455', '2012-02-28', '1');
INSERT INTO `_web_workday` VALUES ('456', '2012-02-29', '1');
INSERT INTO `_web_workday` VALUES ('457', '2012-03-01', '1');
INSERT INTO `_web_workday` VALUES ('458', '2012-03-02', '1');
INSERT INTO `_web_workday` VALUES ('459', '2012-03-03', '0');
INSERT INTO `_web_workday` VALUES ('460', '2012-03-04', '0');
INSERT INTO `_web_workday` VALUES ('461', '2012-03-05', '1');
INSERT INTO `_web_workday` VALUES ('462', '2012-03-06', '1');
INSERT INTO `_web_workday` VALUES ('463', '2012-03-07', '1');
INSERT INTO `_web_workday` VALUES ('464', '2012-03-08', '1');
INSERT INTO `_web_workday` VALUES ('465', '2012-03-09', '1');
INSERT INTO `_web_workday` VALUES ('466', '2012-03-10', '0');
INSERT INTO `_web_workday` VALUES ('467', '2012-03-11', '0');
INSERT INTO `_web_workday` VALUES ('468', '2012-03-12', '1');
INSERT INTO `_web_workday` VALUES ('469', '2012-03-13', '1');
INSERT INTO `_web_workday` VALUES ('470', '2012-03-14', '1');
INSERT INTO `_web_workday` VALUES ('471', '2012-03-15', '1');
INSERT INTO `_web_workday` VALUES ('472', '2012-03-16', '1');
INSERT INTO `_web_workday` VALUES ('473', '2012-03-17', '0');
INSERT INTO `_web_workday` VALUES ('474', '2012-03-18', '0');
INSERT INTO `_web_workday` VALUES ('475', '2012-03-19', '1');
INSERT INTO `_web_workday` VALUES ('476', '2012-03-20', '1');
INSERT INTO `_web_workday` VALUES ('477', '2012-03-21', '1');
INSERT INTO `_web_workday` VALUES ('478', '2012-03-22', '1');
INSERT INTO `_web_workday` VALUES ('479', '2012-03-23', '1');
INSERT INTO `_web_workday` VALUES ('480', '2012-03-24', '0');
INSERT INTO `_web_workday` VALUES ('481', '2012-03-25', '0');
INSERT INTO `_web_workday` VALUES ('482', '2012-03-26', '1');
INSERT INTO `_web_workday` VALUES ('483', '2012-03-27', '1');
INSERT INTO `_web_workday` VALUES ('484', '2012-03-28', '1');
INSERT INTO `_web_workday` VALUES ('485', '2012-03-29', '1');
INSERT INTO `_web_workday` VALUES ('486', '2012-03-30', '1');
INSERT INTO `_web_workday` VALUES ('487', '2012-03-31', '1');
INSERT INTO `_web_workday` VALUES ('488', '2012-04-01', '1');
INSERT INTO `_web_workday` VALUES ('489', '2012-04-02', '0');
INSERT INTO `_web_workday` VALUES ('490', '2012-04-03', '0');
INSERT INTO `_web_workday` VALUES ('491', '2012-04-04', '0');
INSERT INTO `_web_workday` VALUES ('492', '2012-04-05', '1');
INSERT INTO `_web_workday` VALUES ('493', '2012-04-06', '1');
INSERT INTO `_web_workday` VALUES ('494', '2012-04-07', '0');
INSERT INTO `_web_workday` VALUES ('495', '2012-04-08', '0');
INSERT INTO `_web_workday` VALUES ('496', '2012-04-09', '1');
INSERT INTO `_web_workday` VALUES ('497', '2012-04-10', '1');
INSERT INTO `_web_workday` VALUES ('498', '2012-04-11', '1');
INSERT INTO `_web_workday` VALUES ('499', '2012-04-12', '1');
INSERT INTO `_web_workday` VALUES ('500', '2012-04-13', '1');
INSERT INTO `_web_workday` VALUES ('501', '2012-04-14', '0');
INSERT INTO `_web_workday` VALUES ('502', '2012-04-15', '0');
INSERT INTO `_web_workday` VALUES ('503', '2012-04-16', '1');
INSERT INTO `_web_workday` VALUES ('504', '2012-04-17', '1');
INSERT INTO `_web_workday` VALUES ('505', '2012-04-18', '1');
INSERT INTO `_web_workday` VALUES ('506', '2012-04-19', '1');
INSERT INTO `_web_workday` VALUES ('507', '2012-04-20', '1');
INSERT INTO `_web_workday` VALUES ('508', '2012-04-21', '0');
INSERT INTO `_web_workday` VALUES ('509', '2012-04-22', '0');
INSERT INTO `_web_workday` VALUES ('510', '2012-04-23', '1');
INSERT INTO `_web_workday` VALUES ('511', '2012-04-24', '1');
INSERT INTO `_web_workday` VALUES ('512', '2012-04-25', '1');
INSERT INTO `_web_workday` VALUES ('513', '2012-04-26', '1');
INSERT INTO `_web_workday` VALUES ('514', '2012-04-27', '1');
INSERT INTO `_web_workday` VALUES ('515', '2012-04-28', '1');
INSERT INTO `_web_workday` VALUES ('516', '2012-04-29', '0');
INSERT INTO `_web_workday` VALUES ('517', '2012-04-30', '0');
INSERT INTO `_web_workday` VALUES ('518', '2012-05-01', '0');
INSERT INTO `_web_workday` VALUES ('519', '2012-05-02', '1');
INSERT INTO `_web_workday` VALUES ('520', '2012-05-03', '1');
INSERT INTO `_web_workday` VALUES ('521', '2012-05-04', '1');
INSERT INTO `_web_workday` VALUES ('522', '2012-05-05', '0');
INSERT INTO `_web_workday` VALUES ('523', '2012-05-06', '0');
INSERT INTO `_web_workday` VALUES ('524', '2012-05-07', '1');
INSERT INTO `_web_workday` VALUES ('525', '2012-05-08', '1');
INSERT INTO `_web_workday` VALUES ('526', '2012-05-09', '1');
INSERT INTO `_web_workday` VALUES ('527', '2012-05-10', '1');
INSERT INTO `_web_workday` VALUES ('528', '2012-05-11', '1');
INSERT INTO `_web_workday` VALUES ('529', '2012-05-12', '0');
INSERT INTO `_web_workday` VALUES ('530', '2012-05-13', '0');
INSERT INTO `_web_workday` VALUES ('531', '2012-05-14', '1');
INSERT INTO `_web_workday` VALUES ('532', '2012-05-15', '1');
INSERT INTO `_web_workday` VALUES ('533', '2012-05-16', '1');
INSERT INTO `_web_workday` VALUES ('534', '2012-05-17', '1');
INSERT INTO `_web_workday` VALUES ('535', '2012-05-18', '1');
INSERT INTO `_web_workday` VALUES ('536', '2012-05-19', '0');
INSERT INTO `_web_workday` VALUES ('537', '2012-05-20', '0');
INSERT INTO `_web_workday` VALUES ('538', '2012-05-21', '1');
INSERT INTO `_web_workday` VALUES ('539', '2012-05-22', '1');
INSERT INTO `_web_workday` VALUES ('540', '2012-05-23', '1');
INSERT INTO `_web_workday` VALUES ('541', '2012-05-24', '1');
INSERT INTO `_web_workday` VALUES ('542', '2012-05-25', '1');
INSERT INTO `_web_workday` VALUES ('543', '2012-05-26', '0');
INSERT INTO `_web_workday` VALUES ('544', '2012-05-27', '0');
INSERT INTO `_web_workday` VALUES ('545', '2012-05-28', '1');
INSERT INTO `_web_workday` VALUES ('546', '2012-05-29', '1');
INSERT INTO `_web_workday` VALUES ('547', '2012-05-30', '1');
INSERT INTO `_web_workday` VALUES ('548', '2012-05-31', '1');
INSERT INTO `_web_workday` VALUES ('549', '2012-06-01', '1');
INSERT INTO `_web_workday` VALUES ('550', '2012-06-02', '0');
INSERT INTO `_web_workday` VALUES ('551', '2012-06-03', '0');
INSERT INTO `_web_workday` VALUES ('552', '2012-06-04', '0');
INSERT INTO `_web_workday` VALUES ('553', '2012-06-05', '1');
INSERT INTO `_web_workday` VALUES ('554', '2012-06-06', '1');
INSERT INTO `_web_workday` VALUES ('555', '2012-06-07', '1');
INSERT INTO `_web_workday` VALUES ('556', '2012-06-08', '1');
INSERT INTO `_web_workday` VALUES ('557', '2012-06-09', '0');
INSERT INTO `_web_workday` VALUES ('558', '2012-06-10', '0');
INSERT INTO `_web_workday` VALUES ('559', '2012-06-11', '1');
INSERT INTO `_web_workday` VALUES ('560', '2012-06-12', '1');
INSERT INTO `_web_workday` VALUES ('561', '2012-06-13', '1');
INSERT INTO `_web_workday` VALUES ('562', '2012-06-14', '1');
INSERT INTO `_web_workday` VALUES ('563', '2012-06-15', '1');
INSERT INTO `_web_workday` VALUES ('564', '2012-06-16', '1');
INSERT INTO `_web_workday` VALUES ('565', '2012-06-17', '0');
INSERT INTO `_web_workday` VALUES ('566', '2012-06-18', '1');
INSERT INTO `_web_workday` VALUES ('567', '2012-06-19', '1');
INSERT INTO `_web_workday` VALUES ('568', '2012-06-20', '1');
INSERT INTO `_web_workday` VALUES ('569', '2012-06-21', '1');
INSERT INTO `_web_workday` VALUES ('570', '2012-06-22', '0');
INSERT INTO `_web_workday` VALUES ('571', '2012-06-23', '0');
INSERT INTO `_web_workday` VALUES ('572', '2012-06-24', '0');
INSERT INTO `_web_workday` VALUES ('573', '2012-06-25', '1');
INSERT INTO `_web_workday` VALUES ('574', '2012-06-26', '1');
INSERT INTO `_web_workday` VALUES ('575', '2012-06-27', '1');
INSERT INTO `_web_workday` VALUES ('576', '2012-06-28', '1');
INSERT INTO `_web_workday` VALUES ('577', '2012-06-29', '1');
INSERT INTO `_web_workday` VALUES ('578', '2012-06-30', '0');
INSERT INTO `_web_workday` VALUES ('579', '2012-07-01', '0');
INSERT INTO `_web_workday` VALUES ('580', '2012-07-02', '1');
INSERT INTO `_web_workday` VALUES ('581', '2012-07-03', '1');
INSERT INTO `_web_workday` VALUES ('582', '2012-07-04', '1');
INSERT INTO `_web_workday` VALUES ('583', '2012-07-05', '1');
INSERT INTO `_web_workday` VALUES ('584', '2012-07-06', '1');
INSERT INTO `_web_workday` VALUES ('585', '2012-07-07', '0');
INSERT INTO `_web_workday` VALUES ('586', '2012-07-08', '0');
INSERT INTO `_web_workday` VALUES ('587', '2012-07-09', '1');
INSERT INTO `_web_workday` VALUES ('588', '2012-07-10', '1');
INSERT INTO `_web_workday` VALUES ('589', '2012-07-11', '1');
INSERT INTO `_web_workday` VALUES ('590', '2012-07-12', '1');
INSERT INTO `_web_workday` VALUES ('591', '2012-07-13', '1');
INSERT INTO `_web_workday` VALUES ('592', '2012-07-14', '0');
INSERT INTO `_web_workday` VALUES ('593', '2012-07-15', '0');
INSERT INTO `_web_workday` VALUES ('594', '2012-07-16', '1');
INSERT INTO `_web_workday` VALUES ('595', '2012-07-17', '1');
INSERT INTO `_web_workday` VALUES ('596', '2012-07-18', '1');
INSERT INTO `_web_workday` VALUES ('597', '2012-07-19', '1');
INSERT INTO `_web_workday` VALUES ('598', '2012-07-20', '1');
INSERT INTO `_web_workday` VALUES ('599', '2012-07-21', '0');
INSERT INTO `_web_workday` VALUES ('600', '2012-07-22', '0');
INSERT INTO `_web_workday` VALUES ('601', '2012-07-23', '1');
INSERT INTO `_web_workday` VALUES ('602', '2012-07-24', '1');
INSERT INTO `_web_workday` VALUES ('603', '2012-07-25', '1');
INSERT INTO `_web_workday` VALUES ('604', '2012-07-26', '1');
INSERT INTO `_web_workday` VALUES ('605', '2012-07-27', '1');
INSERT INTO `_web_workday` VALUES ('606', '2012-07-28', '0');
INSERT INTO `_web_workday` VALUES ('607', '2012-07-29', '0');
INSERT INTO `_web_workday` VALUES ('608', '2012-07-30', '1');
INSERT INTO `_web_workday` VALUES ('609', '2012-07-31', '1');
INSERT INTO `_web_workday` VALUES ('610', '2012-08-01', '1');
INSERT INTO `_web_workday` VALUES ('611', '2012-08-02', '1');
INSERT INTO `_web_workday` VALUES ('612', '2012-08-03', '1');
INSERT INTO `_web_workday` VALUES ('613', '2012-08-04', '0');
INSERT INTO `_web_workday` VALUES ('614', '2012-08-05', '0');
INSERT INTO `_web_workday` VALUES ('615', '2012-08-06', '1');
INSERT INTO `_web_workday` VALUES ('616', '2012-08-07', '1');
INSERT INTO `_web_workday` VALUES ('617', '2012-08-08', '1');
INSERT INTO `_web_workday` VALUES ('618', '2012-08-09', '1');
INSERT INTO `_web_workday` VALUES ('619', '2012-08-10', '1');
INSERT INTO `_web_workday` VALUES ('620', '2012-08-11', '0');
INSERT INTO `_web_workday` VALUES ('621', '2012-08-12', '0');
INSERT INTO `_web_workday` VALUES ('622', '2012-08-13', '1');
INSERT INTO `_web_workday` VALUES ('623', '2012-08-14', '1');
INSERT INTO `_web_workday` VALUES ('624', '2012-08-15', '1');
INSERT INTO `_web_workday` VALUES ('625', '2012-08-16', '1');
INSERT INTO `_web_workday` VALUES ('626', '2012-08-17', '1');
INSERT INTO `_web_workday` VALUES ('627', '2012-08-18', '0');
INSERT INTO `_web_workday` VALUES ('628', '2012-08-19', '0');
INSERT INTO `_web_workday` VALUES ('629', '2012-08-20', '1');
INSERT INTO `_web_workday` VALUES ('630', '2012-08-21', '1');
INSERT INTO `_web_workday` VALUES ('631', '2012-08-22', '1');
INSERT INTO `_web_workday` VALUES ('632', '2012-08-23', '1');
INSERT INTO `_web_workday` VALUES ('633', '2012-08-24', '1');
INSERT INTO `_web_workday` VALUES ('634', '2012-08-25', '0');
INSERT INTO `_web_workday` VALUES ('635', '2012-08-26', '0');
INSERT INTO `_web_workday` VALUES ('636', '2012-08-27', '1');
INSERT INTO `_web_workday` VALUES ('637', '2012-08-28', '1');
INSERT INTO `_web_workday` VALUES ('638', '2012-08-29', '1');
INSERT INTO `_web_workday` VALUES ('639', '2012-08-30', '1');
INSERT INTO `_web_workday` VALUES ('640', '2012-08-31', '1');
INSERT INTO `_web_workday` VALUES ('641', '2012-09-01', '0');
INSERT INTO `_web_workday` VALUES ('642', '2012-09-02', '0');
INSERT INTO `_web_workday` VALUES ('643', '2012-09-03', '1');
INSERT INTO `_web_workday` VALUES ('644', '2012-09-04', '1');
INSERT INTO `_web_workday` VALUES ('645', '2012-09-05', '1');
INSERT INTO `_web_workday` VALUES ('646', '2012-09-06', '1');
INSERT INTO `_web_workday` VALUES ('647', '2012-09-07', '1');
INSERT INTO `_web_workday` VALUES ('648', '2012-09-08', '0');
INSERT INTO `_web_workday` VALUES ('649', '2012-09-09', '0');
INSERT INTO `_web_workday` VALUES ('650', '2012-09-10', '1');
INSERT INTO `_web_workday` VALUES ('651', '2012-09-11', '1');
INSERT INTO `_web_workday` VALUES ('652', '2012-09-12', '1');
INSERT INTO `_web_workday` VALUES ('653', '2012-09-13', '1');
INSERT INTO `_web_workday` VALUES ('654', '2012-09-14', '1');
INSERT INTO `_web_workday` VALUES ('655', '2012-09-15', '0');
INSERT INTO `_web_workday` VALUES ('656', '2012-09-16', '0');
INSERT INTO `_web_workday` VALUES ('657', '2012-09-17', '1');
INSERT INTO `_web_workday` VALUES ('658', '2012-09-18', '1');
INSERT INTO `_web_workday` VALUES ('659', '2012-09-19', '1');
INSERT INTO `_web_workday` VALUES ('660', '2012-09-20', '1');
INSERT INTO `_web_workday` VALUES ('661', '2012-09-21', '1');
INSERT INTO `_web_workday` VALUES ('662', '2012-09-22', '0');
INSERT INTO `_web_workday` VALUES ('663', '2012-09-23', '0');
INSERT INTO `_web_workday` VALUES ('664', '2012-09-24', '1');
INSERT INTO `_web_workday` VALUES ('665', '2012-09-25', '1');
INSERT INTO `_web_workday` VALUES ('666', '2012-09-26', '1');
INSERT INTO `_web_workday` VALUES ('667', '2012-09-27', '1');
INSERT INTO `_web_workday` VALUES ('668', '2012-09-28', '1');
INSERT INTO `_web_workday` VALUES ('669', '2012-09-29', '1');
INSERT INTO `_web_workday` VALUES ('670', '2012-09-30', '0');
INSERT INTO `_web_workday` VALUES ('671', '2012-10-01', '0');
INSERT INTO `_web_workday` VALUES ('672', '2012-10-02', '0');
INSERT INTO `_web_workday` VALUES ('673', '2012-10-03', '0');
INSERT INTO `_web_workday` VALUES ('674', '2012-10-04', '0');
INSERT INTO `_web_workday` VALUES ('675', '2012-10-05', '0');
INSERT INTO `_web_workday` VALUES ('676', '2012-10-06', '0');
INSERT INTO `_web_workday` VALUES ('677', '2012-10-07', '0');
INSERT INTO `_web_workday` VALUES ('678', '2012-10-08', '1');
INSERT INTO `_web_workday` VALUES ('679', '2012-10-09', '1');
INSERT INTO `_web_workday` VALUES ('680', '2012-10-10', '1');
INSERT INTO `_web_workday` VALUES ('681', '2012-10-11', '1');
INSERT INTO `_web_workday` VALUES ('682', '2012-10-12', '1');
INSERT INTO `_web_workday` VALUES ('683', '2012-10-13', '0');
INSERT INTO `_web_workday` VALUES ('684', '2012-10-14', '0');
INSERT INTO `_web_workday` VALUES ('685', '2012-10-15', '1');
INSERT INTO `_web_workday` VALUES ('686', '2012-10-16', '1');
INSERT INTO `_web_workday` VALUES ('687', '2012-10-17', '1');
INSERT INTO `_web_workday` VALUES ('688', '2012-10-18', '1');
INSERT INTO `_web_workday` VALUES ('689', '2012-10-19', '1');
INSERT INTO `_web_workday` VALUES ('690', '2012-10-20', '0');
INSERT INTO `_web_workday` VALUES ('691', '2012-10-21', '0');
INSERT INTO `_web_workday` VALUES ('692', '2012-10-22', '1');
INSERT INTO `_web_workday` VALUES ('693', '2012-10-23', '1');
INSERT INTO `_web_workday` VALUES ('694', '2012-10-24', '1');
INSERT INTO `_web_workday` VALUES ('695', '2012-10-25', '1');
INSERT INTO `_web_workday` VALUES ('696', '2012-10-26', '1');
INSERT INTO `_web_workday` VALUES ('697', '2012-10-27', '0');
INSERT INTO `_web_workday` VALUES ('698', '2012-10-28', '0');
INSERT INTO `_web_workday` VALUES ('699', '2012-10-29', '1');
INSERT INTO `_web_workday` VALUES ('700', '2012-10-30', '1');
INSERT INTO `_web_workday` VALUES ('701', '2012-10-31', '1');
INSERT INTO `_web_workday` VALUES ('702', '2012-11-01', '1');
INSERT INTO `_web_workday` VALUES ('703', '2012-11-02', '1');
INSERT INTO `_web_workday` VALUES ('704', '2012-11-03', '0');
INSERT INTO `_web_workday` VALUES ('705', '2012-11-04', '0');
INSERT INTO `_web_workday` VALUES ('706', '2012-11-05', '1');
INSERT INTO `_web_workday` VALUES ('707', '2012-11-06', '1');
INSERT INTO `_web_workday` VALUES ('708', '2012-11-07', '1');
INSERT INTO `_web_workday` VALUES ('709', '2012-11-08', '1');
INSERT INTO `_web_workday` VALUES ('710', '2012-11-09', '1');
INSERT INTO `_web_workday` VALUES ('711', '2012-11-10', '0');
INSERT INTO `_web_workday` VALUES ('712', '2012-11-11', '0');
INSERT INTO `_web_workday` VALUES ('713', '2012-11-12', '1');
INSERT INTO `_web_workday` VALUES ('714', '2012-11-13', '1');
INSERT INTO `_web_workday` VALUES ('715', '2012-11-14', '1');
INSERT INTO `_web_workday` VALUES ('716', '2012-11-15', '1');
INSERT INTO `_web_workday` VALUES ('717', '2012-11-16', '1');
INSERT INTO `_web_workday` VALUES ('718', '2012-11-17', '0');
INSERT INTO `_web_workday` VALUES ('719', '2012-11-18', '0');
INSERT INTO `_web_workday` VALUES ('720', '2012-11-19', '1');
INSERT INTO `_web_workday` VALUES ('721', '2012-11-20', '1');
INSERT INTO `_web_workday` VALUES ('722', '2012-11-21', '1');
INSERT INTO `_web_workday` VALUES ('723', '2012-11-22', '1');
INSERT INTO `_web_workday` VALUES ('724', '2012-11-23', '1');
INSERT INTO `_web_workday` VALUES ('725', '2012-11-24', '0');
INSERT INTO `_web_workday` VALUES ('726', '2012-11-25', '0');
INSERT INTO `_web_workday` VALUES ('727', '2012-11-26', '1');
INSERT INTO `_web_workday` VALUES ('728', '2012-11-27', '1');
INSERT INTO `_web_workday` VALUES ('729', '2012-11-28', '1');
INSERT INTO `_web_workday` VALUES ('730', '2012-11-29', '1');
INSERT INTO `_web_workday` VALUES ('731', '2012-11-30', '1');
INSERT INTO `_web_workday` VALUES ('732', '2012-12-01', '0');
INSERT INTO `_web_workday` VALUES ('733', '2012-12-02', '0');
INSERT INTO `_web_workday` VALUES ('734', '2012-12-03', '1');
INSERT INTO `_web_workday` VALUES ('735', '2012-12-04', '1');
INSERT INTO `_web_workday` VALUES ('736', '2012-12-05', '1');
INSERT INTO `_web_workday` VALUES ('737', '2012-12-06', '1');
INSERT INTO `_web_workday` VALUES ('738', '2012-12-07', '1');
INSERT INTO `_web_workday` VALUES ('739', '2012-12-08', '0');
INSERT INTO `_web_workday` VALUES ('740', '2012-12-09', '0');
INSERT INTO `_web_workday` VALUES ('741', '2012-12-10', '1');
INSERT INTO `_web_workday` VALUES ('742', '2012-12-11', '1');
INSERT INTO `_web_workday` VALUES ('743', '2012-12-12', '1');
INSERT INTO `_web_workday` VALUES ('744', '2012-12-13', '1');
INSERT INTO `_web_workday` VALUES ('745', '2012-12-14', '1');
INSERT INTO `_web_workday` VALUES ('746', '2012-12-15', '0');
INSERT INTO `_web_workday` VALUES ('747', '2012-12-16', '0');
INSERT INTO `_web_workday` VALUES ('748', '2012-12-17', '1');
INSERT INTO `_web_workday` VALUES ('749', '2012-12-18', '1');
INSERT INTO `_web_workday` VALUES ('750', '2012-12-19', '1');
INSERT INTO `_web_workday` VALUES ('751', '2012-12-20', '1');
INSERT INTO `_web_workday` VALUES ('752', '2012-12-21', '1');
INSERT INTO `_web_workday` VALUES ('753', '2012-12-22', '0');
INSERT INTO `_web_workday` VALUES ('754', '2012-12-23', '0');
INSERT INTO `_web_workday` VALUES ('755', '2012-12-24', '1');
INSERT INTO `_web_workday` VALUES ('756', '2012-12-25', '1');
INSERT INTO `_web_workday` VALUES ('757', '2012-12-26', '1');
INSERT INTO `_web_workday` VALUES ('758', '2012-12-27', '1');
INSERT INTO `_web_workday` VALUES ('759', '2012-12-28', '1');
INSERT INTO `_web_workday` VALUES ('760', '2012-12-29', '1');
INSERT INTO `_web_workday` VALUES ('761', '2012-12-30', '0');
INSERT INTO `_web_workday` VALUES ('762', '2012-12-31', '0');
INSERT INTO `_web_workday` VALUES ('763', '2013-01-01', '0');
INSERT INTO `_web_workday` VALUES ('764', '2013-01-02', '1');
INSERT INTO `_web_workday` VALUES ('765', '2013-01-03', '1');
INSERT INTO `_web_workday` VALUES ('766', '2013-01-04', '1');
INSERT INTO `_web_workday` VALUES ('767', '2013-01-05', '0');
INSERT INTO `_web_workday` VALUES ('768', '2013-01-06', '0');
INSERT INTO `_web_workday` VALUES ('769', '2013-01-07', '1');
INSERT INTO `_web_workday` VALUES ('770', '2013-01-08', '1');
INSERT INTO `_web_workday` VALUES ('771', '2013-01-09', '1');
INSERT INTO `_web_workday` VALUES ('772', '2013-01-10', '1');
INSERT INTO `_web_workday` VALUES ('773', '2013-01-11', '1');
INSERT INTO `_web_workday` VALUES ('774', '2013-01-12', '0');
INSERT INTO `_web_workday` VALUES ('775', '2013-01-13', '0');
INSERT INTO `_web_workday` VALUES ('776', '2013-01-14', '1');
INSERT INTO `_web_workday` VALUES ('777', '2013-01-15', '1');
INSERT INTO `_web_workday` VALUES ('778', '2013-01-16', '1');
INSERT INTO `_web_workday` VALUES ('779', '2013-01-17', '1');
INSERT INTO `_web_workday` VALUES ('780', '2013-01-18', '1');
INSERT INTO `_web_workday` VALUES ('781', '2013-01-19', '0');
INSERT INTO `_web_workday` VALUES ('782', '2013-01-20', '0');
INSERT INTO `_web_workday` VALUES ('783', '2013-01-21', '1');
INSERT INTO `_web_workday` VALUES ('784', '2013-01-22', '1');
INSERT INTO `_web_workday` VALUES ('785', '2013-01-23', '1');
INSERT INTO `_web_workday` VALUES ('786', '2013-01-24', '1');
INSERT INTO `_web_workday` VALUES ('787', '2013-01-25', '1');
INSERT INTO `_web_workday` VALUES ('788', '2013-01-26', '0');
INSERT INTO `_web_workday` VALUES ('789', '2013-01-27', '0');
INSERT INTO `_web_workday` VALUES ('790', '2013-01-28', '1');
INSERT INTO `_web_workday` VALUES ('791', '2013-01-29', '1');
INSERT INTO `_web_workday` VALUES ('792', '2013-01-30', '1');
INSERT INTO `_web_workday` VALUES ('793', '2013-01-31', '1');
INSERT INTO `_web_workday` VALUES ('794', '2013-02-01', '1');
INSERT INTO `_web_workday` VALUES ('795', '2013-02-02', '0');
INSERT INTO `_web_workday` VALUES ('796', '2013-02-03', '0');
INSERT INTO `_web_workday` VALUES ('797', '2013-02-04', '1');
INSERT INTO `_web_workday` VALUES ('798', '2013-02-05', '1');
INSERT INTO `_web_workday` VALUES ('799', '2013-02-06', '1');
INSERT INTO `_web_workday` VALUES ('800', '2013-02-07', '1');
INSERT INTO `_web_workday` VALUES ('801', '2013-02-08', '1');
INSERT INTO `_web_workday` VALUES ('802', '2013-02-09', '0');
INSERT INTO `_web_workday` VALUES ('803', '2013-02-10', '0');
INSERT INTO `_web_workday` VALUES ('804', '2013-02-11', '0');
INSERT INTO `_web_workday` VALUES ('805', '2013-02-12', '0');
INSERT INTO `_web_workday` VALUES ('806', '2013-02-13', '0');
INSERT INTO `_web_workday` VALUES ('807', '2013-02-14', '0');
INSERT INTO `_web_workday` VALUES ('808', '2013-02-15', '0');
INSERT INTO `_web_workday` VALUES ('809', '2013-02-16', '1');
INSERT INTO `_web_workday` VALUES ('810', '2013-02-17', '1');
INSERT INTO `_web_workday` VALUES ('811', '2013-02-18', '1');
INSERT INTO `_web_workday` VALUES ('812', '2013-02-19', '1');
INSERT INTO `_web_workday` VALUES ('813', '2013-02-20', '1');
INSERT INTO `_web_workday` VALUES ('814', '2013-02-21', '1');
INSERT INTO `_web_workday` VALUES ('815', '2013-02-22', '1');
INSERT INTO `_web_workday` VALUES ('816', '2013-02-23', '0');
INSERT INTO `_web_workday` VALUES ('817', '2013-02-24', '0');
INSERT INTO `_web_workday` VALUES ('818', '2013-02-25', '1');
INSERT INTO `_web_workday` VALUES ('819', '2013-02-26', '1');
INSERT INTO `_web_workday` VALUES ('820', '2013-02-27', '1');
INSERT INTO `_web_workday` VALUES ('821', '2013-02-28', '1');
INSERT INTO `_web_workday` VALUES ('822', '2013-03-01', '1');
INSERT INTO `_web_workday` VALUES ('823', '2013-03-02', '0');
INSERT INTO `_web_workday` VALUES ('824', '2013-03-03', '0');
INSERT INTO `_web_workday` VALUES ('825', '2013-03-04', '1');
INSERT INTO `_web_workday` VALUES ('826', '2013-03-05', '1');
INSERT INTO `_web_workday` VALUES ('827', '2013-03-06', '1');
INSERT INTO `_web_workday` VALUES ('828', '2013-03-07', '1');
INSERT INTO `_web_workday` VALUES ('829', '2013-03-08', '1');
INSERT INTO `_web_workday` VALUES ('830', '2013-03-09', '0');
INSERT INTO `_web_workday` VALUES ('831', '2013-03-10', '0');
INSERT INTO `_web_workday` VALUES ('832', '2013-03-11', '1');
INSERT INTO `_web_workday` VALUES ('833', '2013-03-12', '1');
INSERT INTO `_web_workday` VALUES ('834', '2013-03-13', '1');
INSERT INTO `_web_workday` VALUES ('835', '2013-03-14', '1');
INSERT INTO `_web_workday` VALUES ('836', '2013-03-15', '1');
INSERT INTO `_web_workday` VALUES ('837', '2013-03-16', '0');
INSERT INTO `_web_workday` VALUES ('838', '2013-03-17', '0');
INSERT INTO `_web_workday` VALUES ('839', '2013-03-18', '1');
INSERT INTO `_web_workday` VALUES ('840', '2013-03-19', '1');
INSERT INTO `_web_workday` VALUES ('841', '2013-03-20', '1');
INSERT INTO `_web_workday` VALUES ('842', '2013-03-21', '1');
INSERT INTO `_web_workday` VALUES ('843', '2013-03-22', '1');
INSERT INTO `_web_workday` VALUES ('844', '2013-03-23', '0');
INSERT INTO `_web_workday` VALUES ('845', '2013-03-24', '0');
INSERT INTO `_web_workday` VALUES ('846', '2013-03-25', '1');
INSERT INTO `_web_workday` VALUES ('847', '2013-03-26', '1');
INSERT INTO `_web_workday` VALUES ('848', '2013-03-27', '1');
INSERT INTO `_web_workday` VALUES ('849', '2013-03-28', '1');
INSERT INTO `_web_workday` VALUES ('850', '2013-03-29', '1');
INSERT INTO `_web_workday` VALUES ('851', '2013-03-30', '0');
INSERT INTO `_web_workday` VALUES ('852', '2013-03-31', '0');
INSERT INTO `_web_workday` VALUES ('853', '2013-04-01', '1');
INSERT INTO `_web_workday` VALUES ('854', '2013-04-02', '1');
INSERT INTO `_web_workday` VALUES ('855', '2013-04-03', '1');
INSERT INTO `_web_workday` VALUES ('856', '2013-04-04', '0');
INSERT INTO `_web_workday` VALUES ('857', '2013-04-05', '0');
INSERT INTO `_web_workday` VALUES ('858', '2013-04-06', '0');
INSERT INTO `_web_workday` VALUES ('859', '2013-04-07', '1');
INSERT INTO `_web_workday` VALUES ('860', '2013-04-08', '1');
INSERT INTO `_web_workday` VALUES ('861', '2013-04-09', '1');
INSERT INTO `_web_workday` VALUES ('862', '2013-04-10', '1');
INSERT INTO `_web_workday` VALUES ('863', '2013-04-11', '1');
INSERT INTO `_web_workday` VALUES ('864', '2013-04-12', '1');
INSERT INTO `_web_workday` VALUES ('865', '2013-04-13', '0');
INSERT INTO `_web_workday` VALUES ('866', '2013-04-14', '0');
INSERT INTO `_web_workday` VALUES ('867', '2013-04-15', '1');
INSERT INTO `_web_workday` VALUES ('868', '2013-04-16', '1');
INSERT INTO `_web_workday` VALUES ('869', '2013-04-17', '1');
INSERT INTO `_web_workday` VALUES ('870', '2013-04-18', '1');
INSERT INTO `_web_workday` VALUES ('871', '2013-04-19', '1');
INSERT INTO `_web_workday` VALUES ('872', '2013-04-20', '0');
INSERT INTO `_web_workday` VALUES ('873', '2013-04-21', '0');
INSERT INTO `_web_workday` VALUES ('874', '2013-04-22', '1');
INSERT INTO `_web_workday` VALUES ('875', '2013-04-23', '1');
INSERT INTO `_web_workday` VALUES ('876', '2013-04-24', '1');
INSERT INTO `_web_workday` VALUES ('877', '2013-04-25', '1');
INSERT INTO `_web_workday` VALUES ('878', '2013-04-26', '1');
INSERT INTO `_web_workday` VALUES ('879', '2013-04-27', '1');
INSERT INTO `_web_workday` VALUES ('880', '2013-04-28', '1');
INSERT INTO `_web_workday` VALUES ('881', '2013-04-29', '0');
INSERT INTO `_web_workday` VALUES ('882', '2013-04-30', '0');
INSERT INTO `_web_workday` VALUES ('883', '2013-05-01', '0');
INSERT INTO `_web_workday` VALUES ('884', '2013-05-02', '1');
INSERT INTO `_web_workday` VALUES ('885', '2013-05-03', '1');
INSERT INTO `_web_workday` VALUES ('886', '2013-05-04', '0');
INSERT INTO `_web_workday` VALUES ('887', '2013-05-05', '0');
INSERT INTO `_web_workday` VALUES ('888', '2013-05-06', '1');
INSERT INTO `_web_workday` VALUES ('889', '2013-05-07', '1');
INSERT INTO `_web_workday` VALUES ('890', '2013-05-08', '1');
INSERT INTO `_web_workday` VALUES ('891', '2013-05-09', '1');
INSERT INTO `_web_workday` VALUES ('892', '2013-05-10', '1');
INSERT INTO `_web_workday` VALUES ('893', '2013-05-11', '0');
INSERT INTO `_web_workday` VALUES ('894', '2013-05-12', '0');
INSERT INTO `_web_workday` VALUES ('895', '2013-05-13', '1');
INSERT INTO `_web_workday` VALUES ('896', '2013-05-14', '1');
INSERT INTO `_web_workday` VALUES ('897', '2013-05-15', '1');
INSERT INTO `_web_workday` VALUES ('898', '2013-05-16', '1');
INSERT INTO `_web_workday` VALUES ('899', '2013-05-17', '1');
INSERT INTO `_web_workday` VALUES ('900', '2013-05-18', '0');
INSERT INTO `_web_workday` VALUES ('901', '2013-05-19', '0');
INSERT INTO `_web_workday` VALUES ('902', '2013-05-20', '1');
INSERT INTO `_web_workday` VALUES ('903', '2013-05-21', '1');
INSERT INTO `_web_workday` VALUES ('904', '2013-05-22', '1');
INSERT INTO `_web_workday` VALUES ('905', '2013-05-23', '1');
INSERT INTO `_web_workday` VALUES ('906', '2013-05-24', '1');
INSERT INTO `_web_workday` VALUES ('907', '2013-05-25', '0');
INSERT INTO `_web_workday` VALUES ('908', '2013-05-26', '0');
INSERT INTO `_web_workday` VALUES ('909', '2013-05-27', '1');
INSERT INTO `_web_workday` VALUES ('910', '2013-05-28', '1');
INSERT INTO `_web_workday` VALUES ('911', '2013-05-29', '1');
INSERT INTO `_web_workday` VALUES ('912', '2013-05-30', '1');
INSERT INTO `_web_workday` VALUES ('913', '2013-05-31', '1');
INSERT INTO `_web_workday` VALUES ('914', '2013-06-01', '0');
INSERT INTO `_web_workday` VALUES ('915', '2013-06-02', '0');
INSERT INTO `_web_workday` VALUES ('916', '2013-06-03', '1');
INSERT INTO `_web_workday` VALUES ('917', '2013-06-04', '1');
INSERT INTO `_web_workday` VALUES ('918', '2013-06-05', '1');
INSERT INTO `_web_workday` VALUES ('919', '2013-06-06', '1');
INSERT INTO `_web_workday` VALUES ('920', '2013-06-07', '1');
INSERT INTO `_web_workday` VALUES ('921', '2013-06-08', '1');
INSERT INTO `_web_workday` VALUES ('922', '2013-06-09', '1');
INSERT INTO `_web_workday` VALUES ('923', '2013-06-10', '0');
INSERT INTO `_web_workday` VALUES ('924', '2013-06-11', '0');
INSERT INTO `_web_workday` VALUES ('925', '2013-06-12', '0');
INSERT INTO `_web_workday` VALUES ('926', '2013-06-13', '1');
INSERT INTO `_web_workday` VALUES ('927', '2013-06-14', '1');
INSERT INTO `_web_workday` VALUES ('928', '2013-06-15', '0');
INSERT INTO `_web_workday` VALUES ('929', '2013-06-16', '0');
INSERT INTO `_web_workday` VALUES ('930', '2013-06-17', '1');
INSERT INTO `_web_workday` VALUES ('931', '2013-06-18', '1');
INSERT INTO `_web_workday` VALUES ('932', '2013-06-19', '1');
INSERT INTO `_web_workday` VALUES ('933', '2013-06-20', '1');
INSERT INTO `_web_workday` VALUES ('934', '2013-06-21', '1');
INSERT INTO `_web_workday` VALUES ('935', '2013-06-22', '0');
INSERT INTO `_web_workday` VALUES ('936', '2013-06-23', '0');
INSERT INTO `_web_workday` VALUES ('937', '2013-06-24', '1');
INSERT INTO `_web_workday` VALUES ('938', '2013-06-25', '1');
INSERT INTO `_web_workday` VALUES ('939', '2013-06-26', '1');
INSERT INTO `_web_workday` VALUES ('940', '2013-06-27', '1');
INSERT INTO `_web_workday` VALUES ('941', '2013-06-28', '1');
INSERT INTO `_web_workday` VALUES ('942', '2013-06-29', '0');
INSERT INTO `_web_workday` VALUES ('943', '2013-06-30', '0');
INSERT INTO `_web_workday` VALUES ('944', '2013-07-01', '1');
INSERT INTO `_web_workday` VALUES ('945', '2013-07-02', '1');
INSERT INTO `_web_workday` VALUES ('946', '2013-07-03', '1');
INSERT INTO `_web_workday` VALUES ('947', '2013-07-04', '1');
INSERT INTO `_web_workday` VALUES ('948', '2013-07-05', '1');
INSERT INTO `_web_workday` VALUES ('949', '2013-07-06', '0');
INSERT INTO `_web_workday` VALUES ('950', '2013-07-07', '0');
INSERT INTO `_web_workday` VALUES ('951', '2013-07-08', '1');
INSERT INTO `_web_workday` VALUES ('952', '2013-07-09', '1');
INSERT INTO `_web_workday` VALUES ('953', '2013-07-10', '1');
INSERT INTO `_web_workday` VALUES ('954', '2013-07-11', '1');
INSERT INTO `_web_workday` VALUES ('955', '2013-07-12', '1');
INSERT INTO `_web_workday` VALUES ('956', '2013-07-13', '0');
INSERT INTO `_web_workday` VALUES ('957', '2013-07-14', '0');
INSERT INTO `_web_workday` VALUES ('958', '2013-07-15', '1');
INSERT INTO `_web_workday` VALUES ('959', '2013-07-16', '1');
INSERT INTO `_web_workday` VALUES ('960', '2013-07-17', '1');
INSERT INTO `_web_workday` VALUES ('961', '2013-07-18', '1');
INSERT INTO `_web_workday` VALUES ('962', '2013-07-19', '1');
INSERT INTO `_web_workday` VALUES ('963', '2013-07-20', '0');
INSERT INTO `_web_workday` VALUES ('964', '2013-07-21', '0');
INSERT INTO `_web_workday` VALUES ('965', '2013-07-22', '1');
INSERT INTO `_web_workday` VALUES ('966', '2013-07-23', '1');
INSERT INTO `_web_workday` VALUES ('967', '2013-07-24', '1');
INSERT INTO `_web_workday` VALUES ('968', '2013-07-25', '1');
INSERT INTO `_web_workday` VALUES ('969', '2013-07-26', '1');
INSERT INTO `_web_workday` VALUES ('970', '2013-07-27', '0');
INSERT INTO `_web_workday` VALUES ('971', '2013-07-28', '0');
INSERT INTO `_web_workday` VALUES ('972', '2013-07-29', '1');
INSERT INTO `_web_workday` VALUES ('973', '2013-07-30', '1');
INSERT INTO `_web_workday` VALUES ('974', '2013-07-31', '1');
INSERT INTO `_web_workday` VALUES ('975', '2013-08-01', '1');
INSERT INTO `_web_workday` VALUES ('976', '2013-08-02', '1');
INSERT INTO `_web_workday` VALUES ('977', '2013-08-03', '0');
INSERT INTO `_web_workday` VALUES ('978', '2013-08-04', '0');
INSERT INTO `_web_workday` VALUES ('979', '2013-08-05', '1');
INSERT INTO `_web_workday` VALUES ('980', '2013-08-06', '1');
INSERT INTO `_web_workday` VALUES ('981', '2013-08-07', '1');
INSERT INTO `_web_workday` VALUES ('982', '2013-08-08', '1');
INSERT INTO `_web_workday` VALUES ('983', '2013-08-09', '1');
INSERT INTO `_web_workday` VALUES ('984', '2013-08-10', '0');
INSERT INTO `_web_workday` VALUES ('985', '2013-08-11', '0');
INSERT INTO `_web_workday` VALUES ('986', '2013-08-12', '1');
INSERT INTO `_web_workday` VALUES ('987', '2013-08-13', '1');
INSERT INTO `_web_workday` VALUES ('988', '2013-08-14', '1');
INSERT INTO `_web_workday` VALUES ('989', '2013-08-15', '1');
INSERT INTO `_web_workday` VALUES ('990', '2013-08-16', '1');
INSERT INTO `_web_workday` VALUES ('991', '2013-08-17', '0');
INSERT INTO `_web_workday` VALUES ('992', '2013-08-18', '0');
INSERT INTO `_web_workday` VALUES ('993', '2013-08-19', '1');
INSERT INTO `_web_workday` VALUES ('994', '2013-08-20', '1');
INSERT INTO `_web_workday` VALUES ('995', '2013-08-21', '1');
INSERT INTO `_web_workday` VALUES ('996', '2013-08-22', '1');
INSERT INTO `_web_workday` VALUES ('997', '2013-08-23', '1');
INSERT INTO `_web_workday` VALUES ('998', '2013-08-24', '0');
INSERT INTO `_web_workday` VALUES ('999', '2013-08-25', '0');
INSERT INTO `_web_workday` VALUES ('1000', '2013-08-26', '1');
INSERT INTO `_web_workday` VALUES ('1001', '2013-08-27', '1');
INSERT INTO `_web_workday` VALUES ('1002', '2013-08-28', '1');
INSERT INTO `_web_workday` VALUES ('1003', '2013-08-29', '1');
INSERT INTO `_web_workday` VALUES ('1004', '2013-08-30', '1');
INSERT INTO `_web_workday` VALUES ('1005', '2013-08-31', '0');
INSERT INTO `_web_workday` VALUES ('1006', '2013-09-01', '0');
INSERT INTO `_web_workday` VALUES ('1007', '2013-09-02', '1');
INSERT INTO `_web_workday` VALUES ('1008', '2013-09-03', '1');
INSERT INTO `_web_workday` VALUES ('1009', '2013-09-04', '1');
INSERT INTO `_web_workday` VALUES ('1010', '2013-09-05', '1');
INSERT INTO `_web_workday` VALUES ('1011', '2013-09-06', '1');
INSERT INTO `_web_workday` VALUES ('1012', '2013-09-07', '0');
INSERT INTO `_web_workday` VALUES ('1013', '2013-09-08', '0');
INSERT INTO `_web_workday` VALUES ('1014', '2013-09-09', '1');
INSERT INTO `_web_workday` VALUES ('1015', '2013-09-10', '1');
INSERT INTO `_web_workday` VALUES ('1016', '2013-09-11', '1');
INSERT INTO `_web_workday` VALUES ('1017', '2013-09-12', '1');
INSERT INTO `_web_workday` VALUES ('1018', '2013-09-13', '1');
INSERT INTO `_web_workday` VALUES ('1019', '2013-09-14', '0');
INSERT INTO `_web_workday` VALUES ('1020', '2013-09-15', '0');
INSERT INTO `_web_workday` VALUES ('1021', '2013-09-16', '1');
INSERT INTO `_web_workday` VALUES ('1022', '2013-09-17', '1');
INSERT INTO `_web_workday` VALUES ('1023', '2013-09-18', '1');
INSERT INTO `_web_workday` VALUES ('1024', '2013-09-19', '0');
INSERT INTO `_web_workday` VALUES ('1025', '2013-09-20', '0');
INSERT INTO `_web_workday` VALUES ('1026', '2013-09-21', '0');
INSERT INTO `_web_workday` VALUES ('1027', '2013-09-22', '1');
INSERT INTO `_web_workday` VALUES ('1028', '2013-09-23', '1');
INSERT INTO `_web_workday` VALUES ('1029', '2013-09-24', '1');
INSERT INTO `_web_workday` VALUES ('1030', '2013-09-25', '1');
INSERT INTO `_web_workday` VALUES ('1031', '2013-09-26', '1');
INSERT INTO `_web_workday` VALUES ('1032', '2013-09-27', '1');
INSERT INTO `_web_workday` VALUES ('1033', '2013-09-28', '0');
INSERT INTO `_web_workday` VALUES ('1034', '2013-09-29', '1');
INSERT INTO `_web_workday` VALUES ('1035', '2013-09-30', '1');
INSERT INTO `_web_workday` VALUES ('1036', '2013-10-01', '0');
INSERT INTO `_web_workday` VALUES ('1037', '2013-10-02', '0');
INSERT INTO `_web_workday` VALUES ('1038', '2013-10-03', '0');
INSERT INTO `_web_workday` VALUES ('1039', '2013-10-04', '0');
INSERT INTO `_web_workday` VALUES ('1040', '2013-10-05', '0');
INSERT INTO `_web_workday` VALUES ('1041', '2013-10-06', '0');
INSERT INTO `_web_workday` VALUES ('1042', '2013-10-07', '0');
INSERT INTO `_web_workday` VALUES ('1043', '2013-10-08', '1');
INSERT INTO `_web_workday` VALUES ('1044', '2013-10-09', '1');
INSERT INTO `_web_workday` VALUES ('1045', '2013-10-10', '1');
INSERT INTO `_web_workday` VALUES ('1046', '2013-10-11', '1');
INSERT INTO `_web_workday` VALUES ('1047', '2013-10-12', '1');
INSERT INTO `_web_workday` VALUES ('1048', '2013-10-13', '0');
INSERT INTO `_web_workday` VALUES ('1049', '2013-10-14', '1');
INSERT INTO `_web_workday` VALUES ('1050', '2013-10-15', '1');
INSERT INTO `_web_workday` VALUES ('1051', '2013-10-16', '1');
INSERT INTO `_web_workday` VALUES ('1052', '2013-10-17', '1');
INSERT INTO `_web_workday` VALUES ('1053', '2013-10-18', '1');
INSERT INTO `_web_workday` VALUES ('1054', '2013-10-19', '0');
INSERT INTO `_web_workday` VALUES ('1055', '2013-10-20', '0');
INSERT INTO `_web_workday` VALUES ('1056', '2013-10-21', '1');
INSERT INTO `_web_workday` VALUES ('1057', '2013-10-22', '1');
INSERT INTO `_web_workday` VALUES ('1058', '2013-10-23', '1');
INSERT INTO `_web_workday` VALUES ('1059', '2013-10-24', '1');
INSERT INTO `_web_workday` VALUES ('1060', '2013-10-25', '1');
INSERT INTO `_web_workday` VALUES ('1061', '2013-10-26', '0');
INSERT INTO `_web_workday` VALUES ('1062', '2013-10-27', '0');
INSERT INTO `_web_workday` VALUES ('1063', '2013-10-28', '1');
INSERT INTO `_web_workday` VALUES ('1064', '2013-10-29', '1');
INSERT INTO `_web_workday` VALUES ('1065', '2013-10-30', '1');
INSERT INTO `_web_workday` VALUES ('1066', '2013-10-31', '1');
INSERT INTO `_web_workday` VALUES ('1067', '2013-11-01', '1');
INSERT INTO `_web_workday` VALUES ('1068', '2013-11-02', '0');
INSERT INTO `_web_workday` VALUES ('1069', '2013-11-03', '0');
INSERT INTO `_web_workday` VALUES ('1070', '2013-11-04', '1');
INSERT INTO `_web_workday` VALUES ('1071', '2013-11-05', '1');
INSERT INTO `_web_workday` VALUES ('1072', '2013-11-06', '1');
INSERT INTO `_web_workday` VALUES ('1073', '2013-11-07', '1');
INSERT INTO `_web_workday` VALUES ('1074', '2013-11-08', '1');
INSERT INTO `_web_workday` VALUES ('1075', '2013-11-09', '0');
INSERT INTO `_web_workday` VALUES ('1076', '2013-11-10', '0');
INSERT INTO `_web_workday` VALUES ('1077', '2013-11-11', '1');
INSERT INTO `_web_workday` VALUES ('1078', '2013-11-12', '1');
INSERT INTO `_web_workday` VALUES ('1079', '2013-11-13', '1');
INSERT INTO `_web_workday` VALUES ('1080', '2013-11-14', '1');
INSERT INTO `_web_workday` VALUES ('1081', '2013-11-15', '1');
INSERT INTO `_web_workday` VALUES ('1082', '2013-11-16', '0');
INSERT INTO `_web_workday` VALUES ('1083', '2013-11-17', '0');
INSERT INTO `_web_workday` VALUES ('1084', '2013-11-18', '1');
INSERT INTO `_web_workday` VALUES ('1085', '2013-11-19', '1');
INSERT INTO `_web_workday` VALUES ('1086', '2013-11-20', '1');
INSERT INTO `_web_workday` VALUES ('1087', '2013-11-21', '1');
INSERT INTO `_web_workday` VALUES ('1088', '2013-11-22', '1');
INSERT INTO `_web_workday` VALUES ('1089', '2013-11-23', '0');
INSERT INTO `_web_workday` VALUES ('1090', '2013-11-24', '0');
INSERT INTO `_web_workday` VALUES ('1091', '2013-11-25', '1');
INSERT INTO `_web_workday` VALUES ('1092', '2013-11-26', '1');
INSERT INTO `_web_workday` VALUES ('1093', '2013-11-27', '1');
INSERT INTO `_web_workday` VALUES ('1094', '2013-11-28', '1');
INSERT INTO `_web_workday` VALUES ('1095', '2013-11-29', '1');
INSERT INTO `_web_workday` VALUES ('1096', '2013-11-30', '0');
INSERT INTO `_web_workday` VALUES ('1097', '2013-12-01', '0');
INSERT INTO `_web_workday` VALUES ('1098', '2013-12-02', '1');
INSERT INTO `_web_workday` VALUES ('1099', '2013-12-03', '1');
INSERT INTO `_web_workday` VALUES ('1100', '2013-12-04', '1');
INSERT INTO `_web_workday` VALUES ('1101', '2013-12-05', '1');
INSERT INTO `_web_workday` VALUES ('1102', '2013-12-06', '1');
INSERT INTO `_web_workday` VALUES ('1103', '2013-12-07', '0');
INSERT INTO `_web_workday` VALUES ('1104', '2013-12-08', '0');
INSERT INTO `_web_workday` VALUES ('1105', '2013-12-09', '1');
INSERT INTO `_web_workday` VALUES ('1106', '2013-12-10', '1');
INSERT INTO `_web_workday` VALUES ('1107', '2013-12-11', '1');
INSERT INTO `_web_workday` VALUES ('1108', '2013-12-12', '1');
INSERT INTO `_web_workday` VALUES ('1109', '2013-12-13', '1');
INSERT INTO `_web_workday` VALUES ('1110', '2013-12-14', '0');
INSERT INTO `_web_workday` VALUES ('1111', '2013-12-15', '0');
INSERT INTO `_web_workday` VALUES ('1112', '2013-12-16', '1');
INSERT INTO `_web_workday` VALUES ('1113', '2013-12-17', '1');
INSERT INTO `_web_workday` VALUES ('1114', '2013-12-18', '1');
INSERT INTO `_web_workday` VALUES ('1115', '2013-12-19', '1');
INSERT INTO `_web_workday` VALUES ('1116', '2013-12-20', '1');
INSERT INTO `_web_workday` VALUES ('1117', '2013-12-21', '0');
INSERT INTO `_web_workday` VALUES ('1118', '2013-12-22', '0');
INSERT INTO `_web_workday` VALUES ('1119', '2013-12-23', '1');
INSERT INTO `_web_workday` VALUES ('1120', '2013-12-24', '1');
INSERT INTO `_web_workday` VALUES ('1121', '2013-12-25', '1');
INSERT INTO `_web_workday` VALUES ('1122', '2013-12-26', '1');
INSERT INTO `_web_workday` VALUES ('1123', '2013-12-27', '1');
INSERT INTO `_web_workday` VALUES ('1124', '2013-12-28', '0');
INSERT INTO `_web_workday` VALUES ('1125', '2013-12-29', '0');
INSERT INTO `_web_workday` VALUES ('1126', '2013-12-30', '1');
INSERT INTO `_web_workday` VALUES ('1127', '2013-12-31', '1');
INSERT INTO `_web_workday` VALUES ('1128', '2014-01-01', '0');
INSERT INTO `_web_workday` VALUES ('1129', '2014-01-02', '1');
INSERT INTO `_web_workday` VALUES ('1130', '2014-01-03', '1');
INSERT INTO `_web_workday` VALUES ('1131', '2014-01-04', '0');
INSERT INTO `_web_workday` VALUES ('1132', '2014-01-05', '0');
INSERT INTO `_web_workday` VALUES ('1133', '2014-01-06', '1');
INSERT INTO `_web_workday` VALUES ('1134', '2014-01-07', '1');
INSERT INTO `_web_workday` VALUES ('1135', '2014-01-08', '1');
INSERT INTO `_web_workday` VALUES ('1136', '2014-01-09', '1');
INSERT INTO `_web_workday` VALUES ('1137', '2014-01-10', '1');
INSERT INTO `_web_workday` VALUES ('1138', '2014-01-11', '0');
INSERT INTO `_web_workday` VALUES ('1139', '2014-01-12', '0');
INSERT INTO `_web_workday` VALUES ('1140', '2014-01-13', '1');
INSERT INTO `_web_workday` VALUES ('1141', '2014-01-14', '1');
INSERT INTO `_web_workday` VALUES ('1142', '2014-01-15', '1');
INSERT INTO `_web_workday` VALUES ('1143', '2014-01-16', '1');
INSERT INTO `_web_workday` VALUES ('1144', '2014-01-17', '1');
INSERT INTO `_web_workday` VALUES ('1145', '2014-01-18', '0');
INSERT INTO `_web_workday` VALUES ('1146', '2014-01-19', '0');
INSERT INTO `_web_workday` VALUES ('1147', '2014-01-20', '1');
INSERT INTO `_web_workday` VALUES ('1148', '2014-01-21', '1');
INSERT INTO `_web_workday` VALUES ('1149', '2014-01-22', '1');
INSERT INTO `_web_workday` VALUES ('1150', '2014-01-23', '1');
INSERT INTO `_web_workday` VALUES ('1151', '2014-01-24', '1');
INSERT INTO `_web_workday` VALUES ('1152', '2014-01-25', '0');
INSERT INTO `_web_workday` VALUES ('1153', '2014-01-26', '1');
INSERT INTO `_web_workday` VALUES ('1154', '2014-01-27', '1');
INSERT INTO `_web_workday` VALUES ('1155', '2014-01-28', '1');
INSERT INTO `_web_workday` VALUES ('1156', '2014-01-29', '1');
INSERT INTO `_web_workday` VALUES ('1157', '2014-01-30', '1');
INSERT INTO `_web_workday` VALUES ('1158', '2014-01-31', '0');
INSERT INTO `_web_workday` VALUES ('1159', '2014-02-01', '0');
INSERT INTO `_web_workday` VALUES ('1160', '2014-02-02', '0');
INSERT INTO `_web_workday` VALUES ('1161', '2014-02-03', '0');
INSERT INTO `_web_workday` VALUES ('1162', '2014-02-04', '0');
INSERT INTO `_web_workday` VALUES ('1163', '2014-02-05', '0');
INSERT INTO `_web_workday` VALUES ('1164', '2014-02-06', '0');
INSERT INTO `_web_workday` VALUES ('1165', '2014-02-07', '1');
INSERT INTO `_web_workday` VALUES ('1166', '2014-02-08', '1');
INSERT INTO `_web_workday` VALUES ('1167', '2014-02-09', '0');
INSERT INTO `_web_workday` VALUES ('1168', '2014-02-10', '1');
INSERT INTO `_web_workday` VALUES ('1169', '2014-02-11', '1');
INSERT INTO `_web_workday` VALUES ('1170', '2014-02-12', '1');
INSERT INTO `_web_workday` VALUES ('1171', '2014-02-13', '1');
INSERT INTO `_web_workday` VALUES ('1172', '2014-02-14', '1');
INSERT INTO `_web_workday` VALUES ('1173', '2014-02-15', '0');
INSERT INTO `_web_workday` VALUES ('1174', '2014-02-16', '0');
INSERT INTO `_web_workday` VALUES ('1175', '2014-02-17', '1');
INSERT INTO `_web_workday` VALUES ('1176', '2014-02-18', '1');
INSERT INTO `_web_workday` VALUES ('1177', '2014-02-19', '1');
INSERT INTO `_web_workday` VALUES ('1178', '2014-02-20', '1');
INSERT INTO `_web_workday` VALUES ('1179', '2014-02-21', '1');
INSERT INTO `_web_workday` VALUES ('1180', '2014-02-22', '0');
INSERT INTO `_web_workday` VALUES ('1181', '2014-02-23', '0');
INSERT INTO `_web_workday` VALUES ('1182', '2014-02-24', '1');
INSERT INTO `_web_workday` VALUES ('1183', '2014-02-25', '1');
INSERT INTO `_web_workday` VALUES ('1184', '2014-02-26', '1');
INSERT INTO `_web_workday` VALUES ('1185', '2014-02-27', '1');
INSERT INTO `_web_workday` VALUES ('1186', '2014-02-28', '1');
INSERT INTO `_web_workday` VALUES ('1187', '2014-03-01', '0');
INSERT INTO `_web_workday` VALUES ('1188', '2014-03-02', '0');
INSERT INTO `_web_workday` VALUES ('1189', '2014-03-03', '1');
INSERT INTO `_web_workday` VALUES ('1190', '2014-03-04', '1');
INSERT INTO `_web_workday` VALUES ('1191', '2014-03-05', '1');
INSERT INTO `_web_workday` VALUES ('1192', '2014-03-06', '1');
INSERT INTO `_web_workday` VALUES ('1193', '2014-03-07', '1');
INSERT INTO `_web_workday` VALUES ('1194', '2014-03-08', '0');
INSERT INTO `_web_workday` VALUES ('1195', '2014-03-09', '0');
INSERT INTO `_web_workday` VALUES ('1196', '2014-03-10', '1');
INSERT INTO `_web_workday` VALUES ('1197', '2014-03-11', '1');
INSERT INTO `_web_workday` VALUES ('1198', '2014-03-12', '1');
INSERT INTO `_web_workday` VALUES ('1199', '2014-03-13', '1');
INSERT INTO `_web_workday` VALUES ('1200', '2014-03-14', '1');
INSERT INTO `_web_workday` VALUES ('1201', '2014-03-15', '0');
INSERT INTO `_web_workday` VALUES ('1202', '2014-03-16', '0');
INSERT INTO `_web_workday` VALUES ('1203', '2014-03-17', '1');
INSERT INTO `_web_workday` VALUES ('1204', '2014-03-18', '1');
INSERT INTO `_web_workday` VALUES ('1205', '2014-03-19', '1');
INSERT INTO `_web_workday` VALUES ('1206', '2014-03-20', '1');
INSERT INTO `_web_workday` VALUES ('1207', '2014-03-21', '1');
INSERT INTO `_web_workday` VALUES ('1208', '2014-03-22', '0');
INSERT INTO `_web_workday` VALUES ('1209', '2014-03-23', '0');
INSERT INTO `_web_workday` VALUES ('1210', '2014-03-24', '1');
INSERT INTO `_web_workday` VALUES ('1211', '2014-03-25', '1');
INSERT INTO `_web_workday` VALUES ('1212', '2014-03-26', '1');
INSERT INTO `_web_workday` VALUES ('1213', '2014-03-27', '1');
INSERT INTO `_web_workday` VALUES ('1214', '2014-03-28', '1');
INSERT INTO `_web_workday` VALUES ('1215', '2014-03-29', '0');
INSERT INTO `_web_workday` VALUES ('1216', '2014-03-30', '0');
INSERT INTO `_web_workday` VALUES ('1217', '2014-03-31', '1');
INSERT INTO `_web_workday` VALUES ('1218', '2014-04-01', '1');
INSERT INTO `_web_workday` VALUES ('1219', '2014-04-02', '1');
INSERT INTO `_web_workday` VALUES ('1220', '2014-04-03', '1');
INSERT INTO `_web_workday` VALUES ('1221', '2014-04-04', '1');
INSERT INTO `_web_workday` VALUES ('1222', '2014-04-05', '0');
INSERT INTO `_web_workday` VALUES ('1223', '2014-04-06', '0');
INSERT INTO `_web_workday` VALUES ('1224', '2014-04-07', '0');
INSERT INTO `_web_workday` VALUES ('1225', '2014-04-08', '1');
INSERT INTO `_web_workday` VALUES ('1226', '2014-04-09', '1');
INSERT INTO `_web_workday` VALUES ('1227', '2014-04-10', '1');
INSERT INTO `_web_workday` VALUES ('1228', '2014-04-11', '1');
INSERT INTO `_web_workday` VALUES ('1229', '2014-04-12', '0');
INSERT INTO `_web_workday` VALUES ('1230', '2014-04-13', '0');
INSERT INTO `_web_workday` VALUES ('1231', '2014-04-14', '1');
INSERT INTO `_web_workday` VALUES ('1232', '2014-04-15', '1');
INSERT INTO `_web_workday` VALUES ('1233', '2014-04-16', '1');
INSERT INTO `_web_workday` VALUES ('1234', '2014-04-17', '1');
INSERT INTO `_web_workday` VALUES ('1235', '2014-04-18', '1');
INSERT INTO `_web_workday` VALUES ('1236', '2014-04-19', '0');
INSERT INTO `_web_workday` VALUES ('1237', '2014-04-20', '0');
INSERT INTO `_web_workday` VALUES ('1238', '2014-04-21', '1');
INSERT INTO `_web_workday` VALUES ('1239', '2014-04-22', '1');
INSERT INTO `_web_workday` VALUES ('1240', '2014-04-23', '1');
INSERT INTO `_web_workday` VALUES ('1241', '2014-04-24', '1');
INSERT INTO `_web_workday` VALUES ('1242', '2014-04-25', '1');
INSERT INTO `_web_workday` VALUES ('1243', '2014-04-26', '0');
INSERT INTO `_web_workday` VALUES ('1244', '2014-04-27', '0');
INSERT INTO `_web_workday` VALUES ('1245', '2014-04-28', '1');
INSERT INTO `_web_workday` VALUES ('1246', '2014-04-29', '1');
INSERT INTO `_web_workday` VALUES ('1247', '2014-04-30', '1');
INSERT INTO `_web_workday` VALUES ('1248', '2014-05-01', '0');
INSERT INTO `_web_workday` VALUES ('1249', '2014-05-02', '0');
INSERT INTO `_web_workday` VALUES ('1250', '2014-05-03', '0');
INSERT INTO `_web_workday` VALUES ('1251', '2014-05-04', '1');
INSERT INTO `_web_workday` VALUES ('1252', '2014-05-05', '1');
INSERT INTO `_web_workday` VALUES ('1253', '2014-05-06', '1');
INSERT INTO `_web_workday` VALUES ('1254', '2014-05-07', '1');
INSERT INTO `_web_workday` VALUES ('1255', '2014-05-08', '1');
INSERT INTO `_web_workday` VALUES ('1256', '2014-05-09', '1');
INSERT INTO `_web_workday` VALUES ('1257', '2014-05-10', '0');
INSERT INTO `_web_workday` VALUES ('1258', '2014-05-11', '0');
INSERT INTO `_web_workday` VALUES ('1259', '2014-05-12', '1');
INSERT INTO `_web_workday` VALUES ('1260', '2014-05-13', '1');
INSERT INTO `_web_workday` VALUES ('1261', '2014-05-14', '1');
INSERT INTO `_web_workday` VALUES ('1262', '2014-05-15', '1');
INSERT INTO `_web_workday` VALUES ('1263', '2014-05-16', '1');
INSERT INTO `_web_workday` VALUES ('1264', '2014-05-17', '0');
INSERT INTO `_web_workday` VALUES ('1265', '2014-05-18', '0');
INSERT INTO `_web_workday` VALUES ('1266', '2014-05-19', '1');
INSERT INTO `_web_workday` VALUES ('1267', '2014-05-20', '1');
INSERT INTO `_web_workday` VALUES ('1268', '2014-05-21', '1');
INSERT INTO `_web_workday` VALUES ('1269', '2014-05-22', '1');
INSERT INTO `_web_workday` VALUES ('1270', '2014-05-23', '1');
INSERT INTO `_web_workday` VALUES ('1271', '2014-05-24', '0');
INSERT INTO `_web_workday` VALUES ('1272', '2014-05-25', '0');
INSERT INTO `_web_workday` VALUES ('1273', '2014-05-26', '1');
INSERT INTO `_web_workday` VALUES ('1274', '2014-05-27', '1');
INSERT INTO `_web_workday` VALUES ('1275', '2014-05-28', '1');
INSERT INTO `_web_workday` VALUES ('1276', '2014-05-29', '1');
INSERT INTO `_web_workday` VALUES ('1277', '2014-05-30', '1');
INSERT INTO `_web_workday` VALUES ('1278', '2014-05-31', '0');
INSERT INTO `_web_workday` VALUES ('1279', '2014-06-01', '0');
INSERT INTO `_web_workday` VALUES ('1280', '2014-06-02', '0');
INSERT INTO `_web_workday` VALUES ('1281', '2014-06-03', '1');
INSERT INTO `_web_workday` VALUES ('1282', '2014-06-04', '1');
INSERT INTO `_web_workday` VALUES ('1283', '2014-06-05', '1');
INSERT INTO `_web_workday` VALUES ('1284', '2014-06-06', '1');
INSERT INTO `_web_workday` VALUES ('1285', '2014-06-07', '0');
INSERT INTO `_web_workday` VALUES ('1286', '2014-06-08', '0');
INSERT INTO `_web_workday` VALUES ('1287', '2014-06-09', '1');
INSERT INTO `_web_workday` VALUES ('1288', '2014-06-10', '1');
INSERT INTO `_web_workday` VALUES ('1289', '2014-06-11', '1');
INSERT INTO `_web_workday` VALUES ('1290', '2014-06-12', '1');
INSERT INTO `_web_workday` VALUES ('1291', '2014-06-13', '1');
INSERT INTO `_web_workday` VALUES ('1292', '2014-06-14', '0');
INSERT INTO `_web_workday` VALUES ('1293', '2014-06-15', '0');
INSERT INTO `_web_workday` VALUES ('1294', '2014-06-16', '1');
INSERT INTO `_web_workday` VALUES ('1295', '2014-06-17', '1');
INSERT INTO `_web_workday` VALUES ('1296', '2014-06-18', '1');
INSERT INTO `_web_workday` VALUES ('1297', '2014-06-19', '1');
INSERT INTO `_web_workday` VALUES ('1298', '2014-06-20', '1');
INSERT INTO `_web_workday` VALUES ('1299', '2014-06-21', '0');
INSERT INTO `_web_workday` VALUES ('1300', '2014-06-22', '0');
INSERT INTO `_web_workday` VALUES ('1301', '2014-06-23', '1');
INSERT INTO `_web_workday` VALUES ('1302', '2014-06-24', '1');
INSERT INTO `_web_workday` VALUES ('1303', '2014-06-25', '1');
INSERT INTO `_web_workday` VALUES ('1304', '2014-06-26', '1');
INSERT INTO `_web_workday` VALUES ('1305', '2014-06-27', '1');
INSERT INTO `_web_workday` VALUES ('1306', '2014-06-28', '0');
INSERT INTO `_web_workday` VALUES ('1307', '2014-06-29', '0');
INSERT INTO `_web_workday` VALUES ('1308', '2014-06-30', '1');
INSERT INTO `_web_workday` VALUES ('1309', '2014-07-01', '1');
INSERT INTO `_web_workday` VALUES ('1310', '2014-07-02', '1');
INSERT INTO `_web_workday` VALUES ('1311', '2014-07-03', '1');
INSERT INTO `_web_workday` VALUES ('1312', '2014-07-04', '1');
INSERT INTO `_web_workday` VALUES ('1313', '2014-07-05', '0');
INSERT INTO `_web_workday` VALUES ('1314', '2014-07-06', '0');
INSERT INTO `_web_workday` VALUES ('1315', '2014-07-07', '1');
INSERT INTO `_web_workday` VALUES ('1316', '2014-07-08', '1');
INSERT INTO `_web_workday` VALUES ('1317', '2014-07-09', '1');
INSERT INTO `_web_workday` VALUES ('1318', '2014-07-10', '1');
INSERT INTO `_web_workday` VALUES ('1319', '2014-07-11', '1');
INSERT INTO `_web_workday` VALUES ('1320', '2014-07-12', '0');
INSERT INTO `_web_workday` VALUES ('1321', '2014-07-13', '0');
INSERT INTO `_web_workday` VALUES ('1322', '2014-07-14', '1');
INSERT INTO `_web_workday` VALUES ('1323', '2014-07-15', '1');
INSERT INTO `_web_workday` VALUES ('1324', '2014-07-16', '1');
INSERT INTO `_web_workday` VALUES ('1325', '2014-07-17', '1');
INSERT INTO `_web_workday` VALUES ('1326', '2014-07-18', '1');
INSERT INTO `_web_workday` VALUES ('1327', '2014-07-19', '0');
INSERT INTO `_web_workday` VALUES ('1328', '2014-07-20', '0');
INSERT INTO `_web_workday` VALUES ('1329', '2014-07-21', '1');
INSERT INTO `_web_workday` VALUES ('1330', '2014-07-22', '1');
INSERT INTO `_web_workday` VALUES ('1331', '2014-07-23', '1');
INSERT INTO `_web_workday` VALUES ('1332', '2014-07-24', '1');
INSERT INTO `_web_workday` VALUES ('1333', '2014-07-25', '1');
INSERT INTO `_web_workday` VALUES ('1334', '2014-07-26', '0');
INSERT INTO `_web_workday` VALUES ('1335', '2014-07-27', '0');
INSERT INTO `_web_workday` VALUES ('1336', '2014-07-28', '1');
INSERT INTO `_web_workday` VALUES ('1337', '2014-07-29', '1');
INSERT INTO `_web_workday` VALUES ('1338', '2014-07-30', '1');
INSERT INTO `_web_workday` VALUES ('1339', '2014-07-31', '1');
INSERT INTO `_web_workday` VALUES ('1340', '2014-08-01', '1');
INSERT INTO `_web_workday` VALUES ('1341', '2014-08-02', '0');
INSERT INTO `_web_workday` VALUES ('1342', '2014-08-03', '0');
INSERT INTO `_web_workday` VALUES ('1343', '2014-08-04', '1');
INSERT INTO `_web_workday` VALUES ('1344', '2014-08-05', '1');
INSERT INTO `_web_workday` VALUES ('1345', '2014-08-06', '1');
INSERT INTO `_web_workday` VALUES ('1346', '2014-08-07', '1');
INSERT INTO `_web_workday` VALUES ('1347', '2014-08-08', '1');
INSERT INTO `_web_workday` VALUES ('1348', '2014-08-09', '0');
INSERT INTO `_web_workday` VALUES ('1349', '2014-08-10', '0');
INSERT INTO `_web_workday` VALUES ('1350', '2014-08-11', '1');
INSERT INTO `_web_workday` VALUES ('1351', '2014-08-12', '1');
INSERT INTO `_web_workday` VALUES ('1352', '2014-08-13', '1');
INSERT INTO `_web_workday` VALUES ('1353', '2014-08-14', '1');
INSERT INTO `_web_workday` VALUES ('1354', '2014-08-15', '1');
INSERT INTO `_web_workday` VALUES ('1355', '2014-08-16', '0');
INSERT INTO `_web_workday` VALUES ('1356', '2014-08-17', '0');
INSERT INTO `_web_workday` VALUES ('1357', '2014-08-18', '1');
INSERT INTO `_web_workday` VALUES ('1358', '2014-08-19', '1');
INSERT INTO `_web_workday` VALUES ('1359', '2014-08-20', '1');
INSERT INTO `_web_workday` VALUES ('1360', '2014-08-21', '1');
INSERT INTO `_web_workday` VALUES ('1361', '2014-08-22', '1');
INSERT INTO `_web_workday` VALUES ('1362', '2014-08-23', '0');
INSERT INTO `_web_workday` VALUES ('1363', '2014-08-24', '0');
INSERT INTO `_web_workday` VALUES ('1364', '2014-08-25', '1');
INSERT INTO `_web_workday` VALUES ('1365', '2014-08-26', '1');
INSERT INTO `_web_workday` VALUES ('1366', '2014-08-27', '1');
INSERT INTO `_web_workday` VALUES ('1367', '2014-08-28', '1');
INSERT INTO `_web_workday` VALUES ('1368', '2014-08-29', '1');
INSERT INTO `_web_workday` VALUES ('1369', '2014-08-30', '0');
INSERT INTO `_web_workday` VALUES ('1370', '2014-08-31', '0');
INSERT INTO `_web_workday` VALUES ('1371', '2014-09-01', '1');
INSERT INTO `_web_workday` VALUES ('1372', '2014-09-02', '1');
INSERT INTO `_web_workday` VALUES ('1373', '2014-09-03', '1');
INSERT INTO `_web_workday` VALUES ('1374', '2014-09-04', '1');
INSERT INTO `_web_workday` VALUES ('1375', '2014-09-05', '1');
INSERT INTO `_web_workday` VALUES ('1376', '2014-09-06', '0');
INSERT INTO `_web_workday` VALUES ('1377', '2014-09-07', '0');
INSERT INTO `_web_workday` VALUES ('1378', '2014-09-08', '0');
INSERT INTO `_web_workday` VALUES ('1379', '2014-09-09', '1');
INSERT INTO `_web_workday` VALUES ('1380', '2014-09-10', '1');
INSERT INTO `_web_workday` VALUES ('1381', '2014-09-11', '1');
INSERT INTO `_web_workday` VALUES ('1382', '2014-09-12', '1');
INSERT INTO `_web_workday` VALUES ('1383', '2014-09-13', '0');
INSERT INTO `_web_workday` VALUES ('1384', '2014-09-14', '0');
INSERT INTO `_web_workday` VALUES ('1385', '2014-09-15', '1');
INSERT INTO `_web_workday` VALUES ('1386', '2014-09-16', '1');
INSERT INTO `_web_workday` VALUES ('1387', '2014-09-17', '1');
INSERT INTO `_web_workday` VALUES ('1388', '2014-09-18', '1');
INSERT INTO `_web_workday` VALUES ('1389', '2014-09-19', '1');
INSERT INTO `_web_workday` VALUES ('1390', '2014-09-20', '0');
INSERT INTO `_web_workday` VALUES ('1391', '2014-09-21', '0');
INSERT INTO `_web_workday` VALUES ('1392', '2014-09-22', '1');
INSERT INTO `_web_workday` VALUES ('1393', '2014-09-23', '1');
INSERT INTO `_web_workday` VALUES ('1394', '2014-09-24', '1');
INSERT INTO `_web_workday` VALUES ('1395', '2014-09-25', '1');
INSERT INTO `_web_workday` VALUES ('1396', '2014-09-26', '1');
INSERT INTO `_web_workday` VALUES ('1397', '2014-09-27', '0');
INSERT INTO `_web_workday` VALUES ('1398', '2014-09-28', '1');
INSERT INTO `_web_workday` VALUES ('1399', '2014-09-29', '1');
INSERT INTO `_web_workday` VALUES ('1400', '2014-09-30', '1');
INSERT INTO `_web_workday` VALUES ('1401', '2014-10-01', '0');
INSERT INTO `_web_workday` VALUES ('1402', '2014-10-02', '0');
INSERT INTO `_web_workday` VALUES ('1403', '2014-10-03', '0');
INSERT INTO `_web_workday` VALUES ('1404', '2014-10-04', '0');
INSERT INTO `_web_workday` VALUES ('1405', '2014-10-05', '0');
INSERT INTO `_web_workday` VALUES ('1406', '2014-10-06', '0');
INSERT INTO `_web_workday` VALUES ('1407', '2014-10-07', '0');
INSERT INTO `_web_workday` VALUES ('1408', '2014-10-08', '1');
INSERT INTO `_web_workday` VALUES ('1409', '2014-10-09', '1');
INSERT INTO `_web_workday` VALUES ('1410', '2014-10-10', '1');
INSERT INTO `_web_workday` VALUES ('1411', '2014-10-11', '1');
INSERT INTO `_web_workday` VALUES ('1412', '2014-10-12', '0');
INSERT INTO `_web_workday` VALUES ('1413', '2014-10-13', '1');
INSERT INTO `_web_workday` VALUES ('1414', '2014-10-14', '1');
INSERT INTO `_web_workday` VALUES ('1415', '2014-10-15', '1');
INSERT INTO `_web_workday` VALUES ('1416', '2014-10-16', '1');
INSERT INTO `_web_workday` VALUES ('1417', '2014-10-17', '1');
INSERT INTO `_web_workday` VALUES ('1418', '2014-10-18', '0');
INSERT INTO `_web_workday` VALUES ('1419', '2014-10-19', '0');
INSERT INTO `_web_workday` VALUES ('1420', '2014-10-20', '1');
INSERT INTO `_web_workday` VALUES ('1421', '2014-10-21', '1');
INSERT INTO `_web_workday` VALUES ('1422', '2014-10-22', '1');
INSERT INTO `_web_workday` VALUES ('1423', '2014-10-23', '1');
INSERT INTO `_web_workday` VALUES ('1424', '2014-10-24', '1');
INSERT INTO `_web_workday` VALUES ('1425', '2014-10-25', '0');
INSERT INTO `_web_workday` VALUES ('1426', '2014-10-26', '0');
INSERT INTO `_web_workday` VALUES ('1427', '2014-10-27', '1');
INSERT INTO `_web_workday` VALUES ('1428', '2014-10-28', '1');
INSERT INTO `_web_workday` VALUES ('1429', '2014-10-29', '1');
INSERT INTO `_web_workday` VALUES ('1430', '2014-10-30', '1');
INSERT INTO `_web_workday` VALUES ('1431', '2014-10-31', '1');
INSERT INTO `_web_workday` VALUES ('1432', '2014-11-01', '0');
INSERT INTO `_web_workday` VALUES ('1433', '2014-11-02', '0');
INSERT INTO `_web_workday` VALUES ('1434', '2014-11-03', '1');
INSERT INTO `_web_workday` VALUES ('1435', '2014-11-04', '1');
INSERT INTO `_web_workday` VALUES ('1436', '2014-11-05', '1');
INSERT INTO `_web_workday` VALUES ('1437', '2014-11-06', '1');
INSERT INTO `_web_workday` VALUES ('1438', '2014-11-07', '1');
INSERT INTO `_web_workday` VALUES ('1439', '2014-11-08', '0');
INSERT INTO `_web_workday` VALUES ('1440', '2014-11-09', '0');
INSERT INTO `_web_workday` VALUES ('1441', '2014-11-10', '1');
INSERT INTO `_web_workday` VALUES ('1442', '2014-11-11', '1');
INSERT INTO `_web_workday` VALUES ('1443', '2014-11-12', '1');
INSERT INTO `_web_workday` VALUES ('1444', '2014-11-13', '1');
INSERT INTO `_web_workday` VALUES ('1445', '2014-11-14', '1');
INSERT INTO `_web_workday` VALUES ('1446', '2014-11-15', '0');
INSERT INTO `_web_workday` VALUES ('1447', '2014-11-16', '0');
INSERT INTO `_web_workday` VALUES ('1448', '2014-11-17', '1');
INSERT INTO `_web_workday` VALUES ('1449', '2014-11-18', '1');
INSERT INTO `_web_workday` VALUES ('1450', '2014-11-19', '1');
INSERT INTO `_web_workday` VALUES ('1451', '2014-11-20', '1');
INSERT INTO `_web_workday` VALUES ('1452', '2014-11-21', '1');
INSERT INTO `_web_workday` VALUES ('1453', '2014-11-22', '0');
INSERT INTO `_web_workday` VALUES ('1454', '2014-11-23', '0');
INSERT INTO `_web_workday` VALUES ('1455', '2014-11-24', '1');
INSERT INTO `_web_workday` VALUES ('1456', '2014-11-25', '1');
INSERT INTO `_web_workday` VALUES ('1457', '2014-11-26', '1');
INSERT INTO `_web_workday` VALUES ('1458', '2014-11-27', '1');
INSERT INTO `_web_workday` VALUES ('1459', '2014-11-28', '1');
INSERT INTO `_web_workday` VALUES ('1460', '2014-11-29', '0');
INSERT INTO `_web_workday` VALUES ('1461', '2014-11-30', '0');
INSERT INTO `_web_workday` VALUES ('1462', '2014-12-01', '1');
INSERT INTO `_web_workday` VALUES ('1463', '2014-12-02', '1');
INSERT INTO `_web_workday` VALUES ('1464', '2014-12-03', '1');
INSERT INTO `_web_workday` VALUES ('1465', '2014-12-04', '1');
INSERT INTO `_web_workday` VALUES ('1466', '2014-12-05', '1');
INSERT INTO `_web_workday` VALUES ('1467', '2014-12-06', '0');
INSERT INTO `_web_workday` VALUES ('1468', '2014-12-07', '0');
INSERT INTO `_web_workday` VALUES ('1469', '2014-12-08', '1');
INSERT INTO `_web_workday` VALUES ('1470', '2014-12-09', '1');
INSERT INTO `_web_workday` VALUES ('1471', '2014-12-10', '1');
INSERT INTO `_web_workday` VALUES ('1472', '2014-12-11', '1');
INSERT INTO `_web_workday` VALUES ('1473', '2014-12-12', '1');
INSERT INTO `_web_workday` VALUES ('1474', '2014-12-13', '0');
INSERT INTO `_web_workday` VALUES ('1475', '2014-12-14', '0');
INSERT INTO `_web_workday` VALUES ('1476', '2014-12-15', '1');
INSERT INTO `_web_workday` VALUES ('1477', '2014-12-16', '1');
INSERT INTO `_web_workday` VALUES ('1478', '2014-12-17', '1');
INSERT INTO `_web_workday` VALUES ('1479', '2014-12-18', '1');
INSERT INTO `_web_workday` VALUES ('1480', '2014-12-19', '1');
INSERT INTO `_web_workday` VALUES ('1481', '2014-12-20', '0');
INSERT INTO `_web_workday` VALUES ('1482', '2014-12-21', '0');
INSERT INTO `_web_workday` VALUES ('1483', '2014-12-22', '1');
INSERT INTO `_web_workday` VALUES ('1484', '2014-12-23', '1');
INSERT INTO `_web_workday` VALUES ('1485', '2014-12-24', '1');
INSERT INTO `_web_workday` VALUES ('1486', '2014-12-25', '1');
INSERT INTO `_web_workday` VALUES ('1487', '2014-12-26', '1');
INSERT INTO `_web_workday` VALUES ('1488', '2014-12-27', '0');
INSERT INTO `_web_workday` VALUES ('1489', '2014-12-28', '0');
INSERT INTO `_web_workday` VALUES ('1490', '2014-12-29', '1');
INSERT INTO `_web_workday` VALUES ('1491', '2014-12-30', '1');
INSERT INTO `_web_workday` VALUES ('1492', '2014-12-31', '1');
INSERT INTO `_web_workday` VALUES ('1493', '2015-01-01', '0');
INSERT INTO `_web_workday` VALUES ('1494', '2015-01-02', '0');
INSERT INTO `_web_workday` VALUES ('1495', '2015-01-03', '0');
INSERT INTO `_web_workday` VALUES ('1496', '2015-01-04', '1');
INSERT INTO `_web_workday` VALUES ('1497', '2015-01-05', '1');
INSERT INTO `_web_workday` VALUES ('1498', '2015-01-06', '1');
INSERT INTO `_web_workday` VALUES ('1499', '2015-01-07', '1');
INSERT INTO `_web_workday` VALUES ('1500', '2015-01-08', '1');
INSERT INTO `_web_workday` VALUES ('1501', '2015-01-09', '1');
INSERT INTO `_web_workday` VALUES ('1502', '2015-01-10', '0');
INSERT INTO `_web_workday` VALUES ('1503', '2015-01-11', '0');
INSERT INTO `_web_workday` VALUES ('1504', '2015-01-12', '1');
INSERT INTO `_web_workday` VALUES ('1505', '2015-01-13', '1');
INSERT INTO `_web_workday` VALUES ('1506', '2015-01-14', '1');
INSERT INTO `_web_workday` VALUES ('1507', '2015-01-15', '1');
INSERT INTO `_web_workday` VALUES ('1508', '2015-01-16', '1');
INSERT INTO `_web_workday` VALUES ('1509', '2015-01-17', '0');
INSERT INTO `_web_workday` VALUES ('1510', '2015-01-18', '0');
INSERT INTO `_web_workday` VALUES ('1511', '2015-01-19', '1');
INSERT INTO `_web_workday` VALUES ('1512', '2015-01-20', '1');
INSERT INTO `_web_workday` VALUES ('1513', '2015-01-21', '1');
INSERT INTO `_web_workday` VALUES ('1514', '2015-01-22', '1');
INSERT INTO `_web_workday` VALUES ('1515', '2015-01-23', '1');
INSERT INTO `_web_workday` VALUES ('1516', '2015-01-24', '0');
INSERT INTO `_web_workday` VALUES ('1517', '2015-01-25', '0');
INSERT INTO `_web_workday` VALUES ('1518', '2015-01-26', '1');
INSERT INTO `_web_workday` VALUES ('1519', '2015-01-27', '1');
INSERT INTO `_web_workday` VALUES ('1520', '2015-01-28', '1');
INSERT INTO `_web_workday` VALUES ('1521', '2015-01-29', '1');
INSERT INTO `_web_workday` VALUES ('1522', '2015-01-30', '1');
INSERT INTO `_web_workday` VALUES ('1523', '2015-01-31', '0');
INSERT INTO `_web_workday` VALUES ('1524', '2015-02-01', '0');
INSERT INTO `_web_workday` VALUES ('1525', '2015-02-02', '1');
INSERT INTO `_web_workday` VALUES ('1526', '2015-02-03', '1');
INSERT INTO `_web_workday` VALUES ('1527', '2015-02-04', '1');
INSERT INTO `_web_workday` VALUES ('1528', '2015-02-05', '1');
INSERT INTO `_web_workday` VALUES ('1529', '2015-02-06', '1');
INSERT INTO `_web_workday` VALUES ('1530', '2015-02-07', '0');
INSERT INTO `_web_workday` VALUES ('1531', '2015-02-08', '0');
INSERT INTO `_web_workday` VALUES ('1532', '2015-02-09', '1');
INSERT INTO `_web_workday` VALUES ('1533', '2015-02-10', '1');
INSERT INTO `_web_workday` VALUES ('1534', '2015-02-11', '1');
INSERT INTO `_web_workday` VALUES ('1535', '2015-02-12', '1');
INSERT INTO `_web_workday` VALUES ('1536', '2015-02-13', '1');
INSERT INTO `_web_workday` VALUES ('1537', '2015-02-14', '0');
INSERT INTO `_web_workday` VALUES ('1538', '2015-02-15', '1');
INSERT INTO `_web_workday` VALUES ('1539', '2015-02-16', '1');
INSERT INTO `_web_workday` VALUES ('1540', '2015-02-17', '1');
INSERT INTO `_web_workday` VALUES ('1541', '2015-02-18', '0');
INSERT INTO `_web_workday` VALUES ('1542', '2015-02-19', '0');
INSERT INTO `_web_workday` VALUES ('1543', '2015-02-20', '0');
INSERT INTO `_web_workday` VALUES ('1544', '2015-02-21', '0');
INSERT INTO `_web_workday` VALUES ('1545', '2015-02-22', '0');
INSERT INTO `_web_workday` VALUES ('1546', '2015-02-23', '0');
INSERT INTO `_web_workday` VALUES ('1547', '2015-02-24', '0');
INSERT INTO `_web_workday` VALUES ('1548', '2015-02-25', '1');
INSERT INTO `_web_workday` VALUES ('1549', '2015-02-26', '1');
INSERT INTO `_web_workday` VALUES ('1550', '2015-02-27', '1');
INSERT INTO `_web_workday` VALUES ('1551', '2015-02-28', '1');
INSERT INTO `_web_workday` VALUES ('1552', '2015-03-01', '0');
INSERT INTO `_web_workday` VALUES ('1553', '2015-03-02', '1');
INSERT INTO `_web_workday` VALUES ('1554', '2015-03-03', '1');
INSERT INTO `_web_workday` VALUES ('1555', '2015-03-04', '1');
INSERT INTO `_web_workday` VALUES ('1556', '2015-03-05', '1');
INSERT INTO `_web_workday` VALUES ('1557', '2015-03-06', '1');
INSERT INTO `_web_workday` VALUES ('1558', '2015-03-07', '0');
INSERT INTO `_web_workday` VALUES ('1559', '2015-03-08', '0');
INSERT INTO `_web_workday` VALUES ('1560', '2015-03-09', '1');
INSERT INTO `_web_workday` VALUES ('1561', '2015-03-10', '1');
INSERT INTO `_web_workday` VALUES ('1562', '2015-03-11', '1');
INSERT INTO `_web_workday` VALUES ('1563', '2015-03-12', '1');
INSERT INTO `_web_workday` VALUES ('1564', '2015-03-13', '1');
INSERT INTO `_web_workday` VALUES ('1565', '2015-03-14', '0');
INSERT INTO `_web_workday` VALUES ('1566', '2015-03-15', '0');
INSERT INTO `_web_workday` VALUES ('1567', '2015-03-16', '1');
INSERT INTO `_web_workday` VALUES ('1568', '2015-03-17', '1');
INSERT INTO `_web_workday` VALUES ('1569', '2015-03-18', '1');
INSERT INTO `_web_workday` VALUES ('1570', '2015-03-19', '1');
INSERT INTO `_web_workday` VALUES ('1571', '2015-03-20', '1');
INSERT INTO `_web_workday` VALUES ('1572', '2015-03-21', '0');
INSERT INTO `_web_workday` VALUES ('1573', '2015-03-22', '0');
INSERT INTO `_web_workday` VALUES ('1574', '2015-03-23', '1');
INSERT INTO `_web_workday` VALUES ('1575', '2015-03-24', '1');
INSERT INTO `_web_workday` VALUES ('1576', '2015-03-25', '1');
INSERT INTO `_web_workday` VALUES ('1577', '2015-03-26', '1');
INSERT INTO `_web_workday` VALUES ('1578', '2015-03-27', '1');
INSERT INTO `_web_workday` VALUES ('1579', '2015-03-28', '0');
INSERT INTO `_web_workday` VALUES ('1580', '2015-03-29', '0');
INSERT INTO `_web_workday` VALUES ('1581', '2015-03-30', '1');
INSERT INTO `_web_workday` VALUES ('1582', '2015-03-31', '1');
INSERT INTO `_web_workday` VALUES ('1583', '2015-04-01', '1');
INSERT INTO `_web_workday` VALUES ('1584', '2015-04-02', '1');
INSERT INTO `_web_workday` VALUES ('1585', '2015-04-03', '1');
INSERT INTO `_web_workday` VALUES ('1586', '2015-04-04', '0');
INSERT INTO `_web_workday` VALUES ('1587', '2015-04-05', '0');
INSERT INTO `_web_workday` VALUES ('1588', '2015-04-06', '0');
INSERT INTO `_web_workday` VALUES ('1589', '2015-04-07', '1');
INSERT INTO `_web_workday` VALUES ('1590', '2015-04-08', '1');
INSERT INTO `_web_workday` VALUES ('1591', '2015-04-09', '1');
INSERT INTO `_web_workday` VALUES ('1592', '2015-04-10', '1');
INSERT INTO `_web_workday` VALUES ('1593', '2015-04-11', '0');
INSERT INTO `_web_workday` VALUES ('1594', '2015-04-12', '0');
INSERT INTO `_web_workday` VALUES ('1595', '2015-04-13', '1');
INSERT INTO `_web_workday` VALUES ('1596', '2015-04-14', '1');
INSERT INTO `_web_workday` VALUES ('1597', '2015-04-15', '1');
INSERT INTO `_web_workday` VALUES ('1598', '2015-04-16', '1');
INSERT INTO `_web_workday` VALUES ('1599', '2015-04-17', '1');
INSERT INTO `_web_workday` VALUES ('1600', '2015-04-18', '0');
INSERT INTO `_web_workday` VALUES ('1601', '2015-04-19', '0');
INSERT INTO `_web_workday` VALUES ('1602', '2015-04-20', '1');
INSERT INTO `_web_workday` VALUES ('1603', '2015-04-21', '1');
INSERT INTO `_web_workday` VALUES ('1604', '2015-04-22', '1');
INSERT INTO `_web_workday` VALUES ('1605', '2015-04-23', '1');
INSERT INTO `_web_workday` VALUES ('1606', '2015-04-24', '0');
INSERT INTO `_web_workday` VALUES ('1607', '2015-04-25', '0');
INSERT INTO `_web_workday` VALUES ('1608', '2015-04-26', '1');
INSERT INTO `_web_workday` VALUES ('1609', '2015-04-27', '1');
INSERT INTO `_web_workday` VALUES ('1610', '2015-04-28', '1');
INSERT INTO `_web_workday` VALUES ('1611', '2015-04-29', '1');
INSERT INTO `_web_workday` VALUES ('1612', '2015-04-30', '1');
INSERT INTO `_web_workday` VALUES ('1613', '2015-05-01', '0');
INSERT INTO `_web_workday` VALUES ('1614', '2015-05-02', '0');
INSERT INTO `_web_workday` VALUES ('1615', '2015-05-03', '0');
INSERT INTO `_web_workday` VALUES ('1616', '2015-05-04', '1');
INSERT INTO `_web_workday` VALUES ('1617', '2015-05-05', '1');
INSERT INTO `_web_workday` VALUES ('1618', '2015-05-06', '1');
INSERT INTO `_web_workday` VALUES ('1619', '2015-05-07', '1');
INSERT INTO `_web_workday` VALUES ('1620', '2015-05-08', '1');
INSERT INTO `_web_workday` VALUES ('1621', '2015-05-09', '0');
INSERT INTO `_web_workday` VALUES ('1622', '2015-05-10', '0');
INSERT INTO `_web_workday` VALUES ('1623', '2015-05-11', '1');
INSERT INTO `_web_workday` VALUES ('1624', '2015-05-12', '1');
INSERT INTO `_web_workday` VALUES ('1625', '2015-05-13', '1');
INSERT INTO `_web_workday` VALUES ('1626', '2015-05-14', '1');
INSERT INTO `_web_workday` VALUES ('1627', '2015-05-15', '1');
INSERT INTO `_web_workday` VALUES ('1628', '2015-05-16', '0');
INSERT INTO `_web_workday` VALUES ('1629', '2015-05-17', '0');
INSERT INTO `_web_workday` VALUES ('1630', '2015-05-18', '1');
INSERT INTO `_web_workday` VALUES ('1631', '2015-05-19', '1');
INSERT INTO `_web_workday` VALUES ('1632', '2015-05-20', '1');
INSERT INTO `_web_workday` VALUES ('1633', '2015-05-21', '1');
INSERT INTO `_web_workday` VALUES ('1634', '2015-05-22', '1');
INSERT INTO `_web_workday` VALUES ('1635', '2015-05-23', '0');
INSERT INTO `_web_workday` VALUES ('1636', '2015-05-24', '0');
INSERT INTO `_web_workday` VALUES ('1637', '2015-05-25', '1');
INSERT INTO `_web_workday` VALUES ('1638', '2015-05-26', '1');
INSERT INTO `_web_workday` VALUES ('1639', '2015-05-27', '1');
INSERT INTO `_web_workday` VALUES ('1640', '2015-05-28', '1');
INSERT INTO `_web_workday` VALUES ('1641', '2015-05-29', '1');
INSERT INTO `_web_workday` VALUES ('1642', '2015-05-30', '0');
INSERT INTO `_web_workday` VALUES ('1643', '2015-05-31', '0');
INSERT INTO `_web_workday` VALUES ('1644', '2015-06-01', '1');
INSERT INTO `_web_workday` VALUES ('1645', '2015-06-02', '1');
INSERT INTO `_web_workday` VALUES ('1646', '2015-06-03', '1');
INSERT INTO `_web_workday` VALUES ('1647', '2015-06-04', '1');
INSERT INTO `_web_workday` VALUES ('1648', '2015-06-05', '1');
INSERT INTO `_web_workday` VALUES ('1649', '2015-06-06', '0');
INSERT INTO `_web_workday` VALUES ('1650', '2015-06-07', '0');
INSERT INTO `_web_workday` VALUES ('1651', '2015-06-08', '1');
INSERT INTO `_web_workday` VALUES ('1652', '2015-06-09', '1');
INSERT INTO `_web_workday` VALUES ('1653', '2015-06-10', '1');
INSERT INTO `_web_workday` VALUES ('1654', '2015-06-11', '1');
INSERT INTO `_web_workday` VALUES ('1655', '2015-06-12', '1');
INSERT INTO `_web_workday` VALUES ('1656', '2015-06-13', '0');
INSERT INTO `_web_workday` VALUES ('1657', '2015-06-14', '0');
INSERT INTO `_web_workday` VALUES ('1658', '2015-06-15', '1');
INSERT INTO `_web_workday` VALUES ('1659', '2015-06-16', '1');
INSERT INTO `_web_workday` VALUES ('1660', '2015-06-17', '1');
INSERT INTO `_web_workday` VALUES ('1661', '2015-06-18', '1');
INSERT INTO `_web_workday` VALUES ('1662', '2015-06-19', '1');
INSERT INTO `_web_workday` VALUES ('1663', '2015-06-20', '0');
INSERT INTO `_web_workday` VALUES ('1664', '2015-06-21', '0');
INSERT INTO `_web_workday` VALUES ('1665', '2015-06-22', '0');
INSERT INTO `_web_workday` VALUES ('1666', '2015-06-23', '1');
INSERT INTO `_web_workday` VALUES ('1667', '2015-06-24', '1');
INSERT INTO `_web_workday` VALUES ('1668', '2015-06-25', '1');
INSERT INTO `_web_workday` VALUES ('1669', '2015-06-26', '1');
INSERT INTO `_web_workday` VALUES ('1670', '2015-06-27', '0');
INSERT INTO `_web_workday` VALUES ('1671', '2015-06-28', '0');
INSERT INTO `_web_workday` VALUES ('1672', '2015-06-29', '1');
INSERT INTO `_web_workday` VALUES ('1673', '2015-06-30', '1');
INSERT INTO `_web_workday` VALUES ('1674', '2015-07-01', '1');
INSERT INTO `_web_workday` VALUES ('1675', '2015-07-02', '1');
INSERT INTO `_web_workday` VALUES ('1676', '2015-07-03', '1');
INSERT INTO `_web_workday` VALUES ('1677', '2015-07-04', '0');
INSERT INTO `_web_workday` VALUES ('1678', '2015-07-05', '0');
INSERT INTO `_web_workday` VALUES ('1679', '2015-07-06', '1');
INSERT INTO `_web_workday` VALUES ('1680', '2015-07-07', '1');
INSERT INTO `_web_workday` VALUES ('1681', '2015-07-08', '1');
INSERT INTO `_web_workday` VALUES ('1682', '2015-07-09', '1');
INSERT INTO `_web_workday` VALUES ('1683', '2015-07-10', '1');
INSERT INTO `_web_workday` VALUES ('1684', '2015-07-11', '0');
INSERT INTO `_web_workday` VALUES ('1685', '2015-07-12', '0');
INSERT INTO `_web_workday` VALUES ('1686', '2015-07-13', '1');
INSERT INTO `_web_workday` VALUES ('1687', '2015-07-14', '1');
INSERT INTO `_web_workday` VALUES ('1688', '2015-07-15', '1');
INSERT INTO `_web_workday` VALUES ('1689', '2015-07-16', '1');
INSERT INTO `_web_workday` VALUES ('1690', '2015-07-17', '1');
INSERT INTO `_web_workday` VALUES ('1691', '2015-07-18', '0');
INSERT INTO `_web_workday` VALUES ('1692', '2015-07-19', '0');
INSERT INTO `_web_workday` VALUES ('1693', '2015-07-20', '1');
INSERT INTO `_web_workday` VALUES ('1694', '2015-07-21', '1');
INSERT INTO `_web_workday` VALUES ('1695', '2015-07-22', '1');
INSERT INTO `_web_workday` VALUES ('1696', '2015-07-23', '1');
INSERT INTO `_web_workday` VALUES ('1697', '2015-07-24', '1');
INSERT INTO `_web_workday` VALUES ('1698', '2015-07-25', '0');
INSERT INTO `_web_workday` VALUES ('1699', '2015-07-26', '0');
INSERT INTO `_web_workday` VALUES ('1700', '2015-07-27', '1');
INSERT INTO `_web_workday` VALUES ('1701', '2015-07-28', '1');
INSERT INTO `_web_workday` VALUES ('1702', '2015-07-29', '1');
INSERT INTO `_web_workday` VALUES ('1703', '2015-07-30', '1');
INSERT INTO `_web_workday` VALUES ('1704', '2015-07-31', '1');
INSERT INTO `_web_workday` VALUES ('1705', '2015-08-01', '0');
INSERT INTO `_web_workday` VALUES ('1706', '2015-08-02', '0');
INSERT INTO `_web_workday` VALUES ('1707', '2015-08-03', '1');
INSERT INTO `_web_workday` VALUES ('1708', '2015-08-04', '1');
INSERT INTO `_web_workday` VALUES ('1709', '2015-08-05', '1');
INSERT INTO `_web_workday` VALUES ('1710', '2015-08-06', '1');
INSERT INTO `_web_workday` VALUES ('1711', '2015-08-07', '1');
INSERT INTO `_web_workday` VALUES ('1712', '2015-08-08', '0');
INSERT INTO `_web_workday` VALUES ('1713', '2015-08-09', '0');
INSERT INTO `_web_workday` VALUES ('1714', '2015-08-10', '0');
INSERT INTO `_web_workday` VALUES ('1715', '2015-08-11', '1');
INSERT INTO `_web_workday` VALUES ('1716', '2015-08-12', '1');
INSERT INTO `_web_workday` VALUES ('1717', '2015-08-13', '1');
INSERT INTO `_web_workday` VALUES ('1718', '2015-08-14', '1');
INSERT INTO `_web_workday` VALUES ('1719', '2015-08-15', '1');
INSERT INTO `_web_workday` VALUES ('1720', '2015-08-16', '0');
INSERT INTO `_web_workday` VALUES ('1721', '2015-08-17', '1');
INSERT INTO `_web_workday` VALUES ('1722', '2015-08-18', '1');
INSERT INTO `_web_workday` VALUES ('1723', '2015-08-19', '0');
INSERT INTO `_web_workday` VALUES ('1724', '2015-08-20', '1');
INSERT INTO `_web_workday` VALUES ('1725', '2015-08-21', '1');
INSERT INTO `_web_workday` VALUES ('1726', '2015-08-22', '0');
INSERT INTO `_web_workday` VALUES ('1727', '2015-08-23', '0');
INSERT INTO `_web_workday` VALUES ('1728', '2015-08-24', '1');
INSERT INTO `_web_workday` VALUES ('1729', '2015-08-25', '1');
INSERT INTO `_web_workday` VALUES ('1730', '2015-08-26', '1');
INSERT INTO `_web_workday` VALUES ('1731', '2015-08-27', '1');
INSERT INTO `_web_workday` VALUES ('1732', '2015-08-28', '1');
INSERT INTO `_web_workday` VALUES ('1733', '2015-08-29', '1');
INSERT INTO `_web_workday` VALUES ('1734', '2015-08-30', '0');
INSERT INTO `_web_workday` VALUES ('1735', '2015-08-31', '1');
INSERT INTO `_web_workday` VALUES ('1736', '2015-09-01', '1');
INSERT INTO `_web_workday` VALUES ('1737', '2015-09-02', '1');
INSERT INTO `_web_workday` VALUES ('1738', '2015-09-03', '0');
INSERT INTO `_web_workday` VALUES ('1739', '2015-09-04', '0');
INSERT INTO `_web_workday` VALUES ('1740', '2015-09-05', '0');
INSERT INTO `_web_workday` VALUES ('1741', '2015-09-06', '1');
INSERT INTO `_web_workday` VALUES ('1742', '2015-09-07', '1');
INSERT INTO `_web_workday` VALUES ('1743', '2015-09-08', '1');
INSERT INTO `_web_workday` VALUES ('1744', '2015-09-09', '1');
INSERT INTO `_web_workday` VALUES ('1745', '2015-09-10', '1');
INSERT INTO `_web_workday` VALUES ('1746', '2015-09-11', '1');
INSERT INTO `_web_workday` VALUES ('1747', '2015-09-12', '0');
INSERT INTO `_web_workday` VALUES ('1748', '2015-09-13', '0');
INSERT INTO `_web_workday` VALUES ('1749', '2015-09-14', '1');
INSERT INTO `_web_workday` VALUES ('1750', '2015-09-15', '1');
INSERT INTO `_web_workday` VALUES ('1751', '2015-09-16', '1');
INSERT INTO `_web_workday` VALUES ('1752', '2015-09-17', '1');
INSERT INTO `_web_workday` VALUES ('1753', '2015-09-18', '1');
INSERT INTO `_web_workday` VALUES ('1754', '2015-09-19', '1');
INSERT INTO `_web_workday` VALUES ('1755', '2015-09-20', '0');
INSERT INTO `_web_workday` VALUES ('1756', '2015-09-21', '1');
INSERT INTO `_web_workday` VALUES ('1757', '2015-09-22', '1');
INSERT INTO `_web_workday` VALUES ('1758', '2015-09-23', '1');
INSERT INTO `_web_workday` VALUES ('1759', '2015-09-24', '1');
INSERT INTO `_web_workday` VALUES ('1760', '2015-09-25', '1');
INSERT INTO `_web_workday` VALUES ('1761', '2015-09-26', '0');
INSERT INTO `_web_workday` VALUES ('1762', '2015-09-27', '0');
INSERT INTO `_web_workday` VALUES ('1763', '2015-09-28', '1');
INSERT INTO `_web_workday` VALUES ('1764', '2015-09-29', '0');
INSERT INTO `_web_workday` VALUES ('1765', '2015-09-30', '1');
INSERT INTO `_web_workday` VALUES ('1766', '2015-10-01', '0');
INSERT INTO `_web_workday` VALUES ('1767', '2015-10-02', '0');
INSERT INTO `_web_workday` VALUES ('1768', '2015-10-03', '0');
INSERT INTO `_web_workday` VALUES ('1769', '2015-10-04', '0');
INSERT INTO `_web_workday` VALUES ('1770', '2015-10-05', '0');
INSERT INTO `_web_workday` VALUES ('1771', '2015-10-06', '0');
INSERT INTO `_web_workday` VALUES ('1772', '2015-10-07', '0');
INSERT INTO `_web_workday` VALUES ('1773', '2015-10-08', '1');
INSERT INTO `_web_workday` VALUES ('1774', '2015-10-09', '1');
INSERT INTO `_web_workday` VALUES ('1775', '2015-10-10', '1');
INSERT INTO `_web_workday` VALUES ('1776', '2015-10-11', '0');
INSERT INTO `_web_workday` VALUES ('1777', '2015-10-12', '1');
INSERT INTO `_web_workday` VALUES ('1778', '2015-10-13', '1');
INSERT INTO `_web_workday` VALUES ('1779', '2015-10-14', '1');
INSERT INTO `_web_workday` VALUES ('1780', '2015-10-15', '1');
INSERT INTO `_web_workday` VALUES ('1781', '2015-10-16', '1');
INSERT INTO `_web_workday` VALUES ('1782', '2015-10-17', '1');
INSERT INTO `_web_workday` VALUES ('1783', '2015-10-18', '0');
INSERT INTO `_web_workday` VALUES ('1784', '2015-10-19', '1');
INSERT INTO `_web_workday` VALUES ('1785', '2015-10-20', '1');
INSERT INTO `_web_workday` VALUES ('1786', '2015-10-21', '1');
INSERT INTO `_web_workday` VALUES ('1787', '2015-10-22', '1');
INSERT INTO `_web_workday` VALUES ('1788', '2015-10-23', '1');
INSERT INTO `_web_workday` VALUES ('1789', '2015-10-24', '0');
INSERT INTO `_web_workday` VALUES ('1790', '2015-10-25', '0');
INSERT INTO `_web_workday` VALUES ('1791', '2015-10-26', '1');
INSERT INTO `_web_workday` VALUES ('1792', '2015-10-27', '1');
INSERT INTO `_web_workday` VALUES ('1793', '2015-10-28', '1');
INSERT INTO `_web_workday` VALUES ('1794', '2015-10-29', '1');
INSERT INTO `_web_workday` VALUES ('1795', '2015-10-30', '1');
INSERT INTO `_web_workday` VALUES ('1796', '2015-10-31', '0');
INSERT INTO `_web_workday` VALUES ('1797', '2015-11-01', '0');
INSERT INTO `_web_workday` VALUES ('1798', '2015-11-02', '1');
INSERT INTO `_web_workday` VALUES ('1799', '2015-11-03', '1');
INSERT INTO `_web_workday` VALUES ('1800', '2015-11-04', '1');
INSERT INTO `_web_workday` VALUES ('1801', '2015-11-05', '1');
INSERT INTO `_web_workday` VALUES ('1802', '2015-11-06', '1');
INSERT INTO `_web_workday` VALUES ('1803', '2015-11-07', '0');
INSERT INTO `_web_workday` VALUES ('1804', '2015-11-08', '0');
INSERT INTO `_web_workday` VALUES ('1805', '2015-11-09', '1');
INSERT INTO `_web_workday` VALUES ('1806', '2015-11-10', '1');
INSERT INTO `_web_workday` VALUES ('1807', '2015-11-11', '1');
INSERT INTO `_web_workday` VALUES ('1808', '2015-11-12', '1');
INSERT INTO `_web_workday` VALUES ('1809', '2015-11-13', '1');
INSERT INTO `_web_workday` VALUES ('1810', '2015-11-14', '0');
INSERT INTO `_web_workday` VALUES ('1811', '2015-11-15', '0');
INSERT INTO `_web_workday` VALUES ('1812', '2015-11-16', '1');
INSERT INTO `_web_workday` VALUES ('1813', '2015-11-17', '1');
INSERT INTO `_web_workday` VALUES ('1814', '2015-11-18', '1');
INSERT INTO `_web_workday` VALUES ('1815', '2015-11-19', '1');
INSERT INTO `_web_workday` VALUES ('1816', '2015-11-20', '1');
INSERT INTO `_web_workday` VALUES ('1817', '2015-11-21', '0');
INSERT INTO `_web_workday` VALUES ('1818', '2015-11-22', '0');
INSERT INTO `_web_workday` VALUES ('1819', '2015-11-23', '1');
INSERT INTO `_web_workday` VALUES ('1820', '2015-11-24', '1');
INSERT INTO `_web_workday` VALUES ('1821', '2015-11-25', '1');
INSERT INTO `_web_workday` VALUES ('1822', '2015-11-26', '1');
INSERT INTO `_web_workday` VALUES ('1823', '2015-11-27', '1');
INSERT INTO `_web_workday` VALUES ('1824', '2015-11-28', '0');
INSERT INTO `_web_workday` VALUES ('1825', '2015-11-29', '0');
INSERT INTO `_web_workday` VALUES ('1826', '2015-11-30', '1');
INSERT INTO `_web_workday` VALUES ('1827', '2015-12-01', '1');
INSERT INTO `_web_workday` VALUES ('1828', '2015-12-02', '1');
INSERT INTO `_web_workday` VALUES ('1829', '2015-12-03', '1');
INSERT INTO `_web_workday` VALUES ('1830', '2015-12-04', '1');
INSERT INTO `_web_workday` VALUES ('1831', '2015-12-05', '0');
INSERT INTO `_web_workday` VALUES ('1832', '2015-12-06', '0');
INSERT INTO `_web_workday` VALUES ('1833', '2015-12-07', '1');
INSERT INTO `_web_workday` VALUES ('1834', '2015-12-08', '1');
INSERT INTO `_web_workday` VALUES ('1835', '2015-12-09', '1');
INSERT INTO `_web_workday` VALUES ('1836', '2015-12-10', '1');
INSERT INTO `_web_workday` VALUES ('1837', '2015-12-11', '1');
INSERT INTO `_web_workday` VALUES ('1838', '2015-12-12', '0');
INSERT INTO `_web_workday` VALUES ('1839', '2015-12-13', '0');
INSERT INTO `_web_workday` VALUES ('1840', '2015-12-14', '1');
INSERT INTO `_web_workday` VALUES ('1841', '2015-12-15', '1');
INSERT INTO `_web_workday` VALUES ('1842', '2015-12-16', '1');
INSERT INTO `_web_workday` VALUES ('1843', '2015-12-17', '1');
INSERT INTO `_web_workday` VALUES ('1844', '2015-12-18', '1');
INSERT INTO `_web_workday` VALUES ('1845', '2015-12-19', '0');
INSERT INTO `_web_workday` VALUES ('1846', '2015-12-20', '0');
INSERT INTO `_web_workday` VALUES ('1847', '2015-12-21', '1');
INSERT INTO `_web_workday` VALUES ('1848', '2015-12-22', '1');
INSERT INTO `_web_workday` VALUES ('1849', '2015-12-23', '1');
INSERT INTO `_web_workday` VALUES ('1850', '2015-12-24', '1');
INSERT INTO `_web_workday` VALUES ('1851', '2015-12-25', '1');
INSERT INTO `_web_workday` VALUES ('1852', '2015-12-26', '0');
INSERT INTO `_web_workday` VALUES ('1853', '2015-12-27', '0');
INSERT INTO `_web_workday` VALUES ('1854', '2015-12-28', '1');
INSERT INTO `_web_workday` VALUES ('1855', '2015-12-29', '1');
INSERT INTO `_web_workday` VALUES ('1856', '2015-12-30', '1');
INSERT INTO `_web_workday` VALUES ('1857', '2015-12-31', '1');
INSERT INTO `_web_workday` VALUES ('1858', '2016-01-01', '0');
INSERT INTO `_web_workday` VALUES ('1859', '2016-01-02', '0');
INSERT INTO `_web_workday` VALUES ('1860', '2016-01-03', '0');
INSERT INTO `_web_workday` VALUES ('1861', '2016-01-04', '1');
INSERT INTO `_web_workday` VALUES ('1862', '2016-01-05', '1');
INSERT INTO `_web_workday` VALUES ('1863', '2016-01-06', '1');
INSERT INTO `_web_workday` VALUES ('1864', '2016-01-07', '1');
INSERT INTO `_web_workday` VALUES ('1865', '2016-01-08', '1');
INSERT INTO `_web_workday` VALUES ('1866', '2016-01-09', '0');
INSERT INTO `_web_workday` VALUES ('1867', '2016-01-10', '0');
INSERT INTO `_web_workday` VALUES ('1868', '2016-01-11', '1');
INSERT INTO `_web_workday` VALUES ('1869', '2016-01-12', '1');
INSERT INTO `_web_workday` VALUES ('1870', '2016-01-13', '1');
INSERT INTO `_web_workday` VALUES ('1871', '2016-01-14', '1');
INSERT INTO `_web_workday` VALUES ('1872', '2016-01-15', '1');
INSERT INTO `_web_workday` VALUES ('1873', '2016-01-16', '0');
INSERT INTO `_web_workday` VALUES ('1874', '2016-01-17', '0');
INSERT INTO `_web_workday` VALUES ('1875', '2016-01-18', '1');
INSERT INTO `_web_workday` VALUES ('1876', '2016-01-19', '1');
INSERT INTO `_web_workday` VALUES ('1877', '2016-01-20', '1');
INSERT INTO `_web_workday` VALUES ('1878', '2016-01-21', '1');
INSERT INTO `_web_workday` VALUES ('1879', '2016-01-22', '1');
INSERT INTO `_web_workday` VALUES ('1880', '2016-01-23', '0');
INSERT INTO `_web_workday` VALUES ('1881', '2016-01-24', '0');
INSERT INTO `_web_workday` VALUES ('1882', '2016-01-25', '1');
INSERT INTO `_web_workday` VALUES ('1883', '2016-01-26', '1');
INSERT INTO `_web_workday` VALUES ('1884', '2016-01-27', '1');
INSERT INTO `_web_workday` VALUES ('1885', '2016-01-28', '1');
INSERT INTO `_web_workday` VALUES ('1886', '2016-01-29', '1');
INSERT INTO `_web_workday` VALUES ('1887', '2016-01-30', '0');
INSERT INTO `_web_workday` VALUES ('1888', '2016-01-31', '0');
INSERT INTO `_web_workday` VALUES ('1889', '2016-02-01', '1');
INSERT INTO `_web_workday` VALUES ('1890', '2016-02-02', '1');
INSERT INTO `_web_workday` VALUES ('1891', '2016-02-03', '1');
INSERT INTO `_web_workday` VALUES ('1892', '2016-02-04', '1');
INSERT INTO `_web_workday` VALUES ('1893', '2016-02-05', '1');
INSERT INTO `_web_workday` VALUES ('1894', '2016-02-06', '1');
INSERT INTO `_web_workday` VALUES ('1895', '2016-02-07', '0');
INSERT INTO `_web_workday` VALUES ('1896', '2016-02-08', '0');
INSERT INTO `_web_workday` VALUES ('1897', '2016-02-09', '0');
INSERT INTO `_web_workday` VALUES ('1898', '2016-02-10', '0');
INSERT INTO `_web_workday` VALUES ('1899', '2016-02-11', '0');
INSERT INTO `_web_workday` VALUES ('1900', '2016-02-12', '0');
INSERT INTO `_web_workday` VALUES ('1901', '2016-02-13', '0');
INSERT INTO `_web_workday` VALUES ('1902', '2016-02-14', '1');
INSERT INTO `_web_workday` VALUES ('1903', '2016-02-15', '1');
INSERT INTO `_web_workday` VALUES ('1904', '2016-02-16', '1');
INSERT INTO `_web_workday` VALUES ('1905', '2016-02-17', '1');
INSERT INTO `_web_workday` VALUES ('1906', '2016-02-18', '1');
INSERT INTO `_web_workday` VALUES ('1907', '2016-02-19', '1');
INSERT INTO `_web_workday` VALUES ('1908', '2016-02-20', '0');
INSERT INTO `_web_workday` VALUES ('1909', '2016-02-21', '0');
INSERT INTO `_web_workday` VALUES ('1910', '2016-02-22', '1');
INSERT INTO `_web_workday` VALUES ('1911', '2016-02-23', '1');
INSERT INTO `_web_workday` VALUES ('1912', '2016-02-24', '1');
INSERT INTO `_web_workday` VALUES ('1913', '2016-02-25', '1');
INSERT INTO `_web_workday` VALUES ('1914', '2016-02-26', '1');
INSERT INTO `_web_workday` VALUES ('1915', '2016-02-27', '0');
INSERT INTO `_web_workday` VALUES ('1916', '2016-02-28', '0');
INSERT INTO `_web_workday` VALUES ('1917', '2016-02-29', '1');
INSERT INTO `_web_workday` VALUES ('1918', '2016-03-01', '1');
INSERT INTO `_web_workday` VALUES ('1919', '2016-03-02', '1');
INSERT INTO `_web_workday` VALUES ('1920', '2016-03-03', '1');
INSERT INTO `_web_workday` VALUES ('1921', '2016-03-04', '1');
INSERT INTO `_web_workday` VALUES ('1922', '2016-03-05', '0');
INSERT INTO `_web_workday` VALUES ('1923', '2016-03-06', '0');
INSERT INTO `_web_workday` VALUES ('1924', '2016-03-07', '1');
INSERT INTO `_web_workday` VALUES ('1925', '2016-03-08', '1');
INSERT INTO `_web_workday` VALUES ('1926', '2016-03-09', '1');
INSERT INTO `_web_workday` VALUES ('1927', '2016-03-10', '1');
INSERT INTO `_web_workday` VALUES ('1928', '2016-03-11', '1');
INSERT INTO `_web_workday` VALUES ('1929', '2016-03-12', '0');
INSERT INTO `_web_workday` VALUES ('1930', '2016-03-13', '0');
INSERT INTO `_web_workday` VALUES ('1931', '2016-03-14', '1');
INSERT INTO `_web_workday` VALUES ('1932', '2016-03-15', '1');
INSERT INTO `_web_workday` VALUES ('1933', '2016-03-16', '1');
INSERT INTO `_web_workday` VALUES ('1934', '2016-03-17', '1');
INSERT INTO `_web_workday` VALUES ('1935', '2016-03-18', '1');
INSERT INTO `_web_workday` VALUES ('1936', '2016-03-19', '0');
INSERT INTO `_web_workday` VALUES ('1937', '2016-03-20', '0');
INSERT INTO `_web_workday` VALUES ('1938', '2016-03-21', '1');
INSERT INTO `_web_workday` VALUES ('1939', '2016-03-22', '1');
INSERT INTO `_web_workday` VALUES ('1940', '2016-03-23', '1');
INSERT INTO `_web_workday` VALUES ('1941', '2016-03-24', '1');
INSERT INTO `_web_workday` VALUES ('1942', '2016-03-25', '1');
INSERT INTO `_web_workday` VALUES ('1943', '2016-03-26', '0');
INSERT INTO `_web_workday` VALUES ('1944', '2016-03-27', '0');
INSERT INTO `_web_workday` VALUES ('1945', '2016-03-28', '1');
INSERT INTO `_web_workday` VALUES ('1946', '2016-03-29', '1');
INSERT INTO `_web_workday` VALUES ('1947', '2016-03-30', '1');
INSERT INTO `_web_workday` VALUES ('1948', '2016-03-31', '1');
INSERT INTO `_web_workday` VALUES ('1949', '2016-04-01', '1');
INSERT INTO `_web_workday` VALUES ('1950', '2016-04-02', '0');
INSERT INTO `_web_workday` VALUES ('1951', '2016-04-03', '0');
INSERT INTO `_web_workday` VALUES ('1952', '2016-04-04', '0');
INSERT INTO `_web_workday` VALUES ('1953', '2016-04-05', '1');
INSERT INTO `_web_workday` VALUES ('1954', '2016-04-06', '1');
INSERT INTO `_web_workday` VALUES ('1955', '2016-04-07', '1');
INSERT INTO `_web_workday` VALUES ('1956', '2016-04-08', '1');
INSERT INTO `_web_workday` VALUES ('1957', '2016-04-09', '0');
INSERT INTO `_web_workday` VALUES ('1958', '2016-04-10', '0');
INSERT INTO `_web_workday` VALUES ('1959', '2016-04-11', '1');
INSERT INTO `_web_workday` VALUES ('1960', '2016-04-12', '1');
INSERT INTO `_web_workday` VALUES ('1961', '2016-04-13', '1');
INSERT INTO `_web_workday` VALUES ('1962', '2016-04-14', '1');
INSERT INTO `_web_workday` VALUES ('1963', '2016-04-15', '1');
INSERT INTO `_web_workday` VALUES ('1964', '2016-04-16', '0');
INSERT INTO `_web_workday` VALUES ('1965', '2016-04-17', '0');
INSERT INTO `_web_workday` VALUES ('1966', '2016-04-18', '1');
INSERT INTO `_web_workday` VALUES ('1967', '2016-04-19', '1');
INSERT INTO `_web_workday` VALUES ('1968', '2016-04-20', '1');
INSERT INTO `_web_workday` VALUES ('1969', '2016-04-21', '1');
INSERT INTO `_web_workday` VALUES ('1970', '2016-04-22', '1');
INSERT INTO `_web_workday` VALUES ('1971', '2016-04-23', '0');
INSERT INTO `_web_workday` VALUES ('1972', '2016-04-24', '0');
INSERT INTO `_web_workday` VALUES ('1973', '2016-04-25', '1');
INSERT INTO `_web_workday` VALUES ('1974', '2016-04-26', '1');
INSERT INTO `_web_workday` VALUES ('1975', '2016-04-27', '1');
INSERT INTO `_web_workday` VALUES ('1976', '2016-04-28', '1');
INSERT INTO `_web_workday` VALUES ('1977', '2016-04-29', '1');
INSERT INTO `_web_workday` VALUES ('1978', '2016-04-30', '0');
INSERT INTO `_web_workday` VALUES ('1979', '2016-05-01', '0');
INSERT INTO `_web_workday` VALUES ('1980', '2016-05-02', '0');
INSERT INTO `_web_workday` VALUES ('1981', '2016-05-03', '1');
INSERT INTO `_web_workday` VALUES ('1982', '2016-05-04', '1');
INSERT INTO `_web_workday` VALUES ('1983', '2016-05-05', '1');
INSERT INTO `_web_workday` VALUES ('1984', '2016-05-06', '1');
INSERT INTO `_web_workday` VALUES ('1985', '2016-05-07', '0');
INSERT INTO `_web_workday` VALUES ('1986', '2016-05-08', '0');
INSERT INTO `_web_workday` VALUES ('1987', '2016-05-09', '1');
INSERT INTO `_web_workday` VALUES ('1988', '2016-05-10', '1');
INSERT INTO `_web_workday` VALUES ('1989', '2016-05-11', '1');
INSERT INTO `_web_workday` VALUES ('1990', '2016-05-12', '1');
INSERT INTO `_web_workday` VALUES ('1991', '2016-05-13', '1');
INSERT INTO `_web_workday` VALUES ('1992', '2016-05-14', '0');
INSERT INTO `_web_workday` VALUES ('1993', '2016-05-15', '0');
INSERT INTO `_web_workday` VALUES ('1994', '2016-05-16', '1');
INSERT INTO `_web_workday` VALUES ('1995', '2016-05-17', '1');
INSERT INTO `_web_workday` VALUES ('1996', '2016-05-18', '1');
INSERT INTO `_web_workday` VALUES ('1997', '2016-05-19', '1');
INSERT INTO `_web_workday` VALUES ('1998', '2016-05-20', '1');
INSERT INTO `_web_workday` VALUES ('1999', '2016-05-21', '0');
INSERT INTO `_web_workday` VALUES ('2000', '2016-05-22', '0');
INSERT INTO `_web_workday` VALUES ('2001', '2016-05-23', '1');
INSERT INTO `_web_workday` VALUES ('2002', '2016-05-24', '1');
INSERT INTO `_web_workday` VALUES ('2003', '2016-05-25', '1');
INSERT INTO `_web_workday` VALUES ('2004', '2016-05-26', '1');
INSERT INTO `_web_workday` VALUES ('2005', '2016-05-27', '1');
INSERT INTO `_web_workday` VALUES ('2006', '2016-05-28', '0');
INSERT INTO `_web_workday` VALUES ('2007', '2016-05-29', '0');
INSERT INTO `_web_workday` VALUES ('2008', '2016-05-30', '1');
INSERT INTO `_web_workday` VALUES ('2009', '2016-05-31', '1');
INSERT INTO `_web_workday` VALUES ('2010', '2016-06-01', '1');
INSERT INTO `_web_workday` VALUES ('2011', '2016-06-02', '1');
INSERT INTO `_web_workday` VALUES ('2012', '2016-06-03', '1');
INSERT INTO `_web_workday` VALUES ('2013', '2016-06-04', '0');
INSERT INTO `_web_workday` VALUES ('2014', '2016-06-05', '0');
INSERT INTO `_web_workday` VALUES ('2015', '2016-06-06', '1');
INSERT INTO `_web_workday` VALUES ('2016', '2016-06-07', '1');
INSERT INTO `_web_workday` VALUES ('2017', '2016-06-08', '1');
INSERT INTO `_web_workday` VALUES ('2018', '2016-06-09', '0');
INSERT INTO `_web_workday` VALUES ('2019', '2016-06-10', '0');
INSERT INTO `_web_workday` VALUES ('2020', '2016-06-11', '0');
INSERT INTO `_web_workday` VALUES ('2021', '2016-06-12', '1');
INSERT INTO `_web_workday` VALUES ('2022', '2016-06-13', '1');
INSERT INTO `_web_workday` VALUES ('2023', '2016-06-14', '1');
INSERT INTO `_web_workday` VALUES ('2024', '2016-06-15', '1');
INSERT INTO `_web_workday` VALUES ('2025', '2016-06-16', '1');
INSERT INTO `_web_workday` VALUES ('2026', '2016-06-17', '1');
INSERT INTO `_web_workday` VALUES ('2027', '2016-06-18', '0');
INSERT INTO `_web_workday` VALUES ('2028', '2016-06-19', '0');
INSERT INTO `_web_workday` VALUES ('2029', '2016-06-20', '1');
INSERT INTO `_web_workday` VALUES ('2030', '2016-06-21', '1');
INSERT INTO `_web_workday` VALUES ('2031', '2016-06-22', '1');
INSERT INTO `_web_workday` VALUES ('2032', '2016-06-23', '1');
INSERT INTO `_web_workday` VALUES ('2033', '2016-06-24', '1');
INSERT INTO `_web_workday` VALUES ('2034', '2016-06-25', '0');
INSERT INTO `_web_workday` VALUES ('2035', '2016-06-26', '0');
INSERT INTO `_web_workday` VALUES ('2036', '2016-06-27', '1');
INSERT INTO `_web_workday` VALUES ('2037', '2016-06-28', '1');
INSERT INTO `_web_workday` VALUES ('2038', '2016-06-29', '1');
INSERT INTO `_web_workday` VALUES ('2039', '2016-06-30', '1');
INSERT INTO `_web_workday` VALUES ('2040', '2016-07-01', '1');
INSERT INTO `_web_workday` VALUES ('2041', '2016-07-02', '0');
INSERT INTO `_web_workday` VALUES ('2042', '2016-07-03', '0');
INSERT INTO `_web_workday` VALUES ('2043', '2016-07-04', '1');
INSERT INTO `_web_workday` VALUES ('2044', '2016-07-05', '1');
INSERT INTO `_web_workday` VALUES ('2045', '2016-07-06', '1');
INSERT INTO `_web_workday` VALUES ('2046', '2016-07-07', '1');
INSERT INTO `_web_workday` VALUES ('2047', '2016-07-08', '1');
INSERT INTO `_web_workday` VALUES ('2048', '2016-07-09', '0');
INSERT INTO `_web_workday` VALUES ('2049', '2016-07-10', '0');
INSERT INTO `_web_workday` VALUES ('2050', '2016-07-11', '1');
INSERT INTO `_web_workday` VALUES ('2051', '2016-07-12', '1');
INSERT INTO `_web_workday` VALUES ('2052', '2016-07-13', '1');
INSERT INTO `_web_workday` VALUES ('2053', '2016-07-14', '1');
INSERT INTO `_web_workday` VALUES ('2054', '2016-07-15', '1');
INSERT INTO `_web_workday` VALUES ('2055', '2016-07-16', '0');
INSERT INTO `_web_workday` VALUES ('2056', '2016-07-17', '0');
INSERT INTO `_web_workday` VALUES ('2057', '2016-07-18', '1');
INSERT INTO `_web_workday` VALUES ('2058', '2016-07-19', '1');
INSERT INTO `_web_workday` VALUES ('2059', '2016-07-20', '1');
INSERT INTO `_web_workday` VALUES ('2060', '2016-07-21', '1');
INSERT INTO `_web_workday` VALUES ('2061', '2016-07-22', '1');
INSERT INTO `_web_workday` VALUES ('2062', '2016-07-23', '0');
INSERT INTO `_web_workday` VALUES ('2063', '2016-07-24', '0');
INSERT INTO `_web_workday` VALUES ('2064', '2016-07-25', '1');
INSERT INTO `_web_workday` VALUES ('2065', '2016-07-26', '1');
INSERT INTO `_web_workday` VALUES ('2066', '2016-07-27', '1');
INSERT INTO `_web_workday` VALUES ('2067', '2016-07-28', '1');
INSERT INTO `_web_workday` VALUES ('2068', '2016-07-29', '1');
INSERT INTO `_web_workday` VALUES ('2069', '2016-07-30', '0');
INSERT INTO `_web_workday` VALUES ('2070', '2016-07-31', '0');
INSERT INTO `_web_workday` VALUES ('2071', '2016-08-01', '1');
INSERT INTO `_web_workday` VALUES ('2072', '2016-08-02', '1');
INSERT INTO `_web_workday` VALUES ('2073', '2016-08-03', '1');
INSERT INTO `_web_workday` VALUES ('2074', '2016-08-04', '1');
INSERT INTO `_web_workday` VALUES ('2075', '2016-08-05', '1');
INSERT INTO `_web_workday` VALUES ('2076', '2016-08-06', '0');
INSERT INTO `_web_workday` VALUES ('2077', '2016-08-07', '0');
INSERT INTO `_web_workday` VALUES ('2078', '2016-08-08', '1');
INSERT INTO `_web_workday` VALUES ('2079', '2016-08-09', '1');
INSERT INTO `_web_workday` VALUES ('2080', '2016-08-10', '1');
INSERT INTO `_web_workday` VALUES ('2081', '2016-08-11', '1');
INSERT INTO `_web_workday` VALUES ('2082', '2016-08-12', '1');
INSERT INTO `_web_workday` VALUES ('2083', '2016-08-13', '0');
INSERT INTO `_web_workday` VALUES ('2084', '2016-08-14', '0');
INSERT INTO `_web_workday` VALUES ('2085', '2016-08-15', '1');
INSERT INTO `_web_workday` VALUES ('2086', '2016-08-16', '1');
INSERT INTO `_web_workday` VALUES ('2087', '2016-08-17', '1');
INSERT INTO `_web_workday` VALUES ('2088', '2016-08-18', '1');
INSERT INTO `_web_workday` VALUES ('2089', '2016-08-19', '1');
INSERT INTO `_web_workday` VALUES ('2090', '2016-08-20', '0');
INSERT INTO `_web_workday` VALUES ('2091', '2016-08-21', '0');
INSERT INTO `_web_workday` VALUES ('2092', '2016-08-22', '0');
INSERT INTO `_web_workday` VALUES ('2093', '2016-08-23', '1');
INSERT INTO `_web_workday` VALUES ('2094', '2016-08-24', '1');
INSERT INTO `_web_workday` VALUES ('2095', '2016-08-25', '1');
INSERT INTO `_web_workday` VALUES ('2096', '2016-08-26', '1');
INSERT INTO `_web_workday` VALUES ('2097', '2016-08-27', '1');
INSERT INTO `_web_workday` VALUES ('2098', '2016-08-28', '0');
INSERT INTO `_web_workday` VALUES ('2099', '2016-08-29', '1');
INSERT INTO `_web_workday` VALUES ('2100', '2016-08-30', '1');
INSERT INTO `_web_workday` VALUES ('2101', '2016-08-31', '1');
INSERT INTO `_web_workday` VALUES ('2102', '2016-09-01', '1');
INSERT INTO `_web_workday` VALUES ('2103', '2016-09-02', '1');
INSERT INTO `_web_workday` VALUES ('2104', '2016-09-03', '0');
INSERT INTO `_web_workday` VALUES ('2105', '2016-09-04', '0');
INSERT INTO `_web_workday` VALUES ('2106', '2016-09-05', '1');
INSERT INTO `_web_workday` VALUES ('2107', '2016-09-06', '1');
INSERT INTO `_web_workday` VALUES ('2108', '2016-09-07', '1');
INSERT INTO `_web_workday` VALUES ('2109', '2016-09-08', '1');
INSERT INTO `_web_workday` VALUES ('2110', '2016-09-09', '1');
INSERT INTO `_web_workday` VALUES ('2111', '2016-09-10', '0');
INSERT INTO `_web_workday` VALUES ('2112', '2016-09-11', '0');
INSERT INTO `_web_workday` VALUES ('2113', '2016-09-12', '1');
INSERT INTO `_web_workday` VALUES ('2114', '2016-09-13', '1');
INSERT INTO `_web_workday` VALUES ('2115', '2016-09-14', '1');
INSERT INTO `_web_workday` VALUES ('2116', '2016-09-15', '0');
INSERT INTO `_web_workday` VALUES ('2117', '2016-09-16', '0');
INSERT INTO `_web_workday` VALUES ('2118', '2016-09-17', '0');
INSERT INTO `_web_workday` VALUES ('2119', '2016-09-18', '1');
INSERT INTO `_web_workday` VALUES ('2120', '2016-09-19', '1');
INSERT INTO `_web_workday` VALUES ('2121', '2016-09-20', '1');
INSERT INTO `_web_workday` VALUES ('2122', '2016-09-21', '1');
INSERT INTO `_web_workday` VALUES ('2123', '2016-09-22', '1');
INSERT INTO `_web_workday` VALUES ('2124', '2016-09-23', '1');
INSERT INTO `_web_workday` VALUES ('2125', '2016-09-24', '0');
INSERT INTO `_web_workday` VALUES ('2126', '2016-09-25', '0');
INSERT INTO `_web_workday` VALUES ('2127', '2016-09-26', '1');
INSERT INTO `_web_workday` VALUES ('2128', '2016-09-27', '1');
INSERT INTO `_web_workday` VALUES ('2129', '2016-09-28', '1');
INSERT INTO `_web_workday` VALUES ('2130', '2016-09-29', '1');
INSERT INTO `_web_workday` VALUES ('2131', '2016-09-30', '1');
INSERT INTO `_web_workday` VALUES ('2132', '2016-10-01', '0');
INSERT INTO `_web_workday` VALUES ('2133', '2016-10-02', '0');
INSERT INTO `_web_workday` VALUES ('2134', '2016-10-03', '0');
INSERT INTO `_web_workday` VALUES ('2135', '2016-10-04', '0');
INSERT INTO `_web_workday` VALUES ('2136', '2016-10-05', '0');
INSERT INTO `_web_workday` VALUES ('2137', '2016-10-06', '0');
INSERT INTO `_web_workday` VALUES ('2138', '2016-10-07', '0');
INSERT INTO `_web_workday` VALUES ('2139', '2016-10-08', '1');
INSERT INTO `_web_workday` VALUES ('2140', '2016-10-09', '1');
INSERT INTO `_web_workday` VALUES ('2141', '2016-10-10', '1');
INSERT INTO `_web_workday` VALUES ('2142', '2016-10-11', '1');
INSERT INTO `_web_workday` VALUES ('2143', '2016-10-12', '1');
INSERT INTO `_web_workday` VALUES ('2144', '2016-10-13', '1');
INSERT INTO `_web_workday` VALUES ('2145', '2016-10-14', '1');
INSERT INTO `_web_workday` VALUES ('2146', '2016-10-15', '0');
INSERT INTO `_web_workday` VALUES ('2147', '2016-10-16', '0');
INSERT INTO `_web_workday` VALUES ('2148', '2016-10-17', '1');
INSERT INTO `_web_workday` VALUES ('2149', '2016-10-18', '1');
INSERT INTO `_web_workday` VALUES ('2150', '2016-10-19', '1');
INSERT INTO `_web_workday` VALUES ('2151', '2016-10-20', '1');
INSERT INTO `_web_workday` VALUES ('2152', '2016-10-21', '1');
INSERT INTO `_web_workday` VALUES ('2153', '2016-10-22', '0');
INSERT INTO `_web_workday` VALUES ('2154', '2016-10-23', '0');
INSERT INTO `_web_workday` VALUES ('2155', '2016-10-24', '1');
INSERT INTO `_web_workday` VALUES ('2156', '2016-10-25', '1');
INSERT INTO `_web_workday` VALUES ('2157', '2016-10-26', '1');
INSERT INTO `_web_workday` VALUES ('2158', '2016-10-27', '1');
INSERT INTO `_web_workday` VALUES ('2159', '2016-10-28', '1');
INSERT INTO `_web_workday` VALUES ('2160', '2016-10-29', '0');
INSERT INTO `_web_workday` VALUES ('2161', '2016-10-30', '0');
INSERT INTO `_web_workday` VALUES ('2162', '2016-10-31', '1');
INSERT INTO `_web_workday` VALUES ('2163', '2016-11-01', '1');
INSERT INTO `_web_workday` VALUES ('2164', '2016-11-02', '1');
INSERT INTO `_web_workday` VALUES ('2165', '2016-11-03', '1');
INSERT INTO `_web_workday` VALUES ('2166', '2016-11-04', '1');
INSERT INTO `_web_workday` VALUES ('2167', '2016-11-05', '0');
INSERT INTO `_web_workday` VALUES ('2168', '2016-11-06', '0');
INSERT INTO `_web_workday` VALUES ('2169', '2016-11-07', '1');
INSERT INTO `_web_workday` VALUES ('2170', '2016-11-08', '1');
INSERT INTO `_web_workday` VALUES ('2171', '2016-11-09', '1');
INSERT INTO `_web_workday` VALUES ('2172', '2016-11-10', '1');
INSERT INTO `_web_workday` VALUES ('2173', '2016-11-11', '1');
INSERT INTO `_web_workday` VALUES ('2174', '2016-11-12', '0');
INSERT INTO `_web_workday` VALUES ('2175', '2016-11-13', '0');
INSERT INTO `_web_workday` VALUES ('2176', '2016-11-14', '1');
INSERT INTO `_web_workday` VALUES ('2177', '2016-11-15', '1');
INSERT INTO `_web_workday` VALUES ('2178', '2016-11-16', '1');
INSERT INTO `_web_workday` VALUES ('2179', '2016-11-17', '1');
INSERT INTO `_web_workday` VALUES ('2180', '2016-11-18', '1');
INSERT INTO `_web_workday` VALUES ('2181', '2016-11-19', '0');
INSERT INTO `_web_workday` VALUES ('2182', '2016-11-20', '0');
INSERT INTO `_web_workday` VALUES ('2183', '2016-11-21', '1');
INSERT INTO `_web_workday` VALUES ('2184', '2016-11-22', '1');
INSERT INTO `_web_workday` VALUES ('2185', '2016-11-23', '1');
INSERT INTO `_web_workday` VALUES ('2186', '2016-11-24', '1');
INSERT INTO `_web_workday` VALUES ('2187', '2016-11-25', '1');
INSERT INTO `_web_workday` VALUES ('2188', '2016-11-26', '0');
INSERT INTO `_web_workday` VALUES ('2189', '2016-11-27', '0');
INSERT INTO `_web_workday` VALUES ('2190', '2016-11-28', '1');
INSERT INTO `_web_workday` VALUES ('2191', '2016-11-29', '1');
INSERT INTO `_web_workday` VALUES ('2192', '2016-11-30', '1');
INSERT INTO `_web_workday` VALUES ('2193', '2016-12-01', '1');
INSERT INTO `_web_workday` VALUES ('2194', '2016-12-02', '1');
INSERT INTO `_web_workday` VALUES ('2195', '2016-12-03', '0');
INSERT INTO `_web_workday` VALUES ('2196', '2016-12-04', '0');
INSERT INTO `_web_workday` VALUES ('2197', '2016-12-05', '1');
INSERT INTO `_web_workday` VALUES ('2198', '2016-12-06', '1');
INSERT INTO `_web_workday` VALUES ('2199', '2016-12-07', '1');
INSERT INTO `_web_workday` VALUES ('2200', '2016-12-08', '1');
INSERT INTO `_web_workday` VALUES ('2201', '2016-12-09', '1');
INSERT INTO `_web_workday` VALUES ('2202', '2016-12-10', '0');
INSERT INTO `_web_workday` VALUES ('2203', '2016-12-11', '0');
INSERT INTO `_web_workday` VALUES ('2204', '2016-12-12', '1');
INSERT INTO `_web_workday` VALUES ('2205', '2016-12-13', '1');
INSERT INTO `_web_workday` VALUES ('2206', '2016-12-14', '1');
INSERT INTO `_web_workday` VALUES ('2207', '2016-12-15', '1');
INSERT INTO `_web_workday` VALUES ('2208', '2016-12-16', '1');
INSERT INTO `_web_workday` VALUES ('2209', '2016-12-17', '0');
INSERT INTO `_web_workday` VALUES ('2210', '2016-12-18', '0');
INSERT INTO `_web_workday` VALUES ('2211', '2016-12-19', '1');
INSERT INTO `_web_workday` VALUES ('2212', '2016-12-20', '1');
INSERT INTO `_web_workday` VALUES ('2213', '2016-12-21', '1');
INSERT INTO `_web_workday` VALUES ('2214', '2016-12-22', '1');
INSERT INTO `_web_workday` VALUES ('2215', '2016-12-23', '1');
INSERT INTO `_web_workday` VALUES ('2216', '2016-12-24', '0');
INSERT INTO `_web_workday` VALUES ('2217', '2016-12-25', '0');
INSERT INTO `_web_workday` VALUES ('2218', '2016-12-26', '1');
INSERT INTO `_web_workday` VALUES ('2219', '2016-12-27', '1');
INSERT INTO `_web_workday` VALUES ('2220', '2016-12-28', '1');
INSERT INTO `_web_workday` VALUES ('2221', '2016-12-29', '1');
INSERT INTO `_web_workday` VALUES ('2222', '2016-12-30', '1');
INSERT INTO `_web_workday` VALUES ('2223', '2016-12-31', '0');
INSERT INTO `_web_workday` VALUES ('2224', '2017-01-01', '0');
INSERT INTO `_web_workday` VALUES ('2225', '2017-01-02', '0');
INSERT INTO `_web_workday` VALUES ('2226', '2017-01-03', '1');
INSERT INTO `_web_workday` VALUES ('2227', '2017-01-04', '1');
INSERT INTO `_web_workday` VALUES ('2228', '2017-01-05', '1');
INSERT INTO `_web_workday` VALUES ('2229', '2017-01-06', '1');
INSERT INTO `_web_workday` VALUES ('2230', '2017-01-07', '0');
INSERT INTO `_web_workday` VALUES ('2231', '2017-01-08', '0');
INSERT INTO `_web_workday` VALUES ('2232', '2017-01-09', '1');
INSERT INTO `_web_workday` VALUES ('2233', '2017-01-10', '1');
INSERT INTO `_web_workday` VALUES ('2234', '2017-01-11', '1');
INSERT INTO `_web_workday` VALUES ('2235', '2017-01-12', '1');
INSERT INTO `_web_workday` VALUES ('2236', '2017-01-13', '1');
INSERT INTO `_web_workday` VALUES ('2237', '2017-01-14', '0');
INSERT INTO `_web_workday` VALUES ('2238', '2017-01-15', '0');
INSERT INTO `_web_workday` VALUES ('2239', '2017-01-16', '1');
INSERT INTO `_web_workday` VALUES ('2240', '2017-01-17', '1');
INSERT INTO `_web_workday` VALUES ('2241', '2017-01-18', '1');
INSERT INTO `_web_workday` VALUES ('2242', '2017-01-19', '1');
INSERT INTO `_web_workday` VALUES ('2243', '2017-01-20', '1');
INSERT INTO `_web_workday` VALUES ('2244', '2017-01-21', '1');
INSERT INTO `_web_workday` VALUES ('2245', '2017-01-22', '0');
INSERT INTO `_web_workday` VALUES ('2246', '2017-01-23', '1');
INSERT INTO `_web_workday` VALUES ('2247', '2017-01-24', '1');
INSERT INTO `_web_workday` VALUES ('2248', '2017-01-25', '1');
INSERT INTO `_web_workday` VALUES ('2249', '2017-01-26', '1');
INSERT INTO `_web_workday` VALUES ('2250', '2017-01-27', '0');
INSERT INTO `_web_workday` VALUES ('2251', '2017-01-28', '0');
INSERT INTO `_web_workday` VALUES ('2252', '2017-01-29', '0');
INSERT INTO `_web_workday` VALUES ('2253', '2017-01-30', '0');
INSERT INTO `_web_workday` VALUES ('2254', '2017-01-31', '0');
INSERT INTO `_web_workday` VALUES ('2255', '2017-02-01', '0');
INSERT INTO `_web_workday` VALUES ('2256', '2017-02-02', '0');
INSERT INTO `_web_workday` VALUES ('2257', '2017-02-03', '1');
INSERT INTO `_web_workday` VALUES ('2258', '2017-02-04', '1');
INSERT INTO `_web_workday` VALUES ('2259', '2017-02-05', '0');
INSERT INTO `_web_workday` VALUES ('2260', '2017-02-06', '1');
INSERT INTO `_web_workday` VALUES ('2261', '2017-02-07', '1');
INSERT INTO `_web_workday` VALUES ('2262', '2017-02-08', '1');
INSERT INTO `_web_workday` VALUES ('2263', '2017-02-09', '1');
INSERT INTO `_web_workday` VALUES ('2264', '2017-02-10', '1');
INSERT INTO `_web_workday` VALUES ('2265', '2017-02-11', '0');
INSERT INTO `_web_workday` VALUES ('2266', '2017-02-12', '0');
INSERT INTO `_web_workday` VALUES ('2267', '2017-02-13', '1');
INSERT INTO `_web_workday` VALUES ('2268', '2017-02-14', '1');
INSERT INTO `_web_workday` VALUES ('2269', '2017-02-15', '1');
INSERT INTO `_web_workday` VALUES ('2270', '2017-02-16', '1');
INSERT INTO `_web_workday` VALUES ('2271', '2017-02-17', '1');
INSERT INTO `_web_workday` VALUES ('2272', '2017-02-18', '0');
INSERT INTO `_web_workday` VALUES ('2273', '2017-02-19', '0');
INSERT INTO `_web_workday` VALUES ('2274', '2017-02-20', '1');
INSERT INTO `_web_workday` VALUES ('2275', '2017-02-21', '1');
INSERT INTO `_web_workday` VALUES ('2276', '2017-02-22', '1');
INSERT INTO `_web_workday` VALUES ('2277', '2017-02-23', '1');
INSERT INTO `_web_workday` VALUES ('2278', '2017-02-24', '1');
INSERT INTO `_web_workday` VALUES ('2279', '2017-02-25', '0');
INSERT INTO `_web_workday` VALUES ('2280', '2017-02-26', '0');
INSERT INTO `_web_workday` VALUES ('2281', '2017-02-27', '1');
INSERT INTO `_web_workday` VALUES ('2282', '2017-02-28', '1');
INSERT INTO `_web_workday` VALUES ('2283', '2017-03-01', '1');
INSERT INTO `_web_workday` VALUES ('2284', '2017-03-02', '1');
INSERT INTO `_web_workday` VALUES ('2285', '2017-03-03', '1');
INSERT INTO `_web_workday` VALUES ('2286', '2017-03-04', '0');
INSERT INTO `_web_workday` VALUES ('2287', '2017-03-05', '0');
INSERT INTO `_web_workday` VALUES ('2288', '2017-03-06', '1');
INSERT INTO `_web_workday` VALUES ('2289', '2017-03-07', '1');
INSERT INTO `_web_workday` VALUES ('2290', '2017-03-08', '1');
INSERT INTO `_web_workday` VALUES ('2291', '2017-03-09', '1');
INSERT INTO `_web_workday` VALUES ('2292', '2017-03-10', '1');
INSERT INTO `_web_workday` VALUES ('2293', '2017-03-11', '0');
INSERT INTO `_web_workday` VALUES ('2294', '2017-03-12', '0');
INSERT INTO `_web_workday` VALUES ('2295', '2017-03-13', '1');
INSERT INTO `_web_workday` VALUES ('2296', '2017-03-14', '1');
INSERT INTO `_web_workday` VALUES ('2297', '2017-03-15', '1');
INSERT INTO `_web_workday` VALUES ('2298', '2017-03-16', '1');
INSERT INTO `_web_workday` VALUES ('2299', '2017-03-17', '1');
INSERT INTO `_web_workday` VALUES ('2300', '2017-03-18', '0');
INSERT INTO `_web_workday` VALUES ('2301', '2017-03-19', '0');
INSERT INTO `_web_workday` VALUES ('2302', '2017-03-20', '1');
INSERT INTO `_web_workday` VALUES ('2303', '2017-03-21', '1');
INSERT INTO `_web_workday` VALUES ('2304', '2017-03-22', '1');
INSERT INTO `_web_workday` VALUES ('2305', '2017-03-23', '1');
INSERT INTO `_web_workday` VALUES ('2306', '2017-03-24', '1');
INSERT INTO `_web_workday` VALUES ('2307', '2017-03-25', '0');
INSERT INTO `_web_workday` VALUES ('2308', '2017-03-26', '0');
INSERT INTO `_web_workday` VALUES ('2309', '2017-03-27', '1');
INSERT INTO `_web_workday` VALUES ('2310', '2017-03-28', '1');
INSERT INTO `_web_workday` VALUES ('2311', '2017-03-29', '1');
INSERT INTO `_web_workday` VALUES ('2312', '2017-03-30', '1');
INSERT INTO `_web_workday` VALUES ('2313', '2017-03-31', '1');
INSERT INTO `_web_workday` VALUES ('2314', '2017-04-01', '1');
INSERT INTO `_web_workday` VALUES ('2315', '2017-04-02', '0');
INSERT INTO `_web_workday` VALUES ('2316', '2017-04-03', '0');
INSERT INTO `_web_workday` VALUES ('2317', '2017-04-04', '0');
INSERT INTO `_web_workday` VALUES ('2318', '2017-04-05', '1');
INSERT INTO `_web_workday` VALUES ('2319', '2017-04-06', '1');
INSERT INTO `_web_workday` VALUES ('2320', '2017-04-07', '1');
INSERT INTO `_web_workday` VALUES ('2321', '2017-04-08', '0');
INSERT INTO `_web_workday` VALUES ('2322', '2017-04-09', '0');
INSERT INTO `_web_workday` VALUES ('2323', '2017-04-10', '1');
INSERT INTO `_web_workday` VALUES ('2324', '2017-04-11', '1');
INSERT INTO `_web_workday` VALUES ('2325', '2017-04-12', '1');
INSERT INTO `_web_workday` VALUES ('2326', '2017-04-13', '1');
INSERT INTO `_web_workday` VALUES ('2327', '2017-04-14', '1');
INSERT INTO `_web_workday` VALUES ('2328', '2017-04-15', '0');
INSERT INTO `_web_workday` VALUES ('2329', '2017-04-16', '0');
INSERT INTO `_web_workday` VALUES ('2330', '2017-04-17', '1');
INSERT INTO `_web_workday` VALUES ('2331', '2017-04-18', '1');
INSERT INTO `_web_workday` VALUES ('2332', '2017-04-19', '1');
INSERT INTO `_web_workday` VALUES ('2333', '2017-04-20', '1');
INSERT INTO `_web_workday` VALUES ('2334', '2017-04-21', '1');
INSERT INTO `_web_workday` VALUES ('2335', '2017-04-22', '0');
INSERT INTO `_web_workday` VALUES ('2336', '2017-04-23', '0');
INSERT INTO `_web_workday` VALUES ('2337', '2017-04-24', '1');
INSERT INTO `_web_workday` VALUES ('2338', '2017-04-25', '1');
INSERT INTO `_web_workday` VALUES ('2339', '2017-04-26', '1');
INSERT INTO `_web_workday` VALUES ('2340', '2017-04-27', '1');
INSERT INTO `_web_workday` VALUES ('2341', '2017-04-28', '1');
INSERT INTO `_web_workday` VALUES ('2342', '2017-04-29', '0');
INSERT INTO `_web_workday` VALUES ('2343', '2017-04-30', '0');
INSERT INTO `_web_workday` VALUES ('2344', '2017-05-01', '0');
INSERT INTO `_web_workday` VALUES ('2345', '2017-05-02', '1');
INSERT INTO `_web_workday` VALUES ('2346', '2017-05-03', '1');
INSERT INTO `_web_workday` VALUES ('2347', '2017-05-04', '1');
INSERT INTO `_web_workday` VALUES ('2348', '2017-05-05', '1');
INSERT INTO `_web_workday` VALUES ('2349', '2017-05-06', '0');
INSERT INTO `_web_workday` VALUES ('2350', '2017-05-07', '0');
INSERT INTO `_web_workday` VALUES ('2351', '2017-05-08', '1');
INSERT INTO `_web_workday` VALUES ('2352', '2017-05-09', '1');
INSERT INTO `_web_workday` VALUES ('2353', '2017-05-10', '1');
INSERT INTO `_web_workday` VALUES ('2354', '2017-05-11', '1');
INSERT INTO `_web_workday` VALUES ('2355', '2017-05-12', '1');
INSERT INTO `_web_workday` VALUES ('2356', '2017-05-13', '0');
INSERT INTO `_web_workday` VALUES ('2357', '2017-05-14', '0');
INSERT INTO `_web_workday` VALUES ('2358', '2017-05-15', '1');
INSERT INTO `_web_workday` VALUES ('2359', '2017-05-16', '1');
INSERT INTO `_web_workday` VALUES ('2360', '2017-05-17', '1');
INSERT INTO `_web_workday` VALUES ('2361', '2017-05-18', '1');
INSERT INTO `_web_workday` VALUES ('2362', '2017-05-19', '1');
INSERT INTO `_web_workday` VALUES ('2363', '2017-05-20', '0');
INSERT INTO `_web_workday` VALUES ('2364', '2017-05-21', '0');
INSERT INTO `_web_workday` VALUES ('2365', '2017-05-22', '1');
INSERT INTO `_web_workday` VALUES ('2366', '2017-05-23', '1');
INSERT INTO `_web_workday` VALUES ('2367', '2017-05-24', '1');
INSERT INTO `_web_workday` VALUES ('2368', '2017-05-25', '1');
INSERT INTO `_web_workday` VALUES ('2369', '2017-05-26', '1');
INSERT INTO `_web_workday` VALUES ('2370', '2017-05-27', '1');
INSERT INTO `_web_workday` VALUES ('2371', '2017-05-28', '0');
INSERT INTO `_web_workday` VALUES ('2372', '2017-05-29', '0');
INSERT INTO `_web_workday` VALUES ('2373', '2017-05-30', '0');
INSERT INTO `_web_workday` VALUES ('2374', '2017-05-31', '1');
INSERT INTO `_web_workday` VALUES ('2375', '2017-06-01', '1');
INSERT INTO `_web_workday` VALUES ('2376', '2017-06-02', '1');
INSERT INTO `_web_workday` VALUES ('2377', '2017-06-03', '0');
INSERT INTO `_web_workday` VALUES ('2378', '2017-06-04', '0');
INSERT INTO `_web_workday` VALUES ('2379', '2017-06-05', '1');
INSERT INTO `_web_workday` VALUES ('2380', '2017-06-06', '1');
INSERT INTO `_web_workday` VALUES ('2381', '2017-06-07', '1');
INSERT INTO `_web_workday` VALUES ('2382', '2017-06-08', '1');
INSERT INTO `_web_workday` VALUES ('2383', '2017-06-09', '1');
INSERT INTO `_web_workday` VALUES ('2384', '2017-06-10', '0');
INSERT INTO `_web_workday` VALUES ('2385', '2017-06-11', '0');
INSERT INTO `_web_workday` VALUES ('2386', '2017-06-12', '1');
INSERT INTO `_web_workday` VALUES ('2387', '2017-06-13', '1');
INSERT INTO `_web_workday` VALUES ('2388', '2017-06-14', '1');
INSERT INTO `_web_workday` VALUES ('2389', '2017-06-15', '1');
INSERT INTO `_web_workday` VALUES ('2390', '2017-06-16', '1');
INSERT INTO `_web_workday` VALUES ('2391', '2017-06-17', '0');
INSERT INTO `_web_workday` VALUES ('2392', '2017-06-18', '0');
INSERT INTO `_web_workday` VALUES ('2393', '2017-06-19', '1');
INSERT INTO `_web_workday` VALUES ('2394', '2017-06-20', '1');
INSERT INTO `_web_workday` VALUES ('2395', '2017-06-21', '1');
INSERT INTO `_web_workday` VALUES ('2396', '2017-06-22', '1');
INSERT INTO `_web_workday` VALUES ('2397', '2017-06-23', '1');
INSERT INTO `_web_workday` VALUES ('2398', '2017-06-24', '0');
INSERT INTO `_web_workday` VALUES ('2399', '2017-06-25', '0');
INSERT INTO `_web_workday` VALUES ('2400', '2017-06-26', '1');
INSERT INTO `_web_workday` VALUES ('2401', '2017-06-27', '1');
INSERT INTO `_web_workday` VALUES ('2402', '2017-06-28', '1');
INSERT INTO `_web_workday` VALUES ('2403', '2017-06-29', '1');
INSERT INTO `_web_workday` VALUES ('2404', '2017-06-30', '1');
INSERT INTO `_web_workday` VALUES ('2405', '2017-07-01', '0');
INSERT INTO `_web_workday` VALUES ('2406', '2017-07-02', '0');
INSERT INTO `_web_workday` VALUES ('2407', '2017-07-03', '1');
INSERT INTO `_web_workday` VALUES ('2408', '2017-07-04', '1');
INSERT INTO `_web_workday` VALUES ('2409', '2017-07-05', '1');
INSERT INTO `_web_workday` VALUES ('2410', '2017-07-06', '1');
INSERT INTO `_web_workday` VALUES ('2411', '2017-07-07', '1');
INSERT INTO `_web_workday` VALUES ('2412', '2017-07-08', '0');
INSERT INTO `_web_workday` VALUES ('2413', '2017-07-09', '0');
INSERT INTO `_web_workday` VALUES ('2414', '2017-07-10', '1');
INSERT INTO `_web_workday` VALUES ('2415', '2017-07-11', '1');
INSERT INTO `_web_workday` VALUES ('2416', '2017-07-12', '1');
INSERT INTO `_web_workday` VALUES ('2417', '2017-07-13', '1');
INSERT INTO `_web_workday` VALUES ('2418', '2017-07-14', '1');
INSERT INTO `_web_workday` VALUES ('2419', '2017-07-15', '0');
INSERT INTO `_web_workday` VALUES ('2420', '2017-07-16', '0');
INSERT INTO `_web_workday` VALUES ('2421', '2017-07-17', '1');
INSERT INTO `_web_workday` VALUES ('2422', '2017-07-18', '1');
INSERT INTO `_web_workday` VALUES ('2423', '2017-07-19', '1');
INSERT INTO `_web_workday` VALUES ('2424', '2017-07-20', '1');
INSERT INTO `_web_workday` VALUES ('2425', '2017-07-21', '1');
INSERT INTO `_web_workday` VALUES ('2426', '2017-07-22', '0');
INSERT INTO `_web_workday` VALUES ('2427', '2017-07-23', '0');
INSERT INTO `_web_workday` VALUES ('2428', '2017-07-24', '1');
INSERT INTO `_web_workday` VALUES ('2429', '2017-07-25', '1');
INSERT INTO `_web_workday` VALUES ('2430', '2017-07-26', '1');
INSERT INTO `_web_workday` VALUES ('2431', '2017-07-27', '1');
INSERT INTO `_web_workday` VALUES ('2432', '2017-07-28', '1');
INSERT INTO `_web_workday` VALUES ('2433', '2017-07-29', '0');
INSERT INTO `_web_workday` VALUES ('2434', '2017-07-30', '0');
INSERT INTO `_web_workday` VALUES ('2435', '2017-07-31', '1');
INSERT INTO `_web_workday` VALUES ('2436', '2017-08-01', '1');
INSERT INTO `_web_workday` VALUES ('2437', '2017-08-02', '1');
INSERT INTO `_web_workday` VALUES ('2438', '2017-08-03', '1');
INSERT INTO `_web_workday` VALUES ('2439', '2017-08-04', '1');
INSERT INTO `_web_workday` VALUES ('2440', '2017-08-05', '0');
INSERT INTO `_web_workday` VALUES ('2441', '2017-08-06', '0');
INSERT INTO `_web_workday` VALUES ('2442', '2017-08-07', '1');
INSERT INTO `_web_workday` VALUES ('2443', '2017-08-08', '1');
INSERT INTO `_web_workday` VALUES ('2444', '2017-08-09', '1');
INSERT INTO `_web_workday` VALUES ('2445', '2017-08-10', '1');
INSERT INTO `_web_workday` VALUES ('2446', '2017-08-11', '1');
INSERT INTO `_web_workday` VALUES ('2447', '2017-08-12', '0');
INSERT INTO `_web_workday` VALUES ('2448', '2017-08-13', '0');
INSERT INTO `_web_workday` VALUES ('2449', '2017-08-14', '1');
INSERT INTO `_web_workday` VALUES ('2450', '2017-08-15', '1');
INSERT INTO `_web_workday` VALUES ('2451', '2017-08-16', '1');
INSERT INTO `_web_workday` VALUES ('2452', '2017-08-17', '1');
INSERT INTO `_web_workday` VALUES ('2453', '2017-08-18', '1');
INSERT INTO `_web_workday` VALUES ('2454', '2017-08-19', '0');
INSERT INTO `_web_workday` VALUES ('2455', '2017-08-20', '0');
INSERT INTO `_web_workday` VALUES ('2456', '2017-08-21', '1');
INSERT INTO `_web_workday` VALUES ('2457', '2017-08-22', '1');
INSERT INTO `_web_workday` VALUES ('2458', '2017-08-23', '1');
INSERT INTO `_web_workday` VALUES ('2459', '2017-08-24', '1');
INSERT INTO `_web_workday` VALUES ('2460', '2017-08-25', '1');
INSERT INTO `_web_workday` VALUES ('2461', '2017-08-26', '0');
INSERT INTO `_web_workday` VALUES ('2462', '2017-08-27', '0');
INSERT INTO `_web_workday` VALUES ('2463', '2017-08-28', '1');
INSERT INTO `_web_workday` VALUES ('2464', '2017-08-29', '1');
INSERT INTO `_web_workday` VALUES ('2465', '2017-08-30', '1');
INSERT INTO `_web_workday` VALUES ('2466', '2017-08-31', '1');
INSERT INTO `_web_workday` VALUES ('2467', '2017-09-01', '1');
INSERT INTO `_web_workday` VALUES ('2468', '2017-09-02', '0');
INSERT INTO `_web_workday` VALUES ('2469', '2017-09-03', '0');
INSERT INTO `_web_workday` VALUES ('2470', '2017-09-04', '1');
INSERT INTO `_web_workday` VALUES ('2471', '2017-09-05', '1');
INSERT INTO `_web_workday` VALUES ('2472', '2017-09-06', '1');
INSERT INTO `_web_workday` VALUES ('2473', '2017-09-07', '1');
INSERT INTO `_web_workday` VALUES ('2474', '2017-09-08', '1');
INSERT INTO `_web_workday` VALUES ('2475', '2017-09-09', '0');
INSERT INTO `_web_workday` VALUES ('2476', '2017-09-10', '0');
INSERT INTO `_web_workday` VALUES ('2477', '2017-09-11', '1');
INSERT INTO `_web_workday` VALUES ('2478', '2017-09-12', '1');
INSERT INTO `_web_workday` VALUES ('2479', '2017-09-13', '1');
INSERT INTO `_web_workday` VALUES ('2480', '2017-09-14', '1');
INSERT INTO `_web_workday` VALUES ('2481', '2017-09-15', '1');
INSERT INTO `_web_workday` VALUES ('2482', '2017-09-16', '0');
INSERT INTO `_web_workday` VALUES ('2483', '2017-09-17', '0');
INSERT INTO `_web_workday` VALUES ('2484', '2017-09-18', '1');
INSERT INTO `_web_workday` VALUES ('2485', '2017-09-19', '1');
INSERT INTO `_web_workday` VALUES ('2486', '2017-09-20', '1');
INSERT INTO `_web_workday` VALUES ('2487', '2017-09-21', '1');
INSERT INTO `_web_workday` VALUES ('2488', '2017-09-22', '1');
INSERT INTO `_web_workday` VALUES ('2489', '2017-09-23', '0');
INSERT INTO `_web_workday` VALUES ('2490', '2017-09-24', '0');
INSERT INTO `_web_workday` VALUES ('2491', '2017-09-25', '1');
INSERT INTO `_web_workday` VALUES ('2492', '2017-09-26', '1');
INSERT INTO `_web_workday` VALUES ('2493', '2017-09-27', '1');
INSERT INTO `_web_workday` VALUES ('2494', '2017-09-28', '1');
INSERT INTO `_web_workday` VALUES ('2495', '2017-09-29', '1');
INSERT INTO `_web_workday` VALUES ('2496', '2017-09-30', '1');
INSERT INTO `_web_workday` VALUES ('2497', '2017-10-01', '0');
INSERT INTO `_web_workday` VALUES ('2498', '2017-10-02', '0');
INSERT INTO `_web_workday` VALUES ('2499', '2017-10-03', '0');
INSERT INTO `_web_workday` VALUES ('2500', '2017-10-04', '0');
INSERT INTO `_web_workday` VALUES ('2501', '2017-10-05', '0');
INSERT INTO `_web_workday` VALUES ('2502', '2017-10-06', '0');
INSERT INTO `_web_workday` VALUES ('2503', '2017-10-07', '0');
INSERT INTO `_web_workday` VALUES ('2504', '2017-10-08', '0');
INSERT INTO `_web_workday` VALUES ('2505', '2017-10-09', '1');
INSERT INTO `_web_workday` VALUES ('2506', '2017-10-10', '1');
INSERT INTO `_web_workday` VALUES ('2507', '2017-10-11', '1');
INSERT INTO `_web_workday` VALUES ('2508', '2017-10-12', '1');
INSERT INTO `_web_workday` VALUES ('2509', '2017-10-13', '1');
INSERT INTO `_web_workday` VALUES ('2510', '2017-10-14', '0');
INSERT INTO `_web_workday` VALUES ('2511', '2017-10-15', '0');
INSERT INTO `_web_workday` VALUES ('2512', '2017-10-16', '1');
INSERT INTO `_web_workday` VALUES ('2513', '2017-10-17', '1');
INSERT INTO `_web_workday` VALUES ('2514', '2017-10-18', '1');
INSERT INTO `_web_workday` VALUES ('2515', '2017-10-19', '1');
INSERT INTO `_web_workday` VALUES ('2516', '2017-10-20', '1');
INSERT INTO `_web_workday` VALUES ('2517', '2017-10-21', '0');
INSERT INTO `_web_workday` VALUES ('2518', '2017-10-22', '0');
INSERT INTO `_web_workday` VALUES ('2519', '2017-10-23', '1');
INSERT INTO `_web_workday` VALUES ('2520', '2017-10-24', '1');
INSERT INTO `_web_workday` VALUES ('2521', '2017-10-25', '1');
INSERT INTO `_web_workday` VALUES ('2522', '2017-10-26', '1');
INSERT INTO `_web_workday` VALUES ('2523', '2017-10-27', '1');
INSERT INTO `_web_workday` VALUES ('2524', '2017-10-28', '0');
INSERT INTO `_web_workday` VALUES ('2525', '2017-10-29', '0');
INSERT INTO `_web_workday` VALUES ('2526', '2017-10-30', '1');
INSERT INTO `_web_workday` VALUES ('2527', '2017-10-31', '1');
INSERT INTO `_web_workday` VALUES ('2528', '2017-11-01', '1');
INSERT INTO `_web_workday` VALUES ('2529', '2017-11-02', '1');
INSERT INTO `_web_workday` VALUES ('2530', '2017-11-03', '1');
INSERT INTO `_web_workday` VALUES ('2531', '2017-11-04', '0');
INSERT INTO `_web_workday` VALUES ('2532', '2017-11-05', '0');
INSERT INTO `_web_workday` VALUES ('2533', '2017-11-06', '1');
INSERT INTO `_web_workday` VALUES ('2534', '2017-11-07', '1');
INSERT INTO `_web_workday` VALUES ('2535', '2017-11-08', '1');
INSERT INTO `_web_workday` VALUES ('2536', '2017-11-09', '1');
INSERT INTO `_web_workday` VALUES ('2537', '2017-11-10', '1');
INSERT INTO `_web_workday` VALUES ('2538', '2017-11-11', '0');
INSERT INTO `_web_workday` VALUES ('2539', '2017-11-12', '0');
INSERT INTO `_web_workday` VALUES ('2540', '2017-11-13', '1');
INSERT INTO `_web_workday` VALUES ('2541', '2017-11-14', '1');
INSERT INTO `_web_workday` VALUES ('2542', '2017-11-15', '1');
INSERT INTO `_web_workday` VALUES ('2543', '2017-11-16', '1');
INSERT INTO `_web_workday` VALUES ('2544', '2017-11-17', '1');
INSERT INTO `_web_workday` VALUES ('2545', '2017-11-18', '0');
INSERT INTO `_web_workday` VALUES ('2546', '2017-11-19', '0');
INSERT INTO `_web_workday` VALUES ('2547', '2017-11-20', '1');
INSERT INTO `_web_workday` VALUES ('2548', '2017-11-21', '1');
INSERT INTO `_web_workday` VALUES ('2549', '2017-11-22', '1');
INSERT INTO `_web_workday` VALUES ('2550', '2017-11-23', '1');
INSERT INTO `_web_workday` VALUES ('2551', '2017-11-24', '1');
INSERT INTO `_web_workday` VALUES ('2552', '2017-11-25', '0');
INSERT INTO `_web_workday` VALUES ('2553', '2017-11-26', '0');
INSERT INTO `_web_workday` VALUES ('2554', '2017-11-27', '1');
INSERT INTO `_web_workday` VALUES ('2555', '2017-11-28', '1');
INSERT INTO `_web_workday` VALUES ('2556', '2017-11-29', '1');
INSERT INTO `_web_workday` VALUES ('2557', '2017-11-30', '1');
INSERT INTO `_web_workday` VALUES ('2558', '2017-12-01', '0');
INSERT INTO `_web_workday` VALUES ('2559', '2017-12-02', '1');
INSERT INTO `_web_workday` VALUES ('2560', '2017-12-03', '0');
INSERT INTO `_web_workday` VALUES ('2561', '2017-12-04', '1');
INSERT INTO `_web_workday` VALUES ('2562', '2017-12-05', '1');
INSERT INTO `_web_workday` VALUES ('2563', '2017-12-06', '1');
INSERT INTO `_web_workday` VALUES ('2564', '2017-12-07', '1');
INSERT INTO `_web_workday` VALUES ('2565', '2017-12-08', '1');
INSERT INTO `_web_workday` VALUES ('2566', '2017-12-09', '0');
INSERT INTO `_web_workday` VALUES ('2567', '2017-12-10', '0');
INSERT INTO `_web_workday` VALUES ('2568', '2017-12-11', '1');
INSERT INTO `_web_workday` VALUES ('2569', '2017-12-12', '1');
INSERT INTO `_web_workday` VALUES ('2570', '2017-12-13', '1');
INSERT INTO `_web_workday` VALUES ('2571', '2017-12-14', '1');
INSERT INTO `_web_workday` VALUES ('2572', '2017-12-15', '1');
INSERT INTO `_web_workday` VALUES ('2573', '2017-12-16', '0');
INSERT INTO `_web_workday` VALUES ('2574', '2017-12-17', '0');
INSERT INTO `_web_workday` VALUES ('2575', '2017-12-18', '1');
INSERT INTO `_web_workday` VALUES ('2576', '2017-12-19', '1');
INSERT INTO `_web_workday` VALUES ('2577', '2017-12-20', '1');
INSERT INTO `_web_workday` VALUES ('2578', '2017-12-21', '1');
INSERT INTO `_web_workday` VALUES ('2579', '2017-12-22', '1');
INSERT INTO `_web_workday` VALUES ('2580', '2017-12-23', '0');
INSERT INTO `_web_workday` VALUES ('2581', '2017-12-24', '0');
INSERT INTO `_web_workday` VALUES ('2582', '2017-12-25', '1');
INSERT INTO `_web_workday` VALUES ('2583', '2017-12-26', '1');
INSERT INTO `_web_workday` VALUES ('2584', '2017-12-27', '1');
INSERT INTO `_web_workday` VALUES ('2585', '2017-12-28', '1');
INSERT INTO `_web_workday` VALUES ('2586', '2017-12-29', '1');
INSERT INTO `_web_workday` VALUES ('2587', '2017-12-30', '0');
INSERT INTO `_web_workday` VALUES ('2588', '2017-12-31', '0');
INSERT INTO `_web_workday` VALUES ('2589', '2018-01-01', '0');
INSERT INTO `_web_workday` VALUES ('2590', '2018-01-02', '1');
INSERT INTO `_web_workday` VALUES ('2591', '2018-01-03', '1');
INSERT INTO `_web_workday` VALUES ('2592', '2018-01-04', '1');
INSERT INTO `_web_workday` VALUES ('2593', '2018-01-05', '1');
INSERT INTO `_web_workday` VALUES ('2594', '2018-01-06', '0');
INSERT INTO `_web_workday` VALUES ('2595', '2018-01-07', '0');
INSERT INTO `_web_workday` VALUES ('2596', '2018-01-08', '1');
INSERT INTO `_web_workday` VALUES ('2597', '2018-01-09', '1');
INSERT INTO `_web_workday` VALUES ('2598', '2018-01-10', '1');
INSERT INTO `_web_workday` VALUES ('2599', '2018-01-11', '1');
INSERT INTO `_web_workday` VALUES ('2600', '2018-01-12', '1');
INSERT INTO `_web_workday` VALUES ('2601', '2018-01-13', '0');
INSERT INTO `_web_workday` VALUES ('2602', '2018-01-14', '0');
INSERT INTO `_web_workday` VALUES ('2603', '2018-01-15', '1');
INSERT INTO `_web_workday` VALUES ('2604', '2018-01-16', '1');
INSERT INTO `_web_workday` VALUES ('2605', '2018-01-17', '1');
INSERT INTO `_web_workday` VALUES ('2606', '2018-01-18', '1');
INSERT INTO `_web_workday` VALUES ('2607', '2018-01-19', '1');
INSERT INTO `_web_workday` VALUES ('2608', '2018-01-20', '0');
INSERT INTO `_web_workday` VALUES ('2609', '2018-01-21', '0');
INSERT INTO `_web_workday` VALUES ('2610', '2018-01-22', '1');
INSERT INTO `_web_workday` VALUES ('2611', '2018-01-23', '1');
INSERT INTO `_web_workday` VALUES ('2612', '2018-01-24', '1');
INSERT INTO `_web_workday` VALUES ('2613', '2018-01-25', '1');
INSERT INTO `_web_workday` VALUES ('2614', '2018-01-26', '1');
INSERT INTO `_web_workday` VALUES ('2615', '2018-01-27', '0');
INSERT INTO `_web_workday` VALUES ('2616', '2018-01-28', '0');
INSERT INTO `_web_workday` VALUES ('2617', '2018-01-29', '1');
INSERT INTO `_web_workday` VALUES ('2618', '2018-01-30', '1');
INSERT INTO `_web_workday` VALUES ('2619', '2018-01-31', '1');
INSERT INTO `_web_workday` VALUES ('2620', '2018-02-01', '1');
INSERT INTO `_web_workday` VALUES ('2621', '2018-02-02', '1');
INSERT INTO `_web_workday` VALUES ('2622', '2018-02-03', '0');
INSERT INTO `_web_workday` VALUES ('2623', '2018-02-04', '0');
INSERT INTO `_web_workday` VALUES ('2624', '2018-02-05', '1');
INSERT INTO `_web_workday` VALUES ('2625', '2018-02-06', '1');
INSERT INTO `_web_workday` VALUES ('2626', '2018-02-07', '0');
INSERT INTO `_web_workday` VALUES ('2627', '2018-02-08', '1');
INSERT INTO `_web_workday` VALUES ('2628', '2018-02-09', '1');
INSERT INTO `_web_workday` VALUES ('2629', '2018-02-10', '1');
INSERT INTO `_web_workday` VALUES ('2630', '2018-02-11', '1');
INSERT INTO `_web_workday` VALUES ('2631', '2018-02-12', '1');
INSERT INTO `_web_workday` VALUES ('2632', '2018-02-13', '1');
INSERT INTO `_web_workday` VALUES ('2633', '2018-02-14', '1');
INSERT INTO `_web_workday` VALUES ('2634', '2018-02-15', '0');
INSERT INTO `_web_workday` VALUES ('2635', '2018-02-16', '0');
INSERT INTO `_web_workday` VALUES ('2636', '2018-02-17', '0');
INSERT INTO `_web_workday` VALUES ('2637', '2018-02-18', '0');
INSERT INTO `_web_workday` VALUES ('2638', '2018-02-19', '0');
INSERT INTO `_web_workday` VALUES ('2639', '2018-02-20', '0');
INSERT INTO `_web_workday` VALUES ('2640', '2018-02-21', '0');
INSERT INTO `_web_workday` VALUES ('2641', '2018-02-22', '1');
INSERT INTO `_web_workday` VALUES ('2642', '2018-02-23', '1');
INSERT INTO `_web_workday` VALUES ('2643', '2018-02-24', '1');
INSERT INTO `_web_workday` VALUES ('2644', '2018-02-25', '0');
INSERT INTO `_web_workday` VALUES ('2645', '2018-02-26', '1');
INSERT INTO `_web_workday` VALUES ('2646', '2018-02-27', '1');
INSERT INTO `_web_workday` VALUES ('2647', '2018-02-28', '1');
INSERT INTO `_web_workday` VALUES ('2648', '2018-03-01', '1');
INSERT INTO `_web_workday` VALUES ('2649', '2018-03-02', '1');
INSERT INTO `_web_workday` VALUES ('2650', '2018-03-03', '0');
INSERT INTO `_web_workday` VALUES ('2651', '2018-03-04', '0');
INSERT INTO `_web_workday` VALUES ('2652', '2018-03-05', '1');
INSERT INTO `_web_workday` VALUES ('2653', '2018-03-06', '1');
INSERT INTO `_web_workday` VALUES ('2654', '2018-03-07', '1');
INSERT INTO `_web_workday` VALUES ('2655', '2018-03-08', '1');
INSERT INTO `_web_workday` VALUES ('2656', '2018-03-09', '1');
INSERT INTO `_web_workday` VALUES ('2657', '2018-03-10', '0');
INSERT INTO `_web_workday` VALUES ('2658', '2018-03-11', '0');
INSERT INTO `_web_workday` VALUES ('2659', '2018-03-12', '1');
INSERT INTO `_web_workday` VALUES ('2660', '2018-03-13', '1');
INSERT INTO `_web_workday` VALUES ('2661', '2018-03-14', '1');
INSERT INTO `_web_workday` VALUES ('2662', '2018-03-15', '1');
INSERT INTO `_web_workday` VALUES ('2663', '2018-03-16', '1');
INSERT INTO `_web_workday` VALUES ('2664', '2018-03-17', '0');
INSERT INTO `_web_workday` VALUES ('2665', '2018-03-18', '0');
INSERT INTO `_web_workday` VALUES ('2666', '2018-03-19', '1');
INSERT INTO `_web_workday` VALUES ('2667', '2018-03-20', '1');
INSERT INTO `_web_workday` VALUES ('2668', '2018-03-21', '1');
INSERT INTO `_web_workday` VALUES ('2669', '2018-03-22', '1');
INSERT INTO `_web_workday` VALUES ('2670', '2018-03-23', '1');
INSERT INTO `_web_workday` VALUES ('2671', '2018-03-24', '0');
INSERT INTO `_web_workday` VALUES ('2672', '2018-03-25', '0');
INSERT INTO `_web_workday` VALUES ('2673', '2018-03-26', '1');
INSERT INTO `_web_workday` VALUES ('2674', '2018-03-27', '1');
INSERT INTO `_web_workday` VALUES ('2675', '2018-03-28', '1');
INSERT INTO `_web_workday` VALUES ('2676', '2018-03-29', '1');
INSERT INTO `_web_workday` VALUES ('2677', '2018-03-30', '1');
INSERT INTO `_web_workday` VALUES ('2678', '2018-03-31', '0');
INSERT INTO `_web_workday` VALUES ('2679', '2018-04-01', '0');
INSERT INTO `_web_workday` VALUES ('2680', '2018-04-02', '1');
INSERT INTO `_web_workday` VALUES ('2681', '2018-04-03', '1');
INSERT INTO `_web_workday` VALUES ('2682', '2018-04-04', '1');
INSERT INTO `_web_workday` VALUES ('2683', '2018-04-05', '1');
INSERT INTO `_web_workday` VALUES ('2684', '2018-04-06', '1');
INSERT INTO `_web_workday` VALUES ('2685', '2018-04-07', '0');
INSERT INTO `_web_workday` VALUES ('2686', '2018-04-08', '0');
INSERT INTO `_web_workday` VALUES ('2687', '2018-04-09', '1');
INSERT INTO `_web_workday` VALUES ('2688', '2018-04-10', '1');
INSERT INTO `_web_workday` VALUES ('2689', '2018-04-11', '1');
INSERT INTO `_web_workday` VALUES ('2690', '2018-04-12', '1');
INSERT INTO `_web_workday` VALUES ('2691', '2018-04-13', '1');
INSERT INTO `_web_workday` VALUES ('2692', '2018-04-14', '0');
INSERT INTO `_web_workday` VALUES ('2693', '2018-04-15', '0');
INSERT INTO `_web_workday` VALUES ('2694', '2018-04-16', '1');
INSERT INTO `_web_workday` VALUES ('2695', '2018-04-17', '1');
INSERT INTO `_web_workday` VALUES ('2696', '2018-04-18', '1');
INSERT INTO `_web_workday` VALUES ('2697', '2018-04-19', '1');
INSERT INTO `_web_workday` VALUES ('2698', '2018-04-20', '1');
INSERT INTO `_web_workday` VALUES ('2699', '2018-04-21', '0');
INSERT INTO `_web_workday` VALUES ('2700', '2018-04-22', '0');
INSERT INTO `_web_workday` VALUES ('2701', '2018-04-23', '1');
INSERT INTO `_web_workday` VALUES ('2702', '2018-04-24', '1');
INSERT INTO `_web_workday` VALUES ('2703', '2018-04-25', '1');
INSERT INTO `_web_workday` VALUES ('2704', '2018-04-26', '1');
INSERT INTO `_web_workday` VALUES ('2705', '2018-04-27', '1');
INSERT INTO `_web_workday` VALUES ('2706', '2018-04-28', '0');
INSERT INTO `_web_workday` VALUES ('2707', '2018-04-29', '0');
INSERT INTO `_web_workday` VALUES ('2708', '2018-04-30', '1');
INSERT INTO `_web_workday` VALUES ('2709', '2018-06-01', '1');
INSERT INTO `_web_workday` VALUES ('2710', '2018-06-02', '0');
INSERT INTO `_web_workday` VALUES ('2711', '2018-06-03', '0');
INSERT INTO `_web_workday` VALUES ('2712', '2018-06-04', '1');
INSERT INTO `_web_workday` VALUES ('2713', '2018-06-05', '1');
INSERT INTO `_web_workday` VALUES ('2714', '2018-06-06', '1');
INSERT INTO `_web_workday` VALUES ('2715', '2018-06-07', '1');
INSERT INTO `_web_workday` VALUES ('2716', '2018-06-08', '1');
INSERT INTO `_web_workday` VALUES ('2717', '2018-06-09', '0');
INSERT INTO `_web_workday` VALUES ('2718', '2018-06-10', '0');
INSERT INTO `_web_workday` VALUES ('2719', '2018-06-11', '1');
INSERT INTO `_web_workday` VALUES ('2720', '2018-06-12', '1');
INSERT INTO `_web_workday` VALUES ('2721', '2018-06-13', '1');
INSERT INTO `_web_workday` VALUES ('2722', '2018-06-14', '1');
INSERT INTO `_web_workday` VALUES ('2723', '2018-06-15', '1');
INSERT INTO `_web_workday` VALUES ('2724', '2018-06-16', '0');
INSERT INTO `_web_workday` VALUES ('2725', '2018-06-17', '0');
INSERT INTO `_web_workday` VALUES ('2726', '2018-06-18', '1');
INSERT INTO `_web_workday` VALUES ('2727', '2018-06-19', '1');
INSERT INTO `_web_workday` VALUES ('2728', '2018-06-20', '1');
INSERT INTO `_web_workday` VALUES ('2729', '2018-06-21', '1');
INSERT INTO `_web_workday` VALUES ('2730', '2018-06-22', '1');
INSERT INTO `_web_workday` VALUES ('2731', '2018-06-23', '0');
INSERT INTO `_web_workday` VALUES ('2732', '2018-06-24', '0');
INSERT INTO `_web_workday` VALUES ('2733', '2018-06-25', '1');
INSERT INTO `_web_workday` VALUES ('2734', '2018-06-26', '1');
INSERT INTO `_web_workday` VALUES ('2735', '2018-06-27', '1');
INSERT INTO `_web_workday` VALUES ('2736', '2018-06-28', '1');
INSERT INTO `_web_workday` VALUES ('2737', '2018-06-29', '1');
INSERT INTO `_web_workday` VALUES ('2738', '2018-06-30', '0');

-- ----------------------------
-- Table structure for credit_status
-- ----------------------------
DROP TABLE IF EXISTS `credit_status`;
CREATE TABLE `credit_status` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `status_type` tinyint(2) unsigned zerofill NOT NULL DEFAULT '00',
  `status_no` tinyint(2) unsigned zerofill NOT NULL DEFAULT '00',
  `status_data` varchar(24) NOT NULL DEFAULT '0',
  `status_describe` varchar(64) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=83 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of credit_status
-- ----------------------------
INSERT INTO `credit_status` VALUES ('1', '100', '00', '允许通过', '[进门]');
INSERT INTO `credit_status` VALUES ('2', '100', '01', '允许通过', '[出门]');
INSERT INTO `credit_status` VALUES ('3', '100', '02', '允许通过', '[进门2]');
INSERT INTO `credit_status` VALUES ('4', '100', '03', '允许通过', '[出门2]');
INSERT INTO `credit_status` VALUES ('5', '100', '128', '禁止通过', '(1)号读卡器刷卡禁止通过: 原因不明');
INSERT INTO `credit_status` VALUES ('6', '100', '129', '禁止通过', '(2)号读卡器刷卡禁止通过: 原因不明');
INSERT INTO `credit_status` VALUES ('7', '100', '130', '禁止通过', '(3)号读卡器刷卡禁止通过: 原因不明');
INSERT INTO `credit_status` VALUES ('8', '100', '131', '禁止通过', '(4)号读卡器刷卡禁止通过: 原因不明');
INSERT INTO `credit_status` VALUES ('9', '100', '144', '禁止通过', '(1)号读卡器刷卡禁止通过: 没有权限');
INSERT INTO `credit_status` VALUES ('10', '100', '145', '禁止通过', '(2)号读卡器刷卡禁止通过: 没有权限');
INSERT INTO `credit_status` VALUES ('11', '100', '146', '禁止通过', '(3)号读卡器刷卡禁止通过: 没有权限');
INSERT INTO `credit_status` VALUES ('12', '100', '147', '禁止通过', '(4)号读卡器刷卡禁止通过: 没有权限');
INSERT INTO `credit_status` VALUES ('13', '100', '160', '禁止通过', '(1)号读卡器刷卡禁止通过: 密码不对');
INSERT INTO `credit_status` VALUES ('14', '100', '161', '禁止通过', '(2)号读卡器刷卡禁止通过: 密码不对');
INSERT INTO `credit_status` VALUES ('15', '100', '162', '禁止通过', '(3)号读卡器刷卡禁止通过: 密码不对');
INSERT INTO `credit_status` VALUES ('16', '100', '163', '禁止通过', '(4)号读卡器刷卡禁止通过: 密码不对');
INSERT INTO `credit_status` VALUES ('17', '100', '176', '禁止通过', '(1)号读卡器刷卡禁止通过: 系统有故障');
INSERT INTO `credit_status` VALUES ('18', '100', '177', '禁止通过', '(2)号读卡器刷卡禁止通过: 系统有故障');
INSERT INTO `credit_status` VALUES ('19', '100', '178', '禁止通过', '(3)号读卡器刷卡禁止通过: 系统有故障');
INSERT INTO `credit_status` VALUES ('20', '100', '179', '禁止通过', '(4)号读卡器刷卡禁止通过: 系统有故障');
INSERT INTO `credit_status` VALUES ('21', '100', '192', '禁止通过', '(1)号读卡器刷卡禁止通过: 反潜回, 多卡开门或多门互锁');
INSERT INTO `credit_status` VALUES ('22', '100', '193', '禁止通过', '(2)号读卡器刷卡禁止通过: 反潜回, 多卡开门或多门互锁');
INSERT INTO `credit_status` VALUES ('23', '100', '194', '禁止通过', '(3)号读卡器刷卡禁止通过: 反潜回, 多卡开门或多门互锁');
INSERT INTO `credit_status` VALUES ('24', '100', '195', '禁止通过', '(4)号读卡器刷卡禁止通过: 反潜回, 多卡开门或多门互锁');
INSERT INTO `credit_status` VALUES ('25', '100', '196', '禁止通过', '(1)号读卡器刷卡禁止通过: 反潜回');
INSERT INTO `credit_status` VALUES ('26', '100', '197', '禁止通过', '(2)号读卡器刷卡禁止通过: 反潜回');
INSERT INTO `credit_status` VALUES ('27', '100', '198', '禁止通过', '(3)号读卡器刷卡禁止通过: 反潜回');
INSERT INTO `credit_status` VALUES ('28', '100', '199', '禁止通过', '(4)号读卡器刷卡禁止通过: 反潜回');
INSERT INTO `credit_status` VALUES ('29', '100', '200', '禁止通过', '(1)号读卡器刷卡禁止通过: 多卡');
INSERT INTO `credit_status` VALUES ('30', '100', '201', '禁止通过', '(2)号读卡器刷卡禁止通过: 多卡');
INSERT INTO `credit_status` VALUES ('31', '100', '202', '禁止通过', '(3)号读卡器刷卡禁止通过: 多卡');
INSERT INTO `credit_status` VALUES ('32', '100', '203', '禁止通过', '(4)号读卡器刷卡禁止通过: 多卡');
INSERT INTO `credit_status` VALUES ('33', '100', '204', '禁止通过', '(1)号读卡器刷卡禁止通过: 首卡');
INSERT INTO `credit_status` VALUES ('34', '100', '205', '禁止通过', '(2)号读卡器刷卡禁止通过: 首卡');
INSERT INTO `credit_status` VALUES ('35', '100', '206', '禁止通过', '(3)号读卡器刷卡禁止通过: 首卡');
INSERT INTO `credit_status` VALUES ('36', '100', '207', '禁止通过', '(4)号读卡器刷卡禁止通过: 首卡');
INSERT INTO `credit_status` VALUES ('37', '100', '208', '禁止通过', '(1)号读卡器刷卡禁止通过: 门为常闭');
INSERT INTO `credit_status` VALUES ('38', '100', '209', '禁止通过', '(2)号读卡器刷卡禁止通过: 门为常闭');
INSERT INTO `credit_status` VALUES ('39', '100', '210', '禁止通过', '(3)号读卡器刷卡禁止通过: 门为常闭');
INSERT INTO `credit_status` VALUES ('40', '100', '211', '禁止通过', '(4)号读卡器刷卡禁止通过: 门为常闭');
INSERT INTO `credit_status` VALUES ('41', '100', '212', '禁止通过', '(1)号读卡器刷卡禁止通过: 互锁');
INSERT INTO `credit_status` VALUES ('42', '100', '213', '禁止通过', '(2)号读卡器刷卡禁止通过: 互锁');
INSERT INTO `credit_status` VALUES ('43', '100', '214', '禁止通过', '(3)号读卡器刷卡禁止通过: 互锁');
INSERT INTO `credit_status` VALUES ('44', '100', '215', '禁止通过', '(4)号读卡器刷卡禁止通过: 互锁');
INSERT INTO `credit_status` VALUES ('45', '100', '224', '禁止通过', '(1)号读卡器刷卡禁止通过: 卡过期或不在有效时段');
INSERT INTO `credit_status` VALUES ('46', '100', '225', '禁止通过', '(2)号读卡器刷卡禁止通过: 卡过期或不在有效时段');
INSERT INTO `credit_status` VALUES ('47', '100', '226', '禁止通过', '(3)号读卡器刷卡禁止通过: 卡过期或不在有效时段');
INSERT INTO `credit_status` VALUES ('48', '100', '227', '禁止通过', '(4)号读卡器刷卡禁止通过: 卡过期或不在有效时段');
INSERT INTO `credit_status` VALUES ('49', '00', '00', '按', '1号门按钮动作');
INSERT INTO `credit_status` VALUES ('50', '01', '00', '按钮', '2号门按钮动作');
INSERT INTO `credit_status` VALUES ('51', '02', '00', '按钮', '3号门按钮动作');
INSERT INTO `credit_status` VALUES ('52', '03', '00', '按钮', '4号门按钮动作');
INSERT INTO `credit_status` VALUES ('53', '00', '03', '远程开门', '1号门远程开门动作');
INSERT INTO `credit_status` VALUES ('54', '01', '03', '远程开门', '2号门远程开门动作');
INSERT INTO `credit_status` VALUES ('55', '02', '03', '远程开门', '3号门远程开门动作');
INSERT INTO `credit_status` VALUES ('56', '03', '03', '远程开门', '4号门远程开门动作');
INSERT INTO `credit_status` VALUES ('57', '05', '00', '超级密码开门', '1号读卡器超级密码开门');
INSERT INTO `credit_status` VALUES ('58', '05', '01', '超级密码开门', '2号读卡器超级密码开门');
INSERT INTO `credit_status` VALUES ('59', '05', '02', '超级密码开门', '3号读卡器超级密码开门');
INSERT INTO `credit_status` VALUES ('60', '05', '03', '超级密码开门', '4号读卡器超级密码开门');
INSERT INTO `credit_status` VALUES ('61', '08', '00', '门打开', '1号门打开[门磁信号]');
INSERT INTO `credit_status` VALUES ('62', '09', '00', '门打开', '2号门打开[门磁信号]');
INSERT INTO `credit_status` VALUES ('63', '10', '00', '门打开', '3号门打开[门磁信号]');
INSERT INTO `credit_status` VALUES ('64', '11', '00', '门打开', '4号门打开[门磁信号]');
INSERT INTO `credit_status` VALUES ('65', '12', '00', '门关闭', '1号门关闭[门磁信号]');
INSERT INTO `credit_status` VALUES ('66', '13', '00', '门关闭', '2号门关闭[门磁信号]');
INSERT INTO `credit_status` VALUES ('67', '14', '00', '门关闭', '3号门关闭[门磁信号]');
INSERT INTO `credit_status` VALUES ('68', '15', '00', '门关闭', '4号门关闭[门磁信号]');
INSERT INTO `credit_status` VALUES ('69', '00', '129', '胁迫报警', '1号读卡器胁迫报警');
INSERT INTO `credit_status` VALUES ('70', '01', '129', '胁迫报警', '2号读卡器胁迫报警');
INSERT INTO `credit_status` VALUES ('71', '02', '129', '胁迫报警', '3号读卡器胁迫报警');
INSERT INTO `credit_status` VALUES ('72', '03', '129', '胁迫报警', '4号读卡器胁迫报警');
INSERT INTO `credit_status` VALUES ('73', '00', '129', '门长时间未关报警', '1号门长时间未关报警');
INSERT INTO `credit_status` VALUES ('74', '01', '130', '门长时间未关报警', '2号门长时间未关报警');
INSERT INTO `credit_status` VALUES ('75', '02', '130', '门长时间未关报警', '3号门长时间未关报警');
INSERT INTO `credit_status` VALUES ('76', '03', '130', '门长时间未关报警', '4号门长时间未关报警');
INSERT INTO `credit_status` VALUES ('77', '00', '132', '非法闯入报警', '1号门非法闯入报警');
INSERT INTO `credit_status` VALUES ('78', '01', '132', '非法闯入报警', '2号门非法闯入报警');
INSERT INTO `credit_status` VALUES ('79', '02', '132', '非法闯入报警', '3号门非法闯入报警');
INSERT INTO `credit_status` VALUES ('80', '03', '132', '非法闯入报警', '4号门非法闯入报警');
INSERT INTO `credit_status` VALUES ('81', '04', '160', '火警', '火警动作[针对整个控制器]');
INSERT INTO `credit_status` VALUES ('82', '06', '160', '强制', '强制锁门[针对整个控制器]');

-- ----------------------------
-- Table structure for employee_account
-- ----------------------------
DROP TABLE IF EXISTS `employee_account`;
CREATE TABLE `employee_account` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `employee_no` int(4) unsigned zerofill NOT NULL DEFAULT '0000',
  `employee_name` varchar(16) NOT NULL DEFAULT '0',
  `account_id` int(11) unsigned zerofill NOT NULL DEFAULT '00000000000',
  `employee_dept` varchar(16) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `account_id` (`account_id`)
) ENGINE=MyISAM AUTO_INCREMENT=502 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of employee_account
-- ----------------------------
INSERT INTO `employee_account` VALUES ('424', '0385', '冯超群', '00011764832', '客服部');
INSERT INTO `employee_account` VALUES ('2', '0101', '陈总', '00005326068', '总办');
INSERT INTO `employee_account` VALUES ('426', '0386', '林斯超', '00004931898', '客服部');
INSERT INTO `employee_account` VALUES ('404', '0361', '付勇', '00004632904', '美术部');
INSERT INTO `employee_account` VALUES ('5', '0055', '张凯榕', '00004632932', '客服部');
INSERT INTO `employee_account` VALUES ('401', '0364', '秦羽茜', '00004931871', '策划部');
INSERT INTO `employee_account` VALUES ('402', '0363', '陈祥坤', '00004412227', 'QA部');
INSERT INTO `employee_account` VALUES ('295', '0179', '王娟娟', '00001055559', 'QA部');
INSERT INTO `employee_account` VALUES ('171', '0143', '郭阳', '00004432347', '美术部');
INSERT INTO `employee_account` VALUES ('346', '0324', '叶孝廷', '00004932133', '策划部');
INSERT INTO `employee_account` VALUES ('11', '0003', '张俊泉', '00009999999', 'web开发');
INSERT INTO `employee_account` VALUES ('381', '0030', '周晓林', '00011835713', '程序部');
INSERT INTO `employee_account` VALUES ('203', '0178', '陈鹏志', '00011827098', '程序部');
INSERT INTO `employee_account` VALUES ('192', '0027', '黄灿鑫', '00004561228', '程序');
INSERT INTO `employee_account` VALUES ('377', '0342', '游楠舟', '00011946218', '策划部');
INSERT INTO `employee_account` VALUES ('122', '0100', '余文', '00001061554', 'QA部');
INSERT INTO `employee_account` VALUES ('17', '0025', '党春雨', '00004932135', '程序部');
INSERT INTO `employee_account` VALUES ('394', '0357', '叶文乐', '00012320372', '程序部');
INSERT INTO `employee_account` VALUES ('428', '0384', '张梦婷', '00008502460', '客服部');
INSERT INTO `employee_account` VALUES ('20', '0031', '方建锋', '00005424088', 'QA部');
INSERT INTO `employee_account` VALUES ('409', '0369', '陈诗兰', '00005425147', '总办');
INSERT INTO `employee_account` VALUES ('119', '0016', '邱启福', '00004632951', '美术部');
INSERT INTO `employee_account` VALUES ('23', '0011', '郑晟', '00004628818', '美术部');
INSERT INTO `employee_account` VALUES ('405', '0365', '陈慧芳', '00004632970', '美术部');
INSERT INTO `employee_account` VALUES ('382', '0192', '陈君', '00011832865', '程序部');
INSERT INTO `employee_account` VALUES ('27', '0006', '杨金喜', '00004932101', '运营部');
INSERT INTO `employee_account` VALUES ('414', '0908', '薛尤升', '00012318067', '策划');
INSERT INTO `employee_account` VALUES ('340', '0321', '张伟', '00004632920', 'QA部');
INSERT INTO `employee_account` VALUES ('30', '0000', '叶兵飞', '00004632890', '策划部');
INSERT INTO `employee_account` VALUES ('31', '0021', '郑浩', '00004932131', '策划部');
INSERT INTO `employee_account` VALUES ('32', '0019', '詹承宇', '00005320477', '策划部');
INSERT INTO `employee_account` VALUES ('145', '0125', '黄淋', '00004632941', '程序部');
INSERT INTO `employee_account` VALUES ('146', '5425117', '潘熹', '00000000127', '策划部');
INSERT INTO `employee_account` VALUES ('141', '0124', '薛小燕', '00004628819', '客服部');
INSERT INTO `employee_account` VALUES ('36', '0038', '陈晓清', '00004932081', 'QA部');
INSERT INTO `employee_account` VALUES ('120', '0236', '邵长民', '00004931869', 'QA部');
INSERT INTO `employee_account` VALUES ('38', '0017', '黄春明', '00004932102', '策划部');
INSERT INTO `employee_account` VALUES ('39', '0441', '陈凯', '00004931891', '策划部');
INSERT INTO `employee_account` VALUES ('41', '0062', '王宁', '00004632923', '策划部');
INSERT INTO `employee_account` VALUES ('429', '0129', '游翊晨', '00008502651', '客服部');
INSERT INTO `employee_account` VALUES ('44', '0000', '迎宾卡', '00004632931', '迎宾卡');
INSERT INTO `employee_account` VALUES ('298', '0281', '孙多文', '00004632914', '总办');
INSERT INTO `employee_account` VALUES ('430', '0388', '王琳', '00008512495', '客服');
INSERT INTO `employee_account` VALUES ('48', '0317', '许国荣', '00004932134', '程序部');
INSERT INTO `employee_account` VALUES ('370', '0320', '黄建', '00011715962', '策划部');
INSERT INTO `employee_account` VALUES ('117', '0086', '谢世勇', '00001061107', '程序');
INSERT INTO `employee_account` VALUES ('432', '0389', '何君杰', '00008454422', '客服部');
INSERT INTO `employee_account` VALUES ('52', '0000', '特殊操作', '00000000001', '特殊操作');
INSERT INTO `employee_account` VALUES ('53', '0000', '特殊操作', '00000000002', '特殊操作');
INSERT INTO `employee_account` VALUES ('54', '0000', '特殊操作', '00000000003', '特殊操作');
INSERT INTO `employee_account` VALUES ('55', '0000', '特殊操作', '00000000004', '特殊操作');
INSERT INTO `employee_account` VALUES ('56', '0000', '特殊操作', '00000000005', '特殊操作');
INSERT INTO `employee_account` VALUES ('57', '0000', '特殊操作', '00000000006', '特殊操作');
INSERT INTO `employee_account` VALUES ('58', '0000', '特殊操作', '00000000007', '特殊操作');
INSERT INTO `employee_account` VALUES ('59', '0000', '特殊操作', '00000000008', '特殊操作');
INSERT INTO `employee_account` VALUES ('60', '0000', '特殊操作', '00000000009', '特殊操作');
INSERT INTO `employee_account` VALUES ('61', '0000', '特殊操作', '00000000010', '特殊操作');
INSERT INTO `employee_account` VALUES ('62', '0000', '特殊操作', '00000000011', '特殊操作');
INSERT INTO `employee_account` VALUES ('63', '0000', '特殊操作', '00000000012', '特殊操作');
INSERT INTO `employee_account` VALUES ('64', '0000', '特殊操作', '00000000013', '特殊操作');
INSERT INTO `employee_account` VALUES ('65', '0000', '特殊操作', '00000000014', '特殊操作');
INSERT INTO `employee_account` VALUES ('66', '0000', '特殊操作', '00000000015', '特殊操作');
INSERT INTO `employee_account` VALUES ('397', '0286', '容珊', '00004561179', '美术部');
INSERT INTO `employee_account` VALUES ('69', '0006', '杨金喜', '00004632922', '运营部');
INSERT INTO `employee_account` VALUES ('380', '0344', '王思维', '00009911174', '总办');
INSERT INTO `employee_account` VALUES ('410', '0372', '郑丹', '00004431006', '美术部');
INSERT INTO `employee_account` VALUES ('73', '0004', '蔡纯', '00004556823', 'QA部');
INSERT INTO `employee_account` VALUES ('137', '0111', '鄢忠锦', '00004556734', '程序部');
INSERT INTO `employee_account` VALUES ('112', '0087', '黄焱灯', '00004561224', '客服部');
INSERT INTO `employee_account` VALUES ('82', '0045', '刘斌', '00004561227', '服务端');
INSERT INTO `employee_account` VALUES ('296', '0044', '吴步广', '00011801514', '策划部');
INSERT INTO `employee_account` VALUES ('314', '0902', '付健美', '00001055593', '总办');
INSERT INTO `employee_account` VALUES ('407', '0367', '肖珊珊', '00004632934', 'QA部');
INSERT INTO `employee_account` VALUES ('389', '0450', '王丽玲', '00011810599', '总办');
INSERT INTO `employee_account` VALUES ('288', '0275', '李波', '00004419297', '策划部');
INSERT INTO `employee_account` VALUES ('338', '0235', '邱庆元', '00004429697', '美术部');
INSERT INTO `employee_account` VALUES ('328', '0314', '陈志忠', '00004407496', '美术部');
INSERT INTO `employee_account` VALUES ('100', '0073', '郑少波', '00004407702', 'QA部');
INSERT INTO `employee_account` VALUES ('422', '0444', '林绍', '00011946351', '程序部');
INSERT INTO `employee_account` VALUES ('102', '0076', '李举森', '00004422824', '程序部');
INSERT INTO `employee_account` VALUES ('317', '0903', '谢灵燕', '00011834395', 'QA部');
INSERT INTO `employee_account` VALUES ('301', '0285', '王剑洪', '00004632933', 'QA');
INSERT INTO `employee_account` VALUES ('431', '0390', '王涛', '00008453613', '总办');
INSERT INTO `employee_account` VALUES ('387', '0348', '钟吓丽', '00011944106', '美术部');
INSERT INTO `employee_account` VALUES ('138', '0115', '吴友柱', '00005320481', 'QA部');
INSERT INTO `employee_account` VALUES ('189', '0168', '蒋伟', '00004415685', 'QA部');
INSERT INTO `employee_account` VALUES ('417', '0365', '陈慧芳', '00004433337', '美术部');
INSERT INTO `employee_account` VALUES ('413', '0376', '李冰', '00004409700', '策划部');
INSERT INTO `employee_account` VALUES ('114', '0090', '陈晓毅', '00004431402', '运营部');
INSERT INTO `employee_account` VALUES ('300', '0283', '汤晓森', '00005425117', '程序部');
INSERT INTO `employee_account` VALUES ('134', '0105', '叶嘉旭', '00004553752', '策划部');
INSERT INTO `employee_account` VALUES ('304', '0288', '陈文', '00001059403', '程序部');
INSERT INTO `employee_account` VALUES ('157', '0137', '徐襄', '00004427723', '策划部');
INSERT INTO `employee_account` VALUES ('266', '0246', '陈秀清', '00004411533', '总办');
INSERT INTO `employee_account` VALUES ('160', '0139', '张木林', '00004423413', '程序');
INSERT INTO `employee_account` VALUES ('278', '0272', '吴真', '00004415699', '美术部');
INSERT INTO `employee_account` VALUES ('320', '0306', '黄诗贤', '00004413710', '程序部');
INSERT INTO `employee_account` VALUES ('367', '0400', '林永蘸', '00004409666', '总办');
INSERT INTO `employee_account` VALUES ('334', '0142', '骆斌杰', '00011842522', '程序部');
INSERT INTO `employee_account` VALUES ('208', '0181', '林毅', '00011845014', '程序部');
INSERT INTO `employee_account` VALUES ('378', '0356', '保洁员', '00011813799', '总办');
INSERT INTO `employee_account` VALUES ('169', '0146', '林森程序', '00004429674', '程序部');
INSERT INTO `employee_account` VALUES ('384', '0032', '康晓烽', '00012318519', 'QA部');
INSERT INTO `employee_account` VALUES ('415', '0234', '王丽萍', '00001055679', '美术部');
INSERT INTO `employee_account` VALUES ('412', '0906', '黄辉', '00004932132', '运营');
INSERT INTO `employee_account` VALUES ('177', '0153', '郑振清', '00004410947', '程序部');
INSERT INTO `employee_account` VALUES ('403', '0362', '卢海燕', '00005663841', '美术部');
INSERT INTO `employee_account` VALUES ('180', '0154', '宋立群', '00004414629', '程序部');
INSERT INTO `employee_account` VALUES ('181', '0156', '江晓岚', '00004434894', '客服');
INSERT INTO `employee_account` VALUES ('182', '0160', '于孙担', '00004430968', 'QA');
INSERT INTO `employee_account` VALUES ('319', '0305', '朱剑辉', '00004423644', '程序部');
INSERT INTO `employee_account` VALUES ('383', '0345', '张凯成', '00011958000', '美术部');
INSERT INTO `employee_account` VALUES ('392', '0909', '张晰洁', '00011943794', '运营');
INSERT INTO `employee_account` VALUES ('212', '0242', 'ÕÅÁ¢ÖÒ', '00011814527', 'QA²¿');
INSERT INTO `employee_account` VALUES ('420', '0379', '赵丹丹', '00004416029', '客服部');
INSERT INTO `employee_account` VALUES ('395', '0355', '李强', '00011943862', '美术部');
INSERT INTO `employee_account` VALUES ('398', '0358', '陈晓忠', '00011719713', 'QA部');
INSERT INTO `employee_account` VALUES ('216', '0250', '叶峰', '00011811979', '程序部');
INSERT INTO `employee_account` VALUES ('373', '0340', '张鹏', '00009910748', '策划部');
INSERT INTO `employee_account` VALUES ('232', '0207', '鄢铮铮', '00011813325', 'QA部');
INSERT INTO `employee_account` VALUES ('379', '0312', '林文川', '00011821640', 'QA部');
INSERT INTO `employee_account` VALUES ('323', '0310', '翁礼强', '00011716298', '程序部');
INSERT INTO `employee_account` VALUES ('221', '0194', '邹静晶', '00011822652', '美术部');
INSERT INTO `employee_account` VALUES ('222', '0196', '陈勇', '00011731911', '客服部');
INSERT INTO `employee_account` VALUES ('425', '0382', '王曌影', '00011812303', '美术部');
INSERT INTO `employee_account` VALUES ('390', '0456', '刘金秀', '00011944905', '行政');
INSERT INTO `employee_account` VALUES ('416', '0901', '石敏敏', '00011838440', '总办');
INSERT INTO `employee_account` VALUES ('280', '0274', '董振', '00000000000', '');
INSERT INTO `employee_account` VALUES ('228', '0203', '黄长海', '00011840945', '客服部');
INSERT INTO `employee_account` VALUES ('229', '0265', '汪月华', '00011802927', '运营部');
INSERT INTO `employee_account` VALUES ('230', '0204', '骆嘉俊', '00011835754', 'QA部');
INSERT INTO `employee_account` VALUES ('418', '0000', '陈颖 ', '00000000219', '程序部');
INSERT INTO `employee_account` VALUES ('233', '11829369', '闵晓轩', '00000000210', '程序');
INSERT INTO `employee_account` VALUES ('234', '11765090', '赖根龄', '00000000259', '程序');
INSERT INTO `employee_account` VALUES ('235', '11806369', '黄明枣', '00000000211', '程序');
INSERT INTO `employee_account` VALUES ('479', '0911', '张婷婷', '00006508082', 'QA');
INSERT INTO `employee_account` VALUES ('237', '0209', '赖根龄', '00011765090', '程序部');
INSERT INTO `employee_account` VALUES ('238', '0210', '闵晓轩', '00011829369', '程序部');
INSERT INTO `employee_account` VALUES ('374', '0341', '陈堃', '00012316754', '美术部');
INSERT INTO `employee_account` VALUES ('243', '11809093', '陈天明', '00000000214', '美术部');
INSERT INTO `employee_account` VALUES ('290', '0442', '游朝山', '00011809093', '美术部');
INSERT INTO `employee_account` VALUES ('245', '0085', '张健', '00011829873', '策划部');
INSERT INTO `employee_account` VALUES ('250', '0219', '彭茂荣', '00011809946', '程序部');
INSERT INTO `employee_account` VALUES ('248', '0217', '欧乐辉', '00011831141', 'QA部');
INSERT INTO `employee_account` VALUES ('249', '0449', '白猛', '00011830839', '程序部');
INSERT INTO `employee_account` VALUES ('360', '0337', '谢兴朋', '00012319280', '美术部');
INSERT INTO `employee_account` VALUES ('419', '0448', '肖雪平', '00011806369', '程序部');
INSERT INTO `employee_account` VALUES ('427', '0453', '陈燕斌', '00008503637', 'web开发');
INSERT INTO `employee_account` VALUES ('342', '0199', '加武鹏', '00011823470', '策划部');
INSERT INTO `employee_account` VALUES ('386', '0349', '张兆臻', '00011841943', '美术部');
INSERT INTO `employee_account` VALUES ('388', '0350', '林连帆', '00011949842', '总办');
INSERT INTO `employee_account` VALUES ('258', '0231', '黄喜星', '00011811499', '程序部');
INSERT INTO `employee_account` VALUES ('421', '0380', '陈超', '00004421827', '程序部');
INSERT INTO `employee_account` VALUES ('263', '0237', '林翔宇', '00004409642', '美术部');
INSERT INTO `employee_account` VALUES ('375', '11946218', '游楠舟', '00000000342', '策划部');
INSERT INTO `employee_account` VALUES ('385', '0346', '李书群', '00011958919', '策划部');
INSERT INTO `employee_account` VALUES ('277', '0271', '李畅', '00001054672', '策划部');
INSERT INTO `employee_account` VALUES ('371', '0279', '董震', '00011946620', '策划部');
INSERT INTO `employee_account` VALUES ('433', '0391', '管智渊', '00008503330', 'QA部');
INSERT INTO `employee_account` VALUES ('434', '0904', '邱晓卿', '00008503521', '美术部');
INSERT INTO `employee_account` VALUES ('435', '0395', '辛晓泉', '00002416542', 'QA部');
INSERT INTO `employee_account` VALUES ('436', '0440', '杜唯毅', '00001708304', '策划部');
INSERT INTO `employee_account` VALUES ('437', '0394', '程灵艳', '00002416540', '策划部');
INSERT INTO `employee_account` VALUES ('438', '0396', '方誉', '00001708554', '市场部');
INSERT INTO `employee_account` VALUES ('446', '0402', '郑宏楗', '00008463137', '客服部');
INSERT INTO `employee_account` VALUES ('440', '0398', '陈曦', '00008504127', '客服部');
INSERT INTO `employee_account` VALUES ('441', '0401', '许楠', '00008464808', '客服部');
INSERT INTO `employee_account` VALUES ('442', '0439', '徐惠源', '00008464996', 'QA部');
INSERT INTO `employee_account` VALUES ('443', '0452', '卢丽芳', '00008510840', '美术部');
INSERT INTO `employee_account` VALUES ('477', '0438', '陈樑森', '00008464771', '策划部');
INSERT INTO `employee_account` VALUES ('445', '0405', '陈琦', '00008506415', '程序部');
INSERT INTO `employee_account` VALUES ('447', '0445', '胡晓珊', '00008458213', '总办');
INSERT INTO `employee_account` VALUES ('449', '0409', '刘欢', '00008502588', '总办');
INSERT INTO `employee_account` VALUES ('450', '0331', '赖娜', '00008502779', '策划部');
INSERT INTO `employee_account` VALUES ('451', '0410', '吴婷', '00001649596', '总办');
INSERT INTO `employee_account` VALUES ('452', '0411', '吴李伟', '00001657781', '策划部');
INSERT INTO `employee_account` VALUES ('453', '0412', '陈媛', '00006514665', '策划部');
INSERT INTO `employee_account` VALUES ('454', '0413', '林光海', '00006649359', '程序部');
INSERT INTO `employee_account` VALUES ('455', '0414', '林晨', '00008511339', '美术部');
INSERT INTO `employee_account` VALUES ('456', '0415', '张祚柳', '00008511169', '总办');
INSERT INTO `employee_account` VALUES ('457', '0416', '田青', '00001643319', '总办');
INSERT INTO `employee_account` VALUES ('458', '0418', '夏宇航', '00001641920', '策划部');
INSERT INTO `employee_account` VALUES ('459', '0420', '林永尧', '00006504903', '服务端程序部');
INSERT INTO `employee_account` VALUES ('460', '0455', '詹富伟', '00006532629', '美术部');
INSERT INTO `employee_account` VALUES ('461', '0454', '饶智滨', '00006518027', 'QA部');
INSERT INTO `employee_account` VALUES ('462', '0425', '李淑青', '00001648617', '运营部');
INSERT INTO `employee_account` VALUES ('463', '0424', '崔本凯', '00001647895', '程序部');
INSERT INTO `employee_account` VALUES ('464', '0423', '赖苹苹', '00001703821', '总办');
INSERT INTO `employee_account` VALUES ('465', '0915', '9楼保洁员', '00001647892', '总办');
INSERT INTO `employee_account` VALUES ('466', '0426', '朱雄峰', '00001647405', '程序部');
INSERT INTO `employee_account` VALUES ('467', '0428', '陈悦', '00001648378', '运营部');
INSERT INTO `employee_account` VALUES ('468', '0429', '王星星', '00001649105', '美术部');
INSERT INTO `employee_account` VALUES ('469', '0430', '杨欢', '00006525644', '运营部');
INSERT INTO `employee_account` VALUES ('470', '0431', '张玉玲', '00006519055', '总办');
INSERT INTO `employee_account` VALUES ('471', '0432', '林峰', '00008507922', '总办');
INSERT INTO `employee_account` VALUES ('472', '0433', '林良奎', '00006501768', '美术部');
INSERT INTO `employee_account` VALUES ('473', '0434', '田烨', '00006533961', '策划部');
INSERT INTO `employee_account` VALUES ('474', '0002', '杨小花', '00008512509', '美术部');
INSERT INTO `employee_account` VALUES ('475', '0436', '吴财贵', '00008511012', '程序部');
INSERT INTO `employee_account` VALUES ('476', '0437', '李斌', '00008504719', '总办');
INSERT INTO `employee_account` VALUES ('478', '0232', '陈颖', '00011946219', '程序部');
INSERT INTO `employee_account` VALUES ('480', '0910', '9楼保洁员2', '00006736106', '行政');
INSERT INTO `employee_account` VALUES ('482', '0459', '顾梦妮', '00006735837', '客服');
INSERT INTO `employee_account` VALUES ('483', '0458', '王家裕', '00006520365', '美术');
INSERT INTO `employee_account` VALUES ('484', '0913', '林豪', '00006538791', '客户端程序');
INSERT INTO `employee_account` VALUES ('485', '0917', '蒋瑾慧', '00006533580', '策划员');
INSERT INTO `employee_account` VALUES ('486', '0918', '戴郎达', '00006528546', '客户端程序部');
INSERT INTO `employee_account` VALUES ('487', '0462', '吴家炳', '00007007207', '客户端程序部');
INSERT INTO `employee_account` VALUES ('488', '0461', '陈彬', '00007115098', '策划部');
INSERT INTO `employee_account` VALUES ('489', '0919', '吴美珍', '00007057060', '总办');
INSERT INTO `employee_account` VALUES ('490', '0920', '张雪枫', '00006531902', '运营');
INSERT INTO `employee_account` VALUES ('491', '0463', '陈威', '00006534077', 'web');
INSERT INTO `employee_account` VALUES ('492', '0464', '王龙钦', '00007107532', '客户端');
INSERT INTO `employee_account` VALUES ('493', '0465', '张亦弛', '00006509720', '策划部');
INSERT INTO `employee_account` VALUES ('494', '0466', '黄晓健', '00006506691', '服务端程序员');
INSERT INTO `employee_account` VALUES ('495', '0467', '艾伟金', '00007054132', '客户端');
INSERT INTO `employee_account` VALUES ('496', '0469', '李婉阳', '00007100717', '客服部');
INSERT INTO `employee_account` VALUES ('497', '0470', '林泰忠', '00007103264', 'QA');
INSERT INTO `employee_account` VALUES ('498', '0921', '林陶钧', '00006736114', 'QA');
INSERT INTO `employee_account` VALUES ('499', '0471', '丰奇炜', '00006515475', '策划部');
INSERT INTO `employee_account` VALUES ('500', '0922', '王雍翔', '00006734706', '策划部');
INSERT INTO `employee_account` VALUES ('501', '0472', '周岱峰', '00006525107', '服务端');

-- ----------------------------
-- Table structure for game_name
-- ----------------------------
DROP TABLE IF EXISTS `game_name`;
CREATE TABLE `game_name` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `real_name` varchar(30) NOT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_name
-- ----------------------------

-- ----------------------------
-- Table structure for game_name_vote
-- ----------------------------
DROP TABLE IF EXISTS `game_name_vote`;
CREATE TABLE `game_name_vote` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name_id` int(11) unsigned NOT NULL,
  `ip` varchar(20) NOT NULL,
  `addtime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_name_vote
-- ----------------------------

-- ----------------------------
-- Table structure for hugh_time_log
-- ----------------------------
DROP TABLE IF EXISTS `hugh_time_log`;
CREATE TABLE `hugh_time_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `operaterID` int(11) NOT NULL DEFAULT '0',
  `hughID` int(11) NOT NULL DEFAULT '0',
  `hughTime` float(11,2) NOT NULL DEFAULT '0.00',
  `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=117 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of hugh_time_log
-- ----------------------------

-- ----------------------------
-- Table structure for kaohe_2012_fen
-- ----------------------------
DROP TABLE IF EXISTS `kaohe_2012_fen`;
CREATE TABLE `kaohe_2012_fen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fen` int(11) NOT NULL DEFAULT '0',
  `beiping` int(11) NOT NULL DEFAULT '0',
  `ping` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  `add_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kaohe_2012_fen
-- ----------------------------

-- ----------------------------
-- Table structure for kaohe_2012_myinfo
-- ----------------------------
DROP TABLE IF EXISTS `kaohe_2012_myinfo`;
CREATE TABLE `kaohe_2012_myinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `info1` varchar(5000) NOT NULL DEFAULT '',
  `info2` varchar(5000) NOT NULL DEFAULT '',
  `admin_id` int(11) NOT NULL DEFAULT '0',
  `add_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kaohe_2012_myinfo
-- ----------------------------

-- ----------------------------
-- Table structure for lastbk_time
-- ----------------------------
DROP TABLE IF EXISTS `lastbk_time`;
CREATE TABLE `lastbk_time` (
  `last_time` int(4) unsigned NOT NULL,
  PRIMARY KEY (`last_time`)
) ENGINE=MyISAM AUTO_INCREMENT=83 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lastbk_time
-- ----------------------------
