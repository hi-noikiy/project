<?php

namespace Home\Controller;
use Think\Controller;
class ShakeInitController extends Controller {

	public function _initialize(){
		$shakeCinemaCode = I('request.cinemaCode');
		if (!empty($shakeCinemaCode)) {
			session('shakeCinemaCode', $shakeCinemaCode);
		}
		setTemp('zrfilm');
		$uid=cookie('uid');
		if(!$uid){
			if(!in_array(strtolower(ACTION_NAME), array('login'))){
				$this->redirect('login');
			}
		}
	}
	
	public function success($content, $dataList = array())
	{
		$data['status']  = 0;
		$data['content'] = $content;
		$data['data'] = $dataList;
		$this->ajaxReturn($data);
	}
	
	public function error($content, $status = 1, $dataList = array())
	{
		$data['status']  = $status;
		$data['content'] = $content;
		$data['data'] = $dataList;
		$this->ajaxReturn($data);
	}
	
}