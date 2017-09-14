insert into pre_gift_configs(id,typeId,name,coin,cash,recvCoin,discount,freeCount,littleFlag,orderType,createTime,configName,description,littleSwf)values(78,6,'麋鹿',0,10,5,0,0,1,8,1449642409,'Milu','萌萌的小麋鹿，是圣诞节的精灵~',0),(79,7,'幸运菊花',0,15,3,0,0,1,9,1449642409,'jh','主播得3聊币，单组50起送可得2000倍大奖。每送2朵可得3积分，积分可参与夺宝。',1);
insert into pre_car_configs(id,typeId,name,description,price,orderType,status,configName)values(48,10,'圣诞雪橇','圣诞老人专属座驾，满载节日的祝福',0,410,1,'Milu_deer');
update pre_gift_configs set isDefault=0 where id=61;
update pre_gift_configs set isDefault=1 where id=78;
insert into pre_lucky_gift_configs(giftId,count,pointer)values(78,0,0),(79,0,0);
update pre_gift_package_configs set items='[{"type":0,"cash":188},{"type":3,"id":10,"num":88,"validity":0},{"type":2,"id":30,"num":1,"validity":2592000},{"type":4,"id":9,"num":1,"validity":2592000}]' where id=38;
insert into pre_gift_package_configs(id,`name`,`desc`,items)values(51,'圣诞礼包','圣诞礼包','[{"type":2,"id":48,"num":1,"validity":864000}]');