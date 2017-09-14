ALTER TABLE `pre_room_log`
ADD COLUMN `status`  tinyint(3) DEFAULT 1 COMMENT '房间状态' AFTER `endTime`;