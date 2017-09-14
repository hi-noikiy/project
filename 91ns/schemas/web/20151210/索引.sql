#桃花奖池表
ALTER TABLE `pre_lucky_gift_odds`
ADD INDEX (`sequence`) USING BTREE ;


#消费表
ALTER TABLE `pre_consume_detail_log`
ADD INDEX (`createTime`) USING BTREE ,
ADD INDEX (`isTuo`) USING BTREE ;

ALTER TABLE `pre_consume_detail_log`
ADD INDEX (`uid`, `receiveUid`) USING BTREE ;

#联合索引===针对推广员送礼
ALTER TABLE `pre_consume_detail_log`
ADD INDEX (`receiveUid`, `createTime`) USING BTREE ;

#畅销礼物
ALTER TABLE `pre_consume_detail_log`
ADD INDEX (`itemId`) USING BTREE ;

#幸运礼物配置表
ALTER TABLE `pre_lucky_gift_configs`
ADD INDEX (`giftId`) USING BTREE ;

