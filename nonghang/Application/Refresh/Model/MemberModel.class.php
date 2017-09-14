<?php

namespace Refresh\Model;
use Think\Model;

class MemberModel extends Model {
    function getUser($map){
    	$user=M('Member')->where($map)->find();
    	session('ftuser',$user);
    	cookie('ftuser',$user,3600);
    	return $user;
    }
    function countObj($map=''){
    	return M('Member')->where($map)->count();
    }
    function loginMember($member,$cinemaCode,$passWord){
    	$type=M('cinemaMemberType')->where(array('memberType'=>$member['LevelCode']))->find();
    	if(empty($type['memberGroupId'])){
    		$data['status']=1;
    		$data['text']='该会员卡用户禁止使用';
    	}else{
    		$user=M('member')->where('(cardNum='.$member['CardNum'].' or mobileNum='.$member['CardNum'].') and cinemaCode='.$cinemaCode)->find();
    		$userarr['basicBalance']=$member['BasicBalance'];
    		$userarr['donateBalance']=$member['DonateBalance'];
    		$userarr['integralBalance']=$member['IntegralBalance'];
    		$userarr['id']=$user['id'];
    		M('member')->save($userarr);
    		$data['status']=0;
    		$user=M('member')->where('(cardNum='.$member['CardNum'].' or mobileNum='.$member['CardNum'].') and cinemaCode='.$cinemaCode)->find();
    		$data['user']=$user;
    	}
    	return $data;
    }
}