<?php

namespace Micro\Frameworks\Logic\Validation;

use Phalcon\DI\FactoryDefault;
use Respect\Validation\Validator as respectValidator;

class Validator {
    
    protected $validArray;
    protected $errCode;

    protected $normalCacheKey;

    public function __construct() {
        $this->errCode = "";
        $this->normalCacheKey = 'valid_cache_data_key';
    }

    private function getValidArray() {
        $validatorsCache = FactoryDefault::getDefault()->get('validatorsCache');        

        $validArray = $validatorsCache->get($this->normalCacheKey);
        //if($validArray == null){  //测试阶段，先将缓存关闭
            $validArray = array (
                //email ??
                "id" => array(
                    array(
                        respectValidator::numeric()->notEmpty(),
                    ),
                    "ID_ERROR"
                ),

                "uid" => array(
                    array(
                        respectValidator::numeric()->notEmpty(),
                    ),
                    "UID_ERROR"
                ),

                "accountid" => array(
                    array(
                        respectValidator::string()->notEmpty(),
                    ),
                    "ACCOUNTID_ERROR"
                ),

                "birthday" => array(
                    array(
                        respectValidator::numeric(),
                    ),
                    "BIRTHDAY_ERROR"
                ),

                //2-10个字，数字、字母、中文均可,【【不可纯数字】还没解决】
                "nickname" => array(
                    array(
                        respectValidator::string(),
                        respectValidator::length(2,10),
                    ),
                    "NICKNAME_ERROR"
                ),

                "deviceid" => array(
                    array(
                        respectValidator::string()->notEmpty(),
                    ),
                    "DEVICEID_ERROR"
                ),

                "clientID" => array(
                    array(
                        respectValidator::string()->notEmpty(),
                    ),
                    "CLIENTID_ERROR"
                ),

                "platform" => array(
                    array(
                        respectValidator::numeric(),
                    ),
                    "PLATFORM_ERROR"
                ),

                "devicename" => array(
                    array(
                        respectValidator::string()->notEmpty(),
                    ),
                    "DEVICENAME_ERROR"
                ),

                "version" => array(
                    array(
                        respectValidator::string()->notEmpty(),
                    ),
                    "VERSION_ERROR"
                ),

                "username" => array(
                    array(
                        respectValidator::string(),
                        respectValidator::length(4,12),
                    ),
                    "USERNAME_ERROR"
                ),

                "password" => array(
                    array(
                        respectValidator::string(),
                        respectValidator::length(6,32),
                    ),
                    "PASSWORD_ERROR"
                ),

                "oldpassword" => array(
                    array(
                        respectValidator::string(),
                        respectValidator::length(6,32),
                    ),
                    "OLDPWD_ERROR"
                ),

                "newpassword" => array(
                    array(
                        respectValidator::string(),
                        respectValidator::length(6,32),
                    ),
                    "NEWPWD_ERROR"
                ),

                "roomid" => array(
                    array(
                        respectValidator::numeric()->notEmpty(),
                    ),
                    "ROOMID_ERROR"
                ),

                "content" => array(
                    array(
                        respectValidator::string()->notEmpty(),
                    ),
                    "CONTENT_ERROR"
                ),

                "remarks" => array(
                    array(
                        respectValidator::string(),
                        respectValidator::length(0,7),
                    ),
                    "CONTENT_ERROR"
                ),

                "level" => array(
                    array(
                        respectValidator::numeric(),
                    ),
                    "LEVEL_ERROR"
                ),

                "time" => array(
                    array(
                        respectValidator::numeric(),
                    ),
                    "TIME_ERROR"
                ),

                "price" => array(
                    array(
                        respectValidator::numeric(),
                    ),
                    "PRICE_ERROR"
                ),

                "roomtitle" => array(
                    array(
                        respectValidator::string()->length(0,15),
                    ),
                    "ROOMTITLE_ERROR"
                ),

                "sorttype" => array(
                    array(
                        respectValidator::numeric(),
                    ),
                    "SORTTYPE_ERROR"
                ),

                "isforbid" => array(
                    array(
                        respectValidator::numeric(),
                    ),
                    "ISFORBID_ERROR"
                ),

                "seatpos" => array(
                    array(
                        respectValidator::numeric()->between(0, 5, true),
                    ),
                    "SEATPOS_ERROR"
                ),

                "seatcount" => array(
                    array(
                        respectValidator::int()->positive(),
                    ),
                    "SEATCOUNT_ERROR"
                ),

                "giftcount" => array(
                    array(
                        respectValidator::int()->positive(),//正整数
                    ),
                    "GIFTCOUNT_ERROR"
                ),

				"buytype" => array(
                    array(
                        respectValidator::numeric(),
                    ),
                    "BUYTYPE_ERROR"
                ),

                "type" => array(
                    array(
                        respectValidator::numeric(),
                    ),
                    "TYPE_ERROR"
                ),

                "number" => array(
                    array(
                        respectValidator::numeric(),
                    ),
                    "NUMBER_ERROR"
                ),
                "index" => array(
                    array(
                        respectValidator::numeric(),
                    ),
                    "INDEX_ERROR"
                ),
                "name" => array(
                    array(
                        respectValidator::string()->length(0,15),
                    ),
                    "NAME_ERROR"
                ),
                "answer" => array(
                    array(
                        respectValidator::string()->length(0,15),
                    ),
                    "ANSWER_ERROR"
                ),
                "questionid" => array(
                    array(
                        respectValidator::numeric(),
                    ),
                    "QUESTIONID_ERROR"
                ),
                "consumelogid" => array(
                    array(
                        respectValidator::numeric(),
                    ),
                    "CONSUMELOGID_ERROR"
                ),
                "income" => array(
                    array(
                        respectValidator::numeric(),
                    ),
                    "INCOME_ERROR"
                ),
                "companyname" => array(
                    array(
                        respectValidator::string(),
                    ),
                    "COMPANYNAME_ERROR"
                ),
                "longitude" => array(
                    array(
                        respectValidator::float()->notEmpty(),
                    ),
                    "LONGITUDE_ERROR"
                ),
                "latitude" => array(
                    array(
                        respectValidator::float()->notEmpty(),
                    ),
                    "LATITUDE_ERROR"
                ),
                "roompwd" => array(//直播间密码
                    array(
                        respectValidator::string(),
                        respectValidator::length(1,10),
                    ),
                    "PASSWORD_ERROR"
                ),
                "betTimes" => array(//投注期数
                    array(
                        respectValidator::int()->positive(),
                    ),
                    "BET_TIMES_ERROR"
                ),
                "betNum" => array(//投注
                    array(
                        respectValidator::numeric()->between(1, 10000, true),
                    ),
                    "BET_NUM_ERROR"
                ),
                "showName" => array(//节目名称
                    array(
                        respectValidator::string()->length(1,20),
                    ),
                    "SHOWNAME_ERROR"
                ),
            );

            $validatorsCache->save($this->normalCacheKey, $validArray);
        //}
        
        return $validArray;
    }

