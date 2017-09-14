insert into pre_car_configs(id,typeId,name,description,price,orderType,status,configName,sort)VALUES(49,4,'飞天扫帚','八富专属座驾',0,0,0,'ftsz',205),(50,4,'哈雷重机车','男爵专属座驾',0,0,0,'hlzjc',207),(51,4,'空中飞艇','子爵专属座驾',0,0,0,'kzft',208),(52,4,'挖掘机','伯爵专属座驾',0,0,0,'wjj',215),(53,4,'装甲坦克','侯爵专属座驾',0,0,0,'zjtk',225),(54,4,'飞鹰直升机','公爵专属座驾',0,0,0,'fyzsj',245),(55,4,'全能战舰','王爵专属座驾',0,0,0,'qnzj',255),(56,4,'拉风超跑极速','皇帝专属座驾',0,0,0,'lfcpjs',420),(57,4,'奢华天马','太皇专属座驾',0,0,0,'shtm',460),(58,4,'仙鹤','天皇专属座驾',0,0,0,'xh',470),(59,4,'八骏·古铜','帝皇专属座驾',0,0,0,'bj_gt',480),(60,4,'诺亚方舟','教皇专属座驾',0,0,0,'nyfz',490);
update pre_car_configs set typeId=4,description='九富专属座驾',sort=206 where id=44;

insert into pre_type_config(id,name,typeId,parentTypeId,createTime,description)VALUES(40,'回收站',1000,2,1451978937,'商城不显示的座驾');

ALTER TABLE `pre_car_configs`
MODIFY COLUMN `typeId`  int(11) NULL DEFAULT NULL AFTER `id`;



update pre_car_configs set typeId=1000 where id=8;
update pre_car_configs set typeId=1000 where id=9;
update pre_car_configs set typeId=1000 where id=10;


insert into pre_item_configs(id,type,name,description,configName)values(16,2,'91荣耀徽章','发放给91忠实用户的荣耀徽章。','ryhz');