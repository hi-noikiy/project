<?php

namespace Micro\Models;

//客服后台--机器人消息表
class InvRuleMessage extends \Phalcon\Mvc\Model {

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
   

    public function getSource() {
        return 'inv_rule_message';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'content' => 'content',
          
        );
    }

}
