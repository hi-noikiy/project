<?php
/**
 * 首页
 */
namespace Refresh\Controller;
use Think\Controller;
class IndexController extends Controller {

    public function index()
    {
        if (IS_POST) {
            // print_r(I('request.'));



        }else{
           $this->display(); 
        }
        
    }


    public function success()
    {

        $config['TrustPayConnectMethod'] = 'https';
        $config['TrustPayServerName'] = 'pay.abchina.com';
        $config['TrustPayServerPort'] = '443';
        $config['TrustPayNewLine'] = '2';
        $config['TrustPayTrxURL'] = '/ebus/trustpay/ReceiveMerchantTrxReqServlet';
        $config['TrustPayIETrxURL'] = 'https://pay.abchina.com/ebus/ReceiveMerchantIERequestServlet';
        $config['MerchantErrorURL'] = 'http://127.0.0.1:83/TrustPayClient';
        $config['TrustPayCertFile'] = 'D:\WWWroot\nonghan\nonghan\cert\TrustPay.cer';
        $config['MerchantID'] = '103881310000016';
        $config['MerchantKeyStoreType'] = '0';
        $config['MerchantCertFile'] = 'D:\WWWroot\nonghan\nonghan\cert\1.pfx';
        $config['MerchantCertPassword'] = '08275321';

        //1、取得MSG参数，并利用此参数值生成验证结果对象
        $tResult = new \Think\Pay\Abchina\Result($config);
        wlog('收到支付回调'. json_encode($_POST['MSG']), 'payLog');

        $tResponse = $tResult->init(I('MSG'));

        if ($tResponse->isSuccess()) {
            $array['TrxType'] = $tResponse->getValue('TrxType');
            $array['OrderNo'] = $tResponse->getValue('OrderNo');
            $array['Amount'] = $tResponse->getValue('Amount');
            $array['BatchNo'] = $tResponse->getValue('BatchNo');
            $array['VoucherNo'] = $tResponse->getValue('VoucherNo');
            $array['HostDate'] = $tResponse->getValue('HostDate');
            $array['HostTime'] = $tResponse->getValue('HostTime');
            $array['MerchantRemarks'] = $tResponse->getValue('MerchantRemarks');
            $array['PayType'] = $tResponse->getValue('PayType');
            $array['NotifyType'] = $tResponse->getValue('NotifyType');
            wlog('支付成功'. json_encode($array), 'payLog');
            //2、、支付成功
            print ("TrxType         = [" . $tResponse->getValue("TrxType") . "]<br/>");
            print ("OrderNo         = [" . $tResponse->getValue("OrderNo") . "]<br/>");
            print ("Amount          = [" . $tResponse->getValue("Amount") . "]<br/>");
            print ("BatchNo         = [" . $tResponse->getValue("BatchNo") . "]<br/>");
            print ("VoucherNo       = [" . $tResponse->getValue("VoucherNo") . "]<br/>");
            print ("HostDate        = [" . $tResponse->getValue("HostDate") . "]<br/>");
            print ("HostTime        = [" . $tResponse->getValue("HostTime") . "]<br/>");
            print ("MerchantRemarks = [" . $tResponse->getValue("MerchantRemarks") . "]<br/>");
            print ("PayType         = [" . $tResponse->getValue("PayType") . "]<br/>");
            print ("NotifyType      = [" . $tResponse->getValue("NotifyType") . "]<br/>");

        } else {
            //3、失败
            print ("<br>ReturnCode   = [" . $tResponse->getReturnCode() . "]<br>");
            print ("ErrorMessage = [" . $tResponse->getErrorMessage() . "]<br>");
        }
    }

}