<?php

namespace Micro\Models;

class ConsumeCountLog extends \Phalcon\Mvc\Model {

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
    public $sendCoin;

    /**
     *
     * @var integer
     */
    public $sendStar;

    /**
     *
     * @var integer
     */
    public $receiveCoin;

    public function getSource() {
        return 'pre_consume_count_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'uid' => 'uid',
            'sendCoin' => 'sendCoin',
            'sendStar' => 'sendStar',
            'receiveCoin' => 'receiveCoin'
        );
    }

}
