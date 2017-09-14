<?php

namespace Micro\Models;

class AnchorPoster extends \Phalcon\Mvc\Model {

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
    public $imageUrl;

    /**
     *
     * @var integer
     */
    public $isShow;

    /**
     *
     * @var integer
     */
    public $createTime;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var integer
     */
    public $auditor;

    /**
     *
     * @var integer
     */
    public $auditTime;

    /**
     *
     * @var integer
     */
    public $isUsed;

  
 
    public function getSource() {
        return 'pre_anchor_poster';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'uid' => 'uid',
            'imageUrl' => 'imageUrl',
            'isShow' => 'isShow',
            'createTime' => 'createTime',
            'status' => 'status',
            'createTime' => 'createTime',
            'auditor' => 'auditor',
            'auditTime' => 'auditTime',
            'isUsed' => 'isUsed',
         );
    }

}
