<?php

namespace Micro\Models;

class RankLog extends \Phalcon\Mvc\Model
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
    public $index;


    /**
     *
     * @var integer
     */
    public $lastTime;

    /**
     *
     * @var text
     */
    public $content;

    public function getSource()
    {
        return 'pre_rank_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'index' => 'index',
            'content' => 'content',
            'lastTime' => 'lastTime',
        );
    }

}
