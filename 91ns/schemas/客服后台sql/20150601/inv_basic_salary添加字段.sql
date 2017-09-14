ALTER TABLE `inv_basic_salary` ADD COLUMN `expirationTime`  int(11) NULL DEFAULT 0 COMMENT '过期时间' AFTER `type` ;
ALTER TABLE `inv_basic_salary` ADD COLUMN `changeInfo`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '变更信息' AFTER `expirationTime`;
ALTER TABLE `inv_basic_salary` ADD COLUMN `affectTime`  int(11) NULL DEFAULT 0 COMMENT '生效时间' AFTER `changeInfo`;
ALTER TABLE `inv_basic_salary` ADD COLUMN `status`  tinyint(3) NULL DEFAULT 0 COMMENT '过期类型1为永久' AFTER `uid`;