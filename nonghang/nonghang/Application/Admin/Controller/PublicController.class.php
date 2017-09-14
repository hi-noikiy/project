<?php
/**
 * 登录控制类
 * 
 * @author 王涛
 * @package  admin
 */
namespace Admin\Controller;
use Think\Controller;
class PublicController extends Controller {
    /**
     * 后台用户登录
     * @author 
     */
    public function login($username = null, $password = null, $verify = null){
// echo chrent_md5('123456');
    	if(IS_POST){
			/* 登录用户 */
			$Users = D('Admin');
			
			if($Users->login($username, $password)){ //登录用户
				//TODO:跳转到登录前页面
				$this->success('登录成功！', U('Index/index'));
			} else {
				$this->error($Users->getError());
			}
        } else {
            if(admin_is_login()){
                $this->redirect('Index/index');
            }else{
                
                $this->display();
            }
        }
    }
	
	 public function cinemalogin($username = null, $password = null, $verify = null){
// echo chrent_md5('123456');
    	if(IS_POST){
			/* 登录用户 */
			$Users = D('Admin');
			
			if($Users->login($username, $password)){ //登录用户
				//TODO:跳转到登录前页面
				$this->success('登录成功！', U('Index/index'));
			} else {
				$this->error($Users->getError());
			}
        } else {
            if(admin_is_login()){
                $this->redirect('Index/index');
            }else{
                
                $this->display();
            }
        }
    }
/**
 * 退出登录
 */
    public function logout(){
        if(admin_is_login()){
            // unset($_SESSION);
        	session('adminUserInfo', null);
            session('ADMIN_MENU_LIST_ALL', null);
            S('zmaxfilmMenus' . CPUID, NULL);
      		$this->redirect('Public/login');
        } else {
            $this->redirect('Public/login');
        }
    }

    public function success($content, $dataList = array())
	{
		$data['status']  = 0;
        $data['content'] = $content;
        $data['data'] = $dataList;
        $this->ajaxReturn($data);
	}

	public function error($content, $status = 1)
	{
		$data['status']  = $status;
        $data['content'] = $content;
        $this->ajaxReturn($data);
	}

    public function clearCache()
    {
        $cachePre = C('CACHE_NAME_LIST');
        $cacheName = S(C('CACHE_NAME_LIST'));
        foreach ($cacheName as $key => $value) {
            if (!strstr($key, $cachePre . 'APPINFOtokenId_') && !strstr($key, $cachePre . 'APPINFOUserInfotokenId_')) {
                // echo $key.'<br />';
                S($key, null);
            }
        }
        $this->success('缓存清空成功！');
    }

}