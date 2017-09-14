ALTER TABLE `pre_consume_log`
ADD COLUMN `ratio`  smallint(3) DEFAULT 100 COMMENT '分成比例' AFTER `income`;