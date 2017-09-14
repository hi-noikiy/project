<?php

namespace Api\Model;
use Think\Model;

class MemberTypeModel extends Model {
    function findtype($cinemaCode=''){
    	$memberType=M('cinemaMemberType')->where('cinemaCode='.$cinemaCode.' and memberGroupId!=0')->order('memberGroupId')->find();
    	if(empty($memberType)){
    		$memberType['memberGroupId']='99101';
    	}
    	return $memberType['memberGroupId'];
    }

}