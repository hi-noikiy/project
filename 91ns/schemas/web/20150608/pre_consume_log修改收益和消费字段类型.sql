ALTER TABLE `pre_consume_log`
MODIFY COLUMN `amount`  decimal(32,3) NULL DEFAULT NULL AFTER `familyId`,
MODIFY COLUMN `income`  decimal(32,3) NULL DEFAULT 0.000 COMMENT '�����¼' AFTER `createTime`;

