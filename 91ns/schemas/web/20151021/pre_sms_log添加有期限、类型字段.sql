ALTER TABLE `pre_sms_log`
ADD COLUMN `captcha`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '验证码' AFTER `content`,
ADD COLUMN `expireTime`  int(11) NOT NULL DEFAULT 0 COMMENT '验证码过期时间' AFTER `createTime`;

ALTER TABLE `pre_sms_log`
ADD COLUMN `sidType`  int(11) NOT NULL DEFAULT 1 COMMENT '账号类型' AFTER `type`;

ALTER TABLE `pre_sms_log`
ADD COLUMN `status`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '是否可用' AFTER `expireTime`;





