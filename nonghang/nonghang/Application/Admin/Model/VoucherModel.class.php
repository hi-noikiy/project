<?php

namespace Admin\Model;
use Think\Model;

class VoucherModel extends Model {

    /**
    * 获取票券类型列表
    * @param null;
    * @return null
    * @author 宇
    */
    public function getVoucherTypeList($field = '*', $map = '', $limit = '', $order = '')
    {
        $voucherTypeList = M('VoucherType')->field($field)->limit($limit)->where($map)->order($order)->select();
        //echo M('VoucherType')->_sql();
        return $voucherTypeList;
    }


    /**
    * 获取票券批次列表
    * @param null;
    * @return null
    * @author 宇
    */
    public function getBatchNumList($field = '*', $map = '')
    {
        $batchNumList = M('VoucherTicket')->field($field)->where($map)->group('batchNum')->select();
        // echo M('VoucherTicket')->_sql();
        return $batchNumList;
    }

    /**
    * 获取票券数量
    * @param null;
    * @return null
    * @author 宇
    */
    public function getVoucehrCount($field, $map)
    {
        $voucherTicketCount = M('VoucherTicket')->where($map)->count($field);
        return $voucherTicketCount;
    }


    /**
    * 获取票券订单数量
    * @param null;
    * @return null
    * @author 宇
    */
    public function getVoucehrOrderCount($field, $map)
    {
        $voucherTicketCount = M('VoucherOrder')->where($map)->count($field);
        return $voucherTicketCount;
    }


    /**
    * 获取票券列表
    * @param null;
    * @return null
    * @author 宇
    */

    public function getVoucherList($field = '*', $map = '', $limit = '', $order = 'addTime desc, voucherId desc')
    {
        $voucherTicket = M('VoucherTicket')->field($field)->limit($limit)->where($map)->order($order)->select();
        foreach ($voucherTicket as $k=>$v){
        	if(!empty($v['belongCinemaCode'])){
        		$cinema[$k]=D('cinema')->find($v['belongCinemaCode']);
        		$voucherTicket[$k]['belongCinemaName']=$cinema[$k]['cinemaName'];
        	}
        	if(!empty($v['cinemaCode'])){
        		$str='';
        		$cinemaCodes=explode(',', $v['cinemaCode']);
        		foreach ($cinemaCodes as $key=>$val){
        			$cinema1[$key]=D('cinema')->find($val);
        			$str.=','.$cinema1[$key]['cinemaName'];
        		}
        		$voucherTicket[$k]['cinemaName']=substr($str, 1);
        	}else{
        		$voucherTicket[$k]['cinemaName']='全部影城';
        	}
        }
        // echo M('VoucherType')->_sql();
        return $voucherTicket;
    }

    /**
    * 获取票券订单列表
    * @param null;
    * @return null
    * @author 宇
    */

    public function getVoucherOrderList($field = '*', $map = '', $limit = '', $order = '')
    {
        $voucherOrderList = M('VoucherOrder')->field($field)->limit($limit)->where($map)->order($order)->select();
        // echo M('VoucherOrder')->_sql();
        return $voucherOrderList;
    }


    /**
    * 修改票券信息
    * @param null;
    * @return null
    * @author 宇
    */
    public function setVoucherList($data, $map)
    {
        $mod = M('VoucherTicket');
        if (!$mod->create($data)){
            return false;
        }else{
            $id = $mod->where($map)->data($data)->save();
            if(is_int($id)){
               return true;
            }else{
                return false;
            }
        }
    }

    /**
    * 添加票券订单
    * @param null;
    * @return null
    * @author 宇
    */
    public function addVoucherOrder($data)
    {
        $mod = M('VoucherOrder');
        if (!$mod->create($data)){
            return false;
        }else{
            $id = $mod->add($data);
            if($id){
               return $id;
            }else{
                return false;
            }
        }
    }

