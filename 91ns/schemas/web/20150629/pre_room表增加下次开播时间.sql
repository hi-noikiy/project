ALTER TABLE `pre_rooms`
ADD COLUMN `nextTime`  int(11) DEFAULT 0 COMMENT '下次开播时间' AFTER `useAccelarate`;