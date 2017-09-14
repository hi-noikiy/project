#骰宝
ALTER TABLE `pre_dice_detail`
ADD COLUMN `times`  int(11) NULL DEFAULT 0 COMMENT '连续开庄次数' AFTER `resultTime`;

#手机礼物默认选中
UPDATE pre_gift_configs SET isDefault = 0;
UPDATE pre_gift_configs SET isDefault = 1 WHERE id = 89;