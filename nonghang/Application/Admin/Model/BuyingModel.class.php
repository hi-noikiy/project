<?php

namespace Admin\Model;
use Think\Model;

class BuyingModel extends Model {

    public $errorMsg = '';

    public function getBuyingList($field = '*', $map = '', $limit = '', $order = '')
    {
        $buyingList = M('CinemaBuying')->field($field)->limit($limit)->where($map)->order($order)->select();
        return $buyingList;
    }


    public function getBuyingCount($map = '')
    {
        $count = M('CinemaBuying')->where($map)->count();
        return $count;
    }


    public function getBuyingUserOrderCount($map)
    {
        $count = M('CinemaBuyingOrder')->where($map)->count();
        // echo M('CinemaBuyingOrder')->_sql();
        // echo $count;
        return $count;
    }


    function getBuyingUserOrderSum($field, $map=''){
        $orderSum = M('CinemaBuyingOrder')->where($map)->sum($field);
        return $orderSum;
    }

    public function getBuyingUserOrderList($field = '*', $map = '', $limit = '', $order = '')
    {
        $userOrderList = M('CinemaBuyingOrder')->field($field)->limit($limit)->where($map)->order($order)->select();
        // echo M('CinemaBuyingOrder')->_sql();
        return $userOrderList;
    }

    
    public function getBuyingUserOrderInfoByOrderNo($orderNo)
    {
        $buyingInfo = M('CinemaBuyingOrder')->field($field)->where(array('orderNo'=>$orderNo))->find();
        return $buyingInfo;
    }    

    public function getBuyingInfo($field, $map)
    {
        $buyingInfo = M('CinemaBuying')->field($field)->where($map)->find();
        return $buyingInfo;
    }

    public function getBuyingInfoByBuyingId($buyingId)
    {
        $buyingInfo = $this->getBuyingInfo('*', array('buyingId'=>$buyingId));
        return $buyingInfo;
    }

    public function editOrderStatus($status, $orderId)
    {
        if(M('CinemaBuyingOrder')->where(array('orderId'=>$orderId))->data(array('status'=>$status))->save()){
            return true;
        }else{
            return false;
        }
    }

    //解锁

    public function unlockSeat($orderId)
    {
        $updateMap['orderId'] = $orderId;
        $updateData['status'] = 0;
        $updateData['unLockTime'] = time();
        $saveInfo = M('CinemaBuyingSeatType')->where($updateMap)->save($updateData); 
        wlog('开始解锁'.json_encode($saveInfo) .  M('CinemaBuyingSeatType')->_sql(),'Buying');
        if ($saveInfo) {
            return true;
        }else{
            return false;
        }
    }

    public function addCinemaBuying($data)
    {
        // print_r($data);
        $rules = array(
             array('productName','require','制式不能为空'), //默认情况下用正则进行验证
             array('cinemaName','require','影院名称不能为空'), //默认情况下用正则进行验证
             array('filmName','require','影片名称不能为空'), //默认情况下用正则进行验证
             array('filmStartTime','require','场次时间不能为空'), //默认情况下用正则进行验证
             array('preView','require','影片封面图不能为空'), //默认情况下用正则进行验证
             array('seatView','require','销售座位图不能为空'), //默认情况下用正则进行验证
             array('seat','require','销售座位不能为空'), //默认情况下用正则进行验证
             array('oldPrice','require','原价不能为空'), //默认情况下用正则进行验证
             array('newPrice','require','销售价不能为空'), //默认情况下用正则进行验证
             array('newPrice','require','销售价不能为空'), //默认情况下用正则进行验证
             array('newPrice','require','销售价不能为空'), //默认情况下用正则进行验证
             array('newPrice','require','销售价不能为空'), //默认情况下用正则进行验证
             array('tickNums',array(1,2,3,4,5),'值的范围不正确！',2,'in'), //默认情况下用正则进行验证
             // array('startBuyingTime','require','开始抢购时间不能为空'), //默认情况下用正则进行验证
             // array('endBuyingTime','require','结束抢购时间不能为空'), //默认情况下用正则进行验证
             
             // array('name','','帐号名称已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一
             // array('value',array(1,2,3),'值的范围不正确！',2,'in'), // 当值不为空的时候判断是否在一个范围内
             // array('repassword','password','确认密码不正确',0,'confirm'), // 验证确认密码是否和密码一致
             // array('password','checkPwd','密码格式不正确',0,'function'), // 自定义函数验证密码格式
        );

        // print_r($data);

        $mod = M('CinemaBuying');
        if (!$mod->validate($rules)->create($data)){
        // 如果创建失败 表示验证没有通过 输出错误提示信息
            $this->errorMsg = $mod->getError();
            return false;
        }else{
            $t = $mod->add($data);
            if($t){
                $this->addBuyingSeat($t, $data['seat']);
                return true;
            }else{
                $this->errorMsg = '数据添加失败！';
                return false;
            }
        }
    }

