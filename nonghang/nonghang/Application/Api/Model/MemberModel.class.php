<?php

namespace Api\Model;
use Think\Model;

class MemberModel extends Model {
	
	/**
	 * 添加上映提醒
	 * @param unknown $uid
	 * @param unknown $filmId
	 * @return string
	 */
	function saveRemind($uid,$filmId){
		$member=M('member')->find($uid);
		$films=explode(',', $member['onfilm']);
		$film=M('film')->find($filmId);
		if(!in_array($filmId, $films)){
			$data['id']=$uid;
			if(!empty($member['onfilm'])){
				$data['onfilm']=$member['onfilm'].','.$filmId;
			}else{
				$data['onfilm']=$filmId;
			}
			$result=M('member')->save($data);
			if($result===false){
				$msg['status']=1;
				$msg['info']='提醒失败';
			}else{
				if(!empty($result)){
					M('film')->where(array('id'=>$filmId))->setInc('lookNum',1);
					$film=M('film')->find($filmId);
				}
				$msg['status']=0;

				$msg['info']=$data['onfilm'];
			}
		}else{
			$msg['status']=0;
			$msg['info']=$member['onfilm'];
		}
		$msg['lookNum']=$film['lookNum'];
		return $msg;
	}
	
	/**
	 * 取消上映提醒
	 * @param unknown $uid
	 * @param unknown $filmId
	 * @return string
	 */
	function delRemind($uid,$filmId){
		$member=M('member')->find($uid);
		$films=explode(',', $member['onfilm']);
		$film=M('film')->find($filmId);
		$onfilm='';
		foreach ($films as $v){
			if($v!=$filmId){
				$onfilm.=$v.',';
			}
		}
		$data['id']=$uid;
		$data['onfilm']=substr($onfilm, 0,-1);
		$result=M('member')->save($data);
		if($result===false){
			$msg['status']=1;
			$msg['info']='取消提醒失败';
		}else{
			if(!empty($result)){
				M('film')->where(array('id'=>$filmId))->setInc('lookNum',-1);
				$film=M('film')->find($filmId);
			}
			$msg['status']=0;
			$msg['info']=$onfilm;
		}
		$msg['lookNum']=$film['lookNum'];
		return $msg;
	}
	
   /**
    * 获取用户信息
    * @param unknown $map
    * @return \Think\mixed
    */
    function getUser($map){
    	$carduser = M('Member')->where($map)->find();
        // echo M('Member')->_sql();
    	return $carduser;
    }
    /**
     * 保存会员卡用户信息
     * @param unknown $member
     * @param unknown $cinemaCode
     * @param unknown $passWord
     * @return Ambigous <string, \Think\mixed>
     */
    function loginMember($member,$cinemaCode,$passWord,$appInfo=''){

        $cinemaGroupId = $appInfo['cinemaGroupInfo']['cinemaGroupId'];
    	$type=M('cinemaMemberType')->where(array('memberType'=>$member['LevelCode'], 'cinemaCode'=>$cinemaCode, 'cinemaGroupId'=>$cinemaGroupId))->find();
    	// echo M('cinemaMemberType')->_sql();
        if(empty($type['memberGroupId'])){
    		$data['status']=1;
    		$data['info']='该会员卡用户禁止使用';
    	}else{
    		$user=M('member')->where('(cardNum='.$member['CardNum'].' or mobileNum='.$member['CardNum'].') and cinemaCode='.$cinemaCode)->find();
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
                $userarr['cinemaGroupId'] = $cinemaGroupId;
    			M('member')->add($userarr);
                // echo M('member')->_sql();
    		}else{
    			$userarr['id']=$user['id'];
    			M('member')->save($userarr);
    		}
    		$user=M('member')->where('(cardNum='.$member['CardNum'].' or mobileNum='.$member['CardNum'].') and cinemaCode='.$cinemaCode)->find();
    		$data['status']=0;
    		$data['info']=$user;
    	}
    	return $data;
    }
    /**
     * 修改用户信息
     */
   function saveUser($where,$data){
   	    $saveUser = M('member')->where($where)->data($data)->save();
        wlog(M('member')->_sql(), 'testLog');
   		return $saveUser;
   }
   /**
    * 获取绑定信息
    * @param unknown $map
    * @return \Think\mixed
    */
   function getBindInfo($map){
        $bindInfo = M('memberBind')->where($map)->find();
        // echo M('memberBind')->_sql();
   		return $bindInfo;
   }

    /**
     * 添加券包
     */
    public function addMemberVoucher($data)
    {
        $mod = M('MemberVoucher');
        if (!$mod->create($data)){
            return false;
        }else{
            $id = $mod->add($data);
            wlog($mod->_sql(), 'addvoucher');
            if($id){
               return true;
            }else{
                return false;
            }
        }
    }
    /**
     * 验证票券是否可添加
     */
    public function checkVoucher($voucherNum)
    {
        $voucherInfo = M('MemberVoucher')->field('cardId, memberId')->where(array('voucherNum' => $voucherNum, 'isUnlock' => 0))->find();
        return $voucherInfo;
    }
    /**
     * 获取用户用户券包
     */
    public function getMemberVoucherList($field = '*', $map = '', $limit = '', $order = '')
    {
        // 'voucherName, voucherNum, voucherType, voucherValue, createdDatetime, validData, typeId'
        $voucherList = M('MemberVoucher')->field($field)->limit($limit)->where($map)->order($order)->select();
        return $voucherList ? $voucherList : array();
    }
    /**
     * 获取用户消息中心列表
     */
    public function getMemberMessageList($field = '*', $map = '', $limit = '', $order = '')
    {
        $messageList = M('AppMessage')->field($field)->limit($limit)->where($map)->order($order)->select();
        // echo M('AppMessage')->_sql();
        return $messageList ? $messageList : array();
    } 

    /**
     * 获取用户消息中心信息
     */
    public function getMemberMessageInfo($field = '*', $map = '')
    {
        $messageInfo = M('AppMessage')->field($field)->limit($limit)->where($map)->order($order)->find();
        return $messageInfo;
    }

    public function setMemberMessageInfo($data, $map)
    {
        $mod = M('AppMessage');
        if (!$mod->create($data)){
            return false;
        }else{
            $id = $mod->where($map)->data($data)->save();
            if($id){
                return $id;
            }else{
                return false;
            }
        }
    }
}