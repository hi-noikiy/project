<?php

namespace Micro\Models;

//用户照片表
class UserPhoto extends \Phalcon\Mvc\Model {

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
    public $photoUrl;

    /**
     *
     * @var integer
     */
    public $type;

    public function getSource() {
        return 'pre_user_photo';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'uid' => 'uid',
            'photoUrl' => 'photoUrl',
            'type' => 'type',
        );
    }

}
