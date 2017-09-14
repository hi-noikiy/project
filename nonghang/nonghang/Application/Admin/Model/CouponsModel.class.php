<?php

namespace Admin\Model;
use Think\Model;

class CouponsModel extends Model {
    /*添加抢券产品*/
	public function addCoupons($data)
    {
        $mod = M('Coupons');
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


    public function getCouponsListCount($map=''){
        return M('Coupons')->where($map)->count();
    }

    /*抢券列表*/
    public function couponsList($field = '*', $map = '', $limit = '', $order = '')
    {

        $cinemaList = M('Coupons')->field($field)->limit($limit)->where($map)->order($order)->select();

        return $cinemaList;
    }

    public function getCouponsInfo($field, $map)
    {
        $getCouponsInfo = M('Coupons')->field($field)->where($map)->find();

        return $getCouponsInfo;
    }

    public function getCouponsInfoByCouponId($couponId)
    {
        return $this->getCouponsInfo('', array('couponId' => $couponId));
    }

    public function setCoupons($data, $map)
    {
        $mod = M('Coupons');
        if (!$mod->create($data)){
            return false;
        }else{
            $id = $mod->where($map)->data($data)->save();
            // echo $mod->_sql();
            if($id){
                return true;
            }else{
                return false;
            }
        }
    }


    public function delCoupons($couponId)
    {
        if(M('Coupons')->where(array('couponId'=>$couponId))->delete()){
            return true;
        }else{
            return false;
        }
    }
}