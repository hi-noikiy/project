<?php

/**
 * Created by PhpStorm.
 * User: pan
 * Date: 15/9/15
 * Time: 下午12:40
 */

require_once dirname(__FILE__) . '/KSBaseEntity.php';

class KSBaseResponseEntity extends KSBaseEntity
{
    protected $errNum;// 错误码
    protected $errMsg;// 错误信息
    protected $sign;// 签名
    protected $transdata;

    /**
     * @return mixed
     */
    public function getErrNum()
    {
        return $this->errNum;
    }

    /**
     * @param mixed $errNum
     */
    public function setErrNum($errNum)
    {
        $this->errNum = $errNum;
    }

    /**
     * @return mixed
     */
    public function getErrMsg()
    {
        return $this->errMsg;
    }

    /**
     * @param mixed $errMsg
     */
    public function setErrMsg($errMsg)
    {
        $this->errMsg = $errMsg;
    }

    /**
     * @return mixed
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * @param mixed $sign
     */
    public function setSign($sign)
    {
        $this->sign = $sign;
    }

    /**
     * @return mixed
     */
    public function getTransdata()
    {
        return $this->transdata;
    }

    /**
     * @param mixed $transdata
     */
    public function setTransdata($transdata)
    {
        $this->transdata = $transdata;
    }// 业务数据


    public function toString()
    {
        return "KSBaseResponseEntity [errNum=" . $this->errNum . ", errMsg=" . $this->errMsg . ", sign=" . $this->sign . ", transdata=" . $this->transdata . "]";
    }
}

