ALTER TABLE `pre_user_info`
ADD COLUMN `city`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '所在城市' AFTER `ID`;

