ALTER TABLE `pre_user_profiles`
ADD COLUMN `points`  int(11) NULL DEFAULT 0 COMMENT '积分' AFTER `isOpenSign`;