<?php

namespace Micro\Models;

class InvTmpTable extends \Phalcon\Mvc\Model
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
    public $day;

    /**
     *
     * @var integer
     */
    public $isDel;
   
   /**
     *
     * @var integer
     */
    public $isMerge;

    public function getSource()
    {
        return 'inv_tmp_table';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'uid' => 'uid', 
            'day' => 'day', 
            'isDel' => 'isDel', 
			'isMerge' => 'isMerge',
          
        );
    }

}