    public function addBuyingSeat($buyingId, $seat)
    {
        $mod = M('CinemaBuyingSeat');
        $seat = json_decode($seat);
        foreach ($seat as $key => $value) {
            $seatInfo = explode(',', $value);
            $dataList[] = array('seatId' => sprintf("%020d",$buyingId).'#'.sprintf("%03d",$seatInfo[0]).'#'.sprintf("%03d",$seatInfo[1]),'buyingId'=>$buyingId, 'seatRow' => $seatInfo[0], 'seatColumn' => $seatInfo[1]);
        }
        $mod->addAll($dataList);
    }

    public function delBuyingSeat($buyingId)
    {
        $delInfo = M('CinemaBuyingSeat')->where('buyingId=' . $buyingId)->delete();
        // echo M('CinemaBuyingSeat')->_sql();
        return $delInfo;
    }


    public function delBuying($buyingId)
    {
        $delInfo = M('CinemaBuying')->where('buyingId=' . $buyingId)->delete();
        // echo M('CinemaBuyingSeat')->_sql();
        return $delInfo;
    }

    public function editCinemaBuying($data)
    {
        // print_r($data);
        $rules = array(
             array('productName','require','制式不能为空'), //默认情况下用正则进行验证
             array('cinemaName','require','影院名称不能为空'), //默认情况下用正则进行验证
             array('filmName','require','影片名称不能为空'), //默认情况下用正则进行验证
             array('filmStartTime','require','场次时间不能为空'), //默认情况下用正则进行验证
             array('preView','require','影片封面图不能为空'), //默认情况下用正则进行验证
             array('seatView','require','销售座位图不能为空'), //默认情况下用正则进行验证
             array('seat','require','销售座位不能为空'), //默认情况下用正则进行验证
             array('oldPrice','require','原价不能为空'), //默认情况下用正则进行验证
             array('newPrice','require','销售价不能为空'), //默认情况下用正则进行验证
             array('newPrice','require','销售价不能为空'), //默认情况下用正则进行验证
             array('newPrice','require','销售价不能为空'), //默认情况下用正则进行验证
             array('newPrice','require','销售价不能为空'), //默认情况下用正则进行验证
             array('tickNums',array(1,2,3,4,5),'值的范围不正确！',2,'in'), //默认情况下用正则进行验证
             // array('startBuyingTime','require','开始抢购时间不能为空'), //默认情况下用正则进行验证
             // array('endBuyingTime','require','结束抢购时间不能为空'), //默认情况下用正则进行验证
             
             // array('name','','帐号名称已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一
             // array('value',array(1,2,3),'值的范围不正确！',2,'in'), // 当值不为空的时候判断是否在一个范围内
             // array('repassword','password','确认密码不正确',0,'confirm'), // 验证确认密码是否和密码一致
             // array('password','checkPwd','密码格式不正确',0,'function'), // 自定义函数验证密码格式
        );

        // print_r($data);

        $mod = M('CinemaBuying');
        if (!$mod->validate($rules)->create($data)){
        // 如果创建失败 表示验证没有通过 输出错误提示信息
            $this->errorMsg = $mod->getError();
            return false;
        }else{
            $t = $mod->save($data);
            if($t){
                $this->delBuyingSeat($data['buyingId']);
                $this->addBuyingSeat($data['buyingId'], $data['seat']);
                return true;
            }else{
                $this->errorMsg = '数据更新失败！';
                return false;
            }
        }
    }
}