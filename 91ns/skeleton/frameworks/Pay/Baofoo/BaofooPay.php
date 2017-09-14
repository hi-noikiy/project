<?php

namespace Micro\Frameworks\Pay\Baofoo;

use Phalcon\DI\FactoryDefault;
use Micro\Frameworks\Pay\OrderMgr as OrderMgr;

//宝付银联支付接口
class BaofooPay {

    protected $request;
    protected $di;
    protected $config;
    protected $orderMgr;
    protected $validator;
    protected $status;
    protected $logger;

    public function __construct() {
        $this->di = FactoryDefault::getDefault();
        $this->request = $this->di->get('request');
        $this->config = $this->di->get('config');
        $this->orderMgr = new OrderMgr();
        $this->status = $this->di->get('status');
        $this->logger = $this->di->get('logger');
    }

    //异步回调
    public function verifyNotify() {
        //写入日志start
        $logger = new \Phalcon\Logger\Adapter\File($this->config->directory->logsDir . '/baofoo.log');
        $logger->error(json_encode($_REQUEST));
        //end
        require "inc.php";
        $result = $baofooFiService->recvcheck();
        $orderId = $result["TransID"];
        if ($result["code"] == "01") {
            //当充值成功后同步商户系统订单状态
            //此处编写商户系统处理订单成功流程
            //写入数据库，修改订单状态
            $tradeno = $result["TransID"];//没有传过来流水号，默认用订单号
            $result = $this->orderMgr->editOrder($orderId, $tradeno);
            if ($result) {//修改订单状态成功
                $this->orderMgr->paySuccessOperation($orderId);
                $logger->error("orderId:" . $orderId . ";" . $result['code']);
                echo "OK";
            }
        } else {
            $logger->error("orderId:" . $orderId . ";" . $result['code'] . ";" . $result['message']);
            echo "验签验证失败-" . $result["message"];
        }
        return;
    }

}
