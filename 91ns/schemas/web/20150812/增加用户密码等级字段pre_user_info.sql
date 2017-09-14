ALTER TABLE `pre_user_info`
ADD COLUMN `seclevel`  tinyint(1) NULL DEFAULT 0 AFTER `birthday`;
ALTER TABLE `pre_user_info`
ADD COLUMN `bank`  varchar(255) NULL AFTER `seclevel`,
ADD COLUMN `cardNumber`  varchar(255) NULL AFTER `bank`;
