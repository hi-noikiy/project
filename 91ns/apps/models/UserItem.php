<?php

namespace Micro\Models;

class UserItem extends \Phalcon\Mvc\Model
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
     * @var string
     */
    public $itemType;

    /**
     *
     * @var integer
     */
    public $itemId;

    /**
     *
     * @var integer
     */
    public $itemCount;

    /**
     *
     * @var integer
     */
    public $itemExpireTime;

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

    public function getSource()
    {
        return 'pre_user_item';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'uid' => 'uid', 
            'itemType' => 'itemType', 
            'itemId' => 'itemId', 
            'itemCount' => 'itemCount', 
            'itemExpireTime' => 'itemExpireTime', 
            'createTime' => 'createTime', 
            'status' => 'status'
        );
    }

}
