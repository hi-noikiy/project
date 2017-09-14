update pre_lucky_gift_configs set count=0;
ALTER TABLE `pre_lucky_gift_configs`
ADD COLUMN `pointer`  int(11) NOT NULL DEFAULT 0 COMMENT '当前指针位置' AFTER `count`;
