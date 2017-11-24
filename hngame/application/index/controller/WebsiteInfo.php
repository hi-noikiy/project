<?php
namespace app\index\controller;

use think\Controller;

class WebsiteInfo extends Common
{
    public function _initialize(){
        parent::_initialize();
    }
    
    public function index($type=1)
    {
        $websiteInfo = new \app\index\model\WebsiteInfo();
        $where['status'] = 1;
        $where['type'] = $type;
        $webInfo = $websiteInfo->where($where)->find();
        $this->assign('webInfo', $webInfo);
        if($type==1){
            return $this->fetch('about_us');
        }else if($type==2){
            return $this->fetch('service_center');
        }else if($type==3){
            return $this->fetch('contact_us');
        }else if($type==4){
            return $this->fetch('business');
        }
        
    }
    
}
