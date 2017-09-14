<?php

namespace Check\Model;
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
    	$hallInfo = S('getHallList' . $cinemaCode);
    	if(empty($hallInfo)){
    		$hallInfo = M('CinemaHall')->where(array('cinemaCode' => $cinemaCode))->select();
    		S('getHallList' . $cinemaCode, $hallInfo, 3600);
    	}
    	return $hallInfo;
    }
    /**
    * 获取影院列表
    * @param array();
    * @return array();
    * @author 宇
    */
    public function getCinemaList($field = '*', $map = '', $limit = '', $order = '')
    {

        $userInfo = session('adminGoodsInfo');

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

        

        if ($field == '') {
            $field = '*';
        }

        $newField = str_replace('cinemaCode', '', $field);

        if($newField == $field && $field != '*'){
            $field = 'cinemaCode, ' . $field;
        }

        $newField = str_replace('cinemaGroupId', '', $field);
        if($newField == $field && $field != '*'){
            $field = 'cinemaGroupId, ' . $field;
        }


        $cacheName = 'getCinemaList' . str_replace(array(',',' '), '_', $field) . json_encode($map) . str_replace(array(',',' '), '_', $limit) . str_replace(array(',',' '), '_', $order);
        $cinemaList = S($cacheName);
        $newArray = '';

        if(empty($cinemaList)){
            $cinemaList = M('Cinema')->field($field)->limit($limit)->where($map)->order($order)->select();
            foreach ($cinemaList as $key => $value) {
                $memberGroup = $this->getMemberGroupInfoById($value['cinemaGroupId']);
                $value['memberGroup'] = $memberGroup;
                $newArray[$value['cinemaCode']] = $value;
            }


            unset($cinemaList);
            $cinemaList = $newArray;
            S($cacheName, $newArray, 600);
        }         
        return $cinemaList;
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
    * 获取影院分组
    * @param array();
    * @return true/false
    * @author 宇
    */
    public function getGroup($field = '', $map, $order = "orders desc")
    {
        $userInfo = session('adminGoodsInfo');
        // $move = D('ZMMove');
        // $cinemaPlan = $move->getCinemaPlan('35012401', '2015-08-17');
        // print_r($cinemaPlan);
        if ($userInfo['cinemaGroup'] != '-1' && !empty($userInfo)) {
            $map['id'] = array('IN', $userInfo['cinemaGroup']);
        }
        $cacheName = 'getGroup' . str_replace(array(',',' '), '_', $field) . json_encode($map) . str_replace(array(',',' '), '_', $limit) . str_replace(array(',',' '), '_', $order);
        
        if (empty($cinemaGroup)) {
            $newArray = '';
            if ($userInfo['cinemaGroup'] == '-1'){
                $newArray[-1] = array('id' => -1, 'groupName' => '全部影院组');
            }
            $cinemaGroup = M('CinemaGroup')->field($field)->where($map)->order($order)->select();
            if (!empty($cinemaGroup)) {
                foreach ($cinemaGroup as $key => $value) {
                    $newArray[$value['id']] = $value;
                    $newArray[$value['id'] ]['memberGroupInfo'] = $this->getMemberGroupInfoById($value['id']);
                }
                
            }

            unset($cinemaGroup);

            $cinemaGroup = $newArray;
            S($cacheName, $cinemaGroup, 10);
        }
       
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

        $memberGroupList = M('CinemaMemberGroup')->field($field)->where(array('cinemaGroupId' => $cinemaGroupId))->order($order)->select();
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
            if($mod->add($data)){
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
            if($mod->add($data)){
               return true;
            }else{
                $map['cinemaCode'] = $data['cinemaCode'];
                $map['memberType'] = $data['memberType'];
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

        $userInfo = session('adminGoodsInfo');

        if ($userInfo['cinemaGroup'] != '-1' && !empty($userInfo)) {
            if(empty($map['cinemaCode'])){
                $map['cinemaCode'] = array('IN', $userInfo['cinemaList']);
            }else{
                $map['cinemaCode'] = array('IN', $userInfo['cinemaList'] . ',' . $map['cinemaCode']);
            }
            
        }

        $newArray = '';

        $cinemaMemberTypeList = M('CinemaMemberType')->field($field)->limit($limit)->where($map)->order($order)->select();

        return $cinemaMemberTypeList;
    }
    public function getCinemaMemberTypeCount($map)
    {
        $userInfo = session('adminGoodsInfo');

        if ($userInfo['cinemaGroup'] != '-1' && !empty($userInfo)) {
            if(empty($map['cinemaCode'])){
                $map['cinemaCode'] = array('IN', $userInfo['cinemaList']);
            }else{
                $map['cinemaCode'] = array('IN', $userInfo['cinemaList'] . ',' . $map['cinemaCode']);
            }
            
        }

        $cinemaMemberTypeCount = M('CinemaMemberType')->where($map)->count();

        return $cinemaMemberTypeCount;
    }
    
}