<?php

namespace Micro\Models;

//活动表
class MonitorLog extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $streamname;

    /**
     *
     * @var integer
     */
    public $inbandwidth;

    /**
     *
     * @var integer
     */
    public $Ifr;

    /**
     *
     * @var float
     */
    public $fps;

    /**
     *
     * @var string
     */
    public $deployaddress;

    /**
     *
     * @var string
     */
    public $inaddress;

    /**
     *
     * @var integer
     */
    public $bandwidth;

    /**
     *
     * @var integer
     */
    public $hists;
    
    /**
     *
     * @var integer
     */
    public $logtime;

    public function getSource() {
        return 'inv_monitor_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'streamname' => 'streamname',
            'inbandwidth' => 'inbandwidth',
            'Ifr' => 'Ifr',
            'fps' => 'fps',
            'deployaddress' => 'deployaddress',
            'inaddress' => 'inaddress',
            'bandwidth' => 'bandwidth',
            'hists' => 'hists',
            'logtime' => 'logtime'
        );
    }

}
