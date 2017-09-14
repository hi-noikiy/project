<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 任务模块
* ==============================================
* @date: 2016-10-11
* @author: luoxue
* @version:
*/
class VgTaskController extends Controller{
	protected $sortArr = array('限时任务', '环球任务', '新手任务');
	protected $typeArr = array('日常任务', '回溯任务', '普通任务', '循环任务');
	protected $stateArr = array('开启中', '投票中', '已结算');
	
	public function _condition(&$condition){
		
		$condition=array();
		if(isset($_POST['name']) && !empty($_POST['name']))
			$condition[]="name like '{$_POST['name']}%'";
		
		$condition['param'] = array('sortArr'=>$this->sortArr, 'typeArr'=>$this->typeArr, 'stateArr'=>$this->stateArr);
	}
	
	public function _params(&$params){
		$params = array();
		$params['sortArr'] = $this->sortArr;
		$params['typeArr'] = $this->typeArr;
	}
}