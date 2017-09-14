ALTER TABLE `pre_recommend`
MODIFY COLUMN `id`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT FIRST ,
MODIFY COLUMN `uid`  int(11) NULL DEFAULT 0 COMMENT '推广用户uid' AFTER `id`,
MODIFY COLUMN `url`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '推广链接' AFTER `uid`,
MODIFY COLUMN `createTime`  int(11) NULL DEFAULT 0 COMMENT '添加时间' AFTER `url`,
ADD COLUMN `proportion`  int(11) NULL DEFAULT 5 COMMENT '抽成比例' AFTER `createTime`,
ADD COLUMN `validity`  int(11) NULL DEFAULT 30 AFTER `proportion`,
ADD COLUMN `remark`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '备注' AFTER `validity`,
ADD COLUMN `status`  tinyint(3) NULL DEFAULT 0 COMMENT '状态（暂定删除标志）' AFTER `remark`;

