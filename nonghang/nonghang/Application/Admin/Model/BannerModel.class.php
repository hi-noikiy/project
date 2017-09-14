<?php

namespace Admin\Model;
use Think\Model;

class BannerModel extends Model {
	function getList($map=array(),$order='cinemaCode,priority desc',$start=0,$limit=999999999){
		$banners=M('Banner')->where($map)->select();
		foreach ($banners as $key=>$val){
			if(empty($val['cinemaCode'])){
				$banners[$key]['cinemaName']='中兴院线';
			}else{
				$cinema=M('cinema')->find($val['cinemaCode']);
				$banners[$key]['cinemaName']=$cinema['cinemaName'];
			}
		}
		return $banners;
	}
}