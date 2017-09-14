<?php
/**
 * 查询passport用户信息时作为参数传入的实体
 * User: pan
 * Date: 15/9/15
 * Time: 下午12:44
 */

require_once dirname(__FILE__) . '/KSBaseEntity.php';

class PassportResponseEntity extends KSBaseEntity
{
    private $errNum="null";// 错误码
    private $errMsg="null";// 错误信息
    private $transdata=array();// 业务数据

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
    }

    public function toString()
    {
        return "PassportResponseEntity [errNum=" . $this->errNum . ", errMsg=" . $this->errMsg . ", transdata=" . $this->transdata . "]";
    }
}