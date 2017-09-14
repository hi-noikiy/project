<?php
// +----------------------------------------------------------------------
// | 用户模型
// +----------------------------------------------------------------------
// | 中瑞卷管理系统
// +----------------------------------------------------------------------
// | Author: 嗄沬 280708784@qq.com
// +----------------------------------------------------------------------

namespace Check\Model;
use Think\Model;

class AdminModel extends Model {

    public function lists($status = 1, $order = 'uid DESC', $field = true){
        $map = array('status' => $status);
        return $this->field($field)->where($map)->order($order)->select();
    }

    /**
     * 登录指定用户
     * @param  varchar $username 用户名
     * @param  varchar $password 用户密码
     * @return boolean      ture-登录成功，false-登录失败
     * @Author: 嗄沬 280708784@qq.com
     */
    public function login($username, $password){
        /* 检测是否在当前应用注册 */
        $map['username'] = $username;
        $user = M('Admin')->where($map)->find();

        if(is_array($user) && $user['status']){
            /* 验证用户密码 */
            if(chrent_md5($password) !== $user['password']){
                $this->error = '密码输入错误！'; 
                return false;
            }
            if($user['isguser']!='1'){
            	$this->error = '未开通验证权限！';
            	return false;
            }
        } else {
            $this->error = '帐号或密码有误，请重新输入！'; 
            return false;
        }

        //记录行为
        //action_log('user_login', 'user', $uid, $uid);
        session('adminGoodsInfo', $user);
        /* 登录用户 */
       // $this->autoLogin($user);
        return true;
    }


     /**
     * 自动登录用户
     * @param  integer $user 用户信息数组
     */
    private function autoLogin($user){
        /* 更新登录信息 */
        $data = array(
            'uid'             => $user['uid'],
            'visitCount'     => array('exp', '`visitCount`+1'),
            'lastLoginTime' => NOW_TIME,
            'lastLoginIp'   => get_client_ip(1),
        );
        M('Admin')->save($data);


        /* 记录登录SESSION和COOKIES */
        $auth = array(
            'uid'             => $user['uid'],
            'username'       => $user['username'],
            'realName'       => $user['realName'],
            'lastLoginTime' => $user['lastLoginTime'],
            'cinemaGroup' => $user['cinemaGroup'],
            'roleId' => $user['roleId'],
            'cinemaCodeList' => $user['cinemaCodeList'],
        );
        session('adminUserInfo', $auth);
        session('user_auth_sign', data_auth_sign($auth));
    }
    
    
    
    
  	/**
     * 用户修改密码
     * @param  integer $user 用户信息数组
     * 
     * 
     */
    public function up_password($password,$newpass,$newpass_2){
    	
    	$oldpassword=chrent_md5($password);
		$newpassword=chrent_md5($newpass);
		$repassword=chrent_md5($newpass_2);
		if($newpassword!=$repassword) {
			//echo '{"statusCode":"0", "message":"两次密码输入不一致"}';
			return 1;
		}
		$map=array();
		$map['uid'] = $_SESSION['adminGoodsInfo']['uid'];
        $user = M('Admin')->where($map)->find();
        
        
        

        if($oldpassword !== $user['password']){
//                $this->error = '密码错误！'; 
                return 2;
        }
        
        $data=array();
        
        $data['password']=$newpassword;
//        $data['uid'] = $_SESSION['adminGoodsInfo']['uid'];
        $wherearray=array();
        $wherearray['uid']=array('EQ',$_SESSION['adminGoodsInfo']['uid']);
        $user = M('Admin')->where($wherearray)->save($data);
        
		if($user) {
		
			return 0;
		
		
		
		}else{
		
			return 3;
		
		}
        
    	
	
    	
    }
    
    
    
    
    
    
    
    
    
}