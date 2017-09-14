<?php

namespace Micro\Models;

class AppCount extends \Phalcon\Mvc\Model
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
    public $time;
    /**
     *
     * @var integer
     */
    public $device;
    /**
     *
     * @var string
     */
    public $version;

    /**
     *
     * @var string
     */
    public $ip;

    public function getSource()
    {
        return 'pre_app_count';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'time' => 'time',
            'device' => 'device',
        	'version' => 'version',
        	'ip' => 'ip'
        );
    }

}
