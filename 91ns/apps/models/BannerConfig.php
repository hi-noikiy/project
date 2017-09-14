<?php

namespace Micro\Models;

class BannerConfig extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;
    /**
     *
     * @var integer
     */
    public $btype;
    /**
     *
     * @var integer
     */
    public $time;
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
    public $status;
    /**
     *
     * @var integer
     */
    public $border;
    /**
     *
     * @var string
     */
    public $title;

    public function getSource() {
        return 'inv_banner_config';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'btype' => 'btype',
            'time' => 'time',
            'bannerurl' => 'bannerurl',
            'backgroundcolor' => 'backgroundcolor',
            'extracontent' => 'extracontent',
            'description' => 'description',
            'status' => 'status',
            'border' => 'border',
            'title' => 'title',
        );
    }

}
