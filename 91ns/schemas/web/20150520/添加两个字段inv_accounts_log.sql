ALTER TABLE `inv_accounts_log`
ADD COLUMN `settleType`  tinyint(2) DEFAULT 0 COMMENT '结算方式1收益，2时长，3收益+时长';
ALTER TABLE `inv_accounts_log`
ADD COLUMN `remark`  varchar(2000) DEFAULT null COMMENT '备注';

 