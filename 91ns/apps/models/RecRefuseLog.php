<?php

namespace Micro\Models;

//推荐码拒绝记录表
class RecRefuseLog extends \Phalcon\Mvc\Model {

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
    public $status;

    public function getSource() {
        return 'pre_rec_refuse_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'uid' => 'uid',
            'status' => 'status',
        );
    }

}
