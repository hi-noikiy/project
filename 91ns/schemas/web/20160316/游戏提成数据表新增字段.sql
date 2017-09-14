ALTER TABLE `pre_game_deduct_detail_log`
ADD COLUMN `remark`  varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '骰宝游戏主播提成' COMMENT '说明' AFTER `createTime`;