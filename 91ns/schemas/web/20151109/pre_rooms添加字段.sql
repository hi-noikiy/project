ALTER TABLE `pre_rooms`
ADD COLUMN `isOpenVideo`  tinyint(3) NULL DEFAULT 0 COMMENT '是否开启录像播放0-否-是' AFTER `streamName`;

