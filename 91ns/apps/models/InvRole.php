<?php

namespace Micro\Models;

//客服后台--用户权限表
class InvRole extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $uid;

    /**
     *
     * @var string
     */
    public $roleName;

    /**
     *
     * @var integer
     */
    public $roleType;

    /**
     *
     * @var string
     */
    public $roleModule;

    /**
     *
     * @var integer
     */
    public $status;

    public function getSource() {
        return 'inv_role';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'roleName' => 'roleName',
            'roleType' => 'roleType',
            'roleModule' => 'roleModule',
            'status' => 'status',
        );
    }

}
