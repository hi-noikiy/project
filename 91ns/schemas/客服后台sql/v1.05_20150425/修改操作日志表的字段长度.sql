ALTER TABLE `inv_operation_log`
MODIFY COLUMN `log1`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `createTime`,
MODIFY COLUMN `log2`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `log1`;