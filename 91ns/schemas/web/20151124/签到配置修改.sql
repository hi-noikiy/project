insert into pre_gift_package_configs (id,name,`desc`,items) values(41,'每日签到奖励','每日签到奖励','[{"type":3,"id":61,"num":6,"validity":0}]');

insert into pre_gift_package_configs (id,name,`desc`,items) values(42,'累计签到7天奖励','累计签到7天奖励','[{"type":3,"id":61,"num":20,"validity":0}]');

insert into pre_gift_package_configs (id,name,`desc`,items) values(43,'累计签到14天奖励','累计签到14天奖励','[{"type":3,"id":61,"num":40,"validity":0}]');

insert into pre_gift_package_configs (id,name,`desc`,items) values(44,'累计签到20天奖励','累计签到20天奖励','[{"type":2,"id":19,"num":1,"validity":1728000}]');

insert into pre_gift_package_configs (id,name,`desc`,items) values(45,'连续签到2天奖励','连续签到2天奖励','[{"type":4,"id":15,"num":1000,"validity":0}]');

insert into pre_gift_package_configs (id,name,`desc`,items) values(46,'连续签到3天奖励','连续签到3天奖励','[{"type":3,"id":61,"num":4,"validity":0}]');

insert into pre_gift_package_configs (id,name,`desc`,items) values(47,'连续签到4天奖励','连续签到4天奖励','[{"type":3,"id":61,"num":8,"validity":0}]');

insert into pre_gift_package_configs (id,name,`desc`,items) values(48,'连续签到5天奖励','连续签到5天奖励','[{"type":3,"id":61,"num":12,"validity":0}]');

insert into pre_gift_package_configs (id,name,`desc`,items) values(49,'连续签到6天奖励','连续签到6天奖励','[{"type":3,"id":61,"num":16,"validity":0}]');

insert into pre_gift_package_configs (id,name,`desc`,items) values(50,'连续签到7天奖励','连续签到7天奖励','[{"type":3,"id":61,"num":20,"validity":0}]');

insert into pre_item_configs(id,type,name,description,configName,cash) values(15,1,'经验','富豪经验值卡','exp',1000.000);

update pre_sign_configs set `desc`='单组50起送可得2000倍大奖。',daysNum=7,package='[{"type":0,"ids":"42"}]' where id=1;
update pre_sign_configs set `desc`='单组50起送可得2000倍大奖。',daysNum=14,package='[{"type":0,"ids":"43"}]' where id=2;
update pre_sign_configs set `desc`='签到获得的座驾（20天使用权）',daysNum=20,package='[{"type":0,"ids":"44"}]' where id=3;
update pre_sign_configs set `desc`='领取后可增加1000富豪经验',package='[{"type":0,"ids":"45"}]' where id=4;
update pre_sign_configs set `desc`='单组50起送可得2000倍大奖。',package='[{"type":0,"ids":"46"}]' where id=5;
update pre_sign_configs set `desc`='单组50起送可得2000倍大奖。',package='[{"type":0,"ids":"47"}]' where id=6; 
update pre_sign_configs set `desc`='单组50起送可得2000倍大奖。',package='[{"type":0,"ids":"48"}]' where id=7; 
update pre_sign_configs set `desc`='单组50起送可得2000倍大奖。',package='[{"type":0,"ids":"49"}]' where id=8; 
update pre_sign_configs set `desc`='单组50起送可得2000倍大奖。',daysNum=7,package='[{"type":0,"ids":"50"}]' where id=9; 