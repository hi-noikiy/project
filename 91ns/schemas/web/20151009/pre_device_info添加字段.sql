ALTER TABLE `pre_device_info`
ADD COLUMN `pushTime`  int(11) NULL DEFAULT 0 COMMENT '推送时间' AFTER `lasttime`;

ALTER TABLE `pre_device_info`
ADD COLUMN `pushUid`  int(11) NULL DEFAULT 0 COMMENT 'uid(用户登出不清空)' AFTER `pushTime`;

update pre_device_info set pushUid = uid;