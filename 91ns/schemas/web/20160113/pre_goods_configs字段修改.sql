ALTER TABLE `pre_goods_configs`
MODIFY COLUMN `type`  int(11) NULL DEFAULT 1 COMMENT '商品类型' AFTER `perCash`;