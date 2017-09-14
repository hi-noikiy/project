ALTER TABLE `pre_rooms`
ADD COLUMN `streamName`  varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '流名' AFTER `pushTime`;