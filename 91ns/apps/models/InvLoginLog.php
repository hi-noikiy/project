<?php

namespace Micro\Models;

//客服后台--登录日志表
class InvLoginLog extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $uid;

    /**
     *
     * @var string
     */
    public $ip;

    /**
     *
     * @var string
     */
    public $loginType;
   

    /**
     *
     * @var integer
     */
    public $createTime;

   

    public function getSource() {
        return 'inv_login_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'uid' => 'uid',
            'ip' => 'ip',
            'loginType' => 'loginType',
            'createTime' => 'createTime',
        );
    }

}
