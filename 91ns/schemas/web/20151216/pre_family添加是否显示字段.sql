ALTER TABLE `pre_family`
ADD COLUMN `isHide`  tinyint(3) NULL DEFAULT 0 COMMENT '是否隐藏' AFTER `settlementDate`;