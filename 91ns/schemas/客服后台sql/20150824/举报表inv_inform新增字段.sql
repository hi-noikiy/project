ALTER TABLE `inv_inform`
ADD COLUMN `pic1`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '举报图片地址' AFTER `addTime`,
ADD COLUMN `pic2`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '举报图片地址' AFTER `pic1`,
ADD COLUMN `pic3`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '举报图片地址' AFTER `pic2`;

