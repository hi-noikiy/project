<?php

namespace Micro\Models;

//客服后台--给用户发送通知日志表
class InvNoticeLog extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $createTime;

    /**
     *
     * @var string
     */
    public $uid;

    /**
     *
     * @var string
     */
    public $operator;

    /**
     *
     * @var integer
     */
    public $content;

    public function getSource() {
        return 'inv_notice_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'createTime' => 'createTime',
            'uid' => 'uid',
            'content' => 'content',
            'createTime' => 'createTime',
            'operator' => 'operator',
        );
    }

}
