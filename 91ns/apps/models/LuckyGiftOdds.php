<?php

namespace Micro\Models;

class LuckyGiftOdds extends \Phalcon\Mvc\Model {

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
     * @var integer
     */
    public $sequence;

    /**
     *
     * @var integer
     */
    public $multiple;
 

    public function getSource() {
        return 'pre_lucky_gift_odds';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'giftId' => 'giftId',
            'sequence' => 'sequence',
            'multiple' => 'multiple',
        );
    }

}
