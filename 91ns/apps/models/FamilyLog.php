<?php

namespace Micro\Models;

class FamilyLog extends \Phalcon\Mvc\Model
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
    public $joinTime;

    /**
     *
     * @var integer
     */
    public $outOfTime;

    /**
     *
     * @var integer
     */
    public $familyId;

    /**
     *
     * @var integer
     */
    public $status;

    public function getSource()
    {
        return 'pre_family_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'uid' => 'uid',
            'joinTime' => 'joinTime',
            'outOfTime' => 'outOfTime',
            'familyId' => 'familyId',
            'status' => 'status',
        );
    }

}
