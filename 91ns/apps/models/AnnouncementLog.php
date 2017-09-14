<?php

namespace Micro\Models;

class AnnouncementLog extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var string
     */
    public $startTime;

    /**
     *
     * @var integer
     */
    public $runHours;

    /**
     *
     * @var integer
     */
    public $seconds;

    public function getSource() {
        return 'pre_announcement_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'status' => 'status',
            'startTime' => 'startTime',
            'runHours' => 'runHours',
            'seconds' => 'seconds',
        );
    }

}
