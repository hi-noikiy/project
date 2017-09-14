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

class RoundModel extends Model {

   
    /**
     * 登录指定用户
     * @param  varchar $username 用户名
     * @param  varchar $password 用户密码
     * @return boolean      ture-登录成功，false-登录失败
     * @Author: 嗄沬 280708784@qq.com
     */
    public function login($username, $password){
        /* 检测是否在当前应用注册 */
        $map['account'] = $username;
        $user = M('goodsSeller')->where($map)->find();
//        echo M('goodsSeller')->getlastsql();

        if(is_array($user) ){
            /* 验证用户密码 */
        	
        	
//        	echo chrent_md5($password).'-----'.$user['passwd'];
            if(chrent_md5($password) !== $user['passwd']){
                $this->error = '密码错误！'; 
                return false;
            }
        } else {
            $this->error = '帐号或密码有误，请重新输入！'; 
            return false;
        }
        $userarr['id']=$user['id'];
        $userarr['loginTime']=time();
        D('goodsSeller')->save($userarr);
        $user['loginTime']=$userarr['loginTime'];
        session('adminGoodsInfo', $user);
        //记录行为
        //action_log('user_login', 'user', $uid, $uid);

        /* 登录用户 */
       // $this->autoLogin($user);
        return true;
    }

	function getCountCodes($map){
		$user=session('adminGoodsInfo');
		if($user['account']!='adminTest'){
    		$map['sellerNo']=$user['id'];
    	}
    	$codes=M('orderCode')->where($map)->count();
    	return $codes;
    }
    
    function getCodes($map,$start=0,$limit=9999){
    	$user=session('adminGoodsInfo');
    	if($user['account']!='adminTest'){
    		$map['sellerNo']=$user['id'];
    	}
    	$codes=M('orderCode')->where($map)->order('id')->limit($start,$limit)->select();
    	foreach ($codes as $k=>$v){
    		$order=M('orderRound')->find($v['orderid']);
    		$codes[$k]['cinemaName']=$order['cinemaName'];
    		$codes[$k]['orderTime']=date('Y-m-d H:i',$order['orderTime']);
    		$codes[$k]['goodsName']=$order['goodsName'];
    		$codes[$k]['number']=$order['number'];
    		$codes[$k]['price']=$order['price'];
    		if($v['status']=='1'){
    			$codes[$k]['statustr']='已兑换';
    		}else{
    			$codes[$k]['statustr']='未兑换';
    		}
    	}
    	return $codes;
    }
    
    function checkCode($code,$adminName){
    	$order=M('orderCode')->where(array('code'=>$code))->find();
    	if(!empty($order)&&$order['status']=='1'){
    		$data['status']=0;
    		$data['text']='该券码已兑换';
    	}else{
    		$orderarr['id']=$order['id'];
    		$orderarr['status']=1;
    		$orderarr['gotTime']=time();
    		$orderarr['gotMan']=$adminName;
    		if(M('orderCode')->save($orderarr)){
    			$data['status']=1;
    			$data['text']='兑换成功';
    		}else{
    			$data['status']=0;
    			$data['text']='兑换失败';
    		}
    		
    	}
    	return $data;
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
		$map['id'] = $_SESSION['adminGoodsInfo']['id'];
        $user = M('goodsSeller')->where($map)->find();
        
//        echo M()->getlastsql
        

        if($oldpassword !== $user['passwd']){
//                $this->error = '密码错误！'; 
                return 2;
        }
        
        $data=array();
        
        $data['passwd']=$newpassword;
//        $data['uid'] = $_SESSION['adminGoodsInfo']['uid'];
        $wherearray=array();
        $wherearray['id']=array('EQ',$_SESSION['adminGoodsInfo']['id']);
        $user = M('goodsSeller')->where($wherearray)->save($data);
        
		if($user) {
		
			return 0;
		
		
		
		}else{
		
			return 3;
		
		}
        
    	
	
    	
    }
 
    
}