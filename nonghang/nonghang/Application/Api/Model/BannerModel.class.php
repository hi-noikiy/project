<?php

namespace Api\Model;
use Think\Model;

class BannerModel extends Model {
  	
	function  getAppBanners($map){
		$banners=M('appBanner')->where($map)->order('priority desc')->select();
		foreach ($banners as $k=>$v){
			$banners[$k]['img']=C('IMG_URL').'Uploads/'.$v['img'];
			$data=explode(';', $v['data']);
			foreach ($data as $key=>$val){
				$datas=explode(':', $val);
				$param[$datas[0]]=$datas[1];
			}
			$banners[$k]['param']=$param;
			unset($param);
		}
		return $banners;
	}
}