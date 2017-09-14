ALTER TABLE `pre_consume_log`
MODIFY COLUMN `amount`  float(32,3) NULL DEFAULT NULL AFTER `familyId`;