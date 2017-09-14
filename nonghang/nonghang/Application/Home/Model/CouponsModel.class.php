<?php

namespace Home\Model;
use Think\Model;

class CouponsModel extends Model {
    /*添加抢券产品*/
	public function addOrder($data)
    {
        $mod = M('orderCoupons');
        if (!$mod->create($data)){
            return false;
        }else{
            $orderId = $mod->add($data);
            if($orderId){
               return $orderId;
            }else{
                return false;
            }
        }
    }


    public function setCouponsInfo($map, $data)
    {

        $mod = M('orderCoupons');
        if (!$mod->create($data)){
            return false;
        }else{
            $id = $mod->where($map)->data($data)->save();
            if($id){
                return true;
            }else{
                return false;
            }
        }
    }

    /*抢券列表*/
    public function couponsList($field = '*', $map = '', $limit = '', $order = '')
    {

        $cinemaList = M('Coupons')->field($field)->limit($limit)->where($map)->order($order)->select();
        // echo  M('Coupons')->_sql();
        return $cinemaList;
    }

    public function getCouponsInfo($field, $map)
    {
        $getCouponsInfo = M('Coupons')->field($field)->where($map)->find();

        return $getCouponsInfo;
    }

    public function getCouponsOrderInfo($field, $map)
    {
        $getCouponsOrderInfo = M('orderCoupons')->field($field)->where($map)->find();
        // echo M('getCouponsInfo')->_sql();
        return $getCouponsOrderInfo;
    }

    public function getCouponsOrderList($field, $map)
    {
        $getCouponsOrderList = M('orderCoupons')->field($field)->where($map)->select();
        // echo M('getCouponsInfo')->_sql();
        return $getCouponsOrderList;
    }


    public function getCouponsInfoByCouponId($couponId)
    {
        return $this->getCouponsInfo('', array('couponId' => $couponId));
    }

    public function getSurplusSum($couponId)
    {
        $resultData = $this->getCouponsInfo('couponSum, newPrice, couponId', array('couponId'=>$couponId));
        $map['_string'] = ' (status = 0 and orderTime >=' . (time() - 600) . ') or status = 3 ';
        $map['couponId'] = $couponId;
        $orderSum = M('orderCoupons')->where($map)->sum('couponSum');
        $resultData['couponSum'] = $resultData['couponSum'] - $orderSum;
        return $resultData;

    }

    public function checkCouponsOrder($userId)
    {
        $map['userId'] = $userId;
        $map['status'] = 0;
        $map['orderTime'] = array('gt', time() - 600);
        $orderInfo = M('orderCoupons')->field('couponSum, couponOrderId,couponId, orderTime')->where($map)->find();
        return $orderInfo;
    }

    public function cancelCouponsOrder($couponOrderId)
    {
        $data['status'] = 2;
        $map['couponOrderId'] = $couponOrderId;
        $mod = M('orderCoupons');
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

}