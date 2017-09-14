<?php

namespace Home\Model;
use Think\Model;

class MemberTypeModel extends Model {
    function findtype($cinemaCode=''){
    	$weiXinInfo = getWeiXinInfo();
    	return $weiXinInfo['defaultLevel'];
    }
    
}