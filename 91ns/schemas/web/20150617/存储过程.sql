DELIMITER $$
CREATE PROCEDURE `money`(IN `anchorId` int,IN `price` decimal,IN `isCoin` int,IN `consumeType` int,IN `isFamily` int,OUT `res_code` tinyint)
BEGIN

	#预定义变量
	DECLARE _err_flag, _err_var, old_coin INT DEFAULT 0;
	DECLARE old_money, old_cash FLOAT DEFAULT 0.000;
	#设置异常处理方式
	DECLARE CONTINUE HANDLER FOR SQLEXCEPTION, SQLWARNING, not found SET _err_flag = 1;

	IF isCoin = 1 THEN

		#查询剩余聊豆是否足够
		SELECT coin INTO old_coin FROM pre_user_profiles WHERE uid = anchorId AND coin >= price;

		IF _err_flag = 1 THEN	#聊豆不足或空数据
			SET _err_var = 1;
		ELSE
			IF old_coin >= price THEN	#coin足够
				UPDATE pre_user_profiles SET coin = coin - price WHERE uid = anchorId;
				IF _err_flag = 1 THEN
					SET _err_var = 1;
				END IF;
			END IF;
		END IF;

	ELSE

		#扣除聊币
		IF consumeType = 0 THEN
			#查询剩余聊币是否足够
			SELECT cash INTO old_cash FROM pre_user_profiles WHERE uid = anchorId AND cash >= price;

			IF _err_flag = 1 THEN	#聊币不足或空数据
				SET _err_var = 1;
			ELSE
				IF old_cash >= price THEN	#cash金额足够
					UPDATE pre_user_profiles SET cash = cash - price WHERE uid = anchorId;
					IF _err_flag = 1 THEN
						SET _err_var = 1;
					END IF;
				END IF;
					
			END IF;
		#增加聊币
		ELSE
			#判断是否为家族
			IF isFamily = 1 THEN
				UPDATE pre_sign_anchor SET money = money + price WHERE uid = anchorId;
				IF _err_flag = 1 THEN
					SET _err_var = 1;
				END IF;
			ELSE
				UPDATE pre_user_profiles SET money = money + price WHERE uid = anchorId;
				IF _err_flag = 1 THEN
					SET _err_var = 1;
				END IF;
			END IF;

		END IF;

	END IF;
	
	
	SELECT _err_var INTO res_code;
	SELECT res_code;

END $$
