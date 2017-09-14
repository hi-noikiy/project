<?php

namespace Micro\Models;

class RedPackageLog extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $redPacketId;

    /**
     *
     * @var integer
     */
    public $uid;

    /**
     *
     * @var integer
     */
    public $money;

    /**
     *
     * @var integer
     */
    public $getTime;

    /**
     *
     * @var integer
     */
    public $status;

    public function getSource() {
        return 'pre_red_packet_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'uid' => 'uid',
            'redPacketId' => 'redPacketId',
            'money' => 'money',
            'getTime' => 'getTime',
            'status' => 'status',
        );
    }

}
