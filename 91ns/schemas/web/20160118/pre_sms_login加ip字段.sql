ALTER TABLE `pre_sms_log`
ADD COLUMN `ip`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'ip' AFTER `status`;