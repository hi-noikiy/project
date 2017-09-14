<?php

namespace Home\Controller;
use Think\Controller;
class BookController extends Controller {
	protected $config = '';
	protected $appInfo = '';
	protected $loginCinemaCode = '';
	/**
	 * 系统基础控制器初始化
	 */
	public function _initialize(){


		$this->token = I('request.token');
		if (empty($this->token )) {
			$this->token = session('token');
		}else{
			session('token', $this->token);
		}
		
		if (empty($this->token) && strtolower(ACTION_NAME) != 'pay') {
			die('非法访问');
		}
		//获取微信的相关配置
		$this->weiXinInfo = getWeiXinInfo($this->token);
		//设置模板
		setTemp('zrfilm');

		C('TMPL_PARSE_STRING.__IMG__',__ROOT__ . '/Public/' . MODULE_NAME . '/zrfilm/images');
		C('TMPL_PARSE_STRING.__CSS__',__ROOT__ . '/Public/' . MODULE_NAME . '/zrfilm/css');
		C('TMPL_PARSE_STRING.__JS__',__ROOT__ . '/Public/' . MODULE_NAME . '/zrfilm/js');

	}
	

    public function success($content, $dataList = array()) {
		$data['status']  = 0;
        $data['content'] = $content;
        $data['data'] = $dataList;
        $this->ajaxReturn($data);
	}

	public function error($content, $status = 1) {
		$data['status']  = $status;
        $data['content'] = $content;
        $this->ajaxReturn($data);
	}
	/**
	 * 设置会员卡对应用户信息
	 * @param unknown $mobile
	 */
	function getBindCardInfo($user){
		if(!empty($user)){
			if(!empty($user['cardNum'])){
				$bind=D('member')->getBindInfo(array('cardId'=>$user['cardNum'],'cinemaCode'=>$user['businessCode'],'cinemaGroupId'=>$this->weiXinInfo['cinemaGroupId']));
				if(!empty($bind)){
					$mobileUser=D('member')->getUser(array('mobile'=>$bind['mobile'],'cinemaGroupId'=>$this->weiXinInfo['cinemaGroupId']));
					if(!empty($mobileUser)){
						$user=$mobileUser;
					}
				}
			}
		}
		return $user;
	}
}