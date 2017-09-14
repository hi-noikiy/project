ALTER TABLE `pre_rooms`
ADD COLUMN `totalNum`  int(11) NULL DEFAULT 0 AFTER `showStatus`;
ALTER TABLE `pre_rooms`
ADD INDEX `totalNum` (`totalNum`)