<?php

namespace Micro\Models;

class DeviceInfo extends \Phalcon\Mvc\Model
{
    /**
     * @var integer
     */
    public $id;
    /**
     * @var integer
     */
    public $platform;
    /**
     *
     * @var integer
     */
    public $uid;

    /**
     *
     * @var string
     */
    public $deviceid;

    /**
     *
     * @var string
     */
    public $devicetoken;

    /**
     * @return string
     */
    public $clientID;

    /**
     * @var integer
     */
    public $lasttime;

    /**
     * @var integer
     */
    public $pushTime;

    /**
     * @var integer
     */
    public $pushUid;

    public function getSource()
    {
        return 'pre_device_info';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'platform' => 'platform',
            'uid' => 'uid',
            'deviceid' => 'deviceid',
            'devicetoken' => 'devicetoken',
            'clientID' => 'clientID',
            'lasttime' => 'lasttime',
            'pushTime' => 'pushTime',
            'pushUid' => 'pushUid'
        );
    }

}
