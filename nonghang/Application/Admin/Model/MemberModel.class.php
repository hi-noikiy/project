<?php

namespace Admin\Model;
use Think\Model;

class MemberModel extends Model {
	
	function loginMember($member,$cinemaCode,$passWord,$cinemaGroupId){
		$user=M('member')->where('(cardNum='.$member['CardNum'].' or mobileNum='.$member['CardNum'].') and businessCode='.$cinemaCode.' and cinemaGroupId='.$cinemaGroupId)->find();
		$userarr['basicBalance']=$member['BasicBalance'];
		$userarr['donateBalance']=$member['DonateBalance'];
		$userarr['integralBalance']=$member['IntegralBalance'];
		$userarr['id']=$user['id'];
		M('member')->save($userarr);
		$data['status']=0;
		$user=M('member')->where('(cardNum='.$member['CardNum'].' or mobileNum='.$member['CardNum'].') and businessCode='.$cinemaCode.' and cinemaGroupId='.$cinemaGroupId)->find();
		$data['user']=$user;
		return $data;
	}
	function getMemberList($field = '*', $map = '', $limit = '', $order = ''){

        $userInfo = session('adminUserInfo');


        if ($userInfo['cinemaGroup'] != '-1' && !empty($userInfo)) {
            if(empty($map['businessCode'])){
                $map['businessCode'] = array('IN', $userInfo['cinemaList']);
            }else{
                $map['businessCode'] = array('IN', $userInfo['cinemaList'] . ',' . $map['cinemaCode']);
            }
            
        }

        if ($userInfo['cinemaCodeList'] != '-1' && !empty($userInfo)) {
            if(empty($map['businessCode'])){
                $map['businessCode'] = array('IN', $userInfo['cinemaCodeList']);
            }else{
                $map['businessCode'] = array('IN', $userInfo['cinemaCodeList'] . ',' . $map['cinemaCode']);
            }
            
        }

        $memberList = M('Member')->field($field)->limit($limit)->where($map)->order($order)->select();
        // echo M('CinemaPlan')->_sql();
        return $memberList;

	}


    public function memberCount($field = '*', $map)
    {
        return M('Member')->where($map)->count($field);
    }


    // function find($map=''){
    // 	return M('User')->where($map)->find();
    // }
    // function save($map=''){
    // 	return M('User')->save($map);
    // }
    // function add($map=''){
    // 	return M('User')->add($map);
    // }
    // function delete($map=''){
    // 	return M('User')->where($map)->delete();
    // }
    // function count($map=''){
    // 	return M('User')->where($map)->count();
    // }

    public function delMemberPriceConfigById($id)
    {
       return M('CinemaMemberPrice')->where(array('id' => $id))->delete();
    }

    public function addMemberPriceConfig($data)
    {
        $mod = M('CinemaMemberPrice');
        if (!$mod->create($data)){
            return false;
        }else{
            $id = $mod->add($data);
            // echo $mod->_sql();
            if($id){
               return $id;
            }else{
                // $map['cinemaCode'] = $data['cinemaCode'];
                // $map['featureAppNo'] = $data['featureAppNo'];
                // M('CinemaPlan')->where($map)->data($data)->save();;
                return false;
            }
        }
    }
	/**
	 * 查询用户信息
	 */
	function backMoney($uid,$money=0){
		$user=M('member')->find($uid);
		$userarr['id']=$uid;
		$cinema=D('cinema')->find($user['businessCode']);
		if(!empty($user['cardNum'])){
			$member = D('ZMUser')->verifyMemberLogin(array('cinemaCode'=>$user['businessCode'],'loginNum'=>$user['cardNum'],'password'=>decty($user['pword']),'link'=>$cinema['link'],'cinemaName'=>$user['businessName']));
			if($member['ResultCode'] == 0){//登录成功
				$userarr['basicBalance']=$member['BasicBalance'];
				$userarr['donateBalance']=$member['DonateBalance'];
				$userarr['integralBalance']=$member['IntegralBalance'];
			}
		}else{
			$userarr['mmoney']=$user['mmoney']+$money;
		}
		if(M('member')->save($userarr)){
			echo '1';
			die();
		}
		echo '0';
	}



    public function updateMemberPriceConfig($data, $map)
    {
        $mod = M('CinemaMemberPrice');
        if (!$mod->create($data)){
            return false;
        }else{
            $id = $mod->where($map)->data($data)->save();;
            // echo $mod->_sql();
            if($id){
               return $id;
            }else{
                return false;
            }
        }
    }


    

    public function getMemberPriceConfigCount($map)
    {
        $userInfo = session('adminUserInfo');
        if ($userInfo['cinemaGroup'] != '-1' && !empty($userInfo)) {
            if(empty($map['cinemaGroupId'])){
                $map['cinemaGroupId'] = array('IN', $userInfo['cinemaGroup']);
            }
            
        }
        return M('CinemaMemberPrice')->where($map)->count();
    }


    public function getMemberPriceConfigList($field = '*', $map = '', $limit = '', $order = '')
    {

        $userInfo = session('adminUserInfo');
        if ($userInfo['cinemaGroup'] != '-1' && !empty($userInfo)) {
            if(empty($map['cinemaGroupId'])){
                $map['cinemaGroupId'] = array('IN', $userInfo['cinemaGroup']);
            }
            
        }

        $cinemaMemberPriceList = M('CinemaMemberPrice')->field($field)->limit($limit)->where($map)->order($order)->select();
        // echo M('CinemaMemberPrice')->_sql();
        return $cinemaMemberPriceList;
    }

    public function getMemberPriceConfigInfoById($field = '*', $id)
    {
        $cinemaMemberPriceInfo = $this->getMemberPriceConfigInfo($field, array('id' => $id));
        return $cinemaMemberPriceInfo;
    }


    public function getMemberPriceConfigInfo($field = '*', $map)
    {
        $cinemaMemberPriceInfo = M('CinemaMemberPrice')->field($field)->where($map)->find();
        // echo M('CinemaMemberPrice')->_sql();
        return $cinemaMemberPriceInfo;
    }

}