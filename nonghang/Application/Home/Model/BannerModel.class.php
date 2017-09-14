<?php

namespace Home\Model;
use Think\Model;

class BannerModel extends Model {
	function getList($map=array(),$start=0,$limit=999999999,$order='priority desc'){
		$banners=M('Banner')->where($map)->order($order)->select();
		return $banners;
	}
	function getObj($map){
		$banner=M('Banner')->where($map)->order('priority desc')->find();
		return $banner;
	}
}