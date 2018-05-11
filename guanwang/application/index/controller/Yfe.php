<?php
namespace app\index\controller;

use think\Controller;

class Yfe extends Common
{
    public function _initialize(){
        parent::_initialize();
    }
    
	/**
     * 衣范儿官网首页数据
     */
    public function index()
    {

		$yfe = new \app\index\model\Yfe();
		//type 值代表 1、头图；2、轮播  3、文字  4、资讯图、5资讯图文字
		$where['type'] 	  = 1;
		$banner['type']   = 2;
		$video['type']    = 3;
		$info['type']     = 4;
		$infospan['type'] = 5;
		$infospans['type'] = 6;
		
		$header     = $yfe->where($where)->order('sorts asc')->find();
		$videoList  = $yfe->where($video)->order('sorts asc')->find();
		$infospanl  = $yfe->where($infospan)->order('sorts asc')->find();
		$infoList   = $yfe->where($info)->order('sorts asc')->select();
		$bannerList = $yfe->where($banner)->order('sorts asc')->select();
		$downList = $yfe->where($infospans)->order('sorts asc')->select();
		$ios = $android = '';
		foreach ($downList as $v){
			if(substr($v['name'],0,3) == 'ios'){
				$ios = $v['url'];
			}else{
				$android = $v['url'];
			}
		}
		
		$this->assign('header', $header);
		$this->assign('banner', $bannerList);
		$this->assign('video', $videoList);
		$this->assign('infoList', $infoList);
		$this->assign('infospan', $infospanl);
		$this->assign('ios', $ios);
		$this->assign('android', $android);
		if ($result = $this->checkMobile()){
			if($result == 2){
				$this->assign('down', $ios);
			}else{
				$this->assign('down', $android);
			}
			return $this->fetch('indexmoblie');
		}else {
			return $this->fetch('index');
		}
    }
    
    /**
     * 衣范儿官网首页数据
     */
    public function index2()
    {

		$yfe = new \app\index\model\Yfe();
		//type 值代表 1、头图；2、轮播  3、文字  4、资讯图、5资讯图文字
		$where['type'] 	  = 1;
		$banner['type']   = 2;
		$video['type']    = 3;
		$info['type']     = 4;
		$infospan['type'] = 5;
		$infospans['type'] = 6;
		
		$header     = $yfe->where($where)->order('sorts asc')->find();
		$videoList  = $yfe->where($video)->order('sorts asc')->find();
		$infospanl  = $yfe->where($infospan)->order('sorts asc')->find();
		$infoList   = $yfe->where($info)->order('sorts asc')->select();
		$bannerList = $yfe->where($banner)->order('sorts asc')->select();
		$downList = $yfe->where($infospans)->order('sorts asc')->select();
		$ios = $android = '';
		foreach ($downList as $v){
			if(substr($v['name'],0,3) == 'ios'){
				$ios = $v['url'];
			}else{
				$android = $v['url'];
			}
		}
		
		$this->assign('header', $header);
		$this->assign('banner', $bannerList);
		$this->assign('video', $videoList);
		$this->assign('infoList', $infoList);
		$this->assign('infospan', $infospanl);
		$this->assign('ios', $ios);
		$this->assign('android', $android);
		if ($result = $this->checkMobile()){
			if($result == 2){
				$this->assign('down', $ios);
			}else{
				$this->assign('down', $android);
			}
			return $this->fetch('indexmoblie2');
		}else {
			return $this->fetch('index2');
		}
    }
	
	/**
     * 判断是否是手机访问
     * @return array true 代表手机 false 则是电脑访问
     */
    function CheckSubstrs($substrs,$text){    
			foreach($substrs as $substr)    
				if(false!==strpos($text,$substr)){    
					return true;    
				}    
				return false;    
	}
	public function checkMobile()
	{
	    $useragent=isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';    
		$useragent_commentsblock=preg_match('|\(.*?\)|',$useragent,$matches)>0?$matches[0]:'';      
		  
		$mobile_os_list=array('Google Wireless Transcoder','Windows CE','WindowsCE','Symbian','Android','armv6l','armv5','Mobile','CentOS','mowser','AvantGo','Opera Mobi','J2ME/MIDP','Smartphone','Go.Web','Palm','iPAQ');  
		$mobile_token_list=array('Profile/MIDP','Configuration/CLDC-','160×160','176×220','240×240','240×320','320×240','UP.Browser','UP.Link','SymbianOS','PalmOS','PocketPC','SonyEricsson','Nokia','BlackBerry','Vodafone','BenQ','Novarra-Vision','Iris','NetFront','HTC_','Xda_','SAMSUNG-SGH','Wapaka','DoCoMo','iPhone','iPod','iPad');    
					
		$found_mobile=CheckSubstrs($mobile_os_list,$useragent_commentsblock) ||    
				  CheckSubstrs($mobile_token_list,$useragent);    
					
		if ($found_mobile){    
			if(CheckSubstrs(array('iPhone','iPod'),$useragent)){//判断是否苹果
				return 2;
			}
			return 1;    
		}else{    
			return false;    
		}    
	}	
}
