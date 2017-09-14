ALTER TABLE `pre_user_profiles`
MODIFY COLUMN `cash`  decimal(32,3) NULL DEFAULT NULL AFTER `coin`,
MODIFY COLUMN `money`  decimal(32,3) NULL DEFAULT NULL AFTER `cash`;

