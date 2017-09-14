/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : u591_hj

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-07-05 11:18:10
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
INSERT INTO `web_access` VALUES ('11', '479', '2', '1', null);
INSERT INTO `web_access` VALUES ('11', '473', '2', '1', null);
INSERT INTO `web_access` VALUES ('11', '468', '2', '1', null);
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
INSERT INTO `web_access` VALUES ('11', '461', '2', '1', null);
INSERT INTO `web_access` VALUES ('11', '457', '2', '1', null);
INSERT INTO `web_access` VALUES ('11', '448', '2', '1', null);
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
INSERT INTO `web_access` VALUES ('18', '455', '3', '414', null);
INSERT INTO `web_access` VALUES ('18', '402', '3', '401', null);
INSERT INTO `web_access` VALUES ('19', '445', '3', '409', null);
INSERT INTO `web_access` VALUES ('18', '513', '3', '409', null);
INSERT INTO `web_access` VALUES ('18', '491', '3', '409', null);
INSERT INTO `web_access` VALUES ('18', '467', '3', '409', null);
INSERT INTO `web_access` VALUES ('18', '427', '3', '426', null);
INSERT INTO `web_access` VALUES ('18', '428', '3', '426', null);
INSERT INTO `web_access` VALUES ('18', '429', '3', '426', null);
INSERT INTO `web_access` VALUES ('18', '430', '3', '426', null);
INSERT INTO `web_access` VALUES ('18', '454', '3', '414', null);
INSERT INTO `web_access` VALUES ('18', '466', '3', '409', null);
INSERT INTO `web_access` VALUES ('18', '453', '3', '414', null);
INSERT INTO `web_access` VALUES ('18', '452', '3', '414', null);
INSERT INTO `web_access` VALUES ('18', '451', '3', '414', null);
INSERT INTO `web_access` VALUES ('18', '492', '3', '414', null);
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
INSERT INTO `web_access` VALUES ('18', '431', '3', '414', null);
INSERT INTO `web_access` VALUES ('18', '448', '2', '1', null);
INSERT INTO `web_access` VALUES ('18', '449', '3', '448', null);
INSERT INTO `web_access` VALUES ('18', '493', '3', '414', null);
INSERT INTO `web_access` VALUES ('18', '423', '3', '414', null);
INSERT INTO `web_access` VALUES ('18', '494', '3', '414', null);
INSERT INTO `web_access` VALUES ('18', '420', '3', '414', null);
INSERT INTO `web_access` VALUES ('18', '419', '3', '414', null);
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
INSERT INTO `web_access` VALUES ('18', '417', '3', '414', null);
INSERT INTO `web_access` VALUES ('18', '414', '2', '1', null);
INSERT INTO `web_access` VALUES ('18', '458', '3', '457', null);
INSERT INTO `web_access` VALUES ('18', '459', '3', '457', null);
INSERT INTO `web_access` VALUES ('18', '460', '3', '457', null);
INSERT INTO `web_access` VALUES ('11', '440', '2', '1', null);
INSERT INTO `web_access` VALUES ('11', '432', '2', '1', null);
INSERT INTO `web_access` VALUES ('11', '426', '2', '1', null);
INSERT INTO `web_access` VALUES ('11', '414', '2', '1', null);
INSERT INTO `web_access` VALUES ('11', '409', '2', '1', null);
INSERT INTO `web_access` VALUES ('11', '404', '2', '1', null);
INSERT INTO `web_access` VALUES ('11', '396', '2', '1', null);
INSERT INTO `web_access` VALUES ('11', '401', '2', '1', null);
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
INSERT INTO `web_access` VALUES ('11', '166', '2', '1', null);
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
INSERT INTO `web_access` VALUES ('11', '164', '2', '1', null);
INSERT INTO `web_access` VALUES ('11', '111', '2', '1', null);
INSERT INTO `web_access` VALUES ('11', '106', '2', '1', null);
INSERT INTO `web_access` VALUES ('11', '485', '2', '1', null);
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
INSERT INTO `web_access` VALUES ('18', '415', '3', '414', null);
INSERT INTO `web_access` VALUES ('18', '496', '3', '495', null);
INSERT INTO `web_access` VALUES ('18', '497', '3', '495', null);
INSERT INTO `web_access` VALUES ('18', '498', '3', '495', null);
INSERT INTO `web_access` VALUES ('18', '456', '3', '414', null);
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
) ENGINE=MyISAM AUTO_INCREMENT=124 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_channel
-- ----------------------------
INSERT INTO `web_channel` VALUES ('1', '48', 'lenovo充值', '1');
INSERT INTO `web_channel` VALUES ('2', '9', '手工充值', '1');
INSERT INTO `web_channel` VALUES ('3', '22', 'pp助手充值', '1');
INSERT INTO `web_channel` VALUES ('4', '12', '91充值', '0');
INSERT INTO `web_channel` VALUES ('5', '21', '当乐充值', '1');
INSERT INTO `web_channel` VALUES ('6', '15', 'uc充值', '1');
INSERT INTO `web_channel` VALUES ('7', '16', '5gwan充值', '0');
INSERT INTO `web_channel` VALUES ('8', '34', '华为充值', '0');
INSERT INTO `web_channel` VALUES ('9', '43', '360充值', '1');
INSERT INTO `web_channel` VALUES ('10', '25', '小米充值', '1');
INSERT INTO `web_channel` VALUES ('11', '27', '机锋网充值', '1');
INSERT INTO `web_channel` VALUES ('12', '28', '安智充值', '0');
INSERT INTO `web_channel` VALUES ('13', '35', 'oppo充值', '1');
INSERT INTO `web_channel` VALUES ('14', '24', '移动MM充值', '0');
INSERT INTO `web_channel` VALUES ('15', '61', '17173充值', '0');
INSERT INTO `web_channel` VALUES ('16', '62', '37wan充值', '0');
INSERT INTO `web_channel` VALUES ('17', '63', '8849充值', '0');
INSERT INTO `web_channel` VALUES ('18', '64', 'pptv充值', '0');
INSERT INTO `web_channel` VALUES ('19', '33', '点金充值', '0');
INSERT INTO `web_channel` VALUES ('20', '65', '万普充值', '0');
INSERT INTO `web_channel` VALUES ('21', '66', '禅游充值', '0');
INSERT INTO `web_channel` VALUES ('22', '67', '百度充值', '0');
INSERT INTO `web_channel` VALUES ('23', '50', '有信充值', '0');
INSERT INTO `web_channel` VALUES ('24', '30', '应用汇充值', '0');
INSERT INTO `web_channel` VALUES ('25', '68', 'itools充值', '1');
INSERT INTO `web_channel` VALUES ('26', '70', 'vivo充值', '0');
INSERT INTO `web_channel` VALUES ('27', '71', '3g门户充值', '0');
INSERT INTO `web_channel` VALUES ('28', '72', '博雅科诺充值', '0');
INSERT INTO `web_channel` VALUES ('29', '73', '悠悠村充值', '0');
INSERT INTO `web_channel` VALUES ('30', '74', '酷动充值', '0');
INSERT INTO `web_channel` VALUES ('31', '49', '丫丫网充值', '0');
INSERT INTO `web_channel` VALUES ('32', '75', '虫虫', '1');
INSERT INTO `web_channel` VALUES ('33', '76', '37wanIOS充值', '0');
INSERT INTO `web_channel` VALUES ('34', '77', '搜狗充值', '0');
INSERT INTO `web_channel` VALUES ('35', '78', '迅雷充值', '0');
INSERT INTO `web_channel` VALUES ('36', '79', '快玩充值', '0');
INSERT INTO `web_channel` VALUES ('37', '80', '快用充值', '1');
INSERT INTO `web_channel` VALUES ('38', '81', '新丫丫网充值', '0');
INSERT INTO `web_channel` VALUES ('39', '82', '云游充值', '0');
INSERT INTO `web_channel` VALUES ('40', '19', 'apple充值', '1');
INSERT INTO `web_channel` VALUES ('41', '83', 'apple充值-加强版', '0');
INSERT INTO `web_channel` VALUES ('42', '84', '木蚂蚁充值', '0');
INSERT INTO `web_channel` VALUES ('43', '85', '阿波罗充值', '0');
INSERT INTO `web_channel` VALUES ('44', '86', '浩动IOS充值', '0');
INSERT INTO `web_channel` VALUES ('45', '87', '浩动android充值', '0');
INSERT INTO `web_channel` VALUES ('46', '89', '有信2充值', '0');
INSERT INTO `web_channel` VALUES ('47', '90', '手盟充值', '0');
INSERT INTO `web_channel` VALUES ('48', '46', 'pps充值', '0');
INSERT INTO `web_channel` VALUES ('49', '91', '爱思充值', '1');
INSERT INTO `web_channel` VALUES ('50', '92', '游龙充值', '0');
INSERT INTO `web_channel` VALUES ('51', '93', '起点充值', '0');
INSERT INTO `web_channel` VALUES ('52', '94', '蜗牛充值', '0');
INSERT INTO `web_channel` VALUES ('53', '95', '人人充值', '0');
INSERT INTO `web_channel` VALUES ('54', '96', '平安充值', '0');
INSERT INTO `web_channel` VALUES ('55', '97', 'XY苹果助手', '1');
INSERT INTO `web_channel` VALUES ('56', '98', '海马充值', '1');
INSERT INTO `web_channel` VALUES ('57', '99', '冒泡充值', '0');
INSERT INTO `web_channel` VALUES ('58', '100', 'wo商城', '0');
INSERT INTO `web_channel` VALUES ('59', '101', '绿岸充值', '0');
INSERT INTO `web_channel` VALUES ('60', '102', 'momo充值', '0');
INSERT INTO `web_channel` VALUES ('61', '103', '豌豆荚', '1');
INSERT INTO `web_channel` VALUES ('62', '104', 'YY充值', '0');
INSERT INTO `web_channel` VALUES ('63', '105', '海马android充值', '0');
INSERT INTO `web_channel` VALUES ('64', '106', '金立充值', '0');
INSERT INTO `web_channel` VALUES ('65', '107', '游酷充值', '0');
INSERT INTO `web_channel` VALUES ('66', '108', '同步充值', '1');
INSERT INTO `web_channel` VALUES ('67', '109', '柴米充值', '0');
INSERT INTO `web_channel` VALUES ('68', '110', '37wan-ios2', '0');
INSERT INTO `web_channel` VALUES ('69', '111', '百度91充值', '1');
INSERT INTO `web_channel` VALUES ('70', '112', '靠谱充值', '1');
INSERT INTO `web_channel` VALUES ('71', '113', '海港', '1');
INSERT INTO `web_channel` VALUES ('72', '114', 'ysdk', '1');
INSERT INTO `web_channel` VALUES ('73', '115', '果盘', '1');
INSERT INTO `web_channel` VALUES ('74', '116', '阿斯卡德', '1');
INSERT INTO `web_channel` VALUES ('75', '117', '兔兔', '1');
INSERT INTO `web_channel` VALUES ('76', '118', 'xiao7', '1');
INSERT INTO `web_channel` VALUES ('77', '119', '4399', '1');
INSERT INTO `web_channel` VALUES ('78', '120', '爱普', '1');
INSERT INTO `web_channel` VALUES ('79', '121', 'TT语音', '1');
INSERT INTO `web_channel` VALUES ('80', '122', '07073', '1');
INSERT INTO `web_channel` VALUES ('81', '123', '拇指玩', '1');
INSERT INTO `web_channel` VALUES ('82', '124', '乐游', '1');
INSERT INTO `web_channel` VALUES ('83', '125', '同游游', '1');
INSERT INTO `web_channel` VALUES ('84', '126', '汉风', '1');
INSERT INTO `web_channel` VALUES ('85', '127', '乐8', '1');
INSERT INTO `web_channel` VALUES ('86', '128', '支付宝-app', '1');
INSERT INTO `web_channel` VALUES ('87', '129', '微信支付-app', '1');
INSERT INTO `web_channel` VALUES ('88', '130', '夜神', '1');
INSERT INTO `web_channel` VALUES ('89', '131', '熊猫玩', '1');
INSERT INTO `web_channel` VALUES ('90', '132', '猎宝', '1');
INSERT INTO `web_channel` VALUES ('91', '133', '爱应用', '1');
INSERT INTO `web_channel` VALUES ('92', '134', 'play800-ios', '1');
INSERT INTO `web_channel` VALUES ('93', '135', '奥创', '1');
INSERT INTO `web_channel` VALUES ('94', '136', '点游', '1');
INSERT INTO `web_channel` VALUES ('95', '137', '港台', '1');
INSERT INTO `web_channel` VALUES ('96', '138', '快发', '1');
INSERT INTO `web_channel` VALUES ('97', '139', '魔方', '1');
INSERT INTO `web_channel` VALUES ('98', '140', '顺玩', '1');
INSERT INTO `web_channel` VALUES ('99', '141', '爱洛克', '0');
INSERT INTO `web_channel` VALUES ('100', '142', '牛牛', '1');
INSERT INTO `web_channel` VALUES ('101', '143', '星趣', '1');
INSERT INTO `web_channel` VALUES ('102', '144', 'google', '0');
INSERT INTO `web_channel` VALUES ('103', '145', '爱乐', '1');
INSERT INTO `web_channel` VALUES ('104', '146', '爱贝云', '1');
INSERT INTO `web_channel` VALUES ('105', '147', '1pay', '1');
INSERT INTO `web_channel` VALUES ('106', '148', '16yo', '1');
INSERT INTO `web_channel` VALUES ('107', '149', '龙虾', '1');
INSERT INTO `web_channel` VALUES ('108', '150', '盛天', '1');
INSERT INTO `web_channel` VALUES ('109', '151', '星宇', '1');
INSERT INTO `web_channel` VALUES ('110', '152', '逗游', '1');
INSERT INTO `web_channel` VALUES ('112', '153', 'play800-线下', '1');
INSERT INTO `web_channel` VALUES ('113', '154', '黑桃', '1');
INSERT INTO `web_channel` VALUES ('114', '155', '铠甲', '1');
INSERT INTO `web_channel` VALUES ('116', '156', '游戏fan', '1');
INSERT INTO `web_channel` VALUES ('117', '157', '火树', '1');
INSERT INTO `web_channel` VALUES ('118', '158', '三星数码', '1');
INSERT INTO `web_channel` VALUES ('119', '159', '创游', '1');
INSERT INTO `web_channel` VALUES ('120', '160', '深海', '1');
INSERT INTO `web_channel` VALUES ('121', '161', '乐视', '1');
INSERT INTO `web_channel` VALUES ('122', '162', 'QQ5(魅游)', '1');
INSERT INTO `web_channel` VALUES ('123', '163', '支付宝wap', '1');

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_config
-- ----------------------------
INSERT INTO `web_config` VALUES ('1', '4.4289', '6.9195');

