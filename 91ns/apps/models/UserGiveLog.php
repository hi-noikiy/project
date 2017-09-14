<?php

namespace Micro\Models;

class UserGiveLog extends \Phalcon\Mvc\Model {

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
    public $type;

    /**
     *
     * @var integer
     */
    public $itemId;

    /**
     *
     * @var integer
     */
    public $itemTime;

    /**
     *
     * @var integer
     */
    public $receiveUid;

    /**
     *
     * @var integer
     */
    public $createTime;

    /**
     *
     * @var integer
     */
    public $consumeLogId;

    public function getSource() {
        return 'pre_user_give_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'uid' => 'uid',
            'type' => 'type',
            'itemId' => 'itemId',
            'itemTime' => 'itemTime',
            'receiveUid' => 'receiveUid',
            'createTime' => 'createTime',
            'consumeLogId' => 'consumeLogId',
        );
    }

}
