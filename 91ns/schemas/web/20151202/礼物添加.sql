insert into pre_gift_configs(id,typeId,name,coin,cash,recvCoin,discount,freeCount,littleFlag,orderType,createTime,configName,description)values(65,2,'天使之翼',0,20000,10000,0,0,0,98,1448690539,'tszy','最神圣的翅膀，只属于你最心仪的TA');
insert into pre_gift_configs(id,typeId,name,coin,cash,recvCoin,discount,freeCount,littleFlag,orderType,createTime,configName,description)values(66,2,'女神之翼',0,50000,25000,0,0,0,99,1448690539,'nszy','炫丽的羽翼，女神的象征');
update pre_gift_configs set orderType=CASE id 
 WHEN 62 THEN 101
 WHEN 63 THEN 111
 WHEN 64 THEN 121
 WHEN 65 THEN 131
 WHEN 66 THEN 141
 WHEN 30 THEN 151
 WHEN 29 THEN 161
 WHEN 28 THEN 171
 WHEN 23 THEN 181
 WHEN 5 THEN 191
 WHEN 33 THEN 195
 WHEN 34 THEN 200
END
where id in(62,63,64,65,66,30,29,28,23,5,33,34);
