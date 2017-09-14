<?php

namespace Micro\Models;

class Family extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $shortName;

    /**
     *
     * @var string
     */
    public $announcement;

    /**
     *
     * @var string
     */
    public $description;

    /**
     *
     * @var string
     */
    public $logo;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var integer
     */
    public $createTime;

    /**
     *
     * @var integer
     */
    public $creatorUid;

    /**
     *
     * @var string
     */
    public $address;

    /**
     *
     * @var string
     */
    public $companyName;
	
	/**
     *
     * @var integer
     */
    public $settlementDate;

    /**
     *
     * @var integer
     */
    public $isHide;

    public function getSource()
    {
        return 'pre_family';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'name' => 'name', 
            'shortName' => 'shortName',
            'announcement' => 'announcement', 
            'description' => 'description', 
            'logo' => 'logo', 
            'status' => 'status', 
            'createTime' => 'createTime',
            'creatorUid' => 'creatorUid',
            'companyName' => 'companyName',
            'address' => 'address',
            'settlementDate' => 'settlementDate',
			'isHide' => 'isHide',
        );
    }

}
