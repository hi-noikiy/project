<?php

namespace Micro\Models;

//客服后台--模块表
class InvModule extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $parentId;

    /**
     *
     * @var string
     */
    public $moduleName;

    /**
     *
     * @var string
     */
    public $moduleAction;

    /**
     *
     * @var string
     */
    public $moduleCss;

    /**
     *
     * @var integer
     */
    public $moduleSort;

    /**
     *
     * @var string
     */
    public $moduleDesc;

    /**
     *
     * @var integer
     */
    public $moduleType;

    /**
     *
     * @var integer
     */
    public $createTime;

    /**
     *
     * @var integer
     */
    public $status;

    public function getSource() {
        return 'inv_module';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'parentId' => 'parentId',
            'moduleName' => 'moduleName',
            'moduleAction' => 'moduleAction',
            'moduleCss' => 'moduleCss',
            'moduleSort' => 'moduleSort',
            'moduleDesc' => 'moduleDesc',
            'moduleType' => 'moduleType',
            'createTime' => 'createTime',
            'status' => 'status',
        );
    }

}
