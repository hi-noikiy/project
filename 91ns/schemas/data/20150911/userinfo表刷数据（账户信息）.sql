UPDATE pre_user_info ui 
INNER JOIN pre_sign_anchor sa
ON ui.uid = sa.uid
SET ui.realName = sa.realName, ui.cardNumber = sa.cardNumber, ui.bank = sa.bank, ui.ID = sa.idCard;