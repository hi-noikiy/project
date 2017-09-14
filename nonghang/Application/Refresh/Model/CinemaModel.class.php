<?php

namespace Refresh\Model;
use Think\Model;

class CinemaModel extends Model {
	

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
                M('CinemaHallSeat')->where($map)->data($data)->save();;
                return false;
            }
        }
    }

    public function getMemberPriceConfigInfo($field = '*', $map)
    {
        $cinemaMemberPriceInfo = M('CinemaMemberPrice')->field($field)->where($map)->find();
        // echo M('CinemaMemberPrice')->_sql();
        return $cinemaMemberPriceInfo;
    }


     /**
    * 添加排期信息
    * @param array();
    * @return true/false
    * @author 宇
    */
    public function aotuAddCinemaPlan($data)
    {

        $mod = M('CinemaPlan');
        if (!$mod->create($data)){
            return false;
        }else{
            $id = $mod->add($data);
            // echo $mod->_sql();
            if($id){
               return $id;
            }else{

                
                if ($data['isClose'] == 1) {
                   $map['cinemaCode'] = $data['cinemaCode'];
	                $map['featureAppNo'] = $data['featureAppNo'];
	                $upData['isClose'] = 1;
	                M('CinemaPlan')->where($map)->data($upData)->save();
                }

                return false;
            }
        }
    }

/**
    * 获取影院列表
    * @param array();
    * @return array();
    * @author 宇
    */
    public function getCinemaList($field = '*', $map = '', $limit = '', $order = '')
    {

        $cacheName = md5('getCinemaList' . str_replace(array(',',' '), '_', $field) . json_encode($map) . str_replace(array(',',' '), '_', $limit) . str_replace(array(',',' '), '_', $order));
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

    
}