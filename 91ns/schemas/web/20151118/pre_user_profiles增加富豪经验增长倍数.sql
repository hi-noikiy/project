ALTER TABLE `pre_user_profiles`
ADD COLUMN `richRatio`  decimal(10,2) UNSIGNED NULL DEFAULT 1 COMMENT '富豪经验倍数' AFTER `exp3`;

