<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/2/23
 * Time: 下午8:11
 * 爱贝云计费
 */
define('ROOT_PATH', str_replace('interface/iapppay/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH.'inc/function.php';
$key_arr = array(
    8=>array(
        'ios'=>array(
            'appId'     =>'3010421318',
            'appKey'    =>'MIICXQIBAAKBgQDI9QvPV6ov3y9XckMEYucxvdBPcslBDhpwQJM+HUeISqE45mLTQ7J5TQD673VWyqObozBqM9aE7DaenmGriMzd6uacbtC7Iy+G6Dc4H7rY3AjGFaRWofpVCtlrPmmGGrmePNLfw6MrORWoNirg1M4I/uIAMz7mvCINdbnd4SZfVQIDAQABAoGAJCeqU0iTbv95lgMRuaVNsS5UXybovJKcARwtIZzE9OlwbjtNBnOElbxb88nHU7ErlGHc4Q6ohsu02/7k1abMLML66APdahD2jLGZ0w4F42JyVsDqoQhT8b+dIwk8J7nCyNRSxhVxw1blgvnfxZFW8nYWbgG6yA4whUqhUvHsSgECQQD7qPmg/0OSmacMkyWiLj0qz09JdX+RQwZmseZhEsbjKj+Fzj40e9hNEIZDewJMlYgNJo30/9AzDjc32GsF8AnVAkEAzGw6mAeS04IwiNgDeCqBwvJxBEx/w68p6j3TBJE9Dv3vB1vyIDCIxhfk4w9pcJDUje1LM9Rb7Qvbu0BFmq4/gQJBAIpjfnqZRMhuNF2G4XAQ61QKUnh745229OMuOxjwoWw0RGBJbQ2heO3QA3VCRJ5msD1DEVdEAXLCA31zRk8qk80CQQCGj+eyE6ou2FAihC8kdracIQMMFV807KTmsHOrWf+bUsR5T6j+T3R5EVl1rbt2gZ+pHT6Xi35Hd7rYc+Jdg74BAkA1bnPBPNKJyiBWldFc1OyaXg4QaivugHK6Cl1S+hpSpPgf+ZlsJkvKoHskwXZCfeaY1CQVRJe7ONUc6Ev3JboT',
            'platpKey'  =>'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC+clmCRe0SnAvF0WEMyj08WF8h9g8O+Efd+AFl0C2JtRyxRevoG+oltKxvHyTjyCRJMUWZCSPXgq/roy6qdVfiPo9YeQQpwoTEI1YLI4rrOLLRI2iQ0carzTuBlSCuzFMXRxC/r7qTP7PVoGUVA1tXGCaFz+WgwpfXLyNmlATFGwIDAQAB',
        ),
        'ios1'=>array(
            'appId'     =>'3010421318',
            'appKey'    =>'MIICXQIBAAKBgQDI9QvPV6ov3y9XckMEYucxvdBPcslBDhpwQJM+HUeISqE45mLTQ7J5TQD673VWyqObozBqM9aE7DaenmGriMzd6uacbtC7Iy+G6Dc4H7rY3AjGFaRWofpVCtlrPmmGGrmePNLfw6MrORWoNirg1M4I/uIAMz7mvCINdbnd4SZfVQIDAQABAoGAJCeqU0iTbv95lgMRuaVNsS5UXybovJKcARwtIZzE9OlwbjtNBnOElbxb88nHU7ErlGHc4Q6ohsu02/7k1abMLML66APdahD2jLGZ0w4F42JyVsDqoQhT8b+dIwk8J7nCyNRSxhVxw1blgvnfxZFW8nYWbgG6yA4whUqhUvHsSgECQQD7qPmg/0OSmacMkyWiLj0qz09JdX+RQwZmseZhEsbjKj+Fzj40e9hNEIZDewJMlYgNJo30/9AzDjc32GsF8AnVAkEAzGw6mAeS04IwiNgDeCqBwvJxBEx/w68p6j3TBJE9Dv3vB1vyIDCIxhfk4w9pcJDUje1LM9Rb7Qvbu0BFmq4/gQJBAIpjfnqZRMhuNF2G4XAQ61QKUnh745229OMuOxjwoWw0RGBJbQ2heO3QA3VCRJ5msD1DEVdEAXLCA31zRk8qk80CQQCGj+eyE6ou2FAihC8kdracIQMMFV807KTmsHOrWf+bUsR5T6j+T3R5EVl1rbt2gZ+pHT6Xi35Hd7rYc+Jdg74BAkA1bnPBPNKJyiBWldFc1OyaXg4QaivugHK6Cl1S+hpSpPgf+ZlsJkvKoHskwXZCfeaY1CQVRJe7ONUc6Ev3JboT',
            'platpKey'  =>'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC+clmCRe0SnAvF0WEMyj08WF8h9g8O+Efd+AFl0C2JtRyxRevoG+oltKxvHyTjyCRJMUWZCSPXgq/roy6qdVfiPo9YeQQpwoTEI1YLI4rrOLLRI2iQ0carzTuBlSCuzFMXRxC/r7qTP7PVoGUVA1tXGCaFz+WgwpfXLyNmlATFGwIDAQAB',
        ),
        'ios2'=>array(
            'appId'     =>'3010422288',
            'appKey'    =>'MIICXgIBAAKBgQCCzPXJsvMJCeqDWNZ1nzz3/n0w274+CPVdITarjXEJga/tWxQNWBIBg+u/VGpwuDZDHsuGok4xohBOqEfH+6HB4aRrY4F5raa4f8NQyWILuyAvSlJdb0raySti6FwKHSRw+QJhKPd1QkaUcarPFcLHRJ3RPxAeApW2FBou1n2yZQIDAQABAoGABETiRMoFaqcbM1xD66Td3Y490clqRSYPtBFwclr0dIX6EvolBmZ4d/oxSJZdvv4UL06i/+ruOGp8s8SESz+oQNq95uhJwEFmHoW3R7PpU9+hQDEMZP+6FDRowsezZ54ChMyBPFkFMAUvZPV8yRJSb7Igq5EaKQwex8Re5cna4iECQQDEa3Ar9zUzYedmT07/un4Mo55ULG7AOgcSpfkyTv64sNMJ4U54KZzf+9CCrmUebCfasqgm0gP/+C07LbNuB3OpAkEAqnoCu/MLQGril73M/CgeJJkSa/cC5UW8E8HzDQmnxGufAieNsXVGkQnCwDiMEA1BZVOt+BSsd69hdDEKdS/+XQJBALw17uBSGupHcAH7EMm+m7XkunwRr3YGpGxcGbL5Ot8ioLYg7J370dRWaPAvx7klDfNjqfi42RhXaJACNKLEBTECQQCbukcjH9sIZltFmElXkuIuNnKAk61eeTxcpBB9uXM618DyO+WQ1Pu4ZD02ULGJvEIf6LgD5gbCckBI3r+Z21S1AkEAkMCGEGPJnXl6cqf2IzGqg7UAgk9blcHrggb+dKYiya2Xk8gJY412FhXjbTUfe8y3mMk4rvGBg75gqe6dMdlM2A==',
            'platpKey'  =>'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCKuDGPSy5XMv0PqRTfCqUObI6gBDt0xv57XRe4nhLvNuu+xeFPZ717a3ApQwBBdbQ+pTZLkiMoh4+UHh+ZHkW8onRsGt61pR0W9RspOONLr2HYRsFbveiE392+tMaG5QCOeZMhvgk3SGvLy+jKrXRQS8RRvtMqrCIMX2zWKjo4wwIDAQAB',
        ),
        'ios3'=>array(
            'appId'     =>'3010423930',
            'appKey'    =>'MIICWwIBAAKBgQDSFnrK9fAqXiTx/eos2tkcYeLnh07S/wMgI3L3XkwmUaXOnzqhgtd4Iz1IeSm9Mog+7sirkrNxDGonhrmmHJ7Ae6jjEBo6wxFrx1VvRlewUKSxrr/dSSoHNcsnLXsVyh521wdldMdLBw4GFahrNI/4cWRhfpe9Q0VcGtlW64swCQIDAQABAoGAG4lSwvOFbSg103oijYqJ1oyF/y7fCAzgzf+XfBDiDku5XeHE/5J0x4xm5e7rp57N+OG3b7Wky3aDG3cvMjSiIf+cnxAEIoCLvr9K7AJlWmLXTsjg6NZdM23ZZFd/ajbeZSxx6VGbIGibSApAsXvlTrjsTxlP7F4v0E1wuqfZ8gECQQD54g8VjR/3Ti3jh/Wkp0Vydpa4ITjOE+L5qyPrg82zQo1Wn3ZAT38MU5wotJWuTuJvJEA07Eewogc4T/BFJzBhAkEA1zsJPvDtHm0igCd/jYCXci+5zY+/+uBxv6WjvsjMhXdbzCFt/1jr4CdpGuKtCR8X2YKWil6UU0X6EgKxURRAqQJAGCtwcBI1bFNIhwCIlwOC6R5lV6wG9Y/g3WtbTxVxmQwv5ZgI9RF7ZXLU4O8Op0yEBGIKP9Q23vPdJOo+gEHZIQJAKlXfdLT5P0HiRmj+ivvfTkOa/ZcoARYC6dqQAegHar1Wnil5NH/uLbIy12n89U1baJpqOB9wd4zNKpvC+xrLuQJAXWQXA8aRCLGqvzSkHIOVJCRgNWfNvHJ6K7Gd0nA2zg0EEOfqFa+ww+4jdMaAcf1RLPGgr7w8FL0PCHIcCjT9PQ==',
            'platpKey'  =>'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCZ49O48hguV9UkFIQS4cfY4gpz+lfuw+aEyiKIWPl3kjTg0UF/GNlGPPHK32carD/22VhkoK771tsrW3fkepkwA7wO8j0cExP7PmeLpuwlLQzUBqIRPuNJYvxsWtA2ZMuEHBUn3Pzn3vSTOSTxH/j4BNyOnJnOQVI6ins9gVTd7wIDAQAB',
        ),
        'ios4'=>array(
            'appId'     =>'3010419930',
            'appKey'    =>'MIICXQIBAAKBgQCFT+EcKTRN2L0rZDH4ivbLFJa0Vmynhoyyrqe6ctRT8/qtS9CHcDjlJ5uK73jvcOYw+YnTyH5R8fq715MLk/VmWjWP7K5b9kCyXsdbtkyvKpDtbez0RUPY4SeTdJwcPQ+cs7lfHSygexItZmQOa5UZI7l0W6e/xrJEwXy8a3mQbwIDAQABAoGAVUFYfWH8iSxrDiztqD12xzlLh9kdc6WgpUT9D0gQcgA9+EXb/kHOoP5LgvbTKI3TKM7tTfuSjWVrLGYZK/ZboUOO6wOQLGPww/iloEfHi8VUxg0OSo5Hqg1nGNpRmMBSXLWNlEcZG0Jv73ewSihKtFnif39BimYoF9Sort96vUECQQDO9wtcjqfFDRCaZOSPk2LHPkzvyBBqbAlLUpQMcZLi5DDM5858AhseunAZQlmuRLCNhEDZCX6/miXSsbO/HQp9AkEApOWX8mtYmGuhg6Ezlno95PTV8GlA6dSueSREJnpDqPf8S86fNh7T9jJlQAmayf02kg+2v722Zs3cAjBn3CQOWwJAcl9xpueq7SloKHpjGLjxWHwkVkowUC99/2MWL74/1yUVltbvc/ZR+Gw9cKgLGVQaYUpZ79bgdZABIOtVEuNAnQJBAIFEO0mfPWS/e25lALwGOF80UEKXQHwObngpJgPD9SaQwQfsgoM6x49JEnaREPcnuY/nIaxOXJ9a4g4VRFYfLeECQQCDpbqB9zxnEAO38OKMeNgMoI1/tK9/iPtSpOw/w9u8cAlhUuh803V0TjolwpIom9OBUUuposYVLUJrUF/hFCfp',
            'platpKey'  =>'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCZksEXUAUE05mCUqolc6RhMAS6IwhVP8iGomFlwYbqvVqYnrlhtqrGZNYuO2qkM3HycrM+dEJZ9aBN/k+AZLa+CCpM1F3eyxx0hcyNBtm6lOtmMs6I0DkQUbQFKK/b6KiBbZs7GpeHN8NmZpTWgbqhdy/hun4uPBSjhJcRaC7n8wIDAQAB',
        ),
        'ios5'=>array(
            'appId'     =>'3010420660',
            'appKey'    =>'MIICXgIBAAKBgQC0x0YV7mel6J/b2Ha/zCIGXQCdMZ7JS8PEbB9D4UnbWVznMMPNE4w1J/sz/Q/jigt4nISR1matAJw1fvWJL2HcWaaAaNgzXYFyjngkueqYzkaD6D1I8go5XRpHWcuNqZfImY/HjtqxLYR0wv+ZfzoJmX4qHWfcV0QCaW/tZaq3wwIDAQABAoGBAI5exd5txCkQPB1qq5K/VCiIw/wIYRIM3h9qO3E9EupxxU25xOpUM76FPzuhWAsN45zYPzuPi4tCCMPEueCRdVI80UTD9nqUzpynfNLd9DiTy6nxv8HPZhu86tWuZs/f6biCh+qG7p+V0OoZujQfno8OuBmdLVX1VIUQYxGOAIURAkEA7KXgWmf7Cl5NvqAdzyHl5d5ZkSjeLGs06ylr8Dj0IUka/+0hTfP7mNRE7b/7JwVrSYA8huDSwNY9phzg0jKkiQJBAMOPyt6xORPHA7+DYMiI6SZ0hugKu366eaZH4dHMqPbfW+SZOaPxCdQ7r985/BbZbPWM97R7KuK8Yeu2hF6QvusCQQDql+W4uaubs7DOcFwcojYNkkaoKCNXVU+4b++YzFz3QkmJu8FwTZX1Azjxl4eaPz0EOCPIccd7cubabXZR6fJBAkEAtQ20cdY8FL5lDvLQMPjoemzZ1YzSJ7L3G2ZjgHxtVhpWll7xyYFIa1BTNGUXqgPkZ7X6QwLhIofsVHFnPbHevwJAZa2ay69sdGG2PLLtxm6sqKA0e7xVIHpbtFGR05/egQBGveSx6W6dcrNEmDQ/VsnIt4GfIflXwAG7D81lI2S+Mw==',
            'platpKey'  =>'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCaoUtmAfNHELYboybmLRi0xEEYa2mYZprEjAbImLticvJwcyze031/j3TUqt/1AvUHQTbvELstOp2ZkV1YueaVRGofZxKjEMrqjwsqzLNBdUWRHAkEKzLCi6LWIyTrpOczYOJKpyMmULPZ+5csI1zsI6TvIqexgRrxqLX98OXsKwIDAQAB',
        ),
    ),
);

/**
 * 组装request报文
 * $reqJson 需要组装的json报文
 * $vkey  cp私钥，格式化之前的私钥
 * return 返回组装后的报文
 */
function composeReq($reqJson, $vkey) {
    //获取待签名字符串
    $content = json_encode($reqJson);
    //格式化key，建议将格式化后的key保存，直接调用
    $vkey = formatPriKey($vkey);

    //生成签名
    $sign = sign($content, $vkey);

    //组装请求报文，目前签名方式只支持RSA这一种
    $reqData = "transdata=".urlencode($content)."&sign=".urlencode($sign)."&signtype=RSA";

    return $reqData;
}

/**格式化公钥
 * $priKey PKCS#1格式的私钥串
 * return pem格式私钥， 可以保存为.pem文件
 */
function formatPriKey($priKey) {
    $fKey = "-----BEGIN RSA PRIVATE KEY-----\n";
    $len = strlen($priKey);
    for($i = 0; $i < $len; ) {
        $fKey = $fKey . substr($priKey, $i, 64) . "\n";
        $i += 64;
    }
    $fKey .= "-----END RSA PRIVATE KEY-----";
    return $fKey;
}

/**格式化公钥
 * $pubKey PKCS#1格式的公钥串
 * return pem格式公钥， 可以保存为.pem文件
 */
function formatPubKey($pubKey) {
    $fKey = "-----BEGIN PUBLIC KEY-----\n";
    $len = strlen($pubKey);
    for($i = 0; $i < $len; ) {
        $fKey = $fKey . substr($pubKey, $i, 64) . "\n";
        $i += 64;
    }
    $fKey .= "-----END PUBLIC KEY-----";
    return $fKey;
}

/**RSA验签
 * $data待签名数据
 * $sign需要验签的签名
 * $pubKey爱贝公钥
 * 验签用爱贝公钥，摘要算法为MD5
 * return 验签是否通过 bool值
 */
function verify($data, $sign, $pubKey)  {
    //转换为openssl格式密钥
    $res = openssl_get_publickey($pubKey);

    //调用openssl内置方法验签，返回bool值
    $result = (bool)openssl_verify($data, base64_decode($sign), $res, OPENSSL_ALGO_MD5);

    //释放资源
    openssl_free_key($res);

    //返回资源是否成功
    return $result;
}

/**
 * 解析response报文
 * $content  收到的response报文
 * $pkey     爱贝平台公钥，用于验签
 * $respJson 返回解析后的json报文
 * return    解析成功TRUE，失败FALSE
 */
function parseResp($content, $pkey, &$respJson) {
    $arr=array_map(create_function('$v', 'return explode("=", $v);'), explode('&', $content));
    foreach($arr as $value) {
        $resp[($value[0])] = $value[1];
    }

    //解析transdata
    if(array_key_exists("transdata", $resp)) {
        $respJson = json_decode($resp["transdata"]);
    } else {
        return FALSE;
    }

    //验证签名，失败应答报文没有sign，跳过验签
    if(array_key_exists("sign", $resp)) {
        //校验签名
        $pkey = formatPubKey($pkey);
        return verify($resp["transdata"], $resp["sign"], $pkey);
    } else if(array_key_exists("errmsg", $respJson)) {
        return FALSE;
    }

    return TRUE;
}

