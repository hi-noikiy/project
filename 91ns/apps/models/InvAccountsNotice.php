<?php

namespace Micro\Models;

//客服后台--结算申请通知表
class InvAccountsNotice extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $mobile;

    public function getSource() {
        return 'inv_accounts_notice';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'mobile' => 'mobile',
        );
    }

}
