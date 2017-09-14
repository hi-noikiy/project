<?php

namespace Micro\Models;

class SongList extends \Phalcon\Mvc\Model {

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
    public $name;

    /**
     *
     * @var integer
     */
    public $addtime;

    /**
     *
     * @var integer
     */
    public $status;

    public function getSource() {
        return 'pre_song_list';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'uid' => 'uid',
            'name' => 'name',
            'addtime' => 'addtime',
            'status' => 'status',
        );
    }

}
