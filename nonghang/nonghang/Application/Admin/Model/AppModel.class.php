<?php

namespace Admin\Model;
use Think\Model;

class AppModel extends Model {
	

    /**
    * 获取App设置列表
    * @param cinemaCode 影院编号;
    * @return array();
    * @author 宇
    */
    public function getAppAccountList($field, $map)
    {
        $userInfo = session('adminUserInfo');
         if ($userInfo['cinemaGroup'] != '-1' && !empty($userInfo) && empty($map['cinemaGroupId'])) {
            $map['cinemaGroupId'] = $userInfo['cinemaGroup'];
         }

        $appAccount = M('AppAccount')->field($field)->where($map)->select();
        return $appAccount;
    }

    /**
    * 更新App设置信息
    * @param cinemaCode 影院编号;
    * @return array();
    * @author 宇
    */
    public function updateAppAccount($data, $map)
    {
        $mod = M('AppAccount');
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
    * 增加App消息
    * @param cinemaCode 影院编号;
    * @return array();
    * @author 宇
    */
    public function addMessage($data)
    {
        $mod = M('AppMessage');
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
    * 获取App设置信息
    * @param cinemaCode 影院编号;
    * @return array();
    * @author 宇
    */
    public function getAppAccountInfo($field, $map)
    {
        $userInfo = session('adminUserInfo');
         if ($userInfo['cinemaGroup'] != '-1' && !empty($userInfo) && empty($map['cinemaGroupId'])) {
            $map['cinemaGroupId'] = $userInfo['cinemaGroup'];
         }

        $appAccount = M('AppAccount')->field($field)->where($map)->find();
        return $appAccount;
    }
    
    /**
     * 获取banner信息
     * @param unknown $map
     * @param number $start
     * @param number $limit
     * @param string $order
     * @return unknown
     */
	function getBanners($map=array(),$start=0,$limit=999999999,$order='priority desc'){
		$banners=M('appBanner')->where($map)->order($order)->limit($start,$limit)->select();
		foreach ($banners as $k=>$v){
			$app=D('appAccount')->find($v['appid']);
			$banners[$k]['appName']=$app['appName'];
			if($v['way']=='0'){
				$v['way']='选座页面';
			}elseif($v['way']=='1'){
				$v['way']='外链网页';
			}elseif($v['way']=='2'){
				$v['way']='影片详情';
			}elseif($v['way']=='3'){
				$v['way']='排期列表';
			}
			$banners[$k]['way']=$v['way'];
		}
		return $banners;
	}
	
	function countBanners($map=array()){
		$banners=M('appBanner')->where($map)->count();
		return $banners;
	}
    
}