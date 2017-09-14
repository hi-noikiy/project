ALTER TABLE `inv_accounts_family_log` ADD COLUMN `stype`  tinyint(2) DEFAULT '0' COMMENT '结算方式1收益，2时长，3收益+时长';
ALTER TABLE `inv_accounts_family_log` ADD COLUMN `basicSalary`  float(32,3) DEFAULT NULL COMMENT '底薪';
ALTER TABLE `inv_accounts_family_log` ADD COLUMN `rmb`  float(32,3) DEFAULT NULL COMMENT '分成';