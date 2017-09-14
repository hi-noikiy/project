<?php

/**
 * Created by PhpStorm.
 * User: pan
 * Date: 15/9/15
 * Time: 下午2:16
 */
class NoxConstant
{

    const AES_IV = "BFA3D8F7AB2C3A65";
    const STANDARD_KEY = "cdc1e530296b3b0d70050ba9";

    const QUERY_PAY_RESULT_URL = "http://sdk.bignox.com/ws/payapi/queryresult";
    const QUERY_PASSPORT_RESULT_URL = "https://passport.bignox.com/sso/o2/validation";

    const PARAM_TRANS_DATA = "transdata";
    const PARAM_SIGN = "sign";
    const PARAM_APP_ID = "appid";

    const SUCCESS = '0';
    const FAILED = '-1';

    const MSG_SUCCESS = "SUCCESS";
    const MSG_FAILURE = "FAILURE";

    const MSG_PASSPORT_VALID = "1";
    const MSG_PASSPORT_INVALID = "0";
}
