<?php

namespace Micro\Models;

class PrivateMessageConfig extends \Phalcon\Mvc\Model {

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
    public $toUid;
    /**
     *
     * @var integer
     */
    public $top;
    /**
     *
     * @var string
     */
    public $shield;
    /**
     *
     * @var integer
     */
    public $lastTime;

    public function getSource() {
        return 'pre_privatemessage_config';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'uid' => 'uid',
            'toUid' => 'toUid',
            'shield' => 'shield',
            'top' => 'top',
            'lastTime' => 'lastTime',

        );
    }

}
