/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.71
Source Server Version : 50539
Source Host           : 192.168.1.71:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50539
File Encoding         : 65001

Date: 2015-10-22 23:28:14
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for pre_gift_configs
-- ----------------------------
DROP TABLE IF EXISTS `pre_gift_configs`;
CREATE TABLE `pre_gift_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vipLevel` tinyint(3) DEFAULT '0',
  `richerLevel` tinyint(3) DEFAULT '0',
  `typeId` tinyint(3) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `coin` int(11) DEFAULT NULL,
  `cash` int(11) DEFAULT NULL,
  `recvCoin` int(8) DEFAULT NULL,
  `discount` tinyint(1) DEFAULT NULL,
  `freeCount` tinyint(1) DEFAULT NULL,
  `littleFlag` tinyint(1) DEFAULT NULL,
  `orderType` int(11) DEFAULT NULL,
  `createTime` int(11) DEFAULT NULL,
  `configName` varchar(20) NOT NULL COMMENT '配置名称，索引图片别名用',
  `guardFlag` tinyint(1) DEFAULT '0' COMMENT '是否需要守护',
  `description` varchar(50) DEFAULT NULL COMMENT '描述',
  `tagPic` varchar(50) DEFAULT NULL COMMENT '礼物标签图片',
  `isDefault` tinyint(3) DEFAULT '0' COMMENT '是否默认选中',
  `tagDesc` varchar(20) DEFAULT NULL COMMENT '标签描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='礼物配置表';

