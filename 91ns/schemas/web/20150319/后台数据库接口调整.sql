
-- �������ñ�
-- �����ֶΣ�roomLimitNum���������������ֶΣ�Ԥ����
ALTER TABLE `pre_anchor_configs` ADD COLUMN `roomLimitNum`  int(11) NULL DEFAULT 0 COMMENT '������������';

-- �������ñ�
-- ɾ������ʱ����ֶ�
ALTER TABLE `pre_gift_configs` DROP COLUMN `flashLimit`;
-- �������vip�ȼ��͸����ȼ�Ĭ��ֵΪ0
ALTER TABLE `pre_gift_configs` MODIFY COLUMN `vipLevel`  tinyint(3) NULL DEFAULT 0;
ALTER TABLE `pre_gift_configs` MODIFY COLUMN `richerLevel`  tinyint(3) NULL DEFAULT 0;

-- �������ñ�
-- ɾ������ʱ����ֶ�
ALTER TABLE `pre_car_configs` DROP COLUMN `flashLimit`;
-- -- �������vip�ȼ�Ĭ��ֵΪ0
ALTER TABLE `pre_car_configs` MODIFY COLUMN `vipLevel`  tinyint(3) NULL DEFAULT 0;

-- �������ñ�
-- ���ݽ�����Ķ���Ч��
ALTER TABLE `pre_type_config` ADD COLUMN `roomAnimate`  tinyint(3) NULL DEFAULT 0 COMMENT '��Ҫ���������ϣ�ӵ�����ݽ�����֮���Ƿ��д�����ݹ㲥';







