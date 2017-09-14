ALTER TABLE `pre_rooms`
ADD COLUMN `roomType`  int(11) NULL DEFAULT 0 COMMENT '房间类型' AFTER `robotNum`;