-- ----------------------------
-- Table structure for web_dwfenbao
-- ----------------------------
DROP TABLE IF EXISTS `web_dwfenbao`;
CREATE TABLE `web_dwfenbao` (
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
) ENGINE=MyISAM AUTO_INCREMENT=184 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_dwfenbao
-- ----------------------------

-- ----------------------------
-- Table structure for web_dwfenbao_copy
-- ----------------------------
DROP TABLE IF EXISTS `web_dwfenbao_copy`;
CREATE TABLE `web_dwfenbao_copy` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `fenbao_id` int(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `fenbao` (`fenbao_id`)
) ENGINE=MyISAM AUTO_INCREMENT=73 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_dwfenbao_copy
-- ----------------------------

-- ----------------------------
-- Table structure for web_emp_account
-- ----------------------------
DROP TABLE IF EXISTS `web_emp_account`;
CREATE TABLE `web_emp_account` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `accountid` int(11) NOT NULL,
  `serverid` int(11) NOT NULL,
  `operator` varchar(50) NOT NULL COMMENT '操作人',
  `name` varchar(50) NOT NULL COMMENT '角色名',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk` (`accountid`,`serverid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_emp_account
-- ----------------------------

-- ----------------------------
-- Table structure for web_erp_level
-- ----------------------------
DROP TABLE IF EXISTS `web_erp_level`;
CREATE TABLE `web_erp_level` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `level` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of web_erp_level
-- ----------------------------

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
  `link` varchar(30) NOT NULL,
  `port` smallint(30) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `combined_service` varchar(50) NOT NULL,
  `status` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=531 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_game_server
