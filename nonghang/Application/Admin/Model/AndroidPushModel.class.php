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

class AndroidPushModel extends Model {

    protected function _initialize(){
        $this->push = new \Think\Push\XingeApp('2100162431', '45ae702e06fa837b11e9349c64d1b3b6');
        $this->messageAndroid = new \Think\Push\Message();
        $this->messageAndroid->setType(\Think\Push\Message::TYPE_NOTIFICATION);
        $style = new \Think\Push\Style(0,1,1,1,0);
        $action = new \Think\Push\ClickAction();
        $action->setActionType(\Think\Push\ClickAction::TYPE_ACTIVITY);
        $action->setActivity('com.dreamfilm.app.ui.NewMainActivity');
        $this->messageAndroid->setStyle($style);
        $this->messageAndroid->setAction($action);
        $this->messageAndroid->setExpireTime(86400);
        $acceptTime1 = new \Think\Push\TimeInterval(0, 0, 23, 59);
        $this->messageAndroid->addAcceptTime($acceptTime1);

    }

    #含义：样式编号0，响铃，震动，不可从通知栏清除，不影响先前通知

    // builderId    int 本地通知样式，必填。含义参见终端SDK文档
    // ring int 是否响铃，0否，1是。选填，默认0
    // vibrate  int 是否振动，0否，1是。选填，默认0
    // clearable    int 通知栏是否可清除，0否，1是。选填，默认1
    // nId  int 若大于0，则会覆盖先前弹出的相同id通知；若为0，展示本条通知且不影响其他通知；若为-1，将清除先前弹出的所有通知，仅展示本条通知。选填，默认为0
    // lights   int 是否呼吸灯，0否，1是，选填，默认1
    // iconType 0是应用内图标，1是上传图标，选填。默认0 
    // iconRes  string  应用内图标文件名（xg.png）或者下载图标的url地址，选填 使用setIconRes($value)设置
    // ringRaw  string  是否呼吸灯，0否，1是，选填，默认1
    // styleId  int Web端设置是否覆盖编号的通知样式，0否，1是，选填。默认1
    // $style = new \Think\Push\Style($builderId[,$ring][,$vibrate][,$clearable][,$nId][,$lights][,$iconType][,$styleId]);
    
    public function pushSingleDevice($param)
    {

        $this->messageAndroid->setTitle($param['title']);
        $this->messageAndroid->setContent($param['content']);

        $this->messageAndroid->setCustom($param['custom']);

        $ret = $this->push->PushSingleDevice($param['deviceToken'], $this->messageAndroid, 0);
        return($ret);
    }

    public function pushAllDevices($param)
    {

        $this->messageAndroid->setTitle($param['title']);
        $this->messageAndroid->setContent($param['content']);

        $this->messageAndroid->setCustom($param['custom']);

        $ret = $this->push->PushAllDevices(0, $this->messageAndroid, 0);
        return($ret);
    }

    public function queryTokensOfAccount($param)
    {
        // $push = new XingeApp(000, 'secret_key');
        $ret = $this->push->QueryTokensOfAccount($param['deviceToken']);
        return ($ret);
    }

}