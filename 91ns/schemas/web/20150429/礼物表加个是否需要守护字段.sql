ALTER TABLE `pre_gift_configs`
ADD COLUMN `guardFlag`  tinyint(1) NULL DEFAULT 0 COMMENT '是否需要是守护' AFTER `configName`;

 