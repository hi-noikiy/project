<?php

namespace Micro\Models;

class ConsumeLog extends \Phalcon\Mvc\Model
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
    public $type;

    /**
     *
     * @var integer
     */
    public $anchorId;

    /**
     *
     * @var integer
     */
    public $familyId;

    /**
     *
     * @var integer
     */
    public $amount;

    /**
     *
     * @var integer
     */
    public $income;

    /**
     *
     * @var integer
     */
    public $createTime;

    /**
     *
     * @var integer
     */
    public $ratio;

    public function getSource()
    {
        return 'pre_consume_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'uid' => 'uid', 
            'type' => 'type', 
            'anchorId' => 'anchorId',
            'familyId' => 'familyId',
            'amount' => 'amount', 
            'income' => 'income',
            'createTime' => 'createTime',
            'ratio' => 'ratio'
        );
    }

}
