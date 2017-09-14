<?php

namespace Admin\Model;
use Think\Model;

class PrizeModel extends Model {
	
	function getList($field='*',$map=array(),$start=0,$limit=9999,$order='id'){
		$prizes=M('prizeZr')->field($field)->where($map)->limit($start,$limit)->order($order)->select();
		return $prizes;
	}
	
	function getPrize($field='*',$map=array()){
		$prize=M('prizeZr')->field($field)->where($map)->find();
		return $prize;
	}
	
	
}