ALTER TABLE `pre_order`
ADD COLUMN `orderType`  smallint(4) NULL DEFAULT 1 COMMENT '订单类型 1：普通订单 2：新手引导订单' AFTER `tradeNo`;