    public function validate($jsonData) {
        $validArray = $this->getValidArray();

        foreach ($jsonData as $key=>$value) {
            if (array_key_exists($key, $validArray)) {
                $validators = $validArray[$key][0];
                foreach($validators as $validator){
                    if (!$validator->validate($value)) {
                        $this->errCode = $validArray[$key][1];
                        return false;
                    }
                }
            }
        }

        return true;
    }

    public function checkJsonRawBody($rawBody) {
        if (strlen($rawBody) == 0) {
            return false;
        }

        $jsonData = json_decode($rawBody);
        if (!$jsonData) {
            return false;
        }

        return get_object_vars($jsonData);
    }

    public function getLastError() {
        $status = FactoryDefault::getDefault()->get('status');

        $errMsg['code'] = $status->getValidatorCode($this->errCode);
        $errMsg['info'] = $status->getValidatorInfo($this->errCode);
        return $errMsg;
    }

    public function getError($errCode) {
        $status = FactoryDefault::getDefault()->get('status');

        $errMsg['code'] = $status->getValidatorCode($errCode);
        $errMsg['info'] = $status->getValidatorInfo($errCode);
        return $errMsg;
    }

    public function qs($str){
        if(get_magic_quotes_gpc()){
            return $str;
        }else{
            return addslashes($str);
        }
    }

    public function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
        $ckey_length = 4;
        $ucKey = 'PUTIANMMSESSION';
        $key = md5($key ? $key : $ucKey);
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

        $cryptkey = $keya.md5($keya.$keyc);
        $key_length = strlen($cryptkey);

        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);

        $rndkey = array();
        for($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        for($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if($operation == 'DECODE') {
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            return $keyc.str_replace('=', '', base64_encode($result));
        }
    }
    
    //判断是否是手机号 add by 2015/07/20
    public function isTelephone($telephone) {
        if (preg_match("/1[345789]{1}\d{9}$/", $telephone)) {
            return true;
        } else {
            return false;
        }
    }

}