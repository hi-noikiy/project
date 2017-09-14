ALTER TABLE `pre_user_images`
ADD COLUMN `orderType`  int(11) NULL DEFAULT 0 COMMENT '排序' AFTER `status`;

