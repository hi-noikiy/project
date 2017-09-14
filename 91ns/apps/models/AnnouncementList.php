<?php

namespace Micro\Models;

class AnnouncementList extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $content;

    /**
     *
     * @var string
     */
    public $url;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var integer
     */
    public $addtime;

    /**
     *
     * @var integer
     */
    public $runNum;

    public function getSource() {
        return 'pre_announcement_list';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'content' => 'content',
            'url' => 'url',
            'status' => 'status',
            'addtime' => 'addtime',
            'runNum' => 'runNum',
        );
    }

}
