<?php

namespace Micro\Models;

//活动表
class GameDeductDetailLog extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $percentage;

    /**
     *
     * @var integer
     */
    public $deductTime;

    /**
     *
     * @var integer
     */
    public $gameType;

    /**
     *
     * @var integer
     */
    public $dealerUid;

    /**
     *
     * @var integer
     */
    public $anchorUid;

    /**
     *
     * @var integer
     */
    public $createTime;

    /**
     *
     * @var integer
     */
    public $remark;

    public function getSource() {
        return 'pre_game_deduct_detail_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'percentage' => 'percentage',
            'deductTime' => 'deductTime',
            'gameType' => 'gameType',
            'dealerUid' => 'dealerUid',
            'anchorUid' => 'anchorUid',
            'createTime' => 'createTime',
            'remark' => 'remark',
        );
    }

}
