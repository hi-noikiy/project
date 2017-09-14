ALTER TABLE `pre_users`
ADD INDEX (`internalType`) ;



ALTER TABLE `pre_task_log`
ADD INDEX (`taskId`) ,
ADD INDEX (`status`) ;

ALTER TABLE `pre_sign_anchor`
ADD INDEX (`familyId`) ;

ALTER TABLE `pre_user_profiles`
ADD INDEX `uid` (`uid`) ,
ADD INDEX `level1` (`level1`) ,
ADD INDEX `level2` (`level2`) ,
ADD INDEX `level3` (`level3`) ,
ADD INDEX `level4` (`level4`) ,
ADD INDEX `level5` (`level5`) ,
ADD INDEX `level6` (`level6`) ;


ALTER TABLE `pre_room_log`
ADD INDEX (`roomId`) ,
ADD INDEX (`status`) ;

ALTER TABLE `pre_consume_detail_log`
ADD INDEX (`uid`) ,
ADD INDEX (`receiveUid`) ,
ADD INDEX (`familyId`) ,
ADD INDEX (`type`) ;

