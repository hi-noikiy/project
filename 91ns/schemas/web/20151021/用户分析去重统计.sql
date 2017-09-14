ALTER TABLE `pre_room_user_count_hour`
ADD COLUMN `type`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '类型：1不去重 2：去重的' AFTER `platform`;

