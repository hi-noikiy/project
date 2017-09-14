<?php

namespace Admin\Model;
use Think\Model;

class CinemaModel extends Model {
	

    function cinemaCount($map=''){
    	return M('Cinema')->where($map)->count();
    }
    
    function getCinemasStr($map=''){
    	$cinemaList=D('cinema')->getCinemaList();
		$str='';
		foreach ($cinemaList as $v){
			$str.=','.$v['cinemaCode'];
		}
		return substr($str, 1);
    }


    public function delCinemaByCode($cinemaCode)
    {   
        if(M('Cinema')->where(array('cinemaCode'=>$cinemaCode))->delete()){
            clearCache('getCinemaList');
            return true;
        }else{
            return false;
        }
    }

    /**
    * 根据cinemaCode获取影院信息
    * @param cinemaCode 影院编号;
    * @return array();
    * @author 宇
    */
    public function getCinemaInfoBycode($field, $cinemaCode)
    {
        $cinemaInfo = S('getCinemaInfoBycode' . $cinemaCode . $field);
        if(empty($cinemaInfo)){
            $cinemaInfo = M('Cinema')->field($field)->where(array('cinemaCode' => $cinemaCode))->find();
            S('getCinemaInfoBycode' . $cinemaCode . $field, $cinemaInfo, 3600);
        }
        return $cinemaInfo;
    }
    /**
     * 获取影厅列表
     */
    function getHallList($cinemaCode){
    	$hallInfo = M('CinemaHall')->where(array('cinemaCode' => $cinemaCode))->select();
    	foreach ($hallInfo as $k=>$v){
    		$priceStr='';
    		$price=json_decode($v['price'],true);
    		foreach ($price as $key=>$val){
    			$seat=M('CinemaHallSeat')->field('sectionName')->where(array('cinemaCode' => $cinemaCode,'hallNo'=>$v['hallNo'],'sectionId'=>$key))->find();
    			$priceStr.=';'.$seat['sectionName'].':'.$val;
    			unset($seat);
    		}
    		$hallInfo[$k]['priceStr']=substr($priceStr, 1);
    	}
    	return $hallInfo;
    }
    /**
     * 获取影厅对应分区列表
     */
    function getSectionList($cinemaCode,$hallNo){
    	$sectionInfo = S('getSectionList' . $cinemaCode.$hallNo);
    	if(empty($sectionInfo)){
    		$sectionInfo = M('CinemaHallSeat')->field('sectionId,sectionName')->where(array('cinemaCode' => $cinemaCode,'hallNo'=>$hallNo))->group('sectionId')->select();
    		S('getSectionList' . $cinemaCode.$hallNo, $sectionInfo, 3600);
    	}
    	return $sectionInfo;
    }
    
    /**
    * 获取影院列表
    * @param array();
    * @return array();
    * @author 宇
    */
    public function getCinemaList($field = '*', $map = '', $limit = '', $order = '')
    {

        $userInfo = session('adminUserInfo');

        if ($userInfo['cinemaGroup'] != '-1' && !empty($userInfo)) {
            if(empty($map['cinemaCode'])){
                $map['cinemaCode'] = array('IN', $userInfo['cinemaList']);
            }else{
                $map['cinemaCode'] = array('IN', $userInfo['cinemaList'] . ',' . $map['cinemaCode']);
            }
            
        }

        if ($userInfo['cinemaCodeList'] != '-1' && !empty($userInfo)) {
            if(empty($map['cinemaCode'])){
                $map['cinemaCode'] = array('IN', $userInfo['cinemaCodeList']);
            }else{
                $map['cinemaCode'] = array('IN', $userInfo['cinemaCodeList'] . ',' . $map['cinemaCode']);
            }
            
        }

        


        $cinemaList = M('Cinema')->field($field)->limit($limit)->where($map)->order($order)->select();
        // echo M('Cinema')->_sql();
        return $cinemaList;
    }

    /**
     * 获取影院列表
     * @param array();
     * @return array();
     * @author 宇
     */
    public function _getCinemaList($field = '*', $map = '', $limit = '', $order = ''){
    	$cinemaGroup = M('cinemaGroup')->field($field)->limit($limit)->where($map)->order($order)->find();
    	$list=explode(',', $cinemaGroup['cinemaList']);
    	foreach ($list as $val){
    		$cinema=M('cinema')->find($val);
    		$clist[$val]=$cinema['cinemaName'];
    	}
    	return $clist;
    }



    /**
    * 添加影院信息
    * @param array();
    * @return true/false
    * @author 宇
    */
    public function addCinema($data)
    {
        $mod = M('Cinema');
        if (!$mod->create($data)){
            return false;
        }else{
            if($mod->add($data)){
                clearCache('getCinemaList');
               return true;
            }else{
                return false;
            }
        }
    }


    /**
    * 更新影院信息
    * @param array();
    * @return true/false
    * @author 宇
    */
    public function setCinema($data, $map)
    {
        $mod = M('Cinema');
        if (!$mod->create($data)){
            return false;
        }else{
            $id = $mod->where($map)->data($data)->save();
            if($id){
                clearCache('getCinemaInfoBycode' . $map['cinemaCode']);
                clearCache('getCinemaList');
                return true;
            }else{
                return false;
            }
        }
    }

