<?php

namespace Micro\Models;

class Group extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $shortName;

    /**
     *
     * @var string
     */
    public $leaderUid;

    /**
     *
     * @var string
     */
    public $createTime;

    public function getSource() {
        return 'pre_group';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'name' => 'name',
            'shortName' => 'shortName',
            'createTime' => 'createTime',
            'leaderUid' => 'leaderUid',
        );
    }

}
