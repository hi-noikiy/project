<?php

namespace Micro\Models;

class UserActiveCountDay extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $platform;

    /**
     *
     * @var integer
     */
    public $date;

    /**
     *
     * @var integer
     */
    public $count;

    public function getSource() {
        return 'pre_user_active_count_day';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'platform' => 'platform',
            'date' => 'date',
            'count' => 'count',
        );
    }

}
