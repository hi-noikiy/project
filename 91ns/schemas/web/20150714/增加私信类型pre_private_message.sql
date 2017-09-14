ALTER TABLE `pre_private_message`
ADD COLUMN `type`  tinyint(1) NOT NULL DEFAULT 0 AFTER `toUid`;
