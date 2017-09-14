<?php
// +----------------------------------------------------------------------
// | 公用控制器
// +----------------------------------------------------------------------
// | 中瑞卷管理系统
// +----------------------------------------------------------------------
// | Author: 嗄沬 280708784@qq.com
// +----------------------------------------------------------------------

namespace Check\Controller;
use Think\Controller;
class PublicController extends \Think\Controller {
    /**
     * 影城票券登录
     * @author 
     */
    public function login($username = null, $password = null, $verify = null){
        $username=$_POST['username'];
        $password=$_POST['password'];
        if(IS_POST){
			/* 登录用户 */
			$Admin = D('Admin');
			
			if($Admin->login($username, $password)){ //登录用户
				
				
				$this->success('登入成功');
				//TODO:跳转到登录前页面
				$this->redirect('Index/sale');
			} else {
				$this->tperror($Admin->getError());
			}
        } else {
            // die('222');
            if(is_login()){
                $this->redirect('Index/sale');
            }else{                
                $this->display();
            }
        }
    }
    /**
     * 包场验证登录
     * @author
     */
    public function privatelogin($username = null, $password = null, $verify = null){
        $username=$_POST['username'];
        $password=$_POST['password'];
        if(IS_POST){
			/* 登录用户 */
			$Admin = D('Admin');
			
			if($Admin->login($username, $password)){ //登录用户
				
				
				$this->success('登入成功');
				//TODO:跳转到登录前页面
				$this->redirect('Private/index');
			} else {
				$this->tperror($Admin->getError());
			}
        } else {
            // die('222');
            if(is_login()){
                $this->redirect('Private/index');
            }else{                
                $this->display();
            }
        }
    }

    /* 退出登录 */
    public function logout(){
        if(is_login()){
        	 D('users')->logout();
            session('[destroy]');
           
            $this->redirect('login');
        } else {
        	
            $this->redirect('login');
        }
    }

     /* 包场验证退出登录 */
    public function privatelogout(){
     	if(admin_is_login()){
            // unset($_SESSION);
        	session('adminGoodsInfo', null);
            session('ADMIN_MENU_LIST_ALL', null);
//            S('zmaxfilmMenus' . CPUID, NULL);
      		$this->redirect('privatelogin');
        } else {
            $this->redirect('privatelogin');
        }
       
    }

    public function verify(){
        $verify = new \Think\Verify();
        $verify->entry(1);
    }
    
     /**
     * 用户修改密码
     * @author 
     */
    public function up_pass(){
    	$newpass_2=$_POST['newpass_2'];
    	$password=$_POST['password'];
    	$newpass=$_POST['newpass'];
    	$Users = D('Admin');
    	$flag= $Users->up_password($password,$newpass,$newpass_2);
    	 
    	 
    	switch ($flag) {
    		case 0:
    			$this->success("修改成功！");
    			break;
    		case 1:
    			$this->error('两次密码输入不一致！');
    			break;
    		case 2:
    			$this->error('旧密码错误！');
    			break;
    		case 3:
    			$this->error('修改失败！');
    			break;

    		default:
    			$this->error('修改失败！');
    			break;
    	}
    	 

    }
 /**
     * 用户修改密码
     * @author 
     */
    public function group_up_pass(){
    	$newpass_2=$_POST['newpass_2'];
    	$password=$_POST['password'];
    	$newpass=$_POST['newpass'];
    	$Users = D('Round');
    	$flag= $Users->up_password($password,$newpass,$newpass_2);
    	 
    	 
    	switch ($flag) {
    		case 0:
    			$this->success("修改成功！");
    			break;
    		case 1:
    			$this->error('两次密码输入不一致！');
    			break;
    		case 2:
    			$this->error('旧密码错误！');
    			break;
    		case 3:
    			$this->error('修改失败！');
    			break;

    		default:
    			$this->error('修改失败！');
    			break;
    	}
    	 

    }
    
    
    
    
    
    
    

	 /**
     * 影城票券登录
     * @author 
     */
    public function grouplogin($username = null, $password = null, $verify = null){
        // die('222');
        $username=$_POST['username'];
        $password=$_POST['password'];
        if(IS_POST){
			/* 登录用户 */
			$Admin = D('Round');

			if($Admin->login($username, $password)){ //登录用户
				
				
				$this->success('登入成功');
				//TODO:跳转到登录前页面
				$this->redirect('Round/group');
			} else {
//				echo 'xxx';
				$this->tperror($Admin->getError());
			}
        } else {
            // die('222');
            if(is_login()){
                $this->redirect('Round/group');
            }else{                
                $this->display('grouplogin');
            }
        }
    }
    /* 影城票券退出登录 */
    public function grouplogout(){
     	if(admin_is_login()){
            // unset($_SESSION);
        	session('adminGoodsInfo', null);
            session('ADMIN_MENU_LIST_ALL', null);
//            S('zmaxfilmMenus' . CPUID, NULL);
      		$this->redirect('grouplogin');
        } else {
            $this->redirect('grouplogin');
        }
       
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
	

	
}