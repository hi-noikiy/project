<?php

namespace Micro\Models;

class RecommendLog extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $beRecUid;

    /**
     *
     * @var string
     */
    public $telephone;

    /**
     *
     * @var integer
     */
    public $recUid;

    /**
     *
     * @var string
     */
    public $createTime;

    public function getSource() {
        return 'pre_recommend_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'beRecUid' => 'beRecUid',
            'telephone' => 'telephone',
            'recUid' => 'recUid',
            'createTime' => 'createTime',
        );
    }

}
