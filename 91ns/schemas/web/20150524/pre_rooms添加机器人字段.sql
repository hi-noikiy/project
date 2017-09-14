ALTER TABLE `pre_rooms`
ADD COLUMN `robotNum`  int(11) NULL DEFAULT 0 COMMENT '机器人人数' AFTER `showStatus`;

