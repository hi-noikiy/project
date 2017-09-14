ALTER TABLE `pre_gift_configs`
ADD COLUMN `littleSwf`  tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否小礼物动画' AFTER `tagDesc`;
insert into pre_gift_configs(id,typeId,name,coin,cash,recvCoin,discount,freeCount,littleFlag,orderType,createTime,configName,description,littleSwf)values(60,1,'被雷了',0,50,25,0,0,1,15,1447223021,'shocked','主播，我被你雷到了啦！',1);
update pre_gift_configs set typeId=1 where id=37;
update pre_gift_configs set orderType=CASE id 
 WHEN 59 THEN 11
 WHEN 40 THEN 12
 WHEN 4 THEN 13
 WHEN 7 THEN 14
 WHEN 37 THEN 16
 WHEN 8 THEN 17
 WHEN 13 THEN 18
 WHEN 14 THEN 19
 WHEN 17 THEN 20
 WHEN 19 THEN 21
 WHEN 21 THEN 22
 WHEN 22 THEN 23
WHEN 30 THEN 101
WHEN 29 THEN 102
WHEN 28 THEN 103
WHEN 23 THEN 104
WHEN 5 THEN 105
WHEN 33 THEN 106
WHEN 34 THEN 107
END
where id in(59,40,4,7,37,8,13,14,17,19,21,22,30,29,28,23,5,33,34);


