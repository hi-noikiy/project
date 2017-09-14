-- 属性配置表

-- 商品管理
create table s_items(
  `id` int(10) not null AUTO_INCREMENT,
  `item_name` VARCHAR(100) not null,
  `created_at` int(10) not null,
  `item_type` int(10) not null,/*商品类型*/
  `appid` char(20) not null,
  PRIMARY KEY (`id`)
)ENGINE=InnoDB;
create table s_item_types(
  `id` int(10) not null AUTO_INCREMENT,
  `type_name` VARCHAR(100) not null,
  `created_at` int(10) not null,
  `appid` char(20) not null,
  PRIMARY KEY (`id`)
)ENGINE=InnoDB;

-- 道具管理
create table s_props(
  `id` int(10) not null AUTO_INCREMENT,
  `name` VARCHAR(100) not null,
  `created_at` int(10) not null,
  `type` int(10) not null,/*道具类型*/
  `gain_way` int(10) not null,/*道具获取路径*/
  `appid` char(20) not null,
  PRIMARY KEY (`id`)
)ENGINE=InnoDB;
-- 道具类型配置
create table s_prop_types(
  `id` int(10) not null AUTO_INCREMENT,
  `name` VARCHAR(100) not null,
  `created_at` int(10) not null,
  `appid` char(20) not null,
  PRIMARY KEY (`id`)
)ENGINE=InnoDB;

-- 道具获取路径配置
create table s_prop_way(
  `id` int(10) not null AUTO_INCREMENT,
  `name` VARCHAR(100) not null,
  `created_at` int(10) not null,
  `appid` char(20) not null,
  PRIMARY KEY (`id`)
)ENGINE=InnoDB;