-- ----------------------------
INSERT INTO `web_game_server` VALUES ('46', '8', '3011', '应用宝11-霸王花', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('6', '8', '8001', '1皮卡丘', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('7', '8', '8002', '2妙蛙种子', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('47', '8', '8019', '19派拉斯', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('10', '8', '8003', '3小火龙', '', '0', '', '', '', '1');
INSERT INTO `web_game_server` VALUES ('11', '8', '8004', '4杰尼龟', '', '0', '', '', '', '1');
INSERT INTO `web_game_server` VALUES ('12', '8', '8005', '5绿毛虫', '', '0', '', '', '', '1');
INSERT INTO `web_game_server` VALUES ('13', '8', '8006', '6独角虫', '', '0', '', '', '', '1');
INSERT INTO `web_game_server` VALUES ('14', '8', '8007', '7比比鸟', '', '0', '', '', '', '1');
INSERT INTO `web_game_server` VALUES ('15', '8', '8008', '8小拉达', '', '0', '', '', '', '1');
INSERT INTO `web_game_server` VALUES ('16', '8', '8009', '9大嘴雀', '', '0', '', '', '', '1');
INSERT INTO `web_game_server` VALUES ('17', '8', '8010', '10阿柏蛇', '', '0', '', '', '', '1');
INSERT INTO `web_game_server` VALUES ('18', '8', '3001', '应用宝1-妙蛙花', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('19', '8', '3002', '应用宝2-喷火龙', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('20', '8', '3003', '应用宝3-水箭龟', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('21', '8', '6001', '硬核1-妙蛙草', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('22', '8', '6002', '硬核2-火恐龙', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('23', '8', '6003', '硬核3-卡咪龟', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('24', '8', '6004', '硬核4-铁甲蛹', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('29', '8', '8013', '13尼多朗', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('27', '8', '8011', '11穿山鼠', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('28', '8', '8012', '12尼多兰', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('30', '8', '8014', '14皮皮', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('31', '8', '6005', '硬核5-波波', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('32', '8', '6006', '硬核6-烈雀', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('33', '8', '3004', '应用宝4-巴大胡', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('34', '8', '3005', '应用宝5-大针蜂', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('35', '8', '3006', '应用宝6-拉达', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('36', '8', '3007', '应用宝7-阿柏怪', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('37', '8', '3008', '应用宝8-雷丘', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('38', '8', '3009', '应用宝9-穿山王', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('39', '8', '3010', '应用宝10-尼多后', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('40', '8', '8015', '15六尾', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('41', '8', '8016', '16胖丁', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('42', '8', '8017', '17超音蝠', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('43', '8', '8018', '18走路草', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('44', '8', '6007', '硬核7-双弹瓦斯', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('45', '8', '6008', '硬核8-尼多娜', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('48', '8', '8020', '20毛球', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('49', '8', '8021', '21地鼠', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('50', '8', '6009', '硬核9-皮可西', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('51', '8', '6010', '硬核10-九尾', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('52', '8', '3012', '应用宝12-胖可丁', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('53', '8', '8022', '22喵喵', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('54', '8', '8023', '23可达鸭', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('55', '8', '8024', '24猴怪', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('56', '8', '8025', '25卡蒂狗', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('57', '8', '6011', '硬核11-尼多王', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('58', '8', '6012', '硬核12-勇基拉', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('59', '8', '3013', '应用宝13大嘴蝠', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('60', '8', '3014', '应用宝14臭臭花', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('61', '8', '8026', '26蚊香蝌蚪', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('62', '8', '6013', '硬核13-玛瑙水母', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('63', '8', '3015', '应用宝15派拉斯特', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('64', '8', '8027', '27凯西', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('65', '8', '3016', '应用宝16猫老大', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('66', '8', '3017', '应用宝17哥达鸭', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('67', '8', '6014', '硬核14小火马', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('68', '8', '6015', '硬核15臭臭泥', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('69', '8', '8028', '28腕力', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('70', '8', '8029', '29火暴猴', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('71', '8', '3018', '应用宝18蚊香泳士', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('72', '8', '6016', '硬核16鬼斯通', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('73', '8', '8030', '30风速狗', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('74', '8', '8031', '31墨海马', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('75', '8', '8032', '32角金鱼', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('76', '8', '6017', '硬核17椰蛋树', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('77', '8', '6018', '硬核18顽皮弹', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('78', '8', '3019', '应用宝19口呆花', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('79', '8', '3020', '应用宝20毒刺水母', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('80', '8', '3021', '应用宝21菊草叶', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('81', '8', '8033', '33飞天螳螂', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('82', '8', '8034', '34肯泰罗', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('83', '8', '8035', '35伊布', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('84', '8', '8036', '36暴鲤龙', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('85', '8', '8037', '37菊石兽', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('86', '8', '8038', '38化石盔', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('87', '8', '8039', '39化石翼龙', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('88', '8', '8040', '40卡比兽', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('89', '8', '6019', '硬核19大舌贝', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('90', '8', '6020', '硬核20喇叭芽', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('91', '8', '3022', '应用宝22火球鼠', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('92', '8', '3023', '应用宝23小锯鳄', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('93', '8', '6021', '硬核21小拳石', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('94', '8', '3024', '应用宝24尾立', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('95', '8', '3025', '应用宝25猫头夜鹰', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('96', '8', '3026', '应用宝26圆丝蛛', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('97', '8', '6022', '硬核22月桂叶', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('98', '8', '6023', '硬核23火岩鼠', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('99', '8', '3027', '应用宝27灯笼鱼', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('100', '8', '3028', '应用宝28皮宝宝', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('101', '8', '8041', '41迷你龙', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('102', '8', '8042', '42快龙', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('103', '8', '8043', '43超梦', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('104', '8', '6024', '硬核24蓝鳄', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('105', '8', '3029', '应用宝29波克基古', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('106', '8', '8044', '44大竺葵', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('107', '8', '6025', '硬核25大尾立', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('108', '8', '3030', '应用宝30咩利羊', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('109', '8', '8045', '45火暴兽', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('110', '8', '3031', '应用宝31美丽花', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('111', '8', '6026', '硬核26芭瓢虫', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('112', '8', '8046', '46大力鳄', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('113', '8', '3032', '应用宝32树才怪', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('114', '8', '6027', '硬核27阿利多斯', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('115', '8', '8047', '47咕咕', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('116', '8', '6028', '硬核28电灯怪', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('117', '8', '3033', '应用宝33毽子花', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('118', '8', '8048', '48安瓢虫', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('119', '8', '8049', '49叉字蝠', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('120', '8', '8050', '50皮丘', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('121', '8', '6029', '硬核29宝宝丁', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('122', '8', '3034', '应用宝34向日种子', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('123', '8', '8051', '51波克比', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('124', '8', '6030', '硬核30天然雀', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('125', '8', '3035', '应用宝35乌波', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('126', '8', '8052', '52天然鸟', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('127', '8', '6031', '硬核31茸茸羊', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('128', '8', '3036', '应用宝36太阳伊布', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('129', '8', '8053', '53玛力露丽', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('130', '8', '5001', 'P01皮卡丘', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('131', '8', '8054', '54电龙', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('132', '8', '6033', '硬核33蚊香蛙皇', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('133', '8', '6032', '硬核32玛力露', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('134', '8', '3037', '应用宝37黑暗鸦', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('135', '8', '8055', '55毽子草', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('136', '8', '8056', '56长尾怪手', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('137', '8', '8057', '57蜻蜻蜓', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('138', '8', '6034', '硬核34沼王', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('153', '8', '6035', '硬核35向日花怪', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('141', '8', '3038', '应用宝38未知图腾', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('142', '8', '3039', '应用宝39榛果球', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('143', '8', '3040', '应用宝40天蝎', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('144', '8', '5002', 'P02小火龙', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('145', '8', '5003', 'P03杰尼龟', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('146', '8', '5004', 'P04妙蛙种子', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('147', '8', '5005', 'P05可达鸭', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('148', '8', '5006', 'P06绿毛虫', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('149', '8', '5007', 'P07小拉达', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('150', '8', '5008', 'P08阿柏蛇', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('151', '8', '5009', 'P09走路草', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('152', '8', '5010', 'P10喇叭芽', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('154', '8', '8058', '58月亮伊布', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('155', '8', '6036', '硬核36呆呆王', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('156', '8', '8059', '59梦妖', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('157', '8', '6037', '硬核37毽子棉', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('158', '8', '8060', '60麒麟奇', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('159', '8', '8061', '61土龙弟弟', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('160', '8', '8062', '62布鲁', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('161', '8', '8063', '63巨钳螳螂', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('162', '8', '8064', '64狃拉', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('163', '8', '8065', '65熔岩虫', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('164', '8', '8066', '66长毛猪', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('165', '8', '6038', '硬核38果然翁', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('166', '8', '6039', '硬核39佛烈托斯', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('167', '8', '6040', '硬核40大钢蛇', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('168', '8', '6041', '硬核41千针鱼', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('169', '8', '6042', '硬核42赫拉克罗斯', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('170', '8', '6043', '硬核43圈圈熊', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('171', '8', '6044', '硬核44小山猪', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('172', '8', '3041', '应用宝41布鲁皇', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('173', '8', '3042', '应用宝42壶壶', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('174', '8', '3043', '应用宝43熊宝宝', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('175', '8', '3044', '应用宝44熔岩蜗牛', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('176', '8', '5011', 'P11小拳石', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('177', '8', '5012', 'P12呆呆兽', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('178', '8', '5013', 'P13小海狮', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('179', '8', '3045', '应用宝45太阳珊瑚', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('180', '8', '8067', '67章鱼桶', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('181', '8', '8068', '68盔甲鸟', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('182', '8', '8069', '69刺龙王', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('183', '8', '8070', '70惊角鹿', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('184', '8', '5014', 'P14大舌贝', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('185', '8', '5015', 'P15雷电球', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('186', '8', '5016', 'P16瓦斯弹', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('187', '8', '5017', 'P17吉利蛋', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('188', '8', '5018', 'P18角金鱼', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('189', '8', '5019', 'P19海星星', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('190', '8', '5020', 'P20百变怪', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('191', '8', '5021', 'P21喵喵怪', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('192', '8', '5022', 'P22水伊布', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('193', '8', '5023', 'P23急冻鸟', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('194', '8', '5024', 'P24化石盔', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('195', '8', '5025', 'P25火球鼠', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('196', '8', '6045', '硬核45铁炮鱼', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('197', '8', '15001', 'P安1喷火龙', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('198', '8', '6046', '硬核46巨翅飞鱼', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('199', '8', '6047', '硬核47黑鲁加', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('200', '8', '3046', '应用宝46信使鸟', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('201', '8', '5026', 'P26菊草叶', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('202', '8', '5027', 'P27小锯鳄', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('203', '8', '5028', 'P28宝宝丁', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('204', '8', '5029', 'P29咩利羊', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('205', '8', '5030', 'P30超梦梦', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('206', '8', '15002', 'P安2水箭龟', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('277', '8', '5077', 'P77毽子草', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('278', '8', '5078', 'P78玛力露', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('210', '8', '3047', '应用宝47戴鲁比', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('211', '8', '6048', '硬核48顿甲', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('212', '8', '8071', '71战舞郎', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('213', '8', '6049', '硬核49无畏小子', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('214', '8', '3048', '应用宝48小小象', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('215', '8', '5031', 'P31妙蛙草', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('216', '8', '5032', 'P32火恐龙', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('217', '8', '5033', 'P33卡咪龟', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('218', '8', '5034', 'P34铁甲蛹', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('219', '8', '5035', 'P35独角虫', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('220', '8', '5036', 'P36比比鸟', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('221', '8', '5037', 'P37穿山鼠', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('222', '8', '5038', 'P38尼多兰', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('223', '8', '5039', 'P39超音蝠', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('224', '8', '5040', 'P40臭臭花', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('225', '8', '5041', 'P41派拉斯', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('226', '8', '8072', '72鸭嘴宝宝', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('227', '8', '8073', '73雷公', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('228', '8', '6050', '硬核50电击怪', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('229', '8', '6051', '硬核51幸福蛋', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('230', '8', '3049', '应用宝49图图犬', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('231', '8', '5042', 'P42大葱鸭', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('232', '8', '5043', 'P43小磁怪', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('233', '8', '5044', 'P44椰蛋树', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('234', '8', '5045', 'P45天然雀', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('235', '8', '5046', 'P46火稚鸡', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('236', '8', '5047', 'P47灯笼鱼', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('237', '8', '5048', 'P48芭瓢虫', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('238', '8', '5049', 'P49火岩鼠', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('239', '8', '5050', 'P50大力鳄', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('240', '8', '5051', 'P51茸茸羊', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('241', '8', '8074', '74幼基拉斯', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('242', '8', '6052', '硬核52水君', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('243', '8', '3050', '应用宝50迷唇娃', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('244', '8', '6053', '硬核53班基拉斯', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('245', '8', '8075', '75洛奇亚', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('246', '8', '5052', 'P52美丽花', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('247', '8', '5053', 'P53圈圈熊', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('248', '8', '5054', 'P54熔岩虫', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('249', '8', '5055', 'P55小山猪', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('250', '8', '5056', 'P56千针鱼', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('251', '8', '5057', 'P57榛果球', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('252', '8', '5058', 'P58惊角鹿', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('253', '8', '5059', 'P59图图犬', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('254', '8', '5060', 'P60迷唇娃', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('255', '8', '5061', 'P61卷卷耳', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('256', '8', '5062', 'P62长耳兔', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('257', '8', '5063', 'P63萤光鱼', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('258', '8', '5064', 'P64斗笠菇', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('259', '8', '5065', 'P65沙奈朵', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('260', '8', '5066', 'P66蛇纹熊', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('261', '8', '5067', 'P67优雅猫', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('262', '8', '5068', 'P68傲骨燕', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('263', '8', '5069', 'P69玛沙那', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('264', '8', '5070', 'P70刺尾虫', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('265', '8', '5071', 'P71毒粉蝶', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('266', '8', '5072', 'P72噗噗猪', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('267', '8', '5073', 'P73落雷兽', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('268', '8', '5074', 'P74毒蔷薇', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('269', '8', '5075', 'P75甜甜萤', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('270', '8', '5076', 'P76勾魂眼', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('271', '8', '8076', '76蜥蜴王', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('272', '8', '6054', '硬核54时拉比', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('273', '8', '3051', '应用宝51大奶罐', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('274', '8', '8077', '77火焰鸡', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('275', '8', '6055', '硬核55森林蜥蜴', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('276', '8', '3052', '应用宝52炎帝', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('279', '8', '5079', 'P79黑暗鸦', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('280', '8', '5080', 'P80铁炮鱼', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('281', '8', '5081', 'P81幸福蛋', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('282', '8', '5082', 'P82力壮鸡', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('283', '8', '5083', 'P83水跃鱼', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('284', '8', '5084', 'P84橡实果', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('285', '8', '5085', 'P85果然翁', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('286', '8', '5086', 'P86过动猿', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('287', '8', '5087', 'P87风铃铃', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('288', '8', '5088', 'P88雪童子', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('289', '8', '5089', 'P89珍珠贝', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('290', '8', '5090', 'P90樱花鱼', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('291', '8', '5091', 'P91海魔狮', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('292', '8', '5092', 'P92美纳斯', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('293', '8', '5093', 'P93鲶鱼王', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('294', '8', '3053', '应用宝53沙基拉斯', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('295', '8', '6056', '硬核56力壮鸡', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('296', '8', '6057', '硬核57沼跃鱼', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('297', '8', '8078', '78巨沼怪', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('298', '8', '8079', '79蛇纹熊', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('299', '8', '5094', 'P94电萤虫', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('300', '8', '5095', 'P95蘑蘑菇', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('301', '8', '5096', 'P96热带龙', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('302', '8', '5097', 'P97铁哑铃', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('303', '8', '5098', 'P98固拉多', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('304', '8', '5099', 'P99跳跳猪', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('305', '8', '5100', 'P100基拉祈', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('306', '8', '5101', 'P101朝北鼻', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('307', '8', '8080', '80甲壳茧', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('308', '8', '8081', '81毒粉蛾', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('309', '8', '6058', '硬核58大狼犬', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('310', '8', '6059', '硬核59刺尾虫', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('311', '8', '3054', '应用宝54凤王', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('312', '8', '3055', '应用宝55木守宫', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('313', '8', '8082', '82乐天河童', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('314', '8', '8083', '83狡猾天狗', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('315', '8', '8084', '84长翅鸥', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('316', '8', '6060', '硬核60盾甲茧', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('317', '8', '6061', '硬核61莲帽小童', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('318', '8', '3056', '应用宝56火稚鸡', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('319', '8', '5102', 'P102笨笨鱼', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('320', '8', '5103', 'P103呆火驼', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('321', '8', '8085', '85奇鲁莉安', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('322', '8', '8086', '86雨翅蛾', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('323', '8', '6062', '硬核62长鼻叶', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('324', '8', '6063', '硬核63大王燕', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('325', '8', '3057', '应用宝57水跃鱼', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('326', '8', '3058', '应用宝58土狼犬', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('327', '8', '5104', 'P104向尾喵', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('328', '8', '5105', 'P105盾甲茧', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('329', '8', '5106', 'P106土狼犬', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('330', '8', '5107', 'P107沼跃鱼', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('331', '8', '5108', 'P108火焰鸡', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('332', '8', '5109', 'P109战舞郎', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('333', '8', '5110', 'P110电击怪', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('334', '8', '5111', 'P111树才怪', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('335', '8', '8087', '87懒人獭', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('336', '8', '8088', '88土居忍士', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('337', '8', '8089', '89咕妞妞', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('338', '8', '8090', '90幕下力士', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('339', '8', '8091', '91朝北鼻', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('340', '8', '8092', '92勾魂眼', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('341', '8', '6064', '硬核64拉鲁拉丝', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('342', '8', '6065', '硬核65溜溜糖球', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('343', '8', '6066', '硬核66斗笠菇', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('344', '8', '6067', '硬核67请假王', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('345', '8', '6068', '硬核68脱壳忍者', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('346', '8', '5112', 'P112大尾立', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('347', '8', '5113', 'P113蔓藤怪', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('348', '8', '5114', 'P114飞腿郎', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('349', '8', '5115', 'P115摩鲁蛾', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('350', '8', '5116', 'P116尼多后', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('351', '8', '5117', 'P117勇基拉', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('352', '8', '5118', 'P118催眠貘', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('353', '8', '5119', 'P119快拳郎', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('354', '8', '5120', 'P120墨海马', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('355', '8', '3059', '应用宝59直冲熊', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('356', '8', '3060', '应用宝60狩猎凤蝶', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('357', '8', '3061', '应用宝61莲叶童子', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('358', '8', '3062', '应用宝62橡实果', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('359', '8', '8093', '93可多拉', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('360', '8', '8094', '94落雷兽', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('361', '8', '6069', '硬核69爆音怪', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('362', '8', '6070', '硬核70露力丽', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('363', '8', '3063', '应用宝63傲骨燕', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('364', '8', '3064', '应用宝64大嘴鸥', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('365', '8', '5121', 'P121呆壳兽', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('366', '8', '5122', 'P122烈焰马', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('367', '8', '5123', 'P123三地鼠', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('368', '8', '5124', 'P124尼多娜', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('369', '8', '5125', 'P125巨钳蟹', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('370', '8', '5126', 'P126金鱼王', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('371', '8', '5127', 'P127凯罗斯', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('372', '8', '5128', 'P128暴鲤龙', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('373', '8', '5129', 'P129海刺龙', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('374', '8', '5130', 'P130雷伊布', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('375', '8', '5131', 'P131多边兽', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('376', '8', '5132', 'P132迷你龙', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('377', '8', '5133', 'P133卡比兽', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('378', '8', '5134', 'P134萌芽鹿', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('379', '8', '8095', '95负电拍拍', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('380', '8', '8096', '96毒蔷薇', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('381', '8', '8097', '97利牙鱼', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('382', '8', '8098', '98吼鲸王', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('383', '8', '6071', '硬核71优雅猫', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('384', '8', '6072', '硬核72可可多拉', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('385', '8', '6073', '硬核73恰雷姆', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('386', '8', '6074', '硬核74正电拍拍', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('387', '8', '3065', '应用宝65沙奈朵', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('388', '8', '3066', '应用宝66蘑蘑菇', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('389', '8', '5135', 'P135盖盖虫', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('390', '8', '5136', 'P136石丸子', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('391', '8', '5137', 'P137豆豆鸽', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('392', '8', '5138', 'P138轻飘飘', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('393', '8', '8099', '99煤炭龟', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('394', '8', '8100', '100晃晃斑', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('395', '8', '8101', '101青绵鸟', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('396', '8', '6075', '硬核75甜甜萤', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('397', '8', '6076', '硬核76吞食兽', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('398', '8', '6077', '硬核77吼吼鲸', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('399', '8', '3067', '应用宝67过动猿', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('400', '8', '3068', '应用宝68铁面忍者', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('401', '8', '5139', 'P139波波鸽', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('402', '8', '5140', 'P140花椰猿', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('403', '8', '5141', 'P141木棉球', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('404', '8', '5142', 'P142猫鼬斩', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('405', '8', '5143', 'P143天秤偶', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('406', '8', '5144', 'P144木守宫', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('407', '8', '5145', 'P145懒人翁', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('408', '8', '5146', 'P146爱心鱼', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('409', '8', '5147', 'P147喷火驼', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('410', '8', '5148', 'P148宝贝龙', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('411', '8', '5149', 'P149甲壳龙', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('412', '8', '5150', 'P150甲壳蛹', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('413', '8', '5151', 'P151冰鬼护', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('414', '8', '5152', 'P152盖欧卡', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('415', '8', '5153', 'P153晃晃斑', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('416', '8', '5154', 'P154海豹球', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('417', '8', '5155', 'P155金属怪', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('418', '8', '5156', 'P156大颚蚁', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('419', '8', '5157', 'P157变隐龙', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('420', '8', '8102', '102饭匙蛇', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('421', '8', '8103', '103泥泥鳅', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('422', '8', '8104', '104铁螯龙虾', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('423', '8', '6078', '硬核78喷火驼', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('424', '8', '6079', '硬核79噗噗猪', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('425', '8', '6080', '硬核80沙漠蜻蜓', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('426', '8', '3069', '应用宝69吼爆弹', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('427', '8', '3070', '应用宝70铁掌力士', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('428', '8', '8105', '105触手百合', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('429', '8', '8106', '106太古盔甲', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('430', '8', '8107', '107飘浮泡泡', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('431', '8', '6081', '硬核81猫鼬斩', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('432', '8', '6082', '硬核82太阳岩', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('433', '8', '6083', '硬核83龙虾小兵', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('434', '8', '5158', 'P158咕妞妞', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('435', '8', '5159', 'P159吞食兽', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('436', '8', '5160', 'P160吼爆弹', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('437', '8', '5161', 'P161爆音怪', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('438', '8', '5162', 'P162夜骷颅', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('439', '8', '5163', 'P163吼吼鲸', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('440', '8', '3071', '应用宝71向尾喵', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('441', '8', '3072', '应用宝72大嘴娃', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('442', '8', '5164', 'P164夜巨人', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('443', '8', '5165', 'P165草苗龟', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('444', '8', '5166', 'P166姆克儿', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('445', '8', '5167', 'P167东施喵', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('446', '8', '5168', 'P168由克希', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('447', '8', '5169', 'P169霏欧纳', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('448', '8', '5170', 'P170战槌龙', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('449', '8', '5171', 'P171霓虹鱼', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('450', '8', '5172', 'P172玛纳霏', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('451', '8', '8108', '108诅咒娃娃', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('452', '8', '8109', '109彷徨夜灵', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('453', '8', '8110', '110风铃铃', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('454', '8', '8111', '111小果然', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('455', '8', '8112', '112冰鬼护', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('456', '8', '6084', '硬核84念力土偶', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('457', '8', '6085', '硬核85太古羽虫', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('458', '8', '6086', '硬核86美纳斯', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('495', '8', '13001', '创1皮卡丘', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('496', '8', '13002', '创2妙蛙种子', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('461', '8', '3073', '应用宝73玛沙那', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('462', '8', '3074', '应用宝74雷电兽', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('463', '8', '8113', '113海魔狮', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('464', '8', '8114', '114猎斑鱼', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('465', '8', '8115', '115古空棘鱼', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('466', '8', '8116', '116宝贝龙', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('467', '8', '6087', '硬核87怨影娃娃', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('468', '8', '6088', '硬核88夜巡灵', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('469', '8', '6089', '硬核89热带龙', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('470', '8', '6090', '硬核90阿勃梭鲁', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('471', '8', '6091', '硬核91雪童子', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('472', '8', '6092', '硬核92海豹球', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('473', '8', '5173', 'P173姆克鹰', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('474', '8', '5174', 'P174叶伊布', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('475', '8', '5175', 'P175海牛兽', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('476', '8', '5176', 'P176结草儿', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('477', '8', '5177', 'P177天蝎王', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('478', '8', '5178', 'P178盆才怪', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('479', '8', '5179', 'P179钳尾蝎', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('480', '8', '5180', 'P180好运蛋', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('481', '8', '5181', 'P181龙王蝎', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('482', '8', '5182', 'P182烈焰猴', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('483', '8', '5183', 'P183圆法师', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('484', '8', '5184', 'P184绅士蛾', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('485', '8', '5185', 'P185随风球', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('486', '8', '5186', 'P186魔尼尼', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('487', '8', '5185', 'P185随风球', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('488', '8', '5186', 'P186魔尼尼', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('489', '8', '5187', 'P187勒克猫', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('490', '8', '5188', 'P188梦妖魔', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('491', '8', '5189', 'P189不良蛙', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('492', '8', '5190', 'P190含羞苞', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('493', '8', '3075', '应用宝75电萤虫', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('494', '8', '3076', '应用宝76溶食兽', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('497', '8', '13003', '创3妙蛙草', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('498', '8', '13004', '创4妙蛙花', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('499', '8', '13005', '创5小火龙', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('500', '8', '8117', '117金属怪', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('501', '8', '8118', '118雷吉艾斯', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('502', '8', '8119', '119拉帝亚斯', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('503', '8', '8120', '120盖欧卡', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('504', '8', '8121', '121烈空座', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('505', '8', '8122', '122代欧奇希斯', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('506', '8', '8123', '123树林龟', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('507', '8', '8124', '124猛火猴', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('508', '8', '6093', '硬核93珍珠贝', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('509', '8', '6094', '硬核94樱花鱼', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('510', '8', '6095', '硬核95爱心鱼', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('511', '8', '6096', '硬核96甲壳龙', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('512', '8', '6097', '硬核97铁哑铃', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('513', '8', '6098', '硬核98雷吉洛克', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('514', '8', '6099', '硬核99雷吉斯奇鲁', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('515', '8', '6100', '硬核100拉帝欧斯', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('516', '8', '5191', 'P191花岩怪', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('517', '8', '5192', 'P192毒骷蛙', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('518', '8', '5193', 'P193雪妖女', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('519', '8', '5194', 'P194浮潜鼬', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('520', '8', '5195', 'P195魅力喵', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('521', '8', '5196', 'P196圆陆鲨', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('522', '8', '5197', 'P197尖牙笼', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('523', '8', '5198', 'P198洛托姆', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('524', '8', '5199', 'P199由克希', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('525', '8', '5200', 'P200藤藤蛇', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('526', '8', '5201', 'P201青藤蛇', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('527', '8', '3077', '应用宝77巨牙鲨', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('528', '8', '3078', '应用宝78呆火驼', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('529', '8', '3079', '应用宝79跳跳猪', '', '0', '', '', '', '0');
INSERT INTO `web_game_server` VALUES ('530', '8', '3080', '应用宝80大颚蚁', '', '0', '', '', '', '0');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_login_auto
-- ----------------------------

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
INSERT INTO `web_manager` VALUES ('1', 'admin', 'c571637aa1a2e1b1bf862566ba7eae52', '1', '落雪', '663', '1499224671', '0', '127.0.0.1', null, '8', '1', '0', '0', '0');
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
  PRIMARY KEY (`id`,`addtime`),
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
) ENGINE=MyISAM AUTO_INCREMENT=215 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='菜单';

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
) ENGINE=MyISAM AUTO_INCREMENT=522 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

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
) ENGINE=MyISAM AUTO_INCREMENT=1581995 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_pay_log
-- ----------------------------

-- ----------------------------
-- Table structure for web_pay_log_copy
-- ----------------------------
DROP TABLE IF EXISTS `web_pay_log_copy`;
CREATE TABLE `web_pay_log_copy` (
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
) ENGINE=MyISAM AUTO_INCREMENT=1183794 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_pay_log_copy
-- ----------------------------

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
) ENGINE=MyISAM AUTO_INCREMENT=297998 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_token
-- ----------------------------

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
