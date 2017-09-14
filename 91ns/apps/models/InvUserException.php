<?php

namespace Micro\Models;

//客服后台--用户例外表
class InvUserException extends \Phalcon\Mvc\Model {

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
     * @var integer
     */
    public $value;

    /**
     *
     * @var integer
     */
    public $type;

    public function getSource() {
        return 'inv_user_exception';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'uid' => 'uid',
            'value' => 'value',
            'type' => 'type',
        );
    }

}
