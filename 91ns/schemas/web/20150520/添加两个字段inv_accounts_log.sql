ALTER TABLE `inv_accounts_log`
ADD COLUMN `settleType`  tinyint(2) DEFAULT 0 COMMENT '���㷽ʽ1���棬2ʱ����3����+ʱ��';
ALTER TABLE `inv_accounts_log`
ADD COLUMN `remark`  varchar(2000) DEFAULT null COMMENT '��ע';

 