<?php

namespace Home\Model;
use Think\Model;

class MemberModel extends Model {
	/**
	 * 添加券包
	 */
	public function addMemberVoucher($data){
		$mod = M('MemberVoucher');
		if (!$mod->create($data)){
			return false;
		}else{
			if($mod->add($data)){
				return true;
			}else{
				return false;
			}
		}
	}
	/**
	 * 验证票券是否可添加
	 */
	public function checkVoucher($voucherNum){
		$voucherInfo = M('MemberVoucher')->field('cardId, memberId')->where(array('voucherNum' => $voucherNum, 'isUnlock' => 0))->find();
		return $voucherInfo;
	}
	
	/**
	 * 获取用户用户券包
	 */
	public function getMemberVoucherList($field = '*', $map = '', $limit = '', $order = 'validData desc'){
		// 'voucherName, voucherNum, typeClass, voucherValue, createdDatetime, validData, typeId'
		$voucherList = M('MemberVoucher')->field($field)->limit($limit)->where($map)->order($order)->select();
		// echo M('MemberVoucher')->_sql();
        return $voucherList;
	}
	/**
	 * 获取绑定信息
	 * @param unknown $map
	 * @return \Think\mixed
	 */
	function getBindInfo($map){
		$bindInfo = M('memberBind')->where($map)->find();
		return $bindInfo;
	}
	
	/**
	 * 修改用户信息
	 */
	function saveUser($where,$data){
		$saveUser = M('member')->where($where)->data($data)->save();
		wlog(M('member')->_sql(), 'testLog');
		return $saveUser;
	}
    function getUser($map){
    	$user=M('Member')->where($map)->find();
    	return $user;
    }
    /**
     * 保存会员卡用户信息
     * @param unknown $member
     * @param unknown $cinemaCode
     * @param unknown $passWord
     * @return Ambigous <string, \Think\mixed>
     */
    function loginMember($member,$cinemaCode,$passWord){
    
        $weiXinInfo = getWeiXinInfo();
        // print_r($weiXinInfo);

    	$type=M('cinemaMemberType')->where(array('memberType'=>$member['LevelCode'], 'cinemaCode'=>$cinemaCode, 'cinemaGroupId'=>$weiXinInfo['cinemaGroupId']))->find();
    	// echo M('cinemaMemberType')->_sql();
    	if(empty($type['memberGroupId'])){
    		$data['status']=1;
    		$data['info']='该会员卡用户禁止使用';
    	}else{
    		$user=M('member')->where('(cardNum='.$member['CardNum'].' or mobileNum='.$member['CardNum'].') and cinemaCode='.$cinemaCode.' and cinemaGroupId = ' . $weiXinInfo['cinemaGroupId'])->find();
    		$userarr['cardNum']=$member['CardNum'];
    		$userarr['cinemaCode']=$cinemaCode;
    		$userarr['loginip']=get_client_ip();
    		if(empty($user)){
    			$userarr['logincount']=1;
    		}else{
    			$userarr['logincount']=$user['logincount']+1;
    		}
    		$userarr['logintime']=time();
    		$userarr['pword']=encty($passWord);
    		$userarr['userName']=$member['UserName'];
    		$userarr['memberGroupId']=$type['memberGroupId'];
    		if(empty($user['sex'])){
    			$userarr['sex']=$member['Sex'];
    		}
    		$userarr['creditNum']=$member['CreditNum'];
    		$userarr['mobileNum']=$member['MobileNum'];
    		$userarr['levelCode']=$member['LevelCode'];
    		$userarr['levelName']=$member['LevelName'];
    		if(empty($user['birthday'])){
    			$userarr['birthday']=$member['Birthday'];
    		}
    		$userarr['businessName']=$member['BusinessName'];
    		$userarr['businessCode']=$member['BusinessCode'];
    		$userarr['basicBalance']=$member['BasicBalance'];
    		$userarr['donateBalance']=$member['DonateBalance'];
    		$userarr['integralBalance']=$member['IntegralBalance'];
    		$userarr['expirationTime']=$member['ExpirationTime'];
    		$userarr['cardStatus']=$member['CardStatus'];
    		$userarr['bindTime']=time();
    		if ($appInfo['xgTokenId']) {
    			$userarr['xgTokenId'] = $appInfo['xgTokenId'];
    		}
    		$userarr['deviceType'] = $appInfo['deviceType'];
    		$userarr['appVersion'] = $appInfo['appVersion'];
    		$userarr['deviceNumber'] = $appInfo['deviceNumber'];
    
    		if(empty($user)){
    			$userarr['cinemaGroupId'] = $weiXinInfo['cinemaGroupId'];
    			M('member')->add($userarr);
    			// echo M('member')->_sql();
    		}else{
    			$userarr['id']=$user['id'];
    			M('member')->save($userarr);
    		}
    		$user=M('member')->where('(cardNum='.$member['CardNum'].' or mobileNum='.$member['CardNum'].') and cinemaCode='.$cinemaCode.' and cinemaGroupId = ' . $weiXinInfo['cinemaGroupId'])->find();
    		$data['status']=0;
    		$data['info']=$user;
    		session('ftuser',$user);
    		session('cinemaCode',$cinemaCode);
    		cookie('ftuser',$user,3600);
    	}
    	return $data;
    }
}