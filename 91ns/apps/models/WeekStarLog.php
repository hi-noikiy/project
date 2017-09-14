<?php

namespace Micro\Models;

//礼物周星日志表
class WeekStarLog extends \Phalcon\Mvc\Model {

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
     * @var text
     */
    public $thisweekInfo;

    /**
     *
     * @var text
     */
    public $lastweekInfo;

    /**
     *
     * @var integer
     */
    public $lastTime;

    public function getSource() {
        return 'pre_week_star_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'giftId' => 'giftId',
            'thisweekInfo' => 'thisweekInfo',
            'lastweekInfo' => 'lastweekInfo',
            'lastTime' => 'lastTime'
        );
    }

}
