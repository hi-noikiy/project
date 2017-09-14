<?php

namespace Micro\Models;

class SourceGiftConfigs extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $action;

    /**
     *
     * @var string
     */
    public $utm_source;

    /**
     *
     * @var string
     */
    public $utm_medium;

    /**
     *
     * @var integer
     */
    public $giftPackageId;

    /**
     *
     * @var integer
     */
    public $status;

    public function getSource() {
        return 'pre_source_gift_configs';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'action' => 'action',
            'utm_source' => 'utm_source',
            'utm_medium' => 'utm_medium',
            'giftPackageId' => 'giftPackageId',
            'status' => 'status',
        );
    }

}
