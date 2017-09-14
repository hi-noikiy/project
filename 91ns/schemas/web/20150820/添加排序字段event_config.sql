ALTER TABLE `pre_event_config`
ADD COLUMN `eorder`  int(11) NULL DEFAULT 0 AFTER `eventstarttime`,
ADD INDEX (`eorder`) ;
