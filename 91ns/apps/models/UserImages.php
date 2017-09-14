<?php

namespace Micro\Models;

class UserImages extends \Phalcon\Mvc\Model
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
    public $imgUrl;

    /**
     *
     * @var string
     */
    public $imgWidth;

    /**
     *
     * @var string
     */
    public $imgHeight;

    /**
     *
     * @var string
     */
    public $type;

    /**
     *
     * @var string
     */
    public $createTime;

    /**
     *
     * @var string
     */
    public $dynamicId;

    /**
     *
     * @var string
     */
    public $status;

        /**
     *
     * @var string
     */
    public $orderType;
    
    public function getSource()
    {
        return 'pre_user_images';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'uid' => 'uid',
            'imgUrl' => 'imgUrl',
            'imgWidth' => 'imgWidth',
            'imgHeight' => 'imgHeight',
            'type' => 'type',
            'createTime' => 'createTime',
            'dynamicId' => 'dynamicId',
            'status' => 'status',
            'orderType' => 'orderType',
        );
    }

}
