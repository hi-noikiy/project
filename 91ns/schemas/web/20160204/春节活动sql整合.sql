insert into pre_gift_configs(id,typeId,name,coin,cash,recvCoin,discount,freeCount,littleFlag,orderType,createTime,configName,description)values(81,6,'幸运福袋',0,10,3,0,0,1,5,1453709611,'xyfd','福袋里满满的都是好运（增加1点年味，主播得3聊币）'),(82,6,'新春爆竹',0,2000,600,0,0,0,6,1453709611,'xcbz','爆竹声声辞旧岁（增加200点年味，主播得600聊币）'),(83,6,'财神驾到',0,10000,6000,0,0,0,8,1453709611,'csjd','财神驾到迎新年（主播得6000聊币）'),(84,6,'猴年红包',0,200,100,0,0,1,7,1453709611,'hnhb','猴年红包，更多人参与（赠送可派发红包）'),(85,6,'情书',0,10,6,0,0,1,4,1453709611,'qs','一封写满情话的信笺（主播得6聊币）');
update pre_gift_configs set richerLevel=4,tagDesc='四富及以上',tagPic='tag/zhibojian_dengji.png' where id=84;
insert into pre_lucky_gift_configs(giftId,pointer)values(81,1);
insert into pre_car_configs(id,typeId,name,description,price,status,configName,sort,orderType)values(61,10,'年兽','传说中的神兽，每年春节期间才会出现。',0,0,'ns',530,0);
update pre_car_configs set sort=520 where id=47;
update pre_car_configs set sort=510 where id=48;
insert into pre_item_configs(id,type,name,description,configName)values(22,2,'一掷千金','派发猴年红包次数最多的用户','yizhiqianjin'),(23,2,'财源滚滚','收到猴年红包次数最多的用户','caiyuangungun');

ALTER TABLE `pre_red_packet`
ADD COLUMN `redPacketType`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '红包类型：1：普通红包，2：猴年春节红包' AFTER `id`;
update pre_gift_configs set typeId=6 where id=80;

