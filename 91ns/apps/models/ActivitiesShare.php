<?php

namespace Micro\Models;

class ActivitiesShare extends \Phalcon\Mvc\Model
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
    public $anchorId;

    /**
     *
     * @var integer
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $createTime;

   

    public function getSource()
    {
        return 'pre_activities_share';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'uid' => 'uid', 
            'anchorId' => 'anchorId', 
            'type' => 'type', 
            'createTime' => 'createTime', 
        );
    }

}
