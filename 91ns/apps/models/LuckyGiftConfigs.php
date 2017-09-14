<?php

namespace Micro\Models;

class LuckyGiftConfigs extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $giftId;

    /**
     *
     * @var string
     */
    public $multiple;

    /**
     *
     * @var integer
     */
    public $limit;

    /**
     *
     * @var string
     */
    public $count;

    /**
     *
     * @var string
     */
    public $pointer;

    public function getSource() {
        return 'pre_lucky_gift_configs';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'giftId' => 'giftId',
            'multiple' => 'multiple',
            'limit' => 'limit',
            'count' => 'count',
            'pointer' => 'pointer',
        );
    }

}
