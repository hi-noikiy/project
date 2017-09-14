ALTER TABLE `pre_user_profiles`
ADD COLUMN `level6`  tinyint(3) NULL DEFAULT 0 COMMENT '至尊vip' AFTER `level5`,
ADD COLUMN `vipExpireTime2`  int(11) NULL DEFAULT 0 COMMENT '至尊vip过期时间' AFTER `vipExpireTime`;