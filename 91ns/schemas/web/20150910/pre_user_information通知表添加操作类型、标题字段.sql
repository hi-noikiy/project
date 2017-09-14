
ALTER TABLE `pre_user_information`
ADD COLUMN `operType`  smallint(4) NULL DEFAULT 0 COMMENT '操作类型：查看、审核、续费' AFTER `createTime`,
ADD COLUMN `link`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '链接' AFTER `operType`;



ALTER TABLE `pre_user_information`
ADD COLUMN `title`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '标题' AFTER `uid`;