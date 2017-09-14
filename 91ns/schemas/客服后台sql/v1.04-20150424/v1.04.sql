DROP TABLE IF EXISTS `inv_login_log`;
CREATE TABLE `inv_login_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(32) DEFAULT NULL COMMENT '登录用户名',
  `ip` varchar(32) DEFAULT NULL,
  `loginType` tinyint(1) DEFAULT '0' COMMENT '登录类型：1登录2退出',
  `createTime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


ALTER TABLE `inv_operation_log`
MODIFY COLUMN `uid`  varchar(11) NULL DEFAULT 0 COMMENT '操作者id' AFTER `id`;
