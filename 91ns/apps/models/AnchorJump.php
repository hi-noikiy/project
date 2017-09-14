<?php

namespace Micro\Models;

class AnchorJump extends \Phalcon\Mvc\Model
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
    public $uid;

    /**
     *
     * @var integer
     */
    public $type;

    public function getSource()
    {
        return 'pre_anchor_jump';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'uid' => 'uid',
            'type' => 'type'
        );
    }

}
