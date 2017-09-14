<?php
namespace Micro\Frameworks\Pay\Alipay;

use Micro\Frameworks\Pay\Alipay\AlipayFunction as AlipayFunction;
use Phalcon\DI\FactoryDefault;

//支付宝支付接口
class AlipayNotify {
    /**
     * HTTPS形式消息验证地址
     */
    protected $https_verify_url;
    /**
     * HTTP形式消息验证地址
     */
    protected $http_verify_url;
    protected $alipay_config;
    protected $request;
    protected $di;
    protected $config;
    protected $orderMgr;
    protected $userCash;
    protected $taskMgr;

    function __construct(){
        $this->di = FactoryDefault::getDefault();
        $this->request = $this->di->get('request');
        $this->config = $this->di->get('config');
        $this->taskMgr = $this->di->get('taskMgr');
        $this->alipay_config = $this->config->pay->alipay;
        $this->https_verify_url = $this->alipay_config->https_verify_url;
        $this->http_verify_url = $this->alipay_config->http_verify_url;
    }

    function AlipayNotify() {
        $this->__construct();
    }
    /**
     * 针对notify_url验证消息是否是支付宝发出的合法消息
     * @return 验证结果
     */
    function verifyNotify(){
        if($this->request->isPost()){
            //生成签名结果
            $isSign = $this->getSignVeryfy($this->request->getPost(), $this->request->getPost("sign"));
            //获取支付宝远程服务器ATN结果（验证是否是支付宝发来的消息）
            $notifyId = $this->request->getPost("notify_id");
            $responseTxt = 'true';
            if (!empty($notifyId)) {
                $responseTxt = $this->getResponse($notifyId);
            }

            if (preg_match("/true$/i",$responseTxt) && $isSign) {
                return TRUE;
            } else {
                return FALSE;
            }
        }

        return FALSE;
    }

    /**
     * 针对return_url验证消息是否是支付宝发出的合法消息
     * @return 验证结果
     */
    function verifyReturn(){
        if($this->request->isGet()){
            //生成签名结果
            $isSign = $this->getSignVeryfy($this->request->get(), $this->request->get("sign"));
            //获取支付宝远程服务器ATN结果（验证是否是支付宝发来的消息）
            $notifyId = $this->request->get("notify_id");
            $responseTxt = 'true';
            if (!empty($notifyId)) {
                $responseTxt = $this->getResponse($notifyId);
            }

            if (preg_match("/true$/i",$responseTxt) && $isSign) {
                return TRUE;
            } else {
                return FALSE;
            }
        }

        return FALSE;
    }

    /**
     * 获取返回时的签名验证结果
     * @param $para_temp 通知返回来的参数数组
     * @param $sign 返回的签名结果
     * @return 签名验证结果
     */
    function getSignVeryfy($para_temp, $sign) {
        //除去待签名参数数组中的空值和签名参数
        $para_filter = AlipayFunction::paraFilter($para_temp);
        //对待签名参数数组排序
        $para_sort = AlipayFunction::argSort($para_filter);

        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = AlipayFunction::createLinkstring($para_sort);
        $isSgin = FALSE;
        switch (strtoupper(trim($this->alipay_config['sign_type']))) {
            case "RSA" :
                $isSgin = AlipayFunction::rsaVerify($prestr, trim($this->alipay_config['ali_public_key_path']), $sign);
                break;
            default :
                $isSgin = FALSE;
        }

        return $isSgin;
    }

    /**
     * 获取远程服务器ATN结果,验证返回URL
     * @param $notify_id 通知校验ID
     * @return 服务器ATN结果
     * 验证结果集：
     * invalid命令参数不对 出现这个错误，请检测返回处理中partner和key是否为空
     * true 返回正确信息
     * false 请检查防火墙或者是服务器阻止端口问题以及验证时间是否超过一分钟
     */
    function getResponse($notify_id) {
        $transport = strtolower(trim($this->alipay_config['transport']));
        $partner = trim($this->alipay_config['partner']);
        $veryfy_url = '';
        if($transport == 'https') {
            $veryfy_url = $this->https_verify_url;
        }else {
            $veryfy_url = $this->http_verify_url;
        }

        $veryfy_url = $veryfy_url."partner=" . $partner . "&notify_id=" . $notify_id;
        $responseTxt = AlipayFunction::getHttpResponseGET($veryfy_url, $this->alipay_config['cacert']);
        return $responseTxt;
    }
}