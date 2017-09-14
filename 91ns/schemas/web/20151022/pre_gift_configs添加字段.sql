ALTER TABLE `pre_gift_configs`
ADD COLUMN `tagPic`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '礼物标签图片' AFTER `description`,
ADD COLUMN `isDefault`  tinyint(3) NULL DEFAULT 0 COMMENT '是否默认选中' AFTER `tagPic`;

ALTER TABLE `pre_gift_configs`
ADD COLUMN `tagDesc`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '标签描述' AFTER `isDefault`;