<?php

namespace Micro\Models;

class FamilyRoom extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $familyId;

    /**
     *
     * @var integer
     */
    public $pos;

    /**
     *
     * @var integer
     */
    public $uid;

    /**
     *
     * @var integer
     */
    public $lasttime;

    public function getSource() {
        return 'pre_familyroom_config';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'familyId' => 'familyId',
            'pos' => 'pos',
            'uid' => 'uid',
            'lasttime' => 'lasttime',
        );
    }

}