    /**
    * 添加影院分组
    * @param array();
    * @return true/false
    * @author 宇
    */
    public function addGroup($data)
    {
        $mod = M('CinemaGroup');
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
    * 修改影院分组
    * @param array();
    * @return true/false
    * @author 宇
    */
    public function setMemberGroup($data, $map)
    {
        $mod = M('CinemaGroup');
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


    /**
    * 添加影院分组
    * @param array();
    * @return true/false
    * @author 宇
    */
    public function addMemberGroup($data)
    {
        // print_r($data);
        $mod = M('CinemaMemberGroup');
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
    * 删除影院分组
    * @param array();
    * @return true/false
    * @author 宇
    */
    public function delMCinemaGroupById($id)
    {
        return M('CinemaGroup')->where(array('id'=>$id))->delete();
    }

    /**
    * 删除影院分组
    * @param array();
    * @return true/false
    * @author 宇
    */
    public function delCinemaMemberGroupByid($groupId)
    {
        return M('CinemaMemberGroup')->where(array('groupId'=>$groupId))->delete();
    }
    /**
    * 获取影院分组信息
    * @param array();
    * @return true/false
    * @author 宇
    */
     public function getGroupInfoById($field, $cinemaGroupId){
        $map['id'] = $cinemaGroupId;
        $cinemaGroupInfo = M('CinemaGroup')->field($field)->where($map)->find();
        echo M('CinemaGroup')->_sql();
        return $cinemaGroupInfo;
     }

     /**
    * 获取分组ID获取影院列表
    * @param array();
    * @return true/false
    * @author 宇
    */
     public function getCinemaListByCinemaGroupId($field, $cinemaGroupId){
        $map['id'] = $cinemaGroupId;
        $cinemaGroupInfo = M('CinemaGroup')->field('cinemaList')->where($map)->find();
        $cinemaListMap['cinemaCode'] = array('IN', $cinemaGroupInfo['cinemaList']);
        $getCinemaList = $this->getCinemaList($field, $cinemaListMap);
        return $getCinemaList;
     }
    /**
    * 获取影院分组
    * @param array();
    * @return true/false
    * @author 宇
    */
    public function getGroup($field = '', $map, $order = "orders desc")
    {

        $cinemaGroup = M('CinemaGroup')->field($field)->where($map)->order($order)->select();

        return $cinemaGroup;
    }

    public function getGroupInfo($field = '', $map)
    {
        $cinemaGroup = M('CinemaGroup')->field($field)->where($map)->find();
        // echo M('CinemaGroup')->_sql();
        return $cinemaGroup;

    }

    /**
    * 获取影院会员卡分组
    * @param array();
    * @return true/false
    * @author 宇
    */
    public function getMemberGroupInfoById($cinemaGroupId)
    {

        $memberGroupList = M('CinemaMemberGroup')->field($field)->where(array('cinemaGroupId' => $cinemaGroupId))->order('type desc')->select();

        return $memberGroupList;
    }


    /**
    * 更新影院会员卡分组信息
    * @param array();
    * @return true/false
    * @author 宇
    */
    public function setCinemaMemberType($data, $map)
    {

        $mod = M('CinemaMemberType');
        if (!$mod->create($data)){
            return false;
        }else{
           return $mod->where($map)->data($data)->save();;
        }
    }



    /**
    * 添加影厅信息
    * @param array();
    * @return true/false
    * @author 宇
    */
    public function aotuAddHall($data)
    {
        $mod = M('CinemaHall');
        if (!$mod->create($data)){
            return false;
        }else{
            if($mod->add($data)){
               return true;
            }else{
                $map['cinemaCode'] = $data['cinemaCode'];
                $map['hallNo'] = $data['hallNo'];
                $mod->where($map)->data($data)->save();;
                return false;
            }
        }
    }


    /**
    * 添加影厅座位信息
    * @param array();
    * @return true/false
    * @author 宇
    */
    public function aotuAddHallSite($data)
    {

        $mod = M('CinemaHallSeat');
        if (!$mod->create($data)){
            return false;
        }else{

            $id = $mod->add($data);
            // echo $mod->_sql();
            if($id){
               return true;
            }else{
                $map['cinemaCode'] = $data['cinemaCode'];
                $map['hallNo'] = $data['hallNo'];
                $map['seatCode'] = $data['seatCode'];
                M('CinemaHallSeat')->where($map)->data($data)->save();
                return false;
            }
        }
    }

    

    


    /**
    * 添加影院会员类型
    * @param array();
    * @return true/false
    * @author 宇
    */
    public function aotuAddCinemaMemberType($data)
    {

        $mod = M('CinemaMemberType');
        if (!$mod->create($data)){
            return false;
        }else{
            $id = $mod->add($data);
            if($id){
               return true;
            }else{
                $map['cinemaCode'] = $data['cinemaCode'];
                $map['memberType'] = $data['memberType'];
                $map['cinemaGroupId'] = $data['cinemaGroupId'];
                M('CinemaMemberType')->where($map)->data($data)->save();
                return false;
            }
        }
    }

    /**
    * 获取影院会员类型
    * @param array();
    * @return array();
    * @author 宇
    */
    public function getCinemaMemberType($field = '*', $map = '', $limit = '', $order = '')
    {
        $cinemaMemberTypeList = M('CinemaMemberType')->field($field)->limit($limit)->where($map)->order($order)->select();

        return $cinemaMemberTypeList;
    }
    public function getCinemaMemberTypeCount($map)
    {
        $cinemaMemberTypeCount = M('CinemaMemberType')->where($map)->count();

        return $cinemaMemberTypeCount;
    }

    public function getMemberPriceConfigInfo($field = '*', $map)
    {
        $cinemaMemberPriceInfo = M('CinemaMemberPrice')->field($field)->where($map)->find();
        // echo M('CinemaMemberPrice')->_sql();
        return $cinemaMemberPriceInfo;
    }
    
}