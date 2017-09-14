ALTER TABLE `pre_rooms`
ADD COLUMN `pushTime`  int(11) NULL AFTER `nextTime`;
ALTER TABLE `pre_rooms`
MODIFY COLUMN `pushTime`  int(11) NULL DEFAULT 0 COMMENT '0' AFTER `nextTime`;

