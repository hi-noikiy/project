ALTER TABLE `pre_gift_configs`
MODIFY COLUMN `description`  varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '描述' AFTER `guardFlag`;

insert into pre_gift_configs(id,typeId,name,coin,cash,recvCoin,discount,freeCount,littleFlag,orderType,createTime,configName,description,littleSwf)values(80,7,'开门红',0,10,2,0,0,1,8,1451379585,'kmh','主播得2聊币；50个起送可得2000倍大奖；1点至2点及13点至14点更有机会获得5000倍大奖；1朵得1积分；每2016个触发开门红大动画。',0);
insert into pre_lucky_gift_configs(giftId,count,pointer)values(80,0,0);


update pre_gift_configs set cash=10,recvCoin=2,description='主播得2聊币，单组50起送可得2000倍大奖。每送1朵可得1积分，积分可参与夺宝。' where id=79;

