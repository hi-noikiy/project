<?php

/**
 * Created by PhpStorm.
 * User: pan
 * Date: 15/9/15
 * Time: 下午12:45
 */

require_once dirname(__FILE__) . '/../entity/KSPayResponseEntity.php';
require_once dirname(__FILE__) . '/../entity/KSQueryPayEntity.php';
require_once dirname(__FILE__) . '/../entity/KSBaseResponseEntity.php';
require_once dirname(__FILE__) . '/../entity/PassportResponseEntity.php';
require_once dirname(__FILE__) . '/../utils/NoxConstant.php';
require_once dirname(__FILE__) . '/../utils/AlgorithmUtil.php';
require_once dirname(__FILE__) . '/../utils/HttpClientUtil.php';

class NoxSDKServer
{

    private $appId;
    private $appKey;

    public function __construct($appId, $appKey)
    {
        $this->appId = $appId;
        $this->appKey = $appKey;
    }

    /**
     * 解析回调传入的内容
     * @param $transdata
     * @param $sign
     * @return KSPayResponseEntity
     */
    public function getNotifyResult($transdata, $sign)
    {
        $algorithm = new AlgorithmUtil($this->appKey);
        $jsonStr = $algorithm->decrypt($transdata);
        $jsonArray = json_decode($jsonStr, true);
        $payEntity = new KSPayResponseEntity();
        $payEntity->initByArray($jsonArray);
        $curSign = substr(md5($payEntity->toString()),8,16);
        if ($curSign !== $sign) {
            throw new RuntimeException("验证签名失败");
        } else {
            return $payEntity;
        }
    }

    /**
     * 根据orderId查询支付结果
     * @param $orderId
     * @return KSPayResponseEntity
     */
    public function getPayResult($orderId)
    {

        $payEntity = new KSPayResponseEntity();

        $queryPayEntity = new KSQueryPayEntity();
        $queryPayEntity->setAppId($this->appId);
        $queryPayEntity->setOrderId($orderId);

        try {
            $jsonStr = json_encode($queryPayEntity->toMiniArray());
            $algorithm = new AlgorithmUtil($this->appKey);
            $transdata = $algorithm->encrypt($jsonStr);
            $sign = substr(md5($queryPayEntity->toString()),8,16);
            $paramList = array(
                NoxConstant::PARAM_TRANS_DATA => $transdata,
                NoxConstant::PARAM_SIGN => $sign,
                NoxConstant::PARAM_APP_ID => $this->appId,
            );

            $retJsonStr = HttpClientUtil::post(NoxConstant::QUERY_PAY_RESULT_URL, $paramList);
            $retArray = json_decode($retJsonStr, true);
            $resEntity = new KSBaseResponseEntity();
            $resEntity->initByArray($retArray);
            if ($resEntity->getErrNum() != NoxConstant::SUCCESS) {
                $payEntity->setErrNum($resEntity->getErrNum());
                $payEntity->setErrMsg($resEntity->getErrMsg());
                return $payEntity;
            } else {
                $rData = $algorithm->decrypt($resEntity->getTransdata());
                $payEntity->initByArray(json_decode($rData, true));
                $curSign = substr(md5($payEntity->toString()),8,16);
                if ($curSign !== $resEntity->getSign()) {
                    throw new RuntimeException("验证签名失败");
                }
                $payEntity->setErrNum($resEntity->getErrNum());
                $payEntity->setErrMsg($resEntity->getErrMsg());
            }
        } catch (Exception $ex) {

        }
        return $payEntity;
    }

    /**
     * 验证accessToken和uid是否合法
     * @param $accessToken
     * @param $uid
     * @return PassportResponseEntity
     */
    public function validate($accessToken,$uid){
        $resultEntity=new PassportResponseEntity();
        try{
            $paramsJoin = "accessToken=" . $accessToken . "&uid=" . $uid . "&appId=" . $this->appId;
            $resultJson=HttpClientUtil::get(NoxConstant::QUERY_PASSPORT_RESULT_URL,$paramsJoin);
            if($resultJson){
                $resultEntity->initByArray(json_decode($resultJson, true));
            }
        }catch (Exception $ex) {
        }
        return $resultEntity;
    }
}
