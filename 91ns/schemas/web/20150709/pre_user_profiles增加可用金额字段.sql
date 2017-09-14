ALTER TABLE `pre_user_profiles`
ADD COLUMN `usefulMoney`  decimal(32,3) DEFAULT 0 COMMENT '可用金额' AFTER `answer`;