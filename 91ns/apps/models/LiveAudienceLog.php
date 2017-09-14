<?php

namespace Micro\Models;

class LiveAudienceLog extends \Phalcon\Mvc\Model
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
    public $logId;

    /**
     *
     * @var integer
     */
    public $uid;

    public function getSource()
    {
        return 'pre_live_audience_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'logId' => 'logId',
            'uid' => 'uid',
        );
    }

}
