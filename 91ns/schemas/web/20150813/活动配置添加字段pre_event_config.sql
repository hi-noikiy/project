ALTER TABLE `pre_event_config`
ADD COLUMN `title`  varchar(255) NULL AFTER `id`,
ADD COLUMN `eventstarttime`  int(11) NULL AFTER `description`,
ADD COLUMN `eventendtime`  int(11) NULL AFTER `eventstarttime`,
ADD COLUMN `addtime`  int(11) NULL AFTER `eventendtime`,
ADD INDEX `addtime` (`addtime`) ;