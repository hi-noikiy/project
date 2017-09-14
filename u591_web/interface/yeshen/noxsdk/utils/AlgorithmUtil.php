<?php

/**
 *
 * 传入appKey作为加解密时需要的关键字段
 * User: panwanghai
 *
 * Date: 15/9/15
 * Time: 下午12:47
 */
class AlgorithmUtil
{
    private $appKey;

    /**
     * @param $appKey
     */
    public function __construct($appKey)
    {
        $this->appKey = $appKey;
    }

    /**
     * 算法,另外还有192和256两种长度
     */
    const CIPHER = MCRYPT_RIJNDAEL_128;
    /**
     * 模式
     */
    const MODE = MCRYPT_MODE_CBC;

    /**
     * 加密
     * @param string $str 需加密的字符串
     * @return string
     */
    public function encrypt($str)
    {
        $strInput = base64_encode($str);
        $key = $this->getKey();
        $result = mcrypt_encrypt(self::CIPHER, $key, $strInput, self::MODE, NoxConstant::AES_IV);
        return base64_encode($result);
    }

    /**
     * 解密
     * @param string $str
     * @return string
     */
    public function decrypt($str)
    {
        $strInput = base64_decode($str);
        $key = $this->getKey();
        $result = mcrypt_decrypt(self::CIPHER, $key, $strInput, self::MODE, NoxConstant::AES_IV);
        return base64_decode($result);
    }

    private function getKey()
    {
        $strKey = $this->appKey . NoxConstant::STANDARD_KEY;
        return substr(md5($strKey), 8, 16);
    }
}