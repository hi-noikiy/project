update pre_gift_configs set littleFlag=0 where id=58;
update pre_gift_configs set isDefault=1 where id=61;
update pre_gift_configs set isDefault=0 where id=4;
insert into pre_gift_configs(id,typeId,name,coin,cash,recvCoin,discount,freeCount,littleFlag,orderType,createTime,configName,description)values(62,2,'水晶王冠',0,3000,1500,0,0,0,98,1448690539,'sjwg','璀璨水晶，打动女神心');
insert into pre_gift_configs(id,typeId,name,coin,cash,recvCoin,discount,freeCount,littleFlag,orderType,createTime,configName,description)values(63,2,'女神皇冠',0,5000,2500,0,0,0,99,1448690539,'nshg','最高贵的皇冠请献给最完美的TA');
insert into pre_gift_configs(id,typeId,name,coin,cash,recvCoin,discount,freeCount,littleFlag,orderType,createTime,configName,description)values(64,2,'天使权杖',0,10000,5000,0,0,0,100,1448690539,'tsqz','纯洁无暇的权杖只属于你心中最美的女神');