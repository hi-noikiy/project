<?php

namespace Micro\Models;

class EventConfig extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;
    /**
     *
     * @var integer
     */
    public $etype;
    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $bannerurl;

    /**
     *
     * @var string
     */
    public $backgroundcolor;

    /**
     *
     * @var string
     */
    public $extracontent;

    /**
     *
     * @var string
     */
    public $description;


    /**
     *
     * @var integer
     */
    public $eventstarttime;


    /**
     *
     * @var integer
     */
    public $eventendtime;


    /**
     *
     * @var integer
     */
    public $addtime;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var integer
     */
    public $eorder;

    public function getSource() {
        return 'pre_event_config';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'title' => 'title',
            'etype' => 'etype',
            'bannerurl' => 'bannerurl',
            'backgroundcolor' => 'backgroundcolor',
            'extracontent' => 'extracontent',
            'description' => 'description',
            'status' => 'status',
            'eventstarttime' => 'eventstarttime',
            'eventendtime' => 'eventendtime',
            'addtime' => 'addtime',
            'eorder' => 'eorder',
        );
    }

}
