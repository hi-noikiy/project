<?php

namespace Micro\Models;

class VisitCountLog extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $date;

    /**
     *
     * @var integer
     */
    public $parentType;

    /**
     *
     * @var integer
     */
    public $subType;

    /**
     *
     * @var integer
     */
    public $visit;

    public function getSource() {
        return 'pre_visit_count_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'date' => 'date',
            'parentType' => 'parentType',
            'subType' => 'subType',
            'visit' => 'visit',
        );
    }

}
