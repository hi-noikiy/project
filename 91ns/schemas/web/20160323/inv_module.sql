insert INTO `inv_module`(parentId,moduleName,moduleAction,moduleSort,moduleType,createTime,status)
 values('65','发放vip','sendVip','5','1',unix_timestamp(now()),'1');
insert INTO `inv_module`(parentId,moduleName,moduleAction,moduleSort,moduleType,createTime,status)
 values('4','推广用户','recDetailList','5','1',unix_timestamp(now()),'1');
 
 update `inv_module` set status = 0 where id= 67;
update `inv_module` set moduleName = '发放徽章' where id= 69;

CREATE TABLE `pre_app_count` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) DEFAULT NULL COMMENT '下载时间',
  `device` int(11) DEFAULT NULL COMMENT '设备名称（0安卓1ios）',
  `version` varchar(20) DEFAULT NULL COMMENT '版本号',
  `ip` varchar(20) DEFAULT NULL COMMENT '下载ip',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

insert INTO `inv_module`(parentId,moduleName,moduleAction,moduleSort,moduleType,createTime,status)
 values('54','下载统计','appCount','5','1',unix_timestamp(now()),'1');