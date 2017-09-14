ALTER TABLE `pre_rooms`
ADD COLUMN `showStatus`  tinyint(1) NULL DEFAULT 1 COMMENT '是否显示 1显示 0不显示' AFTER `onlineNum`;

