<?php

namespace Micro\Models;

class WeekStarInfoLog extends \Phalcon\Mvc\Model
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
    public $anchorId;

    /**
     *
     * @var integer
     */
    public $getNum;

    /**
     *
     * @var integer
     */
    public $richerId;

    /**
     *
     * @var integer
     */
    public $sendNum;

    /**
     *
     * @var integer
     */
    public $createTime;

   

    public function getSource()
    {
        return 'pre_week_star_info_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'giftId' => 'giftId',
            'anchorId' => 'anchorId',
            'getNum' => 'getNum',
            'richerId' => 'richerId',
            'sendNum' => 'sendNum',
            'createTime' => 'createTime'
        );
    }

}
