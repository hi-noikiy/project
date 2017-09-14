ALTER TABLE `pre_user_profiles`
ADD COLUMN `isOpenSign`  tinyint(1) NULL DEFAULT 0 AFTER `usefulMoney`,
ADD INDEX (`isOpenSign`) ;

