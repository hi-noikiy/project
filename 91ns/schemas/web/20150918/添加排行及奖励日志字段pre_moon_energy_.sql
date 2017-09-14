ALTER TABLE `pre_moon_energy`
ADD COLUMN `rank`  tinyint(1) NULL AFTER `leftNum`,
ADD COLUMN `reward`  text NULL AFTER `rank`;
