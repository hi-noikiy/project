<?php

namespace Admin\Model;
use Think\Model;

class PlanModel extends Model {

    
    public function delPlanByFeatureAppNo($featureAppNo)
    {
        return M('CinemaPlan')->where(array('featureAppNo' => $featureAppNo))->delete();
    }

    /**
    * 获取排期信息
    * @param array();
    * @return array();
    * @author 宇
    */
    public function getPlanInfo($field = '*', $map = '')
    {

        $userInfo = session('adminUserInfo');

        if ($userInfo['cinemaGroup'] != '-1' && $userInfo['cinemaCodeList'] !=-1) {
            if(empty($map['cinemaCode'])){
                $map['cinemaCode'] = array('IN', $userInfo['cinemaList']);
            }else{
                $map['cinemaCode'] = array('IN', $userInfo['cinemaList'] . ',' . $map['cinemaCode']);
            }
        }

        $planInfo = M('CinemaPlan')->field($field)->where($map)->find();
        // echo M('CinemaPlan')->_sql();
        return $planInfo;
    }

    /**
    * 获取排期列表
    * @param array();
    * @return array();
    * @author 宇
    */
    public function getPlanList($field = '*', $map = '', $limit = '', $order = '')
    {

        $userInfo = session('adminUserInfo');


        if ($userInfo['cinemaGroup'] != '-1') {
            if(empty($map['cinemaCode'])){
                $map['cinemaCode'] = array('IN', $userInfo['cinemaList']);
            }else{
                $map['cinemaCode'] = array('IN', $userInfo['cinemaList'] . ',' . $map['cinemaCode']);
            }
        }

        $planList = M('CinemaPlan')->field($field)->limit($limit)->where($map)->order($order)->select();
        // echo M('CinemaPlan')->_sql();
        return $planList;
    }


    /**
    * 获取排期列表
    * @param array();
    * @return array();
    * @author 宇
    */
    public function getPlanListGroupByFilmNo($field = '*', $map = '', $limit = '', $order = '')
    {

        $userInfo = session('adminUserInfo');

        if ($userInfo['cinemaGroup'] != '-1' && $userInfo['cinemaCodeList'] !=-1) {
            if(empty($map['cinemaCode'])){
                $map['cinemaCode'] = array('IN', $userInfo['cinemaList']);
            }else{
                $map['cinemaCode'] = array('IN', $userInfo['cinemaList'] . ',' . $map['cinemaCode']);
            }
        }

        $planList = M('CinemaPlan')->field($field)->limit($limit)->where($map)->group('filmNo')->order($order)->select();
        // echo M('CinemaPlan')->_sql();
        return $planList;
    }


    function count($map=''){

        $userInfo = session('adminUserInfo');

        if ($userInfo['cinemaGroup'] != '-1' && $userInfo['cinemaCodeList'] !=-1) {
            if(empty($map['cinemaCode'])){
                $map['cinemaCode'] = array('IN', $userInfo['cinemaList']);
            }else{
                $map['cinemaCode'] = array('IN', $userInfo['cinemaList'] . ',' . $map['cinemaCode']);
            }
        }
        
        $count = M('CinemaPlan')->where($map)->count();
        return $count;
    }


    /**
    * 添加排期信息
    * @param array();
    * @return true/false
    * @author 宇
    */
    public function aotuAddCinemaPlan($data)
    {
        // print_r($data);

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
    * 更新排期信息
    * @param array();
    * @return true/false
    * @author 宇
    */
    public function setCinemaPlan($data, $map)
    {

        $mod = M('CinemaPlan');
        if (!$mod->create($data)){
            return false;
        }else{
            $id = $mod->where($map)->data($data)->save();
            // echo $mod->_sql();
            if($id){
               return $id;
            }else{
                return false;
            }
        }
    }
    /**
     * 获取排期期间影片列表
     * 
     */
    public function getPlanFilms(){
    	$map['startTime']= array('egt',strtotime(date('Ymd',time())));
    	return M('CinemaPlan')->field('filmNo,filmName,count(filmName),copyType')->where($map)->group('filmNo')->select();
    }
}