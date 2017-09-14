ALTER TABLE `pre_room_user_status`
ADD COLUMN `levelTimeLine`  int(11) NULL DEFAULT 0 COMMENT '管理员过期时间' AFTER `kickTimeLine`;