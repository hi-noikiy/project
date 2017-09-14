
-- 主播配置表
-- 增加字段：roomLimitNum，房间人数上限字段（预留）
ALTER TABLE `pre_anchor_configs` ADD COLUMN `roomLimitNum`  int(11) NULL DEFAULT 0 COMMENT '房间上限人数';

-- 礼物配置表
-- 删除动画时间的字段
ALTER TABLE `pre_gift_configs` DROP COLUMN `flashLimit`;
-- 设置最低vip等级和富豪等级默认值为0
ALTER TABLE `pre_gift_configs` MODIFY COLUMN `vipLevel`  tinyint(3) NULL DEFAULT 0;
ALTER TABLE `pre_gift_configs` MODIFY COLUMN `richerLevel`  tinyint(3) NULL DEFAULT 0;

-- 座驾配置表
-- 删除动画时间的字段
ALTER TABLE `pre_car_configs` DROP COLUMN `flashLimit`;
-- -- 设置最低vip等级默认值为0
ALTER TABLE `pre_car_configs` MODIFY COLUMN `vipLevel`  tinyint(3) NULL DEFAULT 0;

-- 类型配置表
-- 座驾进房间的动画效果
ALTER TABLE `pre_type_config` ADD COLUMN `roomAnimate`  tinyint(3) NULL DEFAULT 0 COMMENT '主要用在座驾上，拥有座驾进房间之后，是否有大的座驾广播';







