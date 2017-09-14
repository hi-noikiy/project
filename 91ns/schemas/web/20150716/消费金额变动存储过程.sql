DELIMITER $$
CREATE PROCEDURE `consume`(IN `richerId` int,IN `richerCash` decimal,IN `richerExp` int,IN `anchorId` int,IN `anchorCash` decimal,IN `acnhorExp` int,IN `isFamily` tinyint,IN `isCoin` tinyint,OUT `resCode` tinyint)
BEGIN
	#预定义变量
	DECLARE _err_flag, _err_var INT DEFAULT 0;
	DECLARE old_coin BIGINT(32) DEFAULT 0;
	DECLARE old_money, old_cash DECIMAL(32,3) DEFAULT 0.000;
	#设置异常处理方式
	DECLARE CONTINUE HANDLER FOR SQLEXCEPTION, SQLWARNING, NOT FOUND SET _err_flag = 1;
	#富豪
	IF isCoin = 1 THEN
		#查询剩余聊豆是否足够
		SELECT coin INTO old_coin FROM pre_user_profiles WHERE uid = richerId AND coin >= richerCash;
		IF _err_flag = 1 THEN	#聊豆不足或空数据
			SET _err_var = 1;
		ELSE
			IF old_coin >= richerCash THEN	#coin足够
				UPDATE pre_user_profiles SET coin = coin - richerCash WHERE uid = richerId;
				IF _err_flag = 1 THEN
					SET _err_var = 1;
				END IF;
			END IF;
		END IF;
	ELSE
		#查询剩余聊币是否足够
		SELECT cash INTO old_cash FROM pre_user_profiles WHERE uid = richerId AND cash >= richerCash;
		IF _err_flag = 1 THEN	#聊币不足或空数据
			SET _err_var = 1;
		ELSE
			IF old_cash >= richerCash THEN	#coin足够
				UPDATE pre_user_profiles SET cash = cash - richerCash WHERE uid = richerId;
				IF _err_flag = 1 THEN
					SET _err_var = 1;
				ELSE
					IF anchorCash > 0 THEN
						IF isFamily = 1 THEN
							UPDATE pre_sign_anchor SET money = money + anchorCash WHERE uid = anchorId;
							IF _err_flag = 1 THEN
								SET _err_var = 1;
								UPDATE pre_user_profiles SET cash = cash + richerCash WHERE uid = richerId;
							END IF;
						ELSE
							UPDATE pre_user_profiles SET money = money + anchorCash WHERE uid = anchorId;
							IF _err_flag = 1 THEN
								SET _err_var = 1;
								UPDATE pre_user_profiles SET cash = cash + richerCash WHERE uid = richerId;
							END IF;
						END IF;
					END IF;
				END IF;
			END IF;
		END IF;
	END IF;
	SELECT _err_var INTO resCode;
	SELECT resCode;
	
END $$