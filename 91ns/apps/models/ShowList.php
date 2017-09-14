<?php

namespace Micro\Models;

class ShowList extends \Phalcon\Mvc\Model
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
    public $showName;

    /**
     *
     * @var integer
     */
    public $showPrice;

    /**
     *
     * @var integer
     */
    public $showType;

    /**
     *
     * @var integer
     */
    public $createTime;

    /**
     *
     * @var integer
     */
    public $updateTime;

    /**
     *
     * @var integer
     */
    public $status;

   

    public function getSource()
    {
        return 'pre_show_list';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'uid' => 'uid',
            'showName' => 'showName',
            'showPrice' => 'showPrice',
            'showType' => 'showType',
            'createTime' => 'createTime',
            'updateTime' => 'updateTime',
            'status' => 'status',
        );
    }

}
