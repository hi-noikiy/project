ALTER TABLE `inv_banner_config`
ADD COLUMN `border`  int(11) NULL DEFAULT 0 AFTER `time`,
ADD INDEX `border` (`border`) ;

