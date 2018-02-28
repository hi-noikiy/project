<?php
namespace app\pokemon\model;

use think\Model;

class VsNews extends Model
{
    protected $insert  = ['writer'];

    public function vsNewsType()
    {
        return $this->hasOne('VsNewsType', 'id', 'type_id')->field('name');
    }

    public function User()
    {
        return $this->hasOne('User', 'id', 'writer')->field('name');
    }

    protected function setWriterAttr($value)
    {
        if ($value){
            return $value;
        }else{
            return cookie('uid');
        }
    }
}