-- ----------------------------
-- Records of pre_gift_configs
-- ----------------------------
INSERT INTO `pre_gift_configs` VALUES ('4', '0', '0', '1', '红玫瑰', '0', '6', '3', '0', '0', '1', '11', '1426823913', 'mg', '0', '最好用的刷屏神器！', '', '1', null);
INSERT INTO `pre_gift_configs` VALUES ('5', '0', '0', '2', '甜蜜约会', '0', '20000', '10000', '0', '0', '0', '106', '1426823997', 'tmyh', '0', '想约Ta,不甜蜜约会怎么行？', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('6', '0', '0', '8', '掌声', '6', '0', '0', '0', '0', '1', '201', '1426841925', 'gz', '0', '觉得好？鼓掌是一种基本的礼貌吧！', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('7', '0', '0', '1', '便便', '0', '6', '3', '0', '0', '1', '12', '1426842002', 'bb', '0', '砸场专用，威力无边，慎用慎用。', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('8', '0', '0', '1', '香吻', '0', '10', '5', '0', '0', '1', '13', '1426842148', 'xw', '0', '女神这么漂亮可爱，亲一下呗。', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('9', '0', '0', '6', '心心相印', '10', '0', '0', '0', '0', '1', '1022', '1426842200', 'xxxy', '0', '跟主播互动超合拍？就送上一个亲密的心心相印吧。', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('10', '0', '0', '6', '棒棒糖', '10', '0', '0', '0', '0', '1', '1020', '1426842233', 'bbt', '0', '含一支，马上么么哒！', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('11', '0', '0', '6', '蛋糕', '10', '0', '0', '0', '0', '1', '1019', '1426842261', 'dg', '0', '祝主播生日快乐！怎能少了蛋糕呢？', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('12', '0', '0', '8', '勿忘我', '20', '0', '0', '0', '0', '1', '202', '1426842290', 'www', '0', '想让女神记住你？还有什么比这表情更合适的？', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('13', '0', '0', '1', '巧克力', '0', '20', '10', '0', '0', '1', '14', '1426842329', 'qkl', '0', '情人节的代表，女神的等待。', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('14', '0', '0', '1', '红酒', '0', '20', '10', '0', '0', '1', '15', '1426842359', 'hj', '0', '醉了？赶紧来瓶红酒表示一下。', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('15', '0', '0', '6', '玫瑰花束', '500', '0', '0', '0', '0', '0', '1017', '1426842437', 'hs', '0', '在这美丽的时光，送他一份甜蜜的惊喜吧。', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('16', '0', '0', '8', '香水', '50', '0', '0', '0', '0', '1', '203', '1426842548', 'xs', '0', '你的香水，会让主播更加迷人。', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('17', '0', '0', '1', '情侣对戒', '0', '50', '25', '0', '0', '1', '16', '1426842604', 'qldj', '0', '名称已经说明了一切，你觉得送上这个Ta会拒绝吗？', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('18', '0', '0', '6', '水晶鞋', '1000', '0', '0', '0', '0', '0', '1015', '1426842678', 'sjx', '0', '灰姑娘变公主的魔法，你就是她的王子。', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('19', '0', '0', '1', 'LV包', '0', '50', '25', '0', '0', '1', '17', '1426842734', 'lvbb', '0', '女生梦寐以求的必备物品，你的女神怎能没有？', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('20', '0', '0', '6', '劳力士', '2000', '0', '0', '0', '0', '1', '1013', '1426842770', 'sb', '0', '把珍贵的点滴时光送上。', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('21', '0', '0', '1', '钻石', '0', '100', '50', '0', '0', '1', '18', '1426842842', 'zs', '0', '最好爱情的象征，还不快来一颗钻石向女神表明你的心意？', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('22', '0', '0', '1', '皇冠', '0', '100', '50', '0', '0', '1', '19', '1426842875', 'hg', '0', '专业抢榜神器，尽显尊贵奢华，彰显你的身份与实力。', '', '0', '');
INSERT INTO `pre_gift_configs` VALUES ('23', '0', '0', '2', '兰博基尼', '0', '20000', '10000', '9', '0', '0', '105', '1426843012', 'lbjn', '0', '高富帅不二选择——此神器将对所有人引发屏幕特效。', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('24', '0', '0', '6', '私人游艇', '8000', '0', '0', '0', '0', '0', '1011', '1426843042', 'yt', '0', '警告！它将带来世界富豪级的震撼——此神器将对所有人引发屏幕特效。', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('25', '0', '0', '8', '蓝色妖姬', '0', '6', '3', '0', '0', '1', '204', '1426843095', 'lsyj', '1', '满屏幕的红玫瑰？送上蓝色妖姬，让你立刻与众不同！', '/tag/zhibojian_shouhu.png', '0', '守护');
INSERT INTO `pre_gift_configs` VALUES ('26', '0', '0', '8', '挖掘机', '0', '1000', '500', '0', '0', '0', '205', '1426843128', 'dskwjj', '1', '告诉女神，现在挖掘机一台要95万人民币。', '/tag/zhibojian_shouhu.png', '0', '守护');
INSERT INTO `pre_gift_configs` VALUES ('27', '0', '0', '8', '切糕车', '0', '1000', '500', '0', '0', '0', '206', '1426843156', 'dsmqg', '1', '一车的切糕，比一车的黄金还值钱！', '/tag/zhibojian_shouhu.png', '0', '守护');
INSERT INTO `pre_gift_configs` VALUES ('28', '0', '0', '2', '为你心动', '0', '3000', '1500', '0', '0', '0', '103', '1426843186', 'wnxd', '0', '心动就要让女神知道。', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('29', '0', '0', '2', '为你伴舞', '0', '3000', '1500', '0', '0', '0', '102', '1426843211', 'wnbw', '0', '歌唱得好，舞不能少。', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('30', '0', '0', '2', '甜蜜骑行', '0', '3000', '1500', '0', '0', '0', '101', '1426843232', 'tmqx', '0', '甜蜜骑行，甜过初恋。', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('31', '0', '0', '6', '爱的火山', '2000', '0', '0', '0', '0', '0', '1014', '1426843271', 'adhs', '0', 'Max屏幕特效，女神想不认识你都难！火山出手，主播我有！', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('32', '0', '0', '6', '幸福摩天轮', '4000', '0', '0', '0', '0', '0', '1012', '1426843296', 'xfmtl', '0', '邂逅时的心动，只能用它来表达。', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('33', '0', '0', '2', '烛光晚餐', '0', '30000', '15000', '0', '0', '0', '107', '1426843324', 'zgwc', '0', '跟主播一起度过了美妙时光？那就请你的Ta烛光晚餐。', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('34', '0', '0', '2', '私人岛屿', '0', '50000', '25000', '0', '0', '0', '108', '1426843345', 'srdy', '0', '专属你和女神的完美私人空间，她怎能不心动？', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('35', '1', '0', '6', '金玫瑰', '0', '10', '5', '0', '10', '1', '1007', '1426843447', 'jmg', '0', '金玫瑰，金色的热情真爱，代表最珍重的祝福。', '/tag/zhibojian_vip.png', '0', null);
INSERT INTO `pre_gift_configs` VALUES ('36', '0', '1', '8', '小樱桃', '0', '50', '25', '0', '0', '1', '207', '1426843561', 'xyt', '0', '那抹红水嫩得就像恋人的唇！', '/tag/zhibojian_dengji.png', '0', '一富以上');
INSERT INTO `pre_gift_configs` VALUES ('37', '0', '0', '2', '烟花', '0', '10000', '5000', '0', '0', '0', '104', '1426843617', 'yh', '0', '绚烂的烟花表达爱意，秀场有你最浪漫。', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('38', '0', '8', '8', 'BeMyGirl', '0', '60000', '30000', '9', '0', '0', '208', '1426843781', 'zwnpy', '0', '富豪终极技能，让主播变你的女孩。——富豪专属Max屏幕特效。', '/tag/zhibojian_dengji.png', '0', '八富以上');
INSERT INTO `pre_gift_configs` VALUES ('40', '0', '0', '7', '幸运草', '0', '10', '5', '0', '0', '1', '301', '1434597995', 'Lucky_clover', '0', '赠送人有一定几率(低)获得1000倍的奖励', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('41', '0', '0', '7', '幸运星', '0', '50', '25', '0', '0', '1', '302', '1434598040', 'Lucky_star', '0', '赠送人有一定几率(低)获得1000倍的奖励', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('42', '0', '0', '7', '幸运魔方', '0', '100', '50', '0', '0', '1', '303', '1434598079', 'Lucky_cube', '0', '赠送人有一定几率(中)获得1000倍的奖励', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('43', '0', '0', '7', '幸运水晶', '0', '500', '250', '0', '0', '1', '304', '1434598180', 'Lucky_crystal', '0', '赠送人有一定几率(中)获得1000倍的奖励', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('44', '0', '0', '7', '幸运皇冠', '0', '1000', '500', '0', '0', '1', '305', '1434598207', 'Lucky_crown', '0', '赠送人有一定几率(高)获得1000倍的奖励', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('46', '0', '0', '9', '小龙虾', '0', '20', '10', '0', '0', '1', '0', '1437709628', 'xlx', '0', '女神害羞起来和小龙虾一样，红扑扑的，好可爱~', '/tag/zhouxing.png', '0', '周星');
INSERT INTO `pre_gift_configs` VALUES ('47', '0', '0', '6', '莲花灯', '0', '7', '3', '0', '0', '1', '1009', '1439295424', 'Lotus_Light', '0', '在这个最浪漫的日子里，让女神动心的最佳礼物。赠送有一定的几率（低）获得七夕专属座驾—比翼鸟。', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('48', '0', '0', '6', '月饼', '0', '10', '4', '0', '0', '1', '1008', '1442285641', 'zqyb', '0', '中秋赏月怎能没有月饼呢？赠送可贡献1点月光。', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('49', '0', '0', '6', '月亮', '0', '10000', '4000', '0', '0', '0', '1001', '1442285641', 'yl', '0', '中秋佳节，邀请大家来一起赏月吧！赠送可贡献1000点月光。', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('50', '0', '0', '6', '状元饼', '0', '500', '250', '0', '0', '0', '1002', '1442285641', 'zyyb', '0', '中秋博饼会中获得状元的奖励，送出有特效！', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('51', '0', '0', '6', '榜眼饼', '0', '200', '100', '0', '0', '0', '1003', '1442285641', 'byyb', '0', '中秋博饼会中获得榜眼的奖励，送出有特效！', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('52', '0', '0', '6', '探花饼', '0', '100', '50', '0', '0', '0', '1004', '1442285641', 'thyb', '0', '中秋博饼会中获得探花的奖励，送出有特效！', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('53', '0', '0', '6', '进士饼', '0', '50', '25', '0', '0', '1', '1005', '1442285641', 'jsyb', '0', '中秋博饼会中获得进士的奖励。', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('54', '0', '0', '6', '举人饼', '0', '20', '10', '0', '0', '1', '1006', '1442285641', 'jryb', '0', '中秋博饼会中获得举人的奖励。', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('55', '0', '0', '6', '秀才饼', '0', '5', '2', '0', '0', '1', '1010', '1442285641', 'xcyb', '0', '中秋博饼会中获得秀才的奖励。', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('56', '0', '0', '6', '草莓', '10', '0', '0', '0', '0', '1', '1021', '1444300474', 'cm', '0', ' 鲜美多汁的草莓，不来一个么？', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('57', '0', '0', '6', '甜甜圈', '100', '0', '0', '0', '0', '1', '1018', '1444300491', 'ttq', '0', '甜美松软，口感极佳！', null, '0', null);
INSERT INTO `pre_gift_configs` VALUES ('58', '0', '0', '6', '白色郁金香', '500', '0', '0', '0', '0', '1', '1016', '1444300504', 'bsyjx', '0', '她的心灵纯洁得就像这白色郁金香！', null, '0', null);
