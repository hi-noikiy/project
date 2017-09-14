<?php

namespace Micro\Models;

class GiftLog extends \Phalcon\Mvc\Model
{

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
    public $count;

    /**
     *
     * @var integer
     */
    public $consumeLogId;

    public function getSource()
    {
        return 'pre_gift_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'giftId' => 'giftId',
            'count' => 'count', 
            'consumeLogId' => 'consumeLogId'
        );
    }

}
