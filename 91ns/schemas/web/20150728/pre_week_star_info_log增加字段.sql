ALTER TABLE `pre_week_star_info_log`
ADD COLUMN `getNum`  int(11) NULL DEFAULT 0 COMMENT '主播收到的个数' AFTER `anchorId`,
ADD COLUMN `sendNum`  int(11) NULL DEFAULT 0 COMMENT '富豪送出的礼物' AFTER `richerId`;