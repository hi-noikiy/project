<?php

namespace Micro\Models;

//客服后台--用户表
class InvUser extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $uid;

    /**
     *
     * @var string
     */
    public $userName;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var string
     */
    public $picture;

    /**
     *
     * @var integer
     */
    public $roleId;

    /**
     *
     * @var integer
     */
    public $createTime;

    /**
     *
     * @var integer
     */
    public $status;

    public function getSource() {
        return 'inv_user';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'uid' => 'uid',
            'userName' => 'userName',
            'password' => 'password',
            'picture' => 'picture',
            'roleId' => 'roleId',
            'createTime' => 'createTime',
            'status' => 'status',
        );
    }

}
