<?php

namespace Micro\Models;

class CarLog extends \Phalcon\Mvc\Model
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
    public $carId;

    /**
     *
     * @var integer
     */
    public $consumeLogId;

    public function getSource()
    {
        return 'pre_car_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'carId' => 'carId',
            'consumeLogId' => 'consumeLogId'
        );
    }

}
