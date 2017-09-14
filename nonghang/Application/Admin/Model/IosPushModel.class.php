<?php
// +----------------------------------------------------------------------
// | 系统配置模型
// +----------------------------------------------------------------------
// | 中瑞券管理系统
// +----------------------------------------------------------------------
// | Author: 嗄沬 280708784@qq.com
// +----------------------------------------------------------------------

namespace Admin\Model;
use Think\Model;

class IosPushModel extends Model {

    protected function _initialize(){
        $this->push = new \Think\Push\XingeApp('2200162432', '16acdde100c31937449a2121dbefdf4b');
        $this->messageIOS = new \Think\Push\MessageIOS();
        $this->messageIOS->setExpireTime(86400);
        $acceptTime = new \Think\Push\TimeInterval(0, 0, 23, 59);
        $this->messageIOS->addAcceptTime($acceptTime);
    }

    public function pushSingleDevice($param)
    {
        // print_r($param);
        $this->messageIOS->setAlert($param['content']);
        $this->messageIOS->setBadge(1);
        $this->messageIOS->setSound("beep.wav");
        $this->messageIOS->setCustom($param['custom']);
        $ret = $this->push->PushSingleDevice($param['deviceToken'], $this->messageIOS, \Think\Push\XingeApp::IOSENV_DEV);
        return $ret;
    }


    public function pushAllDevices($param)
    {
        $this->messageIOS->setAlert($param['content']);
        $this->messageIOS->setBadge(1);
        $this->messageIOS->setSound("beep.wav");
        $this->messageIOS->setCustom($param['custom']);
        $ret = $this->push->PushAllDevices(0, $this->messageIOS, \Think\Push\XingeApp::IOSENV_DEV);//IOSENV_PROD
        return $ret;
    }


    function PushSingleAccountIOS($param)
    {
        $this->messageIOS->setAlert($param['content']);
        $this->messageIOS->setBadge(1);
        $this->messageIOS->setSound("beep.wav");
        $this->messageIOS->setCustom($param['custom']);
        $ret = $this->push->PushSingleAccount(0,$param['deviceToken'], $this->messageIOS, \Think\Push\XingeApp::IOSENV_DEV);
        // $ret = $push->PushSingleAccount(0, 'joelliu', $mess, XingeApp::IOSENV_DEV);
        return $ret;
    }
}