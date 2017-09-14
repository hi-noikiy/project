ALTER TABLE `pre_recommend`
ADD COLUMN `utmSource`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '广告来源' AFTER `status`,
ADD COLUMN `utmMedium`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '广告媒介' AFTER `utmSource`,
ADD COLUMN `longUrl`  varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '长链接' AFTER `utmMedium`,
ADD COLUMN `tinyUrl`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '短地址' AFTER `longUrl`,
ADD COLUMN `imgPath`  varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '二维码地址' AFTER `tinyUrl`,
ADD COLUMN `type`  tinyint(3) NULL DEFAULT 1 COMMENT '类型1-新用户推广2-广告他推广' AFTER `imgPath`;
