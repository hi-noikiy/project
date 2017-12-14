/*
Navicat MySQL Data Transfer

Source Server         : 俄罗斯web
Source Server Version : 50636
Source Host           : 127.0.0.1:3306
Source Database       : u591

Target Server Type    : MYSQL
Target Server Version : 50636
File Encoding         : 65001

Date: 2017-12-08 16:39:51
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for web_access
-- ----------------------------
DROP TABLE IF EXISTS `web_access`;
CREATE TABLE `web_access` (
  `role_id` smallint(6) unsigned NOT NULL,
  `node_id` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) NOT NULL,
  `pid` smallint(6) NOT NULL,
  `module` varchar(50) DEFAULT NULL,
  KEY `groupId` (`role_id`),
  KEY `nodeId` (`node_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of web_access
-- ----------------------------
INSERT INTO `web_access` VALUES ('9', '1', '1', '0', null);
INSERT INTO `web_access` VALUES ('9', '40', '2', '1', null);
INSERT INTO `web_access` VALUES ('9', '50', '3', '40', null);
INSERT INTO `web_access` VALUES ('7', '1', '1', '0', null);
INSERT INTO `web_access` VALUES ('9', '98', '2', '1', null);
INSERT INTO `web_access` VALUES ('1', '1', '1', '0', null);
INSERT INTO `web_access` VALUES ('2', '1', '1', '0', null);
INSERT INTO `web_access` VALUES ('2', '357', '2', '1', null);
INSERT INTO `web_access` VALUES ('2', '165', '3', '164', null);
INSERT INTO `web_access` VALUES ('2', '349', '2', '1', null);
INSERT INTO `web_access` VALUES ('2', '290', '2', '1', null);
INSERT INTO `web_access` VALUES ('2', '130', '3', '129', null);
INSERT INTO `web_access` VALUES ('2', '291', '3', '290', null);
INSERT INTO `web_access` VALUES ('2', '282', '2', '1', null);
INSERT INTO `web_access` VALUES ('2', '274', '2', '1', null);
INSERT INTO `web_access` VALUES ('2', '266', '2', '1', null);
INSERT INTO `web_access` VALUES ('2', '258', '2', '1', null);
INSERT INTO `web_access` VALUES ('2', '250', '2', '1', null);
INSERT INTO `web_access` VALUES ('2', '210', '2', '1', null);
INSERT INTO `web_access` VALUES ('2', '207', '2', '1', null);
INSERT INTO `web_access` VALUES ('2', '205', '2', '1', null);
INSERT INTO `web_access` VALUES ('2', '193', '2', '1', null);
INSERT INTO `web_access` VALUES ('13', '1', '1', '0', null);
INSERT INTO `web_access` VALUES ('13', '290', '2', '1', null);
INSERT INTO `web_access` VALUES ('2', '188', '2', '1', null);
INSERT INTO `web_access` VALUES ('13', '234', '3', '233', null);
INSERT INTO `web_access` VALUES ('13', '235', '3', '233', null);
INSERT INTO `web_access` VALUES ('13', '240', '3', '238', null);
INSERT INTO `web_access` VALUES ('13', '239', '3', '238', null);
INSERT INTO `web_access` VALUES ('2', '147', '3', '146', null);
INSERT INTO `web_access` VALUES ('2', '148', '3', '146', null);
INSERT INTO `web_access` VALUES ('2', '149', '3', '146', null);
INSERT INTO `web_access` VALUES ('2', '150', '3', '146', null);
INSERT INTO `web_access` VALUES ('2', '151', '3', '146', null);
INSERT INTO `web_access` VALUES ('2', '152', '3', '146', null);
INSERT INTO `web_access` VALUES ('2', '153', '3', '146', null);
INSERT INTO `web_access` VALUES ('2', '206', '3', '205', null);
INSERT INTO `web_access` VALUES ('2', '209', '3', '207', null);
INSERT INTO `web_access` VALUES ('2', '208', '3', '207', null);
INSERT INTO `web_access` VALUES ('2', '309', '3', '210', null);
INSERT INTO `web_access` VALUES ('2', '212', '3', '210', null);
INSERT INTO `web_access` VALUES ('15', '1', '1', '0', null);
INSERT INTO `web_access` VALUES ('1', '165', '3', '164', null);
INSERT INTO `web_access` VALUES ('2', '339', '3', '250', null);
INSERT INTO `web_access` VALUES ('2', '338', '3', '250', null);
INSERT INTO `web_access` VALUES ('2', '255', '3', '250', null);
INSERT INTO `web_access` VALUES ('2', '254', '3', '250', null);
INSERT INTO `web_access` VALUES ('2', '253', '3', '250', null);
INSERT INTO `web_access` VALUES ('14', '291', '3', '290', null);
INSERT INTO `web_access` VALUES ('2', '263', '3', '258', null);
INSERT INTO `web_access` VALUES ('2', '262', '3', '258', null);
INSERT INTO `web_access` VALUES ('2', '261', '3', '258', null);
INSERT INTO `web_access` VALUES ('2', '260', '3', '258', null);
INSERT INTO `web_access` VALUES ('2', '259', '3', '258', null);
INSERT INTO `web_access` VALUES ('15', '361', '2', '1', null);
INSERT INTO `web_access` VALUES ('2', '271', '3', '266', null);
INSERT INTO `web_access` VALUES ('2', '270', '3', '266', null);
INSERT INTO `web_access` VALUES ('2', '269', '3', '266', null);
INSERT INTO `web_access` VALUES ('2', '268', '3', '266', null);
INSERT INTO `web_access` VALUES ('2', '267', '3', '266', null);
INSERT INTO `web_access` VALUES ('15', '357', '2', '1', null);
INSERT INTO `web_access` VALUES ('2', '279', '3', '274', null);
INSERT INTO `web_access` VALUES ('2', '278', '3', '274', null);
INSERT INTO `web_access` VALUES ('2', '277', '3', '274', null);
INSERT INTO `web_access` VALUES ('2', '276', '3', '274', null);
INSERT INTO `web_access` VALUES ('2', '275', '3', '274', null);
INSERT INTO `web_access` VALUES ('15', '349', '2', '1', null);
INSERT INTO `web_access` VALUES ('2', '287', '3', '282', null);
INSERT INTO `web_access` VALUES ('2', '286', '3', '282', null);
INSERT INTO `web_access` VALUES ('2', '285', '3', '282', null);
INSERT INTO `web_access` VALUES ('2', '284', '3', '282', null);
INSERT INTO `web_access` VALUES ('2', '283', '3', '282', null);
INSERT INTO `web_access` VALUES ('2', '178', '2', '1', null);
INSERT INTO `web_access` VALUES ('2', '173', '2', '1', null);
INSERT INTO `web_access` VALUES ('2', '164', '2', '1', null);
INSERT INTO `web_access` VALUES ('2', '154', '2', '1', null);
INSERT INTO `web_access` VALUES ('2', '189', '3', '188', null);
INSERT INTO `web_access` VALUES ('2', '190', '3', '188', null);
INSERT INTO `web_access` VALUES ('2', '191', '3', '188', null);
INSERT INTO `web_access` VALUES ('2', '192', '3', '188', null);
INSERT INTO `web_access` VALUES ('2', '179', '3', '178', null);
INSERT INTO `web_access` VALUES ('2', '180', '3', '178', null);
INSERT INTO `web_access` VALUES ('2', '181', '3', '178', null);
INSERT INTO `web_access` VALUES ('2', '182', '3', '178', null);
INSERT INTO `web_access` VALUES ('2', '174', '3', '173', null);
INSERT INTO `web_access` VALUES ('2', '175', '3', '173', null);
INSERT INTO `web_access` VALUES ('2', '176', '3', '173', null);
INSERT INTO `web_access` VALUES ('2', '177', '3', '173', null);
INSERT INTO `web_access` VALUES ('2', '155', '3', '154', null);
INSERT INTO `web_access` VALUES ('2', '156', '3', '154', null);
INSERT INTO `web_access` VALUES ('2', '157', '3', '154', null);
INSERT INTO `web_access` VALUES ('2', '158', '3', '154', null);
INSERT INTO `web_access` VALUES ('2', '146', '2', '1', null);
INSERT INTO `web_access` VALUES ('2', '194', '3', '193', null);
INSERT INTO `web_access` VALUES ('2', '195', '3', '193', null);
INSERT INTO `web_access` VALUES ('2', '196', '3', '193', null);
INSERT INTO `web_access` VALUES ('2', '197', '3', '193', null);
INSERT INTO `web_access` VALUES ('14', '1', '1', '0', null);
INSERT INTO `web_access` VALUES ('13', '282', '2', '1', null);
INSERT INTO `web_access` VALUES ('14', '195', '3', '193', null);
INSERT INTO `web_access` VALUES ('14', '194', '3', '193', null);
INSERT INTO `web_access` VALUES ('13', '274', '2', '1', null);
INSERT INTO `web_access` VALUES ('14', '165', '3', '164', null);
INSERT INTO `web_access` VALUES ('14', '177', '3', '173', null);
INSERT INTO `web_access` VALUES ('14', '339', '3', '250', null);
INSERT INTO `web_access` VALUES ('14', '338', '3', '250', null);
INSERT INTO `web_access` VALUES ('14', '260', '3', '258', null);
INSERT INTO `web_access` VALUES ('14', '259', '3', '258', null);
INSERT INTO `web_access` VALUES ('14', '268', '3', '266', null);
INSERT INTO `web_access` VALUES ('14', '267', '3', '266', null);
INSERT INTO `web_access` VALUES ('14', '276', '3', '274', null);
INSERT INTO `web_access` VALUES ('14', '275', '3', '274', null);
INSERT INTO `web_access` VALUES ('14', '284', '3', '282', null);
INSERT INTO `web_access` VALUES ('14', '283', '3', '282', null);
INSERT INTO `web_access` VALUES ('11', '1', '1', '0', null);
INSERT INTO `web_access` VALUES ('1', '149', '3', '146', null);
INSERT INTO `web_access` VALUES ('1', '148', '3', '146', null);
INSERT INTO `web_access` VALUES ('1', '147', '3', '146', null);
INSERT INTO `web_access` VALUES ('1', '328', '3', '154', null);
INSERT INTO `web_access` VALUES ('1', '158', '3', '154', null);
INSERT INTO `web_access` VALUES ('1', '157', '3', '154', null);
INSERT INTO `web_access` VALUES ('1', '156', '3', '154', null);
INSERT INTO `web_access` VALUES ('1', '155', '3', '154', null);
INSERT INTO `web_access` VALUES ('1', '316', '3', '159', null);
INSERT INTO `web_access` VALUES ('1', '163', '3', '159', null);
INSERT INTO `web_access` VALUES ('1', '162', '3', '159', null);
INSERT INTO `web_access` VALUES ('1', '161', '3', '159', null);
INSERT INTO `web_access` VALUES ('1', '160', '3', '159', null);
INSERT INTO `web_access` VALUES ('1', '177', '3', '173', null);
INSERT INTO `web_access` VALUES ('1', '176', '3', '173', null);
INSERT INTO `web_access` VALUES ('1', '175', '3', '173', null);
INSERT INTO `web_access` VALUES ('1', '174', '3', '173', null);
INSERT INTO `web_access` VALUES ('1', '182', '3', '178', null);
INSERT INTO `web_access` VALUES ('1', '181', '3', '178', null);
INSERT INTO `web_access` VALUES ('1', '180', '3', '178', null);
INSERT INTO `web_access` VALUES ('1', '179', '3', '178', null);
INSERT INTO `web_access` VALUES ('1', '192', '3', '188', null);
INSERT INTO `web_access` VALUES ('1', '191', '3', '188', null);
INSERT INTO `web_access` VALUES ('1', '190', '3', '188', null);
INSERT INTO `web_access` VALUES ('1', '189', '3', '188', null);
INSERT INTO `web_access` VALUES ('1', '197', '3', '193', null);
INSERT INTO `web_access` VALUES ('1', '196', '3', '193', null);
INSERT INTO `web_access` VALUES ('1', '195', '3', '193', null);
INSERT INTO `web_access` VALUES ('1', '194', '3', '193', null);
INSERT INTO `web_access` VALUES ('1', '311', '3', '198', null);
INSERT INTO `web_access` VALUES ('1', '312', '3', '218', null);
INSERT INTO `web_access` VALUES ('11', '453', '3', '414', null);
INSERT INTO `web_access` VALUES ('11', '491', '3', '409', null);
INSERT INTO `web_access` VALUES ('11', '485', '2', '1', null);
INSERT INTO `web_access` VALUES ('11', '479', '2', '1', null);
INSERT INTO `web_access` VALUES ('11', '473', '2', '1', null);
INSERT INTO `web_access` VALUES ('11', '107', '3', '106', null);
INSERT INTO `web_access` VALUES ('11', '108', '3', '106', null);
INSERT INTO `web_access` VALUES ('11', '109', '3', '106', null);
INSERT INTO `web_access` VALUES ('11', '110', '3', '106', null);
INSERT INTO `web_access` VALUES ('11', '123', '3', '111', null);
INSERT INTO `web_access` VALUES ('11', '122', '3', '111', null);
INSERT INTO `web_access` VALUES ('11', '121', '3', '111', null);
INSERT INTO `web_access` VALUES ('11', '120', '3', '111', null);
INSERT INTO `web_access` VALUES ('11', '119', '3', '111', null);
INSERT INTO `web_access` VALUES ('11', '118', '3', '111', null);
INSERT INTO `web_access` VALUES ('11', '117', '3', '111', null);
INSERT INTO `web_access` VALUES ('11', '116', '3', '111', null);
INSERT INTO `web_access` VALUES ('11', '115', '3', '111', null);
INSERT INTO `web_access` VALUES ('11', '114', '3', '111', null);
INSERT INTO `web_access` VALUES ('11', '113', '3', '111', null);
INSERT INTO `web_access` VALUES ('11', '112', '3', '111', null);
INSERT INTO `web_access` VALUES ('11', '125', '3', '124', null);
INSERT INTO `web_access` VALUES ('11', '126', '3', '124', null);
INSERT INTO `web_access` VALUES ('11', '127', '3', '124', null);
INSERT INTO `web_access` VALUES ('11', '128', '3', '124', null);
INSERT INTO `web_access` VALUES ('1', '150', '3', '146', null);
INSERT INTO `web_access` VALUES ('1', '153', '3', '146', null);
INSERT INTO `web_access` VALUES ('1', '152', '3', '146', null);
INSERT INTO `web_access` VALUES ('1', '151', '3', '146', null);
INSERT INTO `web_access` VALUES ('1', '144', '3', '141', null);
INSERT INTO `web_access` VALUES ('1', '143', '3', '141', null);
INSERT INTO `web_access` VALUES ('1', '142', '3', '141', null);
INSERT INTO `web_access` VALUES ('1', '330', '3', '136', null);
INSERT INTO `web_access` VALUES ('1', '313', '3', '136', null);
INSERT INTO `web_access` VALUES ('1', '145', '3', '141', null);
INSERT INTO `web_access` VALUES ('1', '134', '3', '131', null);
INSERT INTO `web_access` VALUES ('1', '133', '3', '131', null);
INSERT INTO `web_access` VALUES ('1', '132', '3', '131', null);
INSERT INTO `web_access` VALUES ('1', '140', '3', '136', null);
INSERT INTO `web_access` VALUES ('1', '139', '3', '136', null);
INSERT INTO `web_access` VALUES ('1', '137', '3', '136', null);
INSERT INTO `web_access` VALUES ('1', '138', '3', '136', null);
INSERT INTO `web_access` VALUES ('1', '130', '3', '129', null);
INSERT INTO `web_access` VALUES ('1', '135', '3', '131', null);
INSERT INTO `web_access` VALUES ('15', '212', '3', '210', null);
INSERT INTO `web_access` VALUES ('15', '211', '3', '210', null);
INSERT INTO `web_access` VALUES ('15', '330', '3', '136', null);
INSERT INTO `web_access` VALUES ('11', '165', '3', '164', null);
INSERT INTO `web_access` VALUES ('11', '467', '3', '409', null);
INSERT INTO `web_access` VALUES ('11', '466', '3', '409', null);
INSERT INTO `web_access` VALUES ('11', '447', '3', '409', null);
INSERT INTO `web_access` VALUES ('11', '403', '3', '401', null);
INSERT INTO `web_access` VALUES ('11', '402', '3', '401', null);
INSERT INTO `web_access` VALUES ('11', '167', '3', '166', null);
INSERT INTO `web_access` VALUES ('15', '308', '3', '207', null);
INSERT INTO `web_access` VALUES ('15', '209', '3', '207', null);
INSERT INTO `web_access` VALUES ('15', '208', '3', '207', null);
INSERT INTO `web_access` VALUES ('15', '309', '3', '210', null);
INSERT INTO `web_access` VALUES ('15', '337', '3', '111', null);
INSERT INTO `web_access` VALUES ('15', '206', '3', '205', null);
INSERT INTO `web_access` VALUES ('15', '336', '3', '111', null);
INSERT INTO `web_access` VALUES ('15', '123', '3', '111', null);
INSERT INTO `web_access` VALUES ('15', '122', '3', '111', null);
INSERT INTO `web_access` VALUES ('15', '121', '3', '111', null);
INSERT INTO `web_access` VALUES ('15', '120', '3', '111', null);
INSERT INTO `web_access` VALUES ('15', '119', '3', '111', null);
INSERT INTO `web_access` VALUES ('15', '118', '3', '111', null);
INSERT INTO `web_access` VALUES ('15', '117', '3', '111', null);
INSERT INTO `web_access` VALUES ('15', '331', '2', '1', null);
INSERT INTO `web_access` VALUES ('15', '116', '3', '111', null);
INSERT INTO `web_access` VALUES ('15', '115', '3', '111', null);
INSERT INTO `web_access` VALUES ('15', '112', '3', '111', null);
INSERT INTO `web_access` VALUES ('15', '113', '3', '111', null);
INSERT INTO `web_access` VALUES ('15', '114', '3', '111', null);
INSERT INTO `web_access` VALUES ('15', '332', '3', '331', null);
INSERT INTO `web_access` VALUES ('15', '333', '3', '331', null);
INSERT INTO `web_access` VALUES ('15', '334', '3', '331', null);
INSERT INTO `web_access` VALUES ('15', '335', '3', '331', null);
INSERT INTO `web_access` VALUES ('1', '307', '3', '218', null);
INSERT INTO `web_access` VALUES ('1', '292', '2', '1', null);
INSERT INTO `web_access` VALUES ('1', '290', '2', '1', null);
INSERT INTO `web_access` VALUES ('1', '282', '2', '1', null);
INSERT INTO `web_access` VALUES ('1', '274', '2', '1', null);
INSERT INTO `web_access` VALUES ('1', '266', '2', '1', null);
INSERT INTO `web_access` VALUES ('1', '258', '2', '1', null);
INSERT INTO `web_access` VALUES ('1', '250', '2', '1', null);
INSERT INTO `web_access` VALUES ('1', '245', '2', '1', null);
INSERT INTO `web_access` VALUES ('1', '243', '2', '1', null);
INSERT INTO `web_access` VALUES ('1', '238', '2', '1', null);
INSERT INTO `web_access` VALUES ('1', '233', '2', '1', null);
INSERT INTO `web_access` VALUES ('1', '226', '2', '1', null);
INSERT INTO `web_access` VALUES ('1', '221', '2', '1', null);
INSERT INTO `web_access` VALUES ('1', '218', '2', '1', null);
INSERT INTO `web_access` VALUES ('1', '213', '2', '1', null);
INSERT INTO `web_access` VALUES ('1', '210', '2', '1', null);
INSERT INTO `web_access` VALUES ('1', '207', '2', '1', null);
INSERT INTO `web_access` VALUES ('1', '205', '2', '1', null);
INSERT INTO `web_access` VALUES ('1', '198', '2', '1', null);
INSERT INTO `web_access` VALUES ('1', '193', '2', '1', null);
INSERT INTO `web_access` VALUES ('1', '188', '2', '1', null);
INSERT INTO `web_access` VALUES ('1', '183', '2', '1', null);
INSERT INTO `web_access` VALUES ('1', '178', '2', '1', null);
INSERT INTO `web_access` VALUES ('1', '173', '2', '1', null);
INSERT INTO `web_access` VALUES ('1', '164', '2', '1', null);
INSERT INTO `web_access` VALUES ('1', '159', '2', '1', null);
INSERT INTO `web_access` VALUES ('1', '154', '2', '1', null);
INSERT INTO `web_access` VALUES ('1', '146', '2', '1', null);
INSERT INTO `web_access` VALUES ('1', '141', '2', '1', null);
INSERT INTO `web_access` VALUES ('1', '136', '2', '1', null);
INSERT INTO `web_access` VALUES ('1', '131', '2', '1', null);
INSERT INTO `web_access` VALUES ('1', '129', '2', '1', null);
INSERT INTO `web_access` VALUES ('1', '306', '3', '218', null);
INSERT INTO `web_access` VALUES ('1', '305', '3', '218', null);
INSERT INTO `web_access` VALUES ('1', '304', '3', '218', null);
INSERT INTO `web_access` VALUES ('1', '303', '3', '218', null);
INSERT INTO `web_access` VALUES ('1', '314', '3', '221', null);
INSERT INTO `web_access` VALUES ('1', '329', '3', '226', null);
INSERT INTO `web_access` VALUES ('1', '310', '3', '245', null);
INSERT INTO `web_access` VALUES ('1', '291', '3', '290', null);
INSERT INTO `web_access` VALUES ('1', '283', '3', '282', null);
INSERT INTO `web_access` VALUES ('1', '284', '3', '282', null);
INSERT INTO `web_access` VALUES ('1', '285', '3', '282', null);
INSERT INTO `web_access` VALUES ('1', '286', '3', '282', null);
INSERT INTO `web_access` VALUES ('1', '287', '3', '282', null);
INSERT INTO `web_access` VALUES ('1', '288', '3', '282', null);
INSERT INTO `web_access` VALUES ('1', '289', '3', '282', null);
INSERT INTO `web_access` VALUES ('1', '275', '3', '274', null);
INSERT INTO `web_access` VALUES ('1', '276', '3', '274', null);
INSERT INTO `web_access` VALUES ('1', '277', '3', '274', null);
INSERT INTO `web_access` VALUES ('1', '278', '3', '274', null);
INSERT INTO `web_access` VALUES ('1', '279', '3', '274', null);
INSERT INTO `web_access` VALUES ('1', '280', '3', '274', null);
INSERT INTO `web_access` VALUES ('1', '281', '3', '274', null);
INSERT INTO `web_access` VALUES ('1', '267', '3', '266', null);
INSERT INTO `web_access` VALUES ('1', '268', '3', '266', null);
INSERT INTO `web_access` VALUES ('1', '269', '3', '266', null);
INSERT INTO `web_access` VALUES ('1', '270', '3', '266', null);
INSERT INTO `web_access` VALUES ('1', '271', '3', '266', null);
INSERT INTO `web_access` VALUES ('1', '272', '3', '266', null);
INSERT INTO `web_access` VALUES ('1', '273', '3', '266', null);
INSERT INTO `web_access` VALUES ('1', '259', '3', '258', null);
INSERT INTO `web_access` VALUES ('1', '260', '3', '258', null);
INSERT INTO `web_access` VALUES ('1', '261', '3', '258', null);
INSERT INTO `web_access` VALUES ('1', '262', '3', '258', null);
INSERT INTO `web_access` VALUES ('1', '263', '3', '258', null);
INSERT INTO `web_access` VALUES ('1', '264', '3', '258', null);
INSERT INTO `web_access` VALUES ('1', '265', '3', '258', null);
INSERT INTO `web_access` VALUES ('1', '339', '3', '250', null);
INSERT INTO `web_access` VALUES ('1', '338', '3', '250', null);
INSERT INTO `web_access` VALUES ('1', '257', '3', '250', null);
INSERT INTO `web_access` VALUES ('1', '256', '3', '250', null);
INSERT INTO `web_access` VALUES ('1', '255', '3', '250', null);
INSERT INTO `web_access` VALUES ('1', '254', '3', '250', null);
INSERT INTO `web_access` VALUES ('1', '253', '3', '250', null);
INSERT INTO `web_access` VALUES ('1', '249', '3', '245', null);
INSERT INTO `web_access` VALUES ('1', '248', '3', '245', null);
INSERT INTO `web_access` VALUES ('1', '247', '3', '245', null);
INSERT INTO `web_access` VALUES ('1', '246', '3', '245', null);
INSERT INTO `web_access` VALUES ('1', '315', '3', '226', null);
INSERT INTO `web_access` VALUES ('1', '239', '3', '238', null);
INSERT INTO `web_access` VALUES ('1', '240', '3', '238', null);
INSERT INTO `web_access` VALUES ('1', '241', '3', '238', null);
INSERT INTO `web_access` VALUES ('1', '242', '3', '238', null);
INSERT INTO `web_access` VALUES ('1', '234', '3', '233', null);
INSERT INTO `web_access` VALUES ('1', '235', '3', '233', null);
INSERT INTO `web_access` VALUES ('1', '236', '3', '233', null);
INSERT INTO `web_access` VALUES ('1', '237', '3', '233', null);
INSERT INTO `web_access` VALUES ('1', '232', '3', '226', null);
INSERT INTO `web_access` VALUES ('1', '231', '3', '226', null);
INSERT INTO `web_access` VALUES ('1', '230', '3', '226', null);
INSERT INTO `web_access` VALUES ('1', '229', '3', '226', null);
INSERT INTO `web_access` VALUES ('1', '228', '3', '226', null);
INSERT INTO `web_access` VALUES ('1', '227', '3', '226', null);
INSERT INTO `web_access` VALUES ('1', '225', '3', '221', null);
INSERT INTO `web_access` VALUES ('1', '224', '3', '221', null);
INSERT INTO `web_access` VALUES ('1', '223', '3', '221', null);
INSERT INTO `web_access` VALUES ('1', '222', '3', '221', null);
INSERT INTO `web_access` VALUES ('1', '219', '3', '218', null);
INSERT INTO `web_access` VALUES ('1', '214', '3', '213', null);
INSERT INTO `web_access` VALUES ('1', '215', '3', '213', null);
INSERT INTO `web_access` VALUES ('1', '216', '3', '213', null);
INSERT INTO `web_access` VALUES ('1', '217', '3', '213', null);
INSERT INTO `web_access` VALUES ('1', '212', '3', '210', null);
INSERT INTO `web_access` VALUES ('1', '211', '3', '210', null);
INSERT INTO `web_access` VALUES ('1', '209', '3', '207', null);
INSERT INTO `web_access` VALUES ('1', '208', '3', '207', null);
INSERT INTO `web_access` VALUES ('1', '206', '3', '205', null);
INSERT INTO `web_access` VALUES ('1', '204', '3', '198', null);
INSERT INTO `web_access` VALUES ('1', '203', '3', '198', null);
INSERT INTO `web_access` VALUES ('1', '202', '3', '198', null);
INSERT INTO `web_access` VALUES ('1', '201', '3', '198', null);
INSERT INTO `web_access` VALUES ('1', '200', '3', '198', null);
INSERT INTO `web_access` VALUES ('1', '199', '3', '198', null);
INSERT INTO `web_access` VALUES ('15', '323', '2', '1', null);
INSERT INTO `web_access` VALUES ('15', '318', '2', '1', null);
INSERT INTO `web_access` VALUES ('15', '292', '2', '1', null);
INSERT INTO `web_access` VALUES ('15', '290', '2', '1', null);
INSERT INTO `web_access` VALUES ('15', '282', '2', '1', null);
INSERT INTO `web_access` VALUES ('15', '274', '2', '1', null);
INSERT INTO `web_access` VALUES ('15', '266', '2', '1', null);
INSERT INTO `web_access` VALUES ('15', '142', '3', '141', null);
INSERT INTO `web_access` VALUES ('15', '143', '3', '141', null);
INSERT INTO `web_access` VALUES ('15', '144', '3', '141', null);
INSERT INTO `web_access` VALUES ('15', '145', '3', '141', null);
INSERT INTO `web_access` VALUES ('15', '165', '3', '164', null);
INSERT INTO `web_access` VALUES ('15', '319', '3', '318', null);
INSERT INTO `web_access` VALUES ('15', '258', '2', '1', null);
INSERT INTO `web_access` VALUES ('15', '187', '3', '183', null);
INSERT INTO `web_access` VALUES ('15', '186', '3', '183', null);
INSERT INTO `web_access` VALUES ('15', '185', '3', '183', null);
INSERT INTO `web_access` VALUES ('15', '184', '3', '183', null);
INSERT INTO `web_access` VALUES ('15', '320', '3', '318', null);
INSERT INTO `web_access` VALUES ('15', '311', '3', '198', null);
INSERT INTO `web_access` VALUES ('15', '202', '3', '198', null);
INSERT INTO `web_access` VALUES ('15', '201', '3', '198', null);
INSERT INTO `web_access` VALUES ('15', '200', '3', '198', null);
INSERT INTO `web_access` VALUES ('15', '214', '3', '213', null);
INSERT INTO `web_access` VALUES ('15', '215', '3', '213', null);
INSERT INTO `web_access` VALUES ('15', '216', '3', '213', null);
INSERT INTO `web_access` VALUES ('15', '217', '3', '213', null);
INSERT INTO `web_access` VALUES ('15', '307', '3', '218', null);
INSERT INTO `web_access` VALUES ('15', '225', '3', '221', null);
INSERT INTO `web_access` VALUES ('15', '224', '3', '221', null);
INSERT INTO `web_access` VALUES ('15', '223', '3', '221', null);
INSERT INTO `web_access` VALUES ('15', '222', '3', '221', null);
INSERT INTO `web_access` VALUES ('15', '315', '3', '226', null);
INSERT INTO `web_access` VALUES ('15', '232', '3', '226', null);
INSERT INTO `web_access` VALUES ('15', '231', '3', '226', null);
INSERT INTO `web_access` VALUES ('15', '230', '3', '226', null);
INSERT INTO `web_access` VALUES ('15', '229', '3', '226', null);
INSERT INTO `web_access` VALUES ('15', '228', '3', '226', null);
INSERT INTO `web_access` VALUES ('15', '244', '3', '243', null);
INSERT INTO `web_access` VALUES ('15', '249', '3', '245', null);
INSERT INTO `web_access` VALUES ('15', '248', '3', '245', null);
INSERT INTO `web_access` VALUES ('15', '247', '3', '245', null);
INSERT INTO `web_access` VALUES ('15', '246', '3', '245', null);
INSERT INTO `web_access` VALUES ('15', '360', '3', '292', null);
INSERT INTO `web_access` VALUES ('15', '359', '3', '292', null);
INSERT INTO `web_access` VALUES ('15', '348', '3', '292', null);
INSERT INTO `web_access` VALUES ('15', '347', '3', '292', null);
INSERT INTO `web_access` VALUES ('15', '346', '3', '292', null);
INSERT INTO `web_access` VALUES ('15', '345', '3', '292', null);
INSERT INTO `web_access` VALUES ('15', '344', '3', '292', null);
INSERT INTO `web_access` VALUES ('15', '343', '3', '292', null);
INSERT INTO `web_access` VALUES ('15', '250', '2', '1', null);
INSERT INTO `web_access` VALUES ('15', '313', '3', '136', null);
INSERT INTO `web_access` VALUES ('15', '140', '3', '136', null);
INSERT INTO `web_access` VALUES ('15', '139', '3', '136', null);
INSERT INTO `web_access` VALUES ('15', '138', '3', '136', null);
INSERT INTO `web_access` VALUES ('15', '306', '3', '218', null);
INSERT INTO `web_access` VALUES ('15', '305', '3', '218', null);
INSERT INTO `web_access` VALUES ('15', '304', '3', '218', null);
INSERT INTO `web_access` VALUES ('15', '303', '3', '218', null);
INSERT INTO `web_access` VALUES ('15', '219', '3', '218', null);
INSERT INTO `web_access` VALUES ('2', '308', '3', '207', null);
INSERT INTO `web_access` VALUES ('2', '211', '3', '210', null);
INSERT INTO `web_access` VALUES ('1', '308', '3', '207', null);
INSERT INTO `web_access` VALUES ('1', '309', '3', '210', null);
INSERT INTO `web_access` VALUES ('1', '184', '3', '183', null);
INSERT INTO `web_access` VALUES ('1', '185', '3', '183', null);
INSERT INTO `web_access` VALUES ('1', '186', '3', '183', null);
INSERT INTO `web_access` VALUES ('1', '187', '3', '183', null);
INSERT INTO `web_access` VALUES ('1', '301', '3', '183', null);
INSERT INTO `web_access` VALUES ('1', '302', '3', '183', null);
INSERT INTO `web_access` VALUES ('15', '310', '3', '245', null);
INSERT INTO `web_access` VALUES ('15', '199', '3', '198', null);
INSERT INTO `web_access` VALUES ('15', '245', '2', '1', null);
INSERT INTO `web_access` VALUES ('15', '158', '3', '154', null);
INSERT INTO `web_access` VALUES ('15', '157', '3', '154', null);
INSERT INTO `web_access` VALUES ('15', '156', '3', '154', null);
INSERT INTO `web_access` VALUES ('15', '155', '3', '154', null);
INSERT INTO `web_access` VALUES ('15', '312', '3', '218', null);
INSERT INTO `web_access` VALUES ('15', '227', '3', '226', null);
INSERT INTO `web_access` VALUES ('15', '314', '3', '221', null);
INSERT INTO `web_access` VALUES ('15', '137', '3', '136', null);
INSERT INTO `web_access` VALUES ('15', '321', '3', '318', null);
INSERT INTO `web_access` VALUES ('15', '322', '3', '318', null);
INSERT INTO `web_access` VALUES ('15', '243', '2', '1', null);
INSERT INTO `web_access` VALUES ('15', '324', '3', '323', null);
INSERT INTO `web_access` VALUES ('15', '325', '3', '323', null);
INSERT INTO `web_access` VALUES ('15', '326', '3', '323', null);
INSERT INTO `web_access` VALUES ('15', '327', '3', '323', null);
INSERT INTO `web_access` VALUES ('15', '328', '3', '154', null);
INSERT INTO `web_access` VALUES ('15', '329', '3', '226', null);
INSERT INTO `web_access` VALUES ('14', '142', '3', '141', null);
INSERT INTO `web_access` VALUES ('14', '143', '3', '141', null);
INSERT INTO `web_access` VALUES ('14', '144', '3', '141', null);
INSERT INTO `web_access` VALUES ('14', '145', '3', '141', null);
INSERT INTO `web_access` VALUES ('15', '226', '2', '1', null);
INSERT INTO `web_access` VALUES ('15', '221', '2', '1', null);
INSERT INTO `web_access` VALUES ('15', '218', '2', '1', null);
INSERT INTO `web_access` VALUES ('15', '213', '2', '1', null);
INSERT INTO `web_access` VALUES ('15', '210', '2', '1', null);
INSERT INTO `web_access` VALUES ('15', '207', '2', '1', null);
INSERT INTO `web_access` VALUES ('15', '205', '2', '1', null);
INSERT INTO `web_access` VALUES ('15', '198', '2', '1', null);
INSERT INTO `web_access` VALUES ('15', '193', '2', '1', null);
INSERT INTO `web_access` VALUES ('15', '188', '2', '1', null);
INSERT INTO `web_access` VALUES ('15', '183', '2', '1', null);
INSERT INTO `web_access` VALUES ('15', '178', '2', '1', null);
INSERT INTO `web_access` VALUES ('15', '173', '2', '1', null);
INSERT INTO `web_access` VALUES ('15', '166', '2', '1', null);
INSERT INTO `web_access` VALUES ('15', '164', '2', '1', null);
INSERT INTO `web_access` VALUES ('15', '159', '2', '1', null);
INSERT INTO `web_access` VALUES ('15', '154', '2', '1', null);
INSERT INTO `web_access` VALUES ('15', '146', '2', '1', null);
INSERT INTO `web_access` VALUES ('15', '141', '2', '1', null);
INSERT INTO `web_access` VALUES ('15', '136', '2', '1', null);
INSERT INTO `web_access` VALUES ('15', '194', '3', '193', null);
INSERT INTO `web_access` VALUES ('15', '195', '3', '193', null);
INSERT INTO `web_access` VALUES ('15', '196', '3', '193', null);
INSERT INTO `web_access` VALUES ('15', '197', '3', '193', null);
INSERT INTO `web_access` VALUES ('15', '189', '3', '188', null);
INSERT INTO `web_access` VALUES ('15', '190', '3', '188', null);
INSERT INTO `web_access` VALUES ('15', '191', '3', '188', null);
INSERT INTO `web_access` VALUES ('15', '192', '3', '188', null);
INSERT INTO `web_access` VALUES ('15', '179', '3', '178', null);
INSERT INTO `web_access` VALUES ('15', '180', '3', '178', null);
INSERT INTO `web_access` VALUES ('15', '181', '3', '178', null);
INSERT INTO `web_access` VALUES ('15', '182', '3', '178', null);
INSERT INTO `web_access` VALUES ('15', '174', '3', '173', null);
INSERT INTO `web_access` VALUES ('15', '175', '3', '173', null);
INSERT INTO `web_access` VALUES ('15', '176', '3', '173', null);
INSERT INTO `web_access` VALUES ('15', '177', '3', '173', null);
INSERT INTO `web_access` VALUES ('2', '350', '3', '349', null);
INSERT INTO `web_access` VALUES ('15', '172', '3', '166', null);
INSERT INTO `web_access` VALUES ('15', '171', '3', '166', null);
INSERT INTO `web_access` VALUES ('15', '170', '3', '166', null);
INSERT INTO `web_access` VALUES ('15', '168', '3', '166', null);
INSERT INTO `web_access` VALUES ('15', '167', '3', '166', null);
INSERT INTO `web_access` VALUES ('15', '160', '3', '159', null);
INSERT INTO `web_access` VALUES ('15', '161', '3', '159', null);
INSERT INTO `web_access` VALUES ('15', '162', '3', '159', null);
INSERT INTO `web_access` VALUES ('15', '163', '3', '159', null);
INSERT INTO `web_access` VALUES ('15', '316', '3', '159', null);
INSERT INTO `web_access` VALUES ('15', '153', '3', '146', null);
INSERT INTO `web_access` VALUES ('15', '152', '3', '146', null);
INSERT INTO `web_access` VALUES ('15', '151', '3', '146', null);
INSERT INTO `web_access` VALUES ('15', '150', '3', '146', null);
INSERT INTO `web_access` VALUES ('15', '149', '3', '146', null);
INSERT INTO `web_access` VALUES ('15', '148', '3', '146', null);
INSERT INTO `web_access` VALUES ('15', '147', '3', '146', null);
INSERT INTO `web_access` VALUES ('15', '132', '3', '131', null);
INSERT INTO `web_access` VALUES ('15', '133', '3', '131', null);
INSERT INTO `web_access` VALUES ('15', '134', '3', '131', null);
INSERT INTO `web_access` VALUES ('15', '135', '3', '131', null);
INSERT INTO `web_access` VALUES ('15', '130', '3', '129', null);
INSERT INTO `web_access` VALUES ('15', '339', '3', '250', null);
INSERT INTO `web_access` VALUES ('15', '338', '3', '250', null);
INSERT INTO `web_access` VALUES ('15', '255', '3', '250', null);
INSERT INTO `web_access` VALUES ('15', '254', '3', '250', null);
INSERT INTO `web_access` VALUES ('15', '253', '3', '250', null);
INSERT INTO `web_access` VALUES ('15', '259', '3', '258', null);
INSERT INTO `web_access` VALUES ('15', '260', '3', '258', null);
INSERT INTO `web_access` VALUES ('15', '261', '3', '258', null);
INSERT INTO `web_access` VALUES ('15', '262', '3', '258', null);
INSERT INTO `web_access` VALUES ('15', '263', '3', '258', null);
INSERT INTO `web_access` VALUES ('15', '267', '3', '266', null);
INSERT INTO `web_access` VALUES ('15', '268', '3', '266', null);
INSERT INTO `web_access` VALUES ('15', '269', '3', '266', null);
INSERT INTO `web_access` VALUES ('15', '270', '3', '266', null);
INSERT INTO `web_access` VALUES ('15', '271', '3', '266', null);
INSERT INTO `web_access` VALUES ('15', '275', '3', '274', null);
INSERT INTO `web_access` VALUES ('15', '276', '3', '274', null);
INSERT INTO `web_access` VALUES ('15', '277', '3', '274', null);
INSERT INTO `web_access` VALUES ('15', '278', '3', '274', null);
INSERT INTO `web_access` VALUES ('15', '279', '3', '274', null);
INSERT INTO `web_access` VALUES ('15', '283', '3', '282', null);
INSERT INTO `web_access` VALUES ('15', '284', '3', '282', null);
INSERT INTO `web_access` VALUES ('15', '285', '3', '282', null);
INSERT INTO `web_access` VALUES ('15', '286', '3', '282', null);
INSERT INTO `web_access` VALUES ('15', '287', '3', '282', null);
INSERT INTO `web_access` VALUES ('15', '291', '3', '290', null);
INSERT INTO `web_access` VALUES ('14', '403', '3', '401', null);
INSERT INTO `web_access` VALUES ('14', '402', '3', '401', null);
INSERT INTO `web_access` VALUES ('14', '428', '3', '426', null);
INSERT INTO `web_access` VALUES ('19', '1', '1', '0', null);
INSERT INTO `web_access` VALUES ('14', '427', '3', '426', null);
INSERT INTO `web_access` VALUES ('14', '285', '3', '282', null);
INSERT INTO `web_access` VALUES ('14', '286', '3', '282', null);
INSERT INTO `web_access` VALUES ('14', '287', '3', '282', null);
INSERT INTO `web_access` VALUES ('14', '288', '3', '282', null);
INSERT INTO `web_access` VALUES ('14', '289', '3', '282', null);
INSERT INTO `web_access` VALUES ('14', '277', '3', '274', null);
INSERT INTO `web_access` VALUES ('14', '278', '3', '274', null);
INSERT INTO `web_access` VALUES ('14', '279', '3', '274', null);
INSERT INTO `web_access` VALUES ('14', '280', '3', '274', null);
INSERT INTO `web_access` VALUES ('14', '281', '3', '274', null);
INSERT INTO `web_access` VALUES ('14', '269', '3', '266', null);
INSERT INTO `web_access` VALUES ('14', '270', '3', '266', null);
INSERT INTO `web_access` VALUES ('14', '271', '3', '266', null);
INSERT INTO `web_access` VALUES ('14', '272', '3', '266', null);
INSERT INTO `web_access` VALUES ('14', '273', '3', '266', null);
INSERT INTO `web_access` VALUES ('14', '261', '3', '258', null);
INSERT INTO `web_access` VALUES ('14', '262', '3', '258', null);
INSERT INTO `web_access` VALUES ('14', '263', '3', '258', null);
INSERT INTO `web_access` VALUES ('14', '264', '3', '258', null);
INSERT INTO `web_access` VALUES ('14', '265', '3', '258', null);
INSERT INTO `web_access` VALUES ('14', '257', '3', '250', null);
INSERT INTO `web_access` VALUES ('14', '256', '3', '250', null);
INSERT INTO `web_access` VALUES ('14', '255', '3', '250', null);
INSERT INTO `web_access` VALUES ('14', '254', '3', '250', null);
INSERT INTO `web_access` VALUES ('14', '253', '3', '250', null);
INSERT INTO `web_access` VALUES ('14', '211', '3', '210', null);
INSERT INTO `web_access` VALUES ('14', '212', '3', '210', null);
INSERT INTO `web_access` VALUES ('14', '309', '3', '210', null);
INSERT INTO `web_access` VALUES ('14', '208', '3', '207', null);
INSERT INTO `web_access` VALUES ('14', '209', '3', '207', null);
INSERT INTO `web_access` VALUES ('14', '308', '3', '207', null);
INSERT INTO `web_access` VALUES ('14', '206', '3', '205', null);
INSERT INTO `web_access` VALUES ('14', '174', '3', '173', null);
INSERT INTO `web_access` VALUES ('14', '182', '3', '178', null);
INSERT INTO `web_access` VALUES ('14', '181', '3', '178', null);
INSERT INTO `web_access` VALUES ('14', '180', '3', '178', null);
INSERT INTO `web_access` VALUES ('14', '179', '3', '178', null);
INSERT INTO `web_access` VALUES ('14', '196', '3', '193', null);
INSERT INTO `web_access` VALUES ('14', '197', '3', '193', null);
INSERT INTO `web_access` VALUES ('14', '189', '3', '188', null);
INSERT INTO `web_access` VALUES ('14', '190', '3', '188', null);
INSERT INTO `web_access` VALUES ('14', '191', '3', '188', null);
INSERT INTO `web_access` VALUES ('14', '192', '3', '188', null);
INSERT INTO `web_access` VALUES ('14', '176', '3', '173', null);
INSERT INTO `web_access` VALUES ('14', '175', '3', '173', null);
INSERT INTO `web_access` VALUES ('14', '155', '3', '154', null);
INSERT INTO `web_access` VALUES ('14', '156', '3', '154', null);
INSERT INTO `web_access` VALUES ('14', '157', '3', '154', null);
INSERT INTO `web_access` VALUES ('14', '158', '3', '154', null);
INSERT INTO `web_access` VALUES ('14', '328', '3', '154', null);
INSERT INTO `web_access` VALUES ('14', '147', '3', '146', null);
INSERT INTO `web_access` VALUES ('14', '148', '3', '146', null);
INSERT INTO `web_access` VALUES ('14', '149', '3', '146', null);
INSERT INTO `web_access` VALUES ('14', '150', '3', '146', null);
INSERT INTO `web_access` VALUES ('14', '151', '3', '146', null);
INSERT INTO `web_access` VALUES ('14', '152', '3', '146', null);
INSERT INTO `web_access` VALUES ('14', '153', '3', '146', null);
INSERT INTO `web_access` VALUES ('13', '266', '2', '1', null);
INSERT INTO `web_access` VALUES ('13', '258', '2', '1', null);
INSERT INTO `web_access` VALUES ('13', '250', '2', '1', null);
INSERT INTO `web_access` VALUES ('13', '238', '2', '1', null);
INSERT INTO `web_access` VALUES ('13', '233', '2', '1', null);
INSERT INTO `web_access` VALUES ('13', '210', '2', '1', null);
INSERT INTO `web_access` VALUES ('13', '207', '2', '1', null);
INSERT INTO `web_access` VALUES ('13', '205', '2', '1', null);
INSERT INTO `web_access` VALUES ('13', '188', '2', '1', null);
INSERT INTO `web_access` VALUES ('13', '178', '2', '1', null);
INSERT INTO `web_access` VALUES ('13', '173', '2', '1', null);
INSERT INTO `web_access` VALUES ('13', '164', '2', '1', null);
INSERT INTO `web_access` VALUES ('13', '154', '2', '1', null);
INSERT INTO `web_access` VALUES ('13', '146', '2', '1', null);
INSERT INTO `web_access` VALUES ('13', '129', '2', '1', null);
INSERT INTO `web_access` VALUES ('13', '147', '3', '146', null);
INSERT INTO `web_access` VALUES ('13', '148', '3', '146', null);
INSERT INTO `web_access` VALUES ('13', '149', '3', '146', null);
INSERT INTO `web_access` VALUES ('13', '150', '3', '146', null);
INSERT INTO `web_access` VALUES ('13', '151', '3', '146', null);
INSERT INTO `web_access` VALUES ('13', '152', '3', '146', null);
INSERT INTO `web_access` VALUES ('13', '153', '3', '146', null);
INSERT INTO `web_access` VALUES ('13', '130', '3', '129', null);
INSERT INTO `web_access` VALUES ('13', '165', '3', '164', null);
INSERT INTO `web_access` VALUES ('13', '155', '3', '154', null);
INSERT INTO `web_access` VALUES ('13', '156', '3', '154', null);
INSERT INTO `web_access` VALUES ('13', '157', '3', '154', null);
INSERT INTO `web_access` VALUES ('13', '158', '3', '154', null);
INSERT INTO `web_access` VALUES ('13', '328', '3', '154', null);
INSERT INTO `web_access` VALUES ('13', '174', '3', '173', null);
INSERT INTO `web_access` VALUES ('13', '175', '3', '173', null);
INSERT INTO `web_access` VALUES ('13', '176', '3', '173', null);
INSERT INTO `web_access` VALUES ('13', '177', '3', '173', null);
INSERT INTO `web_access` VALUES ('13', '179', '3', '178', null);
INSERT INTO `web_access` VALUES ('13', '180', '3', '178', null);
INSERT INTO `web_access` VALUES ('13', '181', '3', '178', null);
INSERT INTO `web_access` VALUES ('13', '182', '3', '178', null);
INSERT INTO `web_access` VALUES ('13', '189', '3', '188', null);
INSERT INTO `web_access` VALUES ('13', '190', '3', '188', null);
INSERT INTO `web_access` VALUES ('13', '191', '3', '188', null);
INSERT INTO `web_access` VALUES ('13', '192', '3', '188', null);
INSERT INTO `web_access` VALUES ('13', '206', '3', '205', null);
INSERT INTO `web_access` VALUES ('13', '208', '3', '207', null);
INSERT INTO `web_access` VALUES ('13', '209', '3', '207', null);
INSERT INTO `web_access` VALUES ('13', '308', '3', '207', null);
INSERT INTO `web_access` VALUES ('13', '211', '3', '210', null);
INSERT INTO `web_access` VALUES ('13', '212', '3', '210', null);
INSERT INTO `web_access` VALUES ('13', '309', '3', '210', null);
INSERT INTO `web_access` VALUES ('13', '339', '3', '250', null);
INSERT INTO `web_access` VALUES ('13', '338', '3', '250', null);
INSERT INTO `web_access` VALUES ('13', '257', '3', '250', null);
INSERT INTO `web_access` VALUES ('13', '256', '3', '250', null);
INSERT INTO `web_access` VALUES ('13', '255', '3', '250', null);
INSERT INTO `web_access` VALUES ('13', '254', '3', '250', null);
INSERT INTO `web_access` VALUES ('13', '253', '3', '250', null);
INSERT INTO `web_access` VALUES ('13', '259', '3', '258', null);
INSERT INTO `web_access` VALUES ('13', '260', '3', '258', null);
INSERT INTO `web_access` VALUES ('13', '261', '3', '258', null);
INSERT INTO `web_access` VALUES ('13', '262', '3', '258', null);
INSERT INTO `web_access` VALUES ('13', '263', '3', '258', null);
INSERT INTO `web_access` VALUES ('13', '267', '3', '266', null);
INSERT INTO `web_access` VALUES ('13', '268', '3', '266', null);
INSERT INTO `web_access` VALUES ('13', '269', '3', '266', null);
INSERT INTO `web_access` VALUES ('13', '270', '3', '266', null);
INSERT INTO `web_access` VALUES ('13', '271', '3', '266', null);
INSERT INTO `web_access` VALUES ('13', '275', '3', '274', null);
INSERT INTO `web_access` VALUES ('13', '276', '3', '274', null);
INSERT INTO `web_access` VALUES ('13', '277', '3', '274', null);
INSERT INTO `web_access` VALUES ('13', '278', '3', '274', null);
INSERT INTO `web_access` VALUES ('13', '279', '3', '274', null);
INSERT INTO `web_access` VALUES ('13', '283', '3', '282', null);
INSERT INTO `web_access` VALUES ('13', '284', '3', '282', null);
INSERT INTO `web_access` VALUES ('13', '285', '3', '282', null);
INSERT INTO `web_access` VALUES ('13', '286', '3', '282', null);
INSERT INTO `web_access` VALUES ('13', '287', '3', '282', null);
INSERT INTO `web_access` VALUES ('13', '291', '3', '290', null);
INSERT INTO `web_access` VALUES ('15', '252', '3', '250', null);
INSERT INTO `web_access` VALUES ('14', '252', '3', '250', null);
INSERT INTO `web_access` VALUES ('13', '252', '3', '250', null);
INSERT INTO `web_access` VALUES ('2', '252', '3', '250', null);
INSERT INTO `web_access` VALUES ('1', '252', '3', '250', null);
INSERT INTO `web_access` VALUES ('1', '251', '3', '250', null);
INSERT INTO `web_access` VALUES ('2', '251', '3', '250', null);
INSERT INTO `web_access` VALUES ('13', '251', '3', '250', null);
INSERT INTO `web_access` VALUES ('14', '251', '3', '250', null);
INSERT INTO `web_access` VALUES ('15', '251', '3', '250', null);
INSERT INTO `web_access` VALUES ('15', '340', '3', '250', null);
INSERT INTO `web_access` VALUES ('2', '340', '3', '250', null);
INSERT INTO `web_access` VALUES ('1', '340', '3', '250', null);
INSERT INTO `web_access` VALUES ('15', '341', '3', '292', null);
INSERT INTO `web_access` VALUES ('15', '342', '3', '164', null);
INSERT INTO `web_access` VALUES ('14', '342', '3', '164', null);
INSERT INTO `web_access` VALUES ('1', '342', '3', '164', null);
INSERT INTO `web_access` VALUES ('15', '300', '3', '292', null);
INSERT INTO `web_access` VALUES ('15', '299', '3', '292', null);
INSERT INTO `web_access` VALUES ('15', '298', '3', '292', null);
INSERT INTO `web_access` VALUES ('15', '297', '3', '292', null);
INSERT INTO `web_access` VALUES ('15', '296', '3', '292', null);
INSERT INTO `web_access` VALUES ('15', '295', '3', '292', null);
INSERT INTO `web_access` VALUES ('14', '340', '3', '250', null);
INSERT INTO `web_access` VALUES ('13', '340', '3', '250', null);
INSERT INTO `web_access` VALUES ('2', '351', '3', '349', null);
INSERT INTO `web_access` VALUES ('2', '352', '3', '349', null);
INSERT INTO `web_access` VALUES ('2', '353', '3', '349', null);
INSERT INTO `web_access` VALUES ('2', '354', '3', '349', null);
INSERT INTO `web_access` VALUES ('2', '355', '3', '349', null);
INSERT INTO `web_access` VALUES ('14', '395', '3', '166', null);
INSERT INTO `web_access` VALUES ('14', '355', '3', '349', null);
INSERT INTO `web_access` VALUES ('14', '354', '3', '349', null);
INSERT INTO `web_access` VALUES ('14', '353', '3', '349', null);
INSERT INTO `web_access` VALUES ('14', '352', '3', '349', null);
INSERT INTO `web_access` VALUES ('14', '351', '3', '349', null);
INSERT INTO `web_access` VALUES ('14', '350', '3', '349', null);
INSERT INTO `web_access` VALUES ('15', '131', '2', '1', null);
INSERT INTO `web_access` VALUES ('15', '350', '3', '349', null);
INSERT INTO `web_access` VALUES ('15', '351', '3', '349', null);
INSERT INTO `web_access` VALUES ('15', '352', '3', '349', null);
INSERT INTO `web_access` VALUES ('15', '353', '3', '349', null);
INSERT INTO `web_access` VALUES ('15', '354', '3', '349', null);
INSERT INTO `web_access` VALUES ('15', '355', '3', '349', null);
INSERT INTO `web_access` VALUES ('14', '356', '3', '349', null);
INSERT INTO `web_access` VALUES ('2', '129', '2', '1', null);
INSERT INTO `web_access` VALUES ('2', '358', '3', '357', null);
INSERT INTO `web_access` VALUES ('15', '129', '2', '1', null);
INSERT INTO `web_access` VALUES ('15', '358', '3', '357', null);
INSERT INTO `web_access` VALUES ('14', '426', '2', '1', null);
INSERT INTO `web_access` VALUES ('14', '358', '3', '357', null);
INSERT INTO `web_access` VALUES ('15', '294', '3', '292', null);
INSERT INTO `web_access` VALUES ('15', '293', '3', '292', null);
INSERT INTO `web_access` VALUES ('2', '361', '2', '1', null);
INSERT INTO `web_access` VALUES ('2', '362', '3', '361', null);
INSERT INTO `web_access` VALUES ('1', '361', '2', '1', null);
INSERT INTO `web_access` VALUES ('1', '362', '3', '361', null);
INSERT INTO `web_access` VALUES ('1', '364', '3', '361', null);
INSERT INTO `web_access` VALUES ('13', '361', '2', '1', null);
INSERT INTO `web_access` VALUES ('13', '362', '3', '361', null);
INSERT INTO `web_access` VALUES ('14', '414', '2', '1', null);
INSERT INTO `web_access` VALUES ('14', '362', '3', '361', null);
INSERT INTO `web_access` VALUES ('15', '111', '2', '1', null);
INSERT INTO `web_access` VALUES ('15', '362', '3', '361', null);
INSERT INTO `web_access` VALUES ('15', '364', '3', '361', null);
INSERT INTO `web_access` VALUES ('15', '363', '3', '292', null);
INSERT INTO `web_access` VALUES ('15', '365', '2', '1', null);
INSERT INTO `web_access` VALUES ('15', '366', '3', '365', null);
INSERT INTO `web_access` VALUES ('14', '241', '3', '238', null);
INSERT INTO `web_access` VALUES ('14', '242', '3', '238', null);
INSERT INTO `web_access` VALUES ('14', '236', '3', '233', null);
INSERT INTO `web_access` VALUES ('14', '237', '3', '233', null);
INSERT INTO `web_access` VALUES ('16', '394', '1', '0', null);
INSERT INTO `web_access` VALUES ('16', '1', '1', '0', null);
INSERT INTO `web_access` VALUES ('16', '131', '2', '1', null);
INSERT INTO `web_access` VALUES ('16', '129', '2', '1', null);
INSERT INTO `web_access` VALUES ('16', '382', '3', '367', null);
INSERT INTO `web_access` VALUES ('16', '376', '3', '367', null);
INSERT INTO `web_access` VALUES ('16', '371', '3', '367', null);
INSERT INTO `web_access` VALUES ('16', '370', '3', '367', null);
INSERT INTO `web_access` VALUES ('16', '369', '3', '367', null);
INSERT INTO `web_access` VALUES ('16', '368', '3', '367', null);
INSERT INTO `web_access` VALUES ('16', '373', '3', '372', null);
INSERT INTO `web_access` VALUES ('16', '374', '3', '372', null);
INSERT INTO `web_access` VALUES ('16', '375', '3', '372', null);
INSERT INTO `web_access` VALUES ('16', '378', '3', '377', null);
INSERT INTO `web_access` VALUES ('16', '379', '3', '377', null);
INSERT INTO `web_access` VALUES ('16', '380', '3', '377', null);
INSERT INTO `web_access` VALUES ('16', '381', '3', '377', null);
INSERT INTO `web_access` VALUES ('16', '383', '3', '367', null);
INSERT INTO `web_access` VALUES ('16', '124', '2', '1', null);
INSERT INTO `web_access` VALUES ('16', '111', '2', '1', null);
INSERT INTO `web_access` VALUES ('16', '106', '2', '1', null);
INSERT INTO `web_access` VALUES ('16', '136', '2', '1', null);
INSERT INTO `web_access` VALUES ('16', '130', '3', '129', null);
INSERT INTO `web_access` VALUES ('16', '125', '3', '124', null);
INSERT INTO `web_access` VALUES ('16', '126', '3', '124', null);
INSERT INTO `web_access` VALUES ('16', '127', '3', '124', null);
INSERT INTO `web_access` VALUES ('16', '128', '3', '124', null);
INSERT INTO `web_access` VALUES ('16', '109', '3', '106', null);
INSERT INTO `web_access` VALUES ('16', '137', '3', '136', null);
INSERT INTO `web_access` VALUES ('16', '138', '3', '136', null);
INSERT INTO `web_access` VALUES ('16', '132', '3', '131', null);
INSERT INTO `web_access` VALUES ('16', '134', '3', '131', null);
INSERT INTO `web_access` VALUES ('14', '409', '2', '1', null);
INSERT INTO `web_access` VALUES ('14', '166', '2', '1', null);
INSERT INTO `web_access` VALUES ('14', '401', '2', '1', null);
INSERT INTO `web_access` VALUES ('14', '397', '3', '396', null);
INSERT INTO `web_access` VALUES ('14', '430', '3', '426', null);
INSERT INTO `web_access` VALUES ('14', '429', '3', '426', null);
INSERT INTO `web_access` VALUES ('14', '405', '3', '404', null);
INSERT INTO `web_access` VALUES ('14', '413', '3', '409', null);
INSERT INTO `web_access` VALUES ('14', '411', '3', '409', null);
INSERT INTO `web_access` VALUES ('14', '410', '3', '409', null);
INSERT INTO `web_access` VALUES ('14', '425', '3', '414', null);
INSERT INTO `web_access` VALUES ('14', '424', '3', '414', null);
INSERT INTO `web_access` VALUES ('14', '423', '3', '414', null);
INSERT INTO `web_access` VALUES ('14', '422', '3', '414', null);
INSERT INTO `web_access` VALUES ('14', '421', '3', '414', null);
INSERT INTO `web_access` VALUES ('14', '420', '3', '414', null);
INSERT INTO `web_access` VALUES ('14', '419', '3', '414', null);
INSERT INTO `web_access` VALUES ('14', '418', '3', '414', null);
INSERT INTO `web_access` VALUES ('14', '417', '3', '414', null);
INSERT INTO `web_access` VALUES ('14', '416', '3', '414', null);
INSERT INTO `web_access` VALUES ('14', '415', '3', '414', null);
INSERT INTO `web_access` VALUES ('14', '164', '2', '1', null);
INSERT INTO `web_access` VALUES ('11', '468', '2', '1', null);
INSERT INTO `web_access` VALUES ('11', '461', '2', '1', null);
INSERT INTO `web_access` VALUES ('11', '457', '2', '1', null);
INSERT INTO `web_access` VALUES ('11', '452', '3', '414', null);
INSERT INTO `web_access` VALUES ('11', '451', '3', '414', null);
INSERT INTO `web_access` VALUES ('11', '431', '3', '414', null);
INSERT INTO `web_access` VALUES ('11', '423', '3', '414', null);
INSERT INTO `web_access` VALUES ('11', '420', '3', '414', null);
INSERT INTO `web_access` VALUES ('11', '419', '3', '414', null);
INSERT INTO `web_access` VALUES ('11', '418', '3', '414', null);
INSERT INTO `web_access` VALUES ('11', '417', '3', '414', null);
INSERT INTO `web_access` VALUES ('11', '416', '3', '414', null);
INSERT INTO `web_access` VALUES ('11', '415', '3', '414', null);
INSERT INTO `web_access` VALUES ('14', '431', '3', '414', null);
INSERT INTO `web_access` VALUES ('11', '429', '3', '426', null);
INSERT INTO `web_access` VALUES ('11', '428', '3', '426', null);
INSERT INTO `web_access` VALUES ('11', '427', '3', '426', null);
INSERT INTO `web_access` VALUES ('17', '1', '1', '0', null);
INSERT INTO `web_access` VALUES ('17', '432', '2', '1', null);
INSERT INTO `web_access` VALUES ('17', '440', '2', '1', null);
INSERT INTO `web_access` VALUES ('18', '1', '1', '0', null);
INSERT INTO `web_access` VALUES ('17', '439', '3', '432', null);
INSERT INTO `web_access` VALUES ('17', '437', '3', '432', null);
INSERT INTO `web_access` VALUES ('17', '433', '3', '432', null);
INSERT INTO `web_access` VALUES ('17', '441', '3', '440', null);
INSERT INTO `web_access` VALUES ('17', '442', '3', '440', null);
INSERT INTO `web_access` VALUES ('17', '443', '3', '440', null);
INSERT INTO `web_access` VALUES ('17', '444', '3', '440', null);
INSERT INTO `web_access` VALUES ('18', '502', '3', '500', null);
INSERT INTO `web_access` VALUES ('18', '510', '2', '1', null);
INSERT INTO `web_access` VALUES ('18', '505', '2', '1', null);
INSERT INTO `web_access` VALUES ('18', '500', '2', '1', null);
INSERT INTO `web_access` VALUES ('18', '495', '2', '1', null);
INSERT INTO `web_access` VALUES ('18', '457', '2', '1', null);
INSERT INTO `web_access` VALUES ('18', '165', '3', '164', null);
INSERT INTO `web_access` VALUES ('18', '395', '3', '166', null);
INSERT INTO `web_access` VALUES ('18', '494', '3', '414', null);
INSERT INTO `web_access` VALUES ('18', '402', '3', '401', null);
INSERT INTO `web_access` VALUES ('19', '445', '3', '409', null);
INSERT INTO `web_access` VALUES ('18', '513', '3', '409', null);
INSERT INTO `web_access` VALUES ('18', '491', '3', '409', null);
INSERT INTO `web_access` VALUES ('18', '467', '3', '409', null);
INSERT INTO `web_access` VALUES ('18', '427', '3', '426', null);
INSERT INTO `web_access` VALUES ('18', '428', '3', '426', null);
INSERT INTO `web_access` VALUES ('18', '429', '3', '426', null);
INSERT INTO `web_access` VALUES ('18', '430', '3', '426', null);
INSERT INTO `web_access` VALUES ('18', '493', '3', '414', null);
INSERT INTO `web_access` VALUES ('18', '466', '3', '409', null);
INSERT INTO `web_access` VALUES ('18', '492', '3', '414', null);
INSERT INTO `web_access` VALUES ('18', '456', '3', '414', null);
INSERT INTO `web_access` VALUES ('18', '455', '3', '414', null);
INSERT INTO `web_access` VALUES ('18', '454', '3', '414', null);
INSERT INTO `web_access` VALUES ('19', '409', '2', '1', null);
INSERT INTO `web_access` VALUES ('19', '401', '2', '1', null);
INSERT INTO `web_access` VALUES ('19', '412', '3', '409', null);
INSERT INTO `web_access` VALUES ('19', '165', '3', '164', null);
INSERT INTO `web_access` VALUES ('19', '411', '3', '409', null);
INSERT INTO `web_access` VALUES ('19', '164', '2', '1', null);
INSERT INTO `web_access` VALUES ('18', '447', '3', '409', null);
INSERT INTO `web_access` VALUES ('19', '416', '3', '414', null);
INSERT INTO `web_access` VALUES ('19', '414', '2', '1', null);
INSERT INTO `web_access` VALUES ('19', '403', '3', '401', null);
INSERT INTO `web_access` VALUES ('18', '453', '3', '414', null);
INSERT INTO `web_access` VALUES ('18', '448', '2', '1', null);
INSERT INTO `web_access` VALUES ('18', '449', '3', '448', null);
INSERT INTO `web_access` VALUES ('18', '452', '3', '414', null);
INSERT INTO `web_access` VALUES ('18', '451', '3', '414', null);
INSERT INTO `web_access` VALUES ('18', '431', '3', '414', null);
INSERT INTO `web_access` VALUES ('18', '423', '3', '414', null);
INSERT INTO `web_access` VALUES ('18', '420', '3', '414', null);
INSERT INTO `web_access` VALUES ('20', '1', '1', '0', null);
INSERT INTO `web_access` VALUES ('18', '507', '3', '505', null);
INSERT INTO `web_access` VALUES ('18', '506', '3', '505', null);
INSERT INTO `web_access` VALUES ('20', '165', '3', '164', null);
INSERT INTO `web_access` VALUES ('18', '426', '2', '1', null);
INSERT INTO `web_access` VALUES ('20', '492', '3', '414', null);
INSERT INTO `web_access` VALUES ('20', '455', '3', '414', null);
INSERT INTO `web_access` VALUES ('20', '454', '3', '414', null);
INSERT INTO `web_access` VALUES ('20', '414', '2', '1', null);
INSERT INTO `web_access` VALUES ('20', '395', '3', '166', null);
INSERT INTO `web_access` VALUES ('18', '419', '3', '414', null);
INSERT INTO `web_access` VALUES ('18', '414', '2', '1', null);
INSERT INTO `web_access` VALUES ('18', '458', '3', '457', null);
INSERT INTO `web_access` VALUES ('18', '459', '3', '457', null);
INSERT INTO `web_access` VALUES ('18', '460', '3', '457', null);
INSERT INTO `web_access` VALUES ('11', '448', '2', '1', null);
INSERT INTO `web_access` VALUES ('11', '440', '2', '1', null);
INSERT INTO `web_access` VALUES ('11', '432', '2', '1', null);
INSERT INTO `web_access` VALUES ('11', '426', '2', '1', null);
INSERT INTO `web_access` VALUES ('11', '414', '2', '1', null);
INSERT INTO `web_access` VALUES ('11', '409', '2', '1', null);
INSERT INTO `web_access` VALUES ('11', '404', '2', '1', null);
INSERT INTO `web_access` VALUES ('11', '396', '2', '1', null);
INSERT INTO `web_access` VALUES ('11', '397', '3', '396', null);
INSERT INTO `web_access` VALUES ('11', '398', '3', '396', null);
INSERT INTO `web_access` VALUES ('11', '399', '3', '396', null);
INSERT INTO `web_access` VALUES ('11', '400', '3', '396', null);
INSERT INTO `web_access` VALUES ('11', '168', '3', '166', null);
INSERT INTO `web_access` VALUES ('11', '169', '3', '166', null);
INSERT INTO `web_access` VALUES ('11', '170', '3', '166', null);
INSERT INTO `web_access` VALUES ('11', '171', '3', '166', null);
INSERT INTO `web_access` VALUES ('11', '172', '3', '166', null);
INSERT INTO `web_access` VALUES ('11', '395', '3', '166', null);
INSERT INTO `web_access` VALUES ('11', '336', '3', '111', null);
INSERT INTO `web_access` VALUES ('11', '337', '3', '111', null);
INSERT INTO `web_access` VALUES ('11', '405', '3', '404', null);
INSERT INTO `web_access` VALUES ('11', '406', '3', '404', null);
INSERT INTO `web_access` VALUES ('11', '407', '3', '404', null);
INSERT INTO `web_access` VALUES ('11', '408', '3', '404', null);
INSERT INTO `web_access` VALUES ('11', '446', '3', '409', null);
INSERT INTO `web_access` VALUES ('11', '445', '3', '409', null);
INSERT INTO `web_access` VALUES ('11', '413', '3', '409', null);
INSERT INTO `web_access` VALUES ('11', '454', '3', '414', null);
INSERT INTO `web_access` VALUES ('11', '455', '3', '414', null);
INSERT INTO `web_access` VALUES ('11', '456', '3', '414', null);
INSERT INTO `web_access` VALUES ('11', '430', '3', '426', null);
INSERT INTO `web_access` VALUES ('11', '433', '3', '432', null);
INSERT INTO `web_access` VALUES ('11', '437', '3', '432', null);
INSERT INTO `web_access` VALUES ('11', '438', '3', '432', null);
INSERT INTO `web_access` VALUES ('11', '439', '3', '432', null);
INSERT INTO `web_access` VALUES ('11', '441', '3', '440', null);
INSERT INTO `web_access` VALUES ('11', '442', '3', '440', null);
INSERT INTO `web_access` VALUES ('11', '443', '3', '440', null);
INSERT INTO `web_access` VALUES ('11', '444', '3', '440', null);
INSERT INTO `web_access` VALUES ('11', '449', '3', '448', null);
INSERT INTO `web_access` VALUES ('11', '458', '3', '457', null);
INSERT INTO `web_access` VALUES ('11', '459', '3', '457', null);
INSERT INTO `web_access` VALUES ('11', '460', '3', '457', null);
INSERT INTO `web_access` VALUES ('11', '401', '2', '1', null);
INSERT INTO `web_access` VALUES ('11', '462', '3', '461', null);
INSERT INTO `web_access` VALUES ('11', '463', '3', '461', null);
INSERT INTO `web_access` VALUES ('11', '464', '3', '461', null);
INSERT INTO `web_access` VALUES ('11', '465', '3', '461', null);
INSERT INTO `web_access` VALUES ('11', '412', '3', '409', null);
INSERT INTO `web_access` VALUES ('11', '410', '3', '409', null);
INSERT INTO `web_access` VALUES ('18', '446', '3', '409', null);
INSERT INTO `web_access` VALUES ('18', '445', '3', '409', null);
INSERT INTO `web_access` VALUES ('18', '413', '3', '409', null);
INSERT INTO `web_access` VALUES ('21', '1', '1', '0', null);
INSERT INTO `web_access` VALUES ('21', '457', '2', '1', null);
INSERT INTO `web_access` VALUES ('21', '440', '2', '1', null);
INSERT INTO `web_access` VALUES ('21', '432', '2', '1', null);
INSERT INTO `web_access` VALUES ('21', '164', '2', '1', null);
INSERT INTO `web_access` VALUES ('21', '165', '3', '164', null);
INSERT INTO `web_access` VALUES ('21', '474', '3', '473', null);
INSERT INTO `web_access` VALUES ('21', '475', '3', '473', null);
INSERT INTO `web_access` VALUES ('21', '476', '3', '473', null);
INSERT INTO `web_access` VALUES ('21', '480', '3', '479', null);
INSERT INTO `web_access` VALUES ('21', '481', '3', '479', null);
INSERT INTO `web_access` VALUES ('21', '482', '3', '479', null);
INSERT INTO `web_access` VALUES ('21', '486', '3', '485', null);
INSERT INTO `web_access` VALUES ('21', '487', '3', '485', null);
INSERT INTO `web_access` VALUES ('21', '488', '3', '485', null);
INSERT INTO `web_access` VALUES ('11', '166', '2', '1', null);
INSERT INTO `web_access` VALUES ('11', '164', '2', '1', null);
INSERT INTO `web_access` VALUES ('11', '111', '2', '1', null);
INSERT INTO `web_access` VALUES ('11', '106', '2', '1', null);
INSERT INTO `web_access` VALUES ('11', '469', '3', '468', null);
INSERT INTO `web_access` VALUES ('11', '470', '3', '468', null);
INSERT INTO `web_access` VALUES ('11', '471', '3', '468', null);
INSERT INTO `web_access` VALUES ('11', '472', '3', '468', null);
INSERT INTO `web_access` VALUES ('11', '477', '3', '473', null);
INSERT INTO `web_access` VALUES ('11', '476', '3', '473', null);
INSERT INTO `web_access` VALUES ('11', '475', '3', '473', null);
INSERT INTO `web_access` VALUES ('11', '474', '3', '473', null);
INSERT INTO `web_access` VALUES ('11', '478', '3', '473', null);
INSERT INTO `web_access` VALUES ('11', '480', '3', '479', null);
INSERT INTO `web_access` VALUES ('11', '481', '3', '479', null);
INSERT INTO `web_access` VALUES ('11', '482', '3', '479', null);
INSERT INTO `web_access` VALUES ('11', '483', '3', '479', null);
INSERT INTO `web_access` VALUES ('11', '484', '3', '479', null);
INSERT INTO `web_access` VALUES ('11', '486', '3', '485', null);
INSERT INTO `web_access` VALUES ('11', '487', '3', '485', null);
INSERT INTO `web_access` VALUES ('11', '488', '3', '485', null);
INSERT INTO `web_access` VALUES ('11', '489', '3', '485', null);
INSERT INTO `web_access` VALUES ('11', '490', '3', '485', null);
INSERT INTO `web_access` VALUES ('22', '1', '1', '0', null);
INSERT INTO `web_access` VALUES ('22', '510', '2', '1', null);
INSERT INTO `web_access` VALUES ('22', '505', '2', '1', null);
INSERT INTO `web_access` VALUES ('22', '500', '2', '1', null);
INSERT INTO `web_access` VALUES ('22', '485', '2', '1', null);
INSERT INTO `web_access` VALUES ('22', '479', '2', '1', null);
INSERT INTO `web_access` VALUES ('22', '473', '2', '1', null);
INSERT INTO `web_access` VALUES ('22', '468', '2', '1', null);
INSERT INTO `web_access` VALUES ('22', '461', '2', '1', null);
INSERT INTO `web_access` VALUES ('22', '457', '2', '1', null);
INSERT INTO `web_access` VALUES ('22', '440', '2', '1', null);
INSERT INTO `web_access` VALUES ('22', '432', '2', '1', null);
INSERT INTO `web_access` VALUES ('22', '426', '2', '1', null);
INSERT INTO `web_access` VALUES ('22', '414', '2', '1', null);
INSERT INTO `web_access` VALUES ('22', '409', '2', '1', null);
INSERT INTO `web_access` VALUES ('21', '461', '2', '1', null);
INSERT INTO `web_access` VALUES ('21', '473', '2', '1', null);
INSERT INTO `web_access` VALUES ('21', '479', '2', '1', null);
INSERT INTO `web_access` VALUES ('21', '485', '2', '1', null);
INSERT INTO `web_access` VALUES ('22', '404', '2', '1', null);
INSERT INTO `web_access` VALUES ('22', '480', '3', '479', null);
INSERT INTO `web_access` VALUES ('22', '481', '3', '479', null);
INSERT INTO `web_access` VALUES ('22', '482', '3', '479', null);
INSERT INTO `web_access` VALUES ('22', '474', '3', '473', null);
INSERT INTO `web_access` VALUES ('22', '475', '3', '473', null);
INSERT INTO `web_access` VALUES ('22', '476', '3', '473', null);
INSERT INTO `web_access` VALUES ('22', '460', '3', '457', null);
INSERT INTO `web_access` VALUES ('22', '459', '3', '457', null);
INSERT INTO `web_access` VALUES ('22', '458', '3', '457', null);
INSERT INTO `web_access` VALUES ('22', '441', '3', '440', null);
INSERT INTO `web_access` VALUES ('22', '442', '3', '440', null);
INSERT INTO `web_access` VALUES ('22', '443', '3', '440', null);
INSERT INTO `web_access` VALUES ('22', '444', '3', '440', null);
INSERT INTO `web_access` VALUES ('22', '433', '3', '432', null);
INSERT INTO `web_access` VALUES ('22', '437', '3', '432', null);
INSERT INTO `web_access` VALUES ('22', '438', '3', '432', null);
INSERT INTO `web_access` VALUES ('22', '439', '3', '432', null);
INSERT INTO `web_access` VALUES ('22', '427', '3', '426', null);
INSERT INTO `web_access` VALUES ('22', '428', '3', '426', null);
INSERT INTO `web_access` VALUES ('22', '429', '3', '426', null);
INSERT INTO `web_access` VALUES ('22', '430', '3', '426', null);
INSERT INTO `web_access` VALUES ('18', '409', '2', '1', null);
INSERT INTO `web_access` VALUES ('22', '455', '3', '414', null);
INSERT INTO `web_access` VALUES ('22', '454', '3', '414', null);
INSERT INTO `web_access` VALUES ('22', '453', '3', '414', null);
INSERT INTO `web_access` VALUES ('22', '452', '3', '414', null);
INSERT INTO `web_access` VALUES ('22', '451', '3', '414', null);
INSERT INTO `web_access` VALUES ('22', '492', '3', '414', null);
INSERT INTO `web_access` VALUES ('22', '431', '3', '414', null);
INSERT INTO `web_access` VALUES ('22', '493', '3', '414', null);
INSERT INTO `web_access` VALUES ('22', '423', '3', '414', null);
INSERT INTO `web_access` VALUES ('22', '494', '3', '414', null);
INSERT INTO `web_access` VALUES ('22', '420', '3', '414', null);
INSERT INTO `web_access` VALUES ('22', '419', '3', '414', null);
INSERT INTO `web_access` VALUES ('22', '418', '3', '414', null);
INSERT INTO `web_access` VALUES ('22', '416', '3', '414', null);
INSERT INTO `web_access` VALUES ('22', '491', '3', '409', null);
INSERT INTO `web_access` VALUES ('22', '467', '3', '409', null);
INSERT INTO `web_access` VALUES ('22', '466', '3', '409', null);
INSERT INTO `web_access` VALUES ('22', '447', '3', '409', null);
INSERT INTO `web_access` VALUES ('22', '446', '3', '409', null);
INSERT INTO `web_access` VALUES ('22', '445', '3', '409', null);
INSERT INTO `web_access` VALUES ('22', '413', '3', '409', null);
INSERT INTO `web_access` VALUES ('22', '410', '3', '409', null);
INSERT INTO `web_access` VALUES ('22', '405', '3', '404', null);
INSERT INTO `web_access` VALUES ('22', '406', '3', '404', null);
INSERT INTO `web_access` VALUES ('22', '407', '3', '404', null);
INSERT INTO `web_access` VALUES ('22', '408', '3', '404', null);
INSERT INTO `web_access` VALUES ('22', '397', '3', '396', null);
INSERT INTO `web_access` VALUES ('22', '398', '3', '396', null);
INSERT INTO `web_access` VALUES ('22', '399', '3', '396', null);
INSERT INTO `web_access` VALUES ('22', '400', '3', '396', null);
INSERT INTO `web_access` VALUES ('22', '403', '3', '401', null);
INSERT INTO `web_access` VALUES ('22', '402', '3', '401', null);
INSERT INTO `web_access` VALUES ('22', '165', '3', '164', null);
INSERT INTO `web_access` VALUES ('22', '486', '3', '485', null);
INSERT INTO `web_access` VALUES ('22', '487', '3', '485', null);
INSERT INTO `web_access` VALUES ('22', '488', '3', '485', null);
INSERT INTO `web_access` VALUES ('23', '1', '1', '0', null);
INSERT INTO `web_access` VALUES ('23', '485', '2', '1', null);
INSERT INTO `web_access` VALUES ('23', '479', '2', '1', null);
INSERT INTO `web_access` VALUES ('23', '473', '2', '1', null);
INSERT INTO `web_access` VALUES ('23', '469', '3', '468', null);
INSERT INTO `web_access` VALUES ('23', '470', '3', '468', null);
INSERT INTO `web_access` VALUES ('23', '471', '3', '468', null);
INSERT INTO `web_access` VALUES ('23', '472', '3', '468', null);
INSERT INTO `web_access` VALUES ('23', '484', '3', '479', null);
INSERT INTO `web_access` VALUES ('23', '483', '3', '479', null);
INSERT INTO `web_access` VALUES ('23', '489', '3', '485', null);
INSERT INTO `web_access` VALUES ('23', '490', '3', '485', null);
INSERT INTO `web_access` VALUES ('23', '468', '2', '1', null);
INSERT INTO `web_access` VALUES ('23', '474', '3', '473', null);
INSERT INTO `web_access` VALUES ('23', '475', '3', '473', null);
INSERT INTO `web_access` VALUES ('23', '476', '3', '473', null);
INSERT INTO `web_access` VALUES ('23', '477', '3', '473', null);
INSERT INTO `web_access` VALUES ('23', '478', '3', '473', null);
INSERT INTO `web_access` VALUES ('18', '417', '3', '414', null);
INSERT INTO `web_access` VALUES ('18', '496', '3', '495', null);
INSERT INTO `web_access` VALUES ('18', '497', '3', '495', null);
INSERT INTO `web_access` VALUES ('18', '498', '3', '495', null);
INSERT INTO `web_access` VALUES ('18', '415', '3', '414', null);
INSERT INTO `web_access` VALUES ('22', '499', '3', '401', null);
INSERT INTO `web_access` VALUES ('22', '415', '3', '414', null);
INSERT INTO `web_access` VALUES ('22', '456', '3', '414', null);
INSERT INTO `web_access` VALUES ('22', '396', '2', '1', null);
INSERT INTO `web_access` VALUES ('22', '501', '3', '500', null);
INSERT INTO `web_access` VALUES ('22', '502', '3', '500', null);
INSERT INTO `web_access` VALUES ('22', '503', '3', '500', null);
INSERT INTO `web_access` VALUES ('22', '504', '3', '500', null);
INSERT INTO `web_access` VALUES ('20', '166', '2', '1', null);
INSERT INTO `web_access` VALUES ('20', '410', '3', '409', null);
INSERT INTO `web_access` VALUES ('20', '413', '3', '409', null);
INSERT INTO `web_access` VALUES ('20', '445', '3', '409', null);
INSERT INTO `web_access` VALUES ('20', '446', '3', '409', null);
INSERT INTO `web_access` VALUES ('20', '447', '3', '409', null);
INSERT INTO `web_access` VALUES ('20', '466', '3', '409', null);
INSERT INTO `web_access` VALUES ('20', '467', '3', '409', null);
INSERT INTO `web_access` VALUES ('20', '491', '3', '409', null);
INSERT INTO `web_access` VALUES ('20', '164', '2', '1', null);
INSERT INTO `web_access` VALUES ('20', '486', '3', '485', null);
INSERT INTO `web_access` VALUES ('20', '487', '3', '485', null);
INSERT INTO `web_access` VALUES ('20', '488', '3', '485', null);
INSERT INTO `web_access` VALUES ('20', '480', '3', '479', null);
INSERT INTO `web_access` VALUES ('20', '481', '3', '479', null);
INSERT INTO `web_access` VALUES ('20', '482', '3', '479', null);
INSERT INTO `web_access` VALUES ('20', '453', '3', '414', null);
INSERT INTO `web_access` VALUES ('20', '431', '3', '414', null);
INSERT INTO `web_access` VALUES ('20', '423', '3', '414', null);
INSERT INTO `web_access` VALUES ('20', '419', '3', '414', null);
INSERT INTO `web_access` VALUES ('20', '415', '3', '414', null);
INSERT INTO `web_access` VALUES ('20', '494', '3', '414', null);
INSERT INTO `web_access` VALUES ('18', '508', '3', '505', null);
INSERT INTO `web_access` VALUES ('18', '509', '3', '505', null);
INSERT INTO `web_access` VALUES ('18', '401', '2', '1', null);
INSERT INTO `web_access` VALUES ('18', '511', '3', '510', null);
INSERT INTO `web_access` VALUES ('22', '401', '2', '1', null);
INSERT INTO `web_access` VALUES ('22', '511', '3', '510', null);
INSERT INTO `web_access` VALUES ('23', '512', '3', '479', null);
INSERT INTO `web_access` VALUES ('18', '410', '3', '409', null);
INSERT INTO `web_access` VALUES ('18', '166', '2', '1', null);
INSERT INTO `web_access` VALUES ('18', '164', '2', '1', null);
INSERT INTO `web_access` VALUES ('18', '501', '3', '500', null);
INSERT INTO `web_access` VALUES ('18', '503', '3', '500', null);
INSERT INTO `web_access` VALUES ('18', '504', '3', '500', null);
INSERT INTO `web_access` VALUES ('18', '514', '2', '1', null);
INSERT INTO `web_access` VALUES ('18', '515', '3', '514', null);
INSERT INTO `web_access` VALUES ('22', '164', '2', '1', null);
INSERT INTO `web_access` VALUES ('22', '515', '3', '514', null);
INSERT INTO `web_access` VALUES ('18', '521', '3', '409', null);
INSERT INTO `web_access` VALUES ('22', '513', '3', '409', null);
INSERT INTO `web_access` VALUES ('22', '521', '3', '409', null);
INSERT INTO `web_access` VALUES ('22', '514', '2', '1', null);
INSERT INTO `web_access` VALUES ('22', '506', '3', '505', null);
INSERT INTO `web_access` VALUES ('22', '507', '3', '505', null);
INSERT INTO `web_access` VALUES ('22', '508', '3', '505', null);
INSERT INTO `web_access` VALUES ('22', '509', '3', '505', null);
INSERT INTO `web_access` VALUES ('11', '523', '2', '1', null);
INSERT INTO `web_access` VALUES ('11', '524', '3', '523', null);
INSERT INTO `web_access` VALUES ('11', '525', '3', '523', null);
INSERT INTO `web_access` VALUES ('18', '526', '3', '414', null);

-- ----------------------------
-- Table structure for web_access_table
-- ----------------------------
DROP TABLE IF EXISTS `web_access_table`;
CREATE TABLE `web_access_table` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `manager_access` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_access_table
-- ----------------------------
INSERT INTO `web_access_table` VALUES ('1', 'web_dwFenbao', 'income,income_split,tariff,tariff,channel_cost,remark,deal_date,hainiu_income,channel_income', '1,413,415');

-- ----------------------------
-- Table structure for web_account_limit
-- ----------------------------
DROP TABLE IF EXISTS `web_account_limit`;
CREATE TABLE `web_account_limit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `operator` varchar(25) NOT NULL,
  `addtime` datetime NOT NULL,
  `reason` text NOT NULL,
  `account` varchar(25) NOT NULL,
  `type` tinyint(4) NOT NULL COMMENT '账号操作状态\r\n1.封号\r\n2.解号',
  `status` char(1) NOT NULL COMMENT '账号目前状态\r\n1.封号\r\n2.解号',
  `server_id` smallint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=300 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_account_limit
-- ----------------------------

-- ----------------------------
-- Table structure for web_add_good
-- ----------------------------
DROP TABLE IF EXISTS `web_add_good`;
CREATE TABLE `web_add_good` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL,
  `itemtype_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `amount_limit` int(11) NOT NULL DEFAULT '0',
  `sql_string_1` varchar(500) CHARACTER SET gbk NOT NULL DEFAULT '',
  `sql_string_2` varchar(500) CHARACTER SET gbk NOT NULL DEFAULT '',
  `sql_string_3` varchar(500) CHARACTER SET gbk NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `game_id` (`game_id`,`itemtype_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1229 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_add_good
-- ----------------------------

-- ----------------------------
-- Table structure for web_category
-- ----------------------------
DROP TABLE IF EXISTS `web_category`;
CREATE TABLE `web_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `en_name` varchar(30) NOT NULL,
  `seo_title` varchar(50) NOT NULL,
  `seo_keyword` varchar(100) NOT NULL,
  `seo_desc` varchar(150) NOT NULL,
  `sort` smallint(2) NOT NULL,
  `status` char(1) NOT NULL DEFAULT '1',
  `is_index` char(1) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_category
-- ----------------------------

-- ----------------------------
-- Table structure for web_channel
-- ----------------------------
DROP TABLE IF EXISTS `web_channel`;
CREATE TABLE `web_channel` (
  `id` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `cid` smallint(4) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_channel
-- ----------------------------

-- ----------------------------
-- Table structure for web_code_exchange
-- ----------------------------
DROP TABLE IF EXISTS `web_code_exchange`;
CREATE TABLE `web_code_exchange` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `code_id` varchar(63) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `type` int(4) unsigned NOT NULL DEFAULT '0',
  `param` int(11) unsigned NOT NULL DEFAULT '0',
  `account_id` int(4) unsigned NOT NULL DEFAULT '0',
  `time_stamp` int(4) unsigned NOT NULL DEFAULT '0',
  `used` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `used_time_stamp` int(4) unsigned NOT NULL DEFAULT '0',
  `time_limit` int(11) DEFAULT '0',
  `game_type` int(11) DEFAULT '0',
  `register_type` int(11) DEFAULT '0',
  `register_time` int(11) DEFAULT '0',
  `used_type` int(11) DEFAULT '0',
  `is_limit_one` tinyint(1) DEFAULT '0',
  `number` smallint(4) unsigned DEFAULT '1' COMMENT '兑换次数',
  `number_used` smallint(4) unsigned DEFAULT '0',
  `dwFenBaoID` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_id` (`code_id`),
  KEY `account_id` (`account_id`),
  KEY `user_type` (`used_type`)
) ENGINE=MyISAM AUTO_INCREMENT=7962790 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_code_exchange
-- ----------------------------

-- ----------------------------
-- Table structure for web_code_exchange_log
-- ----------------------------
DROP TABLE IF EXISTS `web_code_exchange_log`;
CREATE TABLE `web_code_exchange_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `code_id` varchar(50) NOT NULL,
  `user_time` int(4) NOT NULL,
  `account_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `accountAndCode` (`account_id`,`code_id`)
) ENGINE=MyISAM AUTO_INCREMENT=96223 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_code_exchange_log
-- ----------------------------

-- ----------------------------
-- Table structure for web_compensate_log
-- ----------------------------
DROP TABLE IF EXISTS `web_compensate_log`;
CREATE TABLE `web_compensate_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `game_id` smallint(4) unsigned NOT NULL DEFAULT '0',
  `server_id` text NOT NULL,
  `index_id` smallint(4) NOT NULL DEFAULT '0',
  `begin_time` int(4) unsigned NOT NULL DEFAULT '0',
  `end_time` int(4) unsigned NOT NULL DEFAULT '0',
  `role_begin_time` int(4) unsigned NOT NULL DEFAULT '0',
  `role_end_time` int(4) unsigned NOT NULL DEFAULT '0',
  `level_min` int(4) unsigned NOT NULL DEFAULT '0',
  `level_max` int(4) unsigned NOT NULL DEFAULT '0',
  `message` varchar(200) NOT NULL,
  `addtime` int(4) unsigned NOT NULL DEFAULT '0',
  `operator` varchar(30) NOT NULL,
  `verify_level` smallint(4) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `type1` smallint(4) unsigned NOT NULL,
  `param1` int(11) unsigned DEFAULT '0',
  `amount1` int(11) DEFAULT '0',
  `type2` smallint(4) unsigned DEFAULT NULL,
  `param2` int(11) unsigned DEFAULT '0',
  `amount2` int(11) DEFAULT '0',
  `type3` smallint(4) unsigned DEFAULT NULL,
  `param3` int(11) unsigned DEFAULT '0',
  `amount3` int(11) unsigned DEFAULT '0',
  `type4` smallint(4) unsigned DEFAULT NULL,
  `param4` int(11) unsigned DEFAULT '0',
  `amount4` int(11) unsigned DEFAULT '0',
  `remark` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=81 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_compensate_log
-- ----------------------------

-- ----------------------------
-- Table structure for web_config
-- ----------------------------
DROP TABLE IF EXISTS `web_config`;
CREATE TABLE `web_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `TWD` decimal(6,4) DEFAULT NULL,
  `USD` decimal(6,4) DEFAULT NULL,
  `VND` decimal(6,4) DEFAULT NULL,
  `openbt` int(11) DEFAULT '0',
  `RUB` decimal(6,0) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_config
-- ----------------------------
INSERT INTO `web_config` VALUES ('1', '4.4289', '6.9195', '0.0003', '0', null);

-- ----------------------------
-- Table structure for web_dwFenbao
-- ----------------------------
DROP TABLE IF EXISTS `web_dwFenbao`;
CREATE TABLE `web_dwFenbao` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `fenbao_id` int(4) unsigned NOT NULL DEFAULT '0',
  `income` varchar(100) DEFAULT '0',
  `income_split` varchar(100) DEFAULT NULL,
  `tariff` tinyint(1) DEFAULT '0',
  `channel_cost` tinyint(1) DEFAULT '0',
  `remark` varchar(255) DEFAULT NULL,
  `deal_date` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fenbao` (`fenbao_id`)
) ENGINE=MyISAM AUTO_INCREMENT=85 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_dwFenbao
-- ----------------------------
INSERT INTO `web_dwFenbao` VALUES ('71', '魔方IOS', '910001', '0', null, '0', '0', null, null);
INSERT INTO `web_dwFenbao` VALUES ('72', '魔方安卓', '655001', '0', null, '0', '0', null, null);
INSERT INTO `web_dwFenbao` VALUES ('77', 'ios企业包', '910002', '0', null, '0', '0', null, null);
INSERT INTO `web_dwFenbao` VALUES ('78', '官方充值', '999999', '0', null, '0', '0', null, null);
INSERT INTO `web_dwFenbao` VALUES ('79', '越南ios1', '911001', '0', null, '0', '0', null, null);
INSERT INTO `web_dwFenbao` VALUES ('80', '越南安卓1', '669001', '0', null, '0', '0', null, null);
INSERT INTO `web_dwFenbao` VALUES ('81', '越南安卓2', '669004', '0', null, '0', '0', null, null);
INSERT INTO `web_dwFenbao` VALUES ('82', '越南安卓3', '669005', '0', null, '0', '0', null, null);
INSERT INTO `web_dwFenbao` VALUES ('83', '越南安卓4', '669006', '0', null, '0', '0', null, null);

-- ----------------------------
-- Table structure for web_emp_account
-- ----------------------------
DROP TABLE IF EXISTS `web_emp_account`;
CREATE TABLE `web_emp_account` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `accountid` bigint(11) NOT NULL,
  `name` varchar(50) NOT NULL COMMENT '角色名',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk` (`accountid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_emp_account
-- ----------------------------
INSERT INTO `web_emp_account` VALUES ('1', '1', '的');

-- ----------------------------
-- Table structure for web_erp_level
-- ----------------------------
DROP TABLE IF EXISTS `web_erp_level`;
CREATE TABLE `web_erp_level` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `level` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_erp_level
-- ----------------------------
INSERT INTO `web_erp_level` VALUES ('1', '一级', '0');
INSERT INTO `web_erp_level` VALUES ('2', '二级', '1');

-- ----------------------------
-- Table structure for web_erp_log
-- ----------------------------
DROP TABLE IF EXISTS `web_erp_log`;
CREATE TABLE `web_erp_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `model` varchar(30) NOT NULL,
  `verify_time` int(4) NOT NULL DEFAULT '0',
  `operator` varchar(30) NOT NULL,
  `pid` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=972 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of web_erp_log
-- ----------------------------

-- ----------------------------
-- Table structure for web_game
-- ----------------------------
DROP TABLE IF EXISTS `web_game`;
CREATE TABLE `web_game` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `game_id` int(10) unsigned NOT NULL,
  `game_name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_game
-- ----------------------------
INSERT INTO `web_game` VALUES ('1', '7', '合金弹头');
INSERT INTO `web_game` VALUES ('2', '5', '三国');
INSERT INTO `web_game` VALUES ('3', '8', '口袋妖怪');
INSERT INTO `web_game` VALUES ('4', '9', '时尚');

-- ----------------------------
-- Table structure for web_game_server
-- ----------------------------
DROP TABLE IF EXISTS `web_game_server`;
CREATE TABLE `web_game_server` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `game_id` int(10) unsigned NOT NULL,
  `server_id` int(10) unsigned NOT NULL,
  `server_name` varchar(50) NOT NULL,
  `link` varchar(30) DEFAULT NULL,
  `port` smallint(30) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `combined_service` varchar(50) DEFAULT NULL,
  `status` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_game_server
-- ----------------------------

-- ----------------------------
-- Table structure for web_index_id
-- ----------------------------
DROP TABLE IF EXISTS `web_index_id`;
CREATE TABLE `web_index_id` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `addtime` int(3) DEFAULT '0',
  `game_id` smallint(4) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5082 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_index_id
-- ----------------------------

-- ----------------------------
-- Table structure for web_ipmobile_limit
-- ----------------------------
DROP TABLE IF EXISTS `web_ipmobile_limit`;
CREATE TABLE `web_ipmobile_limit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `operator` varchar(25) NOT NULL,
  `addtime` datetime NOT NULL,
  `reason` text NOT NULL,
  `ipmobile` varchar(100) NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1ip 2mobile',
  `status` smallint(1) NOT NULL DEFAULT '0' COMMENT '0正常1删除',
  `game_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1014 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_ipmobile_limit
-- ----------------------------

-- ----------------------------
-- Table structure for web_login_auto
-- ----------------------------
DROP TABLE IF EXISTS `web_login_auto`;
CREATE TABLE `web_login_auto` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `game_id` smallint(4) unsigned NOT NULL,
  `channel` varchar(30) NOT NULL,
  `account_id` int(11) unsigned NOT NULL,
  `token` varchar(255) NOT NULL,
  `expired_time` int(4) unsigned NOT NULL,
  `addtime` int(4) unsigned NOT NULL,
  `mac` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_login_auto
-- ----------------------------
INSERT INTO `web_login_auto` VALUES ('1', '8', '4720834', '4720834', '41bc507e8e47c4a74b4cdfb5911b1d56', '1509080341', '1509073141', null);
INSERT INTO `web_login_auto` VALUES ('2', '8', '4720834', '4720834', 'dbfdf4bd28d0539b1b2850e01a2704a1', '1509096485', '1509089285', null);
INSERT INTO `web_login_auto` VALUES ('3', '8', '4720835', '4720835', '6767693f03ce5e9654ff345ba3ea3355', '1509097900', '1509090700', null);
INSERT INTO `web_login_auto` VALUES ('4', '8', '4720837', '4720837', 'f20499e443b8d41a816b6da9e302c73a', '1509363720', '1509356520', null);
INSERT INTO `web_login_auto` VALUES ('5', '8', '690001', '4720842', '18b2230a6151b095777abd4066f54bf8', '1509524216', '1509517016', '9dfaa493119da7e9b907bc29c713ecec');
INSERT INTO `web_login_auto` VALUES ('6', '8', '690001', '4720842', 'adef7fd00fea709677d8e88eb1170216', '1509529182', '1509521982', '9dfaa493119da7e9b907bc29c713ecec');
INSERT INTO `web_login_auto` VALUES ('7', '8', '690001', '4720842', 'ffc2e15d967b060e0de0814c71d537aa', '1509532797', '1509525597', '9dfaa493119da7e9b907bc29c713ecec');
INSERT INTO `web_login_auto` VALUES ('8', '8', '690001', '4720848', '18c2585d16ca34ee71046cae5ddfab9e', '1509538189', '1509530989', '9dfaa493119da7e9b907bc29c713ecec');
INSERT INTO `web_login_auto` VALUES ('9', '8', '690001', '4720848', 'c739a60682a1f9e1ba4e342033b661b0', '1510655031', '1510647831', '9dfaa493119da7e9b907bc29c713ecec');
INSERT INTO `web_login_auto` VALUES ('10', '8', '690001', '4720848', 'c3efd30abf8063482eb216fdca3660e7', '1510656598', '1510649398', '9dfaa493119da7e9b907bc29c713ecec');
INSERT INTO `web_login_auto` VALUES ('11', '8', '4720852', '4720852', 'd861addc84f65fe825af59d4476df0cb', '1510659176', '1510651976', null);
INSERT INTO `web_login_auto` VALUES ('12', '8', '4720853', '4720853', '288abaabaa376aa27690da087beaf13d', '1510664174', '1510656974', null);
INSERT INTO `web_login_auto` VALUES ('13', '8', '4720853', '4720853', '08bed86c30567b8a662d440e263a6833', '1510720186', '1510712986', null);
INSERT INTO `web_login_auto` VALUES ('14', '8', '4720853', '4720853', 'e80c35c86f801a8d7682336225b8950a', '1510720901', '1510713701', null);
INSERT INTO `web_login_auto` VALUES ('15', '8', '4720848', '4720848', '5d7dbcf87261b97299b6946ac7481456', '1510721351', '1510714151', null);
INSERT INTO `web_login_auto` VALUES ('16', '8', '4720856', '4720856', '30cfa60caa3614ee2e024b94074a5bbb', '1510721675', '1510714475', null);
INSERT INTO `web_login_auto` VALUES ('17', '8', '4720849', '4720849', 'faa8854596d7674874d4f49474b38333', '1510731495', '1510724295', null);
INSERT INTO `web_login_auto` VALUES ('18', '8', '690001', '4720848', '9e28ab7b274008d3eb4b18993edd9fa3', '1510738254', '1510731054', '9dfaa493119da7e9b907bc29c713ecec');
INSERT INTO `web_login_auto` VALUES ('19', '8', '690001', '4720848', '7d4de2bcb0de664254d77bdbe0751fb4', '1510738382', '1510731182', '9dfaa493119da7e9b907bc29c713ecec');
INSERT INTO `web_login_auto` VALUES ('20', '8', '690001', '4720848', '86eb3300f5ad863220e4ca70512ae289', '1510739026', '1510731826', '9dfaa493119da7e9b907bc29c713ecec');
INSERT INTO `web_login_auto` VALUES ('21', '8', '690001', '4720857', '6cb97cdd51db140021f50d5fa097738d', '1510739442', '1510732242', '9dfaa493119da7e9b907bc29c713ecec');
INSERT INTO `web_login_auto` VALUES ('22', '8', '4720859', '4720859', '72b8b2a7cf3ced7a2b4eaa6c0bd9587d', '1510744455', '1510737255', null);
INSERT INTO `web_login_auto` VALUES ('23', '8', '690001', '4720860', '4b3026559bc339e39e46d3c9f6a77c40', '1510749310', '1510742110', '9dfaa493119da7e9b907bc29c713ecec');
INSERT INTO `web_login_auto` VALUES ('24', '8', '690001', '4720861', 'a7e2d53ff4318ec7a1ebe70918b3d14c', '1510749392', '1510742192', '9dfaa493119da7e9b907bc29c713ecec');
INSERT INTO `web_login_auto` VALUES ('25', '8', '4720862', '4720862', '480940658a24656387aca95cb8eac8eb', '1510751268', '1510744068', null);
INSERT INTO `web_login_auto` VALUES ('26', '8', '690001', '4720863', 'dffe3a5110dae676eacddde4e31aca2e', '1510810302', '1510803102', '9dfaa493119da7e9b907bc29c713ecec');
INSERT INTO `web_login_auto` VALUES ('27', '8', '690001', '4720863', '7dd5c96ab3a9db812c0858819bdac6ac', '1511600456', '1511593256', '9dfaa493119da7e9b907bc29c713ecec');
INSERT INTO `web_login_auto` VALUES ('28', '8', '690001', '4720862', '6c7e57af94aa90a3ceb8dcab52c96d2d', '1511601585', '1511594385', '9dfaa493119da7e9b907bc29c713ecec');
INSERT INTO `web_login_auto` VALUES ('29', '8', '690001', '4720865', 'c488f2ccc1deced410f15b57908549e3', '1511601772', '1511594572', '9dfaa493119da7e9b907bc29c713ecec');
INSERT INTO `web_login_auto` VALUES ('30', '8', '690001', '4720867', 'a89d04c8fe42f8e4faaafbd058bf00a6', '1511604235', '1511597035', '9dfaa493119da7e9b907bc29c713ecec');
INSERT INTO `web_login_auto` VALUES ('31', '8', '690001', '4720869', '2a7284c723b12d0822f3895c2694aefb', '1511607033', '1511599833', '9dfaa493119da7e9b907bc29c713ecec');
INSERT INTO `web_login_auto` VALUES ('32', '8', '690001', '4720867', '6ba1937f9db5ae294986cd0841cf43ce', '1511607104', '1511599904', '9dfaa493119da7e9b907bc29c713ecec');
INSERT INTO `web_login_auto` VALUES ('33', '8', '690001', '4720868', '6307d8520271111fab8b5b1c9e9db159', '1511607394', '1511600194', '9dfaa493119da7e9b907bc29c713ecec');
INSERT INTO `web_login_auto` VALUES ('34', '8', '690001', '4720871', '0477b0b1c07700d40e40b27dff62e3b8', '1511608366', '1511601166', '9dfaa493119da7e9b907bc29c713ecec');
INSERT INTO `web_login_auto` VALUES ('35', '8', '690001', '4720871', '76947525fbb4d9bf1dc4c1d0dcf10e82', '1511608414', '1511601214', '9dfaa493119da7e9b907bc29c713ecec');
INSERT INTO `web_login_auto` VALUES ('36', '8', '4720870', '4720870', '9887eab1356e1b21f2b5fb3c2dfdf1d2', '1511608707', '1511601507', null);
INSERT INTO `web_login_auto` VALUES ('37', '8', '690001', '4720871', '80b0a3a9496438684717664e453fe8e5', '1511608898', '1511601698', '9dfaa493119da7e9b907bc29c713ecec');
INSERT INTO `web_login_auto` VALUES ('38', '8', '690001', '4720870', 'c3b5b8ba40a2eca6b455b7a6f65c8ad5', '1511612468', '1511605268', '9dfaa493119da7e9b907bc29c713ecec');
INSERT INTO `web_login_auto` VALUES ('39', '8', '690001', '4720873', '6c9d4f6be8baf8a61f5aac967eb90f4a', '1511612591', '1511605391', '9dfaa493119da7e9b907bc29c713ecec');
INSERT INTO `web_login_auto` VALUES ('40', '8', '690001', '4720874', '0a69b39a017264431a83c2624a229fe9', '1511856395', '1511849195', '633af0f85259f379a12f6d6cb5583755');

-- ----------------------------
-- Table structure for web_manager
-- ----------------------------
DROP TABLE IF EXISTS `web_manager`;
CREATE TABLE `web_manager` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login_name` varchar(100) NOT NULL,
  `login_pass` char(32) NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `nickname` varchar(100) NOT NULL,
  `login_num` smallint(4) DEFAULT '0',
  `login_time` int(4) DEFAULT '0',
  `reg_time` int(4) DEFAULT '0',
  `login_ip` varchar(50) DEFAULT '0',
  `reg_ip` varchar(50) DEFAULT NULL,
  `game_id` smallint(4) DEFAULT '0',
  `level` tinyint(1) unsigned DEFAULT '0',
  `channel_id` smallint(4) DEFAULT '0',
  `dwFenbao` varchar(255) DEFAULT '0',
  `server_id` varchar(50) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=434 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_manager
-- ----------------------------
INSERT INTO `web_manager` VALUES ('1', 'admin', 'c571637aa1a2e1b1bf862566ba7eae52', '1', '落雪', '674', '1512722241', '0', '222.76.67.254', null, '8', '1', '0', '0', '0');
INSERT INTO `web_manager` VALUES ('411', 'cp_zhangj', 'c571637aa1a2e1b1bf862566ba7eae52', '1', '张建', '41', '1491879315', '1461550744', '220.160.57.12', '::1', '7', '0', '0', '0', '0');
INSERT INTO `web_manager` VALUES ('412', 'cp_koudai', 'c571637aa1a2e1b1bf862566ba7eae52', '1', '口袋妖怪', '4265', '1499218999', '1468290256', '110.90.14.199', '110.90.12.140', '8', '0', '0', '0', '0');
INSERT INTO `web_manager` VALUES ('413', 'wenschan', 'eb263c77186fceb0f9e6703b7cfa3ab4', '1', 'wenschan', '500', '1499222488', '1469772579', '121.204.104.179', '110.90.15.112', '8', '1', '0', '0', '0');
INSERT INTO `web_manager` VALUES ('414', 'cp_linlf', '36cc2c7f3e3841715166d64b501fff72', '1', '林连帆', '44', '1491754033', '1469787007', '121.204.24.115', '110.90.15.112', '0', '0', '0', '0', '0');
INSERT INTO `web_manager` VALUES ('415', 'water', '58b5c80d65b6e72743ef8a5af73cf74a', '1', '吴兴水', '1712', '1499208772', '1471920749', '180.95.233.34', '121.204.78.186', '8', '0', '0', '0', '0');
INSERT INTO `web_manager` VALUES ('416', 'td_tangxs', 'c571637aa1a2e1b1bf862566ba7eae52', '1', '汤晓森', '59', '1478318817', '1472179407', '110.90.13.167', '110.90.12.45', '0', '0', '0', '0', '0');
INSERT INTO `web_manager` VALUES ('418', 'td_wenglq', '36cc2c7f3e3841715166d64b501fff72', '1', '翁礼强', '325', '1499174529', '1477894861', '110.90.14.199', '110.90.14.135', '8', '0', '0', '0', '0');
INSERT INTO `web_manager` VALUES ('417', 'kefu', '36cc2c7f3e3841715166d64b501fff72', '1', '客服', '5', '1475992386', '1475991212', '110.90.14.223', '110.90.14.223', '8', '0', '0', '0', '0');
INSERT INTO `web_manager` VALUES ('429', 'cp_luoxue', '36cc2c7f3e3841715166d64b501fff72', '1', '落雪', '20', '1484703425', '1480479311', '121.204.104.117', '110.90.13.233', '8', '0', '134', '815001,816001,817001,818001,819001,820001,821001,822001,823001,824001', '8');
INSERT INTO `web_manager` VALUES ('430', 'wangning', '369185a7dcfe50c6f2fd65ce782d3a6e', '1', '王宁', '244', '1499087084', '1480946944', '220.160.57.252', '110.90.13.172', '8', '0', '0', '0', '0');
INSERT INTO `web_manager` VALUES ('431', 'lianfanlin', '425a7909bc10e03d0da8fdda7e0ebaac', '1', '林连帆', '552', '1499218558', '1481898722', '121.204.104.179', '220.160.57.87', '8', '0', '0', '0', '0');
INSERT INTO `web_manager` VALUES ('432', 'buguangwu', '56255de771392740a625da5528637a1d', '1', '吴步广', '336', '1499170885', '1481898916', '121.204.104.179', '220.160.57.87', '8', '0', '0', '0', '0');
INSERT INTO `web_manager` VALUES ('433', 'ka_play800', '9598dbd5217dc8f9c3dc22c877da1fdb', '1', 'play800', '159', '1499220826', '1484125367', '58.62.205.161', '121.204.104.33', '8', '0', '134', '815001,816001,817001,818001,819001,820001,821001,822001,823001,824001,825001,826001,827001,828001,829001,830001,831001,832001,833001,834001,835001,836001,837001,838001,839001,840001,841001,842001,843001,844001,845001,846001,847001,660001', '1,5');

-- ----------------------------
-- Table structure for web_manual_log
-- ----------------------------
DROP TABLE IF EXISTS `web_manual_log`;
CREATE TABLE `web_manual_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `game_id` int(4) NOT NULL DEFAULT '0',
  `server_id` int(11) unsigned NOT NULL,
  `type` smallint(4) NOT NULL DEFAULT '0',
  `name` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `order_id` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `dwFenBaoID` int(4) unsigned NOT NULL DEFAULT '0',
  `emoney` decimal(10,2) NOT NULL DEFAULT '0.00',
  `addtime` int(4) NOT NULL DEFAULT '0',
  `operator` varchar(30) CHARACTER SET utf8 NOT NULL,
  `verify_time` int(4) NOT NULL DEFAULT '0',
  `verify_level` smallint(4) DEFAULT '0',
  `remark` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `account_id` int(11) unsigned NOT NULL DEFAULT '0',
  `account_name` varchar(100) COLLATE utf8_bin NOT NULL,
  `payCode` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT 'CNY',
  `is_emp` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1表示内部号',
  PRIMARY KEY (`id`),
  UNIQUE KEY `orderId` (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=278 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of web_manual_log
-- ----------------------------

-- ----------------------------
-- Table structure for web_menu
-- ----------------------------
DROP TABLE IF EXISTS `web_menu`;
CREATE TABLE `web_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '名称',
  `m_id` int(11) DEFAULT NULL,
  `grade` int(11) DEFAULT NULL COMMENT '等级',
  `parentid` int(11) DEFAULT '0',
  `controller` varchar(255) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `status` int(11) DEFAULT '0' COMMENT '状态',
  `home` varchar(255) DEFAULT NULL COMMENT '主页',
  `closeable` int(11) DEFAULT '1' COMMENT '是否可关闭',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `ico` varchar(255) DEFAULT NULL,
  `lock` int(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `m_id` (`m_id`)
) ENGINE=MyISAM AUTO_INCREMENT=217 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='菜单';

-- ----------------------------
-- Records of web_menu
-- ----------------------------
INSERT INTO `web_menu` VALUES ('6', '充值管理', '6', '1', '0', 'payLog', 'index', '', '1', '0', '1', '1', 'nav-inventory', '1');
INSERT INTO `web_menu` VALUES ('186', '时尚管理', '186', '1', '0', 'VgTask', 'index', '', '1', '0', '1', '3', null, '0');
INSERT INTO `web_menu` VALUES ('9', '运营管理', '9', '1', '0', 'codeExchange', 'index', '', '1', '0', '1', '2', 'nav-marketing', '1');
INSERT INTO `web_menu` VALUES ('195', '妆容、发型、皮肤', '186', '3', '189', 'VgModel', 'index', '', '1', '0', '1', '0', null, '0');
INSERT INTO `web_menu` VALUES ('196', '重新加载掉落', '9', '3', '159', 'operators', 'reloadDrop', '', '1', '0', '1', '13', null, '0');
INSERT INTO `web_menu` VALUES ('197', 'ip、机型封解', '9', '3', '159', 'ipmobileLimit', 'index', '', '1', '0', '1', '14', null, '0');
INSERT INTO `web_menu` VALUES ('198', '分包ID设置', '42', '3', '157', 'dwfenbao', 'index', '', '1', '0', '1', '5', null, '0');
INSERT INTO `web_menu` VALUES ('199', '渠道统计', '6', '3', '165', 'payLog', 'fenbaoStatistics', '', '1', '0', '1', '5', null, '0');
INSERT INTO `web_menu` VALUES ('200', '流程等级', '42', '3', '43', 'erpLevel', 'index', '', '1', '0', '1', '4', null, '0');
INSERT INTO `web_menu` VALUES ('202', '充值排行', '6', '3', '165', 'payLog', 'playerStatistics', '', '1', '0', '1', '4', null, '0');
INSERT INTO `web_menu` VALUES ('203', '区服统计', '6', '3', '165', 'payLog', 'serverStatistics', '', '1', '0', '1', '6', null, '0');
INSERT INTO `web_menu` VALUES ('42', '系统管理', '42', '1', '0', 'public', 'about', '', '1', '0', '1', '0', 'nav-home', '1');
INSERT INTO `web_menu` VALUES ('43', '首页', '42', '2', '42', '', '', '', '1', '0', '1', '43', null, '1');
INSERT INTO `web_menu` VALUES ('44', '菜单管理', '42', '3', '43', 'menu', 'index', '', '1', '0', '1', '0', null, '1');
INSERT INTO `web_menu` VALUES ('187', '任务管理', '186', '2', '186', '', '', '', '1', '0', '1', '187', null, '0');
INSERT INTO `web_menu` VALUES ('157', '设置', '42', '2', '42', '', '', '', '1', '0', '1', '157', null, '0');
INSERT INTO `web_menu` VALUES ('158', '修改密码', '42', '3', '157', 'manager', 'edit', '', '1', '0', '1', '0', null, '0');
INSERT INTO `web_menu` VALUES ('159', '运营管理', '9', '2', '9', '', '', '', '1', '0', '1', '159', null, '0');
INSERT INTO `web_menu` VALUES ('160', '激活码列表', '9', '3', '159', 'codeExchange', 'index', '', '1', '0', '1', '0', null, '0');
INSERT INTO `web_menu` VALUES ('161', '激活码生成', '9', '3', '159', 'codeExchange', 'add', '', '1', '0', '1', '1', null, '0');
INSERT INTO `web_menu` VALUES ('162', '关于系统', '42', '3', '157', 'public', 'about', '', '1', '1', '1', '1', null, '0');
INSERT INTO `web_menu` VALUES ('163', '游戏设置', '42', '3', '157', 'game', 'index', '', '1', '0', '1', '2', null, '0');
INSERT INTO `web_menu` VALUES ('164', '渠道管理', '42', '3', '157', 'channel', 'index', '', '1', '0', '1', '3', null, '0');
INSERT INTO `web_menu` VALUES ('165', '充值管理', '6', '2', '6', '', '', '', '1', '0', '1', '165', null, '0');
INSERT INTO `web_menu` VALUES ('166', '充值查询', '6', '3', '165', 'payLog', 'index', '', '1', '0', '1', '0', null, '0');
INSERT INTO `web_menu` VALUES ('168', '网站管理', '9', '2', '9', '', '', '', '1', '0', '1', '168', null, '0');
INSERT INTO `web_menu` VALUES ('169', '导航', '9', '3', '168', 'category', 'index', '', '1', '0', '1', '0', null, '0');
INSERT INTO `web_menu` VALUES ('170', '文章管理', '9', '3', '168', 'article', 'index', '', '1', '0', '1', '0', null, '0');
INSERT INTO `web_menu` VALUES ('201', '游服订单查询', '6', '3', '165', 'payLog', 'gameOrderList', '', '1', '0', '1', '3', null, '0');
INSERT INTO `web_menu` VALUES ('172', '手工充值', '6', '3', '165', 'manualLog', 'index', '', '1', '0', '1', '1', null, '0');
INSERT INTO `web_menu` VALUES ('173', '充值统计', '6', '3', '165', 'payLog', 'statistics', '', '1', '0', '1', '2', null, '0');
INSERT INTO `web_menu` VALUES ('174', '玩家封号', '9', '3', '159', 'operators', 'sealingSolutionLog', '', '1', '0', '1', '2', null, '0');
INSERT INTO `web_menu` VALUES ('175', '物品发放', '9', '3', '159', 'playergoodsLog', 'index', '', '1', '0', '1', '3', null, '0');
INSERT INTO `web_menu` VALUES ('176', '客服命令', '9', '3', '159', 'operators', 'serviceCommand', '', '1', '0', '1', '4', null, '0');
INSERT INTO `web_menu` VALUES ('177', '多服补偿', '9', '3', '159', 'compensateLog', 'index', '', '1', '0', '1', '6', null, '0');
INSERT INTO `web_menu` VALUES ('178', '账号信息查询', '9', '3', '159', 'operators', 'accountInfo', '', '1', '0', '1', '7', null, '0');
INSERT INTO `web_menu` VALUES ('179', '日志查看', '9', '3', '159', 'operators', 'log', '', '1', '0', '1', '9', null, '0');
INSERT INTO `web_menu` VALUES ('181', '角色信息查询', '9', '3', '159', 'operators', 'playerInfo', '', '1', '0', '1', '8', null, '0');
INSERT INTO `web_menu` VALUES ('184', '区服管理', '42', '3', '157', 'gameServer', 'index', '', '1', '0', '1', '4', null, '0');
INSERT INTO `web_menu` VALUES ('185', '游戏公告(多服)', '9', '3', '159', 'operators', 'bulletinLog', '', '1', '0', '1', '10', null, '0');
INSERT INTO `web_menu` VALUES ('117', '角色管理', '42', '3', '43', 'role', 'index', '', '1', '0', '1', '1', null, '0');
INSERT INTO `web_menu` VALUES ('116', '节点管理', '42', '3', '43', 'node', 'index', '', '1', '0', '1', '3', null, '0');
INSERT INTO `web_menu` VALUES ('118', '账号管理', '42', '3', '43', 'manager', 'index', '', '1', '0', '1', '3', null, '0');
INSERT INTO `web_menu` VALUES ('188', '任务查询', '186', '3', '187', 'VgTask', 'index', '', '1', '0', '1', '0', null, '0');
INSERT INTO `web_menu` VALUES ('189', '服饰管理', '186', '2', '186', '', '', '', '1', '0', '1', '189', null, '0');
INSERT INTO `web_menu` VALUES ('190', '服饰查询', '186', '3', '189', 'VgShopitem', 'index', '', '1', '0', '1', '0', null, '0');
INSERT INTO `web_menu` VALUES ('191', '服饰定价', '186', '3', '189', 'VgShopitem', 'setprice', '', '1', '0', '1', '0', null, '0');
INSERT INTO `web_menu` VALUES ('192', '惩罚查询', '9', '3', '159', 'operators', 'serviceCommandLog', '', '1', '0', '1', '5', null, '0');
INSERT INTO `web_menu` VALUES ('193', '自动登录', '9', '3', '159', 'loginAuto', 'index', '', '1', '0', '1', '11', null, '0');
INSERT INTO `web_menu` VALUES ('194', '角色封解', '9', '3', '159', 'operators', 'playerLimitLog', '', '1', '0', '1', '12', null, '0');
INSERT INTO `web_menu` VALUES ('204', '区间统计', '6', '3', '165', 'payLog', 'areaStatistics', '', '1', '0', '1', '7', null, '0');
INSERT INTO `web_menu` VALUES ('205', '账号转换', '9', '3', '159', 'operators', 'accountChange', '', '1', '0', '1', '15', null, '0');
INSERT INTO `web_menu` VALUES ('206', '游戏版图', '9', '3', '159', 'gameEdition', 'index', '', '1', '0', '1', '16', null, '0');
INSERT INTO `web_menu` VALUES ('207', '每月福利', '42', '3', '157', 'welfare', 'index', '', '1', '0', '1', '6', null, '0');
INSERT INTO `web_menu` VALUES ('208', '物品管理', '42', '3', '157', 'addGood', 'index', '', '1', '0', '1', '7', null, '0');
INSERT INTO `web_menu` VALUES ('209', '渠道发送信息', '9', '3', '159', 'OperatorsSe', 'sendFenBaoMessage', '', '1', '0', '1', '17', null, '0');
INSERT INTO `web_menu` VALUES ('210', '渠道查询', '6', '3', '165', 'payLog', 'fenbaoIndex', '', '1', '0', '1', '5', null, '0');
INSERT INTO `web_menu` VALUES ('214', 'T权限管理', '42', '3', '43', 'accessTable', 'index', '', '1', '0', '1', '5', null, '0');
INSERT INTO `web_menu` VALUES ('213', '配置信息', '42', '3', '157', 'config', 'update', '', '1', '0', '1', '0', null, '0');
INSERT INTO `web_menu` VALUES ('215', '内部账号', '42', '3', '157', 'empAccount', 'index', '', '1', '0', '1', '0', null, '0');
INSERT INTO `web_menu` VALUES ('216', '区服ip查询', '9', '3', '159', 'operators', 'getip', '', '1', '0', '1', '0', null, '0');

-- ----------------------------
-- Table structure for web_message
-- ----------------------------
DROP TABLE IF EXISTS `web_message`;
CREATE TABLE `web_message` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `game_id` smallint(4) NOT NULL,
  `username` varchar(30) NOT NULL,
  `code` varchar(10) NOT NULL,
  `addtime` int(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `phonetime` (`username`,`addtime`,`game_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=5810 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_message
-- ----------------------------

-- ----------------------------
-- Table structure for web_node
-- ----------------------------
DROP TABLE IF EXISTS `web_node`;
CREATE TABLE `web_node` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `remark` varchar(255) DEFAULT NULL,
  `sort` smallint(6) unsigned DEFAULT NULL,
  `pid` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) unsigned NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `group_id` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `pid` (`pid`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=527 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of web_node
-- ----------------------------
INSERT INTO `web_node` VALUES ('109', 'update', '修改', '1', '', null, '106', '3', '0', '0');
INSERT INTO `web_node` VALUES ('108', 'add', '新增', '1', '', null, '106', '3', '0', '0');
INSERT INTO `web_node` VALUES ('1', 'Admin', '后台管理', '1', '', null, '0', '1', '0', '0');
INSERT INTO `web_node` VALUES ('110', 'del', '删除', '1', '', null, '106', '3', '0', '0');
INSERT INTO `web_node` VALUES ('107', 'index', '显示', '1', '', null, '106', '3', '0', '0');
INSERT INTO `web_node` VALUES ('106', 'Node', '节点管理', '1', '', null, '1', '2', '0', '0');
INSERT INTO `web_node` VALUES ('111', 'Role', '角色管理', '1', '', null, '1', '2', '0', '0');
INSERT INTO `web_node` VALUES ('112', 'index', '显示', '1', '', null, '111', '3', '0', '0');
INSERT INTO `web_node` VALUES ('113', 'add', '新增', '1', '', null, '111', '3', '0', '0');
INSERT INTO `web_node` VALUES ('114', 'del', '删除', '1', '', null, '111', '3', '0', '0');
INSERT INTO `web_node` VALUES ('115', 'update', '更新', '1', '', null, '111', '3', '0', '0');
INSERT INTO `web_node` VALUES ('116', 'forbid', '禁用', '1', '', null, '111', '3', '0', '0');
INSERT INTO `web_node` VALUES ('117', 'resume', '恢复', '1', '', null, '111', '3', '0', '0');
INSERT INTO `web_node` VALUES ('118', 'app', '授权', '1', '', null, '111', '3', '0', '0');
INSERT INTO `web_node` VALUES ('119', 'setApp', '应用保存', '1', '', null, '111', '3', '0', '0');
INSERT INTO `web_node` VALUES ('120', 'model', '模块授权', '1', '', null, '111', '3', '0', '0');
INSERT INTO `web_node` VALUES ('121', 'setModel', '模块保存', '1', '', null, '111', '3', '0', '0');
INSERT INTO `web_node` VALUES ('122', 'operate', '操作授权', '1', '', null, '111', '3', '0', '0');
INSERT INTO `web_node` VALUES ('123', 'setOperate', '操作保存', '1', '', null, '111', '3', '0', '0');
INSERT INTO `web_node` VALUES ('164', 'Index', '入口', '1', '', null, '1', '2', '0', '0');
INSERT INTO `web_node` VALUES ('165', 'index', '显示', '1', '', null, '164', '3', '0', '0');
INSERT INTO `web_node` VALUES ('166', 'Manager', '账号管理', '1', '', null, '1', '2', '0', '0');
INSERT INTO `web_node` VALUES ('167', 'index', '显示', '1', '', null, '166', '3', '0', '0');
INSERT INTO `web_node` VALUES ('168', 'add', '添加', '1', '', null, '166', '3', '0', '0');
INSERT INTO `web_node` VALUES ('169', 'del', '删除', '1', '', null, '166', '3', '0', '0');
INSERT INTO `web_node` VALUES ('170', 'update', '修改', '1', '', null, '166', '3', '0', '0');
INSERT INTO `web_node` VALUES ('171', 'forbid', '禁用', '1', '', null, '166', '3', '0', '0');
INSERT INTO `web_node` VALUES ('172', 'resume', '恢复', '1', '', null, '166', '3', '0', '0');
INSERT INTO `web_node` VALUES ('403', 'add', '激活码生成', '1', '', null, '401', '3', '0', '0');
INSERT INTO `web_node` VALUES ('402', 'index', '显示', '1', '', null, '401', '3', '0', '0');
INSERT INTO `web_node` VALUES ('401', 'CodeExchange', '激活码', '1', '', null, '1', '2', '0', '0');
INSERT INTO `web_node` VALUES ('396', 'Game', '游戏设置', '1', '', null, '1', '2', '0', '0');
INSERT INTO `web_node` VALUES ('397', 'index', '显示', '1', '', null, '396', '3', '0', '0');
INSERT INTO `web_node` VALUES ('398', 'add', '添加', '1', '', null, '396', '3', '0', '0');
INSERT INTO `web_node` VALUES ('399', 'update', '修改', '1', '', null, '396', '3', '0', '0');
INSERT INTO `web_node` VALUES ('400', 'del', '删除', '1', '', null, '396', '3', '0', '0');
INSERT INTO `web_node` VALUES ('405', 'index', '显示', '1', '', null, '404', '3', '0', '0');
INSERT INTO `web_node` VALUES ('406', 'add', '添加', '1', '', null, '404', '3', '0', '0');
INSERT INTO `web_node` VALUES ('410', 'index', '显示', '1', '', null, '409', '3', '0', '0');
INSERT INTO `web_node` VALUES ('404', 'Channel', '渠道管理', '1', '', null, '1', '2', '0', '0');
INSERT INTO `web_node` VALUES ('336', 'manager', '用户列表', '1', '', null, '111', '3', '0', '0');
INSERT INTO `web_node` VALUES ('337', 'setUser', '保存', '1', '', null, '111', '3', '0', '0');
INSERT INTO `web_node` VALUES ('409', 'PayLog', '充值查询', '1', '', null, '1', '2', '0', '0');
INSERT INTO `web_node` VALUES ('408', 'del', '删除', '1', '', null, '404', '3', '0', '0');
INSERT INTO `web_node` VALUES ('407', 'update', '编辑', '1', '', null, '404', '3', '0', '0');
INSERT INTO `web_node` VALUES ('395', 'edit', '修改密码', '1', '', null, '166', '3', '0', '0');
INSERT INTO `web_node` VALUES ('491', 'areaStatistics', '区间统计', '1', '', null, '409', '3', '0', '0');
INSERT INTO `web_node` VALUES ('412', 'manual', '手工充值', '1', '', null, '409', '3', '0', '0');
INSERT INTO `web_node` VALUES ('413', 'statistics', '充值统计', '1', '', null, '409', '3', '0', '0');
INSERT INTO `web_node` VALUES ('414', 'Operators', '运营管理', '1', '', null, '1', '2', '0', '0');
INSERT INTO `web_node` VALUES ('415', 'sealingSolution', '账号封解', '1', '', null, '414', '3', '0', '0');
INSERT INTO `web_node` VALUES ('416', 'addGood', '添加物品', '1', '', null, '414', '3', '0', '0');
INSERT INTO `web_node` VALUES ('417', 'serviceCommand', '客服命令', '1', '', null, '414', '3', '0', '0');
INSERT INTO `web_node` VALUES ('418', 'compensateGood', '多服补偿', '1', '', null, '414', '3', '0', '0');
INSERT INTO `web_node` VALUES ('419', 'accountInfo', '账号信息查询', '1', '', null, '414', '3', '0', '0');
INSERT INTO `web_node` VALUES ('420', 'log', '日志查看', '1', '', null, '414', '3', '0', '0');
INSERT INTO `web_node` VALUES ('494', 'bulletinCancel', '取消多服公告', '1', '', null, '414', '3', '0', '0');
INSERT INTO `web_node` VALUES ('423', 'playerInfo', '角色信息查询', '1', '', null, '414', '3', '0', '0');
INSERT INTO `web_node` VALUES ('493', 'accountChange', '账号转换', '1', '', null, '414', '3', '0', '0');
INSERT INTO `web_node` VALUES ('426', 'GameServer', '区服管理', '1', '', null, '1', '2', '0', '0');
INSERT INTO `web_node` VALUES ('427', 'index', '显示', '1', '', null, '426', '3', '0', '0');
INSERT INTO `web_node` VALUES ('428', 'add', '新增', '1', '', null, '426', '3', '0', '0');
INSERT INTO `web_node` VALUES ('429', 'update', '编辑', '1', '', null, '426', '3', '0', '0');
INSERT INTO `web_node` VALUES ('430', 'del', '删除', '1', '', null, '426', '3', '0', '0');
INSERT INTO `web_node` VALUES ('431', 'bulletin', '添加多服公告', '1', '', null, '414', '3', '0', '0');
INSERT INTO `web_node` VALUES ('432', 'Category', '文章导航', '1', '', null, '1', '2', '0', '0');
INSERT INTO `web_node` VALUES ('433', 'update', '修改', '1', '', null, '432', '3', '0', '0');
INSERT INTO `web_node` VALUES ('439', 'index', '显示', '1', '', null, '432', '3', '0', '0');
INSERT INTO `web_node` VALUES ('438', 'del', '删除', '1', '', null, '432', '3', '0', '0');
INSERT INTO `web_node` VALUES ('437', 'add', '新增', '1', '', null, '432', '3', '0', '0');
INSERT INTO `web_node` VALUES ('440', 'Article', '文章管理', '1', '', null, '1', '2', '0', '0');
INSERT INTO `web_node` VALUES ('441', 'update', '修改', '1', '', null, '440', '3', '0', '0');
INSERT INTO `web_node` VALUES ('442', 'add', '新增', '1', '', null, '440', '3', '0', '0');
INSERT INTO `web_node` VALUES ('443', 'del', '删除', '1', '', null, '440', '3', '0', '0');
INSERT INTO `web_node` VALUES ('444', 'index', '显示', '1', '', null, '440', '3', '0', '0');
INSERT INTO `web_node` VALUES ('445', 'supplement', '补单', '1', '', null, '409', '3', '0', '0');
INSERT INTO `web_node` VALUES ('446', 'gameOrderList', '游服订单查询', '1', '', null, '409', '3', '0', '0');
INSERT INTO `web_node` VALUES ('447', 'playerStatistics', '充值排行', '1', '', null, '409', '3', '0', '0');
INSERT INTO `web_node` VALUES ('448', 'LoginAuto', '自动登录', '1', '', null, '1', '2', '0', '0');
INSERT INTO `web_node` VALUES ('449', 'index', '显示', '1', '', null, '448', '3', '0', '0');
INSERT INTO `web_node` VALUES ('492', 'bulletinLog', '游戏多服公告显示', '1', '', null, '414', '3', '0', '0');
INSERT INTO `web_node` VALUES ('451', 'cancelServiceCommand', '取消滚动消息', '1', '', null, '414', '3', '0', '0');
INSERT INTO `web_node` VALUES ('452', 'serviceCommandLog', '客服命令日志', '1', '', null, '414', '3', '0', '0');
INSERT INTO `web_node` VALUES ('453', 'sealingSolutionLog', '账号封解列表', '1', '', null, '414', '3', '0', '0');
INSERT INTO `web_node` VALUES ('454', 'playerLimitLog', '角色封解', '1', '', null, '414', '3', '0', '0');
INSERT INTO `web_node` VALUES ('455', 'playerLimit', '角色封解添加', '1', '', null, '414', '3', '0', '0');
INSERT INTO `web_node` VALUES ('456', 'reloadDrop', '重新加载掉落', '1', '', null, '414', '3', '0', '0');
INSERT INTO `web_node` VALUES ('457', 'IpmobileLimit', 'ip、机型封解', '1', '', null, '1', '2', '0', '0');
INSERT INTO `web_node` VALUES ('458', 'index', '显示', '1', '', null, '457', '3', '0', '0');
INSERT INTO `web_node` VALUES ('459', 'add', '添加', '1', '', null, '457', '3', '0', '0');
INSERT INTO `web_node` VALUES ('460', 'cancel', '取消', '1', '', null, '457', '3', '0', '0');
INSERT INTO `web_node` VALUES ('461', 'Menu', '菜单管理', '1', '', null, '1', '2', '0', '0');
INSERT INTO `web_node` VALUES ('462', 'index', '显示', '1', '', null, '461', '3', '0', '0');
INSERT INTO `web_node` VALUES ('463', 'add', '添加', '1', '', null, '461', '3', '0', '0');
INSERT INTO `web_node` VALUES ('464', 'update', '编辑', '1', '', null, '461', '3', '0', '0');
INSERT INTO `web_node` VALUES ('465', 'del', '删除', '1', '', null, '461', '3', '0', '0');
INSERT INTO `web_node` VALUES ('466', 'fenbaoStatistics', '渠道统计', '1', '', null, '409', '3', '0', '0');
INSERT INTO `web_node` VALUES ('467', 'serverStatistics', '区服统计', '1', '', null, '409', '3', '0', '0');
INSERT INTO `web_node` VALUES ('468', 'ErpLevel', '流程等级', '1', '', null, '1', '2', '0', '0');
INSERT INTO `web_node` VALUES ('469', 'index', '显示', '1', '', null, '468', '3', '0', '0');
INSERT INTO `web_node` VALUES ('470', 'add', '添加', '1', '', null, '468', '3', '0', '0');
INSERT INTO `web_node` VALUES ('471', 'update', '编辑', '1', '', null, '468', '3', '0', '0');
INSERT INTO `web_node` VALUES ('472', 'del', '删除', '1', '', null, '468', '3', '0', '0');
INSERT INTO `web_node` VALUES ('473', 'ManualLog', '手工充值', '1', '', null, '1', '2', '0', '0');
INSERT INTO `web_node` VALUES ('474', 'index', '显示', '1', '', null, '473', '3', '0', '0');
INSERT INTO `web_node` VALUES ('475', 'add', '添加', '1', '', null, '473', '3', '0', '0');
INSERT INTO `web_node` VALUES ('476', 'update', '编辑', '1', '', null, '473', '3', '0', '0');
INSERT INTO `web_node` VALUES ('477', 'pass', '通过', '1', '', null, '473', '3', '0', '0');
INSERT INTO `web_node` VALUES ('478', 'repass', '退回', '1', '', null, '473', '3', '0', '0');
INSERT INTO `web_node` VALUES ('479', 'PlayergoodsLog', '物品发放', '1', '', null, '1', '2', '0', '0');
INSERT INTO `web_node` VALUES ('480', 'index', '显示', '1', '', null, '479', '3', '0', '0');
INSERT INTO `web_node` VALUES ('481', 'add', '添加', '1', '', null, '479', '3', '0', '0');
INSERT INTO `web_node` VALUES ('482', 'update', '编辑', '1', '', null, '479', '3', '0', '0');
INSERT INTO `web_node` VALUES ('483', 'pass', '通过', '1', '', null, '479', '3', '0', '0');
INSERT INTO `web_node` VALUES ('484', 'repass', '退回', '1', '', null, '479', '3', '0', '0');
INSERT INTO `web_node` VALUES ('485', 'CompensateLog', '多服补偿', '1', '', null, '1', '2', '0', '0');
INSERT INTO `web_node` VALUES ('486', 'index', '显示', '1', '', null, '485', '3', '0', '0');
INSERT INTO `web_node` VALUES ('487', 'add', '添加', '1', '', null, '485', '3', '0', '0');
INSERT INTO `web_node` VALUES ('488', 'update', '编辑', '1', '', null, '485', '3', '0', '0');
INSERT INTO `web_node` VALUES ('489', 'pass', '通过', '1', '', null, '485', '3', '0', '0');
INSERT INTO `web_node` VALUES ('490', 'repass', '退回', '1', '', null, '485', '3', '0', '0');
INSERT INTO `web_node` VALUES ('495', 'GameEdition', '游戏版图', '1', '', null, '1', '2', '0', '0');
INSERT INTO `web_node` VALUES ('496', 'index', '显示', '1', '', null, '495', '3', '0', '0');
INSERT INTO `web_node` VALUES ('497', 'add', '添加', '1', '', null, '495', '3', '0', '0');
INSERT INTO `web_node` VALUES ('498', 'update', '编辑', '1', '', null, '495', '3', '0', '0');
INSERT INTO `web_node` VALUES ('499', 'codeLog', '激活码领取log', '1', '', null, '401', '3', '0', '0');
INSERT INTO `web_node` VALUES ('500', 'Dwfenbao', '分包ID设置', '1', '', null, '1', '2', '0', '0');
INSERT INTO `web_node` VALUES ('501', 'index', '显示', '1', '', null, '500', '3', '0', '0');
INSERT INTO `web_node` VALUES ('502', 'add', '添加', '1', '', null, '500', '3', '0', '0');
INSERT INTO `web_node` VALUES ('503', 'update', '编辑', '1', '', null, '500', '3', '0', '0');
INSERT INTO `web_node` VALUES ('504', 'del', '删除', '1', '', null, '500', '3', '0', '0');
INSERT INTO `web_node` VALUES ('505', 'AddGood', '物品管理', '1', '', null, '1', '2', '0', '0');
INSERT INTO `web_node` VALUES ('506', 'index', '显示', '1', '', null, '505', '3', '0', '0');
INSERT INTO `web_node` VALUES ('507', 'add', '添加', '1', '', null, '505', '3', '0', '0');
INSERT INTO `web_node` VALUES ('508', 'update', '编辑', '1', '', null, '505', '3', '0', '0');
INSERT INTO `web_node` VALUES ('509', 'del', '删除', '1', '', null, '505', '3', '0', '0');
INSERT INTO `web_node` VALUES ('510', 'OperatorsSe', '渠道发送消息', '1', '', null, '1', '2', '0', '0');
INSERT INTO `web_node` VALUES ('511', 'sendFenBaoMessage', '发送消息', '1', '', null, '510', '3', '0', '0');
INSERT INTO `web_node` VALUES ('512', 'oneKeyPass', '一键审核', '1', '', null, '479', '3', '0', '0');
INSERT INTO `web_node` VALUES ('513', 'fenbaoIndex', '渠道查询', '1', '', null, '409', '3', '0', '0');
INSERT INTO `web_node` VALUES ('514', 'Config', '配置信息', '1', '', null, '1', '2', '0', '0');
INSERT INTO `web_node` VALUES ('515', 'update', '修改', '1', '', null, '514', '3', '0', '0');
INSERT INTO `web_node` VALUES ('516', 'AccessTable', 'T权限管理', '1', '', null, '1', '2', '0', '0');
INSERT INTO `web_node` VALUES ('517', 'index', '显示', '1', '', null, '516', '3', '0', '0');
INSERT INTO `web_node` VALUES ('518', 'add', '添加', '1', '', null, '516', '3', '0', '0');
INSERT INTO `web_node` VALUES ('519', 'update', '更新', '1', '', null, '516', '3', '0', '0');
INSERT INTO `web_node` VALUES ('520', 'del', '删除', '1', '', null, '516', '3', '0', '0');
INSERT INTO `web_node` VALUES ('521', 'oneKeyPass', '一键补单', '1', '', null, '409', '3', '0', '0');
INSERT INTO `web_node` VALUES ('522', 'oneKeyPass', '一键审核', '1', '', null, '473', '3', '0', '0');
INSERT INTO `web_node` VALUES ('523', 'EmpAccount', '内部帐号', '1', '', null, '1', '2', '0', '0');
INSERT INTO `web_node` VALUES ('524', 'index', '显示', '1', '', null, '523', '3', '0', '0');
INSERT INTO `web_node` VALUES ('525', 'add', '新增', '1', '', null, '523', '3', '0', '0');
INSERT INTO `web_node` VALUES ('526', 'getip', '区服ip查询', '1', '', null, '414', '3', '0', '0');

-- ----------------------------
-- Table structure for web_pay_log
-- ----------------------------
DROP TABLE IF EXISTS `web_pay_log`;
CREATE TABLE `web_pay_log` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `PayID` int(4) DEFAULT NULL,
  `PayName` varchar(50) DEFAULT NULL,
  `ServerID` int(4) DEFAULT NULL,
  `PayMoney` float(10,2) DEFAULT '0.00',
  `OrderID` varchar(200) DEFAULT NULL,
  `CardNO` varchar(50) DEFAULT NULL,
  `CardPwd` varchar(50) DEFAULT NULL,
  `BankID` varchar(50) DEFAULT NULL,
  `BankOrderID` varchar(50) DEFAULT NULL,
  `rpCode` varchar(30) DEFAULT NULL,
  `rpTime` datetime DEFAULT NULL,
  `PayType` int(4) DEFAULT NULL,
  `dwFenBaoID` varchar(50) DEFAULT NULL,
  `Add_Time` datetime DEFAULT NULL,
  `PayCode` varchar(50) DEFAULT 'CNY',
  `SubStat` tinyint(1) NOT NULL DEFAULT '1',
  `IsUC` int(11) DEFAULT '0',
  `CPID` int(11) DEFAULT NULL,
  `tag` enum('0','1') NOT NULL DEFAULT '0',
  `game_id` smallint(3) NOT NULL DEFAULT '1',
  `clienttype` varchar(50) DEFAULT NULL,
  `transaction_id` varchar(50) DEFAULT NULL,
  `packageName` varchar(255) DEFAULT NULL,
  `channelID` varchar(255) DEFAULT NULL,
  `currency` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `OrderID` (`OrderID`) USING BTREE,
  KEY `payIdGameId` (`PayID`,`game_id`),
  KEY `addTime` (`Add_Time`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1582015 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_pay_log
-- ----------------------------
INSERT INTO `web_pay_log` VALUES ('1581995', '4720852', 'ACE60647-7994-4483-8509-AD61C57F0A61@u591', '8999', '75.00', '1000000349468678', null, null, null, null, '1', null, null, '914001', '2017-11-03 11:10:35', 'RUB', '1', '1', '19', '0', '8', null, null, null, null, null);
INSERT INTO `web_pay_log` VALUES ('1581996', '4720852', 'ACE60647-7994-4483-8509-AD61C57F0A61@u591', '8999', '75.00', '1000000349468873', null, null, null, null, '1', null, null, '914001', '2017-11-03 11:11:28', 'RUB', '1', '1', '19', '0', '8', null, null, null, null, null);
INSERT INTO `web_pay_log` VALUES ('1581997', '4720852', 'ACE60647-7994-4483-8509-AD61C57F0A61@u591', '8999', '75.00', '1000000349478706', null, null, null, null, '1', null, null, '914001', '2017-11-03 11:44:36', 'RUB', '1', '0', '19', '0', '8', null, null, null, null, null);
INSERT INTO `web_pay_log` VALUES ('1581998', '4720852', 'ACE60647-7994-4483-8509-AD61C57F0A61@u591', '8999', '379.00', '1000000349479144', null, null, null, null, '1', null, null, '914001', '2017-11-03 11:45:55', 'RUB', '1', '0', '19', '0', '8', null, null, null, null, null);
INSERT INTO `web_pay_log` VALUES ('1581999', '4720857', '82ca3a872fc78231@u591', '8999', '299.00', '1000000352378962', null, null, null, null, '1', null, null, '914001', '2017-11-15 11:05:09', 'RUB', '1', '0', '19', '0', '8', null, null, null, null, null);
INSERT INTO `web_pay_log` VALUES ('1582000', '4720857', '82ca3a872fc78231@u591', '8999', '75.00', '1000000352379186', null, null, null, null, '1', null, null, '914001', '2017-11-15 11:05:58', 'RUB', '1', '0', '19', '0', '8', null, null, null, null, null);
INSERT INTO `web_pay_log` VALUES ('1582001', '4720859', 'D4704048-A971-4BE2-9429-13B909AF08EE@u591', '8999', '75.00', '1000000352412286', null, null, null, null, '1', null, null, '914001', '2017-11-15 12:30:24', 'RUB', '1', '0', '19', '0', '8', null, null, null, null, null);
INSERT INTO `web_pay_log` VALUES ('1582002', '4720859', 'D4704048-A971-4BE2-9429-13B909AF08EE@u591', '8999', '75.00', '1000000352412511', null, null, null, null, '1', null, null, '914001', '2017-11-15 12:30:42', 'RUB', '1', '0', '19', '0', '8', null, null, null, null, null);
INSERT INTO `web_pay_log` VALUES ('1582003', '4720859', 'D4704048-A971-4BE2-9429-13B909AF08EE@u591', '8999', '379.00', '1000000352412592', null, null, null, null, '1', null, null, '914001', '2017-11-15 12:31:05', 'RUB', '1', '0', '19', '0', '8', null, null, null, null, null);
INSERT INTO `web_pay_log` VALUES ('1582004', '4720859', 'D4704048-A971-4BE2-9429-13B909AF08EE@u591', '8999', '1490.00', '1000000352412648', null, null, null, null, '1', null, null, '914001', '2017-11-15 12:31:29', 'RUB', '1', '0', '19', '0', '8', null, null, null, null, null);
INSERT INTO `web_pay_log` VALUES ('1582005', '4720859', 'D4704048-A971-4BE2-9429-13B909AF08EE@u591', '8999', '3790.00', '1000000352412710', null, null, null, null, '1', null, null, '914001', '2017-11-15 12:31:56', 'RUB', '1', '0', '19', '0', '8', null, null, null, null, null);
INSERT INTO `web_pay_log` VALUES ('1582006', '4720859', 'D4704048-A971-4BE2-9429-13B909AF08EE@u591', '8999', '7490.00', '1000000352413071', null, null, null, null, '1', null, null, '914001', '2017-11-15 12:32:18', 'RUB', '1', '0', '19', '0', '8', null, null, null, null, null);
INSERT INTO `web_pay_log` VALUES ('1582007', '4720859', 'D4704048-A971-4BE2-9429-13B909AF08EE@u591', '8999', '1490.00', '1000000352413213', null, null, null, null, '1', null, null, '914001', '2017-11-15 12:33:24', 'RUB', '1', '0', '19', '0', '8', null, null, null, null, null);
INSERT INTO `web_pay_log` VALUES ('1582008', '4720859', 'D4704048-A971-4BE2-9429-13B909AF08EE@u591', '8999', '299.00', '1000000352417391', null, null, null, null, '1', null, null, '914001', '2017-11-15 12:46:29', 'RUB', '1', '0', '19', '0', '8', null, null, null, null, null);
INSERT INTO `web_pay_log` VALUES ('1582009', '4720870', 'D4704048-A971-4BE2-9429-13B909AF08EE@u591', '8999', '299.00', '1000000354907858', null, null, null, null, '1', null, null, '914001', '2017-11-25 12:40:36', 'RUB', '1', '0', '19', '0', '8', null, null, null, null, null);
INSERT INTO `web_pay_log` VALUES ('1582010', '4720870', 'D4704048-A971-4BE2-9429-13B909AF08EE@u591', '8999', '75.00', '1000000354907863', null, null, null, null, '1', null, null, '914001', '2017-11-25 12:40:54', 'RUB', '1', '0', '19', '0', '8', null, null, null, null, null);
INSERT INTO `web_pay_log` VALUES ('1582011', '4720870', 'D4704048-A971-4BE2-9429-13B909AF08EE@u591', '8999', '379.00', '1000000354907874', null, null, null, null, '1', null, null, '914001', '2017-11-25 12:41:32', 'RUB', '1', '0', '19', '0', '8', null, null, null, null, null);
INSERT INTO `web_pay_log` VALUES ('1582012', '4720870', 'D4704048-A971-4BE2-9429-13B909AF08EE@u591', '8999', '1490.00', '1000000354907897', null, null, null, null, '1', null, null, '914001', '2017-11-25 12:42:38', 'RUB', '1', '0', '19', '0', '8', null, null, null, null, null);
INSERT INTO `web_pay_log` VALUES ('1582013', '4720870', 'D4704048-A971-4BE2-9429-13B909AF08EE@u591', '8999', '3790.00', '1000000354907950', null, null, null, null, '1', null, null, '914001', '2017-11-25 12:43:56', 'RUB', '1', '0', '19', '0', '8', null, null, null, null, null);
INSERT INTO `web_pay_log` VALUES ('1582014', '4720870', 'D4704048-A971-4BE2-9429-13B909AF08EE@u591', '8999', '7490.00', '1000000354908492', null, null, null, null, '1', null, null, '914001', '2017-11-25 12:45:32', 'RUB', '1', '0', '19', '0', '8', null, null, null, null, null);

-- ----------------------------
-- Table structure for web_player_limit
-- ----------------------------
DROP TABLE IF EXISTS `web_player_limit`;
CREATE TABLE `web_player_limit` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `server_id` int(11) NOT NULL,
  `operator` varchar(25) NOT NULL,
  `addtime` datetime NOT NULL,
  `reason` text NOT NULL,
  `player_id` int(11) NOT NULL,
  `player_name` varchar(30) NOT NULL,
  `type` smallint(1) NOT NULL DEFAULT '0',
  `endtime` int(4) NOT NULL DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=176 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_player_limit
-- ----------------------------

-- ----------------------------
-- Table structure for web_playergoods_log
-- ----------------------------
DROP TABLE IF EXISTS `web_playergoods_log`;
CREATE TABLE `web_playergoods_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `game_id` smallint(4) unsigned NOT NULL,
  `server_id` smallint(4) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `player_id` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `message` varchar(100) NOT NULL,
  `addtime` int(4) NOT NULL DEFAULT '0',
  `operator` varchar(30) NOT NULL,
  `verify_level` smallint(4) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `type1` smallint(4) unsigned NOT NULL,
  `param1` bigint(20) unsigned NOT NULL DEFAULT '0',
  `amount1` int(11) unsigned NOT NULL DEFAULT '0',
  `type2` smallint(4) DEFAULT NULL,
  `param2` bigint(20) unsigned DEFAULT '0',
  `amount2` int(11) unsigned DEFAULT '0',
  `type3` smallint(4) DEFAULT NULL,
  `param3` bigint(20) unsigned DEFAULT '0',
  `amount3` int(11) unsigned DEFAULT '0',
  `type4` smallint(4) DEFAULT NULL,
  `param4` bigint(20) unsigned DEFAULT '0',
  `amount4` int(11) unsigned DEFAULT '0',
  `remark` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1615 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_playergoods_log
-- ----------------------------

-- ----------------------------
-- Table structure for web_role
-- ----------------------------
DROP TABLE IF EXISTS `web_role`;
CREATE TABLE `web_role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `pid` smallint(6) DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `ename` varchar(5) DEFAULT NULL,
  `create_time` int(11) unsigned NOT NULL,
  `update_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parentId` (`pid`),
  KEY `ename` (`ename`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of web_role
-- ----------------------------
INSERT INTO `web_role` VALUES ('14', '普通管理员', '0', '1', '运营操作', null, '1386894906', '1465353011');
INSERT INTO `web_role` VALUES ('11', '系统管理员', '0', '1', '', null, '1385429792', '1386894859');
INSERT INTO `web_role` VALUES ('17', '文章编辑', '0', '1', '', null, '1471416654', '1471416654');
INSERT INTO `web_role` VALUES ('18', '口袋妖怪', '0', '1', '', null, '1474437110', '1474437110');
INSERT INTO `web_role` VALUES ('19', '手工充值', '0', '1', '', null, '1474946793', '1474946793');
INSERT INTO `web_role` VALUES ('20', '渠道', '0', '1', '', null, '1479173064', '1479173064');
INSERT INTO `web_role` VALUES ('21', 'erp流程', '0', '1', '手工充值、物品发放、多服补偿', null, '1481870135', '1481870135');
INSERT INTO `web_role` VALUES ('22', '运营人员', '0', '1', '', null, '1481899015', '1481899015');
INSERT INTO `web_role` VALUES ('23', '审核', '0', '1', '', null, '1481900550', '1481900550');

-- ----------------------------
-- Table structure for web_role_user
-- ----------------------------
DROP TABLE IF EXISTS `web_role_user`;
CREATE TABLE `web_role_user` (
  `role_id` mediumint(9) unsigned DEFAULT NULL,
  `user_id` char(32) DEFAULT NULL,
  KEY `group_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

-- ----------------------------
-- Records of web_role_user
-- ----------------------------
INSERT INTO `web_role_user` VALUES ('18', '430');
INSERT INTO `web_role_user` VALUES ('18', '417');
INSERT INTO `web_role_user` VALUES ('18', '418');
INSERT INTO `web_role_user` VALUES ('21', '432');
INSERT INTO `web_role_user` VALUES ('17', '415');
INSERT INTO `web_role_user` VALUES ('17', '414');
INSERT INTO `web_role_user` VALUES ('18', '415');
INSERT INTO `web_role_user` VALUES ('14', '414');
INSERT INTO `web_role_user` VALUES ('19', '413');
INSERT INTO `web_role_user` VALUES ('18', '413');
INSERT INTO `web_role_user` VALUES ('18', '412');
INSERT INTO `web_role_user` VALUES ('17', '412');
INSERT INTO `web_role_user` VALUES ('18', '432');
INSERT INTO `web_role_user` VALUES ('20', '433');
INSERT INTO `web_role_user` VALUES ('11', '413');
INSERT INTO `web_role_user` VALUES ('21', '431');
INSERT INTO `web_role_user` VALUES ('21', '414');
INSERT INTO `web_role_user` VALUES ('21', '413');
INSERT INTO `web_role_user` VALUES ('21', '412');
INSERT INTO `web_role_user` VALUES ('22', '432');
INSERT INTO `web_role_user` VALUES ('23', '413');
INSERT INTO `web_role_user` VALUES ('11', '1');
INSERT INTO `web_role_user` VALUES ('22', '431');
INSERT INTO `web_role_user` VALUES ('20', '429');

-- ----------------------------
-- Table structure for web_token
-- ----------------------------
DROP TABLE IF EXISTS `web_token`;
CREATE TABLE `web_token` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` int(11) unsigned NOT NULL,
  `game_id` smallint(4) NOT NULL DEFAULT '0',
  `token` text NOT NULL,
  `addtime` int(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `accountIdOrGameId` (`account_id`,`game_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=298128 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_token
-- ----------------------------
INSERT INTO `web_token` VALUES ('297998', '4720836', '8', 'VmIBa1A_bAGtfDgs6CmdRalI_aUHVTcFUlCmUFcgZsBWIAPlo0UW9bZQA4CmUINwJGUzMGPQBgAiwLMlAQDE4IOFYoAT5QZwA8XxcLfgpBURRSN1BlUyhVZAo1BUIGQwUyAGRaYFFlW2AASgpmCDICIlNnBmYANQJuC3RQOwx8CFFWbAFuUG4AOl9mC2EKM1FvUjRQZVMjVTMKeAVwBmsFcQA2WjVRb1tkADsKYgg4AjRTPwY3AGICMAsy', '1509092413');
INSERT INTO `web_token` VALUES ('297999', '4720836', '8', 'VWFeNANtDWYAUQ8_bDWADOFY7BCFRcgJyWjVSJVE7BGMEOg1jVmhYZlpiAG8MM1AUVjYNNlAwBCoPNlkZAEIGNlUrXmEDNA0xAEgPeg1GA0ZWMwQxUSoCM1plUhVRFAQzBGANN1ZiWGNaEABsDDZQcFZiDW1QZQRoD3BZMgBwBl9Vb14xAz0NNwA5D2UNNAM9VjAEMVEhAmRaKFInUTwEcAQyDWJWaFhnWmEAaAw8UGZWOg08UDIEPw8y', '1509092487');
INSERT INTO `web_token` VALUES ('298000', '4720836', '8', 'AzcKYAZoWjFbCgs6DWABOgFsU3ZRcgl5CWZUIwRuBWJTbV4wUG4LNQgwWDcPMFAUAGAHPAVlVXteZwJCDU8ENAN9CjUGMVpmWxMLfg1GAUQBZFNmUSoJOAk2VBMEQQUyUzdeZFBkCzAIQlg0DzVQcAA0B2cFMFU5XiECaQ19BF0DOQplBjhaYFtiC2ENNAE_aAWdTZlEhCW8Je1QhBGkFcVNlXjFQbgs0CDNYMA8_aUGcAZQc0BWJVYV5j', '1509100177');
INSERT INTO `web_token` VALUES ('298001', '4720837', '8', 'WGwLYVU7DWZYCV9uCWQAO10wAidQcwZ2DWIHcAVvB2AEOlk3AD4LQwE4DGcJMVdiARVWEwUWBykLMQI_bAUMDRFgmCzRVFA0wWGNfKgk5AD9dPgJAUCsGMw0wB0QFQgdCBGJZZgAzCzYBPgwXCThXdwE1VjYFMAdrC3QCaQFxA1pYYgtkVWsNN1hhXzUJMAA_bXTsCNlAgBmANfwdyBWgHcwQyWTYAPgs0AToMZAk5V2IBYVZjBWcHMQsy', '1509356453');
INSERT INTO `web_token` VALUES ('298002', '4720837', '8', 'ADRcNlI8DGdcDQs6DGEMNwdqUXQAIwZ2WzRQJwJoCW5QbghmUmxYEFtiCGNdZVFkUkYDRgMQUX8NNwQ4DE5SFQB_bXGNSEwwxXGcLfgw8DDMHZFETAHsGM1tmUBMCRQlMUDYIN1JhWGVbZAgTXWxRcVJmA2MDNlE9DXIEbwx8UgsAOlwzUmwMNlxlC2EMNQwyB2FRZQBwBmBbKVAlAm8JfVBmCGdSbFhnW2AIYF1tUWNSNQMxA2RRZQ0w', '1509421177');
INSERT INTO `web_token` VALUES ('298003', '4720838', '8', 'AzcLYQNtXTZbCl9uCmdVbgdqASRXdAFxWzQDdFI4UzRZZwhmUG5YYVtiDmFdbQMzXE0BOlEyUnxcZFhuUhABRQN9CzQDQ11rW2BfKgpBVWsHZgE3VywBMlsQA0VSZ1NiWUwIMFBmWBVbZg5jXWwDI1xoAWFRZFI_bXCNYM1IiAVgDOQtkAz1dZ1tiXzUKM1VrB2EBOlcnAWdbKQN2Uj9TJ1lvCGdQblhnW2AOZl1tAzFcOwEzUTBSYVxu', '1509421708');
INSERT INTO `web_token` VALUES ('298004', '4720838', '8', 'BDAJY1c5C2AAUVtqCWRSaQFsDShRclIiAW5ZLgZsB2BVaw9hDDIPNgsyCGddbVVlBxYNNlk6AixbY1RiVxVWEgR6CTZXFws9ADtbLglCUmwBYA07USpSYQFKWR8GMwc2VUAPNww6D0ILNghlXWxVdQczDW1ZbAJuWyRUP1cnVg8EPglmV2kLMQA5WzEJMFJsAWcNNlEhUjQBc1ksBmsHc1VjD2AMMg8wCzAIYF1tVWcHYA08WT0CM1tl', '1509422224');
INSERT INTO `web_token` VALUES ('298005', '4720838', '8', 'VGBcNgRqXzQOX11sWDVRalM_bDShVdgFxXTJXIFU_aBWJVawtlBDoIMVtiDWJfbwc3XE1TaAdkVnhdZVhuAkAARFQqXGMERF9pDjVdKFgTUW9TMg07VS4BMl0WVxFVYAU0VUALMwQyCEVbZg1gX24HJ1xoUzMHMlY6XSJYMwJyAFlUblwzBDpfZQ43XTdYYVFvUzUNNlUlAWddL1ciVTgFcVVjC2QEOgg3W2ANZV9vBzVcO1NiB2RWZV1g', '1509422507');
INSERT INTO `web_token` VALUES ('298006', '4720838', '8', 'UGQNZwNtXTZbCltqWDVVblI_aV3JYewBwCGdWIQJoCG9Qblo0VWtfZgozWDcOPgY2V0ZTaAJhUX8LMwcxB0VUEFAuDTIDQ11rW2BbLlgTVWtSM1dhWCMAMwhDVhACNwg5UEVaYlVjXxIKN1g1Dj8GJldjUzMCN1E9C3QHbAd3VA1Qag1iAz1dZ1tiWzFYYVVrUjRXbFgoAGYIelYjAm8IfFBmWjVVa19gCjFYMA4_bBjRXMVNhAmxRYQs0', '1509431835');
INSERT INTO `web_token` VALUES ('298007', '4720838', '8', 'UGRdNwxiAWpdDF9uCWRQa1A9VHEAIwFxDmEAdwRuUTZVa1o0UmwIMQkwAG8NPQY2BRRQa1g7CScKMlNlAkADR1AuXWIMTAE3XWZfKglCUG5QMVRiAHsBMg5FAEYEMVFgVUBaYlJkCEUJNABtDTwGJgUxUDBYbQllCnVTOAJyA1pQal0yDDIBO11kXzUJMFBuUDZUbwBwAWcOfAB1BGlRJVVjWjVSbAg3CTIAaA09BjQFY1BgWD0JOAo1', '1509433325');
INSERT INTO `web_token` VALUES ('298008', '4720839', '8', 'AjYMZg1jCGMOXwo7XDEFPlc6ACUAI1QkWjUFcgJoCG8DPVk3DDJYYg85CGJfFwExUz4FM1lOBSsNPgdGBTBTFgJ8DDMNSAg3DkIKf1xsBTJXNwBAAHtUEVpmBTgCRwhMA2RZFAw2WGQPNAhhX2cBIVNnBWVZbAVpDXIHbAV1UwoCOAxjDTMIMg43CmBcZQU7VzEAOgBwVDJaKAVwAm8IfAM1WTYMMlhnDzQIYF9vATNTNQUzWT4FMQ0x', '1509435176');
INSERT INTO `web_token` VALUES ('298009', '4720840', '8', 'AzdcNgFvWjEBUA08CGVSaVE8BiMCIVQkD2AEcwVvVTIAPls1Az1YEw1FWEUNMA0_bVzcBMlk2UX8AOFZlUhQITwN9XGMBRFoWAToNeAhDUhBRRQY2AnlUFA9HBDgFNVUXABdbEgNGWGQNRlhGDTINLVdjAWFZbFE9AH9WPVIiCFEDOVwzAT9aYAE4DWcIMVJsUTAGNQJyVDIPfQRxBWhVIQA2WzQDPVhnDTZYMA09DT9XMQE0WTlRZQA6', '1509436670');
INSERT INTO `web_token` VALUES ('298010', '4720840', '8', 'BzMOZFc5DGdbCgg5AG1VbgFsAyZQcwZ2CmUDdAZsAmUAPghmUW9dFglBDBEJNFBjUjIGNVM8BiheZlVmB0EITwd5DjFXEgxAW2AIfQBLVRcBFQMzUCsGRgpCAz8GNgJAABcIQVEUXWEJQgwSCTZQcFJmBmZTZgZqXiFVPgd3CFEHPQ5hV2kMNltiCGIAOVVrAWADMFAgBmAKeAN2BmsCdgA2CGdRb11iCTIMZAk5UGJSMwY1UzIGM15j', '1509440767');
INSERT INTO `web_token` VALUES ('298011', '4720841', '8', 'VGAKYFU7D2QAUQ8_bWjcCOVY7VHEEJwV1WzQFcgVvA2QHOQ5gVmgOMgs8D2cJNFJmB2EMPFg3AC4INlQVDTgFNFQqCjVVYA85AD4PeloSAjxWM1QRBH8FNlthBTMFRwM9B2AONVZmDkILNw9nCTZScgczDGxYbQBsCHdUPw19BVxUbgplVWsPNQA5D2VaYwI8VjdUZgR0BWNbKQVwBWgDdwcxDmFWaA4xCzAPZwk5UmAHZgw9WD4AMQg6', '1509442028');
INSERT INTO `web_token` VALUES ('298012', '4720842', '8', 'BTEOZFU7D2QAUQw9DWANNgVoACVZegR0AG8HcAVvAGdUaglnBDoKPFtnW2BYMFZjBzNWbQBhBzYOYgNlV2QHOQVkDjZVZw8nAG8MNw1nDWQFJQBtWXsEWABjB2YFPAAyVDAJMAQ3CjxbYVsxWHdWNQcqViUAPwd2DmEDYlduBzAFYw41VW8PNAA_aDGINNQ05BWM=', '1509516123');
INSERT INTO `web_token` VALUES ('298013', '4720842', '8', 'VWEPZQ1jXTZaC1xtCmcNNlM_bDShTcAV1DmFYLwVvCG8FO1wyDDJcal9jW2ALYwcyBTFTaAdmVmcOYlE3ATIHOVU0DzcNP111WjVcZwpgDWRTcw1gU3EFWQ5tWDkFPAg6BWFcZQw_aXGpfZVsxCyQHZAUoUyAHOFYnDmFRMAE4BzBVMw80DTddZlplXDIKOw0_aUzA=', '1509516846');
INSERT INTO `web_token` VALUES ('298014', '4720842', '8', 'U2dbMQ1jWzBaC11sAWxXbFQ5UHVScQl5DmEDdABqBGMFOwpkDDIJPws3W2AMZFFkV2NUb1Q1U2IOYgVjBzQINlMyW2MNP1tzWjVdZgFrVz5UdFA9UnAJVQ5tA2IAOQQ2BWEKMww_aCT8LMVsxDCNRMld6VCdUa1MiDmEFZAc_bCD9TNVtgDTdbYFplXTIBOFdhVDk=', '1509517008');
INSERT INTO `web_token` VALUES ('298015', '4720844', '8', 'VWFeNANtCGMLWg08AG1SaQJvAicEJ1UlWjUDdFE7UjVTbQ5gUG4PNFxmDWFcEQA_aBWVWZlg_aAS8PPFcQBjoBQ1UrXmEDMwgzC0ENeAAxUmICZQJEBH9VblpkAzdREVISUzIORlBlDzdcYw1nXBcAIAUxVjZYbQFtD3BXPAZ2AVhVb14xAz0IMgsyDWcAOVJsAmMCNQR0VTNaKAN2UTxSJlNlDmFQbg8wXGcNZVxsADMFYlZnWD0BNQ82', '1509522373');
INSERT INTO `web_token` VALUES ('298016', '4720844', '8', 'VGALYQFvCmELWlprXTBSaVwxBSADIAd3CGcFclc9VjFUaghmDDJdZgsxCmYOQ1ZpVTVTYwJlUX9baABHV2sJS1QqCzQBMQoxC0FaL11sUmJcOwVDA3gHPAg2BTFXF1YWVDUIQAw5XWULNApgDkVWdlVhUzMCN1E9WyQAa1cnCVBUbgtkAT8KMAsyWjBdZFJsXD0FMgNzB2EIegVwVzpWIlRiCGcMMl1iCzAKYg4_bVmVVMlNiAmBRa1th', '1509522490');
INSERT INTO `web_token` VALUES ('298017', '4720844', '8', 'AzdZMwZoDWZYCQ4_aXTADOAJvAyZTcFYmDGNXIAFrVDNUalwyVWsLMA40CmYPQgwzUzMCMlYxVHpaaQJFAz8HRQN9WWYGNg02WBIOe11sAzMCZQNFUyhWbQwyV2MBQVQUVDVcFFVgCzMOMQpgD0QMLFNnAmJWY1Q4WiUCaQNzB14DOVk2BjgNN1hhDmRdZAM9AmMDNFMjVjAMflciAWxUIFRiXDNVaws0DjUKYg8_aDD9TNAIyVjBUYVpk', '1509523064');
INSERT INTO `web_token` VALUES ('298018', '4720844', '8', 'VmJeNFE_aXzQOXwk4DWABOlM_bBSAFJgR0AG8CdVI4VjEEOghmUW9dZgw2CmYKR1ZpAmIAMFI1UnwJOlkeBzsGRFYoXmFRYV9kDkQJfA08ATFTNAVDBX4EPwA_bAjZSElYWBGUIQFFkXWUMMwpgCkFWdgI2AGBSZ1I_bCXZZMgd3Bl9WbF4xUW9fZQ43CWMNNAE_aUzIFMgV1BGIAcgJ3Uj9WIgQyCGdRb11iDDcKYgo6VmUCZQAwUjJSYwk0', '1509523627');
INSERT INTO `web_token` VALUES ('298019', '4720844', '8', 'UWVZM1A_bCmEBUFtqAG0BOgBtU3ZQcwNzDmFUI1I4CG9YZgFvAjxYY11nCmYKRwY5VTUAMFE2U30IOwVCUm4BQ1EvWWZQYAoxAUtbLgAxATEAZ1MVUCsDOA4wVGBSEghIWDkBSQI3WGBdYgpgCkEGJlVhAGBRZFM_aCHcFblIiAVhRa1k2UG4KMAE4WzEAOQE_aAGFTZFAgA2UOfFQhUj8IfFhuAW4CPFhnXWYKYgo6BjVVMgAwUT5TZwg0', '1509523976');
INSERT INTO `web_token` VALUES ('298020', '4720844', '8', 'U2cOZFA_bXTYAUQ4_aWzYMN1Q5UHUCIQl5XTJSJVY8AGcAPlk3UG4NNl9lDmIMQQI9UzNUZFA3AC5aaVUSBjpRE1MtDjFQYF1mAEoOe1tqDDxUM1AWAnkJMl1jUmZWFgBAAGFZEVBlDTVfYA5kDEcCIlNnVDRQZQBsWiVVPgZ2UQhTaQ5hUG5dZwA5DmRbYgwyVDVQZwJyCW9dL1InVjsAdAA2WTZQbg0yX2QOZgw8AjFTNFRjUDYANVpm', '1509524066');
INSERT INTO `web_token` VALUES ('298021', '4720842', '8', 'AzdcNgZoDmUPXg8_bXDEEP1Y7ByJRclUlC2QHcFA6AGdSbFwyVmgANg8zCzAMZFdiU2dUb1U0CDlaNgRiVmUJNwNiXGQGNA4mD2APNFw2BG1WdgdqUXNVCQtoB2ZQaQAyUjZcZVZlADYPNQthDCNXNFN_bVCdVagh5WjUEZVZvCT4DZVxnBjwONQ8zD2JcYAQ7VjM=', '1509525590');
INSERT INTO `web_token` VALUES ('298022', '4720844', '8', 'UmYPZQVrCmEOX1prWzYDOFY7BSBUdwJyCWZXIARuVTJZZwpkDTNaYV1nCmYPQgE_bXDwBMVk_bAC4PPAVCV2sERlIsDzAFNQoxDkRaL1tqAzNWMQVDVC8COQk3V2MERFUVWTgKQg04WmJdYgpgD0QBIVxoAWFZbABsD3AFblcnBF1SaA9gBTsKMA43WjBbYgM9VjcFMlQkAmQJe1ciBGlVIVlvCmUNM1plXWYKYg8_aATJcOwE0WTgAMw88', '1509526709');
INSERT INTO `web_token` VALUES ('298023', '4720844', '8', 'VmJcNg1jXTYBUF1sWDUNNlU4AicFJgd3AW4FcgdtB2BUaghmAD4JMggyWDQJRAI9VzdUZARjVnhbaAJFVmpSEFYoXGMNPV1mAUtdKFhpDT1VMgJEBX4HPAE_aBTEHRwdHVDUIQAA1CTEIN1gyCUICIldjVDQEMVY6WyQCaVYmUgtWbFwzDTNdZwE4XTdYYQ0zVTQCNQV1B2EBcwVwB2oHc1RiCGcAPgk2CDNYMAk5AjFXMFRgBGZWZltm', '1509527437');
INSERT INTO `web_token` VALUES ('298024', '4720846', '8', 'UmZeNABuCmEKWwo7DWABOlc6AyYDIFUlXjECdVY8BWJVaw5gVWtdZgszWjJfFwYxARYANwVqByldYFluBzQCRVIsXmEAMwo2CjUKfw1FAUVXQQM5A3hVY15gAkZWYwU0VT8ONVVnXWILMlpAXxQGJgE1AGAFMAdrXSJZMgd3AltSaF4xAD4KMAozCmANNAE_aVzYDNgNzVTNeLAJ3VjsFcVVjDmFVa11iCzBaMl9vBjUBZgA6BWIHPV1i', '1509529195');
INSERT INTO `web_token` VALUES ('298025', '4720846', '8', 'BzNZM1Y4WzALWlprWzYNNlM_bAyZTcFIiC2QDdFA6CW5Qbg9hUmwNNgkxD2cLQwE2UUYFMlk2BykBPFBnUWJUEwd5WWZWZVtnCzRaL1sTDUlTRQM5UyhSZAs1A0dQZQk4UDoPNFJgDTIJMA8VC0ABIVFlBWVZbAdrAX5QO1EhVA0HPVk2VmhbYQsyWjBbYg0zUzIDNlMjUjQLeQN2UD0JfVBmD2BSbA0yCTIPZws7ATJRNgU_aWTYHNgEy', '1509529929');
INSERT INTO `web_token` VALUES ('298026', '4720842', '8', 'BDAKYAZoXzRdDFxtDmMEP1I_aAicEJ1UlCWYCdQNpAGcEOlk3VmgKPA0xXGcPZw04XWkNNlIzAjMAbFg_bUmFTbQRlCjIGNF93XTJcZw5kBG1ScgJvBCZVCQlqAmMDOgAyBGBZYFZlCjwNN1w2DyANbl1wDX5SbQJzAG9YOVJrU2QEYgoxBjxfZF1hXD0OPgQxUjE=', '1509529936');
INSERT INTO `web_token` VALUES ('298027', '4720846', '8', 'BDABawxiCmFdDA8_bCWRQa1E8V3JWdQZ2DGNUIwVvAWYFOw1jDDIIM1piAWlYEFZhVUJRZlY5AS8LNlFmADMBRgR6AT4MPwo2XWIPeglBUBRRR1dtVi0GMAwyVBAFMAEwBW8NNgw_bCDdaYwEbWBNWdlVhUTFWYwFtC3RROgBwAVgEPgFuDDIKMF1kD2UJMFBuUTBXYlYmBmAMflQhBWgBdQUzDWIMMgg3WmEBaVhoVmVVM1FiVjABMQsx', '1509530030');
INSERT INTO `web_token` VALUES ('298028', '4720847', '8', 'VmIMZgRqAWpaCww9XTBQa1A9V3JUd1MjWzRWIQJoUzQCPAxiV2kIMw83WzMPRwE2XEtRZgNsBCoMMVdgADMDRFYoDDMENwE9WmUMeV0VUBRQRldtVC9TZVtlVhICN1NiAmgMN1dlCDcPNltBD0QBIVxoUTEDNgRoDHNXPABwA1pWbAxjBDoBO1pjDGZdZFBuUDFXY1QkUzVbKVYjAm9TJwI0DGNXaQg3DzRbMw8_aATJcOlFiA2UEPgw0', '1509530092');
INSERT INTO `web_token` VALUES ('298029', '4720848', '8', 'UWVaMAFvXzQBUA4_aDWBRagZrDShRcgJyCWZWIQVvAmVTbQBuVmgIPgg0ATpbMwM2XWkAO1U0VWQOYlYwVWYBP1EwWmIBM193AW4ONQ1nUTgGJg1gUXMCXglqVjcFPAIwUzcAOVZlCD4IMgFhW3QDYF1wAHNValUkDmFWN1VsATZRN1phATtfZAE8DmYNPVFvBmQ=', '1509530987');
INSERT INTO `web_token` VALUES ('298030', '4720847', '8', 'AzdbMQRqCGNfDlhpXzJXbAFsBiMFJlQkWzQAd1E7UzQDPQ5gBjgPNFxkCWFaEgI1UUYCNQRrBCoKNwM0UGMJTgN9W2QENwg0X2BYLV8XVxMBFwY8BX5UYltlAERRZFNiA2kONQY0DzBcZQkTWhECIlFlAmIEMQRoCnUDaFAgCVADOVs0BDoIMl9mWDJfZldpAWAGMgV1VDJbKQB1UTxTJwM1DmEGOA8wXGcJYVpqAjFRNwIxBGsEPgo5', '1509530999');
INSERT INTO `web_token` VALUES ('298031', '4720847', '8', 'VmJeNFY4CmFdDF9uDGEEPwVoVnMDIABwD2BXIA1nUTYAPgpkBTsLMAgwDmZdFQUyARZTZAdoUX9bZlFmUGMJTlYoXmFWZQo2XWJfKgxEBEAFE1ZsA3gANg8xVxMNOFFgAGoKMQU3CzQIMQ4UXRYFJQE1UzMHMlE9WyRROlAgCVBWbF4xVmgKMF1kXzUMNQQ6BWRWYgNzAGYPfVciDWBRJQA2CmUFOws0CDMOZl1tBTYBZ1NhB2FRZ1ti', '1509531053');
INSERT INTO `web_token` VALUES ('298032', '4720849', '8', 'VGAMZgZoDGdYCQo7AG1QawFsUHUFJgR0DGMEcw1nBmEEOghmBTsNQl0XARpdZVJmUkRUYFlOCScOMQJEAEJSYlQqDDMGRwxDWBcKfwBIUG8BYVBnBX4EMwwyBDQNOQZGBG8IMQVFDUZdZwFtXW1SclJmVDRZbAllDnECaQBwUgtUbgxjBjgMNlhhCmAAOVBuAWBQagV1BGIMfgRxDWAGcgQyCGcFOw0yXWYBaV1tUmFSNFRmWTwJOg40', '1509531300');
INSERT INTO `web_token` VALUES ('298033', '4720850', '8', 'UmYLYQBuWzABUF9uXzIHPFI_aDCkCIVQkDmFTJFI4AWZZZw5gVWtcY11rCmoJNQU2VDQENQVqUnwONFZmVmpUa1IsCzQAQVsWATxfKl8XB0RSMwxLAnlUYg4zUxNSZAExWTMONFViXBZdEQoQCTEFJVRgBGQFMFI_bDnFWPVYmVA1SaAtkAD5bYQE4XzVfZgc5UjIMPwJyVDIOfFMmUj8BdVlvDmFVa1xjXWYKYgk5BTZUMgQ1BWFSYA4x', '1509532215');
INSERT INTO `web_token` VALUES ('298034', '4720850', '8', 'AjYIYgdpCGMIWQ4_aDWAMN1Q5V3JYe1IiCmUAd1Y8BmFQbg5gUmxdYl9pAWFbZ1BjXDxUZVU6BihbYQIyDDBSbQJ8CDcHRghFCDUOew1FDE9UNVcQWCNSZAo3AEBWYAY2UDoONFJlXRdfEwEbW2NQcFxoVDRVYAZqWyQCaQx8UgsCOAhnBzkIMggxDmQNNAwyVDRXZFgoUjQKeAB1VjsGclBmDmFSbF1iX2QBaVtrUGNcOlRlVToGN1tn', '1509532926');
INSERT INTO `web_token` VALUES ('298035', '4720850', '8', 'U2ddNwRqWDNdDFxtAWwEP1Q5ByJRcgl5WjVQJw1nUjUCPF4wDTMLNF1rDGwAPFFiBmYHNgVqCSdZY1ZmDTEDPFMtXWIERVgVXWBcKQFJBEdUNQdAUSoJP1pnUBANO1JiAmheZA06C0FdEQwWADhRcQYyB2cFMAllWSZWPQ19A1pTaV0yBDpYYl1kXDYBOAQ6VDQHNFEhCW9aKFAlDWBSJgI0XjENMws0XWYMZAAwUWIGYAcwBWsJPFlq', '1509534869');
INSERT INTO `web_token` VALUES ('298036', '4720850', '8', 'UmYPZQ1jAGtdDA08CmcEP1A9AicCIVYmCWZQJ1E7UjVTbV0zAD4JNg85WzsLNwMwAWFWZwJtAC5dZ1lpUm5ValIsDzANTABNXWANeApCBEdQMQJFAnlWYAk0UBBRZ1JiUzldZwA3CUMPQ1tBCzMDIwE1VjYCNwBsXSJZMlIiVQxSaA9gDTMAOl1kDWcKMwQ6UDACMQJyVjAJe1AlUTxSJlNlXTIAPgk2DzRbMws7AzABZ1ZgAmwANF1l', '1509535872');
INSERT INTO `web_token` VALUES ('298037', '4720848', '8', 'WGwAag1jAWoJWF9uCWQFPlA9ACVYe1IiWzRUIwxmVjFRbwFvAz0KPAA8XWYIYAE0UGQFPgRlBTQBbVA2BTZRb1g5ADgNPwEpCWZfZAljBWxQcABtWHpSDls4VDUMNVZkUTUBOAMwCjwAOl09CCcBYlB9BXYEOwV0AW5QMQU8UWZYPgA7DTcBOgk0XzEJMQU6UDA=', '1509536195');
INSERT INTO `web_token` VALUES ('298038', '4720850', '8', 'BDALYVU7DmUMXQs6XzIBOlI_aBCFUd1EhCWYCdVU_aBWIAPl0zDDIKNVpsWzsNMQw_aUzNTYlY5BihdZwAwAT0APwR6CzRVFA5DDDELfl8XAUJSMwRDVC9RZwk0AkJVYwU1AGpdZww7CkBaFltBDTUMLFNnUzNWYwZqXSIAawFxAFkEPgtkVWsONAw1C2FfZgE_aUjIEN1QkUTcJewJ3VTgFcQA2XTIMMgo1WmFbMw09DD9TNVNmVjYGNl1g', '1509536637');
INSERT INTO `web_token` VALUES ('298039', '4720850', '8', 'AzcKYAZoAWpfDg8_bCWRXbABtByJTcABwCGdQJwZsBWJYZg5gDTMNMg85DW1aZgMwUzNXZgBvBykNN1hoVmoAPwN9CjUGRwFMX2IPeglBVxQAYQdAUygANgg1UBAGMAU1WDIONA06DUcPQw0XWmIDI1NnVzcANQdrDXJYM1YmAFkDOQplBjgBO19mD2UJMFdpAGAHNFMjAGYIelAlBmsFcVhuDmENMw0yDzQNZVpqAzBTNVdjAGYHNQ0w', '1509537017');
INSERT INTO `web_token` VALUES ('298040', '4720848', '8', 'U2daMFA_bCmEIWQg5DWAAO1E8UXQCIQd3WTYAdwJoA2RSbFo0AT8JPwA8ADsAaAUwUmYBOlQ1BjddMVYwUWJTbVMyWmJQYgoiCGcIMw1nAGlRcVE8AiAHW1k6AGECOwMxUjZaYwEyCT8AOgBgAC8FZlJ_aAXJUawZ3XTJWN1FoU2RTNVphUGoKMQg1CGcNMwAyUTU=', '1509537741');
INSERT INTO `web_token` VALUES ('298041', '4720850', '8', 'V2MKYFA_bXDdfDgo7WDUNNgVoASQCIVQkD2BUIwRuBmEAPgFvDDJcY1ttAWELNwU2UTFXZgdoU30AOlNjADwIN1cpCjVQEVwRX2IKf1gQDU4FZAFGAnlUYg8yVBQEMgY2AGoBOww7XBZbFwEbCzMFJVFlVzcHMlM_aAH9TOABwCFFXbQplUG5cZl9mCmBYYQ0zBWUBMgJyVDIPfVQhBGkGcgA2AW4MMlxjW2ABaQs7BTZRN1dsB2FTZgA6', '1509538060');
INSERT INTO `web_token` VALUES ('298042', '4720850', '8', 'UWUMZgZoDGdcDQ4_aXDENNlM_bU3YEJwJyAG9VIlY8AmVXaVs1V2kKNQA2XDwNMQQ3VzdWZ1k2BigMNgc3BztWaVEvDDMGRwxBXGEOe1wUDU5TMlMUBH8CNAA9VRVWYAIyVz1bYVdgCkAATFxGDTUEJFdjVjZZbAZqDHMHbAd3Vg9RawxjBjgMNlxlDmRcZQ0zUzNTYAR0AmQAclUgVjsCdldhWzRXaQo1ADtcNA09BDdXMVZtWTsGPAw2', '1509538490');
INSERT INTO `web_token` VALUES ('298043', '4720850', '8', 'AzcAalU7AGsLWgw9DmNRalM_bBSACIQV1CGdTJFA6UTYFO1wyAD4NMgg_bXT1dYVVmUTEFNAJtVHoLMQU1Um4JNgN9AD9VFABNCzYMeQ5GURJTMgVCAnkFMwg1UxNQZlFhBW9cZgA3DUcIRF1HXWVVdVFlBWUCN1Q4C3QFblIiCVADOQBvVWsAOgsyDGYON1FvUzMFNgJyBWMIelMmUD1RJQUzXDMAPg0yCDNdNV1tVWZRNwU_bAmFUYAs2', '1509538577');
INSERT INTO `web_token` VALUES ('298044', '4720850', '8', 'ADQPZQdpD2RYCQAxDWBRalE8BSBWdQh4DmFWIQVvVjFTbV0zBjgNMlpsAGAAPAMwAmJXZgVqVngJMwIyAz8APwB_bDzAHRg9CWGUAdQ1FURJRMAVCVi0IPg4zVhYFM1ZmUzldZwYxDUdaFgAaADgDIwI2VzcFMFY6CXYCaQNzAFkAOg9gBzkPNVhhAGoNNFFvUTEFNlYmCG4OfFYjBWhWIlNlXTIGOA0yWmEAaAAwAzACZFdsBWZWYgk7', '1509538578');
INSERT INTO `web_token` VALUES ('298045', '4720850', '8', 'UWUBa1A_bWzBdDFtqDGFSaVA9U3ZYe1MjWjVYLwNpAWYAPlo0BzkBPlttAGAJNVVmVjYGN1k2AiwIMlFhBzsEO1EvAT5QEVsWXWBbLgxEUhFQMVMUWCNTZVpnWBgDNQExAGpaYAcwAUtbFwAaCTFVdVZiBmZZbAJuCHdROgd3BF1RawFuUG5bYV1kWzEMNVJsUDBTYFgoUzVaKFgtA24BdQA2WjUHOQE_bW2AAaAk5VWZWMAY9WToCOQgz', '1509538581');
INSERT INTO `web_token` VALUES ('298046', '4720848', '8', 'UmZeNAZoCWIIWQEwDGFSaVE8ByIDIAZ2XDNQJ1I4BGNRbwtlAD4NO1tnXGcJYQE0XGgHPFY3BDUPYwdhUGMFO1IzXmYGNAkhCGcBOgxmUjtRcQdqAyEGWlw_aUDFSawQ2UTULMgAzDTtbYVw8CSYBYlxxB3RWaQR1D2AHZlBpBTJSNF5lBjwJMggyAWkMMVJmUT0=', '1509540429');
INSERT INTO `web_token` VALUES ('298047', '4720850', '8', 'BzMMZgZoCGNcDQ4_aWjdQawdqU3ZRclYmCGcCdQNpAWZWaFk3BTtaZQ44C2sINAY1UDABMFE_bUnwLMVNjAT0GOQd5DDMGRwhFXGEOe1oSUBMHZlMUUSpWYAg1AkIDNQExVjxZYwUyWhAOQgsRCDAGJlBkAWFRZFI_bC3RTOAFxBl8HPQxjBjgIMlxlDmRaY1BuB2dTYFEhVjAIegJ3A24BdVZgWTYFO1plDjULYwg4BjVQMQEwUTZSaAs1', '1509542194');
INSERT INTO `web_token` VALUES ('298048', '4720850', '8', 'VGAIYgRqAWpdDA4_aAWxXbFM_bVHFVdgBwCWYEc1c9AGcDPV0zDTMBPgo8DW0INAMwXT1WZwVqVHpZY1lpUW1Ua1QqCDcERQFMXWAOewFJVxRTMlQTVS4ANgk0BERXYQAwA2ldZw06AUsKRg0XCDADI11pVjYFMFQ4WSZZMlEhVA1UbghnBDoBO11kDmQBOFdpUzNUZ1UlAGYJewRxVzoAdAM1XTINMwE_bCjENZQg4AzBdPFZnBWZUZlli', '1509542511');
INSERT INTO `web_token` VALUES ('298049', '4720850', '8', 'UGQJYwxiCmEJWAs6AWwFPlI_aDClScQR0CGcAd1I4BmFWaFs1DDIKNVhuXT0APA0_bXT0ENVM8BCpaYAc3BzsJNlAuCTYMTQpHCTQLfgFJBUZSMwxLUikEMgg1AEBSZAY2VjxbYQw7CkBYFF1HADgNLV1pBGRTZgRoWiUHbAd3CVBQaglmDDIKMAkwC2EBOAU7UjIMP1IiBGIIegB1Uj8GclZgWzQMMgo1WGNdNQAwDT5dPAQ0UzAENFpg', '1509543530');
INSERT INTO `web_token` VALUES ('298050', '4720850', '8', 'BDAPZQZoAWoNXA8_bWDVRagZrASRRcgJyC2QDdFE7VTJQbg1jBjgAP1psWDgOMlZlUDAGN1M8BSsLMVFhUW0FOgR6DzAGRwFMDTAPelgQURIGZwFGUSoCNAs2A0NRZ1VlUDoNNwYxAEpaFlhCDjZWdlBkBmZTZgVpC3RROlEhBVwEPg9gBjgBOw00D2VYYVFvBmYBMlEhAmQLeQN2UTxVIVBmDWIGOAA_aWmFYMA4_bVmVQMQY2Uz0FPwsx', '1509543890');
INSERT INTO `web_token` VALUES ('298051', '4720852', '8', 'VGBZMwFvWDNcDQ8_bXzIHPFY7UHVWdVEhXjFQJwRuAWYHOQpkBTsKRVoXChcKNQUzAmEMO1Q1BCoKN1FtAz4AMlQqWWYBNlhuXGEPel9uBzRWM1BqVi1RE14QUGMEMQFEB2EKNgVACjRaFQpkCjIFJQI2DGxUYQRoCnVROgNzAFlUblk2AT9YYlxlD2VfZgc5VjZQYVYmUTdeLFAlBGkBdQcxCmUFOwo1WmEKYgo6BTYCbgw3VDcEMwo2', '1509598546');
INSERT INTO `web_token` VALUES ('298052', '4720852', '8', 'VGBeNAVrC2AJWA08XTBWbQVoVnMCIVEhDmEAdwZsBmFRbw5gBjgATwxBARxcYwQyVTZXYAdmAS8MMQQ4BDkAMlQqXmEFMgs9CTQNeF1sVmUFYFZsAnlREw5AADMGMwZDUTcOMgZDAD4MQwFvXGQEJFVhVzcHMgFtDHMEbwR0AFlUbl4xBTsLMQkwDWddZFZoBWVWZwJyUTcOfAB1BmsGclFnDmEGOAA_aDDcBaVxsBDRVMFdlB2QBNww3', '1509601551');
INSERT INTO `web_token` VALUES ('298053', '4720852', '8', 'BDALYQZoD2QPXgEwAWxWbQdqBCECIQBwXjFZLlI4VjFSbF0zBjhYFwxBWkcMM1BmUjEDNFIzUnwKN1ZqVWgGNAR6CzQGMQ85DzIBdAEwVmUHYgQ_bAnkAQl4QWWpSZ1YTUjRdYQZDWGYMQ1o0DDRQcFJmA2NSZ1I_bCnVWPVUlBl8EPgtkBjgPNQ82AWsBOFZoB2cENQJyAGZeLFksUj9WIlJkXTIGOFhnDDdaMgw8UGBSNwMzUjFSaQow', '1509603580');
INSERT INTO `web_token` VALUES ('298054', '4720852', '8', 'BzMKYA1jXzQPXltqXzJXbAJvVHEHJAFxCmUDdAVvAGdZZwhmV2lbFAlEWkcMM1BmBWZUY1EwCScJNFRoAj8DMQd5CjUNOl9pDzJbLl9uV2QCZ1RuB3wBQwpEAzAFMABFWT8INFcSW2UJRlo0DDRQcAUxVDRRZAllCXZUPwJyA1oHPQplDTNfZQ82WzFfZldpAmJUZQd3AWcKeAN2BWgAdFlvCGdXaVtkCTJaMgw8UGAFYFRjUTUJPQkx', '1509604272');
INSERT INTO `web_token` VALUES ('298055', '4720852', '8', 'ADRdNwdpCGNdDAk4CWQMN1c6AidVdgZ2D2ACdQFrUzRTbVwyAz0MQ1wRDxJdYlZgVDcHMFEwAy0BPFZqAz4BMwB_bXWIHMAg_bXWAJfAk4DD9XMgI4VS4GRA9BAjEBNFMWUzVcYANGDDJcEw9hXWVWdlRgB2dRZANvAX5WPQNzAVgAOl0yBzkIMl1kCWMJMAwyVzcCM1UlBmAPfQJ3AWxTJ1NlXDMDPQwzXGcPZ11tVmZUMQcwUTIDNAE8', '1509604547');
INSERT INTO `web_token` VALUES ('298056', '4720852', '8', 'U2cAagxiCGMIWQw9XDFXbFM_bAicFJgh4D2BTJFA6AWZUagFvUmwPQAtGWEUNMgE3VDdWYQRlAixdYFJuAj9SYFMtAD8MOwg_bCDUMeVxtV2RTNgI4BX4ISg9BU2BQZQFEVDIBPVIXDzELRFg2DTUBIVRgVjYEMQJuXSJSOQJyUgtTaQBvDDIIMggxDGZcZVdpUzMCMwV1CG4PfVMmUD0BdVRiAW5SbA8wCzBYMA09ATFUMVZgBGcCN11l', '1509605562');
INSERT INTO `web_token` VALUES ('298057', '4720852', '8', 'BTFcNg1jWzBbCgk4XTAHPFU4BCEFJgBwD2ADdFc9VTIFO14wAD4IRwFMDxIPMFdhVDcFMgNiUnwPMgA8BTgENgV7XGMNOlttW2YJfF1sBzRVMAQ_bBX4AQg9BAzBXYlUQBWNeYgBFCDYBTg9hDzdXd1RgBWUDNlI_bD3AAawV1BF0FP1wzDTNbYVtiCWNdZAc5VTUENQV1AGYPfQN2VzpVIQUzXjEAPgg3AToPZw8_aV2dUMQUwA2xSYA83', '1509606912');
INSERT INTO `web_token` VALUES ('298058', '4720852', '8', 'WW0JY1c5DWZaC19uXDEEP1I_aByJVdgR0XTJQJ1E7CW5WaF4wBjhbFAFMDxIBPg07VDdUYwBhVXsBPAA8DTBWZFknCTZXYA07WmdfKlxtBDdSNwc9VS4ERl0TUGNRZAlMVjBeYgZDW2UBTg9hATkNLVRgVDQANVU5AX4Aaw19Vg9ZYwlmV2kNN1pjXzVcZQQ6UjIHNlUlBGJdL1AlUTwJfVZgXjEGOFtkAToPZwExDT1UOVRkAGJVYAEz', '1509683468');
INSERT INTO `web_token` VALUES ('298059', '4720852', '8', 'AjYLYQVrDWYAUQAxCWRQa1wxU3ZQcwd3C2RYL1A6VTIAPl4wBjgLRAFMCxZYZ1VjAmEMOwJjCSdZZFBsDDFRYwJ8CzQFMg07AD0AdQk4UGNcOVNpUCsHRQtFWGtQZVUQAGZeYgZDCzUBTgtlWGBVdQI2DGwCNwllWSZQOwx8UQgCOAtkBTsNNwA5AGoJMFBuXDxTYlAgB2ELeVgtUD1VIQA2XjEGOAs0AToLY1hoVWUCbww2AmYJPllk', '1509689247');
INSERT INTO `web_token` VALUES ('298060', '4720852', '8', 'BDBZMwJsAGsLWg8_bAG0AO1Q5DCkCIQNzXjEAd1E7AmVSbABuVmhbFFsWAB0LNAM1BWYDNFQ1CSdbZgU5ATxUZgR6WWYCNQA2CzYPegAxADNUMQw2AnkDQV4QADNRZAJHUjQAPFYTW2VbFABuCzMDIwUxA2NUYQllWyQFbgFxVA0EPlk2AjwAOgsyD2UAOQA_bVDQMPQJyA2VeLAB1UTwCdlJkAG9WaFtkW2AAaAs7AzMFaQMwVDMJOlto', '1509690109');
INSERT INTO `web_token` VALUES ('298061', '4720852', '8', 'VmIPZVE_aAGsIWQAxXDENNgFsDShWdQd3WjVQJ1A6BWIEOgFvBTtcEwBNCBVYZwA2VzQFMlk4UX8NMAM_aUG0DMVYoDzBRZgA2CDUAdVxtDT4BZA03Vi0HRVoUUGNQZQVABGIBPQVAXGIATwhmWGAAIFdjBWVZbFE9DXIDaFAgA1pWbA9gUW8AOggxAGpcZQ0zAWENPFYmB2FaKFAlUD0FcQQyAW4FO1xjADsIYFhoADBXOwU2WTZRZA0_a', '1509690968');
INSERT INTO `web_token` VALUES ('298062', '4720852', '8', 'UGQBa1E_aCmEIWVtqDmMNNl0wAidYewBwAW5XIAVvA2QFO1k3UG4KRV8SX0IKNQA2UDMFMgBhVngJNFhkATwHNVAuAT5RZgo8CDVbLg4_aDT5dOAI4WCMAQgFPV2QFMANGBWNZZVAVCjRfEF8xCjIAIFBkBWUANVY6CXZYMwFxB15QagFuUW8KMAgxWzEONw0zXT0CM1goAGYBc1ciBWgDdwUzWTZQbgo1X2RfNwo6ADBQPAUyAG9WZgk3', '1509694934');
INSERT INTO `web_token` VALUES ('298063', '4720852', '8', 'VWFbMVU7WDMPXg8_bXTBXbFA9ACVQcwl5XTIFclI4AmVZZ1s1DDIJRgBNDhMLNFJkXD8BNgdmBCoINQM_aV2pUZlUrW2RVYlhuDzIPel1sV2RQNQA6UCsJS10TBTZSZwJHWT9bZwxJCTcATw5gCzNSclxoAWEHMgRoCHcDaFcnVA1Vb1s0VWtYYg82D2VdZFdpUDAAMVAgCW9dLwVwUj8CdllvWzQMMgk2ADsOZgs7UmJcMAE0B2QEMQg3', '1509696565');
INSERT INTO `web_token` VALUES ('298064', '4720852', '8', 'UmYLYVE_aCmEOX1tqCmcFPl0wBCEAI1EhCWZXIAxmA2RXaQ5gUW9bFF0QDhMPMFdhVDdXYFAxVngAPVRoDTAHNVIsCzRRZgo8DjNbLgo7BTZdOAQ_bAHtREwlHV2QMOQNGVzEOMlEUW2VdEg5gDzdXd1RgVzdQZVY6AH9UPw19B15SaAtkUW8KMA43WzEKMwU7XT0ENQBwUTcJe1ciDGEDd1dhDmFRb1tkXWYOZg8_aV2dUOFdsUDBWZQA7', '1509698601');
INSERT INTO `web_token` VALUES ('298065', '4720852', '8', 'UWUPZQNtXTYIWV1sD2IMN1Q5VHFZelYmWTZYL1E7UTZZZwhmUmwIR1oXAB0APwM1BmUHMFQ1CCYPMlRoBDlUZlEvDzADNF1rCDVdKA8_bDD9UMVRuWSJWFFkXWGtRZFEUWT8INFIXCDZaFQBuADgDIwYyB2dUYQhkD3BUPwR0VA1Raw9gAz1dZwgxXTcPNgwyVDRUZVkpVjBZK1gtUTxRJVlvCGdSbAg3WmEAaAAwAzwGYAcwVDAIPA80', '1509934271');
INSERT INTO `web_token` VALUES ('298066', '4720852', '8', 'ADQMZgJsDWYBUFhpDGFVbgdqBiNXdFYmC2RWIQRuUzRUaghmAD5bFA1ADhNfYAQyAmFTZFg5BCpeYwA8UG1WZAB_bDDMCNQ07ATxYLQw9VWYHYgY8VyxWFAtFVmUEMVMWVDIINABFW2UNQg5gX2cEJAI2UzNYbQRoXiEAa1AgVg8AOgxjAjwNNwE4WDIMNVVrB2cGN1cnVjALeVYjBGlTJ1RiCGcAPltkDTYOZl9vBDsCZFNlWD8EMV5g', '1509935164');
INSERT INTO `web_token` VALUES ('298067', '4720853', '8', 'ADRZM1Y4WzBdDF9uXzIMNwZrU3YAIwl5WzRSJVI4VTIHOQFvAT9cFlxmXzBYYQAyAWRWYVY4U30IQwA8UWIEMwB_bWWZWF1sQXWFfKl9vDD4GYVNpAHsJO1tiUhVSb1VjB20BSwFEXGJcal9CWBQAIAE1VjZWY1M_aCHcAa1EhBF0AOlk2VmhbYV1kXzVfZgwyBmZTYwBwCW9bKVInUj9VIQcxAW4BP1xjXGdfNlhhADYBZlZnVjRTZwg6', '1510022478');
INSERT INTO `web_token` VALUES ('298068', '4720853', '8', 'UmYIYlA_bCGMLWgg5XzICOQJvAyZRclEhCWYDdA1nCG9WaFwyAT8ASgkzDWJaYwMxAmcAN1Q6U31cF1ZqVWYGMVIsCDdQEQhDCzcIfV9vAjACZQM5USpRYwkwA0QNMAg_bVjxcFgFEAD4JPw0QWhYDIwI2AGBUYVM_aXCNWPVUlBl9SaAhnUG4IMgsyCGJfZgI8AmIDM1EhUTcJewN2DWAIfFZgXDMBPwA_aCTINZFpjAzUCYgAyVDdTZ1xu', '1510051578');
INSERT INTO `web_token` VALUES ('298069', '4720853', '8', 'BDANZ1Y4DmUKWwAxAG1VblwxVHFZelMjDWIEcwRuAGdTbVk3VmgBS11nD2BaYwIwAGUAN1A_bBykBSlNvADMFMgR6DTJWFw5FCjYAdQAwVWdcO1RuWSJTYQ00BEMEOQA2UzlZE1YTAT9daw8SWhYCIgA0AGBQZQdrAX5TOABwBVwEPg1iVmgONAozAGoAOVVrXDxUZFkpUzUNfwRxBGkAdFNlWTZWaAE_bXWYPZlpjAjQAYAAwUDAHMAE_a', '1510053644');
INSERT INTO `web_token` VALUES ('298070', '4720853', '8', 'AzdbMQxiWDMLWg8_bAWwCOQJvV3JScVQkAG8Fclc9BGNTbV4wDDJYEgw2WzRYYQU3UzYBNgNtU30NRgU5VmUANwN9W2QMTVgTCzcPegExAjACZVdtUilUZgA5BUJXagQyUzleFAxJWGYMOltGWBQFJVNnAWEDNlM_aDXIFblYmAFkDOVs0DDJYYgsyD2UBOAI8AmJXZ1IiVDIAcgVwVzoEcFNlXjEMMlhnDDdbMlhhBTJTNwExA2ZTYA02', '1510113301');
INSERT INTO `web_token` VALUES ('298071', '4720853', '8', 'VGBcNgdpC2ALWgg5C2YHPFM_bVHFUdwNzAG9VIlY8BGNUag5gDDIJQ1hiWDcKM1dlAmcHMFQ6UnwBSgA8ATICNVQqXGMHRgtACzcIfQs7BzVTNFRuVC8DMQA5VRJWawQyVD4ORAxJCTdYblhFCkZXdwI2B2dUYVI_bAX4AawFxAltUblwzBzkLMQsyCGILMgc5UzNUZFQkA2UAclUgVjsEcFRiDmEMMgk2WGNYMQozV2ACZgc3VDFSZwE_a', '1510113364');
INSERT INTO `web_token` VALUES ('298072', '4720853', '8', 'V2MIYgVrDGcJWAAxDWAGPQFsU3YDIABwCWZSJQJoBGNRb1o0AT9dF19lCGcPNgw_bAGUEMwJsAS9dFgI_bADMJPlcpCDcFRAxHCTUAdQ09BjQBZlNpA3gAMgkwUhUCPwQyUTtaEAFEXWNfaQgVD0MMLAA0BGQCNwFtXSICaQBwCVBXbQhnBTsMNgkwAGoNNAY4AWFTYwNzAGYJe1InAm8EcFFnWjUBP11iX2QIYQ82DDsAZAQ0AmIBMV1i', '1510113635');
INSERT INTO `web_token` VALUES ('298073', '4720853', '8', 'AzcMZlE_aWDMKWwo7XzIDOF0wU3YCIQd3WjUEcwRuBWJVawhmUmxbEVxmDWIIMVBiVDEMOwBuBCoNRgQ4BjUGMQN9DDNREFgTCjYKf19vAzFdOlNpAnkHNVpjBEMEOQUzVT8IQlIXW2Vcag0QCERQcFRgDGwANQRoDXIEbwZ2Bl8DOQxjUW9YYgozCmBfZgM9XT1TYwJyB2FaKARxBGkFcVVjCGdSbFtkXGcNZAgxUGdUMAw7AGYEMg0_b', '1510114059');
INSERT INTO `web_token` VALUES ('298074', '4720853', '8', 'AzdbMQdpD2QBUAAxD2IFPlY7AyYDIFYmWjUCdQZsAmVSbA1jAD4BSw03D2AON1ZkUzYMO1Q6CScNRldrBzQFMgN9W2QHRg9EAT0AdQ8_aBTdWMQM5A3hWZFpjAkUGOwI0UjgNRwBFAT8NOw8SDkJWdlNnDGxUYQllDXJXPAd3BVwDOVs0BzkPNQE4AGoPNgU7VjYDMwNzVjBaKAJ3BmsCdlJkDWIAPgE_bDTYPZg43VmFTNww7VDcJOQ02', '1510114531');
INSERT INTO `web_token` VALUES ('298075', '4720853', '8', 'WGxdN1Y4AWoOXwAxCmcCOVM_bUHVXdFMjXDNQJ1A6UTZQbgxiDDIBSwgyXTJbYldlAGUGMVY4VnheFQU5ADNTZFgmXWJWFwFKDjIAdQo6AjBTNFBqVyxTYVxlUBdQbVFnUDoMRgxJAT8IPl1AWxdXdwA0BmZWY1Y6XiEFbgBwUwpYYl0yVmgBOw43AGoKMwI8UzNQYFcnUzVcLlAlUD1RJVBmDGMMMgE_bCDNdNFtiV2AAZAYxVjdWbF5g', '1510114794');
INSERT INTO `web_token` VALUES ('298076', '4720852', '8', 'AzcKYAVrCGMKWws6XzIEP1I_aAyZTcFQkXTJQJwBqCW5WaA9hV2kPQAxBWEUMM1VjVzQANwVkVHoPMgM_aAz4IOgN9CjUFMgg_bCjcLfl9uBDdSNwM5UyhUFl0TUGMANQlMVjAPM1cSDzEMQ1g2DDRVdVdjAGAFMFQ4D3ADaANzCFEDOQplBTsIMgozC2FfZgQ6UjIDMlMjVDJdL1AlAG0JfVZgD2BXaQ8wDDdYMQw1VWBXMwAwBWpUYA89', '1510313978');
INSERT INTO `web_token` VALUES ('298077', '4720852', '8', 'AjZcNgNtXzRbCgw9XTBVbgZrByJUdwR0DmFQJ1c9CG8HOQxiAT8KRQxBW0ZbZAw6VjUMO1Y3CCYINVFtBjsIOgJ8XGMDNF9pW2YMeV1sVWYGYwc9VC8ERg5AUGNXYghNB2EMMAFECjQMQ1s1W2MMLFZiDGxWYwhkCHdROgZ2CFECOFwzAz1fZVtiDGZdZFVrBmYHNlQkBGIOfFAlVzoIfAcxDGMBPwo1DDdbMltiDDlWMgw7VjEIOggy', '1510314110');
INSERT INTO `web_token` VALUES ('298078', '4720852', '8', 'BTELYQBuD2QJWAg5DWABOgJvDSgCIQBwWzRZLgxmCG9XaQhmAD5fEApHDBEIN1JkVzRXYFU0UX9dYFhkDTAAMgV7CzQANw85CTQIfQ08ATICZw03AnkAQlsVWWoMOQhNVzEINABFX2EKRQxiCDBScldjVzdVYFE9XSJYMw19AFkFPwtkAD4PNQkwCGINNAE_aAmINPAJyAGZbKVksDGEIfFdhCGcAPl9gCjEMZQgxUmdXNVdtVTtRZF1k', '1510379863');
INSERT INTO `web_token` VALUES ('298079', '4720848', '8', 'AzdZMwJsWDNbCl9uDmMHPFU4BiNXdFIiD2AEc1A6AWZWaA9hBjgMOlpmDDcBaVVgAjYGPVg5U2JbNwJkATJTbQNiWWECMFhwWzRfZA5kB25VdQZrV3VSDg9sBGVQaQEzVjIPNgY1DDpaYAxsAS5VNgIvBnVYZ1MiWzQCYwE4U2QDZVljAjFYYFthXzAOPwczVTY=', '1510647826');
INSERT INTO `web_token` VALUES ('298080', '4720848', '8', 'U2cNZwJsXDddDFprWzZWbVE8BCECIQd3XjEEcwBqAGcFO1s1UG5faVpmATpbMwUwU2cEPwdmATABbVg_bUmFWaFMyDTUCMFx0XTJaYVsxVj9RcQRpAiAHW149BGUAOQAyBWFbYlBjX2laYAFhW3QFZlN_bBHcHOAFwAW5YOVJrVmFTNQ03AjFcZF1nWjtbYVZpUTU=', '1510649391');
INSERT INTO `web_token` VALUES ('298081', '4720852', '8', 'UmZdNwxiCWINXAo7XDEFPlE8DClWdQZ2WzQFclc9VTICPFwyUmwNQloXX0IJNgUzAWJWYVg5UnwBPFFtBDlTYVIsXWIMOwk_aDTAKf1xtBTZRNAw2Vi0GRFsVBTZXYlUQAmRcYFIXDTNaFV8xCTEFJQE1VjZYbVI_bAX5ROgR0UwpSaF0yDDIJMw00CmBcZQU7UTEMPVYmBmBbKQVwVzpVIQI0XDNSbA0yWmFfNgkwBTUBYVZkWDdSYAE8', '1510651917');
INSERT INTO `web_token` VALUES ('298082', '4720853', '8', 'WW0AagZoAWpaCw08XzIHPF0wUHVZelIiAG9YLw1nB2BZZwxiAz0BSw81DmELMg0_aB2IMO1E_aAS9aEQc7AjECNVknAD8GRwFKWmYNeF9vBzVdOlBqWSJSYAA5WB8NMAcxWTMMRgNGAT8POQ4TC0cNLQczDGxRZAFtWiUHbAJyAltZYwBvBjgBO1pjDWdfZgc5XT1QYFkpUjQAclgtDWAHc1lvDGMDPQE_bDzQOZwsyDT0HZww5UT8BNVpl', '1510656875');
INSERT INTO `web_token` VALUES ('298083', '4720853', '8', 'U2cBawNtXDdfDgo7CWRQawVoVHFZegZ2CmUEc1c9BGNVa14wBzkORA81XDMONwQ2BmNXYFI8CScPRAc7ADMIP1MtAT4DQlwXX2MKfwk5UGIFYlRuWSIGNAozBENXagQyVT9eFAdCDjAPOVxBDkIEJAYyVzdSZwllD3AHbABwCFFTaQFuAz1cZl9mCmAJMFBuBWVUZFkpBmAKeARxVzoEcFVjXjEHOQ4xDzRcNQ43BDUGYldlUj0JOA8x', '1510711924');
INSERT INTO `web_token` VALUES ('298084', '4720853', '8', 'ADQKYFU7XDdfDltqDGFXbFM_bAicAIwV1CmVSJQFrAmVWaA1jUmxcFgsxAG9aY1ZkXTgFMlM9UX9ZEgc7BTYGMQB_bCjVVFFwXX2NbLgw8V2VTNAI4AHsFNwozUhUBPAI0VjwNR1IXXGILPQAdWhZWdl1pBWVTZlE9WSYHbAV1Bl8AOgplVWtcZl9mWzEMNVdpUzMCMgBwBWMKeFInAWwCdlZgDWJSbFxjCzAAaVpjVmddOQU1UzNRZllr', '1510713648');
INSERT INTO `web_token` VALUES ('298085', '4720848', '8', 'VGBaMAxiDmUNXF9uDmNXbF0wACVTcFIiXjECdQJoBGMDPVwyBDoJPw8zAToPZwM2VGBRalMyAjMKZlI0AzAJN1Q1WmIMPg4mDWJfZA5kVz5dfQBtU3FSDl49AmMCOwQ2A2dcZQQ3CT8PNQFhDyADYFR5USJTbAJzCmVSMwM6CT5UMlpgDD8ONw0wXzYON1dlXTA=', '1510731048');
INSERT INTO `web_token` VALUES ('298086', '4720848', '8', 'UWVeNAxiCmEKWwAxXDEFPl0wBiNUd1UlWTZRJlU_aAWZSbFk3AD4IPgk1DTYJYQUwXGhTaAJjU2IIZFM1BTZSbFEwXmYMPgoiCmUAO1w2BWxdfQZrVHZVCVk6UTBVbAEzUjZZYAAzCD4JMw1tCSYFZlxxUyACPVMiCGdTMgU8UmVRN15kDD8KMwo3AGlcbQUxXTw=', '1510731824');
INSERT INTO `web_token` VALUES ('298087', '4720852', '8', 'WGwPZQRqAGsLWltqXDFSaVE8BiMHJAV1WzQDdAFrUjVRbw9hUG4LRABNXUAOMVJkVjUMO1AxBCpdYFJuBDkHNVgmDzAEMwA2CzZbLlxtUmFRNAY8B3wFR1sVAzABNFIXUTcPM1AVCzUAT10zDjZSclZiDGxQZQRoXSJSOQR0B15YYg9gBDoAOgsyWzFcZVJsUTEGNwd3BWNbKQN2AWxSJlFnD2BQbgs0ADtdNA43UmNWMAw9UDcEMl1u', '1510732159');
INSERT INTO `web_token` VALUES ('298088', '4720857', '8', 'VWEKYARqCGMNXA08XzIAOwBtDShVdgR0D2BVIlI4BWJSbABuAT8POQ4yX2QPZwM2ADRTaFY3ADEAbAVjAzBUalU0CjIENgggDWINNl81AGkAIA1gVXcEWA9sVTRSawU3UjYAOQEyDzkONV8wDyADYAAtUyBWaQBxAG8FZAM6VGNVMwowBDcIMQ0wDWdfZAAyAGU=', '1510732240');
INSERT INTO `web_token` VALUES ('298089', '4720857', '8', 'BDBZM1E_aXzQOXw8_bCGUBOgBtACVXdAJyAW4Fclc9UjVQbgtlVWtfaQo2CzAJYQ04V2MDOANiUmMBbQdhV2RTbQRlWWFRY193DmEPNAhiAWgAIABtV3UCXgFiBWRXblJgUDQLMlVmX2kKMQtkCSYNbld6A3ADPFIjAW4HZlduU2QEYlljUWJfZg4zD2UIMwEzAGQ=', '1510732241');
INSERT INTO `web_token` VALUES ('298090', '4720857', '8', 'U2dZMw1jCGMPXgk4AG0EP1A9VHFQcwNzWjUCdQxmAmVXaQpkUW9faQwwDDcMZAcyAjZRagBhUWBaNgdhADMBP1MyWWENPwggD2AJMgBqBG1QcFQ5UHIDX1o5AmMMNQIwVzMKM1FiX2kMNwxjDCMHZAIvUSIAP1EgWjUHZgA5ATZTNVljDT4IMQ8yCWMAOwQ2UDQ=', '1510732241');
INSERT INTO `web_token` VALUES ('298091', '4720857', '8', 'V2NeNFY4WDMMXQs6CmcFPgBtACVVdgJyXTJXIA1nBGMCPFwyUmwKPF1hWGMNZVJnADQNNlAxATAAbFUzDT5Ualc2XmZWZFhwDGMLMApgBWwAIABtVXcCXl0_bVzYNNAQ2AmZcZVJhCjxdZlg3DSJSMQAtDX5QbwFwAG9VNA00VGNXMV5kVmVYYQwxC2AKNgUwAGU=', '1510733530');
INSERT INTO `web_token` VALUES ('298092', '4720858', '8', 'V2NcNlc5AGsJWFxtDWAGPQJvACVYewJyAW5SJVY8AGcDPQhmBTsMQw9CDRBaZQM1BmVRZlk4BStdYFVpDDFUZlcpXGNXYAA2CTRcKQ08BjUCZwA6WCMCQAFPUmFWYwBFA2UINAVADDIPQA1jWmIDIwYyUTFZbAVpXSJVPgx8VA1XbVwzV2kAOgkwXDYNNAY4AmIAO1goAmQBc1InVjsAdAM1CGcFOwwzDzQNZFpjAzIGYFFnWT4FN11m', '1510735111');
INSERT INTO `web_token` VALUES ('298093', '4720859', '8', 'WW1eNAVrAWoJWAg5XDFXbFM_bUHVWdVIiDmEFcgJoUzRTbVwyBzkIQgw2XTILMlZkBmMMO1U7Ay1eFVdrUmEBNlknXmEFRAFKCTUIfVxsV2VTNFBqVi1SYA43BUICP1NlUzlcFgdCCDYMOl1AC0dWdgYyDGxVYANvXiFXPFIiAVhZY14xBTsBOwkwCGJcZVdpUzNQalYmUjQOfAVwAm9TJ1NlXDMHOQg3DDddNAsyVmcGYAw4VTMDM15s', '1510737038');
INSERT INTO `web_token` VALUES ('298094', '4720858', '8', 'UGQJYwxiAWoBUA4_aDWAFPgZrBSBVdgR0DWIAd1A6AGdRbwxiAD4KRQlECBUNMlBmAmEFMgJjAixZZAM_aBDlUZlAuCTYMOwE3ATwOew08BTYGYwU_aVS4ERg1DADNQZQBFUTcMMABFCjQJRghmDTVQcAI2BWUCNwJuWSYDaAR0VA1QaglmDDIBOwE4DmQNNAU7BmYFPlUlBGINfwB1UD0AdFFnDGMAPgo1CTIIYQ00UGECZAUxAmcCN1li', '1510737361');
INSERT INTO `web_token` VALUES ('298095', '4720859', '8', 'V2NbMQxiCGNcDQAxC2ZQawJvU3ZXdAJyXjFUI1c9CW4APghmUG4NRwgyDmEBOAAyAGUCNVk3U30JQlFtUmEJPlcpW2QMTQhDXGAAdQs7UGICZVNpVywCMF5nVBNXagk_aAGoIQlAVDTMIPg4TAU0AIAA0AmJZbFM_aCXZROlIiCVBXbVs0DDIIMlxlAGoLMlBuAmJTaVcnAmReLFQhVzoJfQA2CGdQbg0yCDMOZwE4ADEAZgI2WTpTZQkx', '1510737552');
INSERT INTO `web_token` VALUES ('298096', '4720859', '8', 'U2cIYgxiCWINXF9uD2IHPFU4UXQFJgl5C2QAdwxmB2ADPQhmDTMNRwkzAG8IMVZkVzIBNlA_bVngIQwI_bBTYBNlMtCDcMTQlCDTFfKg8_aBzVVMlFrBX4JOwsyAEcMMQcxA2kIQg1IDTMJPwAdCERWdldjAWFQZVY6CHcCaQV1AVhTaQhnDDIJMw00XzUPNgc5VTVRawV1CW8LeQB1DGEHcwM1CGcNMw0yCTIAaQgxVmdXMQE7UDdWZAg1', '1510739117');
INSERT INTO `web_token` VALUES ('298097', '4720857', '8', 'WGwNZw1jCGMJWAw9DGFXbAFsV3IEJwh4DWJTJARuUzRVa1o0BjhfaQE9W2APZ1FkVmIEP1U0BDUIZAJkBjUGOFg5DTUNPwggCWYMNwxmVz4BIVc6BCYIVA1uUzIEPVNhVTFaYwY1X2kBOls0DyBRMlZ7BHdVagR1CGcCYwY_aBjFYPg03DT4IMQkzDGUMPFdkAWY=', '1510741952');
INSERT INTO `web_token` VALUES ('298098', '4720863', '8', 'AzcAagVrWzAIWQk4XTABOgFsBSBRcgBwWzRVIgZsVjFZZw1jAz1Ybgg0W2BcNFZjBjJXbABhATBcMFM1BjUHOQNiADgFN1tzCGcJMl03AWgBIQVoUXMAXFs4VTQGP1ZkWT0NNAMwWG4IMFswXHNWNQYrVyQAPwFwXDNTMgY_aBzADZQA6BTZbbQg2CWJdZQE3AWU=', '1510803101');
INSERT INTO `web_token` VALUES ('298099', '4720863', '8', 'UWUKYFE_aWDMJWF1sWzYCOVc6ASRQcwh4XDMAd1U_aUTYFO1s1UW8MOgs3DTZYMFVgADRUbwVkUWBbNwNlADNVa1EwCjJRY1hwCWZdZlsxAmtXdwFsUHIIVFw_aAGFVbFFjBWFbYlFiDDoLMw1mWHdVNgAtVCcFOlEgWzQDYgA5VWJRNwowUWJYbgk3XTZbYwI0VzM=', '1510803101');
INSERT INTO `web_token` VALUES ('298100', '4720864', '8', 'BzMJY1I8D2RaCwo7WDUDOFE8VHFVdlYmDmFRJgxmBmFUalwyAT8LRFsWCBUINwA2XD8BNlEwVnhcYVRoUG0IOgd5CTZSZQ85WmcKf1hpAzBRNFRuVS5WFA5AUWIMOQZDVDJcYAFECzVbFAhmCDAAIFxoAWFRZFY6XCNUP1AgCFEHPQlmUmwPNVpjCmBYYQM9UTJUY1UlVjAOfFEkDGEGclRiXDMBPws0W2AIYQgwADNcOAE1UTFWZVxn', '1511517601');
INSERT INTO `web_token` VALUES ('298101', '4720864', '8', 'WW0BawxiCGMJWAAxWjcEPwZrDSgHJAJyD2ACdQxmAGdQblo0AjxYF10QARwBPgQyUzABNgBhUX8APVRoDTBVZ1knAT4MOwg_bCTQAdVprBDcGYw03B3wCQA9BAjEMOQBFUDZaZgJHWGZdEgFvATkEJFNnAWEANVE9AH9UPw19VQxZYwFuDDIIMgkwAGpaYwQ6BmUNOgd3AmQPfQJ3DGEAdFBmWjUCPFhnXWYBaAE5BDdTNwE6AGVRYQA4', '1511518332');
INSERT INTO `web_token` VALUES ('298102', '4720864', '8', 'AzdZM1c5XDcJWA4_aAWwHPFA9AidUdwZ2WzQAd1I4UTZWaA9hDDIMQwpHXEEBPgM1B2QFMlg5VHoBPFhkVmtRYwN9WWZXYFxqCTQOewEwBzRQNQI4VC8GRFsVADNSZ1EUVjAPMwxJDDIKRVwyATkDIwczBWVYbVQ4AX5YM1YmUQgDOVk2V2lcZgkwDmQBOAc5UDMCNVQkBmBbKQB1Uj9RJVZgD2AMMgwzCjFcNQE5AzAHYwU_bWD1UYQE5', '1511518362');
INSERT INTO `web_token` VALUES ('298103', '4720864', '8', 'BTFeNFA_bXDcKWws6D2IBOgFsAyYFJlMjXTJSJVE7BWJUag5gDTNfEAxBDxIJNgUzVzQBNlc2BigINVFtATwGNAV7XmFQZ1xqCjcLfg8_bATIBZAM5BX5TEV0TUmFRZAVAVDIOMg1IX2EMQw9hCTEFJVdjAWFXYgZqCHdROgFxBl8FP14xUG5cZgozC2EPNgE_aAWIDNAV1UzVdL1InUTwFcVRiDmENM19gDDcPZgkxBTZXMwE6VzIGMwg7', '1511518369');
INSERT INTO `web_token` VALUES ('298104', '4720864', '8', 'BTFeNFU7WzAOX1xtC2YDOFQ5V3JZelQkWjVUIwRuAmVWaA9hBzkJRloXCBVcY1FnB2QMOwBhU30INVdrDDFUZgV7XmFVYlttDjNcKQs6AzBUMVdtWSJUFloUVGcEMQJHVjAPMwdCCTdaFQhmXGRRcQczDGwANVM_aCHdXPAx8VA0FP14xVWtbYQ43XDYLMgM9VDdXYFkpVDJaKFQhBGkCdlZgD2AHOQk2WmEIYVxkUWIHYAw6AGRTYgg0', '1511525226');
INSERT INTO `web_token` VALUES ('298105', '4720863', '8', 'UmZeNARqCGNdDA08CmdWbQFsAyZXdAh4XTJSJQBqAGdUal0zVmhdawwwX2RYMAI3UGQGPVg5ATAMYFYwAjEDPVIzXmYENgggXTINNgpgVj8BIQNuV3UIVF0_bUjMAOQAyVDBdZFZlXWsMNF80WHcCYVB9BnVYZwFwDGNWNwI7AzRSNF5kBDYIM11qDWcKMFZhAWQ=', '1511592310');
INSERT INTO `web_token` VALUES ('298106', '4720863', '8', 'WGwIYgBuD2QKWwg5CWRQa1Q5UHVXdAV1DGMAd1A6B2BUal0zUG4NOws3WmELYwcyXWkBOlAxBDVcMFE3UmFVa1g5CDAAMg8nCmUIMwljUDlUdFA9V3UFWQxvAGFQaQc1VDBdZFBjDTsLM1oxCyQHZF1wAXJQbwR1XDNRMFJrVWJYPggyADIPNAo9CGMJMVBkVDA=', '1511593121');
INSERT INTO `web_token` VALUES ('298107', '4720863', '8', 'BTELYQFvWzBfDl9uXTAMNwZrASRWdQl5CWYFcgdtB2AEOlk3DDIJP1tnW2AAaAcyBTEBOlAxUWBbN1cxBDdUagVkCzMBM1tzXzBfZF03DGUGJgFsVnQJVQlqBWQHPgc1BGBZYAw_aCT9bY1swAC8HZAUoAXJQb1EgWzRXNgQ9VGMFYwsxATNbYF9oXzRdZQwyBmQ=', '1511593187');
INSERT INTO `web_token` VALUES ('298108', '4720863', '8', 'UmYLYVA_bXDcOX19uCmcMN10wV3IAIwNzWTYFcgxmAmVVa1s1UG4POV1hW2ANZQw5UGRRalg5U2IJZVM1VWYDPVIzCzNQYlx0DmFfZApgDGVdfVc6ACIDX1k6BWQMNQIwVTFbYlBjDzldZVswDSIMb1B9USJYZ1MiCWZTMlVsAzRSNAsxUGJcZw45XzQKMQw4XT0=', '1511593225');
INSERT INTO `web_token` VALUES ('298109', '4720863', '8', 'VWFZMwBuD2QKWww9DWACOQFsUXQHJAV1WTZUI1Y8UTYDPQtlUmwNOw8zWGNYMAI3V2MEPwBhUmNeMlcxBTYBP1U0WWEAMg8nCmUMNw1nAmsBIVE8ByUFWVk6VDVWb1FjA2cLMlJhDTsPN1gzWHcCYVd6BHcAP1IjXjFXNgU8ATZVM1ljADIPNAo9DGcNNgIxAWY=', '1511593252');
INSERT INTO `web_token` VALUES ('298110', '4720863', '8', 'ADQOZARqC2BcDQEwD2IGPVE8BSBScQFxXjFRJg1nUTZRbwBuUG5cag4yDzRcNAM2U2dWbVg5VGVeMlcxBDdTbQBhDjYENgsjXDMBOg9lBm9RcQVoUnABXV49UTANNFFjUTUAOVBjXGoONg9kXHMDYFN_bViVYZ1QlXjFXNgQ9U2QAZg40BDYLMFxrAWoPNAY3UTM=', '1511593277');
INSERT INTO `web_token` VALUES ('298111', '4720863', '8', 'VGAPZQ1jD2QLWlhpWjcGPV0wVnMAIwV1D2AAd1U_aAWZTbQBuUmwBNws3ATpfNwcyBzMBOgRlVGVeMlcxATJTbVQ1DzcNPw8nC2RYY1owBm9dfVY7ACIFWQ9sAGFVbAEzUzcAOVJhATcLMwFqX3AHZAcqAXIEO1QlXjFXNgE4U2RUMg81DT8PNAs8WDNaYAYxXTA=', '1511593318');
INSERT INTO `web_token` VALUES ('298112', '4720863', '8', 'UmYOZABuXzQPXgEwDmNRagBtByJVdgl5AG9ZLlY8BWJZZw9hBzkMOlhkCDNcNAUwBzNWbQVkADEBbQVjUWIDPVIzDjYAMl93D2ABOg5kUTgAIAdqVXcJVQBjWThWbwU3WT0PNgc0DDpYYAhjXHMFZgcqViUFOgBxAW4FZFFoAzRSNA40ADJfZA84AW0OM1FlAG0=', '1511594428');
INSERT INTO `web_token` VALUES ('298113', '4720864', '8', 'WGwLYQFvCGMBUA8_bAG0NNlwxVnMFJlUlWTZUI1U_aCW4FOwFvUmwLRF0QDBEOMQQyAGNUYwJjVHoBPAM_aVmsCMFgmCzQBNgg_bATwPegAxDT5cOVZsBX5VF1kXVGdVYAlMBWMBPVIXCzVdEgxiDjYEJAA0VDQCN1Q4AX4DaFYmAltYYgtkAT8IMgE4D2UAOQ0zXD9WYQV1VTNZK1QhVTgJfQUzAW5SbAs0XWYMZQ42BDcAbFRjAmNUYQEz', '1511594768');
INSERT INTO `web_token` VALUES ('298114', '4720864', '8', 'WGwAagJsDWYKWwEwD2JQawFsDCkFJgNzXjEFcgJoAGcEOghmAz1bFF8SWkdfYFZgUjEHMABhAiwOM1hkDTBSYFgmAD8CNQ07CjcBdA8_bUGMBZAw2BX4DQV4QBTYCNwBFBGIINANGW2VfEFo0X2dWdlJmB2cANQJuDnFYMw19UgtYYgBvAjwNNwozAWsPNlBuAWIMOwV1A2VeLAVwAm8AdAQyCGcDPVtkX2RaM19nVmVSPgcyAGUCMg4y', '1511596336');
INSERT INTO `web_token` VALUES ('298115', '4720866', '8', 'AjYNZ1E_aDmUKW19uCGVRalA9ASRZegd3CWYHcANpBWIFOw1jBDoIQgowCGcBOA0_aUDVXYFQ6AS9ZElRoV2QCNQJ8DTJREA5FCjZfKgg4UWNQNwE7WSIHNQkwB0ADPgUzBW8NRwRBCDYKPAgVAU0NLVBkVzdUYQFtWSZUP1cnAlsCOA1iUW8ONAozXzUIMVFvUDMBNFkpB2EJewdyA24FcQUzDWIEOgg3CjEIYQE5DT5QPFdiVDEBO1lg', '1511596393');
INSERT INTO `web_token` VALUES ('298116', '4720866', '8', 'BzMNZ1E_aCGMKWw08CmcCOVU4ASRVdgFxWTYFclU_aVTJQbg1jUmwIQg40AW4KMwEzVTAHMFk3BCoBSgU5BTYANwd5DTJREAhDCjYNeAo6AjBVMgE7VS4BM1lgBUJVaFVjUDoNR1IXCDYOOAEcCkYBIVVhB2dZbARoAX4FbgV1AFkHPQ1iUW8IMgozDWcKMwI8VTYBNFUlAWdZKwVwVThVIVBmDWJSbAg3DjUBaAoyATJVOQcyWToENgE4', '1511596513');
INSERT INTO `web_token` VALUES ('298117', '4720867', '8', 'VWEAagNtDmUJWFhpXzIBOlI_aUXRQcwh4CmVSJVA6BWJXaQpkBDoLM1tlC2QKMAQ6BmsMOVU7BSsPYwQ1BWAGNFUrADgDYg4zCTFYLV9vATZSMlE3UCsIbQo4UjVQNQU6VzYKMgQ1CzZbZQtqCmcEJAYyDGxVYAVpD3AEbwV1Bl9VbwBvAz0ONAkwWDJfZgE_aUjFRZVAgCG4KeFInUD0FcVdhCmUEOgs0W2ALYgoyBDcGagw4VTMFNA8z', '1511597026');
INSERT INTO `web_token` VALUES ('298118', '4720868', '8', 'VGANZ1c5WzAPXl9uWjdXbFQ5UXQEJwNzCmVYLwxmBmFZZw9hDTMLQQkzD2APNgU3UDVWYQBuVnhcFwA8BTYFMlQqDTJXFlsQDzNfKlpqV2VUM1FrBH8DMQozWB8MMQYwWTMPRQ1ICzUJPw8SD0MFJVBkVjYANVY6XCMAawV1BVxUbg1iV2lbYQ82XzVaY1dpVDdRagR0A2UKeFgtDGEGcllvD2ANMws0CTIPZg83BTZQPFZtAGZWbVxk', '1511598082');
INSERT INTO `web_token` VALUES ('298119', '4720868', '8', 'BTEJY1U7DGcAUQEwDGEMN1wxU3ZRcgV1CWZYL1E7UTYCPFk3DTMMRlthWzRaYwU3B2JWYQBuBykAS1RoUGMEMwV7CTZVFAxHADwBdAw8DD5cO1NpUSoFNwkwWB9RbFFnAmhZEw1IDDJbbVtGWhYFJQczVjYANQdrAH9UP1AgBF0FPwlmVWsMNgA5AWsMNQwyXD9TaFEhBWMJe1gtUTxRJQI0WTYNMwwzW2BbMlpiBTYHa1ZsAGcHMwA_a', '1511599175');
INSERT INTO `web_token` VALUES ('298120', '4720869', '8', 'VWFaMFY4CmEPXgg5D2IDOFwxBSAFJgNzCmVUI1U_aVTJZZwBuDTNda1xgAToPZw04UWUNNlEwCDlcMFk_aAjEEOlU0WmJWZAoiD2AIMw9lA2pcfAVoBScDXwppVDVVbFVnWT0AOQ0_bXWtcZAFgDyANblF8DX5Rbgh5XDNZOAI7BDNVM1pgVmQKMQ84CGkPPgM3XDE=', '1511599828');
INSERT INTO `web_token` VALUES ('298121', '4720870', '8', 'VmIOZAVrCmEBUAo7XzINNgZrVHEHJFIiCWYFclU_aBmEHOVwyDTNbEVthCWYNNAAyXThQZwBuUX8AS1VpBzQJPlYoDjEFRApBAT0Kf19vDT8GYVRuB3xSYAkwBUJVaAYwB21cFg1IW2VbbQkUDUEAIF1pUDAANVE9AH9VPgd3CVBWbA5hBTsKMAE4CmBfZg0zBmRUZwd3UjQJewVwVTgGcgcxXDMNM1tkW2AJYA01ADBdOFBiAGdRZgA4', '1511601142');
INSERT INTO `web_token` VALUES ('298122', '4720871', '8', 'BTELYVc5AGtbClprCmcAO1M_bUXRVdlYmWjUDdAZsBmFRbw9hUW9YblxgATpfNwM2VWEBOlAxVmcLZ1k_aV2QBPwVkCzNXZQAoWzRaYQpgAGlTc1E8VXdWClo5A2IGPwY0UTUPNlFiWG5cZQFoX3ADYFV4AXJQb1YnC2RZOFduATYFYwsxV2UAOFtlWjMKMgAwUzU=', '1511601163');
INSERT INTO `web_token` VALUES ('298123', '4720871', '8', 'UGQMZgNtXzQLWg08C2ZRalQ5DClYewh4DGMEc1I4BGNYZlwyUG4NO11hCDMMZAUwATUNNlAxVGUAbFYwV2QGOFAxDDQDMV93C2QNNgthUThUdAxhWHoIVAxvBGVSawQ2WDxcZVBjDTtdZAhhDCMFZgEsDX5Qb1QlAG9WN1duBjFQNgw2AzFfZws1DWQLNFFuVDU=', '1511601694');
INSERT INTO `web_token` VALUES ('298124', '4720870', '8', 'AzcJYwRqAGsIWQ8_bCWRRalc6AidRclYmAG9XIAVvVDMEOl0zBzkNRw81AW4AOQQ2BmNUY1I8AS9aEVVpUGMBNgN9CTYERQBLCDQPegk5UWNXMAI4USpWZAA5VxAFOFRiBG5dFwdCDTMPOQEcAEwEJAYyVDRSZwFtWiVVPlAgAVgDOQlmBDoAOggxD2UJMFFvVzUCMVEhVjAAclciBWhUIAQyXTIHOQ0yDzQBaAA4BDQGY1RmUjMBO1pn', '1511601797');
INSERT INTO `web_token` VALUES ('298125', '4720871', '8', 'WGwPZQFvWDNcDQo7DWACOVQ5ByJUd1MjC2QEcw1nBWJRbwhmUG5faQs3CDMMZAcyADQHPFY3AzILZwNlUmFSbFg5DzcBM1hwXDMKMQ1nAmtUdAdqVHZTDwtoBGUNNAU3UTUIMVBjX2kLMghhDCMHZAAtB3RWaQNyC2QDYlJrUmVYPg81ATNYYFxiCmcNNwI2VDY=', '1511605327');
INSERT INTO `web_token` VALUES ('298126', '4720873', '8', 'AzdcNgxiCWJfDl1sDmNVblE8U3ZXdAZ2XDNRJlI4B2BVawxiBTsIPl1hDjUOZlVgAjZWbVQ1BDVaNgNlVmUEOgNiXGQMPgkhXzBdZg5kVTxRcVM_bV3UGWlw_aUTBSawc1VTEMNQU2CD5dZA5lDiFVNgIvViVUawR1WjUDYlZvBDMDZVxmDD4JMV9hXTAONFVrUTw=', '1511605388');
INSERT INTO `web_token` VALUES ('298127', '4720873', '8', 'AzcJYwVrCWJaCw08WDVQa1Y7ByJXdAh4XTJVIgRuCW4FOw1jVmgBNw0xXWZaMgI3VWEEPwdmADELZ1E3VmUEOgNiCTEFNwkhWjUNNlgyUDlWdgdqV3UIVF0_bVTQEPQk7BWENNFZlATcNNF02WnUCYVV4BHcHOABxC2RRMFZvBDMDZQkzBTQJNlpmDWdYZlBgVjo=', '1512122769');

-- ----------------------------
-- Table structure for web_welfare
-- ----------------------------
DROP TABLE IF EXISTS `web_welfare`;
CREATE TABLE `web_welfare` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `real_name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `player_name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `emoney` smallint(4) unsigned NOT NULL DEFAULT '1000',
  `server_id` smallint(4) unsigned NOT NULL DEFAULT '0',
  `player_id` int(11) unsigned NOT NULL DEFAULT '0',
  `pay_date` char(6) COLLATE utf8_bin NOT NULL DEFAULT '',
  `pay_used_date` char(6) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of web_welfare
-- ----------------------------
