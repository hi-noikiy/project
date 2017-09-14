insert into pre_gift_configs(id,typeId,name,coin,cash,recvCoin,discount,freeCount,littleFlag,orderType,createTime,configName,description)values(67,1,'掌声',0,10,5,0,0,1,98,1449642409,'Applaud','为TA的精彩表现鼓掌'),(68,1,'啤酒',0,10,5,0,0,1,99,1449642409,'Beer','让我们干了这杯酒'),(69,1,'芭比娃娃',0,20,10,0,0,1,99,1449642409,'Barbie_doll','你如芭比般梦幻'),(70,1,'熊猫',0,50,25,0,0,1,99,1449642409,'Panda','能给我拍一张彩色照片吗？'),(71,1,'玫瑰花束',0,90,45,0,0,1,99,1449642409,'mghs','请收下我一大捧的心意吧'),(72,1,'天使心',0,100,50,0,0,1,99,1449642409,'Angel_heart','如天使般纯洁的爱'),(73,1,'粉丝灯牌',0,150,75,0,0,1,99,1449642409,'Light_board','爱她，请让她看到'),(74,1,'丘比特',0,200,100,0,0,1,99,1449642409,'Cupid','来一“箭”钟情吧'),(75,1,'泰迪熊',0,240,120,0,0,1,99,1449642409,'Teddy_bear','最可爱的泰迪熊送给最可爱的TA'),(76,1,'Iphone6',0,400,200,0,0,1,99,1449642409,'Iphone6','请多多联系我'),(77,2,'梦幻城堡',0,8000,4000,0,0,0,99,1449642409,'Dream_castle','共筑爱巢，永浴爱河。');
update pre_gift_configs set orderType=CASE id 
 WHEN 4 THEN 20
 WHEN 7 THEN 25
 WHEN 37 THEN 30
 WHEN 67 THEN 35
 WHEN 68 THEN 40
 WHEN 69 THEN 45
 WHEN 70 THEN 50
 WHEN 71 THEN 51
 WHEN 72 THEN 55
 WHEN 73 THEN 60
 WHEN 74 THEN 65
 WHEN 75 THEN 70
 WHEN 76 THEN 75
 WHEN 60 THEN 80 
 WHEN 8 THEN 85
 WHEN 13 THEN 90  
 WHEN 14 THEN 95
 WHEN 17 THEN 100
 WHEN 19 THEN 105
 WHEN 21 THEN 110
 WHEN 22 THEN 115
 WHEN 77 THEN 145
END
where id in(4,7,37,67,68,69,70,71,72,73,74,75,76,77,60,8,13,14,17,19,21,22);
