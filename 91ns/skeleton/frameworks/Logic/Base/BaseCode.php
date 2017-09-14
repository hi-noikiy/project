<?php

namespace Micro\Frameworks\Logic\Base;


use \Securimage;
use Phalcon\DI\FactoryDefault;

class BaseCode{

    protected $di;
    protected $config;
    protected $request;
    protected $geetest;

    public function __construct(){
        $this->di = FactoryDefault::getDefault();
        $this->config = $this->di->get('config');
        $this->request = $this->di->get('request');
        $this->geetest = $this->di->get('geetest');
    }


    /*
     * 验证模块
     * */
    public function checkSecurityCode($captchaId, $securityCode) {
        if(Securimage::checkByCaptchaId($captchaId, $securityCode) == true){
            return TRUE;
        }

        return FALSE;
    }

    /*
     * 验证模块
     * */
    public function checkCaptcha() {
        if($this->config->captchaType == 'geeTest'){
            return $this->geetest->checkGeetest($this->request);
        }else{
            $captchaId = $this->request->getPost('captchaId');
            $securityCode = $this->request->getPost('securityCode');
            return Securimage::checkByCaptchaId($captchaId, $securityCode);
        }
    }

    /**
     * 获取服务器时间
     */
    public function getServerTime() {
        return time();
    }

    /*
     * 按数据中的某个字段进行排序
     * @param array $arr 要排序的数组
     * @param string $key 字段名
     */

    function arrayMultiSort($arr, $sort, $sortBy = FALSE)
    {
        $tmpOrder = array();
        foreach ($arr as $k => $val) {
            $tmpOrder[$k] = $val[$sort];
        }

        if ($sortBy) {
            array_multisort($tmpOrder, SORT_DESC, $arr);
        } else {
            array_multisort($tmpOrder, SORT_ASC, $arr);
        }

        return $arr;
    }

    function writeLog($moduleType, $levelType, $logData) {
        // 1 判断module，得出要将日志写到哪个文件中
        // 2 生成要写到的日志文件的路径，这里面要判断日期文件夹是否生成
        // 3 写入日志内容
    }

    function isMobile()
    {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
        {
            return true;
        } 
        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset ($_SERVER['HTTP_VIA']))
        { 
            // 找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        } 
        // 脑残法，判断手机发送的客户端标志,兼容性有待提高
        if (isset ($_SERVER['HTTP_USER_AGENT']))
        {
            $clientkeywords = array ('nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ipod',
                'blackberry',
                'meizu',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'micromessenger',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap',
                'mobile'
                ); 
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
            {
                return true;
            } 
        } 
        // 协议法，因为有可能不准确，放到最后判断
        if (isset ($_SERVER['HTTP_ACCEPT']))
        { 
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
            {
                return true;
            } 
        } 
        return false;
    }

    /*
     * 更新session
     */
    function updateSession() {
        $session = $this->di->get('session');
        if( !empty($session->get('last_access') ) || ( time() - $session->get('last_access') )>60 )  {
            $session->set('last_access', time());
        }
    }

    function is_not_json($str){ 
        return is_null(json_decode($str));
    }
}