    /**
    * 获取票券类型的默认排序
    * @param null;
    * @return null
    * @author 宇
    */
    public function getSortOrder($map)
    {
    	$maxSortOrder = M('VoucherType')->max('sortOrder');
    	return $maxSortOrder;
    }


    /**
    * 添加票券类型
    * @param null;
    * @return null
    * @author 宇
    */
    public function addVoucherType($data)
    {
    	$mod = M('VoucherType');
        if (!$mod->create($data)){
            return false;
        }else{
            $id = $mod->add($data);
            if($id){
               return $id;
            }else{
                return false;
            }
        }
    }

    /**
    * 修改票券类型
    * @param null;
    * @return null
    * @author 宇
    */
    public function editVoucherType($data, $map)
    {
        $mod = M('VoucherType');
        if (!$mod->create($data)){
            return false;
        }else{
            $id = $mod->where($map)->data($data)->save();
            if(is_int($id)){
               return true;
            }else{
                return false;
            }
        }
    }

    

    /**
    * 根据票券类型ID获取类型信息
    * @param null;
    * @return null
    * @author 宇
    */
    public function getVoucherTypeByTypeId($typeId)
    {
        $voucherTypeInfo = $this->getVoucherType('', array('typeId' => $typeId));
        return $voucherTypeInfo;
    }


    /**
    * 添加票券类型
    * @param null;
    * @return null
    * @author 宇
    */
    public function getVoucherType($field, $map)
    {
         $voucherTypeInfo = M('VoucherType')->field($field)->limit($limit)->where($map)->order($order)->find();
        // echo M('VoucherType')->_sql();
        return $voucherTypeInfo;
    }


    /**
    * 根据票券类型ID获取类型信息
    * @param null;
    * @return null
    * @author 宇
    */
    public function delVoucherTypeByTypeId($typeId)
    {
        $voucherTypeInfo = $this->delVoucherType(array('typeId' => $typeId));
        return $voucherTypeInfo;
    }

    /**
    * 添加票券类型
    * @param null;
    * @return null
    * @author 宇
    */
    public function delVoucherType($map)
    {
        if(M('VoucherType')->where($map)->delete()){
            return true;
        }else{
            return false;
        }
    }


    /**
    * 自动添加票券
    * @param null;
    * @return null
    * @author 宇
    */
    public function autoAddVoucher($data)
    {
        $mod = M('VoucherTicket');
        if (!$mod->create($data)){
            return false;
        }else{
            $id = $mod->add($data);
            if($id){
               return $id;
            }else{
                return false;
            }
        }
    }


    /**
    * 添加票券配置方案
    * @param null;
    * @return null
    * @author 宇
    */
    public function addVoucherSetting($data)
    {
        $mod = M('VoucherSetting');
        if (!$mod->create($data)){
            return false;
        }else{
            $id = $mod->add($data);
            if($id){
               return $id;
            }else{
                return false;
            }
        }
    }

    /**
    * 修改票券配置方案
    * @param null;
    * @return null
    * @author 宇
    */
    public function editVoucherSetting($data, $map)
    {
        $mod = M('VoucherSetting');
        if (!$mod->create($data)){
            return false;
        }else{
            $id = $mod->where($map)->data($data)->save();
            if(is_int($id)){
               return true;
            }else{
                return false;
            }
        }
    }

    /**
    * 获取票券列表
    * @param null;
    * @return null
    * @author 宇
    */

    public function getVoucherSettingList($field = '*', $map = '', $limit = '', $order = '')
    {
        $voucherSettingList = M('VoucherSetting')->field($field)->limit($limit)->where($map)->order($order)->select();
        // echo M('VoucherType')->_sql();
        return $voucherSettingList;
    }

     /**
    * 根据ID获取设置方案信息
    * @param null;
    * @return null
    * @author 宇
    */
    public function getSetingInfoById($id)
    {
        $setingInfo = M('VoucherSetting')->where(array('id' => $id))->find();
        return $setingInfo;
    }


}