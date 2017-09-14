<?php

namespace Micro\Models;

class DayGiftsLog extends \Phalcon\Mvc\Model
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
    public $uid;

    /**
     *
     * @var integer
     */
    public $familyId;

    /**
     *
     * @var integer
     */
    public $creatorUid;

    /**
     *
     * @var float
     */
    public $allIncome;

    /**
     *
     * @var float
     */
    public $myIncome;

    /**
     *
     * @var integer
     */
    public $platRatio;

    /**
     *
     * @var float
     */
    public $divideIncome;


    /**
     *
     * @var integer
     */
    public $divideRatio;

    /**
     *
     * @var integer
     */
    public $createTime;

    /**
     *
     * @var integer
     */
    public $source;

    public function getSource()
    {
        return 'pre_day_gifts_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'uid' => 'uid',
            'familyId' => 'familyId',
            'creatorUid' => 'creatorUid',
            'allIncome' => 'allIncome',
            'myIncome' => 'myIncome',
            'platRatio' => 'platRatio',
            'divideIncome' => 'divideIncome',
            'divideRatio' => 'divideRatio',
            'createTime' => 'createTime',
            'source' => 'source'
        );
    }

}
