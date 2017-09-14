<?php

namespace Micro\Models;

class BetPointsResultLog extends \Phalcon\Mvc\Model
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
    public $times;

    /**
     *
     * @var integer
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $createTime;

    /**
     *
     * @var integer
     */
    public $remark;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var integer
     */
    public $openTime;

    /**
     *
     * @var integer
     */
    public $mobile;

    public function getSource()
    {
        return 'pre_bet_points_result_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'uid' => 'uid', 
            'times' => 'times',
            'type' => 'type',
            'createTime' => 'createTime', 
            'remark' => 'remark', 
            'status' => 'status', 
            'openTime' => 'openTime',
        	'mobile' => 'mobile',
        );
    }

}
