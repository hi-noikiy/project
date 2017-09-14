ALTER TABLE `pre_guest_log`
MODIFY COLUMN `parentType`  varchar(30) NULL DEFAULT '' COMMENT '渠道类型' AFTER `uuid`,
MODIFY COLUMN `subType`  varchar(30) NULL DEFAULT '' COMMENT '渠道信息' AFTER `parentType`;

ALTER TABLE `pre_register_log`
MODIFY COLUMN `parentType`  varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '渠道类型' AFTER `id`,
MODIFY COLUMN `subType`  varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '渠道信息' AFTER `parentType`;

ALTER TABLE `pre_login_log`
ADD COLUMN `parentType`  varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '渠道类型' AFTER `ip`,
ADD COLUMN `subType`  varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '渠道信息' AFTER `parentType`;

