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
        	if($result = checkMobile()){
	        	$html = 'about_us_mobile';
	        }else{
	        	$html = 'about_us';
	        }
	        return $this->fetch($html);
        }else if($type==2){
        	if($result = checkMobile()){
	        	$html = 'service_center_mobile';
	        }else{
	        	$html = 'service_center';
	        }
            return $this->fetch($html);
        }else if($type==3){
        	if($result = checkMobile()){
	        	$html = 'contact_us_mobile';
	        }else{
	        	$html = 'contact_us';
	        }
            return $this->fetch($html);
        }else if($type==4){
        	if($result = checkMobile()){
	        	$html = 'business_mobile';
	        }else{
	        	$html = 'business';
	        }
            return $this->fetch($html);
        }
        
    }
    
}
