ALTER TABLE `pre_user_info`
ADD COLUMN `realName`  varchar(255) NULL AFTER `cardNumber`,
ADD COLUMN `ID`  varchar(100) NULL AFTER `realName`;

