<?php

namespace Micro\Models;

//活动表
class ActivityAnchors extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $uid;

    /**
     *
     * @var integer
     */
    public $createTime;

    /**
     *
     * @var integer
     */
    public $type;

    public function getSource() {
        return 'pre_activity_anchors';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'uid' => 'uid',
            'createTime' => 'createTime',
            'type' => 'type',
        );
    }

}
