<?php

namespace Micro\Models;

class PrivateMessage extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;
    /**
     *
     * @var integer
     */
    public $pcId;


    /**
     *
     * @var integer
     */
    public $sendUid;

    /**
     *
     * @var integer
     */
    public $toUid;

    /**
     *
     * @var integer
     */
    public $type;

    /**
     *
     * @var string
     */
    public $content;

    /**
     *
     * @var integer
     */
    public $status;
    /**
     *
     * @var integer
     */
    public $isdel;
    /**
     *
     * @var integer
     */
    public $addtime;
    public function getSource() {
        return 'pre_private_message';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'pcId' => 'pcId',
            'sendUid' => 'sendUid',
            'toUid' => 'toUid',
            'type' => 'type',
            'content' => 'content',
            'status' => 'status',
            'isdel' => 'isdel',
            'addtime' => 'addtime',
        );
    }

}
