ALTER TABLE `pre_rooms`
ADD COLUMN `showStatus`  tinyint(1) NULL DEFAULT 1 COMMENT '�Ƿ���ʾ 1��ʾ 0����ʾ' AFTER `onlineNum`;

