<?php

namespace Micro\Models;

class GuardLog extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $guardType;

    /**
     *
     * @var integer
     */
    public $consumeLogId;

    public function getSource()
    {
        return 'pre_guard_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'guardType' => 'guardType',
            'consumeLogId' => 'consumeLogId'
        );
    }

}
