<?php

namespace Micro\Models;


class FamilySkin extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $fid;

    /**
     *
     * @var integer
     */
    public $styleType;

    /**
     *
     * @var string
     */
    public $backgroundColor;

    /**
     *
     * @var string
     */
    public $backgroundImg;

    public function getSource() {
        return 'pre_family_skin';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'fid' => 'fid',
            'backgroundColor' => 'backgroundColor',
            'backgroundImg' => 'backgroundImg',
            'styleType' => 'styleType',
        );
    }

}
