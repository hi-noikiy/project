<?php

namespace Micro\Models;

//用户通知表
class UserInformation extends \Phalcon\Mvc\Model {

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
    public $title;

    /**
     *
     * @var string
     */
    public $content;

    /**
     *
     * @var integer
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var integer
     */
    public $operType;

    /**
     *
     * @var integer
     */
    public $createTime;

    /**
     *
     * @var integer
     */
    public $link;

    public function getSource() {
        return 'pre_user_information';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'uid' => 'uid',
            'title' => 'title',
            'content' => 'content',
            'type' => 'type',
            'email' => 'email',
            'status' => 'status',
            'createTime' => 'createTime',
            'operType' => 'operType',
            'link' => 'link',
        );
    }

}
