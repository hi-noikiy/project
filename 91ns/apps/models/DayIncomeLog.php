<?php

namespace Micro\Models;

class DayIncomeLog extends \Phalcon\Mvc\Model
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
     * @var float
     */
    public $money;

    /**
     *
     * @var integer
     */
    public $createTime;

    /**
     *
     * @var integer
     */
    public $type;

    /**
     *
     * @var string
     */
    public $description;

    public function getSource()
    {
        return 'pre_day_income_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'uid' => 'uid',
            'money' => 'money',
            'createTime' => 'createTime',
            'type' => 'type',
            'description' => 'description'
        );
    }

}
