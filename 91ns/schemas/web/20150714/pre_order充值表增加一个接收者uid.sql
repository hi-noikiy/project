ALTER TABLE `pre_order`
ADD COLUMN `receiveUid`  int(11) NULL DEFAULT 0 COMMENT '接收者的id' AFTER `orderType`;