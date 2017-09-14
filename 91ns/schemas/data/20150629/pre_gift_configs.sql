/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.70
Source Server Version : 50539
Source Host           : 192.168.1.70:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50539
File Encoding         : 65001

Date: 2015-06-29 18:27:31
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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=46 DEFAULT CHARSET=utf8 COMMENT='礼物配置表';

-- ----------------------------
-- Records of pre_gift_configs
-- ----------------------------
INSERT INTO `pre_gift_configs` VALUES ('4', '0', '0', '1', '红玫瑰', '0', '5', '2', '0', '0', '1', '0', '1426823913', 'mg', '0', '最好用的刷屏神器！');
INSERT INTO `pre_gift_configs` VALUES ('5', '0', '5', '2', '甜蜜约会', '0', '20000', '10000', '9', '0', '0', '0', '1426823997', 'tmyh', '0', '想约Ta,不甜蜜约会怎么行？');
INSERT INTO `pre_gift_configs` VALUES ('6', '0', '0', '1', '掌声', '5', '0', '0', '0', '0', '1', '0', '1426841925', 'gz', '0', '觉的好？鼓掌是一种基本的礼貌吧！');
INSERT INTO `pre_gift_configs` VALUES ('7', '0', '0', '1', '砖头', '0', '5', '2', '0', '0', '1', '0', '1426842002', 'bn', '0', '砸场专用，威力无边，慎用慎用。');
INSERT INTO `pre_gift_configs` VALUES ('8', '0', '0', '1', '香吻', '0', '10', '5', '0', '0', '1', '0', '1426842148', 'xw', '0', '女神这么漂亮可爱，亲一下呗。');
INSERT INTO `pre_gift_configs` VALUES ('9', '0', '0', '6', '心心相印', '10', '0', '0', '0', '0', '1', '0', '1426842200', 'xxxy', '0', '跟主播互动超合拍？就送上一个亲密的心心相印吧。');
INSERT INTO `pre_gift_configs` VALUES ('10', '0', '0', '6', '棒棒糖', '10', '0', '0', '0', '0', '1', '0', '1426842233', 'bbt', '0', '含一支，马上么么哒！');
INSERT INTO `pre_gift_configs` VALUES ('11', '0', '0', '6', '蛋糕', '10', '0', '0', '0', '0', '1', '0', '1426842261', 'dg', '0', '祝主播生日快乐！怎能少了蛋糕呢？');
INSERT INTO `pre_gift_configs` VALUES ('12', '0', '0', '1', '勿忘我', '20', '0', '0', '0', '0', '1', '0', '1426842290', 'www', '0', '想让女神记住你？还有什么比这表情更合适的？');
INSERT INTO `pre_gift_configs` VALUES ('13', '0', '0', '1', '巧克力', '0', '20', '10', '0', '0', '1', '0', '1426842329', 'qkl', '0', '情人节的代表，女神的等待。');
INSERT INTO `pre_gift_configs` VALUES ('14', '0', '0', '1', '红酒', '0', '20', '10', '0', '0', '1', '0', '1426842359', 'hj', '0', '醉了？赶紧来瓶红酒表示一下。');
INSERT INTO `pre_gift_configs` VALUES ('15', '0', '0', '6', '玫瑰花束', '50', '0', '0', '0', '0', '1', '0', '1426842437', 'hs', '0', '在这美丽的时光，送他一份甜蜜的惊喜吧。');
INSERT INTO `pre_gift_configs` VALUES ('16', '0', '0', '1', '香水', '50', '0', '0', '0', '0', '1', '0', '1426842548', 'xs', '0', '你的香水，会让主播更加迷人。');
INSERT INTO `pre_gift_configs` VALUES ('17', '0', '0', '2', '情侣对戒', '0', '1000', '500', '9', '0', '1', '0', '1426842604', 'qldj', '0', '名称已经说明了一切，你觉得送上这个Ta会拒绝吗？');
INSERT INTO `pre_gift_configs` VALUES ('18', '0', '0', '6', '水晶鞋', '1000', '0', '0', '0', '0', '1', '0', '1426842678', 'sjx', '0', '灰姑娘变公主的魔法，你就是她的王子。');
INSERT INTO `pre_gift_configs` VALUES ('19', '0', '0', '2', 'LV包', '0', '1000', '500', '9', '0', '1', '0', '1426842734', 'lvbb', '0', '女生梦寐以求的必备物品，你的女神怎能没有？');
INSERT INTO `pre_gift_configs` VALUES ('20', '0', '0', '6', '劳力士', '2000', '0', '0', '0', '0', '1', '0', '1426842770', 'sb', '0', '把珍贵的点滴时光送上。');
INSERT INTO `pre_gift_configs` VALUES ('21', '0', '0', '2', '钻石', '0', '2000', '1000', '9', '0', '1', '0', '1426842842', 'zs', '0', '最好爱情的象征，还不快来一颗钻石向女神表明你的心意？');
INSERT INTO `pre_gift_configs` VALUES ('22', '0', '0', '2', '皇冠', '0', '2000', '1000', '9', '0', '1', '0', '1426842875', 'hg', '0', '专业抢榜神器，尽显尊贵奢华，彰显你的身份与实力。');
INSERT INTO `pre_gift_configs` VALUES ('23', '0', '0', '2', '兰博基尼', '0', '20000', '10000', '9', '0', '0', '0', '1426843012', 'lbjn', '0', '高富帅不二选择——此神器将对所有人引发屏幕特效。');
INSERT INTO `pre_gift_configs` VALUES ('24', '0', '0', '2', '私人游艇', '0', '60000', '30000', '9', '0', '0', '0', '1426843042', 'yt', '0', '警告！它将带来世界富豪级的震撼——此神器将对所有人引发屏幕特效。');
INSERT INTO `pre_gift_configs` VALUES ('25', '0', '0', '1', '蓝色妖姬', '0', '5', '2', '0', '0', '1', '0', '1426843095', 'lsyj', '1', '满屏幕的红玫瑰？送上蓝色妖姬，让你立刻与众不同！');
INSERT INTO `pre_gift_configs` VALUES ('26', '0', '0', '2', '挖掘机', '0', '1000', '500', '0', '0', '0', '0', '1426843128', 'dskwjj', '1', '告诉女神，现在挖掘机一台要95万人民币。');
INSERT INTO `pre_gift_configs` VALUES ('27', '0', '0', '2', '切糕车', '0', '1000', '500', '0', '0', '0', '0', '1426843156', 'dsmqg', '1', '一车的切糕，比一车的黄金还值钱！');
INSERT INTO `pre_gift_configs` VALUES ('28', '0', '0', '2', '为你心动', '0', '3000', '1500', '0', '0', '0', '0', '1426843186', 'wnxd', '1', '心动就要让女神知道。');
INSERT INTO `pre_gift_configs` VALUES ('29', '0', '0', '2', '为你伴舞', '0', '3000', '1500', '0', '0', '0', '0', '1426843211', 'wnbw', '1', '歌唱得好，舞不能少。');
INSERT INTO `pre_gift_configs` VALUES ('30', '0', '0', '2', '甜蜜骑行', '0', '3000', '1500', '0', '0', '0', '0', '1426843232', 'tmqx', '1', '甜蜜骑行，甜过初恋。');
INSERT INTO `pre_gift_configs` VALUES ('31', '0', '0', '2', '爱的火山', '0', '10000', '5000', '0', '0', '0', '0', '1426843271', 'adhs', '1', 'Max屏幕特效，女神想不认识你都难！火山出手，主播我有！');
INSERT INTO `pre_gift_configs` VALUES ('32', '0', '0', '2', '幸福摩天轮', '0', '20000', '10000', '0', '0', '0', '0', '1426843296', 'xfmtl', '1', '邂逅时的心动，只能用它来表达。');
INSERT INTO `pre_gift_configs` VALUES ('33', '0', '0', '2', '烛光晚餐', '0', '30000', '15000', '0', '0', '0', '0', '1426843324', 'zgwc', '1', '跟主播一起度过了美妙时光？那就请你的Ta烛光晚餐。');
INSERT INTO `pre_gift_configs` VALUES ('34', '0', '0', '2', '私人岛屿', '0', '50000', '25000', '0', '0', '0', '0', '1426843345', 'srdy', '1', '专属你和女神的完美私人空间，她怎能不心动。');
INSERT INTO `pre_gift_configs` VALUES ('35', '1', '0', '6', '金玫瑰', '0', '10', '5', '0', '10', '1', '0', '1426843447', 'jmg', '0', '金玫瑰，金色的热情真爱，代表最珍重的祝福。');
INSERT INTO `pre_gift_configs` VALUES ('36', '0', '1', '1', '月饼', '0', '50', '25', '0', '0', '1', '0', '1426843561', 'yb', '0', '今夜月圆花好主播美？做富豪的你不送块月饼表示一下？');
INSERT INTO `pre_gift_configs` VALUES ('37', '0', '5', '2', '烟花', '0', '10000', '5000', '9', '0', '0', '0', '1426843617', 'yh', '0', '绚烂的烟花表达爱意，秀场有你最浪漫。');
INSERT INTO `pre_gift_configs` VALUES ('38', '0', '8', '2', 'BeMyGirl', '0', '60000', '30000', '9', '0', '0', '0', '1426843781', 'zwnpy', '0', '富豪终极技能，让主播变你的女孩。——富豪专属Max屏幕特效。');
INSERT INTO `pre_gift_configs` VALUES ('40', '0', '0', '7', '幸运草', '0', '10', '5', '0', '0', '1', '0', '1434597995', 'Lucky_clover', '0', '赠送人有一定几率(低)获得1000倍的奖励');
INSERT INTO `pre_gift_configs` VALUES ('41', '0', '0', '7', '幸运星', '0', '50', '25', '0', '0', '1', '0', '1434598040', 'Lucky_star', '0', '赠送人有一定几率(低)获得1000倍的奖励');
INSERT INTO `pre_gift_configs` VALUES ('42', '0', '0', '7', '幸运魔方', '0', '100', '50', '0', '0', '1', '0', '1434598079', 'Lucky_cube', '0', '赠送人有一定几率(中)获得1000倍的奖励');
INSERT INTO `pre_gift_configs` VALUES ('43', '0', '0', '7', '幸运水晶', '0', '500', '250', '0', '0', '1', '0', '1434598180', 'Lucky_crystal', '0', '赠送人有一定几率(中)获得1000倍的奖励');
INSERT INTO `pre_gift_configs` VALUES ('44', '0', '0', '7', '幸运皇冠', '0', '1000', '500', '0', '0', '1', '0', '1434598207', 'Lucky_crown', '0', '赠送人有一定几率(高)获得1000倍的奖励');
