<?php

namespace Micro\Models;

class MonthRankLog extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $content;

    /**
     *
     * @var integer
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $month;

    /**
     *
     * @var integer
     */
    public $isGet;

    /**
     *
     * @var string
     */
    public $getTime;

    public function getSource() {
        return 'pre_month_rank_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'content' => 'content',
            'type' => 'type',
            'month' => 'month',
            'isGet' => 'isGet',
            'getTime' => 'getTime'
        );
    }

}
