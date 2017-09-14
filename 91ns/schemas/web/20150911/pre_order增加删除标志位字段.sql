ALTER TABLE `pre_order`
ADD COLUMN `isDelete`  tinyint(3) NULL DEFAULT 0 COMMENT '删除标志0-正常1-删除' AFTER `receiveUid`;