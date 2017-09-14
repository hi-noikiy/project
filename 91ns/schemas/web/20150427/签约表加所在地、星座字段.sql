ALTER TABLE `pre_sign_anchor`
ADD COLUMN `location`  varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0591' COMMENT 'ËùÔÚµØ' AFTER `address`,
ADD COLUMN `constellation`  smallint(4) NULL DEFAULT 0 COMMENT 'ÐÇ×ù' AFTER `location`;
