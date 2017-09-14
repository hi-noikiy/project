<?php

namespace Micro\Models;

class AppVersionConfig extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $version;

    /**
     *
     * @var string
     */
    public $updateContent;

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
     * @var float
     */
    public $size;
    /**
     *
     * @var integer
     */
    public $device;
    public function getSource() {
        return 'pre_appversion_config';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'version' => 'version',
            'updateContent' => 'updateContent',
            'addtime' => 'addtime',
            'size' => 'size',
            'status' => 'status',
            'device' => 'device'
        );
    }

}
