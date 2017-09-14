<?php

namespace Interfaces\Model;
use Think\Model;

class ServiceModel extends Model {


    /**
    * 获取对应APP帐号信息
    * @param array();
    * @return array();
    * @author 宇
    */

    public function getAppAccount($field, $map)
    {
        $appAccountInfo = M('CinemaGroup')->field($field)->where($map)->order($order)->find();
        return $appAccountInfo;
    }

}