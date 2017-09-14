<?php

namespace Micro\Models;

class GuardList extends \Phalcon\Mvc\Model
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
    public $guardUid;

    /**
     *
     * @var integer
     */
    public $beGuardedUid;

    /**
     *
     * @var integer
     */
    public $guardLevel;

    /**
     *
     * @var integer
     */
    public $addTime;

    /**
     *
     * @var integer
     */
    public $expireTime;

    public function getSource()
    {
        return 'pre_guard_list';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'guardUid' => 'guardUid', 
            'beGuardedUid' => 'beGuardedUid', 
            'guardLevel' => 'guardLevel', 
            'addTime' => 'addTime', 
            'expireTime' => 'expireTime'
        );
    }

}
