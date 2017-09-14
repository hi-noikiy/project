ALTER TABLE `pre_car_configs`
ADD COLUMN `hasBigCar`  tinyint(3) NULL DEFAULT 0 COMMENT '是否有大座驾' AFTER `configName`,
ADD COLUMN `positionX`  int(11) NULL DEFAULT 0 COMMENT 'X轴位置' AFTER `hasBigCar`,
ADD COLUMN `positionY`  int(11) NULL DEFAULT 0 COMMENT 'Y轴位置' AFTER `positionX`,
ADD COLUMN `sort`  tinyint(3) NULL DEFAULT 1 COMMENT '排序' AFTER `positionY`;


ALTER TABLE `pre_car_configs`
CHANGE COLUMN `positionX` `positionX1`  int(11) NULL DEFAULT 0 COMMENT 'X轴位置1' AFTER `hasBigCar`,
CHANGE COLUMN `positionY` `positionY1`  int(11) NULL DEFAULT 0 COMMENT 'Y轴位置1' AFTER `positionX1`,
ADD COLUMN `positionX2`  int(11) NULL DEFAULT 0 COMMENT 'X轴位置2' AFTER `sort`,
ADD COLUMN `positionY2`  int(11) NULL DEFAULT 0 COMMENT 'Y轴位置2' AFTER `positionX2`;

ALTER TABLE `pre_car_configs`
MODIFY COLUMN `sort`  int(11) NULL DEFAULT 1 COMMENT '排序' AFTER `positionY1`;

