<?php

namespace Micro\Frameworks\Logic\Base;

use Phalcon\DI\FactoryDefault;

class BaseStatistics {

    protected $di;
    protected $config;
    protected $cookies;
    protected $session;

    public function __construct() {
        $this->di = FactoryDefault::getDefault();
        $this->config = $this->di->get('config');
        $this->cookies = $this->di->get('cookies');
        $this->guid = $this->di->get('uid');
        $this->session = $this->di->get('session');
        $this->request = $this->di->get('request');
    }

    //记录用户访问网站
    public function setGuestLog() {

        //记录渠道cookies,前端弹窗用的
        if ($this->request->get('utm_source')) {
            $this->cookies->set($this->config->websitecookies->utm_source, $this->request->get('utm_source'), time() + 86400);
            $this->cookies->set($this->config->websitecookies->utm_medium, $this->request->get('utm_medium'), time() + 86400);
        }
        //记录渠道cookies,留存率用的
        $parentType = '';
        $subType = '';
        $time = 2592000;
        //$uuid = $this->guid->fguid();
        if ($this->request->get('sem')) {
            $parentType = 'sem';
            $subType = $this->request->get('sem');
            $this->cookies->set($this->config->websitecookies->guestchannel, $parentType, time() + $time); //渠道
            $this->cookies->set($this->config->websitecookies->guestchanneltype, $subType, time() + $time); //渠道内容
        } elseif ($this->request->get('wailian')) {
            $parentType = 'wailian';
            $subType = $this->request->get('wailian');
            $this->cookies->set($this->config->websitecookies->guestchannel, $parentType, time() + $time); //渠道
            $this->cookies->set($this->config->websitecookies->guestchanneltype, $subType, time() + $time); //渠道内容
        } elseif ($this->request->get('utm_source')) {
            $parentType = $this->request->get('utm_source');
            $subType = $this->request->get('utm_medium');
            $this->cookies->set($this->config->websitecookies->guestchannel, $parentType, time() + $time); //渠道
            $this->cookies->set($this->config->websitecookies->guestchanneltype, $subType, time() + $time); //渠道内容
        }

        $uid = $this->request->get('uid');
        if ($uid) {
            $key = "91ns.com_";
            $cookieVal = urlencode(base64_encode($key . $uid));
            $this->cookies->set($this->config->websitecookies->recommendStr, $cookieVal, time() + $time); //渠道内容
            //$this->cookies->set($this->config->websitecookies->guestinfo, $uuid, time() + $time);
        }

        return;
    }

    //获取用户渠道
    public function getSource() {
        $sourceSession = $this->session->get($this->config->websitecookies->source_gift);
        if ($sourceSession) {
            $this->cookies->set($this->config->websitecookies->utm_source, '', time() - 1); //清空cookie
            $this->cookies->set($this->config->websitecookies->utm_medium, '', time() - 1); //清空cookie
            $this->cookies->set($this->config->websitecookies->guestchannel, '', time() - 1); //清空cookie
            $this->cookies->set($this->config->websitecookies->guestchanneltype, '', time() - 1); //清空cookie
            $this->cookies->set($this->config->websitecookies->guestinfo, '', time() - 1); //清空cookie
            $this->cookies->set($this->config->websitecookies->recommendStr, '', time() - 1); //清空cookie
            $this->session->remove($this->config->websitecookies->guestinfo);
            $this->session->remove($this->config->websitecookies->source_gift);
        } else {
            return NULL;
            if ($this->request->get('utm_source')) {
                return $this->request->get('utm_source');
            } else {
                $source = trim($this->cookies->get($this->config->websitecookies->utm_source)->getValue());
                if ($source != NULL) {
                    return $source;
                }
            }
        }
        return NULL;
    }

    //记录用户注册
    public function setRegisterLog($uid) {
        $uuid = trim($this->cookies->get($this->config->websitecookies->guestinfo)->getValue());
        $parentType = trim($this->cookies->get($this->config->websitecookies->guestchannel)->getValue());
        $subType = trim($this->cookies->get($this->config->websitecookies->guestchanneltype)->getValue());
        // 获得设备
        $deviceSession = $this->session->get($this->config->websiteinfo->mobileauthkey);
        $platform = 1; //注册平台:pc
        if (isset($deviceSession['platform'])) {
            $platform = intval($deviceSession['platform']);
        }

        $normalLib = $this->di->get('normalLib');
        $ip = $normalLib->getip();
        
        $new = new \Micro\Models\RegisterLog();
        $new->uuid = $uuid;
        $new->uid = $uid;
        $new->parentType = $parentType;
        $new->subType = $subType;
        $new->createTime = time();
        $new->ip = $ip;
        $new->platform = $platform;
        $new->save();
        return;
    }

    //记录用户登录
    public function setLoginLog($uid) {
        $today = strtotime(date("Ymd"), time());
        $log = \Micro\Models\LoginLog::findfirst("uid='" . $uid . "' and createTime>=" . $today);
        if ($log != false) {
            return;
        }
        $parentType = trim($this->cookies->get($this->config->websitecookies->guestchannel)->getValue());
        $subType = trim($this->cookies->get($this->config->websitecookies->guestchanneltype)->getValue());
        $new = new \Micro\Models\LoginLog();
        $new->uid = $uid;
        $new->createTime = time();
        $new->ip = $this->getip();
        $new->parentType = $parentType;
        $new->subType = $subType;
        $new->save();
        return;
    }

    private function getip() {
        static $realip;
        if (isset($_SERVER)) {
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
                $realip = $_SERVER["HTTP_CLIENT_IP"];
            } else {
                $realip = $_SERVER["REMOTE_ADDR"];
            }
        } else {
            if (getenv("HTTP_X_FORWARDED_FOR")) {
                $realip = getenv("HTTP_X_FORWARDED_FOR");
            } else if (getenv("HTTP_CLIENT_IP")) {
                $realip = getenv("HTTP_CLIENT_IP");
            } else {
                $realip = getenv("REMOTE_ADDR");
            }
        }
        return $realip;
    }

}
