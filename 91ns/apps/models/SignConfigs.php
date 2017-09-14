<?php

namespace Micro\Models;

class SignConfigs extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $type;

    /**
     *
     * @var string
     */
    public $desc;

    /**
     *
     * @var string
     */
    public $package;

    /**
     *
     * @var integer
     */
    public $daysNum;

    /**
     *
     * @var integer
     */
    public $validity;

    public function getSource() {
        return 'pre_sign_configs';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'type' => 'type',
            'desc' => 'desc',
            'package' => 'package',
            'daysNum' => 'daysNum',
            'validity' => 'validity',
        );
    }

}
